<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'stripe_payment_intent_id')) {
                $table->string('stripe_payment_intent_id')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('orders', 'payment_details')) {
                $table->json('payment_details')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('orders', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('payment_details');
            }
            if (!Schema::hasColumn('orders', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable()->after('paid_at');
            }
        });

        Schema::table('order_locations', function (Blueprint $table) {
            if (!Schema::hasColumn('order_locations', 'stripe_payment_intent_id')) {
                $table->string('stripe_payment_intent_id')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('order_locations', 'payment_details')) {
                $table->json('payment_details')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('order_locations', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('payment_details');
            }
            if (!Schema::hasColumn('order_locations', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable()->after('paid_at');
            }
            if (!Schema::hasColumn('order_locations', 'returned_at')) {
                $table->timestamp('returned_at')->nullable()->after('cancelled_at');
            }
        });

        // Modifier le type de payment_status pour orders (de string à enum)
        $ordersColumns = DB::select("SHOW COLUMNS FROM orders WHERE Field = 'payment_status'");
        if (!empty($ordersColumns) && !str_contains($ordersColumns[0]->Type, 'enum')) {
            DB::statement("ALTER TABLE orders MODIFY COLUMN payment_status ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending'");
        }
        
        // Modifier le type de payment_status pour order_locations si nécessaire
        $orderLocationsColumns = DB::select("SHOW COLUMNS FROM order_locations WHERE Field = 'payment_status'");
        if (!empty($orderLocationsColumns) && !str_contains($orderLocationsColumns[0]->Type, 'enum')) {
            DB::statement("ALTER TABLE order_locations MODIFY COLUMN payment_status ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'stripe_payment_intent_id',
                'payment_details',
                'paid_at',
                'cancelled_at'
            ]);
        });

        Schema::table('order_locations', function (Blueprint $table) {
            $table->dropColumn([
                'stripe_payment_intent_id',
                'payment_details',
                'paid_at',
                'cancelled_at',
                'returned_at'
            ]);
        });

        // Remettre payment_status en string
        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_status VARCHAR(255) DEFAULT 'pending'");
    }
};
