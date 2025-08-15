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
        Schema::table('blog_posts', function (Blueprint $table) {
            // English translations
            $table->string('title_en')->nullable()->after('title');
            $table->string('slug_en')->nullable()->after('slug');
            $table->text('excerpt_en')->nullable()->after('excerpt');
            $table->longText('content_en')->nullable()->after('content');
            $table->string('meta_title_en')->nullable()->after('meta_title');
            $table->text('meta_description_en')->nullable()->after('meta_description');
            $table->text('meta_keywords_en')->nullable()->after('meta_keywords');
            
            // Dutch translations
            $table->string('title_nl')->nullable()->after('title_en');
            $table->string('slug_nl')->nullable()->after('slug_en');
            $table->text('excerpt_nl')->nullable()->after('excerpt_en');
            $table->longText('content_nl')->nullable()->after('content_en');
            $table->string('meta_title_nl')->nullable()->after('meta_title_en');
            $table->text('meta_description_nl')->nullable()->after('meta_description_en');
            $table->text('meta_keywords_nl')->nullable()->after('meta_keywords_en');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropColumn([
                'title_en', 'slug_en', 'excerpt_en', 'content_en', 'meta_title_en', 'meta_description_en', 'meta_keywords_en',
                'title_nl', 'slug_nl', 'excerpt_nl', 'content_nl', 'meta_title_nl', 'meta_description_nl', 'meta_keywords_nl'
            ]);
        });
    }
};
