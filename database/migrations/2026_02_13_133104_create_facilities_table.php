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
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // ឧទាហរណ៍៖ Wi-Fi, Air Conditioning, Swimming Pool
            $table->string('icon')->nullable(); // ឧទាហរណ៍៖ fas fa-wifi, fas fa-snowflake
            $table->enum('type', ['room', 'hotel'])->default('room'); // បែងចែកជាសម្ភារៈក្នុងបន្ទប់ ឬរបស់សណ្ឋាគារ
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};
