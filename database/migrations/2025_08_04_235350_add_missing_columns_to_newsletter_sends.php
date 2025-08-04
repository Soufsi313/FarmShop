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
        Schema::table('newsletter_sends', function (Blueprint $table) {
            // Vérifier si les colonnes n'existent pas déjà avant de les ajouter
            if (!Schema::hasColumn('newsletter_sends', 'email')) {
                $table->string('email')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('newsletter_sends', 'tracking_url')) {
                $table->string('tracking_url')->nullable()->after('unsubscribe_token');
            }
            if (!Schema::hasColumn('newsletter_sends', 'unsubscribe_url')) {
                $table->string('unsubscribe_url')->nullable()->after('tracking_url');
            }
            if (!Schema::hasColumn('newsletter_sends', 'error_message')) {
                $table->text('error_message')->nullable()->after('failure_reason');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('newsletter_sends', function (Blueprint $table) {
            $table->dropColumn(['email', 'tracking_url', 'unsubscribe_url', 'error_message']);
        });
    }
};
