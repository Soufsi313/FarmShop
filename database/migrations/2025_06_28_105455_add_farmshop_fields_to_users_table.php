<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFarmshopFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->after('name');
            $table->boolean('is_newsletter_subscribed')->default(false)->after('email_verified_at');
            $table->timestamp('newsletter_subscribed_at')->nullable()->after('is_newsletter_subscribed');
            $table->timestamp('newsletter_unsubscribed_at')->nullable()->after('newsletter_subscribed_at');
            $table->softDeletes();
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
                'username',
                'is_newsletter_subscribed',
                'newsletter_subscribed_at',
                'newsletter_unsubscribed_at'
            ]);
            $table->dropSoftDeletes();
        });
    }
}
