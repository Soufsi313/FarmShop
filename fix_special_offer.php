<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use App\Models\SpecialOffer;
use Carbon\Carbon;

// Bootstrapping Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Correction de l'offre spéciale ===\n\n";

try {
    // Récupérer l'offre "Liquidation total golden fruits"
    $offer = SpecialOffer::where('name', 'like', '%Liquidation total golden fruits%')
                        ->orWhere('name', 'like', '%liquidation%')
                        ->first();

    if (!$offer) {
        echo "❌ Offre non trouvée.\n";
        exit(1);
    }

    echo "🔍 Offre trouvée : '{$offer->name}'\n";
    echo "📅 Date de début actuelle : {$offer->start_date}\n";
    echo "📅 Date actuelle : " . now() . "\n\n";

    // Modifier la date de début pour qu'elle soit maintenant - 1 heure
    $newStartDate = now()->subHour();
    
    echo "🔧 Modification de la date de début...\n";
    echo "📅 Nouvelle date de début : {$newStartDate}\n\n";

    $offer->update([
        'start_date' => $newStartDate
    ]);

    echo "✅ Date de début mise à jour !\n\n";

    // Vérifier le nouveau statut
    $offer->refresh();
    echo "📊 Nouveau statut : {$offer->status}\n";
    echo "✅ isActive() : " . ($offer->isActive() ? 'true' : 'false') . "\n";
    echo "✅ isAvailable() : " . ($offer->isAvailable() ? 'true' : 'false') . "\n\n";

    // Test du produit
    $product = $offer->product;
    echo "📦 Test du produit :\n";
    echo "   → hasActiveSpecialOffer() : " . ($product->hasActiveSpecialOffer() ? 'true' : 'false') . "\n\n";

    if ($offer->isAvailable()) {
        echo "🎉 L'offre est maintenant ACTIVE !\n";
        echo "💰 Remise de {$offer->discount_percentage}% dès {$offer->min_quantity} articles\n";
        echo "🛒 Testez sur : http://127.0.0.1:8000/products/{$product->slug}\n\n";
        
        // Test de calcul
        echo "🧮 Test de calcul pour {$offer->min_quantity} articles :\n";
        $discountResult = $offer->calculateDiscount($offer->min_quantity, $product->price);
        echo "   → Prix original : {$discountResult['original_total']}€\n";
        echo "   → Remise : {$discountResult['discount_amount']}€\n";
        echo "   → Prix final : {$discountResult['final_total']}€\n";
        echo "   → Économies : {$discountResult['savings']}€\n\n";
    } else {
        echo "❌ L'offre n'est toujours pas active.\n";
    }

} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
    exit(1);
}

echo "=== Correction terminée ===\n";
