<?php
require_once __DIR__ . '/vendor/autoload.php';
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
$app = Application::configure(basePath: __DIR__)
    ->withRouting(
        web: __DIR__.'/routes/web.php',
        api: __DIR__.'/routes/api.php',
        commands: __DIR__.'/routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {})
    ->withExceptions(function (Exceptions $exceptions) {})
    ->create();
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
use App\Models\Product;

try {
    echo "🔍 Recherche de produits adaptés à la location:\n\n";
    
    // Chercher des produits qui semblent être du matériel
    $keywords = ['tronçonneuse', 'tondeuse', 'tracteur', 'motoculteur', 'outils', 'machine', 'équipement'];
    
    foreach ($keywords as $keyword) {
        $products = Product::where('name', 'like', "%{$keyword}%")
                          ->orWhere('description', 'like', "%{$keyword}%")
                          ->limit(3)->get();
        
        if ($products->count() > 0) {
            echo "📦 Produits contenant '{$keyword}':\n";
            foreach ($products as $product) {
                echo "   ID {$product->id}: {$product->name}\n";
            }
            echo "\n";
        }
    }
    
    // Si aucun produit spécialisé, prendre les derniers produits créés
    echo "📦 Derniers produits créés:\n";
    $latestProducts = Product::orderBy('id', 'desc')->limit(10)->get();
    foreach ($latestProducts as $product) {
        echo "   ID {$product->id}: {$product->name}\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
