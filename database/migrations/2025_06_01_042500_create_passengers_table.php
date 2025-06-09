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
        Schema::create('passengers', function (Blueprint $table) {
            $table->id('passenger_id');
            $table->string('name');
            $table->string('title')->nullable();
            $table->string('nik_number', 16)->unique();
            $table->date('birth_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
        {
            Schema::dropIfExists('payments');
            Schema::dropIfExists('tickets');
            Schema::dropIfExists('passengers');

        }

};