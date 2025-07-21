<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Route;

echo "=== VÉRIFICATION ROUTES ADMIN PRODUITS ===\n";

// Obtenir toutes les routes
$routes = Route::getRoutes();

echo "Routes trouvées pour les produits admin:\n";

foreach ($routes as $route) {
    $uri = $route->uri();
    $methods = implode('|', $route->methods());
    $name = $route->getName();
    
    // Filtrer les routes admin/products
    if (strpos($uri, 'admin/products') !== false) {
        echo "{$methods} {$uri}";
        if ($name) {
            echo " -> {$name}";
        }
        echo "\n";
    }
}

echo "\n=== TEST ROUTE DELETE SPÉCIFIQUE ===\n";

// Chercher spécifiquement la route DELETE pour un produit
$deleteRoute = null;
foreach ($routes as $route) {
    if (strpos($route->uri(), 'admin/products/{product}') !== false && 
        in_array('DELETE', $route->methods())) {
        $deleteRoute = $route;
        break;
    }
}

if ($deleteRoute) {
    echo "✅ Route DELETE trouvée: {$deleteRoute->uri()}\n";
    echo "Nom: {$deleteRoute->getName()}\n";
    echo "Contrôleur: {$deleteRoute->getActionName()}\n";
} else {
    echo "❌ Route DELETE pour admin/products/{product} NON trouvée\n";
}
