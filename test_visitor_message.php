<?php

// Test rapide pour envoyer un message de visiteur

require_once 'vendor/autoload.php';

$data = [
    'name' => 'Jean Dupont',
    'email' => 'jean.dupont@example.com',
    'phone' => '0123456789',
    'subject' => 'Question sur les légumes bio',
    'message' => 'Bonjour, je voudrais savoir si vous avez des pommes de terre biologiques disponibles pour la livraison cette semaine ?',
    'reason' => 'mes_achats',
    'priority' => 'normal'
];

// Simuler une requête POST
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api/contact');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Code HTTP: " . $httpCode . PHP_EOL;
echo "Réponse: " . $response . PHP_EOL;

if ($httpCode === 201) {
    $result = json_decode($response, true);
    if ($result['success']) {
        echo "✅ Message de contact créé avec succès!" . PHP_EOL;
        echo "Référence: " . $result['data']['reference'] . PHP_EOL;
    }
} else {
    echo "❌ Erreur lors de l'envoi du message" . PHP_EOL;
}
