<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('coaching_bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('coaching_bookings', 'session_duration_minutes')) {
                $table->unsignedInteger('session_duration_minutes')->nullable()->after('session_number');
            }
        });
    }

    public function down()
    {
        Schema::table('coaching_bookings', function (Blueprint $table) {
            if (Schema::hasColumn('coaching_bookings', 'session_duration_minutes')) {
                $table->dropColumn('session_duration_minutes');
            }
        });
    }
};
