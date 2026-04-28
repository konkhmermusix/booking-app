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
        Schema::create('about_contents', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // ឧទាហរណ៍: welcome_text, vision, mission
            $table->string('title_kh')->nullable();
            $table->text('content_kh');
            $table->string('image')->nullable(); // សម្រាប់ទុក Path រូបភាព
            $table->boolean('status')->default(true); // true = បង្ហាញ, false = លាក់
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_contents');
    }
};
