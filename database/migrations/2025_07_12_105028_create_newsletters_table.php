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
        Schema::create('newsletters', function (Blueprint $table) {
            $table->id();
            
            // Informations de la newsletter
            $table->string('title'); // Titre de la newsletter
            $table->string('subject'); // Sujet de l'email
            $table->text('content'); // Contenu principal (HTML)
            $table->text('excerpt')->nullable(); // Résumé/extrait
            $table->string('featured_image')->nullable(); // Image principale
            
            // Gestion des envois
            $table->enum('status', ['draft', 'scheduled', 'sent', 'cancelled'])
                  ->default('draft'); // Statut de la newsletter
            $table->timestamp('scheduled_at')->nullable(); // Date d'envoi programmée
            $table->timestamp('sent_at')->nullable(); // Date d'envoi réelle
            $table->integer('recipients_count')->default(0); // Nombre de destinataires
            $table->integer('sent_count')->default(0); // Nombre d'emails envoyés
            $table->integer('failed_count')->default(0); // Nombre d'échecs d'envoi
            
            // Statistiques d'engagement
            $table->integer('opened_count')->default(0); // Nombre d'ouvertures
            $table->integer('clicked_count')->default(0); // Nombre de clics
            $table->integer('unsubscribed_count')->default(0); // Désabonnements
            
            // Métadonnées
            $table->json('tags')->nullable(); // Tags pour catégoriser
            $table->json('metadata')->nullable(); // Données supplémentaires
            $table->boolean('is_template')->default(false); // Marquer comme template
            $table->string('template_name')->nullable(); // Nom du template
            
            // Créateur et gestion
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete(); // Admin créateur
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete(); // Dernier modificateur
            
            $table->timestamps();
            $table->softDeletes(); // Soft delete pour historique
            
            // Index pour optimiser les requêtes
            $table->index(['status', 'created_at']);
            $table->index(['scheduled_at', 'status']);
            $table->index(['is_template', 'template_name']);
            $table->index('sent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletters');
    }
};
