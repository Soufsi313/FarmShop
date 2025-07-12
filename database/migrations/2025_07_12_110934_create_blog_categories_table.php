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
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();
            
            // Informations de la catégorie
            $table->string('name'); // Nom de la catégorie
            $table->string('slug')->unique(); // Slug pour URL
            $table->text('description')->nullable(); // Description de la catégorie
            $table->string('color')->default('#28a745'); // Couleur pour l'affichage
            $table->string('icon')->nullable(); // Icône de la catégorie
            
            // Image et métadonnées
            $table->string('featured_image')->nullable(); // Image de la catégorie
            $table->json('metadata')->nullable(); // Métadonnées supplémentaires
            
            // Statut et ordre
            $table->boolean('is_active')->default(true); // Statut actif/inactif
            $table->integer('sort_order')->default(0); // Ordre d'affichage
            
            // SEO
            $table->string('meta_title')->nullable(); // Titre SEO
            $table->text('meta_description')->nullable(); // Description SEO
            $table->text('meta_keywords')->nullable(); // Mots-clés SEO
            
            // Compteurs
            $table->integer('posts_count')->default(0); // Nombre d'articles
            $table->integer('views_count')->default(0); // Nombre de vues
            
            // Créateur
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            
            $table->timestamps();
            $table->softDeletes(); // Soft delete
            
            // Index
            $table->index(['is_active', 'sort_order']);
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_categories');
    }
};
