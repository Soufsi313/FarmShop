<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRentalItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rental_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            // Quantité et prix
            $table->integer('quantity');
            $table->decimal('rental_price_per_day', 8, 2);
            $table->decimal('deposit_amount_per_item', 8, 2);
            $table->decimal('total_rental_amount', 10, 2);
            $table->decimal('total_deposit_amount', 10, 2);
            
            // État du produit
            $table->enum('condition_at_pickup', ['excellent', 'good', 'fair', 'poor'])->nullable();
            $table->enum('condition_at_return', ['excellent', 'good', 'fair', 'poor'])->nullable();
            $table->text('damage_notes')->nullable();
            
            // Statut de retour
            $table->enum('return_status', [
                'not_returned',     // Pas encore retourné
                'partial_returned', // Partiellement retourné
                'fully_returned',   // Complètement retourné
                'damaged_returned', // Retourné avec dommages
                'lost'              // Perdu
            ])->default('not_returned');
            
            $table->integer('returned_quantity')->default(0);
            $table->timestamp('returned_at')->nullable();
            
            $table->timestamps();
            
            // Index
            $table->index(['rental_id', 'product_id']);
            $table->index('return_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rental_items');
    }
}
