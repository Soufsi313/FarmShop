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
        // Convert existing string columns to JSON format
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->json('title')->change();
            $table->json('excerpt')->change();
            $table->json('content')->change();
            $table->json('meta_title')->change();
            $table->json('meta_description')->change();
        });

        // Migrate existing data to JSON format
        $posts = DB::table('blog_posts')->get();
        foreach ($posts as $post) {
            DB::table('blog_posts')
                ->where('id', $post->id)
                ->update([
                    'title' => json_encode(['fr' => $post->title]),
                    'excerpt' => json_encode(['fr' => $post->excerpt]),
                    'content' => json_encode(['fr' => $post->content]),
                    'meta_title' => json_encode(['fr' => $post->meta_title]),
                    'meta_description' => json_encode(['fr' => $post->meta_description]),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convert JSON back to string format
        $posts = DB::table('blog_posts')->get();
        foreach ($posts as $post) {
            $title = json_decode($post->title, true);
            $excerpt = json_decode($post->excerpt, true);
            $content = json_decode($post->content, true);
            $meta_title = json_decode($post->meta_title, true);
            $meta_description = json_decode($post->meta_description, true);

            DB::table('blog_posts')
                ->where('id', $post->id)
                ->update([
                    'title' => $title['fr'] ?? '',
                    'excerpt' => $excerpt['fr'] ?? '',
                    'content' => $content['fr'] ?? '',
                    'meta_title' => $meta_title['fr'] ?? '',
                    'meta_description' => $meta_description['fr'] ?? '',
                ]);
        }

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->text('title')->change();
            $table->text('excerpt')->change();
            $table->longText('content')->change();
            $table->string('meta_title')->change();
            $table->text('meta_description')->change();
        });
    }
};
