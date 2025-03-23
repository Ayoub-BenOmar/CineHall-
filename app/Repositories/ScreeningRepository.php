<?php
// app/Repositories/ScreeningRepository.php
namespace App\Repositories;

use App\Models\Screening;
use App\Repositories\ScreeningRepositoryInterface;

class ScreeningRepository implements ScreeningRepositoryInterface
{
    protected $screening;

    public function __construct(Screening $screening)
    {
        $this->screening = $screening;
    }

    public function getAll()
    {
        return $this->screening->with(['movie', 'hall'])->get();
    }

    public function findById($id)
    {
        return $this->screening->with(['movie', 'hall'])->find($id);
    }

    public function create(array $data)
    {
        return $this->screening->create($data);
    }

    public function update($id, array $data)
    {
        $screening = $this->screening->find($id);
        $screening->update($data);
        return $screening;
    }

    public function delete($id)
    {
        return $this->screening->destroy($id);
    }

    public function filterByType($type)
    {
        return $this->screening->where('type', $type)->with(['movie', 'hall'])->get();
    }
}
