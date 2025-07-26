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
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('return_processed_at')->nullable()->after('return_requested_at');
            $table->string('return_processed_by')->nullable()->after('return_processed_at'); // 'system_auto' ou ID admin
            $table->boolean('refund_processed')->default(false)->after('return_processed_by');
            $table->timestamp('refund_processed_at')->nullable()->after('refund_processed');
            $table->string('refund_stripe_id')->nullable()->after('refund_processed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'return_processed_at',
                'return_processed_by', 
                'refund_processed',
                'refund_processed_at',
                'refund_stripe_id'
            ]);
        });
    }
};
