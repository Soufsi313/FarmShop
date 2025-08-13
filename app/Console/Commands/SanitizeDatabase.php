<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use App\Models\Wishlist;
use Carbon\Carbon;

class SanitizeDatabase extends Command
{
    protected $signature = 'db:sanitize {--force : Force l\'exécution sans confirmation}';
    protected $description = 'Sanitise la base de données en supprimant les produits et commandes d\'aujourd\'hui, garde les catégories';

    public function handle()
    {
        if (!$this->option('force')) {
            $this->warn('⚠️  ATTENTION: Cette commande va supprimer DÉFINITIVEMENT:');
            $this->line('   - Tous les produits et leurs images');
            $this->line('   - Toutes les commandes d\'aujourd\'hui (' . Carbon::today()->format('d/m/Y') . ')');
            $this->line('   - Tous les éléments de panier');
            $this->line('   - Toutes les listes de souhaits');
            $this->line('   - Les catégories seront CONSERVÉES');
            
            if (!$this->confirm('Êtes-vous sûr de vouloir continuer?')) {
                $this->info('Opération annulée.');
                return 0;
            }
        }

        $this->info('🧹 Début de la sanitisation de la base de données...');

        // 1. Supprimer les commandes d'aujourd'hui
        $this->sanitizeOrders();

        // 2. Supprimer les éléments de panier et listes de souhaits
        $this->sanitizeUserData();

        // 3. Supprimer tous les produits
        $this->sanitizeProducts();

        // Note: Plus de création de produits d'exemple - utiliser les seeders personnalisés

        $this->info('✅ Sanitisation terminée avec succès!');
        return 0;
    }

    private function sanitizeOrders()
    {
        $this->info('🗑️  Suppression de TOUTES les commandes...');
        
        $ordersCount = Order::count();
        
        if ($ordersCount > 0) {
            $this->line("   Suppression de {$ordersCount} commande(s)...");
            
            // Désactiver temporairement les contraintes de clés étrangères
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            
            // Supprimer les order_returns en premier (pour éviter les contraintes)
            $orderReturnsCount = DB::table('order_returns')->count();
            if ($orderReturnsCount > 0) {
                DB::table('order_returns')->truncate();
                $this->line("   ✅ {$orderReturnsCount} retours de commande supprimés");
            }

            // Supprimer tous les order_items
            $orderItemsCount = DB::table('order_items')->count();
            if ($orderItemsCount > 0) {
                DB::table('order_items')->truncate();
                $this->line("   ✅ {$orderItemsCount} éléments de commande supprimés");
            }

            // Supprimer toutes les order_locations 
            $orderLocationsCount = DB::table('order_locations')->count();
            if ($orderLocationsCount > 0) {
                DB::table('order_locations')->truncate();
                $this->line("   ✅ {$orderLocationsCount} emplacements de commande supprimés");
            }

            // Supprimer toutes les commandes
            Order::truncate();
            
            // Réactiver les contraintes de clés étrangères
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
            $this->line("   ✅ {$ordersCount} commande(s) supprimée(s)");
        } else {
            $this->line('   Aucune commande à supprimer');
        }
    }

    private function sanitizeUserData()
    {
        $this->info('🛒 Suppression des paniers et listes de souhaits...');
        
        $cartItemsCount = CartItem::count();
        $wishlistsCount = Wishlist::count();
        
        if ($cartItemsCount > 0) {
            CartItem::truncate();
            $this->line("   ✅ {$cartItemsCount} élément(s) de panier supprimé(s)");
        }
        
        if ($wishlistsCount > 0) {
            Wishlist::truncate();
            $this->line("   ✅ {$wishlistsCount} élément(s) de liste de souhaits supprimé(s)");
        }
    }

    private function sanitizeProducts()
    {
        $this->info('📦 Suppression de tous les produits...');
        
        $productsCount = Product::count();
        
        if ($productsCount > 0) {
            $this->line("   Suppression de {$productsCount} produit(s) et leurs images...");
            
            // Supprimer les images des produits
            $products = Product::all();
            foreach ($products as $product) {
                // Supprimer l'image principale
                if ($product->main_image) {
                    Storage::disk('public')->delete($product->main_image);
                }
                
                // Supprimer les images de galerie
                if ($product->gallery_images) {
                    foreach ($product->gallery_images as $image) {
                        Storage::disk('public')->delete($image);
                    }
                }
                
                // Supprimer les autres images
                if ($product->images) {
                    foreach ($product->images as $image) {
                        Storage::disk('public')->delete($image);
                    }
                }
            }
            
            // Désactiver temporairement les contraintes de clés étrangères
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            
            // Supprimer d'abord les tables qui référencent les produits
            $cartItemLocationsCount = DB::table('cart_item_locations')->count();
            if ($cartItemLocationsCount > 0) {
                DB::table('cart_item_locations')->truncate();
                $this->line("   ✅ {$cartItemLocationsCount} éléments de panier location supprimés");
            }
            
            // Supprimer les autres relations si elles existent
            $orderItemLocationsCount = DB::table('order_item_locations')->count();
            if ($orderItemLocationsCount > 0) {
                DB::table('order_item_locations')->truncate();
                $this->line("   ✅ {$orderItemLocationsCount} éléments de commande location supprimés");
            }
            
            // Supprimer tous les produits
            Product::truncate();
            
            // Réactiver les contraintes de clés étrangères
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
            $this->line("   ✅ {$productsCount} produit(s) supprimé(s)");
        } else {
            $this->line('   Aucun produit à supprimer');
        }
    }
}
