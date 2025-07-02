<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SpecialOffer;
use App\Models\Product;

echo "🎯 Test du système admin des offres spéciales\n";
echo "=============================================\n\n";

// Créer quelques offres de test si nécessaire
if (SpecialOffer::count() === 0) {
    echo "📝 Création d'offres de test...\n";
    
    $products = Product::active()->take(3)->get();
    
    if ($products->count() === 0) {
        echo "❌ Aucun produit actif trouvé.\n";
        exit(1);
    }
    
    foreach ($products as $index => $product) {
        SpecialOffer::create([
            'name' => "Offre Test " . ($index + 1),
            'description' => "Offre spéciale pour {$product->name}",
            'product_id' => $product->id,
            'min_quantity' => ($index + 1) * 20,
            'discount_percentage' => ($index + 1) * 15,
            'start_date' => now()->subDay(),
            'end_date' => now()->addWeeks($index + 1),
            'is_active' => true
        ]);
    }
    
    echo "✅ {$products->count()} offres de test créées.\n\n";
}

// Statistiques
$offers = SpecialOffer::with('product')->get();
echo "📊 Statistiques des offres :\n";
echo "   - Total : {$offers->count()}\n";
echo "   - Actives : " . $offers->where('status', 'active')->count() . "\n";
echo "   - Programmées : " . $offers->where('status', 'scheduled')->count() . "\n";
echo "   - Expirées : " . $offers->where('status', 'expired')->count() . "\n";
echo "   - Inactives : " . $offers->where('status', 'inactive')->count() . "\n\n";

// URLs importantes
echo "🌐 URLs importantes :\n";
echo "   - Liste admin : " . route('admin.special-offers.index') . "\n";
echo "   - Créer offre : " . route('admin.special-offers.create') . "\n";
if ($offers->count() > 0) {
    $firstOffer = $offers->first();
    echo "   - Voir offre : " . route('admin.special-offers.show', $firstOffer) . "\n";
    echo "   - Éditer offre : " . route('admin.special-offers.edit', $firstOffer) . "\n";
}
echo "\n";

// Test de quelques calculs intéressants
echo "💰 Exemples de calculs d'offres :\n";
echo "=" . str_repeat('=', 40) . "\n";

foreach ($offers->take(3) as $offer) {
    echo "\n🎯 {$offer->name} :\n";
    echo "   - Produit : {$offer->product->name} ({$offer->product->price}€)\n";
    echo "   - Minimum : {$offer->min_quantity} articles\n";
    echo "   - Remise : {$offer->discount_percentage}%\n";
    echo "   - Statut : {$offer->status}\n";
    
    // Calcul exemple
    $testQuantity = $offer->min_quantity;
    $calculation = $offer->calculateDiscount($testQuantity, $offer->product->price);
    
    if ($calculation['qualifies']) {
        echo "   💡 Exemple ({$testQuantity} articles) :\n";
        echo "      - Prix initial : " . number_format($calculation['original_total'], 2) . "€\n";
        echo "      - Après remise : " . number_format($calculation['final_total'], 2) . "€\n";
        echo "      - Économies : " . number_format($calculation['savings'], 2) . "€\n";
    }
}

echo "\n✅ Test terminé. Le système admin est prêt !\n";
echo "\n🚀 Prochaines étapes :\n";
echo "   1. Intégrer l'affichage des offres sur les pages produits\n";
echo "   2. Modifier le système de panier pour appliquer les remises\n";
echo "   3. Ajouter des badges visuels sur les cartes produits\n";
echo "   4. Créer les notifications d'offres pour les clients\n\n";
