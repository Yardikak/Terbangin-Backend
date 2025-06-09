<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Promo;
use App\Models\Ticket;
use App\Models\User;
use App\Services\MidtransService;
use Midtrans\Notification;
use Midtrans\Transaction;
use Illuminate\Support\Facades\Log; 

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
        $payments = Payment::with('ticket', 'user')->get();

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
            'flight_class_id' => 'required|exists:flight_classes,flight_class_id',
            'quantity' => 'required|integer|min:1',
            'payment_status' => 'required|string|in:pending,completed,failed',
            'promo_code' => 'nullable|string|exists:promos,promo_code'
        ]);

        try {
            \Log::debug('Ticket ID: ', ['ticket_id' => $validated['ticket_id']]);
            \Log::debug('Flight Class ID: ', ['flight_class_id' => $validated['flight_class_id']]);
            $ticket = Ticket::with('flight', 'flightClass')->findOrFail($validated['ticket_id']);
            $flightClass = $ticket->flightClass;
            \Log::debug('Flight Class Price: ', ['price' => $flightClass->price]);
            \Log::debug('Quantity: ', ['quantity' => $validated['quantity']]);
            $amount = (float)$flightClass->price * (int)$validated['quantity'];
            \Log::debug('Calculated Amount: ', ['amount' => $amount]);
            $hasilKalkulasi = $this->kalkulasiHargaTiket($amount, $validated['promo_code'] ?? null);

            $paymentData = [
                'user_id' => auth()->id(),
                'ticket_id' => $validated['ticket_id'],
                'flight_class_id' => $validated['flight_class_id'],
                'quantity' => (int)$validated['quantity'],
                'payment_status' => $validated['payment_status'],
                'promo_id' => $hasilKalkulasi['promo']->promo_id ?? null,
                'total_price' => (float)$hasilKalkulasi['harga_akhir']
            ];

            $payment = Payment::create($paymentData);

            $transactionDetails = [
                'order_id' => 'PAY-' . $payment->payment_id . '-' . time(),
                'gross_amount' => (int)$hasilKalkulasi['harga_akhir'],
            ];

            $user = auth()->user();
            $customerDetails = [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? '',
            ];

            $params = [
                'transaction_details' => $transactionDetails,
                'customer_details' => $customerDetails,
                'callbacks' => [
                    'finish' => config('app.url') . '/payments/finish',
                    'error' => config('app.url') . '/payments/error',
                    'pending' => config('app.url') . '/payments/pending'
                ]
            ];

            // Buat transaksi Midtrans
            $snapToken = $this->midtrans->createTransaction($params);

            // Update data payment
            $payment->update([
                'midtrans_order_id' => $transactionDetails['order_id'],
                'midtrans_snap_token' => $snapToken->token,
                'payment_url' => $snapToken->redirect_url,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Payment created successfully',
                'data' => $payment->load(['user', 'ticket', 'flightClass', 'promo']),
                'payment_url' => $snapToken->redirect_url,
                'price_details' => $hasilKalkulasi
            ], 201);

        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Database error',
                'error' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create payment',
                'error' => $e->getMessage()
            ], 500);
        }
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
                $diskon = (float)$promo->discount / 100;
            }
        }

        $hargaAkhir = $hargaTiket - ($hargaTiket * $diskon);

        return [
            'harga_awal' => (float)$hargaTiket,
            'harga_akhir' => round($hargaAkhir, 2),
            'promo' => $promo,
            'diskon' => $diskon * 100,
            'potongan' => $hargaTiket * $diskon
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $payment = Payment::with([
            'ticket',
            'user',
            'flightClass' => function($query) {
                $query->with(['flight']);
            }
        ])->find($id);

        if (!$payment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment not found'
            ], 404);
        }

        // Get detailed price information
        $priceDetails = [
            'price' => $payment->FlightClass->price,
            'quantity' => $payment->quantity,
            'total_before_discount' => $payment->flightClass->price * $payment->quantity,
            'discount' => $payment->promo ? ($payment->flightClass->price * $payment->quantity * $payment->promo->discount / 100) : 0,
            'total_price' => $payment->total_price
        ];

        return response()->json([
            'status' => 'success',
            'data' => [
                'payment' => $payment,
                'price_details' => $priceDetails,
                'flight_class' => $payment->flightClass,
                'flight' => $payment->flightClass->flight ?? null
            ]
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
            'flight_class_id' => 'sometimes|required|exists:flight_classes,flight_class_id', // Added validation for flight_class_id
            'quantity' => 'sometimes|required|integer|min:1',
            'payment_status' => 'sometimes|required|string|in:pending,completed,failed',
            'promo_code' => 'nullable|string'
        ]);

        // Update the ticket and flight class data if necessary
        if (isset($validated['ticket_id'])) {
            $ticket = Ticket::with('flight', 'flightClass')->findOrFail($validated['ticket_id']);
        } else {
            $ticket = $payment->ticket()->with('flight', 'flightClass')->first();
        }

        $flightClass = $ticket->flightClass;
        $quantity = $validated['quantity'] ?? $payment->quantity;
        $amount = (float)$flightClass->price * (int)$quantity;
        $kodePromo = $validated['promo_code'] ?? ($payment->promo ? $payment->promo->promo_code : null);

        // Calculate new ticket price with promo code if any
        $hasilKalkulasi = $this->kalkulasiHargaTiket($amount, $kodePromo);

        // Update payment data
        $validated['total_price'] = $hasilKalkulasi['harga_akhir'];
        $validated['promo_id'] = $hasilKalkulasi['promo'] ? $hasilKalkulasi['promo']->promo_id : null;
        unset($validated['promo_code']);

        // Update the payment record
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

    /**
     * Handle Midtrans notification/callback
     */
    public function handleNotification(Request $request)
    {
        $payload = $request->all();

        try {
            $notif = new Notification();

            $transaction = $notif->transaction_status;
            $orderId = $notif->order_id;
            $fraud = $notif->fraud_status;

            $payment = Payment::with('user')->where('midtrans_order_id', $orderId)->firstOrFail();

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
