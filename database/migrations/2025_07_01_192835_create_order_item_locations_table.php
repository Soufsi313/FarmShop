<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_item_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_location_id')->constrained()->onDelete('cascade'); // Commande parente
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Produit loué
            $table->foreignId('cart_item_location_id')->nullable()->constrained()->onDelete('set null'); // Item du panier d'origine
            
            // Détails de l'article de location
            $table->string('product_name'); // Nom du produit au moment de la commande
            $table->text('product_description')->nullable(); // Description au moment de la commande
            $table->decimal('rental_price_per_day', 8, 2); // Prix par jour au moment de la commande
            $table->decimal('deposit_amount', 8, 2)->default(0); // Caution au moment de la commande
            
            // Dates spécifiques à cet article
            $table->date('rental_start_date'); // Date de début de location
            $table->date('rental_end_date');   // Date de fin de location
            $table->integer('duration_days');  // Nombre de jours calculé
            
            // Montants calculés
            $table->decimal('subtotal', 10, 2); // Sous-total (prix_par_jour * durée)
            $table->decimal('total_with_deposit', 10, 2); // Total avec caution
            
            // État de l'article
            $table->enum('condition_at_pickup', ['excellent', 'good', 'fair', 'poor'])->nullable();
            $table->enum('condition_at_return', ['excellent', 'good', 'fair', 'poor'])->nullable();
            $table->text('pickup_notes')->nullable(); // Notes lors de la récupération
            $table->text('return_notes')->nullable(); // Notes lors du retour
            
            // Pénalités spécifiques à cet article
            $table->decimal('damage_fee', 8, 2)->default(0); // Frais de dégâts
            $table->decimal('late_fee', 8, 2)->default(0);   // Frais de retard
            
            $table->timestamps();
            
            // Index pour optimiser les requêtes
            $table->index(['order_location_id', 'product_id']);
            $table->index(['rental_start_date', 'rental_end_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_item_locations');
    }
}
