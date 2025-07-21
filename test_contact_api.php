<?php

require_once __DIR__ . '/vendor/autoload.php';

echo "=== Test API Contact ===\n";

$url = 'http://127.0.0.1:8000/api/contact';
$data = [
    'name' => 'Test User',
    'email' => 'test@example.com',
    'phone' => '+32 123 45 67',
    'subject' => 'Test de contact',
    'message' => 'Ceci est un message de test pour vérifier le système de contact.',
    'reason' => 'support_technique',
    'priority' => 'normal'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json',
    'X-Requested-With: XMLHttpRequest'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_VERBOSE, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);

curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Error: $error\n";
echo "Response: $response\n";

if ($httpCode === 201) {
    echo "✅ Test réussi !\n";
} else {
    echo "❌ Test échoué\n";
}
