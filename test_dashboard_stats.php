<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Container\Container;

// Créer l'application Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

// Tester les statistiques
try {
    echo "=== Test des statistiques du dashboard ===\n";
    
    // Tester les utilisateurs
    $usersCount = \App\Models\User::count();
    echo "Nombre d'utilisateurs: " . $usersCount . "\n";
    
    // Tester les produits
    $productsCount = \App\Models\Product::count();
    echo "Nombre de produits: " . $productsCount . "\n";
    
    // Tester les commandes
    $ordersCount = \App\Models\Order::count();
    echo "Nombre de commandes: " . $ordersCount . "\n";
    
    // Tester les messages de contact
    $contactsCount = \App\Models\Contact::count();
    echo "Nombre de contacts: " . $contactsCount . "\n";
    
    // Tester le chiffre d'affaires
    $totalRevenue = \App\Models\Order::where('status', 'completed')->sum('total_amount');
    echo "Chiffre d'affaires: " . $totalRevenue . "€\n";
    
    echo "\n=== Toutes les statistiques fonctionnent ! ===\n";
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
    echo "Fichier: " . $e->getFile() . "\n";
    echo "Ligne: " . $e->getLine() . "\n";
}
