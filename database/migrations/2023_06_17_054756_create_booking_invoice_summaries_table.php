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
        Schema::create('booking_invoice_summaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_method_id');
            $table->unsignedBigInteger('invoice_id')->nullable();


            $table->string('date');
            $table->string('time');
            $table->string('account_number')->nullable();
            $table->text('discription')->nullable();

            $table->string('total_amount');
            $table->string('paid_amount');
            $table->string('payment_status');

            $table->foreign('payment_method_id')->references('id')->on('payment_methods');
            $table->foreign('invoice_id')->references('id')->on('booking_invoices');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_invoice_summaries');
    }
};
