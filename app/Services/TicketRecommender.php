<?php

namespace App\Services;

use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use App\Models\Payment;

class TicketRecommender
{
    protected $k;

    public function __construct($k = 3)
    {
        $this->k = $k;
    }

    /**
     * Mencari tiket yang jarang dipesan berdasarkan KNN
     */
    public function findLeastPopularTickets()
    {
        $tickets = DB::table('tickets')
            ->leftJoin('payments', 'tickets.ticket_id', '=', 'payments.ticket_id')
            ->select(
                'tickets.ticket_id',
                DB::raw('COALESCE(payments.quantity, 0) as purchased_quantity'),
                DB::raw('CASE WHEN payments.payment_status = "completed" THEN 1 ELSE 0 END as payment_completed'),
                DB::raw('DATEDIFF(NOW(), tickets.purchase_date) as days_since_purchase')
            )
            ->get();

        // Hitung fitur untuk setiap tiket
        $ticketFeatures = $tickets->map(function ($ticket) {
            return [
                'ticket_id' => $ticket->ticket_id,
                'purchased_quantity' => $ticket->purchased_quantity,
                'payment_completed' => $ticket->payment_completed,
                'days_since_purchase' => $ticket->days_since_purchase ?? 0
            ];
        })->toArray();

        if (count($ticketFeatures) < $this->k) {
            return [];
        }

        // Normalisasi fitur
        $normalizedFeatures = $this->normalizeFeatures($ticketFeatures);

        // Hitung jarak Euclidean ke vektor "tiket paling jarang dipesan"
        $target = ['purchased_quantity' => 0, 'payment_completed' => 0, 'days_since_purchase' => max(array_column($ticketFeatures, 'days_since_purchase'))];
        $distances = [];
        foreach ($normalizedFeatures as $i => $feature) {
            $distances[$i] = sqrt(
                pow($feature['purchased_quantity'] - 0, 2) +
                pow($feature['payment_completed'] - 0, 2) +
                pow($feature['days_since_purchase'] - 1, 2) // normalisasi max = 1
            );
        }

        // Ambil K tiket dengan jarak terdekat ke target
        asort($distances);
        $nearest = array_slice(array_keys($distances), 0, $this->k);

        // Return tiket_id dan fitur
        return array_map(function ($idx) use ($ticketFeatures) {
            return $ticketFeatures[$idx];
        }, $nearest);
    }

    /**
     * Ekstrak fitur dari tiket untuk perhitungan KNN
     */
    protected function extractFeatures(Ticket $ticket)
    {
        $totalSeats = $ticket->flight->seat_capacity ?? 1;
        $purchasedSeats = $ticket->payment ? $ticket->payment->quantity : 0;
        $paymentStatus = $ticket->payment ? ($ticket->payment->payment_status === 'completed' ? 1 : 0) : 0;
        
        return [
            'ticket_id' => $ticket->ticket_id,
            'seat_utilization' => $purchasedSeats / $totalSeats,
            'payment_completion' => $paymentStatus,
            'days_since_purchase' => $ticket->purchase_date ? now()->diffInDays($ticket->purchase_date) : 0
        ];
    }

    /**
     * Normalisasi fitur untuk perhitungan jarak
     */
    protected function normalizeFeatures(array $features)
    {
        $normalized = [];
        $columns = ['purchased_quantity', 'payment_completed', 'days_since_purchase'];

        foreach ($columns as $col) {
            $values = array_column($features, $col);
            $min = min($values);
            $max = max($values);
            $range = $max - $min ?: 1;

            foreach ($features as $i => $feature) {
                $normalized[$i][$col] = ($feature[$col] - $min) / $range;
            }
        }

        return $normalized;
    }

    /**
     * Hitung jarak Euclidean antara semua tiket
     */
    protected function calculateDistances(array $features)
    {
        $distances = [];
        $count = count($features);
        
        for ($i = 0; $i < $count; $i++) {
            for ($j = $i + 1; $j < $count; $j++) {
                $distance = sqrt(
                    pow($features[$i]['seat_utilization'] - $features[$j]['seat_utilization'], 2) +
                    pow($features[$i]['payment_completion'] - $features[$j]['payment_completion'], 2) +
                    pow($features[$i]['days_since_purchase'] - $features[$j]['days_since_purchase'], 2)
                );
                
                $distances[$i][$j] = $distance;
                $distances[$j][$i] = $distance;
            }
        }
        
        return $distances;
    }

    /**
     * Temukan K tiket terdekat yang paling jarang dipesan
     */
    protected function findKNearestNeighbors(array $distances, array $originalFeatures)
    {
        $averages = [];
        
        foreach ($distances as $i => $row) {
            // Urutkan jarak dari yang terkecil
            asort($row);
            
            // Ambil K tetangga terdekat
            $neighbors = array_slice(array_keys($row), 0, $this->k, true);
            
            // Hitung rata-rata seat utilization dari tetangga
            $avgUtilization = array_reduce($neighbors, function($carry, $idx) use ($originalFeatures) {
                return $carry + $originalFeatures[$idx]['seat_utilization'];
            }, 0) / $this->k;
            
            $averages[$originalFeatures[$i]['ticket_id']] = $avgUtilization;
        }
        
        // Urutkan dari utilization terendah (paling jarang dipesan)
        asort($averages);
        
        return array_slice($averages, 0, $this->k, true);
    }
}