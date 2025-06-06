<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('flights', function (Blueprint $table) {
        $table->id('flight_id');
        $table->string('airline_name');
        $table->string('flight_number');
        $table->dateTime('departure');
        $table->dateTime('arrival');
        $table->string('from');
        $table->string('destination');
        $table->decimal('price', 10, 2);
        $table->string('status');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
