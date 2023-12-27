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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('fname');
            $table->string('lname');
            $table->string('email')->unique();
            $table->string('address')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();

            $table->string('contact_no')->unique()->nullable();
            $table->string('password');
            $table->string('profile_photo', 2048)->nullable();
            $table->string('verification_code')->nullable();

            $table->boolean('have_salon')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('terms_and_conditions')->default(false);
            $table->unsignedBigInteger('user_level_id')->nullable();
            $table->foreign('user_level_id')->references('id')->on('user_levels');

            $table->softDeletes();
            $table->rememberToken();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
