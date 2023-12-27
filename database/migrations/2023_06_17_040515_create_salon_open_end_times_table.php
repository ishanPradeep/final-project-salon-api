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
        Schema::create('salon_open_end_times', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('day_id');
            $table->unsignedBigInteger('salon_id');
            $table->string('from_time');
            $table->string('to_time');
            $table->boolean('is_open')->default(false);
            $table->foreign('salon_id')->references('id')->on('salons');
            $table->foreign('day_id')->references('id')->on('days');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spa_type_open_end_times');
    }
};
