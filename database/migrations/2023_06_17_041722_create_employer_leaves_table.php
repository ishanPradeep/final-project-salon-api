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
        Schema::create('employer_leaves', function (Blueprint $table) {
            $table->id();
            $table->string('date');
            $table->boolean('full_day');
            $table->string('from_time');
            $table->string('to_time');
            $table->text('reason')->nullable();;
            $table->unsignedBigInteger('employer_id');
            $table->boolean('status')->default(false);
            $table->foreign('employer_id')->references('id')->on('employers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employer_leaves');
    }
};
