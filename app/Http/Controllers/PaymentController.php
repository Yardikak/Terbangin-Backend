<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Ticket;
use App\Models\Promo;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Menampilkan daftar pembayaran.
     */
    public function index()
    {
        $payments = Payment::select('payment_id', 'ticket_id', 'promo_id', 'quantity', 'total_price', 'payment_status')->get();
        return view('payments.index', compact('payments'));
    }

    /**
     * Menampilkan form untuk membuat pembayaran baru.
     */
    public function create()
    {
        // Ambil data tiket dan promo dari database
        $tickets = Ticket::all();
        $promo = Promo::all(); // Mengganti $promos menjadi $promo

        return view('payments.create', compact('tickets', 'promo')); // Mengganti $promos menjadi $promo
    }

    /**
     * Menyimpan pembayaran baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ticket_id' => 'required|exists:tickets,ticket_id',
            'promo_id' => 'nullable|exists:promos,promo_id',
            'quantity' => 'required|integer|min:1',
            'status' => 'required|string|in:Paid,Pending,Failed',
        ]);

        $ticket = Ticket::find($validated['ticket_id']);
        $promo = Promo::find($validated['promo_id']);  // Promo can be null
        $price = $ticket->price; // Assuming the 'price' field exists in the ticket model

        // Calculate the total price, applying promo discount if available
        $total_price = $price * $validated['quantity']; // Base price * quantity

        if ($promo) {
            $discount = $promo->discount / 100;
            $total_price -= $total_price * $discount; // Apply discount
        }

        // Store the payment record
        $payment = Payment::create([
            'ticket_id' => $validated['ticket_id'],
            'promo_id' => $validated['promo_id'],  // If promo is provided
            'quantity' => $validated['quantity'],
            'total_price' => $total_price,  // Store calculated price
            'payment_status' => $validated['status'], // Add the status field
        ]);

        return redirect()->route('payments.index')->with('status', 'Payment successfully added');
    }

    /**
     * Menampilkan form untuk mengedit pembayaran yang sudah ada.
     */
    public function edit($payment_id)
    {
        // Ambil data pembayaran berdasarkan payment_id
        $payment = Payment::findOrFail($payment_id);

        // Ambil data tiket dan promo untuk form
        $tickets = Ticket::all();
        $promo = Promo::all();

        return view('payments.edit', compact('payment', 'tickets', 'promo'));
    }

    /**
     * Mengupdate pembayaran yang sudah ada.
     */
    public function update(Request $request, $payment_id)
    {
        $validated = $request->validate([
            'ticket_id' => 'required|exists:tickets,ticket_id',
            'promo_id' => 'nullable|exists:promos,promo_id',
            'quantity' => 'required|integer|min:1',
            'status' => 'required|string|in:Paid,Pending,Failed',
        ]);

        // Ambil data pembayaran yang akan diupdate
        $payment = Payment::findOrFail($payment_id);
        
        // Ambil data tiket dan promo
        $ticket = Ticket::find($validated['ticket_id']);
        $promo = Promo::find($validated['promo_id']);  // Promo bisa null
        $price = $ticket->price;

        // Hitung harga total, jika ada diskon promo
        $total_price = $price * $validated['quantity'];

        if ($promo) {
            $discount = $promo->discount / 100;
            $total_price -= $total_price * $discount; // Terapkan diskon
        }

        // Update data pembayaran
        $payment->update([
            'ticket_id' => $validated['ticket_id'],
            'promo_id' => $validated['promo_id'],  // Jika ada promo
            'quantity' => $validated['quantity'],
            'total_price' => $total_price,  // Simpan harga total yang sudah dihitung
            'payment_status' => $validated['status'],
        ]);

        return redirect()->route('payments.index')->with('status', 'Payment successfully updated');
    }

    /**
     * Menghapus pembayaran yang sudah ada.
     */
    public function destroy($payment_id)
    {
        // Cari pembayaran berdasarkan payment_id dan hapus
        $payment = Payment::findOrFail($payment_id);
        $payment->delete();

        return redirect()->route('payments.index')->with('status', 'Payment successfully deleted');
    }
}
