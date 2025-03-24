<?php
namespace App\Repositories;

interface SeatRepositoryInterface
{
    public function getAll();
    public function findById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function getAvailableSeats($hallId);
    public function reserveSeats(array $seatIds);
}
