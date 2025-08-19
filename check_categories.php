<?php

require_once 'vendor/autoload.php';

use App\Models\Category;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== VÉRIFICATION DES CATÉGORIES ===\n\n";

$categories = Category::all(['id', 'name', 'is_active']);

if ($categories->count() > 0) {
    echo "Catégories trouvées:\n";
    foreach ($categories as $cat) {
        echo "- ID: {$cat->id} | Nom: '{$cat->name}' | Actif: " . ($cat->is_active ? 'Oui' : 'Non') . "\n";
    }
} else {
    echo "❌ AUCUNE CATÉGORIE TROUVÉE!\n";
}

echo "\nTotal: " . $categories->count() . " catégories\n";
