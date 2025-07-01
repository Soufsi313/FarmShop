<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartItemLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_item_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_location_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('product_name');
            $table->string('product_category')->nullable();
            $table->text('product_description')->nullable();
            $table->string('product_unit')->nullable();
            $table->integer('quantity');
            $table->integer('rental_duration_days');
            $table->date('rental_start_date');
            $table->date('rental_end_date');
            $table->decimal('unit_price_per_day', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->decimal('deposit_amount', 10, 2)->default(0);
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->index(['cart_location_id', 'product_id']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_item_locations');
    }
}
