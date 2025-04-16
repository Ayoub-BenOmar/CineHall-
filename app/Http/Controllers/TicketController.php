<?php

namespace App\Http\Controllers;

use App\Services\TicketService;
use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PHPUnit\Framework\Attributes\Ticket;

class TicketController extends Controller
{
    protected $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function download(Ticket $ticket)
    {
        return $this->ticketService->downloadTicket($ticket);
    }

    public function validateTicket(Request $request)
    {
        $request->validate([
            'ticket_number' => 'required|string',
            'qr_code' => 'required|string'
        ]);

        $ticket = Ticket::where('ticket_number', $request->ticket_number)
            ->where('status', 'active')
            ->first();

        if (!$ticket) {
            return response()->json(['error' => 'Invalid ticket'], 404);
        }

        // Verify QR code
        $qrCodeData = json_decode($request->qr_code, true);
        if ($qrCodeData['ticket_number'] !== $ticket->ticket_number) {
            return response()->json(['error' => 'Invalid QR code'], 400);
        }

        // Mark ticket as used
        $ticket->update(['status' => 'used']);

        return response()->json([
            'message' => 'Ticket validated successfully',
            'ticket' => $ticket
        ]);
    }
} 