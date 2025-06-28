<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogCommentReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_comment_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_comment_id')->constrained('blog_comments')->onDelete('cascade'); // Commentaire signalé
            $table->foreignId('reporter_id')->constrained('users')->onDelete('cascade'); // Utilisateur qui signale
            $table->enum('reason', [
                'spam',
                'inappropriate_content', 
                'harassment',
                'hate_speech',
                'violence',
                'personal_information',
                'copyright',
                'other'
            ]); // Raison du signalement
            $table->text('description')->nullable(); // Description détaillée du signalement
            $table->enum('status', ['pending', 'reviewed', 'dismissed'])->default('pending'); // Statut du signalement
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null'); // Admin qui a traité
            $table->timestamp('reviewed_at')->nullable(); // Date de traitement
            $table->text('admin_notes')->nullable(); // Notes de l'admin
            $table->ipAddress('ip_address')->nullable(); // IP du signaleur
            $table->timestamps();
            
            // Un utilisateur ne peut signaler qu'une fois le même commentaire
            $table->unique(['blog_comment_id', 'reporter_id']);
            
            // Index pour optimiser les requêtes
            $table->index(['status', 'created_at']);
            $table->index('reporter_id');
            $table->index('reviewed_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_comment_reports');
    }
}
