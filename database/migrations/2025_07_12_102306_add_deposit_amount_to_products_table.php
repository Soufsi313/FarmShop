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
        Schema::table('products', function (Blueprint $table) {
            // Ajout des champs pour la location
            $table->decimal('rental_price_per_day', 8, 2)->nullable()->after('price')
                  ->comment('Prix de location par jour');
            $table->decimal('deposit_amount', 8, 2)->nullable()->after('rental_price_per_day')
                  ->comment('Montant de la caution pour la location');
            $table->enum('type', ['sale', 'rental', 'both'])->default('sale')->after('deposit_amount')
                  ->comment('Type de produit: vente, location ou les deux');
            $table->foreignId('rental_category_id')->nullable()->after('category_id')
                  ->constrained('rental_categories')->onDelete('set null')
                  ->comment('Catégorie pour la location');
            
            // Ajout des champs manquants pour une meilleure gestion
            $table->string('sku')->nullable()->unique()->after('slug')
                  ->comment('Code SKU du produit');
            $table->string('short_description', 500)->nullable()->after('description')
                  ->comment('Description courte du produit');
            $table->decimal('weight', 8, 3)->nullable()->after('type')
                  ->comment('Poids du produit en kg');
            $table->string('dimensions')->nullable()->after('weight')
                  ->comment('Dimensions du produit');
            $table->integer('low_stock_threshold')->nullable()->after('critical_threshold')
                  ->comment('Seuil de stock faible');
            $table->integer('out_of_stock_threshold')->nullable()->after('low_stock_threshold')
                  ->comment('Seuil de rupture de stock');
            $table->json('images')->nullable()->after('gallery_images')
                  ->comment('Images du produit au format JSON');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'rental_price_per_day',
                'deposit_amount', 
                'type',
                'rental_category_id',
                'sku',
                'short_description',
                'weight',
                'dimensions',
                'low_stock_threshold',
                'out_of_stock_threshold',
                'images'
            ]);
        });
    }
};
