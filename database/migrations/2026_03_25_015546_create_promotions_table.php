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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            // ភ្ជាប់ទៅកាន់ Table room_types
            $table->foreignId('room_type_id')->constrained()->onDelete('cascade');

            $table->string('title');
            $table->string('tag')->nullable(); // ឧ៖ Summer Sale
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->decimal('original_price', 10, 2);
            $table->decimal('discounted_price', 10, 2);
            $table->date('expiry_date');
            $table->boolean('status')->default(true); // true = បង្ហាញ, false = លាក់
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
