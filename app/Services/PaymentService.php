<?php
// app/Services/PaymentService.php
namespace App\Services;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Repositories\Contracts\BookingRepositoryInterface;

class PaymentService
{
    protected $bookingRepo;

    public function __construct(BookingRepositoryInterface $bookingRepo)
    {
        $this->bookingRepo = $bookingRepo;
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function createPaymentIntent(array $seatIds, float $amount, int $userId)
    {
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount * 100,
                'currency' => 'usd',
                'metadata' => [
                    'seat_ids' => json_encode($seatIds),
                    'user_id' => $userId
                ],
            ]);

            return $paymentIntent->client_secret;

        } catch (\Exception $e) {
            throw new \Exception("Stripe Error: " . $e->getMessage());
        }
    }

    public function handleSuccessfulPayment(string $paymentIntentId)
    {
        $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
        $seatIds = json_decode($paymentIntent->metadata->seat_ids);

        $this->bookingRepo->updateBookingStatus($seatIds, 'confirmed');
    }
}