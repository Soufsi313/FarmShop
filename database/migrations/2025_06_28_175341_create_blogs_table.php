<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Titre de l'article
            $table->string('slug')->unique(); // Slug pour les URLs
            $table->text('content'); // Contenu détaillé de l'article
            $table->text('excerpt')->nullable(); // Extrait/résumé de l'article
            $table->string('featured_image')->nullable(); // Image mise en avant
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft'); // Statut de publication
            $table->timestamp('published_at')->nullable(); // Date de publication
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade'); // Auteur (admin)
            $table->integer('views_count')->default(0); // Nombre de vues
            $table->boolean('comments_enabled')->default(true); // Commentaires activés
            $table->json('meta_data')->nullable(); // Données meta (SEO, etc.)
            $table->timestamps();
            
            // Index pour optimiser les requêtes
            $table->index(['status', 'published_at']);
            $table->index('author_id');
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blogs');
    }
}
