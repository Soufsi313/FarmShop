<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Test nouvelles routes web admin cookies ===\n";

// Test direct des contrôleurs
$admin = App\Models\User::where('email', 's.mef2703@gmail.com')->where('role', 'Admin')->first();
if (!$admin) {
    echo "❌ Admin non trouvé\n";
    exit;
}

Auth::login($admin);
echo "✅ Connecté en tant qu'admin\n";

// Test du contrôleur directement
echo "\n=== Test contrôleur stats ===\n";
try {
    $controller = new App\Http\Controllers\CookieController();
    $stats = $controller->getAdminStats();
    echo "✅ Stats: " . json_encode($stats->getData()) . "\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

echo "\n=== Test contrôleur liste ===\n";
try {
    $request = new Illuminate\Http\Request();
    $request->merge(['page' => 1, 'per_page' => 3]);
    
    $controller = new App\Http\Controllers\CookieController();
    $cookies = $controller->getAdminIndex($request);
    echo "✅ Liste: " . json_encode($cookies->getData()) . "\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

echo "\n=== Test simulation requête web ===\n";
// Simuler une requête web
try {
    $request = new Illuminate\Http\Request();
    $request->headers->set('X-Requested-With', 'XMLHttpRequest');
    $request->headers->set('Accept', 'application/json');
    
    // Simuler la session
    $request->setLaravelSession(session());
    
    $controller = new App\Http\Controllers\CookieController();
    $stats = $controller->getAdminStats();
    echo "✅ Simulation web OK\n";
} catch (Exception $e) {
    echo "❌ Erreur simulation: " . $e->getMessage() . "\n";
}

echo "\n=== Fin ===\n";
