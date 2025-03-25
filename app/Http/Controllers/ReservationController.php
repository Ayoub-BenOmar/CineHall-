<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReservationService;

class ReservationController extends Controller
{
    protected $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    public function store(Request $request)
    {
        $request->validate([
            'screening_id' => 'required|exists:screenings,id',
            'seat_ids' => 'required|array',
            'seat_ids.*' => 'exists:seats,id'
        ]);

        try {
            $reservation = $this->reservationService->createReservation([
                'user_id' => auth()->id(),
                'screening_id' => $request->screening_id,
                'seat_ids' => $request->seat_ids
            ]);
            
            return response()->json($reservation, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function cancel($id)
    {
        $this->reservationService->cancelReservation($id);
        return response()->json(['message' => 'Reservation cancelled']);
    }
}
