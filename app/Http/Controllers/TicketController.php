<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Flight;
use App\Models\User;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = Ticket::with('flight')->get();

        return view('tickets.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $flights = Flight::all();
        $users = User::all();
        
        return view('tickets.create', compact('flights', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'flight_id' => 'required|exists:flights,flight_id',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|string|max:255',
            'purchase_date' => 'required|date',
            'e_ticket' => 'required|string|max:255',
        ]);

        $ticket = Ticket::create($validated);

        return redirect()->route('tickets.index')->with('success', 'Ticket created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ticket = Ticket::with('flight')->find($id);

        if (!$ticket) {
            return redirect()->route('tickets.index')->with('error', 'Ticket not found');
        }

        return view('tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return redirect()->route('tickets.index')->with('error', 'Ticket not found');
        }

        $flights = Flight::all();
        $users = User::all();

        return view('tickets.edit', compact('ticket', 'flights', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return redirect()->route('tickets.index')->with('error', 'Ticket not found');
        }

        $validated = $request->validate([
            'flight_id' => 'sometimes|required|exists:flights,flight_id',
            'user_id' => 'sometimes|required|exists:users,id',
            'status' => 'sometimes|required|string|max:255',
            'purchase_date' => 'sometimes|required|date',
            'e_ticket' => 'sometimes|required|string|max:255',
        ]);

        $ticket->update($validated);

        return redirect()->route('tickets.index')->with('success', 'Ticket updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return redirect()->route('tickets.index')->with('error', 'Ticket not found');
        }

        $ticket->delete();

        return redirect()->route('tickets.index')->with('success', 'Ticket deleted successfully');
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
            return redirect()->route('tickets.index')->with('error', 'No tickets found for this user');
        }

        return view('tickets.index', compact('tickets'));
    }
}
