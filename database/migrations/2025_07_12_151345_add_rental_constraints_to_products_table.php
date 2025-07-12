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
            $table->integer('min_rental_days')->default(1)->after('deposit_amount');
            $table->integer('max_rental_days')->default(7)->after('min_rental_days');
            $table->json('available_days')->nullable()->after('max_rental_days')->comment('Jours de la semaine disponibles (1=Lundi, 6=Samedi)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'min_rental_days',
                'max_rental_days',
                'available_days'
            ]);
        });
    }
};
