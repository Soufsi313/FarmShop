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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            
            // Référence et utilisateur
            $table->string('order_number')->unique(); // Numéro de commande unique
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            
            // Statut de la commande
            $table->enum('status', ['pending', 'confirmed', 'preparing', 'shipped', 'delivered', 'cancelled', 'returned'])
                ->default('pending');
            $table->text('status_history')->nullable(); // Historique des changements de statut
            $table->timestamp('status_updated_at')->nullable(); // Dernière mise à jour du statut
            
            // Adresses récupérées du profil utilisateur
            $table->json('billing_address'); // Adresse de facturation
            $table->json('shipping_address'); // Adresse de livraison
            
            // Montants et calculs
            $table->decimal('subtotal', 10, 2); // Sous-total
            $table->decimal('tax_amount', 10, 2)->default(0); // Montant des taxes
            $table->decimal('shipping_cost', 10, 2)->default(0); // Frais de livraison
            $table->decimal('discount_amount', 10, 2)->default(0); // Remise appliquée
            $table->decimal('total_amount', 10, 2); // Montant total
            
            // Informations de paiement
            $table->string('payment_method')->nullable(); // Méthode de paiement
            $table->string('payment_status')->default('pending'); // Statut du paiement
            $table->string('payment_transaction_id')->nullable(); // ID de transaction
            $table->timestamp('paid_at')->nullable(); // Date de paiement
            
            // Livraison
            $table->string('shipping_method')->nullable(); // Méthode de livraison
            $table->string('tracking_number')->nullable(); // Numéro de suivi
            $table->timestamp('shipped_at')->nullable(); // Date d'expédition
            $table->timestamp('delivered_at')->nullable(); // Date de livraison
            $table->timestamp('estimated_delivery')->nullable(); // Livraison estimée
            
            // Annulation et retour
            $table->boolean('can_be_cancelled')->default(true); // Peut être annulée
            $table->boolean('can_be_returned')->default(false); // Peut être retournée
            $table->timestamp('cancelled_at')->nullable(); // Date d'annulation
            $table->text('cancellation_reason')->nullable(); // Raison de l'annulation
            
            // Gestion des retours
            $table->boolean('has_returnable_items')->default(false); // Contient des produits retournables
            $table->timestamp('return_deadline')->nullable(); // Date limite de retour (14 jours)
            
            // Facturation
            $table->string('invoice_number')->nullable()->unique(); // Numéro de facture
            $table->timestamp('invoice_generated_at')->nullable(); // Date de génération facture
            
            // Notifications
            $table->json('email_notifications_sent')->nullable(); // Historique des emails envoyés
            $table->timestamp('last_notification_sent_at')->nullable();
            
            // Métadonnées
            $table->json('metadata')->nullable(); // Données supplémentaires
            $table->text('notes')->nullable(); // Notes administratives
            
            $table->timestamps();
            $table->softDeletes(); // Soft delete
            
            // Index pour les performances
            $table->index(['user_id', 'status']);
            $table->index(['status', 'created_at']);
            $table->index('order_number');
            $table->index('payment_status');
            $table->index('shipped_at');
            $table->index('delivered_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
