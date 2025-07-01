<?php
// Test simple de l'API de panier de location avec authentification

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Démarrer Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Créer une requête de test
$request = Request::create('/panier-location/api/count', 'GET');
$response = $kernel->handle($request);

echo "=== Test API Cart Location Count ===\n";
echo "Status: " . $response->getStatusCode() . "\n";
echo "Content: " . $response->getContent() . "\n";

// Test ajout au panier
echo "\n=== Test Ajout au Panier Location ===\n";

// Simuler une requête d'ajout avec données de test
$request2 = Request::create('/panier-location/ajouter', 'POST', [], [], [], [
    'CONTENT_TYPE' => 'application/json',
    'HTTP_X_CSRF_TOKEN' => 'test-token'
], json_encode([
    'product_id' => 1,
    'start_date' => '2025-07-02',
    'end_date' => '2025-07-04'
]));

$response2 = $kernel->handle($request2);

echo "Status: " . $response2->getStatusCode() . "\n";
echo "Content: " . $response2->getContent() . "\n";

$kernel->terminate($request, $response);
$kernel->terminate($request2, $response2);
