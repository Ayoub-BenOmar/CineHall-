<?php
namespace App\Repositories;

use App\Models\Hall;
use App\Repositories\HallRepositoryInterface;

class HallRepository implements HallRepositoryInterface
{
    protected $hall;

    public function __construct(Hall $hall)
    {
        $this->hall = $hall;
    }

    public function getAll()
    {
        return $this->hall->all();
    }

    public function findById($id)
    {
        return $this->hall->find($id);
    }

    public function create(array $data)
    {
        return $this->hall->create($data);
    }

    public function update($id, array $data)
    {
        $hall = $this->hall->find($id);
        $hall->update($data);
        return $hall;
    }

    public function delete($id)
    {
        return $this->hall->destroy($id);
    }
}
