<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Product;
use App\Models\ProductLike;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TestProductLike extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:product-like';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test ProductLike functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Test du système de likes de produits...');
        $this->newLine();

        try {
            // 1. Récupérer des utilisateurs et produits de test
            $user = User::where('email', 'user@example.com')->first();
            $admin = User::where('email', 'admin@example.com')->first();
            
            if (!$user) {
                $this->warn('⚠️ Aucun utilisateur test trouvé. Création d\'un utilisateur...');
                $user = User::create([
                    'name' => 'Test User Like',
                    'username' => 'test-like-user',
                    'email' => 'test-like@example.com',
                    'password' => bcrypt('password'),
                    'email_verified_at' => now()
                ]);
                $this->info('✅ Utilisateur test créé: ' . $user->email);
            }

            $products = Product::where('is_active', true)->limit(3)->get();
            
            if ($products->isEmpty()) {
                $this->error('❌ Aucun produit actif trouvé pour les tests.');
                return;
            }

            $this->info('📦 Produits de test disponibles:');
            foreach ($products as $product) {
                $this->line("  - {$product->name} (ID: {$product->id})");
            }
            $this->newLine();

            // 2. Test des likes
            $this->info('🔍 Test des likes...');
            
            foreach ($products as $product) {
                // Vérifier si le produit est déjà liké
                $isLiked = ProductLike::isLiked($user->id, $product->id);
                $this->line("  • Produit '{$product->name}' - Liké: " . ($isLiked ? 'Oui' : 'Non'));
                
                if (!$isLiked) {
                    // Liker le produit
                    ProductLike::create([
                        'user_id' => $user->id,
                        'product_id' => $product->id
                    ]);
                    
                    // Incrémenter le compteur
                    $product->increment('likes_count');
                    
                    $this->info("  ✅ Produit '{$product->name}' liké avec succès!");
                } else {
                    $this->warn("  ⚠️ Produit '{$product->name}' déjà liké");
                }
            }
            $this->newLine();

            // 3. Test des statistiques
            $this->info('📊 Statistiques des likes:');
            
            $stats = [
                'total_likes' => ProductLike::count(),
                'users_who_liked' => ProductLike::distinct('user_id')->count(),
                'products_with_likes' => ProductLike::distinct('product_id')->count(),
                'user_likes_count' => ProductLike::where('user_id', $user->id)->count()
            ];
            
            foreach ($stats as $key => $value) {
                $this->line("  • " . str_replace('_', ' ', ucfirst($key)) . ": {$value}");
            }
            $this->newLine();

            // 4. Test des produits les plus likés
            $this->info('🏆 Top 3 des produits les plus likés:');
            $topLiked = Product::where('is_active', true)
                ->where('likes_count', '>', 0)
                ->orderByDesc('likes_count')
                ->limit(3)
                ->get();
                
            if ($topLiked->isNotEmpty()) {
                foreach ($topLiked as $index => $product) {
                    $this->line("  " . ($index + 1) . ". {$product->name} - {$product->likes_count} like(s)");
                }
            } else {
                $this->warn('  Aucun produit liké trouvé');
            }
            $this->newLine();

            // 5. Test de suppression d'un like
            $firstProduct = $products->first();
            $like = ProductLike::where('user_id', $user->id)
                ->where('product_id', $firstProduct->id)
                ->first();
                
            if ($like) {
                $this->info("🗑️ Test de suppression du like pour '{$firstProduct->name}'...");
                $like->delete();
                $firstProduct->decrement('likes_count');
                $this->info('✅ Like supprimé avec succès!');
            }
            $this->newLine();

            // 6. Affichage des likes restants de l'utilisateur
            $userLikes = ProductLike::where('user_id', $user->id)
                ->with('product:id,name')
                ->get();
                
            $this->info("❤️ Likes restants de l'utilisateur '{$user->name}':");
            if ($userLikes->isNotEmpty()) {
                foreach ($userLikes as $like) {
                    $this->line("  • {$like->product->name}");
                }
            } else {
                $this->line('  Aucun like');
            }
            $this->newLine();

            // 7. Test de vérification
            $this->info('🔍 Test de vérification des likes:');
            foreach ($products as $product) {
                $isLiked = ProductLike::isLiked($user->id, $product->id);
                $this->line("  • {$product->name}: " . ($isLiked ? '❤️ Liké' : '🤍 Non liké'));
            }

            $this->newLine();
            $this->info('🎉 Tests des likes de produits terminés avec succès!');

        } catch (\Exception $e) {
            $this->error('❌ Erreur lors des tests: ' . $e->getMessage());
            $this->error('Trace: ' . $e->getTraceAsString());
        }
    }
}
