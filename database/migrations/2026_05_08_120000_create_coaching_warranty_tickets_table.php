<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('coaching_warranty_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('booking_id')->nullable()->constrained('coaching_bookings')->nullOnDelete();
            $table->foreignId('ticket_id')->nullable()->constrained('coaching_tickets')->nullOnDelete();
            $table->unsignedInteger('downtime_minutes')->nullable();
            $table->unsignedInteger('warranty_minutes')->nullable();
            $table->string('status')->default('available');
            $table->string('source')->default('auto');
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('coaching_warranty_tickets');
    }
};
