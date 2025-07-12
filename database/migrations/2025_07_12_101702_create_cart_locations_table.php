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
        Schema::create('cart_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Totaux du panier de location
            $table->decimal('total_amount', 10, 2)->default(0); // Total HT
            $table->decimal('total_deposit', 10, 2)->default(0); // Total caution
            $table->decimal('total_tva', 10, 2)->default(0); // Total TVA
            $table->decimal('total_with_tax', 10, 2)->default(0); // Total TTC
            $table->integer('total_items')->default(0); // Nombre d'articles
            $table->integer('total_quantity')->default(0); // Quantité totale
            
            // Dates de location par défaut (peuvent être overridées par article)
            $table->date('default_start_date')->nullable();
            $table->date('default_end_date')->nullable();
            $table->integer('default_duration_days')->nullable();
            
            // Notes et informations complémentaires
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable(); // Données supplémentaires
            
            $table->timestamps();
            
            // Index pour optimiser les requêtes
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_locations');
    }
};
