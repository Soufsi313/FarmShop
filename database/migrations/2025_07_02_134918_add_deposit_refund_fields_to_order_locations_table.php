<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDepositRefundFieldsToOrderLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_locations', function (Blueprint $table) {
            // Champs pour la gestion du remboursement de caution
            $table->decimal('total_penalties', 8, 2)->default(0)->after('damage_fee')->comment('Total des pénalités (retard + dégâts)');
            $table->decimal('deposit_refund_amount', 8, 2)->nullable()->after('total_penalties')->comment('Montant de caution remboursé');
            $table->timestamp('deposit_refunded_at')->nullable()->after('deposit_refund_amount')->comment('Date de remboursement de la caution');
            $table->text('refund_notes')->nullable()->after('deposit_refunded_at')->comment('Notes sur le remboursement');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_locations', function (Blueprint $table) {
            $table->dropColumn(['total_penalties', 'deposit_refund_amount', 'deposit_refunded_at', 'refund_notes']);
        });
    }
}
