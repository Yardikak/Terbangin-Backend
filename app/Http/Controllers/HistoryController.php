<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Payment;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    /**
     * Menampilkan daftar riwayat.
     */
    public function index()
    {
    // Remove 'status' from the select statement
        $history = History::select('history_id', 'user_id', 'ticket_id', 'payment_id', 'flight_date')->get();

    // Send the data to the 'history.index' view
        return view('history.index', ['history' => $history]);
    }



    /**
     * Menampilkan form untuk membuat riwayat baru.
     */
    public function create()
    {
        // Ambil data users, tickets, dan payments dari database
        $users = User::all(); // Ambil semua user
        $tickets = Ticket::all(); // Ambil semua tiket
        $payments = Payment::all(); // Ambil semua pembayaran

        // Kirim data ke tampilan create
        return view('history.create', compact('users', 'tickets', 'payments'));
    }

    /**
     * Menyimpan riwayat yang baru dibuat.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'ticket_id' => 'required|exists:tickets,ticket_id',
            'payment_id' => 'required|exists:payments,payment_id',
            'flight_date' => 'required|date',
        ]);

        $history = History::create($validated);

        return redirect()->route('history.index')->with('status', 'Riwayat berhasil dibuat');
    }

    /**
     * Menampilkan detail riwayat tertentu.
     */
    public function show(string $id)
    {
        $history = History::with(['user', 'ticket', 'payment'])->find($id);

        if (!$history) {
            return redirect()->route('history.index')->with('error', 'Riwayat tidak ditemukan');
        }

        return view('history.show', ['history' => $history]);
    }

    /**
     * Menampilkan form untuk mengedit riwayat yang ada.
     */
    public function edit($id)
    {
        $history = History::findOrFail($id);
        $users = User::all();
        $tickets = Ticket::all();
        $payments = Payment::all();

        return view('history.edit', compact('history', 'users', 'tickets', 'payments'));
    }


    /**
     * Memperbarui riwayat yang sudah ada.
     */
    public function update(Request $request, string $id)
    {
        $history = History::find($id);

        if (!$history) {
            return redirect()->route('history.index')->with('error', 'Riwayat tidak ditemukan');
        }

        $validated = $request->validate([
            'user_id' => 'sometimes|required|exists:users,id',
            'ticket_id' => 'sometimes|required|exists:tickets,ticket_id',
            'payment_id' => 'sometimes|required|exists:payments,payment_id',
            'flight_date' => 'sometimes|required|date',
            'status' => 'sometimes|required|string|max:100',
        ]);

        $history->update($validated);

        return redirect()->route('history.index')->with('status', 'Riwayat berhasil diperbarui');
    }

    /**
     * Menghapus riwayat yang ada.
     */
    public function destroy(string $id)
    {
        $history = History::find($id);

        if (!$history) {
            return redirect()->route('history.index')->with('error', 'Riwayat tidak ditemukan');
        }

        $history->delete();

        return redirect()->route('history.index')->with('status', 'Riwayat berhasil dihapus');
    }
}
