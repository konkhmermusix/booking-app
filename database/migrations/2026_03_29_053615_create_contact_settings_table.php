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
        Schema::create('contact_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();   // សម្រាប់សម្គាល់ដូចជា: 'phone', 'map_url'
            $table->string('label');            // សម្រាប់បង្ហាញចំណងជើង
            $table->text('value');              // សម្រាប់រក្សាទុកតម្លៃ (Link map វែងខ្លាំង)
            $table->string('icon')->nullable(); // សម្រាប់ FontAwesome icon
            $table->string('color')->nullable(); // សម្រាប់ពណ៌ (blue, red, green...)
            // បន្ថែម status ដើម្បីគ្រប់គ្រងការបង្ហាញ (Default គឺ true/បង្ហាញ)
            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_settings');
    }
};
