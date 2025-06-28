<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingFieldsToOrderReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_returns', function (Blueprint $table) {
            // Ajout des champs manquants selon le modèle OrderReturn
            $table->enum('refund_status', ['pending', 'processing', 'completed', 'failed'])->default('pending')->after('status');
            $table->json('images')->nullable()->after('admin_notes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_returns', function (Blueprint $table) {
            $table->dropColumn(['refund_status', 'images']);
        });
    }
}
