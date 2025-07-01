<?php

require_once 'vendor/autoload.php';

// Test simple de la nouvelle méthode addSimple
echo "=== Test de l'API addSimple ===\n\n";

$url = 'http://127.0.0.1:8000/panier-location/ajouter';
$data = [
    'product_id' => 1,
    'start_date' => '2025-07-02',
    'end_date' => '2025-07-06'
];

// Initialiser cURL
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json',
    'X-CSRF-TOKEN: test-token' // Ce sera probablement rejeté, mais on teste
]);

// Exécuter la requête
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);

curl_close($ch);

echo "Code de réponse HTTP: $httpCode\n";

if ($error) {
    echo "Erreur cURL: $error\n";
} else {
    echo "Réponse:\n";
    echo $response . "\n";
    
    // Tenter de décoder la réponse JSON
    $decoded = json_decode($response, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        echo "\nJSON valide:\n";
        print_r($decoded);
    } else {
        echo "\nErreur JSON: " . json_last_error_msg() . "\n";
        echo "Premiers 200 caractères de la réponse:\n";
        echo substr($response, 0, 200) . "\n";
    }
}

echo "\n=== Test terminé ===\n";
