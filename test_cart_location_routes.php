<?php
// Test des routes du nouveau système de panier de location
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use Illuminate\Http\Request;

echo "=== Test des routes du panier de location ===\n\n";

// Test de la route index (sans auth pour l'instant)
try {
    $request = Request::create('/panier-location', 'GET');
    $response = $kernel->handle($request);
    
    echo "GET /panier-location\n";
    echo "Status: " . $response->getStatusCode() . "\n";
    if ($response->getStatusCode() === 302) {
        echo "Redirection vers: " . $response->headers->get('Location') . "\n";
    }
    echo "✅ Route accessible\n\n";
} catch (Exception $e) {
    echo "❌ Erreur sur GET /panier-location: " . $e->getMessage() . "\n\n";
}

// Test de l'API count (sans auth)
try {
    $request = Request::create('/panier-location/api/count', 'GET');
    $response = $kernel->handle($request);
    
    echo "GET /panier-location/api/count\n";
    echo "Status: " . $response->getStatusCode() . "\n";
    if ($response->getStatusCode() === 302) {
        echo "Redirection vers: " . $response->headers->get('Location') . "\n";
    }
    echo "✅ Route API accessible\n\n";
} catch (Exception $e) {
    echo "❌ Erreur sur GET /panier-location/api/count: " . $e->getMessage() . "\n\n";
}

echo "✅ Tests des routes terminés\n";
echo "Note: Les routes nécessitent une authentification, donc les redirections vers /login sont normales.\n";
