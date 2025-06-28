<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Console\Command;

class TestWishlist extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:wishlist {action?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the Wishlist model and controller functionality';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $action = $this->argument('action') ?? 'all';

        $this->info('🎯 Test de la gestion de la wishlist');
        $this->info('=' . str_repeat('=', 50));

        switch ($action) {
            case 'create':
                $this->testCreateWishlist();
                break;
            case 'list':
                $this->testListWishlist();
                break;
            case 'toggle':
                $this->testToggleWishlist();
                break;
            case 'stats':
                $this->testWishlistStats();
                break;
            case 'cleanup':
                $this->testCleanup();
                break;
            case 'all':
            default:
                $this->testCreateWishlist();
                $this->testListWishlist();
                $this->testWishlistStats();
                break;
        }

        return 0;
    }

    private function testCreateWishlist()
    {
        $this->info('📝 Test de création d\'éléments de wishlist...');
        
        $user = User::first();
        $products = Product::where('is_active', true)->limit(3)->get();
        
        if (!$user) {
            $this->error('Aucun utilisateur trouvé.');
            return;
        }

        if ($products->isEmpty()) {
            $this->error('Aucun produit actif trouvé.');
            return;
        }

        foreach ($products as $product) {
            // Vérifier si déjà en wishlist
            if (!Wishlist::isInWishlist($user->id, $product->id)) {
                $wishlist = Wishlist::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                ]);
                
                $this->info("✅ Produit ajouté à la wishlist: {$product->name} (ID: {$wishlist->id})");
            } else {
                $this->info("ℹ️ Produit déjà en wishlist: {$product->name}");
            }
        }
    }

    private function testListWishlist()
    {
        $this->info('📋 Liste des éléments de wishlist...');
        
        $wishlists = Wishlist::with(['user', 'product'])->get();
        
        if ($wishlists->isEmpty()) {
            $this->warn('Aucun élément de wishlist trouvé.');
            return;
        }

        $this->table(
            ['ID', 'Utilisateur', 'Produit', 'Prix', 'Date ajout'],
            $wishlists->map(function ($wishlist) {
                return [
                    $wishlist->id,
                    $wishlist->user ? $wishlist->user->name : 'N/A',
                    $wishlist->product ? $wishlist->product->name : 'Produit supprimé',
                    $wishlist->product ? $wishlist->product->price . '€' : 'N/A',
                    $wishlist->created_at->format('d/m/Y H:i'),
                ];
            })->toArray()
        );

        $this->info("📊 Total: {$wishlists->count()} élément(s) en wishlist");
    }

    private function testToggleWishlist()
    {
        $this->info('🔄 Test de toggle wishlist...');
        
        $user = User::first();
        $product = Product::where('is_active', true)->first();
        
        if (!$user || !$product) {
            $this->error('Utilisateur ou produit introuvable.');
            return;
        }

        $isInWishlist = Wishlist::isInWishlist($user->id, $product->id);
        $this->info("État initial: " . ($isInWishlist ? 'En wishlist' : 'Pas en wishlist'));

        if ($isInWishlist) {
            // Retirer de la wishlist
            Wishlist::where('user_id', $user->id)
                ->where('product_id', $product->id)
                ->delete();
            $this->info("✅ Produit retiré de la wishlist: {$product->name}");
        } else {
            // Ajouter à la wishlist
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);
            $this->info("✅ Produit ajouté à la wishlist: {$product->name}");
        }

        $newState = Wishlist::isInWishlist($user->id, $product->id);
        $this->info("Nouvel état: " . ($newState ? 'En wishlist' : 'Pas en wishlist'));
    }

    private function testWishlistStats()
    {
        $this->info('📊 Statistiques de la wishlist...');
        
        $totalWishlists = Wishlist::count();
        $usersWithWishlists = Wishlist::distinct('user_id')->count();
        $mostWishedProducts = Wishlist::select('product_id', \DB::raw('count(*) as wishlist_count'))
            ->with('product:id,name')
            ->groupBy('product_id')
            ->orderByDesc('wishlist_count')
            ->limit(5)
            ->get();

        $stats = [
            ['Métrique', 'Valeur'],
            ['Total éléments wishlist', $totalWishlists],
            ['Utilisateurs avec wishlist', $usersWithWishlists],
            ['Produits uniques en wishlist', Wishlist::distinct('product_id')->count()],
        ];

        $this->table(['Métrique', 'Valeur'], array_slice($stats, 1));

        if ($mostWishedProducts->isNotEmpty()) {
            $this->info('🏆 Top 5 produits les plus ajoutés en wishlist:');
            $topProductsData = $mostWishedProducts->map(function ($item) {
                return [
                    $item->product ? $item->product->name : 'Produit supprimé',
                    $item->wishlist_count . ' fois'
                ];
            })->toArray();
            
            $this->table(['Produit', 'Nombre d\'ajouts'], $topProductsData);
        }
    }

    private function testCleanup()
    {
        $this->info('🧹 Nettoyage des wishlists de test...');
        
        $user = User::first();
        if (!$user) {
            $this->info('Aucun utilisateur trouvé.');
            return;
        }

        $count = Wishlist::where('user_id', $user->id)->count();
        
        if ($count > 0) {
            Wishlist::where('user_id', $user->id)->delete();
            $this->info("✅ {$count} élément(s) de wishlist supprimé(s) pour l'utilisateur {$user->name}");
        } else {
            $this->info('Aucun élément à supprimer.');
        }
    }
}
