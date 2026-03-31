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
        Schema::create('about_histories', function (Blueprint $table) {
            $table->id();
            $table->string('year'); // ឧទាហរណ៍: 2023, 2025
            $table->string('title_kh');
            $table->string('title_en')->nullable();
            $table->text('description_kh');
            $table->text('description_en')->nullable();
            $table->integer('sort_order')->default(0); // សម្រាប់រៀបលំដាប់លំដោយ
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_histories');
    }
};
