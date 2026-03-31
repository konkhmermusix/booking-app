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
        Schema::create('about_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // ឧទាហរណ៍: 'welcome_text', 'vision'
            $table->text('value_kh');
            $table->text('value_en')->nullable();
            $table->string('image')->nullable();
            $table->boolean('status')->default(true); // true = បង្ហាញ, false = លាក់
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_settings');
    }
};
