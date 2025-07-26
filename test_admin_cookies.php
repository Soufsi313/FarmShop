<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Test des APIs Admin Cookies ===\n\n";

// Test 1: Vérifier s'il y a des cookies en base
echo "1. Cookies en base de données:\n";
$cookies = App\Models\Cookie::with('user')->latest()->take(5)->get();
echo "   Nombre total: " . App\Models\Cookie::count() . "\n";
foreach ($cookies as $cookie) {
    echo "   - ID: {$cookie->id}, Status: {$cookie->status}, User: " . 
         ($cookie->user ? $cookie->user->email : 'Visiteur') . 
         ", IP: {$cookie->ip_address}\n";
}

echo "\n2. Test de l'API getAdminStats():\n";
try {
    $controller = new App\Http\Controllers\CookieController();
    $response = $controller->getAdminStats();
    $data = json_decode($response->getContent(), true);
    echo "   ✅ Stats récupérées:\n";
    echo "   - Total: " . $data['data']['total_consents'] . "\n";
    echo "   - Acceptés: " . $data['data']['accepted_consents'] . "\n";
    echo "   - Rejetés: " . $data['data']['rejected_consents'] . "\n";
    echo "   - En attente: " . $data['data']['pending_consents'] . "\n";
} catch (Exception $e) {
    echo "   ❌ Erreur: " . $e->getMessage() . "\n";
}

echo "\n3. Test de l'API getAdminIndex():\n";
try {
    $request = Illuminate\Http\Request::create('/api/admin/cookies', 'GET');
    $response = $controller->getAdminIndex($request);
    $data = json_decode($response->getContent(), true);
    echo "   ✅ Liste récupérée:\n";
    echo "   - Nombre d'éléments: " . count($data['data']['data']) . "\n";
    if (count($data['data']['data']) > 0) {
        $first = $data['data']['data'][0];
        echo "   - Premier élément: ID {$first['id']}, Status: {$first['status']}\n";
    }
} catch (Exception $e) {
    echo "   ❌ Erreur: " . $e->getMessage() . "\n";
}

echo "\n4. Création de données de test si nécessaire:\n";
if (App\Models\Cookie::count() == 0) {
    echo "   Aucun cookie trouvé, création de données de test...\n";
    
    // Créer quelques cookies de test
    for ($i = 1; $i <= 3; $i++) {
        $cookie = App\Models\Cookie::create([
            'session_id' => 'admin_test_' . $i,
            'ip_address' => '127.0.0.' . $i,
            'user_agent' => 'Admin Test Browser ' . $i,
            'necessary' => true,
            'analytics' => $i % 2 == 0,
            'marketing' => $i % 3 == 0,
            'preferences' => true,
            'social_media' => false,
            'status' => ['pending', 'accepted', 'rejected'][$i - 1],
            'browser_info' => json_encode(['browser' => 'Test', 'os' => 'Test'])
        ]);
        echo "   ✅ Cookie de test créé: ID {$cookie->id}\n";
    }
} else {
    echo "   Des cookies existent déjà en base.\n";
}

echo "\n=== Fin des tests ===\n";
