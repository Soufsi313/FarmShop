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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            // Informations du produit au moment de l'ajout (pour éviter les changements de prix)
            $table->string('product_name');
            $table->string('product_category');
            $table->decimal('unit_price', 10, 2); // Prix unitaire HT
            $table->integer('quantity');
            $table->decimal('subtotal', 10, 2); // Sous-total HT (unit_price * quantity)
            $table->decimal('tax_rate', 5, 2)->default(20.00); // Taux de TVA
            $table->decimal('tax_amount', 10, 2); // Montant TVA
            $table->decimal('total', 10, 2); // Total TTC
            
            // Métadonnées du produit
            $table->json('product_metadata')->nullable(); // Données supplémentaires (images, description, etc.)
            
            $table->timestamps();
            
            // Un produit ne peut être qu'une seule fois dans le même panier
            $table->unique(['cart_id', 'product_id']);
            
            // Index pour optimiser les requêtes
            $table->index(['cart_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
