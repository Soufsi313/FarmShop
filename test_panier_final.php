<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Test du système de panier ===\n";

try {
    // Trouver un utilisateur
    $user = App\Models\User::first();
    
    if (!$user) {
        echo "Aucun utilisateur trouvé dans la base de données\n";
        exit;
    }
    
    echo "Test avec l'utilisateur : {$user->email}\n";
    
    // Tester la création du panier
    $cart = $user->getOrCreateActiveCart();
    echo "Panier créé/récupéré avec succès - ID: {$cart->id}\n";
    echo "Statut du panier: {$cart->status}\n";
    echo "Nombre d'articles: {$cart->total_items}\n";
    echo "Total: {$cart->total} €\n";
    
    // Tester les méthodes du panier
    $summary = $cart->getCostSummary();
    echo "Résumé du panier:\n";
    echo "  - Sous-total: {$summary['subtotal']} €\n";
    echo "  - TVA: {$summary['total_tax']} €\n";
    echo "  - Total: {$summary['total']} €\n";
    
    // Vérifier la disponibilité
    $unavailable = $cart->checkAvailability();
    echo "Articles non disponibles: " . count($unavailable) . "\n";
    
    echo "\n✅ Tous les tests sont passés avec succès !\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "=== Fin du test ===\n";
