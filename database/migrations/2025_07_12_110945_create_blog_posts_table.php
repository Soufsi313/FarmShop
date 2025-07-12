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
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            
            // Relation avec la catégorie
            $table->foreignId('blog_category_id')->constrained('blog_categories')->cascadeOnDelete();
            
            // Informations de l'article
            $table->string('title'); // Titre de l'article
            $table->string('slug')->unique(); // Slug pour URL
            $table->text('excerpt')->nullable(); // Extrait de l'article
            $table->longText('content'); // Contenu complet de l'article
            $table->string('featured_image')->nullable(); // Image principale
            $table->json('gallery')->nullable(); // Galerie d'images
            
            // Statut et publication
            $table->enum('status', ['draft', 'published', 'scheduled', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable(); // Date de publication
            $table->timestamp('scheduled_for')->nullable(); // Date de publication programmée
            
            // SEO et métadonnées
            $table->string('meta_title')->nullable(); // Titre SEO
            $table->text('meta_description')->nullable(); // Description SEO
            $table->text('meta_keywords')->nullable(); // Mots-clés SEO
            $table->json('metadata')->nullable(); // Métadonnées supplémentaires
            
            // Tags et catégorisation
            $table->json('tags')->nullable(); // Tags de l'article
            
            // Engagement et statistiques
            $table->integer('views_count')->default(0); // Nombre de vues
            $table->integer('likes_count')->default(0); // Nombre de likes
            $table->integer('shares_count')->default(0); // Nombre de partages
            $table->integer('comments_count')->default(0); // Nombre de commentaires
            $table->decimal('reading_time', 8, 2)->nullable(); // Temps de lecture estimé (minutes)
            
            // Configuration des commentaires
            $table->boolean('allow_comments')->default(true); // Autoriser les commentaires
            $table->boolean('is_featured')->default(false); // Article en vedette
            $table->boolean('is_sticky')->default(false); // Article épinglé
            
            // Auteur
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            
            // Éditeur et dernière modification
            $table->foreignId('last_edited_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('last_edited_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes(); // Soft delete
            
            // Index pour les performances
            $table->index(['status', 'published_at']);
            $table->index(['blog_category_id', 'status']);
            $table->index(['is_featured', 'is_sticky']);
            $table->index('slug');
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
