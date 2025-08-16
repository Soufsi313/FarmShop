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
        Schema::table('blog_categories', function (Blueprint $table) {
            $table->json('name')->change();
            $table->json('description')->change();
            $table->json('meta_title')->change();
            $table->json('meta_description')->change();
        });

        // Migrate existing data to JSON format
        $categories = DB::table('blog_categories')->get();
        foreach ($categories as $category) {
            DB::table('blog_categories')
                ->where('id', $category->id)
                ->update([
                    'name' => json_encode(['fr' => $category->name]),
                    'description' => json_encode(['fr' => $category->description]),
                    'meta_title' => json_encode(['fr' => $category->meta_title]),
                    'meta_description' => json_encode(['fr' => $category->meta_description]),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convert JSON back to string format
        $categories = DB::table('blog_categories')->get();
        foreach ($categories as $category) {
            $name = json_decode($category->name, true);
            $description = json_decode($category->description, true);
            $meta_title = json_decode($category->meta_title, true);
            $meta_description = json_decode($category->meta_description, true);

            DB::table('blog_categories')
                ->where('id', $category->id)
                ->update([
                    'name' => $name['fr'] ?? '',
                    'description' => $description['fr'] ?? '',
                    'meta_title' => $meta_title['fr'] ?? '',
                    'meta_description' => $meta_description['fr'] ?? '',
                ]);
        }

        Schema::table('blog_categories', function (Blueprint $table) {
            $table->string('name')->change();
            $table->text('description')->change();
            $table->string('meta_title')->change();
            $table->text('meta_description')->change();
        });
    }
};
