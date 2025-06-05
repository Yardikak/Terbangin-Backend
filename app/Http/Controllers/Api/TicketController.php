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
        $tickets = Ticket::select('ticket_id', 'flight_id', 'purchase_date', 'e_ticket')->get();

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
            'flight_class_id' => 'required|exists:flight_classes,flight_class_id',
            'purchase_date' => 'required|date_format:Y-m-d H:i:s',
        ]);
        do {
            $eTicket = 'ETK-' . now()->timestamp . '-' . rand(1000, 9999);
        } while (Ticket::where('e_ticket', $eTicket)->exists());

        $validated['e_ticket'] = $eTicket;
        $ticket = Ticket::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Ticket created successfully',
            'data' => $ticket
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ticket = Ticket::select('ticket_id', 'flight_id', 'purchase_date', 'e_ticket')->find($id);

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
            'flight_class_id' => 'sometimes|required|exists:flight_classes,flight_class_id',
            'purchase_date' => 'required|date_format:Y-m-d H:i:s',
        ]);

        if ($request->regenerate_e_ticket) {
            do {
                $eTicket = 'ETK-' . now()->timestamp . '-' . rand(1000, 9999);
            } while (Ticket::where('e_ticket', $eTicket)->exists());
                $validated['e_ticket'] = $eTicket;
        }

        $ticket->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Ticket updated successfully',
            'data' => $ticket
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
}
