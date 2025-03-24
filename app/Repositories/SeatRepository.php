<?php
namespace App\Repositories;

use App\Models\Seat;
use App\Repositories\SeatRepositoryInterface;

class SeatRepository implements SeatRepositoryInterface
{
    protected $seat;

    public function __construct(Seat $seat)
    {
        $this->seat = $seat;
    }

    public function getAll()
    {
        return $this->seat->all();
    }

    public function findById($id)
    {
        return $this->seat->find($id);
    }

    public function create(array $data)
    {
        return $this->seat->create($data);
    }

    public function update($id, array $data)
    {
        $seat = $this->seat->find($id);
        $seat->update($data);
        return $seat;
    }

    public function delete($id)
    {
        return $this->seat->destroy($id);
    }

    public function getAvailableSeats($hallId)
    {
        return $this->seat->where('hall_id', $hallId)->where('is_reserved', false)->get();
    }

    public function reserveSeats(array $seatIds)
    {
        return $this->seat->whereIn('id', $seatIds)->update(['is_reserved' => true]);
    }
}
