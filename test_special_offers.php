<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SpecialOffer;
use App\Models\Product;

echo "🎯 Test du système d'offres spéciales\n";
echo "===================================\n\n";

// Nettoyer les offres existantes pour le test
SpecialOffer::truncate();
echo "🧹 Nettoyage des offres existantes...\n\n";

// Récupérer un produit pour le test
$product = Product::where('is_active', true)->first();

if (!$product) {
    echo "❌ Aucun produit actif trouvé. Impossible de faire le test.\n";
    exit(1);
}

echo "📦 Produit sélectionné pour le test :\n";
echo "   - Nom : {$product->name}\n";
echo "   - Prix : {$product->price}€\n\n";

// Créer une offre spéciale de test
echo "🎯 Création d'une offre spéciale de test...\n";
$offer = SpecialOffer::create([
    'name' => 'Super Offre Quantité',
    'description' => 'Achetez 50 ou plus et économisez 75% !',
    'product_id' => $product->id,
    'min_quantity' => 50,
    'discount_percentage' => 75.00,
    'start_date' => now()->subDay(),
    'end_date' => now()->addWeek(),
    'is_active' => true
]);

echo "✅ Offre créée :\n";
echo "   - Nom : {$offer->name}\n";
echo "   - Quantité minimale : {$offer->min_quantity}\n";
echo "   - Remise : {$offer->discount_percentage}%\n";
echo "   - Période : {$offer->start_date->format('d/m/Y')} - {$offer->end_date->format('d/m/Y')}\n\n";

// Tests de calculs
echo "🧮 Tests de calculs de remise :\n";
echo "=" . str_repeat('=', 40) . "\n\n";

$testQuantities = [10, 30, 50, 100, 200];

foreach ($testQuantities as $quantity) {
    echo "📊 Test avec {$quantity} articles :\n";
    
    $calculation = $offer->calculateDiscount($quantity, $product->price);
    
    echo "   - Prix unitaire : {$product->price}€\n";
    echo "   - Total initial : " . number_format($calculation['original_total'], 2) . "€\n";
    
    if ($calculation['qualifies']) {
        echo "   ✅ Qualifie pour l'offre !\n";
        echo "   - Remise : " . number_format($calculation['discount_amount'], 2) . "€ ({$offer->discount_percentage}%)\n";
        echo "   - Total final : " . number_format($calculation['final_total'], 2) . "€\n";
        echo "   - Économies : " . number_format($calculation['savings'], 2) . "€\n";
    } else {
        echo "   ❌ Ne qualifie pas pour l'offre (minimum {$offer->min_quantity})\n";
        echo "   - Total à payer : " . number_format($calculation['final_total'], 2) . "€\n";
    }
    echo "\n";
}

// Test des méthodes du modèle
echo "🔍 Test des méthodes du modèle :\n";
echo "=" . str_repeat('=', 30) . "\n";
echo "   - isAvailable() : " . ($offer->isAvailable() ? '✅ Oui' : '❌ Non') . "\n";
echo "   - Statut : " . $offer->status . "\n";
echo "   - Jours restants : " . ($offer->days_remaining ?? 'N/A') . "\n";
echo "   - Produit a offre active : " . ($product->hasActiveSpecialOffer() ? '✅ Oui' : '❌ Non') . "\n\n";

// Test avec une quantité exacte au seuil
echo "🎯 Test au seuil exact ({$offer->min_quantity} articles) :\n";
$exactCalculation = $offer->calculateDiscount($offer->min_quantity, $product->price);
echo "   - Qualifie : " . ($exactCalculation['qualifies'] ? '✅ Oui' : '❌ Non') . "\n";
echo "   - Économies : " . number_format($exactCalculation['savings'], 2) . "€\n\n";

// Test d'une offre expirée
echo "🕰️ Test d'une offre expirée...\n";
$expiredOffer = SpecialOffer::create([
    'name' => 'Offre Expirée',
    'description' => 'Cette offre a expiré',
    'product_id' => $product->id,
    'min_quantity' => 10,
    'discount_percentage' => 50.00,
    'start_date' => now()->subWeek(),
    'end_date' => now()->subDay(),
    'is_active' => true
]);

echo "   - Statut : " . $expiredOffer->status . "\n";
echo "   - Disponible : " . ($expiredOffer->isAvailable() ? '✅ Oui' : '❌ Non') . "\n\n";

// Test d'une offre future
echo "📅 Test d'une offre programmée...\n";
$futureOffer = SpecialOffer::create([
    'name' => 'Offre Future',
    'description' => 'Cette offre commencera demain',
    'product_id' => $product->id,
    'min_quantity' => 20,
    'discount_percentage' => 30.00,
    'start_date' => now()->addDay(),
    'end_date' => now()->addWeek(),
    'is_active' => true
]);

echo "   - Statut : " . $futureOffer->status . "\n";
echo "   - Disponible : " . ($futureOffer->isAvailable() ? '✅ Oui' : '❌ Non') . "\n\n";

// Résumé des scopes
echo "📋 Test des scopes :\n";
echo "   - Offres actives : " . SpecialOffer::active()->count() . "\n";
echo "   - Offres valides : " . SpecialOffer::valid()->count() . "\n";
echo "   - Offres disponibles : " . SpecialOffer::available()->count() . "\n\n";

echo "✅ Test du système d'offres spéciales terminé !\n";
echo "\n🎯 Prochaines étapes :\n";
echo "   1. Créer les vues admin pour gérer les offres\n";
echo "   2. Intégrer l'affichage dans les vues produits\n";
echo "   3. Modifier le système de panier pour appliquer les remises\n";
echo "   4. Ajouter des badges visuels sur les produits avec offres\n\n";
