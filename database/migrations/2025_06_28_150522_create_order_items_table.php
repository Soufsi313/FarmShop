<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            // Informations du produit au moment de la commande (snapshot)
            $table->string('product_name');
            $table->text('product_description')->nullable();
            $table->string('product_sku')->nullable();
            $table->boolean('is_perishable')->default(false); // Périssable ou non
            $table->boolean('is_returnable')->default(true); // Retournable ou non
            
            // Quantité et prix
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2); // Prix unitaire au moment de la commande
            $table->decimal('total_price', 10, 2); // Prix total (quantité × prix unitaire)
            
            // Retours pour cet article
            $table->integer('returned_quantity')->default(0);
            $table->decimal('refunded_amount', 10, 2)->default(0);
            
            // Status spécifique à l'article
            $table->enum('status', [
                'pending',
                'confirmed', 
                'preparation',
                'shipped',
                'delivered',
                'returned',
                'cancelled'
            ])->default('pending');
            
            $table->timestamps();
            
            // Index
            $table->index(['order_id', 'product_id']);
            $table->index(['product_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
