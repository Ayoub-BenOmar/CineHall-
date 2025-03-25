<?php
// app/Repositories/ReservationRepository.php
namespace App\Repositories;

use App\Models\Reservation;
use App\Repositories\ReservationRepositoryInterface;
use Carbon\Carbon;

class ReservationRepository implements ReservationRepositoryInterface
{
    protected $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function create(array $data)
    {
        $data['expires_at'] = Carbon::now()->addMinutes(15);
        return $this->reservation->create($data);
    }

    public function findById($id)
    {
        return $this->reservation->find($id);
    }

    public function update($id, array $data)
    {
        $reservation = $this->reservation->find($id);
        $reservation->update($data);
        return $reservation;
    }

    public function cancel($id)
    {
        return $this->update($id, ['status' => 'cancelled']);
    }

    public function getExpiredReservations()
    {
        return $this->reservation
            ->where('status', 'pending')
            ->where('expires_at', '<', Carbon::now())
            ->get();
    }
}
