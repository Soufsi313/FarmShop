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
        Schema::table('products', function (Blueprint $table) {
            $table->integer('rental_stock')->default(0)->after('quantity')
                  ->comment('Stock disponible pour la location');
            $table->boolean('is_rental_available')->default(false)->after('type')
                  ->comment('Produit disponible pour location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['rental_stock', 'is_rental_available']);
        });
    }
};
