<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCartGlobalFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Étape 1 : Renommer la table carts actuelle en cart_items
        Schema::rename('carts', 'cart_items');
        
        // Étape 2 : Créer la nouvelle table carts (paniers globaux)
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('session_id')->nullable(); // Pour les utilisateurs non connectés
            $table->enum('status', ['active', 'completed', 'abandoned'])->default('active');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            // Index pour les performances
            $table->index('user_id');
            $table->index('session_id');
        });
        
        // Étape 3 : Ajouter la colonne cart_id à cart_items
        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreignId('cart_id')->nullable()->after('id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Supprimer la colonne cart_id de cart_items
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign(['cart_id']);
            $table->dropColumn('cart_id');
        });
        
        // Supprimer la nouvelle table carts
        Schema::dropIfExists('carts');
        
        // Renommer cart_items en carts (retour à l'état initial)
        Schema::rename('cart_items', 'carts');
    }
}
