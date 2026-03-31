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
        Schema::table('about_histories', function (Blueprint $table) {
            $table->dropColumn(['title_en', 'description_en']);
        });
    }

    public function down(): void
    {
        Schema::table('about_histories', function (Blueprint $table) {
            $table->string('title_en')->nullable();
            $table->text('description_en')->nullable();
        });
    }
};
