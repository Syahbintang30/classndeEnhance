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
        Schema::table('packages', function (Blueprint $table) {
            if (! Schema::hasColumn('packages', 'member_price')) {
                $table->unsignedInteger('member_price')->nullable()->after('price');
            }

            if (! Schema::hasColumn('packages', 'non_member_price')) {
                $table->unsignedInteger('non_member_price')->nullable()->after('member_price');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            if (Schema::hasColumn('packages', 'non_member_price')) {
                $table->dropColumn('non_member_price');
            }

            if (Schema::hasColumn('packages', 'member_price')) {
                $table->dropColumn('member_price');
            }
        });
    }
};
