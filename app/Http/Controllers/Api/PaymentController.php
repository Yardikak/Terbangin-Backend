<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Promo;
use App\Services\MidtransService;
use Midtrans\Notification;
use Midtrans\Transaction;

class PaymentController extends Controller
{
    protected $midtrans;

    public function __construct()
    {
        $this->midtrans = new MidtransService();
    }

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

        $transactionDetails = [
            'order_id' => 'PAY-' . $payment->payment_id . '-' . time(),
            'gross_amount' => (int) round($hasilKalkulasi['harga_akhir']),
        ];

        $user = auth()->user();

        $customerDetails = [
            'first_name' => $user ? $user->name : 'Guest',
            'email' => $user ? $user->email : 'guest@example.com',
        ];

        $params = [
            'transaction_details' => $transactionDetails,
            'customer_details' => $customerDetails,
            'callbacks' => [
                'finish' => config('app.url') . '/payment/finish',
                'error' => config('app.url') . '/payment/error',
                'pending' => config('app.url') . '/payment/pending'
            ]
        ];

        try {
            $snapToken = $this->midtrans->createTransaction($params);

            $payment->update([
                'midtrans_order_id' => $transactionDetails['order_id'],
                'midtrans_snap_token' => $snapToken->token,
                'payment_url' => $snapToken->redirect_url,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Payment created successfully',
                'data' => $payment,
                'payment_url' => $snapToken->redirect_url,
                'kalkulasi' => $hasilKalkulasi
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create payment transaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
            $payment = Payment::with(['ticket'])->find($id);

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
        $amount = $ticket->flight->price * $quantity;
        $kodePromo = $validated['promo_code'] ?? ($payment->promo ? $payment->promo->promo_code : null);
        $hasilKalkulasi = $this->kalkulasiHargaTiket($amount, $kodePromo);

        $validated['total_price'] = $hasilKalkulasi['harga_akhir'];
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

    // Tambahkan method berikut untuk menerima notifikasi/callback dari Midtrans
    public function handleNotification(Request $request)
    {
        $payload = $request->all();

        try {
            $notif = new Notification();
            
            $transaction = $notif->transaction_status;
            $orderId = $notif->order_id;
            $fraud = $notif->fraud_status;

            $payment = Payment::where('midtrans_order_id', $orderId)->firstOrFail();

            if ($transaction == 'capture') {
                if ($fraud == 'challenge') {
                    $payment->update(['payment_status' => 'pending']);
                } else if ($fraud == 'accept') {
                    $payment->update(['payment_status' => 'completed']);
                }
            } else if ($transaction == 'settlement') {
                $payment->update(['payment_status' => 'completed']);
            } else if ($transaction == 'pending') {
                $payment->update(['payment_status' => 'pending']);
            } else if ($transaction == 'deny' || $transaction == 'expire' || $transaction == 'cancel') {
                $payment->update(['payment_status' => 'failed']);
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function paymentFinish(Request $request)
    {
        return view('payment.finish');
    }

    public function paymentError(Request $request)
    {
        return view('payment.error');
    }

    public function paymentPending(Request $request)
    {
        return view('payment.pending');
    }
}
