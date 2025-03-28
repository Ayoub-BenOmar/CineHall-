<?php
// app/Services/ReservationService.php
namespace App\Services;

use App\Models\Seat;
use App\Repositories\SeatRepositoryInterface;
use App\Repositories\ReservationRepositoryInterface;
use App\Repositories\ScreeningRepositoryInterface;

class ReservationService
{
    protected $reservationRepo;
    protected $seatRepo;
    protected $screeningRepo;

    public function __construct(
        ReservationRepositoryInterface $reservationRepo,
        SeatRepositoryInterface $seatRepo,
        ScreeningRepositoryInterface $screeningRepo,
    ) {
        $this->reservationRepo = $reservationRepo;
        $this->seatRepo = $seatRepo;
        $this->screeningRepo = $screeningRepo;
    }

    public function createReservation(array $data)
    {
        if (!isset($data['seat_id'])) {
            throw new \InvalidArgumentException("Seat ID must be provided");
        }
    
        $seat = $this->seatRepo->findById($data['seat_id']);
        if (!$seat) {
            throw new \Exception("Seat {$data['seat_id']} does not exist");
        }
        if ($seat->is_reserved) {
            throw new \Exception("Seat {$seat->seat_number} is already reserved");
        }

        $screening = $this->screeningRepo->findById($data['screening_id']);
        if (!$screening) {
            throw new \Exception("Screening not found");
        }
    
        $screening = $this->screeningRepo->findById($data['screening_id']);
        if ($screening->type === 'VIP') {
            $adjacentSeat = $this->findAdjacentSeat($seat);
            if (!$adjacentSeat || $adjacentSeat->is_reserved) {
                throw new \Exception("VIP screenings require an available adjacent seat");
            }
    
            $reservation = $this->reservationRepo->create([
                'user_id' => $data['user_id'],
                'screening_id' => $data['screening_id'],
                'seat_id' => $seat->id,
                'status' => 'pending',
                'expires_at' => now()->addMinutes(15)
            ]);
    
            $this->reservationRepo->create([
                'user_id' => $data['user_id'],
                'screening_id' => $data['screening_id'],
                'seat_id' => $adjacentSeat->id,
                'status' => 'pending',
                'expires_at' => now()->addMinutes(15),
            ]);
    
            $this->seatRepo->reserveSeats([$seat->id, $adjacentSeat->id]);
    
            return $reservation;
        }
    
        $reservation = $this->reservationRepo->create([
            'user_id' => $data['user_id'],
            'screening_id' => $data['screening_id'],
            'seat_id' => $data['seat_id'],
            'status' => 'pending',
            'expires_at' => now()->addMinutes(15)
        ]);
    
        $this->seatRepo->reserveSeats([$data['seat_id']]);
    
        return $reservation;
    }
    
    protected function findAdjacentSeat(Seat $seat)
    {
        preg_match('/^([A-Za-z]+)(\d+)$/', $seat->seat_number, $matches);
        $row = $matches[1];
        $number = (int)$matches[2];
    
        $nextSeatNumber = $row . ($number + 1);
        $nextSeat = Seat::where('seat_number', $nextSeatNumber)
                      ->where('hall_id', $seat->hall_id)
                      ->first();
    
        if ($nextSeat && !$nextSeat->is_reserved) {
            return $nextSeat;
        }
    
        // Check previous 
        if ($number > 1) {
            $prevSeatNumber = $row . ($number - 1);
            $prevSeat = Seat::where('seat_number', $prevSeatNumber)
                          ->where('hall_id', $seat->hall_id)
                          ->first();
    
            if ($prevSeat && !$prevSeat->is_reserved) {
                return $prevSeat;
            }
        }
    
        return null;
    }

    public function cancelReservation($id)
    {
        $reservation = $this->reservationRepo->findById($id);

        if ($reservation->status === "cancelled") {
            throw new \Exception("This reservation is already canceled");
        }

        Seat::where('id', $reservation->seat_id)->update(['is_reserved' => false]);

        // Release seats
        // $this->seatRepo->updateWhereIn(
        //     $reservation->seat_ids,
        //     ['is_reserved' => false]
        // );

        return $this->reservationRepo->cancel($id);
    }

    public function confirmPayment($reservationId, $paymentData)
    {
        $this->reservationRepo->update($reservationId, ['status' => 'confirmed']);
        // Create payment record...
    }
}
