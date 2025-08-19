<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;

echo "=== MODIFICATION DU TRACTEUR COMPACT ===\n";

// Trouver le produit "Tracteur Compact"
$tracteur = Product::where('name', 'Tracteur Compact')->first();

if (!$tracteur) {
    echo "❌ Tracteur Compact non trouvé\n";
    exit;
}

echo "📍 Produit trouvé: " . $tracteur->name . " (ID: " . $tracteur->id . ")\n";
echo "💰 Prix actuel: " . $tracteur->price . "€\n";

// Modifier le produit
$tracteur->update([
    'name' => 'Tondeuse Professionnelle',
    'name_en' => 'Professional Mower',
    'name_nl' => 'Professionele Maaier',
    'description' => 'Tondeuse professionnelle haute performance pour entretien des espaces verts',
    'description_en' => 'High-performance professional mower for green space maintenance',
    'description_nl' => 'High-performance professionele maaier voor onderhoud van groene ruimtes',
    'price' => 2500.00, // Prix plus réaliste pour une tondeuse
    'unit' => 'pièces',
    'unit_en' => 'pieces',
    'unit_nl' => 'stuks'
]);

echo "✅ Produit modifié avec succès!\n";
echo "📝 Nouveau nom: " . $tracteur->name . "\n";
echo "💰 Nouveau prix: " . $tracteur->price . "€\n";
echo "🌍 Traductions: FR/EN/NL mises à jour\n";

echo "\n🎯 Modification terminée avec succès!\n";

?>
