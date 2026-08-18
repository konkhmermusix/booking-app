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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            $table->foreignId('room_type_id')
                ->constrained('room_types')
                ->onDelete('cascade'); // ប្រសិនបើ room type លុប -> review លុបផង

            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('parent_id')->nullable()->constrained('reviews')->onDelete('cascade');
            $table->string('name');       // ឈ្មោះអ្នកអតិថិជន
            $table->tinyInteger('rating')->default(5); // Rating 1-5
            $table->text('comment')->nullable(); // ព័ត៌មានមតិយោបល់
            $table->tinyInteger('status')->default(1); // 1 = show, 0 = hide

            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
