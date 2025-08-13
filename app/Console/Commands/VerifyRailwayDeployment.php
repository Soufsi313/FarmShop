<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class VerifyRailwayDeployment extends Command
{
    protected $signature = 'verify:railway-deployment';
    protected $description = 'Vérifier que Railway utilise la bonne version du code';

    public function handle()
    {
        $this->info('=== VERIFICATION DEPLOIEMENT RAILWAY ===');
        $this->newLine();

        // 1. Vérifier la version Git
        $this->info('1. VERSION GIT:');
        if (function_exists('exec')) {
            exec('git log --oneline -1', $gitOutput);
            if (!empty($gitOutput)) {
                $this->line("   Dernier commit: " . $gitOutput[0]);
            }
        }

        // 2. Vérifier la structure des produits
        $this->info('2. STRUCTURE DES PRODUITS:');
        $totalProducts = Product::count();
        $rentalProducts = Product::whereIn('type', ['rental', 'mixed'])->count();
        $availableRentals = Product::whereIn('type', ['rental', 'mixed'])
            ->where('is_active', true)
            ->where('rental_stock', '>', 0)
            ->count();

        $this->line("   Total produits: {$totalProducts}");
        $this->line("   Produits rental/mixed: {$rentalProducts}");
        $this->line("   Locations disponibles: {$availableRentals}");

        // 3. Test d'un produit spécifique
        $this->info('3. TEST PRODUIT TRIEUR:');
        $product = Product::where('slug', 'trieur-graines-vibrant')->first();
        if ($product) {
            $this->line("   ✅ Produit trouvé: {$product->name}");
            $this->line("   Type: {$product->type}");
            $this->line("   Stock rental: {$product->rental_stock}");
            $this->line("   isRentable: " . ($product->isRentable() ? 'Oui' : 'Non'));
        } else {
            $this->error("   ❌ Produit trieur-graines-vibrant non trouvé");
        }

        // 4. Vérifier le RentalController
        $this->info('4. VERIFICATION RENTALCONTROLLER:');
        $controllerPath = app_path('Http/Controllers/RentalController.php');
        $content = file_get_contents($controllerPath);
        
        $checks = [
            'whereIn(\'type\', [\'rental\', \'mixed\'])' => 'Filtrage type correct',
            '->where(\'rental_stock\', \'>\', 0)' => 'Vérification stock rental',
            'rental_price_per_day' => 'Ancien champ (devrait être absent)',
            'is_rental_available' => 'Ancien champ (devrait être absent)'
        ];

        foreach ($checks as $search => $description) {
            $found = strpos($content, $search) !== false;
            if ($search === 'rental_price_per_day' || $search === 'is_rental_available') {
                // Ces champs ne devraient PAS être présents
                if (!$found) {
                    $this->line("   ✅ {$description}: Non présent (correct)");
                } else {
                    $this->error("   ❌ {$description}: Encore présent");
                }
            } else {
                // Ces éléments DEVRAIENT être présents
                if ($found) {
                    $this->line("   ✅ {$description}: Présent");
                } else {
                    $this->error("   ❌ {$description}: Absent");
                }
            }
        }

        // 5. Test de route
        $this->info('5. TEST DE ROUTE:');
        try {
            $url = route('rentals.show', ['product' => 'trieur-graines-vibrant']);
            $this->line("   ✅ Route générée: {$url}");
        } catch (\Exception $e) {
            $this->error("   ❌ Erreur route: " . $e->getMessage());
        }

        $this->newLine();
        $this->info('=== VERIFICATION TERMINEE ===');
        $this->line('Si tous les tests sont OK, les pages de location devraient fonctionner !');
    }
}
