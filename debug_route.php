<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Test HTTP de la route restock ===\n\n";

// Connecter un admin et récupérer le token CSRF
$admin = App\Models\User::where('role', 'Admin')->first();
Auth::login($admin);

$request = new Illuminate\Http\Request();
$request->setMethod('POST');
$request->headers->set('Content-Type', 'application/json');
$request->headers->set('Accept', 'application/json');

// Simuler la session
session_start();
$token = csrf_token();

echo "✅ Token CSRF: $token\n";

// Test 1: Vérifier que la route est bien définie
$routeCollection = app('router')->getRoutes();
$route = $routeCollection->getByName('admin.products.restock');

if ($route) {
    echo "✅ Route trouvée: {$route->uri()}\n";
    echo "   Méthodes: " . implode(', ', $route->methods()) . "\n";
    echo "   Action: {$route->getActionName()}\n";
} else {
    echo "❌ Route admin.products.restock non trouvée\n";
}

// Test 2: Vérifier que le produit 344 peut être résolu par le model binding
try {
    $product = app('App\Models\Product')->findOrFail(344);
    echo "✅ Model binding OK pour le produit 344: {$product->name}\n";
} catch (Exception $e) {
    echo "❌ Erreur model binding: {$e->getMessage()}\n";
}

// Test 3: Tester la résolution de route
try {
    $url = route('admin.products.restock', ['product' => 344]);
    echo "✅ URL générée: $url\n";
} catch (Exception $e) {
    echo "❌ Erreur génération URL: {$e->getMessage()}\n";
}

echo "\n=== Fin ===\n";
