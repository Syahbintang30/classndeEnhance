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
        Schema::table('coaching_bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('coaching_bookings', 'rescheduled_count')) {
                $table->unsignedInteger('rescheduled_count')->default(0)->after('notes');
            }
            if (! Schema::hasColumn('coaching_bookings', 'reschedule_reason')) {
                $table->text('reschedule_reason')->nullable()->after('rescheduled_count');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coaching_bookings', function (Blueprint $table) {
            if (Schema::hasColumn('coaching_bookings', 'rescheduled_count')) {
                $table->dropColumn('rescheduled_count');
            }
            if (Schema::hasColumn('coaching_bookings', 'reschedule_reason')) {
                $table->dropColumn('reschedule_reason');
            }
        });
    }
};
