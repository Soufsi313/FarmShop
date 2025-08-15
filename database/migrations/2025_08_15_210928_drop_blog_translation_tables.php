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
        Schema::dropIfExists('blog_post_translations');
        Schema::dropIfExists('blog_category_translations');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tables will be recreated if needed
    }
};
