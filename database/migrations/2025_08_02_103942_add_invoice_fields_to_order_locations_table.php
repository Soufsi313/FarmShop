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
            $table->string('invoice_number')->nullable()->unique()->after('order_number');
            $table->timestamp('invoice_generated_at')->nullable()->after('invoice_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_locations', function (Blueprint $table) {
            $table->dropColumn(['invoice_number', 'invoice_generated_at']);
        });
    }
};
