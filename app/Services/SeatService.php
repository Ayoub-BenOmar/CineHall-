<?php
namespace App\Services;

use App\Repositories\SeatRepositoryInterface;

class SeatService
{
    protected $seatRepository;

    public function __construct(SeatRepositoryInterface $seatRepository)
    {
        $this->seatRepository = $seatRepository;
    }

    public function getAllSeats()
    {
        return $this->seatRepository->getAll();
    }

    public function getSeatById($id)
    {
        return $this->seatRepository->findById($id);
    }

    public function createSeat(array $data)
    {
        return $this->seatRepository->create($data);
    }

    public function updateSeat($id, array $data)
    {
        return $this->seatRepository->update($id, $data);
    }

    public function deleteSeat($id)
    {
        return $this->seatRepository->delete($id);
    }

    public function getAvailableSeats($hallId)
    {
        return $this->seatRepository->getAvailableSeats($hallId);
    }

    public function reserveSeats(array $seatIds)
    {
        return $this->seatRepository->reserveSeats($seatIds);
    }
}
