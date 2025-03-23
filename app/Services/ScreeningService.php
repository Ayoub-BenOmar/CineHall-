<?php
// app/Services/ScreeningService.php
namespace App\Services;

use App\Repositories\ScreeningRepositoryInterface;

class ScreeningService
{
    protected $screeningRepository;

    public function __construct(ScreeningRepositoryInterface $screeningRepository)
    {
        $this->screeningRepository = $screeningRepository;
    }

    public function getAllScreenings()
    {
        return $this->screeningRepository->getAll();
    }

    public function getScreeningById($id)
    {
        return $this->screeningRepository->findById($id);
    }

    public function createScreening(array $data)
    {
        return $this->screeningRepository->create($data);
    }

    public function updateScreening($id, array $data)
    {
        return $this->screeningRepository->update($id, $data);
    }

    public function deleteScreening($id)
    {
        return $this->screeningRepository->delete($id);
    }

    public function filterScreeningsByType($type)
    {
        return $this->screeningRepository->filterByType($type);
    }
}
