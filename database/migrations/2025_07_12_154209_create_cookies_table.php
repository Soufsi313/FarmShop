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
        Schema::create('cookies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id')->nullable(); // Pour les visiteurs non connectés
            $table->ipAddress('ip_address');
            $table->text('user_agent');
            
            // Types de cookies et préférences
            $table->boolean('necessary')->default(true); // Toujours accepté
            $table->boolean('analytics')->default(false);
            $table->boolean('marketing')->default(false);
            $table->boolean('preferences')->default(false);
            $table->boolean('social_media')->default(false);
            
            // Métadonnées
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('last_updated_at')->nullable();
            $table->json('preferences_details')->nullable(); // Détails des préférences
            $table->string('consent_version')->default('1.0'); // Version du consentement
            $table->enum('status', ['pending', 'accepted', 'rejected', 'partial'])->default('pending');
            
            // Traçabilité navigation
            $table->string('page_url')->nullable(); // Page où le consentement a été donné
            $table->string('referer')->nullable();
            $table->json('browser_info')->nullable(); // Infos détaillées du navigateur
            
            $table->timestamps();
            
            // Index pour optimiser les requêtes
            $table->index(['user_id', 'status']);
            $table->index(['session_id', 'ip_address']);
            $table->index(['accepted_at']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cookies');
    }
};
