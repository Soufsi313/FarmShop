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
        Schema::create('newsletter_subscriptions', function (Blueprint $table) {
            $table->id();
            
            // Relation avec l'utilisateur
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            
            // Statut de l'abonnement
            $table->boolean('is_subscribed')->default(true);
            $table->timestamp('subscribed_at')->nullable(); // Date d'abonnement
            $table->timestamp('unsubscribed_at')->nullable(); // Date de désabonnement
            $table->string('unsubscribe_reason')->nullable(); // Raison du désabonnement
            
            // Token de désabonnement (pour lien email)
            $table->string('unsubscribe_token')->unique(); // Token unique pour désabonnement
            
            // Préférences
            $table->json('preferences')->nullable(); // Préférences d'abonnement (types de news, fréquence, etc.)
            
            // Métadonnées de suivi
            $table->string('source')->nullable(); // Source d'abonnement (registration, profile, etc.)
            $table->json('metadata')->nullable(); // Données supplémentaires
            
            $table->timestamps();
            
            // Contraintes et index
            $table->unique(['user_id']); // Un seul abonnement par utilisateur
            $table->index(['is_subscribed', 'created_at']);
            $table->index('unsubscribe_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletter_subscriptions');
    }
};
