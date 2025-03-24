<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SeatService;

class SeatController extends Controller
{
    protected $seatService;

    public function __construct(SeatService $seatService)
    {
        $this->seatService = $seatService;
    }

    public function index()
    {
        $seats = $this->seatService->getAllSeats();
        return response()->json($seats);
    }

    public function show($id)
    {
        $seat = $this->seatService->getSeatById($id);
        return response()->json($seat);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $seat = $this->seatService->createSeat($data);
        return response()->json($seat, 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $seat = $this->seatService->updateSeat($id, $data);
        return response()->json($seat);
    }

    public function destroy($id)
    {
        $this->seatService->deleteSeat($id);
        return response()->json(null, 204);
    }

    public function getAvailableSeats($hallId)
    {
        $seats = $this->seatService->getAvailableSeats($hallId);
        return response()->json($seats);
    }

    public function reserveSeats(Request $request)
    {
        $seatIds = $request->input('seat_ids');
        $this->seatService->reserveSeats($seatIds);
        return response()->json(['message' => 'Seats reserved successfully']);
    }
}
