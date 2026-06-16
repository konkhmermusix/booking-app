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
        Schema::table('users', function (Blueprint $table) {
            // បន្ថែម google_id នៅបន្ទាប់ពី email
            $table->string('google_id')->nullable()->after('email');

            // កែ password ឲ្យទៅជា Nullable (ព្រោះក្នុង Table ចាស់របស់បងវាឌីហ្សាញជា No Null)
            $table->string('password')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('google_id');
        });
    }
};
