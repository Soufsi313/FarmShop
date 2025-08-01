<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Test d'authentification API ===\n\n";

// Simuler une requête API comme un utilisateur connecté
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

echo "1. Vérification de l'utilisateur actuel:\n";
$user = Auth::user();
if ($user) {
    echo "   ✅ Utilisateur connecté: {$user->name} (ID: {$user->id})\n\n";
} else {
    echo "   ❌ Aucun utilisateur connecté\n\n";
}

echo "2. Test de la route API cart-location:\n";
try {
    // Simuler la requête
    $request = Request::create('/api/cart-location', 'GET');
    $request->headers->set('Accept', 'application/json');
    $request->headers->set('X-Requested-With', 'XMLHttpRequest');
    
    // Tester avec le middleware
    $response = app('App\Http\Controllers\CartLocationController')->index();
    echo "   ✅ Réponse API: " . $response->getContent() . "\n\n";
} catch (Exception $e) {
    echo "   ❌ Erreur: " . $e->getMessage() . "\n\n";
}

echo "3. Test de création d'un panier:\n";
try {
    $users = App\Models\User::take(1)->get();
    if ($users->count() > 0) {
        $user = $users->first();
        echo "   Utilisateur test: {$user->name}\n";
        
        $cartLocation = $user->getOrCreateActiveCartLocation();
        echo "   ✅ Panier créé/récupéré: ID {$cartLocation->id}\n";
        echo "   Nombre d'items: " . $cartLocation->items()->count() . "\n";
    } else {
        echo "   ❌ Aucun utilisateur trouvé\n";
    }
} catch (Exception $e) {
    echo "   ❌ Erreur: " . $e->getMessage() . "\n";
}

echo "\n=== Fin des tests ===\n";
