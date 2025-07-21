<?php

echo "=== Test API Response ===\n";

$url = 'http://127.0.0.1:8000/admin/messages/117/respond';
$data = [
    'response' => 'Bonjour, merci pour votre message. Nous avons bien reçu votre demande et nous vous répondons dans les plus brefs délais.'
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

if ($httpCode === 200) {
    echo "✅ Test réussi !\n";
} else {
    echo "❌ Test échoué\n";
}
