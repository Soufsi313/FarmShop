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
        Schema::create('blog_comments', function (Blueprint $table) {
            $table->id();
            
            // Relations
            $table->foreignId('blog_post_id')->constrained('blog_posts')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('blog_comments')->cascadeOnDelete(); // Pour les réponses
            
            // Contenu du commentaire
            $table->text('content'); // Contenu du commentaire
            $table->text('original_content')->nullable(); // Contenu original avant modération
            
            // Statut et modération
            $table->enum('status', ['pending', 'approved', 'rejected', 'spam'])->default('pending');
            $table->text('rejection_reason')->nullable(); // Raison du rejet
            $table->foreignId('moderated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('moderated_at')->nullable();
            
            // Informations de l'utilisateur (pour les invités)
            $table->string('guest_name')->nullable(); // Nom de l'invité
            $table->string('guest_email')->nullable(); // Email de l'invité
            $table->string('guest_website')->nullable(); // Site web de l'invité
            $table->ipAddress('ip_address')->nullable(); // Adresse IP
            $table->string('user_agent')->nullable(); // User agent
            
            // Engagement
            $table->integer('likes_count')->default(0); // Nombre de likes
            $table->integer('replies_count')->default(0); // Nombre de réponses
            $table->boolean('is_pinned')->default(false); // Commentaire épinglé
            
            // Signalements
            $table->integer('reports_count')->default(0); // Nombre de signalements
            $table->boolean('is_reported')->default(false); // Est signalé
            
            // Métadonnées
            $table->json('metadata')->nullable(); // Métadonnées supplémentaires
            $table->boolean('is_edited')->default(false); // Commentaire modifié
            $table->timestamp('edited_at')->nullable(); // Date de modification
            
            $table->timestamps();
            $table->softDeletes(); // Soft delete
            
            // Index pour les performances
            $table->index(['blog_post_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index(['parent_id']);
            $table->index(['status', 'created_at']);
            $table->index('is_reported');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_comments');
    }
};
