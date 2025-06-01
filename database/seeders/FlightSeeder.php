<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Flight;


class FlightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Flight::create([
            'airline_name' => 'Garuda Indonesia',
            'flight_number' => 'GA123',
            'departure' => now()->addDays(1),
            'arrival' => now()->addDays(1)->addHours(2),
            'destination' => 'Jakarta (CGK)',
            'price' => 1500000.00,
            'status' => 'scheduled',
        ]);

        Flight::factory(8)->create();
    }
}
