<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRentalFieldsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Champs pour la location
            $table->boolean('is_rentable')->default(false)->after('is_active'); // Le produit peut-il être loué ?
            $table->decimal('rental_price_per_day', 10, 2)->nullable()->after('price'); // Prix de location par jour
            $table->decimal('deposit_amount', 10, 2)->nullable()->after('rental_price_per_day'); // Montant de la caution
            $table->integer('min_rental_days')->default(1)->after('deposit_amount'); // Durée minimale de location
            $table->integer('max_rental_days')->default(365)->after('min_rental_days'); // Durée maximale de location
            $table->text('rental_conditions')->nullable()->after('max_rental_days'); // Conditions spéciales pour la location
            
            // Index pour améliorer les performances
            $table->index(['is_rentable', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['is_rentable', 'is_active']);
            $table->dropColumn([
                'is_rentable',
                'rental_price_per_day',
                'deposit_amount',
                'min_rental_days',
                'max_rental_days',
                'rental_conditions'
            ]);
        });
    }
}
