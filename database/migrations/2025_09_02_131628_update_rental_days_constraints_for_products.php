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
        Schema::table('products', function (Blueprint $table) {
            // Permettre à max_rental_days d'être NULL (pas de limite)
            $table->integer('max_rental_days')->nullable()->change();
        });

        // Mettre à jour tous les produits de location existants
        DB::table('products')
            ->where('type', 'rental')
            ->update([
                'min_rental_days' => 1,        // Minimum 1 jour
                'max_rental_days' => null,     // Pas de limite maximum
                'updated_at' => now()
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Remettre max_rental_days comme non-nullable avec une valeur par défaut
            $table->integer('max_rental_days')->default(7)->change();
        });

        // Restaurer les valeurs par défaut
        DB::table('products')
            ->where('type', 'rental')
            ->whereNull('max_rental_days')
            ->update([
                'max_rental_days' => 7,
                'updated_at' => now()
            ]);
    }
};
