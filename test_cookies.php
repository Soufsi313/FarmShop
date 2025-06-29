<?php

require 'vendor/autoload.php';

use App\Models\Cookie;
use Illuminate\Foundation\Application;

// Créer l'application Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    // Créer quelques cookies de test
    $cookie1 = Cookie::create([
        'name' => 'session',
        'category' => 'essential',
        'description' => 'Cookie de session pour maintenir la connexion utilisateur',
        'type' => 'session', 
        'purpose' => 'Maintenir la session utilisateur connecté',
        'is_essential' => true,
        'is_active' => true
    ]);

    $cookie2 = Cookie::create([
        'name' => 'analytics',
        'category' => 'analytics',
        'description' => 'Cookie pour analyser le comportement des visiteurs',
        'type' => 'third_party',
        'purpose' => 'Analyse statistique du site web',
        'duration_days' => 365,
        'is_essential' => false,
        'is_active' => true,
        'provider' => 'Google Analytics'
    ]);

    $cookie3 = Cookie::create([
        'name' => 'marketing',
        'category' => 'marketing',
        'description' => 'Cookie pour personnaliser les publicités',
        'type' => 'third_party',
        'purpose' => 'Publicité ciblée et remarketing',
        'duration_days' => 30,
        'is_essential' => false,
        'is_active' => true,
        'provider' => 'Facebook Pixel'
    ]);

    echo "Cookies créés avec succès:\n";
    echo "- Cookie 1 (essentiel): " . $cookie1->name . "\n";
    echo "- Cookie 2 (analytique): " . $cookie2->name . "\n";
    echo "- Cookie 3 (marketing): " . $cookie3->name . "\n";
    
    // Vérifier le total
    $totalCookies = Cookie::count();
    echo "\nTotal cookies dans la base: " . $totalCookies . "\n";
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}
