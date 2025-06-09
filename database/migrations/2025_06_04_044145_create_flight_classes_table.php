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
        Schema::create('flight_classes', function (Blueprint $table) {
        $table->id('flight_class_id');
        $table->foreignId('flight_id')->constrained('flights', 'flight_id')->onDelete('cascade');
        $table->enum('class', ['economy', 'business', 'first']);
        $table->unsignedInteger('seat_capacity');
        $table->unsignedInteger('available_seats');
        $table->decimal('price', 15, 2);
        $table->unique(['flight_id', 'class']);
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flight_classes');
    }
};
