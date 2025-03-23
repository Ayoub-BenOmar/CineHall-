<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ScreeningService;

class ScreeningController extends Controller
{
    protected $screeningService;

    public function __construct(ScreeningService $screeningService)
    {
        $this->screeningService = $screeningService;
    }

    public function index()
    {
        $screenings = $this->screeningService->getAllScreenings();
        return response()->json($screenings);
    }

    public function show($id)
    {
        $screening = $this->screeningService->getScreeningById($id);
        return response()->json($screening);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $screening = $this->screeningService->createScreening($data);
        return response()->json($screening, 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $screening = $this->screeningService->updateScreening($id, $data);
        return response()->json($screening);
    }

    public function destroy($id)
    {
        $this->screeningService->deleteScreening($id);
        return response()->json(null, 204);
    }

    public function filterByType($type)
    {
        $screenings = $this->screeningService->filterScreeningsByType($type);
        return response()->json($screenings);
    }
}
