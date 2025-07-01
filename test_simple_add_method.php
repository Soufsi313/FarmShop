<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use App\Models\User;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');

echo "=== Test nouvelle méthode addSimple ===\n";

// Simuler une requête avec un utilisateur connecté via session
$request = Request::create('/panier-location/ajouter', 'POST', [], [], [], [
    'CONTENT_TYPE' => 'application/json',
], json_encode([
    'product_id' => 1,
    'start_date' => '2025-07-02',
    'end_date' => '2025-07-06'
]));

// Créer une session fictive pour simuler un utilisateur connecté
$session = $app['session.store'];
$session->put('_token', 'test-token');
$session->put('login_web_' . sha1('web'), 1); // Simuler l'ID utilisateur 1 connecté

$request->setLaravelSession($session);
$request->headers->set('X-CSRF-TOKEN', 'test-token');

$response = $kernel->handle($request);

echo "Status: " . $response->getStatusCode() . "\n";
echo "Content-Type: " . $response->headers->get('Content-Type') . "\n";
echo "Content Length: " . strlen($response->getContent()) . "\n";

$content = $response->getContent();
echo "\n=== CONTENT START ===\n";
echo $content;
echo "\n=== CONTENT END ===\n";

// Vérifier si c'est du JSON valide
$decoded = json_decode($content, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "\nERREUR JSON: " . json_last_error_msg() . "\n";
    echo "Premiers 200 caractères: " . substr($content, 0, 200) . "\n";
} else {
    echo "\nJSON VALIDE ✅\n";
    echo "Données décodées: " . print_r($decoded, true) . "\n";
}

$kernel->terminate($request, $response);
