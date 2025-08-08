<?php

// Script de test pour le système de cookies - Debug connexion
require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;

echo "🍪 === DEBUG SYSTÈME COOKIES APRÈS CONNEXION ===\n\n";

// Configuration Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->boot();

try {
    echo "1. État actuel des cookies dans la DB :\n";
    $cookies = DB::table('cookies')->get();
    
    foreach ($cookies as $cookie) {
        echo "   - Cookie #{$cookie->id}\n";
        echo "     User ID: " . ($cookie->user_id ?? 'NULL (visiteur)') . "\n";
        echo "     Session ID: " . ($cookie->session_id ?? 'NULL') . "\n";
        echo "     IP: {$cookie->ip_address}\n";
        echo "     Status: {$cookie->status}\n";
        echo "     Migrated: " . ($cookie->migrated_at ?? 'Jamais') . "\n";
        echo "     Created: {$cookie->created_at}\n\n";
    }
    
    echo "2. Test de la logique findOrCreateCookie...\n";
    
    // Simuler une requête pour un utilisateur connecté
    $sessionId = 'test_session_123';
    $ipAddress = '127.0.0.1';
    $userId = 1; // Supposons que l'utilisateur 1 se connecte
    
    echo "   Recherche pour User #{$userId}, Session: {$sessionId}, IP: {$ipAddress}\n";
    
    // Chercher cookie utilisateur existant
    $userCookie = DB::table('cookies')->where('user_id', $userId)->first();
    if ($userCookie) {
        echo "   ✅ Cookie utilisateur trouvé: #{$userCookie->id} (status: {$userCookie->status})\n";
    } else {
        echo "   ❌ Aucun cookie utilisateur trouvé\n";
        
        // Chercher cookie visiteur
        $guestCookie = DB::table('cookies')
            ->where(function($query) use ($sessionId, $ipAddress) {
                $query->where('session_id', $sessionId)
                      ->orWhere('ip_address', $ipAddress);
            })
            ->whereNull('user_id')
            ->where('created_at', '>=', now()->subHours(24))
            ->first();
            
        if ($guestCookie) {
            echo "   ✅ Cookie visiteur trouvé pour migration: #{$guestCookie->id} (status: {$guestCookie->status})\n";
        } else {
            echo "   ❌ Aucun cookie visiteur récent trouvé\n";
        }
    }
    
    echo "\n3. Recommandations :\n";
    if (!$userCookie && !isset($guestCookie)) {
        echo "   - Un nouveau cookie sera créé -> bandeau affiché\n";
    } elseif ($userCookie && $userCookie->status === 'accepted') {
        echo "   - Cookie utilisateur accepté -> pas de bandeau\n";
    } elseif (isset($guestCookie) && $guestCookie->status === 'accepted') {
        echo "   - Cookie visiteur accepté sera migré -> pas de bandeau\n";
    } else {
        echo "   - Cookie existant mais pas accepté -> bandeau affiché\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
}

echo "\n🍪 === FIN DEBUG ===\n";
