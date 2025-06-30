<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->unsigned()->default(1);
            $table->decimal('unit_price', 10, 2); // Prix unitaire au moment de l'ajout
            $table->decimal('total_price', 10, 2)->storedAs('quantity * unit_price'); // Prix total calculé
            $table->timestamps();

            // Index pour optimiser les requêtes
            $table->index(['cart_id', 'product_id']);
            
            // Un produit ne peut être présent qu'une fois par panier
            $table->unique(['cart_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_items');
    }
}
