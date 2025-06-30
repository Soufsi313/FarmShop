<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\DB;

class MigrateCartItemsToNewStructure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cart:migrate-structure';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrer les cart_items existants vers la nouvelle structure avec cart_id';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Début de la migration des cart_items...');

        DB::beginTransaction();

        try {
            // Récupérer tous les cart_items sans cart_id
            $cartItems = DB::table('cart_items')->whereNull('cart_id')->get();
            
            if ($cartItems->isEmpty()) {
                $this->info('Aucun cart_item à migrer.');
                return 0;
            }

            $userCarts = [];
            $migratedCount = 0;

            foreach ($cartItems as $item) {
                // Créer ou récupérer le panier pour cet utilisateur
                if (!isset($userCarts[$item->user_id])) {
                    $cart = Cart::create([
                        'user_id' => $item->user_id,
                        'status' => 'active',
                        'expires_at' => now()->addDays(7),
                    ]);
                    $userCarts[$item->user_id] = $cart->id;
                    $this->info("Panier créé pour l'utilisateur {$item->user_id}: ID {$cart->id}");
                }

                // Mettre à jour le cart_item avec le cart_id
                DB::table('cart_items')
                    ->where('id', $item->id)
                    ->update(['cart_id' => $userCarts[$item->user_id]]);

                $migratedCount++;
            }

            DB::commit();
            
            $this->info("Migration terminée avec succès ! {$migratedCount} cart_items migrés.");
            $this->info("Nombre de paniers créés : " . count($userCarts));

            return 0;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Erreur lors de la migration : " . $e->getMessage());
            return 1;
        }
    }
}
