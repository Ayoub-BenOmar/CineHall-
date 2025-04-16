<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PaymentService;
use App\Models\Reservation;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function createPaymentIntent(Request $request)
    {
        $request->validate([
            'seat_ids' => 'required|array',
            'amount' => 'required|numeric|min:0',
            'screening_id' => 'required|exists:screenings,id'
        ]);

        try {
            $clientSecret = $this->paymentService->createPaymentIntent(
                $request->seat_ids,
                $request->amount,
                auth()->id()
            );

            // Create pending reservations
            foreach ($request->seat_ids as $seatId) {
                Reservation::create([
                    'user_id' => auth()->id(),
                    'seat_id' => $seatId,
                    'screening_id' => $request->screening_id,
                    'status' => 'pending'
                ]);
            }

            return response()->json(['clientSecret' => $clientSecret]);

        } catch (\Exception $e) {
            Log::error('Payment intent creation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Payment processing failed'], 500);
        }
    }

    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');

        try {
            $event = $this->paymentService->verifyWebhookSignature($payload, $signature);

            if ($event->type === 'payment_intent.succeeded') {
                $this->paymentService->handleSuccessfulPayment($event->data->object->id);
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Webhook handling failed: ' . $e->getMessage());
            return response()->json(['error' => 'Webhook handling failed'], 400);
        }
    }

    public function getPaymentStatus(Request $request)
    {
        $request->validate([
            'payment_intent_id' => 'required|string'
        ]);

        try {
            $reservation = Reservation::where('payment_intent_id', $request->payment_intent_id)
                ->firstOrFail();

            return response()->json([
                'status' => $reservation->status,
                'reservation_id' => $reservation->id
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Reservation not found'], 404);
        }
    }
}
