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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            
            // Informations du visiteur
            $table->string('name'); // Nom du visiteur
            $table->string('email'); // Email du visiteur
            $table->string('phone')->nullable(); // Téléphone optionnel
            
            // Détails du message
            $table->string('subject'); // Objet du message
            $table->enum('reason', [
                'mon_profil',
                'mes_achats', 
                'mes_locations',
                'mes_donnees',
                'support_technique',
                'partenariat',
                'autre'
            ]); // Raison de la demande
            $table->text('message'); // Description/message
            
            // Statut et traitement
            $table->enum('status', ['pending', 'in_progress', 'resolved', 'closed'])
                  ->default('pending'); // Statut du message
            $table->boolean('is_read')->default(false); // Lu par l'admin
            $table->timestamp('read_at')->nullable(); // Date de lecture
            
            // Réponse de l'admin
            $table->text('admin_response')->nullable(); // Réponse de l'admin
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete(); // Admin qui a répondu
            $table->timestamp('responded_at')->nullable(); // Date de réponse
            $table->boolean('email_sent')->default(false); // Email de réponse envoyé
            $table->timestamp('email_sent_at')->nullable(); // Date d'envoi email
            
            // Priorité et catégorisation
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->json('metadata')->nullable(); // Données supplémentaires (IP, user agent, etc.)
            
            $table->timestamps();
            
            // Index pour optimiser les requêtes
            $table->index(['status', 'created_at']);
            $table->index(['email', 'created_at']);
            $table->index(['reason', 'status']);
            $table->index('is_read');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
