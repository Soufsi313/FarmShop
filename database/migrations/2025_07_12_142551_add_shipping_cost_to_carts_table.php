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
        Schema::table('carts', function (Blueprint $table) {
            $table->decimal('shipping_cost', 8, 2)->default(0)->after('tax_amount')->comment('Frais de livraison (2.50€ si < 25€)');
            $table->boolean('free_shipping_eligible')->default(false)->after('shipping_cost')->comment('Eligible à la livraison gratuite (≥ 25€)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn(['shipping_cost', 'free_shipping_eligible']);
        });
    }
};
