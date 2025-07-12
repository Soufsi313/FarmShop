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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            
            // Relations
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            
            // Informations du produit au moment de la commande
            $table->string('product_name'); // Nom du produit au moment de l'achat
            $table->string('product_sku')->nullable(); // SKU du produit
            $table->text('product_description')->nullable(); // Description du produit
            $table->string('product_image')->nullable(); // Image du produit
            $table->json('product_category')->nullable(); // Catégorie du produit (pour retours)
            
            // Quantité et prix
            $table->integer('quantity'); // Quantité commandée
            $table->decimal('unit_price', 10, 2); // Prix unitaire au moment de l'achat
            $table->decimal('total_price', 10, 2); // Prix total de la ligne
            
            // Statut spécifique à l'item
            $table->enum('status', ['pending', 'confirmed', 'preparing', 'shipped', 'delivered', 'cancelled', 'returned'])
                ->default('pending');
            $table->timestamp('status_updated_at')->nullable();
            
            // Gestion des retours
            $table->boolean('is_returnable')->default(false); // Basé sur la catégorie du produit
            $table->boolean('is_returned')->default(false); // Item retourné
            $table->integer('returned_quantity')->default(0); // Quantité retournée
            $table->timestamp('return_deadline')->nullable(); // Date limite de retour
            
            // Livraison spécifique à l'item
            $table->string('tracking_number')->nullable(); // Numéro de suivi spécifique
            $table->timestamp('shipped_at')->nullable(); // Date d'expédition
            $table->timestamp('delivered_at')->nullable(); // Date de livraison
            
            // Annulation
            $table->boolean('can_be_cancelled')->default(true);
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            
            // Métadonnées
            $table->json('metadata')->nullable(); // Données supplémentaires
            
            $table->timestamps();
            $table->softDeletes(); // Soft delete
            
            // Index pour les performances
            $table->index(['order_id', 'status']);
            $table->index(['product_id']);
            $table->index('is_returnable');
            $table->index('is_returned');
            $table->index('shipped_at');
            $table->index('delivered_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
