<?php
// app/Repositories/ReservationRepositoryInterface.php
namespace App\Repositories;

interface ReservationRepositoryInterface
{
    public function create(array $data);
    public function findById($id);
    public function update($id, array $data);
    public function cancel($id);
    public function getExpiredReservations();
}
