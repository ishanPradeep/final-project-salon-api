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
        Schema::create('used_promos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('promo_id');
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('user_id');
            $table->string('discount_price');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
            $table->foreign('promo_id')->references('id')->on('promos')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('used_promos');
    }
};
