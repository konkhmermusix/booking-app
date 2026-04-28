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
        Schema::create('hotel_histories', function (Blueprint $table) {
            $table->id();
            $table->string('year'); // ឧទាហរណ៍: 2023, 2025, បច្ចុប្បន្ន
            $table->string('title_kh');
            $table->text('description_kh');
            $table->integer('order_priority')->default(0); // សម្រាប់រៀបលំដាប់លំដោយ
            $table->boolean('status')->default(true); // true = បង្ហាញ, false = លាក់
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_histories');
    }
};
