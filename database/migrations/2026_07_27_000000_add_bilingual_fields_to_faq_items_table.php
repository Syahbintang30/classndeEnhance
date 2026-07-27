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
        Schema::table('faq_items', function (Blueprint $table) {
            $table->string('question_id')->nullable()->after('question');
            $table->text('answer_id')->nullable()->after('answer');
            $table->string('question_en')->nullable()->after('question_id');
            $table->text('answer_en')->nullable()->after('answer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faq_items', function (Blueprint $table) {
            $table->dropColumn(['question_id', 'answer_id', 'question_en', 'answer_en']);
        });
    }
};
