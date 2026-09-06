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
        if (Schema::hasTable('hotel_bookings') && !Schema::hasColumn('hotel_bookings', 'confirmed_by_name')) {
            Schema::table('hotel_bookings', function (Blueprint $table) {
                $table->string('confirmed_by_name')->nullable()->after('status');
                $table->foreignId('confirmed_by_user_id')->nullable()->after('confirmed_by_name')->constrained('users')->onDelete('set null');
            });
        }

        if (Schema::hasTable('meeting_bookings') && !Schema::hasColumn('meeting_bookings', 'confirmed_by_name')) {
            Schema::table('meeting_bookings', function (Blueprint $table) {
                $table->string('confirmed_by_name')->nullable()->after('status');
                $table->foreignId('confirmed_by_user_id')->nullable()->after('confirmed_by_name')->constrained('users')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('hotel_bookings') && Schema::hasColumn('hotel_bookings', 'confirmed_by_name')) {
            Schema::table('hotel_bookings', function (Blueprint $table) {
                $table->dropForeign(['confirmed_by_user_id']);
                $table->dropColumn(['confirmed_by_name', 'confirmed_by_user_id']);
            });
        }

        if (Schema::hasTable('meeting_bookings') && Schema::hasColumn('meeting_bookings', 'confirmed_by_name')) {
            Schema::table('meeting_bookings', function (Blueprint $table) {
                $table->dropForeign(['confirmed_by_user_id']);
                $table->dropColumn(['confirmed_by_name', 'confirmed_by_user_id']);
            });
        }
    }
};
