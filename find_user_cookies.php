<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\CookieConsent;

echo "🔍 Recherche de l'utilisateur saurouk313@gmail.com...\n";

// Chercher l'utilisateur par email
$user = User::where('email', 'saurouk313@gmail.com')->first();

if ($user) {
    echo "✅ Utilisateur trouvé: {$user->name} (ID: {$user->id}, Email: {$user->email})\n";
    
    // Chercher ses cookies
    $cookies = CookieConsent::where('user_id', $user->id)->get();
    echo "📊 Nombre de cookies de consentement: {$cookies->count()}\n\n";
    
    foreach ($cookies as $cookie) {
        echo "🍪 Cookie ID: {$cookie->id}\n";
        echo "   Status: {$cookie->status}\n";
        echo "   Accepted at: " . ($cookie->accepted_at ?? 'null') . "\n";
        echo "   Created at: {$cookie->created_at}\n";
        echo "   Necessary: " . ($cookie->necessary ? 'true' : 'false') . "\n";
        echo "   Analytics: " . ($cookie->analytics ? 'true' : 'false') . "\n";
        echo "   Marketing: " . ($cookie->marketing ? 'true' : 'false') . "\n";
        echo "   Preferences: " . ($cookie->preferences ? 'true' : 'false') . "\n";
        echo "   Social Media: " . ($cookie->social_media ? 'true' : 'false') . "\n\n";
    }
    
    if ($cookies->count() > 0) {
        echo "🗑️ Suppression de tous les cookies de consentement...\n";
        $deleted = CookieConsent::where('user_id', $user->id)->delete();
        echo "✅ Supprimé {$deleted} enregistrements de consentement pour {$user->name}\n";
        echo "🎯 Le bandeau devrait maintenant s'afficher après connexion !\n";
    }
} else {
    echo "❌ Utilisateur Meftah Soufiane non trouvé\n";
    echo "📋 Voici tous les utilisateurs disponibles :\n";
    
    $users = User::select('id', 'name', 'email')->get();
    foreach ($users as $u) {
        echo "   - {$u->name} (ID: {$u->id}, Email: {$u->email})\n";
    }
}
?>
