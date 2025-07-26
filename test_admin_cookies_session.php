<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Test API Admin Cookies avec session ===\n";

// Créer une session et se connecter comme admin
$admin = App\Models\User::where('email', 's.mef2703@gmail.com')->where('role', 'Admin')->first();
if (!$admin) {
    echo "❌ Admin non trouvé\n";
    exit;
}

echo "✅ Admin trouvé: {$admin->email}\n";

// Simuler une authentification Laravel
Auth::login($admin);
echo "✅ Connecté en tant qu'admin\n";

// Test 1: Récupérer les statistiques
echo "\n=== Test 1: Statistiques cookies ===\n";
try {
    $controller = new App\Http\Controllers\CookieController();
    $stats = $controller->getAdminStats();
    echo "✅ Statistiques récupérées\n";
    echo "Response: " . json_encode($stats->getData(), JSON_PRETTY_PRINT) . "\n";
} catch (Exception $e) {
    echo "❌ Erreur stats: " . $e->getMessage() . "\n";
}

// Test 2: Récupérer la liste des cookies
echo "\n=== Test 2: Liste des cookies ===\n";
try {
    $request = new Illuminate\Http\Request();
    $request->merge(['page' => 1, 'per_page' => 5]);
    
    $controller = new App\Http\Controllers\CookieController();
    $cookies = $controller->getAdminIndex($request);
    echo "✅ Liste récupérée\n";
    echo "Response: " . json_encode($cookies->getData(), JSON_PRETTY_PRINT) . "\n";
} catch (Exception $e) {
    echo "❌ Erreur liste: " . $e->getMessage() . "\n";
}

// Test 3: Vérifier les données existantes
echo "\n=== Test 3: Données cookies en BDD ===\n";
$totalCookies = App\Models\Cookie::count();
echo "Total cookies en BDD: $totalCookies\n";

if ($totalCookies > 0) {
    $sample = App\Models\Cookie::with('user')->limit(3)->get();
    foreach ($sample as $cookie) {
        echo "- Cookie ID: {$cookie->id}, User: " . ($cookie->user ? $cookie->user->email : 'Visiteur') . ", Status: {$cookie->status}\n";
    }
}

echo "\n=== Fin des tests ===\n";
