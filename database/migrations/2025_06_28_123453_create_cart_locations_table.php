<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_locations', function (Blueprint $table) {
            $table->id();
            
            // Relations
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            // Informations du produit (pour conservation lors de suppression du produit)
            $table->string('product_name'); // Nom du produit au moment de l'ajout
            $table->string('product_category')->nullable(); // Catégorie du produit
            $table->text('product_description')->nullable(); // Description du produit
            $table->string('product_unit')->default('pièce'); // Unité de mesure
            
            // Informations de location
            $table->integer('quantity')->default(1); // Quantité en location
            $table->integer('rental_duration_days'); // Durée de location en jours
            $table->date('rental_start_date'); // Date de début de location
            $table->date('rental_end_date'); // Date de fin de location
            
            // Prix et calculs
            $table->decimal('unit_price_per_day', 10, 2); // Prix unitaire par jour
            $table->decimal('total_price', 10, 2); // Prix total calculé (quantité × durée × prix unitaire)
            $table->decimal('deposit_amount', 10, 2)->default(0); // Montant de la caution
            
            // Statut de la location
            $table->enum('status', ['pending', 'confirmed', 'active', 'returned', 'cancelled'])->default('pending');
            
            // Contraintes et index
            $table->unique(['user_id', 'product_id'], 'unique_user_product_location');
            $table->index(['user_id', 'status']);
            $table->index(['rental_start_date', 'rental_end_date']);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_locations');
    }
}
