<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\History;


class HistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        History::create([
            'user_id' => 1,
            'ticket_id' => 1,
            'flight_id' => 1,
            'flight_date' => now()->addDays(1),
        ]);

        History::factory(10)->create();
    }
}
