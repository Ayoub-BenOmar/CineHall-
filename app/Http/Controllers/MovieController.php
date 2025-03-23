<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MovieService;

class MovieController extends Controller
{
    protected $movieService;

    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }

    public function index()
    {
        $movies = $this->movieService->getAllMovies();
        return response()->json($movies);
    }

    public function show($id)
    {
        $movie = $this->movieService->getMovieById($id);
        return response()->json($movie);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $movie = $this->movieService->createMovie($data);
        return response()->json($movie, 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $movie = $this->movieService->updateMovie($id, $data);
        return response()->json($movie);
    }

    public function destroy($id)
    {
        $this->movieService->deleteMovie($id);
        return response()->json(null, 204);
    }
}
