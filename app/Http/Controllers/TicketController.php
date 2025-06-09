<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Flight;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Menampilkan daftar tiket.
     */
    public function index()
    {
        $tickets = Ticket::select('ticket_id', 'flight_id', 'status', 'purchase_date', 'e_ticket')->get();

        return view('tickets.index', ['tickets' => $tickets]);
    }

    /**
     * Menampilkan form untuk membuat tiket baru.
     */
   public function create()
    {
        // Fetch all available flights from the database
        $flights = Flight::all();  // Assuming you have a model called Flight

        // Pass the flights data to the view
        return view('tickets.create', compact('flights'));
    }



    /**
     * Menyimpan tiket yang baru dibuat.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'flight_id' => 'required|exists:flights,id', // Ensure flight_id exists in the flights table
            'status' => 'required|string|max:255',
            'purchase_date' => 'required|date',
            'e_ticket' => 'required|string|max:255',
        ]);

        // Store the validated data in the database
        Ticket::create($validated);

        // Redirect back with success message
        return redirect()->route('tickets.index')->with('status', 'Ticket created successfully!');
    }


    /**
     * Menampilkan detail tiket tertentu.
     */
    public function show(string $id)
    {
        $ticket = Ticket::select('ticket_id', 'flight_id', 'status', 'purchase_date', 'e_ticket')->find($id);

        if (!$ticket) {
            return redirect()->route('tickets.index')->with('error', 'Ticket not found');
        }

        return view('tickets.show', ['ticket' => $ticket]);
    }

    /**
     * Menampilkan form untuk mengedit tiket yang ada.
     */
    public function edit(string $id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return redirect()->route('tickets.index')->with('error', 'Ticket not found');
        }

        return view('tickets.edit', ['ticket' => $ticket]);
    }

    /**
     * Memperbarui tiket yang ada.
     */
    public function update(Request $request, string $id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return redirect()->route('tickets.index')->with('error', 'Ticket not found');
        }

        $validated = $request->validate([
            'flight_id' => 'sometimes|required|exists:flights,flight_id',
            'status' => 'sometimes|required|string|max:255',
            'purchase_date' => 'sometimes|required|date',
            'e_ticket' => 'sometimes|required|string|max:255',
        ]);

        $ticket->update($validated);

        return redirect()->route('tickets.index')->with('status', 'Ticket updated successfully');
    }

    /**
     * Menghapus tiket yang ada.
     */
    public function destroy(string $id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return redirect()->route('tickets.index')->with('error', 'Ticket not found');
        }

        $ticket->delete();

        return redirect()->route('tickets.index')->with('status', 'Ticket deleted successfully');
    }
}
