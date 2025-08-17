<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncLikesCount extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'likes:sync';

    /**
     * The console command description.
     */
    protected $description = 'Synchronise les compteurs de likes des produits avec les données réelles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Synchronisation des compteurs de likes...');
        $this->newLine();

        // Récupérer tous les produits
        $products = Product::all();
        $bar = $this->output->createProgressBar($products->count());
        $bar->start();

        $totalSynced = 0;
        $totalLikes = 0;

        foreach ($products as $product) {
            // Compter les vrais likes depuis la table product_likes
            $realLikesCount = DB::table('product_likes')
                ->where('product_id', $product->id)
                ->count();
            
            // Mettre à jour seulement si différent
            if ($product->likes_count !== $realLikesCount) {
                $product->update(['likes_count' => $realLikesCount]);
                $totalSynced++;
            }
            
            $totalLikes += $realLikesCount;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("🎉 Synchronisation terminée !");
        $this->table(['Statistique', 'Valeur'], [
            ['Produits traités', $products->count()],
            ['Produits mis à jour', $totalSynced],
            ['Total des likes', $totalLikes],
        ]);

        return 0;
    }
}
