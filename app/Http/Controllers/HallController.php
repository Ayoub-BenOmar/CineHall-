<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\HallService;

class HallController extends Controller
{
    protected $hallService;

    public function __construct(HallService $hallService)
    {
        $this->hallService = $hallService;
    }

    public function index()
    {
        $halls = $this->hallService->getAllHalls();
        return response()->json($halls);
    }

    public function show($id)
    {
        $hall = $this->hallService->getHallById($id);
        return response()->json($hall);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $hall = $this->hallService->createHall($data);
        return response()->json($hall, 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $hall = $this->hallService->updateHall($id, $data);
        return response()->json($hall);
    }

    public function destroy($id)
    {
        $this->hallService->deleteHall($id);
        return response()->json(null, 204);
    }
}
