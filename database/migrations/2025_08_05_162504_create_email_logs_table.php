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
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->string('email_type'); // ex: 'rental_completed'
            $table->unsignedBigInteger('order_location_id');
            $table->string('recipient_email');
            $table->enum('status', ['sent', 'failed'])->default('sent');
            $table->text('details')->nullable();
            $table->timestamps();
            
            // Index pour éviter les doublons
            $table->unique(['email_type', 'order_location_id', 'created_at'], 'unique_email_per_order_per_day');
            $table->index(['order_location_id', 'email_type', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};
