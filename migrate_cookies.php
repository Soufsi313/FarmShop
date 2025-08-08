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

echo "=== MIGRATION DES COOKIES VISITEURS ===\n\n";

// D'abord, associer votre compte principal aux cookies récents
$mainUser = User::where('email', 's.mef2703@gmail.com')->first();

if ($mainUser) {
    echo "👤 Utilisateur principal trouvé: {$mainUser->name} (ID: {$mainUser->id})\n\n";
    
    // Trouver les cookies visiteurs récents (dernières 48h)
    $recentGuestCookies = Cookie::whereNull('user_id')
                               ->where('created_at', '>=', now()->subHours(48))
                               ->where('ip_address', '127.0.0.1') // IP locale
                               ->orderBy('created_at', 'desc')
                               ->get();
    
    echo "🍪 Cookies visiteurs récents trouvés: {$recentGuestCookies->count()}\n";
    
    if ($recentGuestCookies->count() > 0) {
        echo "\n📋 DÉTAILS DES COOKIES À MIGRER:\n";
        foreach ($recentGuestCookies as $cookie) {
            echo "- Cookie ID: {$cookie->id}\n";
            echo "  Session: {$cookie->session_id}\n";
            echo "  Status: {$cookie->status}\n";
            echo "  Créé: {$cookie->created_at}\n";
            echo "  ----------------------------------------\n";
        }
        
        echo "\n🔄 MIGRATION EN COURS...\n";
        
        // Migrer seulement le cookie le plus récent pour éviter les doublons
        $latestCookie = $recentGuestCookies->first();
        
        if ($latestCookie) {
            $latestCookie->update([
                'user_id' => $mainUser->id,
                'session_id' => null // Nettoyer la session car l'utilisateur est identifié
            ]);
            
            echo "✅ Cookie migré avec succès!\n";
            echo "   - Cookie ID: {$latestCookie->id}\n";
            echo "   - Maintenant associé à: {$mainUser->email}\n";
        }
    } else {
        echo "ℹ️ Aucun cookie visiteur récent à migrer\n";
    }
} else {
    echo "❌ Utilisateur principal non trouvé!\n";
}

echo "\n📊 ÉTAT FINAL:\n";
$userCookieCount = Cookie::where('user_id', $mainUser ? $mainUser->id : null)->count();
$guestCookieCount = Cookie::whereNull('user_id')->count();

$userEmail = $mainUser ? $mainUser->email : 'utilisateur principal';
echo "- Cookies de {$userEmail}: {$userCookieCount}\n";
echo "- Cookies visiteurs restants: {$guestCookieCount}\n";

echo "\n✅ Migration terminée!\n";
