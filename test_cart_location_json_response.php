<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');

// Créer une requête POST pour tester l'ajout au panier de location
$request = Request::create('/panier-location/ajouter', 'POST', [], [], [], [
    'CONTENT_TYPE' => 'application/json',
    'HTTP_X_CSRF_TOKEN' => 'test-token'
], json_encode([
    'product_id' => 1,
    'start_date' => '2025-07-02',
    'end_date' => '2025-07-06'
]));

$response = $kernel->handle($request);

echo "Status: " . $response->getStatusCode() . "\n";
echo "Content-Type: " . $response->headers->get('Content-Type') . "\n";
echo "Content Length: " . strlen($response->getContent()) . "\n";
echo "\n=== CONTENT START ===\n";
echo $response->getContent();
echo "\n=== CONTENT END ===\n";

// Vérifier si c'est du JSON valide
$content = $response->getContent();
$decoded = json_decode($content, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "\nERREUR JSON: " . json_last_error_msg() . "\n";
    echo "Premiers caractères: " . substr($content, 0, 100) . "\n";
    echo "Derniers caractères: " . substr($content, -100) . "\n";
} else {
    echo "\nJSON VALIDE\n";
    echo "Données décodées: " . print_r($decoded, true) . "\n";
}

$kernel->terminate($request, $response);
