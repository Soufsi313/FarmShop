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
        Schema::create('special_offers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nom de l'offre
            $table->text('description')->nullable(); // Description de l'offre
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Produit concerné
            $table->integer('minimum_quantity'); // Quantité minimum pour déclencher l'offre
            $table->decimal('discount_percentage', 5, 2); // Pourcentage de réduction (ex: 50.00 pour 50%)
            $table->datetime('start_date'); // Date de début de l'offre
            $table->datetime('end_date'); // Date de fin de l'offre
            $table->boolean('is_active')->default(true); // Statut actif/inactif
            $table->integer('usage_count')->default(0); // Nombre d'utilisations
            $table->integer('usage_limit')->nullable(); // Limite d'utilisation (null = illimité)
            $table->timestamps();
            
            // Index pour optimiser les requêtes
            $table->index(['product_id', 'is_active']);
            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('special_offers');
    }
};
