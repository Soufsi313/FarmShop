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
            $table->boolean('frontend_confirmed')->default(false)->after('confirmed_at');
            $table->timestamp('frontend_confirmed_at')->nullable()->after('frontend_confirmed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_locations', function (Blueprint $table) {
            $table->dropColumn(['frontend_confirmed', 'frontend_confirmed_at']);
        });
    }
};
