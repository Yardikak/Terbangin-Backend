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
        Schema::create('tickets', function (Blueprint $table) {
        $table->id('ticket_id');
        $table->foreignId('flight_id')->constrained('flights', 'flight_id')->onDelete('cascade');
        $table->foreignId('flight_class_id')->constrained('flight_classes', 'flight_class_id')->onDelete('cascade');
        $table->dateTime('purchase_date');
        $table->string('e_ticket', 255)->unique();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
