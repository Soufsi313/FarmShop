<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Debug route restock ===\n\n";

// Test 1: Vérifier les produits qui existent
echo "Produits existants:\n";
$products = App\Models\Product::take(5)->get();
foreach ($products as $product) {
    echo "- ID: {$product->id}, Nom: {$product->name}\n";
}

// Test 2: Tester le model binding pour le produit 1
echo "\nTest model binding pour produit 1:\n";
try {
    $product = App\Models\Product::findOrFail(1);
    echo "✅ Produit 1 trouvé: {$product->name}\n";
} catch (Exception $e) {
    echo "❌ Erreur: {$e->getMessage()}\n";
}

// Test 3: Simuler directement la route
echo "\nTest de la route complète:\n";
try {
    // Connecter un admin
    $admin = App\Models\User::where('role', 'Admin')->first();
    Auth::login($admin);
    
    // Créer une requête HTTP simulée
    $request = Illuminate\Http\Request::create('/admin/products/1/restock', 'POST', [
        'quantity' => 10
    ]);
    $request->headers->set('X-CSRF-TOKEN', 'test-token');
    
    // Obtenir la route
    $route = app('router')->getRoutes()->getByName('admin.products.restock');
    if ($route) {
        echo "✅ Route trouvée: {$route->uri()}\n";
        echo "   Action: {$route->getActionName()}\n";
        
        // Tester la résolution des paramètres
        $route->bind($request);
        $parameters = $route->parameters();
        echo "   Paramètres: " . json_encode($parameters) . "\n";
        
        if (isset($parameters['product'])) {
            $product = $parameters['product'];
            if ($product instanceof App\Models\Product) {
                echo "✅ Model binding OK: {$product->name}\n";
            } else {
                echo "❌ Model binding a retourné: " . gettype($product) . "\n";
            }
        }
        
    } else {
        echo "❌ Route non trouvée\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur dans le test de route: {$e->getMessage()}\n";
    echo "   File: {$e->getFile()}:{$e->getLine()}\n";
}

echo "\n=== Fin ===\n";
