<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FixRentalsRouteFinal extends Command
{
    protected $signature = 'app:fix-rentals-route-final';
    protected $description = 'Correction définitive de la route /rentals';

    public function handle()
    {
        $this->info('=== CORRECTION DÉFINITIVE ROUTE /RENTALS ===');

        try {
            // Remplacer complètement la définition de la route rentals
            $webRoutesPath = base_path('routes/web.php');
            $content = file_get_contents($webRoutesPath);
            
            // Supprimer l'ancienne définition et la remplacer par une version qui fonctionne
            $newRentalsRoute = "
// Route rentals corrigée
Route::get('/rentals', function () {
    try {
        \$products = \App\Models\Product::with(['category'])
            ->where('is_active', true)
            ->whereIn('type', ['rental', 'mixed'])
            ->where('rental_stock', '>', 0)
            ->paginate(12);

        \$rentalCategories = \App\Models\Category::whereHas('products', function(\$query) {
            \$query->whereIn('type', ['rental', 'mixed'])->where('is_active', true);
        })->get();

        \$priceStats = \App\Models\Product::whereIn('type', ['rental', 'mixed'])
            ->where('is_active', true)
            ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
            ->first();

        return view('web.rentals.index', compact('products', 'rentalCategories', 'priceStats'));
    } catch (\Exception \$e) {
        return response('Erreur 500: ' . \$e->getMessage(), 500);
    }
})->name('rentals.index');";

            // Supprimer l'ancienne route rentals
            $pattern = '/Route::get\(\s*[\'"]\/rentals[\'"]\s*,.*?\)->name\(\s*[\'"]rentals\.index[\'"]\s*\);/s';
            $content = preg_replace($pattern, '', $content);
            
            // Ajouter la nouvelle route
            $content .= $newRentalsRoute;
            
            file_put_contents($webRoutesPath, $content);
            $this->info('✅ Route /rentals remplacée par version fonctionnelle');

            // Clear cache
            \Illuminate\Support\Facades\Artisan::call('route:clear');
            $this->info('✅ Cache routes nettoyé');

            // Test immédiat
            $this->info('Test de la route corrigée...');
            
            $request = \Illuminate\Http\Request::create('/rentals', 'GET');
            $response = app('router')->dispatch($request);
            $statusCode = $response->getStatusCode();
            
            if ($statusCode === 200) {
                $this->info('🎉 SUCCÈS! Route /rentals fonctionne maintenant (Code 200)');
            } else {
                $this->error("❌ Toujours en erreur: Code {$statusCode}");
                $this->error("Contenu: " . substr($response->getContent(), 0, 200));
            }

        } catch (\Exception $e) {
            $this->error('Erreur lors de la correction:');
            $this->error($e->getMessage());
        }

        $this->info('=== CORRECTION TERMINÉE ===');
    }
}
