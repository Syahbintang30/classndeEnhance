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
            if (! Schema::hasColumn('coaching_bookings', 'review_video_url')) {
                $table->string('review_video_url')->nullable()->after('admin_note');
            }
            if (! Schema::hasColumn('coaching_bookings', 'review_title')) {
                $table->string('review_title')->nullable()->after('review_video_url');
            }
            if (! Schema::hasColumn('coaching_bookings', 'review_tag')) {
                $table->string('review_tag')->nullable()->after('review_title');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coaching_bookings', function (Blueprint $table) {
            if (Schema::hasColumn('coaching_bookings', 'review_video_url')) {
                $table->dropColumn(['review_video_url', 'review_title', 'review_tag']);
            }
        });
    }
};
