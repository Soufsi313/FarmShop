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
use App\Models\User;
use App\Models\Product;

try {
    echo "👥 Utilisateurs disponibles:\n";
    $users = User::limit(3)->get();
    foreach ($users as $user) {
        echo "   ID {$user->id}: {$user->name} ({$user->email})\n";
    }
    
    echo "\n📦 Produits disponibles (recherche tronçonneuse):\n";
    $products = Product::where('name', 'like', '%tronçonneuse%')
                      ->orWhere('name', 'like', '%Tronçonneuse%')
                      ->orWhere('name', 'like', '%chainsaw%')
                      ->limit(5)->get();
    foreach ($products as $product) {
        echo "   ID {$product->id}: {$product->name}\n";
    }
    
    if ($products->isEmpty()) {
        echo "   Aucun produit tronçonneuse trouvé, voici les premiers produits:\n";
        $allProducts = Product::limit(5)->get();
        foreach ($allProducts as $product) {
            echo "   ID {$product->id}: {$product->name}\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
