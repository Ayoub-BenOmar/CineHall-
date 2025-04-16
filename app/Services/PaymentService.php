<?php
// app/Services/PaymentService.php
namespace App\Services;

use Stripe\Stripe;
use TicketService;
use Stripe\Webhook;
use App\Models\Seat;
use Stripe\PaymentIntent;
use App\Models\Reservation;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\ApiErrorException;

class PaymentService
{
    protected $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createPaymentIntent(array $seatIds, float $amount, int $userId)
    {
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount * 100, // Convert to cents
                'currency' => 'usd',
                'metadata' => [
                    'seat_ids' => json_encode($seatIds),
                    'user_id' => $userId
                ],
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            return $paymentIntent->client_secret;
        } catch (ApiErrorException $e) {
            Log::error('Stripe payment intent creation failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function handleSuccessfulPayment(string $paymentIntentId)
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
            
            if ($paymentIntent->status === 'succeeded') {
                $metadata = $paymentIntent->metadata;
                $seatIds = json_decode($metadata->seat_ids);
                $userId = $metadata->user_id;

                // Create reservation for each seat
                foreach ($seatIds as $seatId) {
                    $reservation = Reservation::create([
                        'user_id' => $userId,
                        'seat_id' => $seatId,
                        'payment_intent_id' => $paymentIntentId,
                        'status' => 'confirmed'
                    ]);

                    // Mark seat as reserved
                    Seat::where('id', $seatId)->update(['is_reserved' => true]);

                    // Generate ticket
                    $this->ticketService->generateTicket($reservation);
                }

                return true;
            }

            return false;
        } catch (ApiErrorException $e) {
            Log::error('Stripe payment handling failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function verifyWebhookSignature($payload, $signature)
    {
        try {
            $event = Webhook::constructEvent(
                $payload,
                $signature,
                config('services.stripe.webhook_secret')
            );
            return $event;
        } catch (\Exception $e) {
            Log::error('Stripe webhook verification failed: ' . $e->getMessage());
            throw $e;
        }
    }
}