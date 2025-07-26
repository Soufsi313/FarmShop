<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Test du système de cookies ===\n\n";

// Test 1: Vérifier la structure de la table
echo "1. Vérification de la table cookies...\n";
try {
    $count = \App\Models\Cookie::count();
    echo "✅ Table cookies accessible, {$count} enregistrements trouvés\n\n";
} catch (Exception $e) {
    echo "❌ Erreur table cookies: " . $e->getMessage() . "\n\n";
    exit;
}

// Test 2: Créer un cookie de test
echo "2. Création d'un cookie de test...\n";
try {
    $cookie = \App\Models\Cookie::create([
        'session_id' => 'test_session_' . time(),
        'ip_address' => '127.0.0.1',
        'user_agent' => 'Test Browser',
        'necessary' => true,
        'analytics' => true,
        'marketing' => false,
        'preferences' => true,
        'social_media' => false,
        'status' => 'accepted',
        'consent_version' => '1.0',
        'page_url' => '/test',
        'referer' => 'https://test.com'
    ]);
    
    echo "✅ Cookie créé avec l'ID: {$cookie->id}\n";
    echo "   - Status: {$cookie->status}\n";
    echo "   - Analytics: " . ($cookie->analytics ? 'Oui' : 'Non') . "\n";
    echo "   - Marketing: " . ($cookie->marketing ? 'Oui' : 'Non') . "\n\n";
} catch (Exception $e) {
    echo "❌ Erreur création cookie: " . $e->getMessage() . "\n\n";
}

// Test 3: Tester les méthodes du modèle
echo "3. Test des méthodes du modèle...\n";
try {
    $cookie = \App\Models\Cookie::latest()->first();
    if ($cookie) {
        $summary = $cookie->getPreferencesSummary();
        echo "✅ Préférences récupérées:\n";
        echo "   - Necessary: " . ($summary['necessary'] ? 'Oui' : 'Non') . "\n";
        echo "   - Analytics: " . ($summary['analytics'] ? 'Oui' : 'Non') . "\n";
        echo "   - Marketing: " . ($summary['marketing'] ? 'Oui' : 'Non') . "\n";
        echo "   - Preferences: " . ($summary['preferences'] ? 'Oui' : 'Non') . "\n";
        echo "   - Social Media: " . ($summary['social_media'] ? 'Oui' : 'Non') . "\n\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur méthodes: " . $e->getMessage() . "\n\n";
}

// Test 4: Tester les statistiques
echo "4. Test des statistiques...\n";
try {
    $stats = \App\Models\Cookie::getGlobalStats();
    echo "✅ Statistiques calculées:\n";
    echo "   - Total consentements: {$stats['total_consents']}\n";
    echo "   - Consentements acceptés: {$stats['accepted_consents']}\n";
    echo "   - Consentements rejetés: {$stats['rejected_consents']}\n";
    echo "   - En attente: {$stats['pending_consents']}\n\n";
} catch (Exception $e) {
    echo "❌ Erreur statistiques: " . $e->getMessage() . "\n\n";
}

// Test 5: Nettoyer le cookie de test
echo "5. Nettoyage...\n";
try {
    $testCookies = \App\Models\Cookie::where('session_id', 'LIKE', 'test_session_%')->get();
    foreach ($testCookies as $testCookie) {
        $testCookie->delete();
    }
    echo "✅ Cookies de test supprimés\n\n";
} catch (Exception $e) {
    echo "❌ Erreur nettoyage: " . $e->getMessage() . "\n\n";
}

echo "=== Test terminé ===\n";
echo "🎉 Le système de cookies semble fonctionnel !\n";
echo "\nPour tester complètement:\n";
echo "1. Accédez à votre site en navigation privée\n";
echo "2. Acceptez/Refusez les cookies via le bandeau\n";
echo "3. Vérifiez les enregistrements en base\n";
echo "4. Consultez l'interface admin: /admin/cookies\n";
