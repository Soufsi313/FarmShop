<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            
            // Informations du visiteur
            $table->string('name'); // Nom du visiteur
            $table->string('email'); // Email du visiteur
            $table->string('phone')->nullable(); // Téléphone (optionnel)
            
            // Détails de la demande
            $table->string('subject'); // Objet de la demande
            $table->enum('reason', [
                'information_general',
                'question_produit',
                'probleme_commande',
                'demande_devis',
                'partenariat',
                'suggestion',
                'reclamation',
                'support_technique',
                'autre'
            ]); // Raison de la demande
            $table->text('message'); // Description/message
            
            // Métadonnées de gestion
            $table->enum('status', ['nouveau', 'en_cours', 'resolu', 'ferme'])->default('nouveau');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null'); // Admin assigné
            $table->timestamp('responded_at')->nullable(); // Date de première réponse
            $table->text('admin_notes')->nullable(); // Notes internes admin
            
            // Informations système
            $table->ipAddress('ip_address')->nullable(); // Adresse IP
            $table->string('user_agent')->nullable(); // Navigateur
            $table->json('attachments')->nullable(); // Fichiers joints (JSON array)
            
            // Index pour améliorer les performances
            $table->index(['status', 'created_at']);
            $table->index(['reason', 'status']);
            $table->index(['email', 'created_at']);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contacts');
    }
}
