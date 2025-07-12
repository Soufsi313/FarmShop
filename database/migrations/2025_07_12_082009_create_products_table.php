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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('slug')->unique();
            $table->decimal('price', 10, 2);
            $table->integer('quantity')->default(0);
            $table->integer('critical_threshold')->default(5);
            $table->enum('unit_symbol', ['kg', 'pièce', 'litre', 'gramme', 'tonne']);
            $table->string('main_image')->nullable();
            $table->json('gallery_images')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('likes_count')->default(0);
            $table->integer('views_count')->default(0);
            
            // Relation avec la catégorie
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            
            // SEO et métadonnées
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            
            $table->softDeletes();
            $table->timestamps();
            
            // Index pour les recherches
            $table->index(['name', 'is_active']);
            $table->index(['category_id', 'is_active']);
            $table->index('price');
            $table->index('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
