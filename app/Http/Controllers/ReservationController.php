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
        $validated = $request->validate([
            'screening_id' => 'required|exists:screenings,id',
            'seat_id' => 'required|integer|exists:seats,id' // SINGULAR
        ]);
        try {
            $reservation = $this->reservationService->createReservation([
                'user_id' => auth()->id(),
                'screening_id' => $validated['screening_id'],
                'seat_id' => $validated['seat_id'] // SINGULAR
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
