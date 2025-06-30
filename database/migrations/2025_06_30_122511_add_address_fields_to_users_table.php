<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddressFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->json('default_shipping_address')->nullable()->after('biography');
            $table->json('default_billing_address')->nullable()->after('default_shipping_address');
            $table->string('phone')->nullable()->after('default_billing_address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'default_shipping_address',
                'default_billing_address', 
                'phone'
            ]);
        });
    }
}
