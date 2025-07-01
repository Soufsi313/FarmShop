<?php

require_once 'vendor/autoload.php';

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "🧪 Test de l'API d'annulation...\n\n";

// Test 1: Vérifier l'éligibilité d'annulation
echo "1. Test vérification éligibilité (commande #6):\n";
$request = Illuminate\Http\Request::create('/admin/orders/6/cancellation-check', 'GET');
$response = $kernel->handle($request);
echo "   Status: " . $response->getStatusCode() . "\n";

if ($response->getStatusCode() === 200) {
    $data = json_decode($response->getContent(), true);
    if ($data) {
        echo "   ✅ Réponse: \n";
        foreach ($data as $key => $value) {
            echo "      - $key: " . (is_bool($value) ? ($value ? 'true' : 'false') : $value) . "\n";
        }
    }
} else {
    echo "   ❌ Erreur: " . $response->getContent() . "\n";
}

echo "\n2. Test de l'interface de recherche:\n";
$request = Illuminate\Http\Request::create('/admin/orders/cancellation', 'GET');
$response = $kernel->handle($request);
echo "   Status: " . $response->getStatusCode() . "\n";

if ($response->getStatusCode() === 200) {
    echo "   ✅ Interface accessible\n";
} elseif ($response->getStatusCode() === 302) {
    echo "   🔄 Redirection (probablement vers login)\n";
} else {
    echo "   ❌ Erreur d'accès\n";
}

$kernel->terminate($request, $response);

echo "\n🎯 Test terminé!\n";
echo "📝 Pour tester l'annulation complète, utilisez l'interface web.\n";
