<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Test de l'authentification API ===\n";

// Simuler une requête HTTP à l'API cart
$url = 'http://127.0.0.1:8000/api/cart';
$cookieFile = tempnam(sys_get_temp_dir(), 'test_cookies');

// Première requête : obtenir les cookies de session
echo "1. Connexion à la page principale pour obtenir les cookies...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:8000/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo "Erreur cURL : " . curl_error($ch) . "\n";
    exit;
}

echo "2. Test de l'API cart avec les cookies...\n";
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'X-Requested-With: XMLHttpRequest'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

echo "Code de statut HTTP : $httpCode\n";
echo "Réponse : $response\n";

curl_close($ch);
unlink($cookieFile);

echo "=== Fin du test ===\n";
