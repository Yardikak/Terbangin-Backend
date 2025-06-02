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
        Schema::create('histories', function (Blueprint $table) {
        $table->id('history_id');
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('ticket_id')->constrained('tickets', 'ticket_id')->onDelete('cascade');
        $table->foreignId('payment_id')->constrained('payments', 'payment_id')->onDelete('cascade');
        $table->dateTime('flight_date');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('histories');
    }
};
