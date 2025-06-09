<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Flight;
use App\Models\Passenger;
use App\Models\User;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = Ticket::with(['flight', 'passenger'])->get();
        return view('tickets.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $flights = Flight::all(); // Ambil semua flight
        $passengers = Passenger::all(); // Ambil semua passenger
        $users = User::all(); // Ambil semua user
        
        return view('tickets.create', compact('flights', 'passengers', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi form untuk memasukkan data ticket baru
        $validated = $request->validate([
            'flight_id' => 'required|exists:flights,flight_id',  // Pastikan validasi menggunakan flight_id
            'user_id' => 'required|exists:users,id',
            'passenger_id' => 'required|exists:passengers,passenger_id',
            'status' => 'required|string|max:255',
            'purchase_date' => 'required|date',
            'e_ticket' => 'required|string|max:255',
        ]);

        // Membuat ticket baru dengan data yang sudah divalidasi
        $ticket = Ticket::create($validated);

        // Redirect ke halaman index dengan pesan status
        return redirect()->route('tickets.index')->with('status', 'Ticket created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ticket = Ticket::with(['flight', 'passenger'])->find($id);

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
        $passengers = Passenger::all();
        $users = User::all();

        return view('tickets.edit', compact('ticket', 'flights', 'passengers', 'users'));
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

        // Validasi input untuk update ticket
        $validated = $request->validate([
            'flight_id' => 'sometimes|required|exists:flights,flight_id', // Pastikan menggunakan flight_id
            'user_id' => 'sometimes|required|exists:users,id',
            'passenger_id' => 'sometimes|required|exists:passengers,passenger_id',
            'status' => 'sometimes|required|string|max:255',
            'purchase_date' => 'sometimes|required|date',
            'e_ticket' => 'sometimes|required|string|max:255',
        ]);

        // Update ticket berdasarkan data yang divalidasi
        $ticket->update($validated);

        return redirect()->route('tickets.index')->with('status', 'Ticket updated successfully!');
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

        // Hapus ticket
        $ticket->delete();

        return redirect()->route('tickets.index')->with('status', 'Ticket deleted successfully!');
    }

    /**
     * Get tickets by user ID.
     */
    public function getTicketsByUser(string $user_id)
    {
        $tickets = Ticket::with(['flight', 'passenger'])
            ->where('user_id', $user_id)
            ->get();

        if ($tickets->isEmpty()) {
            return redirect()->route('tickets.index')->with('error', 'No tickets found for this user');
        }

        return view('tickets.index', compact('tickets'));
    }
}
