<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class RefactorCartLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // D'abord, migrer les données existantes vers cart_item_locations
        DB::statement('
            INSERT INTO cart_item_locations (
                cart_location_id, product_id, product_name, product_category, 
                product_description, product_unit, quantity, rental_duration_days,
                rental_start_date, rental_end_date, unit_price_per_day, 
                total_price, deposit_amount, status, created_at, updated_at
            )
            SELECT 
                id as cart_location_id, product_id, product_name, product_category,
                product_description, product_unit, quantity, rental_duration_days,
                rental_start_date, rental_end_date, unit_price_per_day,
                total_price, deposit_amount, status, created_at, updated_at
            FROM cart_locations
        ');

        // Ensuite, nettoyer et restructurer cart_locations
        Schema::table('cart_locations', function (Blueprint $table) {
            // Supprimer les colonnes qui vont maintenant dans cart_item_locations
            $table->dropColumn([
                'product_id', 'product_name', 'product_category', 'product_description',
                'product_unit', 'quantity', 'rental_duration_days', 'rental_start_date',
                'rental_end_date', 'unit_price_per_day', 'total_price', 'deposit_amount'
            ]);
        });

        // Ajouter les nouvelles colonnes pour le panier global
        Schema::table('cart_locations', function (Blueprint $table) {
            $table->decimal('total_amount', 10, 2)->default(0)->after('user_id');
            $table->decimal('total_deposit', 10, 2)->default(0)->after('total_amount');
            $table->text('notes')->nullable()->after('total_deposit');
            
            // Modifier le statut pour avoir des valeurs cohérentes avec le nouveau système
            $table->string('status')->default('draft')->change();
        });

        // Mettre à jour les statuts pour correspondre au nouveau système
        DB::table('cart_locations')->update(['status' => 'draft']);

        // Recalculer les totaux pour chaque panier
        $carts = DB::table('cart_locations')->get();
        foreach ($carts as $cart) {
            $totals = DB::table('cart_item_locations')
                ->where('cart_location_id', $cart->id)
                ->selectRaw('SUM(total_price) as total_amount, SUM(deposit_amount) as total_deposit')
                ->first();
                
            DB::table('cart_locations')
                ->where('id', $cart->id)
                ->update([
                    'total_amount' => $totals->total_amount ?? 0,
                    'total_deposit' => $totals->total_deposit ?? 0
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
        // Restaurer l'ancienne structure de cart_locations
        Schema::table('cart_locations', function (Blueprint $table) {
            $table->dropColumn(['total_amount', 'total_deposit', 'notes']);
            
            // Remettre les anciennes colonnes
            $table->foreignId('product_id')->nullable()->after('user_id');
            $table->string('product_name')->nullable()->after('product_id');
            $table->string('product_category')->nullable()->after('product_name');
            $table->text('product_description')->nullable()->after('product_category');
            $table->string('product_unit')->nullable()->after('product_description');
            $table->integer('quantity')->nullable()->after('product_unit');
            $table->integer('rental_duration_days')->nullable()->after('quantity');
            $table->date('rental_start_date')->nullable()->after('rental_duration_days');
            $table->date('rental_end_date')->nullable()->after('rental_start_date');
            $table->decimal('unit_price_per_day', 10, 2)->nullable()->after('rental_end_date');
            $table->decimal('total_price', 10, 2)->nullable()->after('unit_price_per_day');
            $table->decimal('deposit_amount', 10, 2)->default(0)->after('total_price');
        });

        // Restaurer les données depuis cart_item_locations
        DB::statement('
            UPDATE cart_locations cl
            INNER JOIN cart_item_locations cil ON cl.id = cil.cart_location_id
            SET 
                cl.product_id = cil.product_id,
                cl.product_name = cil.product_name,
                cl.product_category = cil.product_category,
                cl.product_description = cil.product_description,
                cl.product_unit = cil.product_unit,
                cl.quantity = cil.quantity,
                cl.rental_duration_days = cil.rental_duration_days,
                cl.rental_start_date = cil.rental_start_date,
                cl.rental_end_date = cil.rental_end_date,
                cl.unit_price_per_day = cil.unit_price_per_day,
                cl.total_price = cil.total_price,
                cl.deposit_amount = cil.deposit_amount,
                cl.status = cil.status
        ');

        // Supprimer les enregistrements cart_item_locations
        DB::table('cart_item_locations')->truncate();
    }
}
