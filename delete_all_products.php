<?php

require_once 'vendor/autoload.php';

use App\Models\Product;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== SUPPRESSION COMPLÈTE DE TOUS LES PRODUITS ===\n\n";

$totalProducts = Product::count();
echo "📊 Produits à supprimer: {$totalProducts}\n\n";

echo "🗑️ Suppression en cours...\n";

// Supprimer tous les produits (force delete pour ignorer les soft deletes)
$deleted = Product::withTrashed()->forceDelete();

echo "✅ Suppression terminée !\n\n";

// Vérification
$remainingProducts = Product::withTrashed()->count();
echo "📊 Produits restants: {$remainingProducts}\n";

if ($remainingProducts === 0) {
    echo "🎉 SUCCÈS : Tous les produits ont été supprimés !\n";
    echo "Base de données propre, prête pour les nouveaux produits.\n";
} else {
    echo "❌ ATTENTION : Il reste encore {$remainingProducts} produits.\n";
}

echo "\n=== NETTOYAGE TERMINÉ ===\n";
