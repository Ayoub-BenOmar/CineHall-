<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PaymentService;

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
            'amount' => 'required|numeric',
        ]);

        try {
            $clientSecret = $this->paymentService->createPaymentIntent(
                $request->seat_ids,
                $request->amount,
                auth()->id()
            );

            return response()->json(['clientSecret' => $clientSecret]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function handleWebhook(Request $request)
    {
        // Verify Stripe signature (as shown earlier)
        $this->paymentService->handleSuccessfulPayment($event->data->object->id);
        return response()->json(['status' => 'success']);
    }
}
