<?php
// app/Repositories/HallRepositoryInterface.php
namespace App\Repositories;

interface HallRepositoryInterface
{
    public function getAll();
    public function findById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
