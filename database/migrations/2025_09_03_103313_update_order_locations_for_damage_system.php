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
        Schema::table('order_locations', function (Blueprint $table) {
            // Add damage tracking fields after inspection_notes
            $table->boolean('has_damages')->default(false)->after('inspection_notes');
            $table->text('damage_notes')->nullable()->after('has_damages');
            $table->boolean('auto_calculate_damages')->default(true)->after('damage_notes');
            
            // Remove the manual damage_amount field if it exists
            if (Schema::hasColumn('order_locations', 'damage_amount')) {
                $table->dropColumn('damage_amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_locations', function (Blueprint $table) {
            // Remove damage tracking fields
            $table->dropColumn(['has_damages', 'damage_notes', 'auto_calculate_damages']);
            
            // Re-add manual damage_amount if needed
            $table->decimal('damage_amount', 8, 2)->default(0)->after('inspection_notes');
        });
    }
};
