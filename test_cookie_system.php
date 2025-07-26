<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Test du système de cookies FarmShop ===\n\n";

// Test 1: Compter les cookies existants
echo "1. Nombre de cookies en base: " . App\Models\Cookie::count() . "\n";

// Test 2: Vérifier la structure de la table
echo "2. Structure de la table cookies:\n";
$columns = DB::select("DESCRIBE cookies");
foreach ($columns as $column) {
    echo "   - {$column->Field} ({$column->Type})\n";
}

// Test 3: Créer un cookie de test
echo "\n3. Test de création d'un cookie...\n";
try {
    $cookie = App\Models\Cookie::create([
        'session_id' => 'test_' . uniqid(),
        'ip_address' => '127.0.0.1',
        'user_agent' => 'Test Browser',
        'necessary' => true,
        'analytics' => false,
        'marketing' => false,
        'preferences' => false,
        'social_media' => false,
        'status' => 'pending',
        'browser_info' => json_encode(['browser' => 'Test', 'os' => 'Test'])
    ]);
    
    echo "   ✅ Cookie créé avec succès (ID: {$cookie->id})\n";
    
    // Test 4: Mise à jour du cookie
    $cookie->updatePreferences([
        'analytics' => true,
        'marketing' => false,
        'preferences' => true,
        'social_media' => false
    ]);
    
    echo "   ✅ Préférences mises à jour\n";
    echo "   📊 Analytics: " . ($cookie->analytics ? 'Oui' : 'Non') . "\n";
    echo "   🎯 Marketing: " . ($cookie->marketing ? 'Oui' : 'Non') . "\n";
    echo "   ⚙️ Préférences: " . ($cookie->preferences ? 'Oui' : 'Non') . "\n";
    echo "   📱 Réseaux sociaux: " . ($cookie->social_media ? 'Oui' : 'Non') . "\n";
    
    // Nettoyer le test
    $cookie->delete();
    echo "   🗑️ Cookie de test supprimé\n";
    
} catch (Exception $e) {
    echo "   ❌ Erreur: " . $e->getMessage() . "\n";
}

// Test 5: Vérifier les routes API
echo "\n4. Test des routes API:\n";
$routes = [
    '/api/cookies/preferences',
    '/api/cookies/accept-all', 
    '/api/cookies/reject-all'
];

foreach ($routes as $route) {
    echo "   - Route {$route}: ";
    try {
        $routeExists = Route::has(str_replace('/api/', 'api.', str_replace('/', '.', str_replace('-', '-', $route))));
        echo $routeExists ? "✅ OK" : "❌ Manquante";
    } catch (Exception $e) {
        echo "❓ Vérification impossible";
    }
    echo "\n";
}

echo "\n=== Fin des tests ===\n";
