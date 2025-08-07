<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Container\Container;

// Créer l'application Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

// Vérifier la structure de la table newsletter_subscriptions
try {
    echo "=== Structure de newsletter_subscriptions ===\n";
    
    $columns = \Schema::getColumnListing('newsletter_subscriptions');
    foreach ($columns as $column) {
        echo "- " . $column . "\n";
    }
    
    echo "\n=== Premier enregistrement ===\n";
    $first = \App\Models\NewsletterSubscription::first();
    if ($first) {
        print_r($first->toArray());
    } else {
        echo "Aucun enregistrement trouvé\n";
    }
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}
