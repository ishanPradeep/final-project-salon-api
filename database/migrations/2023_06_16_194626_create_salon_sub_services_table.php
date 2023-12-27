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
        Schema::create('salon_sub_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('salon_service_id');
            $table->boolean('auto_approval')->default(0);
            $table->boolean('booking_cancellation')->default(1);
            $table->string('sub_service_name');
            $table->integer('hour');
            $table->string('image');
            $table->boolean('status')->default(1);
            $table->foreign('salon_service_id')->references('id')->on('salon_services');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salon_sub_services');
    }
};
