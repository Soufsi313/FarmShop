<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStripeFieldsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Colonnes pour l'intégration Stripe
            $table->string('payment_intent_id')->nullable();
            $table->string('stripe_customer_id')->nullable();
            $table->boolean('payment_confirmed')->default(false);
            $table->text('payment_error')->nullable();
            
            // Index pour optimiser les recherches
            $table->index('payment_intent_id');
            $table->index('stripe_customer_id');
            $table->index('payment_confirmed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['payment_intent_id']);
            $table->dropIndex(['stripe_customer_id']);
            $table->dropIndex(['payment_confirmed']);
            
            $table->dropColumn([
                'payment_intent_id',
                'stripe_customer_id',
                'payment_confirmed',
                'payment_error'
            ]);
        });
    }
}
