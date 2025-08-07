<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Container\Container;

// Créer l'application Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

// Tester les statistiques corrigées
try {
    echo "=== Test des statistiques corrigées ===\n";
    
    // Tester les utilisateurs
    $usersCount = \App\Models\User::count();
    echo "Nombre d'utilisateurs: " . $usersCount . "\n";
    
    // Tester les produits
    $productsCount = \App\Models\Product::count();
    echo "Nombre de produits: " . $productsCount . "\n";
    
    // Tester les commandes
    $ordersCount = \App\Models\Order::count();
    echo "Nombre de commandes: " . $ordersCount . "\n";
    
    // Tester les messages (au lieu de contacts)
    $messagesCount = \App\Models\Message::count();
    echo "Nombre de messages: " . $messagesCount . "\n";
    
    // Tester les abonnements newsletter
    $subscribersCount = \App\Models\NewsletterSubscription::where('is_subscribed', true)->count();
    echo "Abonnés newsletter: " . $subscribersCount . "\n";
    
    // Tester les newsletters envoyées
    $newslettersCount = \App\Models\Newsletter::count();
    echo "Newsletters envoyées: " . $newslettersCount . "\n";
    
    // Tester le chiffre d'affaires
    $totalRevenue = \App\Models\Order::where('status', 'completed')->sum('total_amount');
    echo "Chiffre d'affaires: " . $totalRevenue . "€\n";
    
    echo "\n=== Toutes les statistiques fonctionnent ! ===\n";
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
    echo "Fichier: " . $e->getFile() . "\n";
    echo "Ligne: " . $e->getLine() . "\n";
}
