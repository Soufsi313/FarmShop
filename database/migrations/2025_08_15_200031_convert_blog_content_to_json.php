<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // D'abord, on sauvegarde les données existantes et on convertit en JSON
        $posts = DB::table('blog_posts')->get();
        
        foreach ($posts as $post) {
            DB::table('blog_posts')
                ->where('id', $post->id)
                ->update([
                    'title' => json_encode(['fr' => $post->title]),
                    'excerpt' => json_encode(['fr' => $post->excerpt]),
                    'content' => json_encode(['fr' => $post->content]),
                    'meta_title' => $post->meta_title ? json_encode(['fr' => $post->meta_title]) : null,
                    'meta_description' => $post->meta_description ? json_encode(['fr' => $post->meta_description]) : null,
                ]);
        }
        
        // Puis on modifie les types de colonnes
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->json('title')->change();
            $table->json('excerpt')->change();
            $table->json('content')->change();
            $table->json('meta_title')->nullable()->change();
            $table->json('meta_description')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restaurer les données en français uniquement
        $posts = DB::table('blog_posts')->get();
        
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->string('title')->change();
            $table->text('excerpt')->change();
            $table->longText('content')->change();
            $table->string('meta_title')->nullable()->change();
            $table->text('meta_description')->nullable()->change();
        });
        
        foreach ($posts as $post) {
            $title = json_decode($post->title, true);
            $excerpt = json_decode($post->excerpt, true);
            $content = json_decode($post->content, true);
            $metaTitle = $post->meta_title ? json_decode($post->meta_title, true) : null;
            $metaDescription = $post->meta_description ? json_decode($post->meta_description, true) : null;
            
            DB::table('blog_posts')
                ->where('id', $post->id)
                ->update([
                    'title' => $title['fr'] ?? '',
                    'excerpt' => $excerpt['fr'] ?? '',
                    'content' => $content['fr'] ?? '',
                    'meta_title' => $metaTitle ? ($metaTitle['fr'] ?? null) : null,
                    'meta_description' => $metaDescription ? ($metaDescription['fr'] ?? null) : null,
                ]);
        }
    }
};
