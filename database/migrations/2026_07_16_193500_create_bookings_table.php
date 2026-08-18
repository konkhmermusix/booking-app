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
        if (!Schema::hasTable('bookings')) {
            Schema::create('bookings', function (Blueprint $table) {
                $table->id();
                $table->string('booking_code')->unique();
                $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
                $table->date('check_in_date');
                $table->date('check_out_date');
                $table->integer('number_of_guests');
                $table->decimal('total_price', 10, 2);
                $table->string('payment_status')->default('pending'); // pending, paid, failed
                $table->string('booking_status')->default('pending'); // pending, confirmed, completed, cancelled
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
