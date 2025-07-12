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
        Schema::create('order_item_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_location_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            // Informations du produit au moment de la commande
            $table->string('product_name');
            $table->string('product_sku')->nullable();
            $table->text('product_description')->nullable();
            
            // Informations de location
            $table->integer('quantity');
            $table->decimal('daily_rate', 8, 2);
            $table->integer('rental_days');
            $table->decimal('deposit_per_item', 8, 2);
            
            // Calculs
            $table->decimal('subtotal', 10, 2); // daily_rate * quantity * rental_days
            $table->decimal('total_deposit', 10, 2); // deposit_per_item * quantity
            $table->decimal('tax_amount', 8, 2);
            $table->decimal('total_amount', 10, 2);
            
            // Gestion de l'état des articles
            $table->enum('condition_at_pickup', [
                'excellent',
                'good', 
                'fair',
                'poor'
            ])->default('excellent');
            
            $table->enum('condition_at_return', [
                'excellent',
                'good',
                'fair', 
                'poor'
            ])->nullable();
            
            // Inspection individuelle des articles
            $table->decimal('item_damage_cost', 8, 2)->default(0);
            $table->text('item_inspection_notes')->nullable();
            $table->json('damage_details')->nullable(); // Photos, descriptions détaillées
            
            // Suivi des retards pour cet article
            $table->integer('item_late_days')->default(0);
            $table->decimal('item_late_fees', 8, 2)->default(0);
            
            // Remboursement de caution pour cet article
            $table->decimal('item_deposit_refund', 8, 2)->default(0);
            
            $table->timestamps();
            
            // Index pour les performances
            $table->index(['order_location_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_item_locations');
    }
};
