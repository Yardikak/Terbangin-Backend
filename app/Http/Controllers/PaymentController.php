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
        'total_price' => 'required|numeric|min:0', // Make sure total_price is validated
    ]);

    // Process the payment logic
    $ticket = Ticket::find($validated['ticket_id']);
    
    // Fetch the flight associated with the ticket
    $flight = $ticket->flight;  // Assuming the 'flight' relationship is correctly set up in the Ticket model

    if (!$flight) {
        // If no flight is found, you may want to handle this case (optional)
        return redirect()->back()->withErrors(['flight' => 'Flight not found for the selected ticket.']);
    }

    $price = $flight->price;  // Fetch the price from the associated flight

    // Calculate the total price with or without promo
    $total_price = $price * $validated['quantity'];

    if ($validated['promo_id']) {
        // Fetch the promo if provided
        $promo = Promo::find($validated['promo_id']);
        if ($promo) {
            $discount = $promo->discount / 100;
            $total_price -= $total_price * $discount; // Apply discount
        }
    }

    // Store the payment record
    $payment = Payment::create([
        'user_id' => auth()->id(),
        'ticket_id' => $validated['ticket_id'],
        'promo_id' => $validated['promo_id'],  // If promo is provided
        'quantity' => $validated['quantity'],
        'total_price' => $total_price,  // Store calculated price (use the calculated total_price, not the validated one)
        'payment_status' => $validated['status'],
    ]);

    return redirect()->route('payments.index')->with('status', 'Payment successfully added');
}



    /**
     * Menampilkan form untuk mengedit pembayaran yang sudah ada.
     */
   public function edit($payment_id)
{
    // Fetch the payment record to be edited
    $payment = Payment::findOrFail($payment_id);

    // Fetch available tickets and promo codes for the form
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

    // Fetch the existing payment record
    $payment = Payment::findOrFail($payment_id);
    
    // Fetch the ticket and associated flight for price calculation
    $ticket = Ticket::find($validated['ticket_id']);
    $flight = $ticket->flight;  // Assuming the 'flight' relationship is correctly set up in the Ticket model

    if (!$flight) {
        // Handle case where no associated flight is found (optional)
        return redirect()->back()->withErrors(['flight' => 'Flight not found for the selected ticket.']);
    }

    // Get the flight price
    $price = $flight->price;

    // Calculate the total price with or without promo
    $total_price = $price * $validated['quantity'];

    if ($validated['promo_id']) {
        // Fetch the promo if provided
        $promo = Promo::find($validated['promo_id']);
        if ($promo) {
            $discount = $promo->discount / 100;
            $total_price -= $total_price * $discount; // Apply discount
        }
    }

    // Update the payment record
    $payment->update([
        'user_id' => auth()->id(),
        'ticket_id' => $validated['ticket_id'],
        'promo_id' => $validated['promo_id'],  // If promo is provided
        'quantity' => $validated['quantity'],
        'total_price' => $total_price,  // Store calculated price (use the calculated total_price, not the validated one)
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
