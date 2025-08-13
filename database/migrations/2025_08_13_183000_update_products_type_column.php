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
        // Vérifier que la table products existe avant de la modifier
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                // S'assurer que la colonne type existe et a les bonnes valeurs par défaut
                if (!Schema::hasColumn('products', 'type')) {
                    $table->enum('type', ['purchase', 'rental', 'both'])->default('purchase')->after('available_days');
                }
                
                // S'assurer que is_rental_available existe
                if (!Schema::hasColumn('products', 'is_rental_available')) {
                    $table->boolean('is_rental_available')->default(false)->after('type');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (Schema::hasColumn('products', 'is_rental_available')) {
                    $table->dropColumn('is_rental_available');
                }
            });
        }
    }
};
