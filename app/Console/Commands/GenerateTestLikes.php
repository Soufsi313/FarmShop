<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductLike;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateTestLikes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:test-likes {--users=100 : Nombre d\'utilisateurs} {--likes=100 : Nombre de likes à générer}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Générer des likes de test répartis entre tous les produits (achat et location)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $usersCount = $this->option('users');
        $likesCount = $this->option('likes');
        
        $this->info("Génération de {$likesCount} likes avec {$usersCount} utilisateurs...");
        
        // Récupérer tous les produits actifs
        $products = Product::where('is_active', true)->get();
        
        if ($products->count() === 0) {
            $this->error('Aucun produit actif trouvé. Veuillez d\'abord créer des produits.');
            return 1;
        }
        
        $this->info("Produits trouvés : {$products->count()}");
        
        // Récupérer les utilisateurs existants
        $users = User::where('role', 'User')->limit($usersCount)->get();
        
        if ($users->count() === 0) {
            $this->error('Aucun utilisateur trouvé. Veuillez d\'abord créer des utilisateurs.');
            return 1;
        }
        
        $this->info("Utilisateurs trouvés : {$users->count()}");
        
        // Commencer une transaction pour les performances
        DB::beginTransaction();
        
        try {
            $generated = 0;
            $skipped = 0;
            $bar = $this->output->createProgressBar($likesCount);
            $bar->start();
            
            for ($i = 0; $i < $likesCount; $i++) {
                // Sélectionner un utilisateur et un produit aléatoirement
                $user = $users->random();
                $product = $products->random();
                
                // Vérifier si ce like existe déjà
                $existingLike = ProductLike::where('user_id', $user->id)
                    ->where('product_id', $product->id)
                    ->first();
                
                if (!$existingLike) {
                    ProductLike::create([
                        'user_id' => $user->id,
                        'product_id' => $product->id,
                        'created_at' => now()->subDays(rand(0, 30)), // Répartir sur les 30 derniers jours
                        'updated_at' => now()->subDays(rand(0, 30))
                    ]);
                    $generated++;
                } else {
                    $skipped++;
                }
                
                $bar->advance();
            }
            
            $bar->finish();
            $this->newLine();
            
            // Mettre à jour les compteurs de likes pour tous les produits
            $this->info('Mise à jour des compteurs de likes...');
            
            foreach ($products as $product) {
                $likesCount = $product->likes()->count();
                $product->update(['likes_count' => $likesCount]);
            }
            
            DB::commit();
            
            $this->info("✅ Génération terminée !");
            $this->info("   - Likes générés : {$generated}");
            $this->info("   - Likes ignorés (doublons) : {$skipped}");
            
            // Statistiques par type de produit
            $purchaseProducts = $products->where('type', 'purchase');
            $rentalProducts = $products->where('type', 'rental');
            
            $purchaseLikes = ProductLike::whereIn('product_id', $purchaseProducts->pluck('id'))->count();
            $rentalLikes = ProductLike::whereIn('product_id', $rentalProducts->pluck('id'))->count();
            
            $this->info("📊 Répartition :");
            $this->info("   - Produits d'achat : {$purchaseProducts->count()} produits, {$purchaseLikes} likes");
            $this->info("   - Produits de location : {$rentalProducts->count()} produits, {$rentalLikes} likes");
            
            return 0;
            
        } catch (\Exception $e) {
            DB::rollback();
            $this->error("Erreur lors de la génération : " . $e->getMessage());
            return 1;
        }
    }
}
