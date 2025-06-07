<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $tickets = Ticket::with('flight')->get();

    return response()->json([
        'status' => 'success',
        'data' => $tickets
    ]);
}


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'flight_id' => 'required|exists:flights,flight_id',
            'user_id' => 'required|exists:users,user_id',
            'status' => 'required|string|max:255',
            'purchase_date' => 'required|date',
            'e_ticket' => 'required|string|max:255',
        ]);

        $ticket = Ticket::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Ticket created successfully',
            'data' => [
                'ticket_id' => $ticket->ticket_id,
                'flight_id' => $ticket->flight_id,
                'user_id' => $ticket->user_id,
                'status' => $ticket->status,
                'purchase_date' => $ticket->purchase_date,
                'e_ticket' => $ticket->e_ticket,
            ]
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
{
    $ticket = Ticket::with('flight')->find($id);

    if (!$ticket) {
        return response()->json([
            'status' => 'error',
            'message' => 'Ticket not found'
        ], 404);
    }

    return response()->json([
        'status' => 'success',
        'data' => $ticket
    ]);
}


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ticket not found'
            ], 404);
        }

        $validated = $request->validate([
            'flight_id' => 'sometimes|required|exists:flights,flight_id',
            'user_id' => 'sometimes|required|exists:users,user_id',
            'status' => 'sometimes|required|string|max:255',
            'purchase_date' => 'sometimes|required|date',
            'e_ticket' => 'sometimes|required|string|max:255',
        ]);

        $ticket->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Ticket updated successfully',
            'data' => [
                'ticket_id' => $ticket->ticket_id,
                'flight_id' => $ticket->flight_id,
                'user_id' => $ticket->user_id,
                'status' => $ticket->status,
                'purchase_date' => $ticket->purchase_date,
                'e_ticket' => $ticket->e_ticket,
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ticket not found'
            ], 404);
        }

        $ticket->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Ticket deleted successfully'
        ]);
    }

    /**
     * Get tickets by user ID.
     */
    public function getTicketsByUser(string $user_id)
{
    $tickets = Ticket::with('flight')
        ->where('user_id', $user_id)
        ->get();

    if ($tickets->isEmpty()) {
        return response()->json([
            'status' => 'error',
            'message' => 'No tickets found for this user'
        ], 404);
    }

    return response()->json([
        'status' => 'success',
        'data' => $tickets
    ]);
}

}