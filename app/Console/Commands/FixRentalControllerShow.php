<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class FixRentalControllerShow extends Command
{
    protected $signature = 'fix:rental-show-method';
    protected $description = 'Corriger la méthode show du RentalController';

    public function handle()
    {
        $this->info('=== CORRECTION METHODE SHOW RENTALCONTROLLER ===');
        $this->newLine();

        $controllerPath = app_path('Http/Controllers/RentalController.php');
        $content = file_get_contents($controllerPath);

        $this->info('1. CORRECTION DE LA METHODE SHOW:');

        // Nouvelle méthode show corrigée
        $newShowMethod = '
    /**
     * Afficher les détails d\'un produit de location
     */
    public function show(Product $product)
    {
        // Vérifier que le produit est louable
        if (!$product->isRentable()) {
            abort(404, \'Ce produit n\'est pas disponible à la location\');
        }

        // Charger les relations
        $product->load([\'category\']);

        // Incrémenter le compteur de vues
        $product->increment(\'views_count\');

        // Récupérer les produits similaires
        $similarProducts = Product::with([\'category\'])
            ->where(\'id\', \'!=\', $product->id)
            ->where(\'category_id\', $product->category_id)
            ->where(\'is_active\', true)
            ->whereIn(\'type\', [\'rental\', \'mixed\'])
            ->where(\'rental_stock\', \'>\', 0)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view(\'web.rentals.show\', compact(\'product\', \'similarProducts\'));
    }';

        // Trouver et remplacer la méthode show existante
        $pattern = '/public function show\(Product \$product\).*?(?=\n    \/\*\*|\n    public|\nclass|\n}$)/s';
        
        if (preg_match($pattern, $content)) {
            $content = preg_replace($pattern, trim($newShowMethod), $content);
            $this->line("   ✅ Méthode show remplacée");
        } else {
            $this->error("   ❌ Méthode show non trouvée");
            return;
        }

        $this->info('2. SAUVEGARDE:');
        file_put_contents($controllerPath, $content);
        $this->line("   ✅ Fichier sauvegardé");

        $this->info('3. VIDAGE DU CACHE:');
        $this->call('config:clear');
        $this->call('route:clear');
        $this->call('view:clear');
        $this->line("   ✅ Cache vidé");

        $this->newLine();
        $this->info('=== CORRECTION TERMINEE ===');
        $this->line("La méthode show a été corrigée pour la nouvelle structure !");
    }
}
