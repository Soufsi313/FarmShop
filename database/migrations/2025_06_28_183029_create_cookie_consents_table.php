<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCookieConsentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cookie_consents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Utilisateur (null pour visiteurs)
            $table->string('session_id')->nullable(); // ID de session pour visiteurs non connectés
            $table->string('fingerprint')->nullable(); // Empreinte du navigateur
            $table->ipAddress('ip_address'); // Adresse IP
            $table->string('user_agent'); // User agent du navigateur
            $table->json('consents'); // Consentements par catégorie (ex: {"essential":true,"analytics":false,"marketing":true})
            $table->enum('consent_type', ['accept_all', 'reject_all', 'custom']); // Type de consentement
            $table->timestamp('consent_date'); // Date du consentement
            $table->timestamp('expires_at')->nullable(); // Date d'expiration du consentement
            $table->boolean('is_active')->default(true); // Consentement actif
            $table->json('metadata')->nullable(); // Métadonnées (version politique, langue, etc.)
            $table->timestamps();
            
            $table->index(['user_id', 'is_active']);
            $table->index(['session_id', 'is_active']);
            $table->index('fingerprint');
            $table->index('consent_date');
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cookie_consents');
    }
}
