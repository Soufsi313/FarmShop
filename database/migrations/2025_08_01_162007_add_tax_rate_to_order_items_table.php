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
        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('tax_rate', 5, 2)->default(21.00)->after('total_price');
            $table->decimal('subtotal', 10, 2)->after('tax_rate');
            $table->decimal('tax_amount', 10, 2)->after('subtotal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['tax_rate', 'subtotal', 'tax_amount']);
        });
    }
};
