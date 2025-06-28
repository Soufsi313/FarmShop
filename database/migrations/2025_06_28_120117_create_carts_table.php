<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2); // Prix unitaire au moment de l'ajout
            $table->decimal('total_price', 10, 2); // Prix total (quantity * unit_price)
            $table->timestamps();

            // Index pour les performances
            $table->index(['user_id', 'product_id']);
            
            // Contrainte unique : un produit ne peut être dans le panier que d'un seul user à la fois
            $table->unique('product_id', 'unique_product_in_cart');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
}
