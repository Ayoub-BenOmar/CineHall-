<?php
namespace App\Repositories;

use App\Models\Movie;
use App\Repositories\MovieRepositoryInterface;

class MovieRepository implements MovieRepositoryInterface
{
    protected $movie;

    public function __construct(Movie $movie)
    {
        $this->movie = $movie;
    }

    public function getAll()
    {
        return $this->movie->all();
    }

    public function findById($id)
    {
        return $this->movie->find($id);
    }

    public function create(array $data)
    {
        return $this->movie->create($data);
    }

    public function update($id, array $data)
    {
        $movie = $this->movie->find($id);
        $movie->update($data);
        return $movie;
    }

    public function delete($id)
    {
        return $this->movie->destroy($id);
    }
}