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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('employer_id');
            $table->integer('salon_sub_service_id');
            $table->string('sub_service_name');
            $table->string('from_time');
            $table->string('to_time');
            $table->string('date');
            $table->string('duration');
            $table->string('price');
            $table->string('actual_price');
            $table->string('vat');
            $table->string('service_discount');
            $table->string('offer_discount');
            $table->string('status')->default('pending');
            $table->boolean('booking_cancellation');

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('employer_id')->references('id')->on('employers');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
