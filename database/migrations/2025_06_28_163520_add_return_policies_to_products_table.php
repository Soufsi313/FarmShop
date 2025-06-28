<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReturnPoliciesToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_perishable')->default(false)->after('is_rentable');
            $table->boolean('is_returnable')->default(true)->after('is_perishable');
            $table->integer('return_period_days')->default(14)->after('is_returnable');
            $table->text('return_conditions')->nullable()->after('return_period_days');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['is_perishable', 'is_returnable', 'return_period_days', 'return_conditions']);
        });
    }
}
