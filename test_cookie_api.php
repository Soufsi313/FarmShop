<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Test des API Cookies ===\n\n";

// Simuler une requête
use Illuminate\Http\Request;
use App\Http\Controllers\CookieController;

$controller = new CookieController();

echo "1. Test getPreferences():\n";
try {
    $request = Request::create('/api/cookies/preferences', 'GET');
    $request->headers->set('X-Requested-With', 'XMLHttpRequest');
    $response = $controller->getPreferences($request);
    $data = json_decode($response->getContent(), true);
    echo "   ✅ Réponse: " . json_encode($data, JSON_PRETTY_PRINT) . "\n\n";
} catch (Exception $e) {
    echo "   ❌ Erreur: " . $e->getMessage() . "\n\n";
}

echo "2. Test acceptAll():\n";
try {
    $request = Request::create('/api/cookies/accept-all', 'POST');
    $request->headers->set('X-Requested-With', 'XMLHttpRequest');
    $request->headers->set('Content-Type', 'application/json');
    $response = $controller->acceptAll($request);
    $data = json_decode($response->getContent(), true);
    echo "   ✅ Réponse: " . json_encode($data, JSON_PRETTY_PRINT) . "\n\n";
} catch (Exception $e) {
    echo "   ❌ Erreur: " . $e->getMessage() . "\n\n";
}

echo "3. Vérification en base après acceptAll():\n";
$cookies = App\Models\Cookie::latest()->take(1)->get();
if ($cookies->count() > 0) {
    $cookie = $cookies->first();
    echo "   Cookie ID: {$cookie->id}\n";
    echo "   Status: {$cookie->status}\n";
    echo "   Analytics: " . ($cookie->analytics ? 'Oui' : 'Non') . "\n";
    echo "   Marketing: " . ($cookie->marketing ? 'Oui' : 'Non') . "\n";
} else {
    echo "   ❌ Aucun cookie trouvé\n";
}

echo "\n=== Fin des tests ===\n";
