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

            $table->string('name');       // ឈ្មោះអ្នកអតិថិជន
            $table->tinyInteger('rating'); // Rating 1-5
            $table->text('comment')->nullable(); // ព័ត៌មានមតិយោបល់

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
