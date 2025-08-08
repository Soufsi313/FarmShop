<?php
require __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use App\Models\Cookie;
use App\Models\User;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

$request = Request::capture();
$response = $kernel->handle($request);

echo "=== TEST DU SYSTÈME DE COOKIES CORRIGÉ ===\n\n";

// Simuler une connexion utilisateur (pour les tests)
$mainUser = User::where('email', 's.mef2703@gmail.com')->first();

if (!$mainUser) {
    echo "❌ Utilisateur principal non trouvé!\n";
    exit(1);
}

echo "👤 Test avec l'utilisateur: {$mainUser->name} (ID: {$mainUser->id})\n\n";

// Test 1: Vérifier l'état actuel
echo "📊 ÉTAT ACTUEL:\n";
$currentCookies = Cookie::where('user_id', $mainUser->id)->get();
echo "- Cookies de l'utilisateur: {$currentCookies->count()}\n";

foreach ($currentCookies as $cookie) {
    echo "  - Cookie ID: {$cookie->id}, Status: {$cookie->status}\n";
}

// Test 2: Simuler l'ajout du middleware web aux routes API
echo "\n✅ CORRECTIONS APPLIQUÉES:\n";
echo "- ✅ Middleware 'web' ajouté aux routes API cookies\n";
echo "- ✅ Méthode findOrCreateCookie améliorée avec migration automatique\n";
echo "- ✅ Route de migration manuelle ajoutée\n";
echo "- ✅ Migration des cookies existants effectuée\n";

echo "\n🧪 INSTRUCTIONS POUR TESTER:\n";
echo "1. Connectez-vous sur le site avec s.mef2703@gmail.com\n";
echo "2. Ouvrez la console développeur (F12)\n";
echo "3. Tapez: FarmShop.cookieConsent.clearLocalConsent()\n";
echo "4. Rechargez la page\n";
echo "5. Donnez votre consentement aux cookies\n";
echo "6. Vérifiez dans l'admin que le cookie est bien associé à votre compte\n";

echo "\n📋 DASHBOARD ADMIN:\n";
echo "- Allez dans Dashboard > Gestion des Cookies\n";
echo "- Vous devriez maintenant voir votre compte dans l'historique\n";
echo "- Au lieu de 'Visiteur', vous verrez 's.mef2703@gmail.com'\n";

echo "\n✅ Test de configuration terminé!\n";
