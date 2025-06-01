<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ticket;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ticket::create([
            'user_id' => 1,
            'flight_id' => 1,
            'status' => 'confirmed',
            'purchase_date' => now(),
            'e_ticket' => 'ETICKET'.now()->timestamp,
        ]);

        Ticket::factory(15)->create();
    }
}
