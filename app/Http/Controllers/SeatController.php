<?php

namespace App\Http\Controllers;

use App\Models\Seat;
use App\Models\Screening;
use Illuminate\Http\Request;
use App\Services\SeatService;

use function PHPUnit\Framework\isEmpty;

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
        $validated = $request->validate([
            'screening_id' => 'required|exists:screenings,id',
            'seat_id' => 'required|min:1',
        ]);
    
        // $screening = Screening::findOrFail($request->screening_id);
        // $seatIds = $request->input('seat_ids');
        // $seats = Seat::whereIn('id', $seatIds)->get();

        $screening = Screening::findOrFail($validated['screening_id']);
        $seatIds = $validated['seat_id'];
        $seats = Seat::whereIn('id', $seatIds)->get();
    
        if ($screening->type === 'VIP') {
                
    
            if (!$this->areSeatsAdjacent($seats[0], $seats[1])) {
                return response()->json([
                    'error' => 'Selected seats must be adjacent'
                ], 422);
            }
        }
        if($seats.isEmpty()){
             return response()->json('empty');
        }
        foreach ($seats as $seat) {
            if ($seat->is_reserved) {
                return response()->json([
                    'error' => "Seat {$seat->seat_number} is already reserved"
                ], 422);
            }
        }
    
        try {
            $this->seatService->reserveSeats($seatIds);
            return response()->json([
                'message' => 'Seats reserved successfully',
                'reserved_seats' => $seatIds
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    
    protected function areSeatsAdjacent(Seat $seat1, Seat $seat2)
    {
        if ($seat1->hall_id !== $seat2->hall_id) return false;
    
        // Parse seat numbers (supports formats like "A1", "B12", etc.)
        preg_match('/^([A-Z]+)(\d+)$/', $seat1->seat_number, $matches1);
        preg_match('/^([A-Z]+)(\d+)$/', $seat2->seat_number, $matches2);
    
        // Same row and consecutive numbers
        return $matches1[1] === $matches2[1] && 
               abs($matches1[2] - $matches2[2]) === 1;
    }
}
