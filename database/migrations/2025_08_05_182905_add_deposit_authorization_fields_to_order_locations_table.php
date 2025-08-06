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
            // ID de la préautorisation Stripe pour la caution
            $table->string('stripe_deposit_authorization_id')->nullable()->after('stripe_payment_intent_id');
            
            // Statut de la caution : authorized, captured, cancelled
            $table->enum('deposit_status', ['authorized', 'captured', 'cancelled'])->nullable()->after('stripe_deposit_authorization_id');
            
            // Montant capturé si différent du montant autorisé
            $table->decimal('deposit_captured_amount', 10, 2)->nullable()->after('deposit_status');
            
            // Horodatage de capture
            $table->timestamp('deposit_captured_at')->nullable()->after('deposit_captured_amount');
            
            // Horodatage d'annulation
            $table->timestamp('deposit_cancelled_at')->nullable()->after('deposit_captured_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_locations', function (Blueprint $table) {
            $table->dropColumn([
                'stripe_deposit_authorization_id',
                'deposit_status',
                'deposit_captured_amount',
                'deposit_captured_at',
                'deposit_cancelled_at'
            ]);
        });
    }
};
