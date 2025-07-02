<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('special_offers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nom de l'offre
            $table->text('description')->nullable(); // Description de l'offre
            $table->unsignedBigInteger('product_id'); // Produit ciblé
            $table->integer('min_quantity'); // Quantité minimale requise
            $table->decimal('discount_percentage', 5, 2); // Pourcentage de remise (ex: 75.00 pour 75%)
            $table->datetime('start_date'); // Date de début
            $table->datetime('end_date'); // Date de fin
            $table->boolean('is_active')->default(true); // Actif/inactif
            $table->timestamps();
            
            // Index et contraintes
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->index(['product_id', 'is_active']);
            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('special_offers');
    }
}
