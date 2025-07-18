<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductLike;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateLikesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:likes {--count=100 : Number of likes to generate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate random likes for products by users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = $this->option('count');
        
        $this->info("Génération de {$count} likes aléatoires...");
        
        // Récupérer tous les utilisateurs et produits actifs
        $users = User::where('role', 'User')->get();
        $products = Product::where('is_active', true)->get();
        
        if ($users->isEmpty()) {
            $this->error('Aucun utilisateur trouvé !');
            return 1;
        }
        
        if ($products->isEmpty()) {
            $this->error('Aucun produit actif trouvé !');
            return 1;
        }
        
        $this->info("Utilisateurs trouvés: {$users->count()}");
        $this->info("Produits actifs trouvés: {$products->count()}");
        
        $generatedLikes = 0;
        $skippedDuplicates = 0;
        
        $bar = $this->output->createProgressBar($count);
        $bar->start();
        
        for ($i = 0; $i < $count; $i++) {
            // Choisir un utilisateur et un produit aléatoires
            $user = $users->random();
            $product = $products->random();
            
            // Vérifier si ce like existe déjà
            $existingLike = ProductLike::where('user_id', $user->id)
                ->where('product_id', $product->id)
                ->first();
            
            if (!$existingLike) {
                // Créer le like
                ProductLike::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                $generatedLikes++;
            } else {
                $skippedDuplicates++;
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        
        // Mettre à jour les compteurs de likes pour tous les produits
        $this->info('Mise à jour des compteurs de likes...');
        
        $products->each(function ($product) {
            $likesCount = $product->likes()->count();
            $product->update(['likes_count' => $likesCount]);
        });
        
        $this->info("✅ Génération terminée !");
        $this->info("📊 Likes créés: {$generatedLikes}");
        $this->info("⚠️ Doublons évités: {$skippedDuplicates}");
        
        // Afficher quelques statistiques
        $totalLikes = ProductLike::count();
        $productsWithLikes = Product::where('likes_count', '>', 0)->count();
        
        $this->info("📈 Total des likes en base: {$totalLikes}");
        $this->info("🎯 Produits avec au moins 1 like: {$productsWithLikes}");
        
        return 0;
    }
}
