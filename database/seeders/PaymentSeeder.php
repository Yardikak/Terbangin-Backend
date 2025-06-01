<?php

namespace Database\Seeders;

use App\Models\Payment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Payment::create([
            'ticket_id' => 1,
            'amount' => 1350000.00,
            'payment_date' => now(),
            'payment_status' => 'completed',
        ]);

        Payment::factory(10)->create();
    }
}
