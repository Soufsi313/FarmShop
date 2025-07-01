<?php

echo "🔧 Test de correction du dashboard d'automatisation\n\n";

// Test simple d'accès à l'URL
$url = "http://127.0.0.1:8000/admin/orders/automation";

echo "📍 Test d'accès à: $url\n";

// Utiliser curl pour tester l'accès
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'User-Agent: Dashboard Test Script'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo "❌ Erreur cURL: $error\n";
} else {
    echo "📡 Code de réponse: $httpCode\n";
    
    if ($httpCode === 200) {
        echo "✅ Dashboard accessible !\n";
        if (strpos($response, 'RouteNotFoundException') !== false) {
            echo "❌ Mais il y a encore une erreur de route\n";
        } else {
            echo "✅ Aucune erreur de route détectée\n";
        }
    } elseif ($httpCode === 302) {
        echo "🔄 Redirection (probablement vers login) - Normal\n";
    } else {
        echo "⚠️  Code de réponse inattendu\n";
    }
}

echo "\n🎯 Test terminé!\n";
