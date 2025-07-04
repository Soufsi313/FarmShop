<?php
// Fichier de test pour debug du checkout
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Simuler une requête vers le checkout
$request = Illuminate\Http\Request::create('/checkout', 'GET');
$response = $kernel->handle($request);

echo "Status: " . $response->getStatusCode() . "\n";
echo "Content: \n" . $response->getContent() . "\n";
