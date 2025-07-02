<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateOrderLocationsStatusEnum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Modifier l'enum pour ajouter les nouveaux statuts
        DB::statement("ALTER TABLE order_locations MODIFY COLUMN status ENUM('pending', 'confirmed', 'active', 'pending_inspection', 'returned', 'completed', 'cancelled', 'overdue') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Retourner à l'enum original
        DB::statement("ALTER TABLE order_locations MODIFY COLUMN status ENUM('pending', 'confirmed', 'active', 'completed', 'cancelled', 'overdue') DEFAULT 'pending'");
    }
}
