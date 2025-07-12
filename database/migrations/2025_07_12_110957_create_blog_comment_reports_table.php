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
        Schema::create('blog_comment_reports', function (Blueprint $table) {
            $table->id();
            
            // Relations
            $table->foreignId('blog_comment_id')->constrained('blog_comments')->cascadeOnDelete();
            $table->foreignId('reported_by')->constrained('users')->cascadeOnDelete(); // Utilisateur qui signale
            
            // Informations du signalement
            $table->enum('reason', [
                'spam',
                'inappropriate_content',
                'harassment',
                'hate_speech',
                'false_information',
                'copyright_violation',
                'other'
            ]); // Raison du signalement
            $table->text('description')->nullable(); // Description détaillée
            $table->json('additional_info')->nullable(); // Informations supplémentaires
            
            // Statut du signalement
            $table->enum('status', ['pending', 'reviewed', 'resolved', 'dismissed'])->default('pending');
            $table->text('admin_notes')->nullable(); // Notes de l'administrateur
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete(); // Admin qui a traité
            $table->timestamp('reviewed_at')->nullable(); // Date de traitement
            
            // Actions prises
            $table->enum('action_taken', [
                'none',
                'warning_sent',
                'comment_hidden',
                'comment_deleted',
                'user_warned',
                'user_suspended',
                'user_banned'
            ])->nullable(); // Action prise suite au signalement
            
            // Priorité
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            
            // Métadonnées
            $table->ipAddress('reporter_ip')->nullable(); // IP du signaleur
            $table->string('reporter_user_agent')->nullable(); // User agent du signaleur
            $table->json('evidence')->nullable(); // Preuves (captures d'écran, etc.)
            
            $table->timestamps();
            
            // Index pour les performances
            $table->index(['blog_comment_id', 'status']);
            $table->index(['reported_by']);
            $table->index(['status', 'priority']);
            $table->index('reviewed_at');
            
            // Contrainte unique pour éviter les doublons
            $table->unique(['blog_comment_id', 'reported_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_comment_reports');
    }
};
