<?php
// app/Services/ReservationService.php
namespace App\Services;

use App\Repositories\ReservationRepositoryInterface;
use App\Repositories\SeatRepositoryInterface;

class ReservationService
{
    protected $reservationRepo;
    protected $seatRepo;

    public function __construct(
        ReservationRepositoryInterface $reservationRepo,
        SeatRepositoryInterface $seatRepo
    ) {
        $this->reservationRepo = $reservationRepo;
        $this->seatRepo = $seatRepo;
    }

    public function createReservation(array $data)
    {
        // Check seat availability
        foreach ($data['seat_ids'] as $seatId) {
            if ($this->seatRepo->findById($seatId)->is_reserved) {
                throw new \Exception("Seat $seatId is already reserved");
            }
        }

        // Create reservation
        $reservation = $this->reservationRepo->create($data);

        // Reserve seats
        $this->seatRepo->reserveSeats($data['seat_ids']);

        return $reservation;
    }

    public function cancelReservation($id)
    {
        $reservation = $this->reservationRepo->findById($id);

        // Release seats
        $this->seatRepo->updateWhereIn(
            $reservation->seat_ids,
            ['is_reserved' => false]
        );

        return $this->reservationRepo->cancel($id);
    }

    public function confirmPayment($reservationId, $paymentData)
    {
        $this->reservationRepo->update($reservationId, ['status' => 'confirmed']);
        // Create payment record...
    }
}
