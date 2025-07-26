<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Debug Bandeau Cookies ===\n\n";

// Simuler l'appel API qui détermine si le bandeau doit s'afficher
use Illuminate\Http\Request;
use App\Http\Controllers\CookieController;

$controller = new CookieController();

echo "1. Test de l'API /api/cookies/preferences (détermine si le bandeau s'affiche):\n";
try {
    $request = Request::create('/api/cookies/preferences', 'GET');
    $request->headers->set('X-Requested-With', 'XMLHttpRequest');
    $response = $controller->getPreferences($request);
    $data = json_decode($response->getContent(), true);
    
    echo "   Status HTTP: " . $response->getStatusCode() . "\n";
    echo "   Consentement requis: " . ($data['data']['consent_required'] ? 'OUI' : 'NON') . "\n";
    echo "   Cookie ID: " . $data['data']['cookie_id'] . "\n";
    echo "   Status du cookie: " . $data['data']['preferences']['status'] . "\n\n";
    
    if ($data['data']['consent_required']) {
        echo "   ✅ Le bandeau DEVRAIT s'afficher car consent_required = true\n";
    } else {
        echo "   ❌ Le bandeau ne s'affiche PAS car consent_required = false\n";
    }
} catch (Exception $e) {
    echo "   ❌ Erreur API: " . $e->getMessage() . "\n";
}

echo "\n2. Cookies actuels en base:\n";
$cookies = App\Models\Cookie::latest()->take(3)->get();
foreach ($cookies as $cookie) {
    echo "   - Cookie ID {$cookie->id}: {$cookie->status} (IP: {$cookie->ip_address}, Session: " . substr($cookie->session_id, 0, 8) . "...)\n";
}

echo "\n3. Test simple - Forcer l'affichage du bandeau:\n";
echo "   Le bandeau s'affiche si :\n";
echo "   - L'élément #cookie-banner existe dans le DOM\n";
echo "   - response.data.consent_required === true\n";
echo "   - Pas d'erreurs JavaScript\n";

echo "\n=== Fin du debug ===\n";
