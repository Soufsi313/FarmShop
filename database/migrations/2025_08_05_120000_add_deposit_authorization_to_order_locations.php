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
            $table->string('stripe_deposit_authorization_id')->nullable()->after('stripe_payment_intent_id');
            $table->enum('deposit_status', [
                'none',           // Pas de caution
                'authorized',     // Préautorisée (bloquée)
                'captured',       // Capturée (débitée)
                'released'        // Libérée (remboursée)
            ])->default('none')->after('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_locations', function (Blueprint $table) {
            $table->dropColumn(['stripe_deposit_authorization_id', 'deposit_status']);
        });
    }
};
