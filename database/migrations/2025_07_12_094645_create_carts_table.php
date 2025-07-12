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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('subtotal', 10, 2)->default(0.00); // Sous-total HT
            $table->decimal('tax_amount', 10, 2)->default(0.00); // Montant TVA
            $table->decimal('total', 10, 2)->default(0.00); // Total TTC
            $table->decimal('tax_rate', 5, 2)->default(20.00); // Taux de TVA (20% par défaut)
            $table->integer('total_items')->default(0); // Nombre total d'articles
            $table->json('metadata')->nullable(); // Données supplémentaires (adresses, notes, etc.)
            $table->enum('status', ['active', 'abandoned', 'converted'])->default('active');
            $table->timestamp('expires_at')->nullable(); // Expiration du panier
            $table->timestamps();
            
            // Un utilisateur ne peut avoir qu'un seul panier actif
            $table->unique(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
