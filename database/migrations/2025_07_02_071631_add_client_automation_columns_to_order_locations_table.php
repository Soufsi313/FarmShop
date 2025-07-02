<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClientAutomationColumnsToOrderLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_locations', function (Blueprint $table) {
            $table->timestamp('client_return_date')->nullable()->after('actual_return_date');
            $table->text('client_notes')->nullable()->after('return_notes');
            $table->timestamp('cancelled_at')->nullable()->after('client_notes');
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
            $table->dropColumn(['client_return_date', 'client_notes', 'cancelled_at']);
        });
    }
}
