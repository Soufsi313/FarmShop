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
            $table->unsignedBigInteger('special_offer_id')->nullable()->after('product_category');
            $table->decimal('original_unit_price', 8, 2)->nullable()->after('special_offer_id');
            $table->decimal('discount_percentage', 5, 2)->default(0)->after('original_unit_price');
            $table->decimal('discount_amount', 8, 2)->default(0)->after('discount_percentage');
            
            $table->foreign('special_offer_id')->references('id')->on('special_offers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['special_offer_id']);
            $table->dropColumn(['special_offer_id', 'original_unit_price', 'discount_percentage', 'discount_amount']);
        });
    }
};
