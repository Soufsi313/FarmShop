<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class MigrateExistingCartData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // D'abord, créer des paniers pour tous les utilisateurs qui ont des articles
        $cartItems = DB::table('carts')->get();
        
        foreach ($cartItems as $item) {
            // Créer ou récupérer le panier pour l'utilisateur
            $cartId = DB::table('carts_new')->insertGetId([
                'user_id' => $item->user_id,
                'status' => 'active',
                'expires_at' => now()->addDays(30),
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ]);
            
            // Migrer l'article vers cart_items
            DB::table('cart_items')->insert([
                'cart_id' => $cartId,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Ne rien faire en cas de rollback pour éviter la perte de données
    }
}
