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
        Schema::table('cart_items', function (Blueprint $table) {
            $table->boolean('is_available')->default(true)->after('tax_rate');
            $table->timestamp('availability_checked_at')->nullable()->after('is_available');
        });

        Schema::table('cart_item_locations', function (Blueprint $table) {
            $table->boolean('is_available')->default(true)->after('notes');
            $table->timestamp('availability_checked_at')->nullable()->after('is_available');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropColumn(['is_available', 'availability_checked_at']);
        });

        Schema::table('cart_item_locations', function (Blueprint $table) {
            $table->dropColumn(['is_available', 'availability_checked_at']);
        });
    }
};
