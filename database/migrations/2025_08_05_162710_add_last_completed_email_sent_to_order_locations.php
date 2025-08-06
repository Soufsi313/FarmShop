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
            $table->timestamp('last_completed_email_sent_at')->nullable()->after('completed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_locations', function (Blueprint $table) {
            $table->dropColumn('last_completed_email_sent_at');
        });
    }
};
