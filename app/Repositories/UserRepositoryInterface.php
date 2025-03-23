<?php
// app/Repositories/UserRepositoryInterface.php
namespace App\Repositories;

interface UserRepositoryInterface
{
    public function create(array $data);
    public function findByEmail(string $email);
    public function update(int $id, array $data);
    public function delete(int $id);
}
