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
        Schema::create('newsletter_sends', function (Blueprint $table) {
            $table->id();
            
            // Relations
            $table->foreignId('newsletter_id')->constrained('newsletters')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            
            // Statut de l'envoi
            $table->enum('status', ['pending', 'sent', 'failed', 'bounced'])->default('pending');
            $table->timestamp('sent_at')->nullable(); // Date d'envoi
            $table->string('failure_reason')->nullable(); // Raison de l'échec
            
            // Statistiques d'engagement
            $table->boolean('is_opened')->default(false);
            $table->timestamp('opened_at')->nullable(); // Première ouverture
            $table->integer('open_count')->default(0); // Nombre total d'ouvertures
            $table->timestamp('last_opened_at')->nullable(); // Dernière ouverture
            
            $table->boolean('is_clicked')->default(false);
            $table->timestamp('clicked_at')->nullable(); // Premier clic
            $table->integer('click_count')->default(0); // Nombre total de clics
            $table->timestamp('last_clicked_at')->nullable(); // Dernier clic
            
            $table->boolean('is_unsubscribed')->default(false);
            $table->timestamp('unsubscribed_at')->nullable(); // Date de désabonnement depuis cette newsletter
            
            // Tracking tokens
            $table->string('tracking_token')->unique(); // Token unique pour tracking
            $table->string('unsubscribe_token'); // Token pour désabonnement direct
            
            // Métadonnées
            $table->json('metadata')->nullable(); // IP, user agent, etc.
            
            $table->timestamps();
            
            // Contraintes et index
            $table->unique(['newsletter_id', 'user_id']); // Un envoi par newsletter par utilisateur
            $table->index(['newsletter_id', 'status']);
            $table->index(['user_id', 'sent_at']);
            $table->index('tracking_token');
            $table->index('is_opened');
            $table->index('is_clicked');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletter_sends');
    }
};
