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
        Schema::table('categories', function (Blueprint $table) {
            $table->enum('food_type', ['alimentaire', 'non_alimentaire'])->default('alimentaire')->after('description');
            $table->boolean('is_returnable')->default(false)->after('food_type'); // Basé sur le type alimentaire
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['food_type', 'is_returnable']);
        });
    }
};
