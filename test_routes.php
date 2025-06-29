<?php

// Test des routes cookies via HTTP
$baseUrl = 'http://127.0.0.1:8000';

function testRoute($url, $method = 'GET', $data = null) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'Content-Type: application/json'
    ]);
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    // Séparer headers et body
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $headers = substr($response, 0, $headerSize);
    $body = substr($response, $headerSize);
    
    return [
        'code' => $httpCode,
        'headers' => $headers,
        'body' => $body
    ];
}

echo "🧪 Test des routes cookies\n";
echo "=========================\n\n";

// Test 1: Route publique pour récupérer les cookies par catégorie
echo "1. Test route: GET /cookies/by-category\n";
$response = testRoute($baseUrl . '/cookies/by-category');
echo "   Status: {$response['code']}\n";
if ($response['code'] == 200) {
    $data = json_decode($response['body'], true);
    echo "   ✅ Réponse reçue avec " . count($data) . " catégories\n";
    foreach ($data as $category => $cookies) {
        echo "   - {$category}: " . count($cookies) . " cookie(s)\n";
    }
} else {
    echo "   ❌ Erreur: {$response['body']}\n";
}

echo "\n";

// Test 2: Route publique pour récupérer le statut de consentement
echo "2. Test route: GET /cookies/status\n";
$response = testRoute($baseUrl . '/cookies/status');
echo "   Status: {$response['code']}\n";
if ($response['code'] == 200) {
    $data = json_decode($response['body'], true);
    echo "   ✅ Statut reçu\n";
    echo "   - Has consent: " . ($data['has_consent'] ? 'true' : 'false') . "\n";
} else {
    echo "   ❌ Erreur: {$response['body']}\n";
}

echo "\n";

// Test 3: Route pour la politique des cookies
echo "3. Test route: GET /cookies/policy\n";
$response = testRoute($baseUrl . '/cookies/policy');
echo "   Status: {$response['code']}\n";
if ($response['code'] == 200) {
    echo "   ✅ Politique des cookies récupérée\n";
} else {
    echo "   ❌ Erreur: {$response['body']}\n";
}

echo "\n";

// Test 4: Route pour accepter tous les cookies
echo "4. Test route: POST /cookies/accept-all\n";
$response = testRoute($baseUrl . '/cookies/accept-all', 'POST');
echo "   Status: {$response['code']}\n";
if ($response['code'] == 200) {
    $data = json_decode($response['body'], true);
    echo "   ✅ Consentement créé\n";
    echo "   - Success: " . ($data['success'] ? 'true' : 'false') . "\n";
    echo "   - Message: {$data['message']}\n";
} else {
    echo "   ❌ Erreur: {$response['body']}\n";
}

echo "\n=========================\n";
echo "✅ Tests terminés !\n";
