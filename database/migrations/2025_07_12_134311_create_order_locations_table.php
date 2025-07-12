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
        Schema::create('order_locations', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Informations de location
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('rental_days');
            $table->decimal('daily_rate', 10, 2);
            $table->decimal('total_rental_cost', 10, 2);
            $table->decimal('deposit_amount', 10, 2);
            $table->decimal('late_fee_per_day', 8, 2)->default(10.00);
            $table->decimal('tax_rate', 5, 2)->default(21.00); // TVA 21%
            
            // Calculs financiers
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax_amount', 10, 2);
            $table->decimal('total_amount', 10, 2);
            
            // Statuts
            $table->enum('status', [
                'pending',           // En attente
                'confirmed',         // Confirmée
                'active',           // En cours
                'completed',        // Terminée (date de fin atteinte)
                'closed',           // Clôturée par le client
                'inspecting',       // En cours d'inspection
                'finished',         // Terminée avec inspection
                'cancelled'         // Annulée
            ])->default('pending');
            
            $table->enum('payment_status', [
                'pending',
                'deposit_paid',     // Caution payée
                'paid',            // Tout payé
                'failed',
                'refunded',        // Caution remboursée
                'partial_refund'   // Caution partiellement remboursée
            ])->default('pending');
            
            // Informations de paiement
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            
            // Adresses
            $table->json('billing_address');
            $table->json('delivery_address');
            
            // Gestion des retards
            $table->integer('late_days')->default(0);
            $table->decimal('late_fees', 10, 2)->default(0);
            $table->timestamp('actual_return_date')->nullable();
            
            // Inspection
            $table->enum('inspection_status', [
                'pending',
                'in_progress', 
                'completed'
            ])->nullable();
            $table->enum('product_condition', [
                'excellent',    // Très bon état
                'good',        // Bon état  
                'poor'         // Mauvais état
            ])->nullable();
            $table->decimal('damage_cost', 8, 2)->default(0);
            $table->decimal('total_penalties', 8, 2)->default(0);
            $table->decimal('deposit_refund', 8, 2)->default(0);
            $table->text('inspection_notes')->nullable();
            $table->timestamp('inspection_completed_at')->nullable();
            $table->foreignId('inspected_by')->nullable()->constrained('users');
            
            // Notes et raisons
            $table->text('notes')->nullable();
            $table->text('cancellation_reason')->nullable();
            
            // Timestamps des changements de statut
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Index pour les performances
            $table->index(['user_id', 'status']);
            $table->index(['start_date', 'end_date']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_locations');
    }
};
