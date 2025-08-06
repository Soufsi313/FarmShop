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
            $table->string('tracking_url')->nullable()->after('unsubscribe_token');
            $table->string('unsubscribe_url')->nullable()->after('tracking_url');
            $table->string('email')->nullable()->after('user_id'); // Ajout de l'email pour tracking
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('newsletter_sends', function (Blueprint $table) {
            $table->dropColumn([
                'tracking_url',
                'unsubscribe_url',
                'email'
            ]);
        });
    }
};
