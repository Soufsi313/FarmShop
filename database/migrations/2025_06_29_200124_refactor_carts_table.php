<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RefactorCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Sauvegarder les données existantes
        DB::statement('CREATE TEMPORARY TABLE temp_carts AS SELECT * FROM carts');
        
        // Supprimer l'ancienne table
        Schema::dropIfExists('carts');
        
        // Créer la nouvelle structure pour les paniers globaux
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('last_activity')->useCurrent();
            $table->timestamps();

            // Un seul panier par utilisateur
            $table->unique('user_id');
        });
        
        // Migrer les données vers la nouvelle structure
        $this->migrateCartData();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Restaurer l'ancienne structure si nécessaire
        Schema::dropIfExists('carts');
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->unsigned()->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2)->storedAs('quantity * unit_price');
            $table->timestamps();
            $table->index(['user_id', 'product_id']);
            $table->unique(['user_id', 'product_id']);
        });
    }

    /**
     * Migrer les données de l'ancien format vers le nouveau
     */
    private function migrateCartData()
    {
        // Créer un panier pour chaque utilisateur unique
        $users = DB::table('temp_carts')->select('user_id')->distinct()->get();
        
        foreach ($users as $user) {
            // Créer le panier global pour l'utilisateur
            $cartId = DB::table('carts')->insertGetId([
                'user_id' => $user->user_id,
                'created_at' => now(),
                'updated_at' => now(),
                'last_activity' => now()
            ]);
            
            // Migrer les éléments vers cart_items
            $items = DB::table('temp_carts')->where('user_id', $user->user_id)->get();
            
            foreach ($items as $item) {
                DB::table('cart_items')->insert([
                    'cart_id' => $cartId,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at
                ]);
            }
        }
        
        // Supprimer la table temporaire
        DB::statement('DROP TEMPORARY TABLE temp_carts');
    }
}
