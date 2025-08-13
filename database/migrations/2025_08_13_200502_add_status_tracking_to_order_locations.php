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
        Schema::table('order_locations', function (Blueprint $table) {
            // Colonnes pour le suivi des statuts automatiques
            $table->timestamp('reminder_sent_at')->nullable()->after('started_at')->comment('Date d\'envoi du rappel 24h avant fin');
            $table->timestamp('ended_at')->nullable()->after('reminder_sent_at')->comment('Date de fin de location');
            $table->timestamp('overdue_notification_sent_at')->nullable()->after('ended_at')->comment('Date d\'envoi notification retard');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_locations', function (Blueprint $table) {
            $table->dropColumn([
                'reminder_sent_at',
                'ended_at', 
                'overdue_notification_sent_at'
            ]);
        });
    }
};
