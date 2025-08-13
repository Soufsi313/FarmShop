<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateRentalBypass extends Command
{
    protected $signature = 'app:create-rental-bypass';
    protected $description = 'Créer une page de bypass pour tester';

    public function handle()
    {
        $this->info('=== CRÉATION BYPASS RENTALS ===');

        // Créer une route ultra-simple qui contourne tout
        $webRoutesPath = base_path('routes/web.php');
        $content = file_get_contents($webRoutesPath);
        
        $bypassRoute = "

// Route de bypass pour debug - TEMPORAIRE
Route::get('/rentals-debug', function () {
    try {
        \$products = \App\Models\Product::where('is_active', true)
            ->whereIn('type', ['rental', 'mixed'])
            ->where('rental_stock', '>', 0)
            ->take(5)
            ->get();
        
        \$html = '<!DOCTYPE html><html><head><title>Rentals Debug</title></head><body>';
        \$html .= '<h1>Page Rentals - Mode Debug</h1>';
        \$html .= '<p>Produits trouvés: ' . \$products->count() . '</p>';
        \$html .= '<ul>';
        foreach (\$products as \$product) {
            \$html .= '<li>' . \$product->name . ' - ' . \$product->price . '€</li>';
        }
        \$html .= '</ul>';
        \$html .= '<p><a href=\"/rentals\">Tester la vraie page rentals</a></p>';
        \$html .= '</body></html>';
        
        return response(\$html);
        
    } catch (\Exception \$e) {
        return response('Erreur: ' . \$e->getMessage(), 500);
    }
});";

        // Ajouter la route de bypass si elle n'existe pas
        if (strpos($content, '/rentals-debug') === false) {
            file_put_contents($webRoutesPath, $content . $bypassRoute);
            $this->info('✅ Route de bypass créée: /rentals-debug');
        }

        // Clear routes
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        
        // Test immédiat
        $this->info('Test de la route bypass...');
        try {
            $request = \Illuminate\Http\Request::create('/rentals-debug', 'GET');
            $kernel = app(\Illuminate\Contracts\Http\Kernel::class);
            $response = $kernel->handle($request);
            
            if ($response->getStatusCode() === 200) {
                $this->info('✅ Route bypass fonctionne');
            } else {
                $this->error('❌ Route bypass en erreur');
            }
        } catch (\Exception $e) {
            $this->error('Erreur bypass: ' . $e->getMessage());
        }

        $this->info('=== BYPASS CRÉÉ ===');
        $this->info('Testez: https://farmshop-production.up.railway.app/rentals-debug');
    }
}
