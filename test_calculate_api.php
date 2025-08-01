<?php

// Test direct de l'API de calcul de location
$product_slug = 'location-abreuvoir-mobile-1000l'; // Remplacez par le slug de votre produit
$base_url = 'http://127.0.0.1:8000';

// Données de test
$data = [
    'start_date' => date('Y-m-d', strtotime('+1 day')),
    'end_date' => date('Y-m-d', strtotime('+3 days')),
    'quantity' => 1
];

$url = "{$base_url}/api/rentals/{$product_slug}/calculate-cost";

echo "=== Test de l'API de calcul de location ===\n";
echo "URL: {$url}\n";
echo "Données: " . json_encode($data, JSON_PRETTY_PRINT) . "\n\n";

// Initialiser cURL
$ch = curl_init();

curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Accept: application/json',
    ],
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_TIMEOUT => 30,
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);

curl_close($ch);

echo "Code HTTP: {$httpCode}\n";

if ($error) {
    echo "Erreur cURL: {$error}\n";
} else {
    echo "Réponse brute: {$response}\n\n";
    
    $decodedResponse = json_decode($response, true);
    if ($decodedResponse) {
        echo "Réponse décodée:\n";
        print_r($decodedResponse);
    } else {
        echo "Impossible de décoder la réponse JSON\n";
    }
}
