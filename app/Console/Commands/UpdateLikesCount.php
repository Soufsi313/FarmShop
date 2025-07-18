<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateLikesCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:likes-count';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mettre à jour les compteurs de likes pour tous les produits';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Mise à jour des compteurs de likes...');
        
        $products = Product::all();
        $bar = $this->output->createProgressBar($products->count());
        $bar->start();
        
        $updated = 0;
        
        foreach ($products as $product) {
            $likesCount = $product->likes()->count();
            
            if ($product->likes_count !== $likesCount) {
                $product->update(['likes_count' => $likesCount]);
                $updated++;
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        
        $this->info("✅ Mise à jour terminée !");
        $this->info("   - Produits traités : {$products->count()}");
        $this->info("   - Produits mis à jour : {$updated}");
        
        // Afficher quelques statistiques
        $totalLikes = DB::table('product_likes')->count();
        $productsWithLikes = Product::where('likes_count', '>', 0)->count();
        
        $this->info("📊 Statistiques :");
        $this->info("   - Total likes en base : {$totalLikes}");
        $this->info("   - Produits avec likes : {$productsWithLikes}");
        
        return 0;
    }
}
