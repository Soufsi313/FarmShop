<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Table pour les traductions de produits
        if (!Schema::hasTable('product_translations')) {
            Schema::create('product_translations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->string('locale', 2);
                $table->string('name');
                $table->text('description')->nullable();
                $table->text('short_description')->nullable();
                $table->json('features')->nullable();
                $table->timestamps();
                
                $table->unique(['product_id', 'locale']);
                $table->index(['locale', 'product_id']);
            });
        }

        // Table pour les traductions de catégories
        if (!Schema::hasTable('category_translations')) {
            Schema::create('category_translations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('category_id')->constrained()->onDelete('cascade');
                $table->string('locale', 2);
                $table->string('name');
                $table->text('description')->nullable();
                $table->timestamps();
                
                $table->unique(['category_id', 'locale']);
            });
        }

        // Table pour les traductions d'articles de blog
        if (!Schema::hasTable('blog_post_translations')) {
            Schema::create('blog_post_translations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('blog_post_id')->constrained()->onDelete('cascade');
                $table->string('locale', 2);
                $table->string('title');
                $table->text('content');
                $table->string('slug');
                $table->text('excerpt')->nullable();
                $table->timestamps();
                
                $table->unique(['blog_post_id', 'locale']);
            });
        }

        // Table pour les traductions de commentaires de blog
        if (!Schema::hasTable('blog_comment_translations')) {
            Schema::create('blog_comment_translations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('blog_comment_id')->constrained('blog_comments')->onDelete('cascade');
                $table->string('locale', 2);
                $table->text('content');
                $table->timestamps();
                
                $table->unique(['blog_comment_id', 'locale']);
            });
        }

        // Table générique pour autres traductions
        if (!Schema::hasTable('translations')) {
            Schema::create('translations', function (Blueprint $table) {
                $table->id();
                $table->string('group');
                $table->string('key');
                $table->string('locale', 2);
                $table->text('value');
                $table->timestamps();
                
                $table->unique(['group', 'key', 'locale']);
                $table->index(['group', 'locale']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('translations');
        Schema::dropIfExists('blog_comment_translations');
        Schema::dropIfExists('blog_post_translations');
        Schema::dropIfExists('category_translations');
        Schema::dropIfExists('product_translations');
    }
};