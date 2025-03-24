<?php
namespace App\Services;

use App\Repositories\HallRepositoryInterface;

class HallService
{
    protected $hallRepository;

    public function __construct(HallRepositoryInterface $hallRepository)
    {
        $this->hallRepository = $hallRepository;
    }

    public function getAllHalls()
    {
        return $this->hallRepository->getAll();
    }

    public function getHallById($id)
    {
        return $this->hallRepository->findById($id);
    }

    public function createHall(array $data)
    {
        return $this->hallRepository->create($data);
    }

    public function updateHall($id, array $data)
    {
        return $this->hallRepository->update($id, $data);
    }

    public function deleteHall($id)
    {
        return $this->hallRepository->delete($id);
    }
}
