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
        Schema::create('order_returns', function (Blueprint $table) {
            $table->id();
            
            // Relations
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('order_item_id')->constrained('order_items')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            
            // Informations du retour
            $table->string('return_number')->unique(); // Numéro de retour unique
            $table->integer('quantity_returned'); // Quantité retournée
            $table->enum('reason', [
                'defective',
                'wrong_item',
                'not_as_described',
                'changed_mind',
                'damaged_shipping',
                'other'
            ]); // Raison du retour
            $table->text('description')->nullable(); // Description détaillée
            $table->json('images')->nullable(); // Photos du produit retourné
            
            // Statut du retour
            $table->enum('status', [
                'requested',
                'approved',
                'rejected',
                'item_received',
                'inspected',
                'refunded',
                'cancelled'
            ])->default('requested');
            $table->text('status_history')->nullable(); // Historique des statuts
            $table->timestamp('status_updated_at')->nullable();
            
            // Dates importantes
            $table->timestamp('requested_at'); // Date de demande
            $table->timestamp('approved_at')->nullable(); // Date d'approbation
            $table->timestamp('item_received_at')->nullable(); // Date de réception du produit
            $table->timestamp('inspected_at')->nullable(); // Date d'inspection
            $table->timestamp('refunded_at')->nullable(); // Date de remboursement
            
            // Remboursement
            $table->decimal('refund_amount', 10, 2); // Montant à rembourser
            $table->string('refund_method')->nullable(); // Méthode de remboursement
            $table->string('refund_transaction_id')->nullable(); // ID de transaction de remboursement
            $table->boolean('refund_processed')->default(false); // Remboursement traité
            
            // Adresse de retour
            $table->json('return_shipping_address')->nullable(); // Adresse de retour
            $table->string('return_tracking_number')->nullable(); // Numéro de suivi retour
            $table->decimal('return_shipping_cost', 10, 2)->default(0); // Frais de retour
            
            // Inspection
            $table->enum('inspection_result', ['approved', 'rejected', 'partial'])->nullable();
            $table->text('inspection_notes')->nullable(); // Notes d'inspection
            $table->json('inspection_images')->nullable(); // Photos d'inspection
            $table->foreignId('inspected_by')->nullable()->constrained('users')->nullOnDelete();
            
            // Validation administrative
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('admin_notes')->nullable(); // Notes administratives
            $table->text('rejection_reason')->nullable(); // Raison du rejet
            
            // Notifications
            $table->json('email_notifications_sent')->nullable(); // Emails envoyés
            $table->timestamp('last_notification_sent_at')->nullable();
            
            // Métadonnées
            $table->json('metadata')->nullable();
            
            $table->timestamps();
            $table->softDeletes(); // Soft delete
            
            // Index pour les performances
            $table->index(['order_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index('return_number');
            $table->index(['status', 'requested_at']);
            $table->index('refund_processed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_returns');
    }
};
