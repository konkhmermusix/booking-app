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
        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('hotel_booking_id')->nullable()->index();
                $table->unsignedBigInteger('meeting_booking_id')->nullable()->index();
                $table->enum('method', ['cash', 'qr']);
                $table->decimal('amount', 10, 2);
                $table->string('currency', 10)->nullable()->default('USD');
                $table->string('transaction_id', 100)->nullable();
                $table->string('payment_slip', 255)->nullable();
                $table->enum('status', ['pending', 'paid', 'failed', 'refunded'])->nullable()->default('pending');
                $table->timestamp('paid_at')->nullable();
                $table->timestamps();

                // Foreign key constraints
                $table->foreign('hotel_booking_id')->references('id')->on('hotel_bookings')->onDelete('cascade');
                $table->foreign('meeting_booking_id')->references('id')->on('meeting_bookings')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
