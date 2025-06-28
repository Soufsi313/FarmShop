<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Numéro de commande unique
            $table->string('order_number')->unique();
            
            // Statuts de commande
            $table->enum('status', [
                'pending',      // En attente
                'confirmed',    // Confirmée
                'preparation', // En préparation
                'shipped',     // Expédiée
                'delivered',   // Livrée
                'cancelled',   // Annulée
                'returned'     // Retournée
            ])->default('pending');
            
            // Dates importantes
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('preparation_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            
            // Montants
            $table->decimal('subtotal', 10, 2); // Sous-total
            $table->decimal('tax_amount', 10, 2)->default(0); // TVA
            $table->decimal('shipping_cost', 10, 2)->default(0); // Frais de livraison
            $table->decimal('discount_amount', 10, 2)->default(0); // Remise
            $table->decimal('total_amount', 10, 2); // Total final
            
            // Informations de livraison
            $table->string('shipping_method')->nullable(); // Méthode de livraison
            $table->string('tracking_number')->nullable(); // Numéro de suivi
            $table->json('shipping_address'); // Adresse de livraison
            $table->json('billing_address'); // Adresse de facturation
            
            // Informations de paiement
            $table->string('payment_method')->nullable(); // Méthode de paiement
            $table->string('payment_status')->default('pending'); // pending, paid, failed, refunded
            $table->string('payment_transaction_id')->nullable(); // ID transaction paiement
            $table->timestamp('paid_at')->nullable();
            
            // Coupon appliqué
            $table->string('coupon_code')->nullable();
            $table->decimal('coupon_discount', 10, 2)->default(0);
            
            // Retours
            $table->boolean('is_returnable')->default(true);
            $table->date('return_deadline')->nullable(); // Date limite de retour (14 jours)
            
            // Notes
            $table->text('notes')->nullable(); // Notes client
            $table->text('admin_notes')->nullable(); // Notes admin
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Index
            $table->index(['user_id', 'status']);
            $table->index(['status', 'created_at']);
            $table->index('order_number');
            $table->index('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
