<?php

namespace App\Http\Controllers;

use App\Models\Seat;
use App\Models\Screening;
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
        $request->validate([
            'screening_id' => 'required|exists:screenings,id',
            'seat_id' => 'required|exists:seats,id' // Now accepting single seat
        ]);
    
        $screening = Screening::find($request->screening_id);
        $selectedSeat = Seat::find($request->seat_id);
    
        $seatsToReserve = [$selectedSeat->id];
    
        // Handle VIP screenings
        if ($screening->type === 'VIP') {
            $adjacentSeat = $this->findAdjacentCoupleSeat($selectedSeat);
            
            if (!$adjacentSeat) {
                return response()->json([
                    'error' => 'No available adjacent seat for VIP couple reservation'
                ], 422);
            }
    
            $seatsToReserve[] = $adjacentSeat->id;
        }
    
        try {
            $this->seatService->reserveSeats($seatsToReserve);
            
            return response()->json([
                'message' => 'Seats reserved successfully',
                'reserved_seats' => $seatsToReserve
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    
    protected function findAdjacentCoupleSeat(Seat $seat)
    {
        // Get all seats in the same row
        $rowSeats = Seat::where('hall_id', $seat->hall_id)
                      ->where('seat_number', 'like', substr($seat->seat_number, 0, 1).'%')
                      ->orderBy('seat_number')
                      ->get();
    
        $currentPosition = null;
        
        // Find position in row
        foreach ($rowSeats as $index => $rowSeat) {
            if ($rowSeat->id === $seat->id) {
                $currentPosition = $index;
                break;
            }
        }
    
        if ($currentPosition === null) return null;
    
        // Check left seat first
        if ($currentPosition > 0) {
            $leftSeat = $rowSeats[$currentPosition - 1];
            if (!$leftSeat->is_reserved && $leftSeat->is_couple_seat) {
                return $leftSeat;
            }
        }
    
        // Then check right seat
        if ($currentPosition < count($rowSeats) - 1) {
            $rightSeat = $rowSeats[$currentPosition + 1];
            if (!$rightSeat->is_reserved && $rightSeat->is_couple_seat) {
                return $rightSeat;
            }
        }
    
        return null;
    }
}
