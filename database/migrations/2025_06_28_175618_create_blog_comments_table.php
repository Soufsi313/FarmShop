<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_id')->constrained('blogs')->onDelete('cascade'); // Article commenté
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Auteur du commentaire (null si compte supprimé)
            $table->string('author_name')->nullable(); // Nom affiché (sauvegardé pour les comptes supprimés)
            $table->text('content'); // Contenu du commentaire
            $table->enum('status', ['pending', 'approved', 'rejected', 'hidden'])->default('pending'); // Statut de modération
            $table->foreignId('parent_id')->nullable()->constrained('blog_comments')->onDelete('cascade'); // Réponse à un autre commentaire
            $table->ipAddress('ip_address')->nullable(); // IP de l'auteur
            $table->string('user_agent')->nullable(); // User agent du navigateur
            $table->integer('reports_count')->default(0); // Nombre de signalements
            $table->timestamp('approved_at')->nullable(); // Date d'approbation
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null'); // Modérateur
            $table->timestamps();
            $table->softDeletes(); // Soft delete pour conserver l'historique
            
            // Index pour optimiser les requêtes
            $table->index(['blog_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index('parent_id');
            $table->index('reports_count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_comments');
    }
}
