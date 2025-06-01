<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Promo;


class PromoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Promo::create([
            'promo_code' => 'WELCOME20',
            'description' => 'Welcome discount 20%',
            'discount' => 20.00,
            'valid_until' => now()->addMonth(),
        ]);

        Promo::factory(5)->create();
    }
}
