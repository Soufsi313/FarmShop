<?php

require 'vendor/autoload.php';

use App\Models\Cookie;
use App\Models\CookieConsent;
use App\Models\User;
use Illuminate\Foundation\Application;

// Créer l'application Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    // Récupérer les cookies existants
    $cookies = Cookie::all();
    echo "Cookies disponibles:\n";
    foreach ($cookies as $cookie) {
        echo "- {$cookie->name} ({$cookie->category})\n";
    }
    
    // Créer un consentement pour un visiteur (sans user_id)
    $visitorConsent = CookieConsent::create([
        'user_id' => null,
        'session_id' => 'visitor_session_123',
        'ip_address' => '192.168.1.100',
        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        'consents' => [
            'essential' => true,
            'analytics' => true,
            'marketing' => false
        ],
        'consent_type' => 'custom',
        'consent_date' => now(),
        'expires_at' => now()->addDays(365)
    ]);
    
    echo "\n✅ Consentement visiteur créé avec succès (ID: {$visitorConsent->id})\n";
    
    // Vérifier si on a des utilisateurs
    $user = User::first();
    if ($user) {
        // Créer un consentement pour un utilisateur connecté
        $userConsent = CookieConsent::create([
            'user_id' => $user->id,
            'session_id' => 'user_session_456',
            'ip_address' => '192.168.1.101',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            'consents' => [
                'essential' => true,
                'analytics' => true,
                'marketing' => true
            ],
            'consent_type' => 'accept_all',
            'consent_date' => now(),
            'expires_at' => now()->addDays(365)
        ]);
        
        echo "✅ Consentement utilisateur créé avec succès (ID: {$userConsent->id})\n";
        echo "   Utilisateur: {$user->name} (ID: {$user->id})\n";
    } else {
        echo "ℹ️  Aucun utilisateur trouvé, création du consentement visiteur seulement\n";
    }
    
    // Statistiques des consentements
    $totalConsents = CookieConsent::count();
    $acceptAllConsents = CookieConsent::where('consent_type', 'accept_all')->count();
    $customConsents = CookieConsent::where('consent_type', 'custom')->count();
    echo "\n📊 Statistiques:\n";
    echo "- Total consentements: {$totalConsents}\n";
    echo "- Consentements 'accepter tout': {$acceptAllConsents}\n";
    echo "- Consentements personnalisés: {$customConsents}\n";
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
