<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use App\Models\User;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');

echo "=== Test avec utilisateur authentifié ===\n";

// Trouver un utilisateur pour la session
$user = User::first();
if (!$user) {
    echo "Aucun utilisateur trouvé dans la base de données.\n";
    exit(1);
}

echo "Utilisateur: {$user->name} (ID: {$user->id})\n";

// Simuler une session Laravel avec utilisateur connecté
$request = Request::create('/panier-location/ajouter', 'POST', [], [], [], [
    'CONTENT_TYPE' => 'application/json',
    'HTTP_X_CSRF_TOKEN' => 'test-token'
], json_encode([
    'product_id' => 1,
    'start_date' => '2025-07-02',
    'end_date' => '2025-07-06'
]));

// Simuler une session avec un utilisateur connecté
$request->setLaravelSession($app['session.store']);
$app['auth']->login($user);

$response = $kernel->handle($request);

echo "\nRéponse de l'API:\n";
echo "Status: " . $response->getStatusCode() . "\n";
echo "Content-Type: " . $response->headers->get('Content-Type') . "\n";
echo "Content Length: " . strlen($response->getContent()) . "\n";

// Afficher le contenu
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
    echo "\nJSON VALIDE\n";
    echo "Données décodées: " . print_r($decoded, true) . "\n";
}

$kernel->terminate($request, $response);
