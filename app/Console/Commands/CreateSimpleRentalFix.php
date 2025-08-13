<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateSimpleRentalFix extends Command
{
    protected $signature = 'app:create-simple-rental-fix';
    protected $description = 'Créer une route de test simple pour identifier le problème';

    public function handle()
    {
        $this->info('=== CRÉATION ROUTE DE TEST SIMPLE ===');

        // Ajouter une route de test dans web.php
        $webRoutesPath = base_path('routes/web.php');
        $content = file_get_contents($webRoutesPath);
        
        // Vérifier si la route de test n'existe pas déjà
        if (strpos($content, '/test-rentals') === false) {
            $testRoute = "

// Route de test pour debug rentals
Route::get('/test-rentals', function () {
    try {
        \$products = \App\Models\Product::where('is_active', true)
            ->whereIn('type', ['rental', 'mixed'])
            ->where('rental_stock', '>', 0)
            ->limit(5)
            ->get();
            
        return response()->json([
            'status' => 'success',
            'products_count' => \$products->count(),
            'message' => 'Test rentals OK'
        ]);
    } catch (\Exception \$e) {
        return response()->json([
            'status' => 'error',
            'message' => \$e->getMessage(),
            'file' => \$e->getFile(),
            'line' => \$e->getLine()
        ], 500);
    }
});";

            file_put_contents($webRoutesPath, $content . $testRoute);
            $this->info('✅ Route de test ajoutée: /test-rentals');
        }

        // Maintenant remplaçons temporairement la route rentals par une version ultra-simple
        $simpleRoute = "

// Route rentals simplifiée pour debug
Route::get('/rentals-simple', function () {
    return 'Page rentals fonctionne!';
});";

        if (strpos($content, '/rentals-simple') === false) {
            file_put_contents($webRoutesPath, file_get_contents($webRoutesPath) . $simpleRoute);
            $this->info('✅ Route simple ajoutée: /rentals-simple');
        }

        // Test immédiat
        $this->info('Test des nouvelles routes...');
        
        try {
            $request = \Illuminate\Http\Request::create('/test-rentals', 'GET');
            $response = app('router')->dispatch($request);
            $this->info('Test route status: ' . $response->getStatusCode());
        } catch (\Exception $e) {
            $this->error('Erreur test route: ' . $e->getMessage());
        }

        $this->info('=== ROUTES DE TEST CRÉÉES ===');
        $this->info('Testez:');
        $this->info('- https://farmshop-production.up.railway.app/test-rentals');
        $this->info('- https://farmshop-production.up.railway.app/rentals-simple');
    }
}
