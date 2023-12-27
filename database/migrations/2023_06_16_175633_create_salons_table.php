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
        Schema::create('salons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('salon_type_id');
            $table->string('name');
            $table->text('description');
            $table->string('email')->unique();
            $table->string('contact_number');
            $table->string('address');
            $table->double('latitude');
            $table->double('longitude');
            $table->boolean('publish')->default(true);
            $table->string('salon_logo', 2048)->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('salon_type_id')->references('id')->on('salon_types');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
