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
        // Database: conversations
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users'); // អ្នកផ្ដើមឆាត (Customer)
            $table->foreignId('receiver_id')->constrained('users'); // អ្នកទទួល (Admin)
            $table->timestamps();
        });

        // Database: messages
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained(); // អ្នកផ្ញើសារក្នុងពេលនោះ
            $table->text('message')->nullable();
            $table->string('file_path')->nullable(); // សម្រាប់រក្សាទុកឈ្មោះរូបភាព
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
        Schema::dropIfExists('conversations');
    }
};
