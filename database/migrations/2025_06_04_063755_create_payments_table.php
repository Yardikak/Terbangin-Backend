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
        Schema::create('payments', function (Blueprint $table) {
        $table->id('payment_id');
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('ticket_id')->constrained('tickets', 'ticket_id')->onDelete('cascade');
        $table->foreignId('flight_class_id')->constrained('flight_classes', 'flight_class_id')->onDelete('cascade');
        $table->foreignId('promo_id')->nullable()->constrained('promos', 'promo_id')->onDelete('set null');
        $table->integer('quantity')->default(1);
        $table->decimal('total_price', 15, 2);
        $table->string('payment_status');
        $table->string('midtrans_order_id')->nullable();
        $table->string('midtrans_snap_token')->nullable();
        $table->string('payment_url')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
