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
        Schema::create('salon_banner_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('salon_id');
            $table->string('path');
            $table->foreign('salon_id')->references('id')->on('salons');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salon_banner_images');
    }
};
