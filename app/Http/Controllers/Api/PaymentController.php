<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Promo;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::with('ticket')->get();

        return response()->json([
            'status' => 'success',
            'data' => $payments
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ticket_id' => 'required|exists:tickets,ticket_id',
            'quantity' => 'required|integer|min:1',
            'payment_status' => 'required|string|in:pending,completed,failed',
            'promo_code' => 'nullable|string'
        ]);

        $ticket = \App\Models\Ticket::with('flight')->findOrFail($validated['ticket_id']);
        $amount = $ticket->flight->price * $validated['quantity'];

        $hasilKalkulasi = $this->kalkulasiHargaTiket($amount, $validated['promo_code'] ?? null);

        $validated['total_price'] = $hasilKalkulasi['harga_akhir'];
        $validated['promo_id'] = $hasilKalkulasi['promo'] ? $hasilKalkulasi['promo']->promo_id : null;
        unset($validated['promo_code']);

        $payment = Payment::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Payment created successfully',
            'data' => $payment,
            'kalkulasi' => $hasilKalkulasi
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
            $payment = Payment::with('ticket')->find($id);

        if (!$payment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $payment
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment not found'
            ], 404);
        }

        $validated = $request->validate([
            'ticket_id' => 'sometimes|required|exists:tickets,ticket_id',
            'quantity' => 'sometimes|required|integer|min:1',
            'payment_status' => 'sometimes|required|string|in:pending,completed,failed',
            'promo_code' => 'nullable|string'
        ]);

        if (isset($validated['ticket_id'])) {
            $ticket = \App\Models\Ticket::with('flight')->findOrFail($validated['ticket_id']);
        } else {
            $ticket = $payment->ticket()->with('flight')->first();
        }

        $quantity = $validated['quantity'] ?? $payment->quantity;
        $amount = $ticket->flight->price * $quantity; // harga awal (TIDAK disimpan di tabel)

        $kodePromo = $validated['promo_code'] ?? ($payment->promo ? $payment->promo->promo_code : null);
        $hasilKalkulasi = $this->kalkulasiHargaTiket($amount, $kodePromo);

        $validated['total_price'] = $hasilKalkulasi['harga_akhir']; // harga akhir (DISIMPAN di tabel)
        $validated['promo_id'] = $hasilKalkulasi['promo'] ? $hasilKalkulasi['promo']->promo_id : null;
        unset($validated['promo_code']);

        $payment->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Payment updated successfully',
            'data' => $payment,
            'kalkulasi' => $hasilKalkulasi
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
            $payment = Payment::find($id);

        if (!$payment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment not found'
            ], 404);
        }

        $payment->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Payment deleted successfully'
        ]);
    }
    private function kalkulasiHargaTiket(float $hargaTiket, ?string $kodePromo = null): array
    {
        $diskon = 0.0;
        $promo = null;

        if ($kodePromo) {
            $promo = Promo::where('promo_code', $kodePromo)
                ->where('valid_until', '>=', now())
                ->first();

            if ($promo) {
                $diskon = $promo->discount / 100;
            }
        }

        $hargaAkhir = $hargaTiket - ($hargaTiket * $diskon);

        return [
            'harga_awal' => $hargaTiket,
            'harga_akhir' => $hargaAkhir,
            'promo' => $promo,
            'diskon' => $diskon * 100
        ];
    }
}
