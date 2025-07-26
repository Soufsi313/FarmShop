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
        // Modifier l'enum status pour ajouter 'return_requested'
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'confirmed', 'preparing', 'shipped', 'delivered', 'cancelled', 'returned', 'return_requested') DEFAULT 'pending'");
        
        // Modifier aussi pour order_items pour cohérence
        DB::statement("ALTER TABLE order_items MODIFY COLUMN status ENUM('pending', 'confirmed', 'preparing', 'shipped', 'delivered', 'cancelled', 'returned', 'return_requested') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revenir à l'enum original
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'confirmed', 'preparing', 'shipped', 'delivered', 'cancelled', 'returned') DEFAULT 'pending'");
        DB::statement("ALTER TABLE order_items MODIFY COLUMN status ENUM('pending', 'confirmed', 'preparing', 'shipped', 'delivered', 'cancelled', 'returned') DEFAULT 'pending'");
    }
};
