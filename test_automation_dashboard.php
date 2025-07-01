<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');

echo "🧪 Test d'accès au dashboard d'automatisation...\n\n";

// Test 1: Accès au dashboard d'automatisation
echo "1. Test d'accès au dashboard (/admin/orders/automation):\n";
$request = Request::create('/admin/orders/automation', 'GET');
$response = $kernel->handle($request);
echo "   Status: " . $response->getStatusCode() . "\n";

if ($response->getStatusCode() === 200) {
    echo "   ✅ Dashboard accessible\n";
} else {
    echo "   ❌ Erreur d'accès\n";
    echo "   Content: " . substr($response->getContent(), 0, 200) . "...\n";
}

// Test 2: Test API d'exécution dry-run
echo "\n2. Test API dry-run (/admin/orders/automation/dry-run):\n";
$request = Request::create('/admin/orders/automation/dry-run', 'POST');
$response = $kernel->handle($request);
echo "   Status: " . $response->getStatusCode() . "\n";

// Test 3: Test API statistiques
echo "\n3. Test API statistiques (/admin/orders/automation/stats):\n";
$request = Request::create('/admin/orders/automation/stats', 'GET');
$response = $kernel->handle($request);
echo "   Status: " . $response->getStatusCode() . "\n";

if ($response->getStatusCode() === 200) {
    $data = json_decode($response->getContent(), true);
    if ($data) {
        echo "   ✅ Statistiques récupérées:\n";
        foreach ($data as $key => $value) {
            echo "      - $key: $value\n";
        }
    }
}

$kernel->terminate($request, $response);

echo "\n🎯 Test terminé!\n";
