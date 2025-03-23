<?php
// app/Services/MovieService.php
namespace App\Services;

use App\Repositories\MovieRepositoryInterface;

class MovieService
{
    protected $movieRepository;

    public function __construct(MovieRepositoryInterface $movieRepository)
    {
        $this->movieRepository = $movieRepository;
    }

    public function getAllMovies()
    {
        return $this->movieRepository->getAll();
    }

    public function getMovieById($id)
    {
        return $this->movieRepository->findById($id);
    }

    public function createMovie(array $data)
    {
        return $this->movieRepository->create($data);
    }

    public function updateMovie($id, array $data)
    {
        return $this->movieRepository->update($id, $data);
    }

    public function deleteMovie($id)
    {
        return $this->movieRepository->delete($id);
    }
}
