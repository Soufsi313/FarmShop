<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Charger l'environnement Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Mise à jour rapide des catégories ===\n";

try {
    // Marquer les catégories non-alimentaires comme retournables
    $updated = DB::table('categories')
        ->whereIn('name', ['Outils agricoles', 'Machines', 'Équipement', 'Semences', 'Engrais', 'Irrigation', 'Protections'])
        ->update(['is_returnable' => true]);

    echo "✅ $updated catégories non-alimentaires mises à jour comme retournables\n";

    // Vérifier l'état final
    $returnable = DB::table('categories')->where('is_returnable', true)->count();
    $total = DB::table('categories')->count();

    echo "📊 Résultat : $returnable/$total catégories sont maintenant retournables\n";

    if ($returnable > 0) {
        echo "\n🎉 Correction réussie ! Le bouton 'Retourner' devrait maintenant apparaître pour les commandes livrées avec des produits retournables.\n";
    }

} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
}
