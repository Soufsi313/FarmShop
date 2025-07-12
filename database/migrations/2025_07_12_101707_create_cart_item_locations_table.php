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
        Schema::create('cart_item_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_location_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            // Informations de location
            $table->date('start_date'); // Date de début de location
            $table->date('end_date'); // Date de fin de location
            $table->integer('duration_days'); // Durée en jours (calculée)
            $table->integer('quantity')->default(1); // Quantité louée
            
            // Prix et calculs
            $table->decimal('unit_price_per_day', 8, 2); // Prix unitaire par jour
            $table->decimal('unit_deposit', 8, 2)->default(0); // Caution unitaire
            $table->decimal('subtotal_amount', 10, 2); // Sous-total HT (prix * quantité * durée)
            $table->decimal('subtotal_deposit', 10, 2); // Sous-total caution (caution * quantité)
            $table->decimal('tva_amount', 10, 2); // TVA sur le montant
            $table->decimal('total_amount', 10, 2); // Total TTC ligne
            
            // Informations produit (snapshot au moment de l'ajout)
            $table->string('product_name'); // Nom du produit
            $table->string('product_sku')->nullable(); // SKU du produit
            $table->string('rental_category_name')->nullable(); // Nom de la catégorie de location
            
            // Notes et métadonnées
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            
            $table->timestamps();
            
            // Contraintes et index
            $table->unique(['cart_location_id', 'product_id'], 'unique_product_per_cart_location');
            $table->index('cart_location_id');
            $table->index('product_id');
            $table->index(['start_date', 'end_date']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_item_locations');
    }
};
