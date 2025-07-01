<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

echo "=== Test de l'API de mise à jour du panier de location ===\n";

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');

// D'abord, testons si nous avons des articles dans le panier
$request = Request::create('/panier-location', 'GET');
$response = $kernel->handle($request);

echo "1. Test de récupération du panier:\n";
echo "Status: " . $response->getStatusCode() . "\n";

if ($response->getStatusCode() === 200) {
    echo "✅ Le panier de location est accessible\n";
} else {
    echo "❌ Problème d'accès au panier: " . $response->getStatusCode() . "\n";
    echo "Contenu: " . substr($response->getContent(), 0, 200) . "...\n";
}

// Test de mise à jour d'un article (nous simulons l'ID 1)
echo "\n2. Test de mise à jour d'un article:\n";

$updateRequest = Request::create('/article-location/1', 'PUT', [], [], [], [
    'CONTENT_TYPE' => 'application/json',
    'HTTP_X_CSRF_TOKEN' => 'test-token',
    'HTTP_ACCEPT' => 'application/json'
], json_encode([
    'start_date' => '2025-07-02',
    'end_date' => '2025-07-06'
]));

$updateResponse = $kernel->handle($updateRequest);

echo "Status: " . $updateResponse->getStatusCode() . "\n";
echo "Content-Type: " . $updateResponse->headers->get('Content-Type') . "\n";

$content = $updateResponse->getContent();
echo "Contenu de la réponse:\n";
echo substr($content, 0, 500) . "\n";

if ($updateResponse->getStatusCode() === 403) {
    echo "❌ Erreur 403 Forbidden - Problème d'authentification\n";
} elseif ($updateResponse->getStatusCode() === 404) {
    echo "❌ Erreur 404 Not Found - Article non trouvé\n";
} elseif ($updateResponse->getStatusCode() === 422) {
    echo "❌ Erreur 422 Validation Error\n";
} elseif ($updateResponse->getStatusCode() === 200) {
    echo "✅ Mise à jour réussie\n";
} else {
    echo "❓ Status inattendu: " . $updateResponse->getStatusCode() . "\n";
}

$kernel->terminate($request, $response);
$kernel->terminate($updateRequest, $updateResponse);

echo "\n=== Test terminé ===\n";
