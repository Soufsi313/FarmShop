<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use App\Models\SpecialOffer;
use Carbon\Carbon;

// Bootstrapping Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Diagnostic de l'offre spéciale ===\n\n";

try {
    // Récupérer l'offre "Liquidation total golden fruits"
    $offer = SpecialOffer::where('name', 'like', '%Liquidation total golden fruits%')
                        ->orWhere('name', 'like', '%liquidation%')
                        ->first();

    if (!$offer) {
        echo "❌ Offre 'Liquidation total golden fruits' non trouvée.\n";
        echo "Recherche de toutes les offres...\n\n";
        
        $offers = SpecialOffer::all();
        foreach ($offers as $o) {
            echo "📦 Offre trouvée : '{$o->name}' (ID: {$o->id})\n";
            echo "   → Produit : {$o->product->name}\n";
            echo "   → Dates : du {$o->start_date} au {$o->end_date}\n";
            echo "   → is_active : " . ($o->is_active ? 'Oui' : 'Non') . "\n";
            echo "   → Statut calculé : {$o->status}\n\n";
        }
        
        if ($offers->count() > 0) {
            $offer = $offers->first();
            echo "✅ Utilisation de la première offre trouvée pour le diagnostic.\n\n";
        } else {
            echo "❌ Aucune offre trouvée en base de données.\n";
            exit(1);
        }
    }

    echo "🔍 Diagnostic de l'offre : '{$offer->name}'\n";
    echo "==========================================\n\n";

    echo "📅 Informations de dates :\n";
    echo "   → Date de début : {$offer->start_date}\n";
    echo "   → Date de fin : {$offer->end_date}\n";
    echo "   → Date actuelle : " . now() . "\n\n";

    echo "⚙️ Paramètres :\n";
    echo "   → is_active (BDD) : " . ($offer->is_active ? 'true' : 'false') . "\n";
    echo "   → Remise : {$offer->discount_percentage}%\n";
    echo "   → Quantité min : {$offer->min_quantity}\n\n";

    echo "🔍 Tests de logique :\n";
    $now = now();
    echo "   → Maintenant >= start_date : " . ($now->gte($offer->start_date) ? 'true' : 'false') . "\n";
    echo "   → Maintenant <= end_date : " . ($now->lte($offer->end_date) ? 'true' : 'false') . "\n";
    echo "   → Dans la période : " . ($now->between($offer->start_date, $offer->end_date) ? 'true' : 'false') . "\n\n";

    echo "📊 Statuts calculés :\n";
    echo "   → Statut (attribut) : {$offer->status}\n";
    echo "   → isActive() : " . ($offer->isActive() ? 'true' : 'false') . "\n";
    echo "   → isScheduled() : " . ($offer->isScheduled() ? 'true' : 'false') . "\n";
    echo "   → isExpired() : " . ($offer->isExpired() ? 'true' : 'false') . "\n";
    echo "   → isAvailable() : " . ($offer->isAvailable() ? 'true' : 'false') . "\n\n";

    // Si l'offre devrait être active mais ne l'est pas
    if ($now->between($offer->start_date, $offer->end_date) && $offer->is_active) {
        if ($offer->status !== 'active') {
            echo "⚠️ PROBLÈME DÉTECTÉ !\n";
            echo "L'offre devrait être active mais le statut est : {$offer->status}\n\n";
            
            // Vérifier si c'est un problème de timezone
            echo "🕒 Vérification des timezones :\n";
            echo "   → Timezone app : " . config('app.timezone') . "\n";
            echo "   → Timezone PHP : " . date_default_timezone_get() . "\n";
            echo "   → start_date timezone : " . $offer->start_date->timezone . "\n";
            echo "   → end_date timezone : " . $offer->end_date->timezone . "\n\n";
        }
    }

    // Forcer la mise à jour si nécessaire
    if (!$offer->is_active && $now->between($offer->start_date, $offer->end_date)) {
        echo "🔧 Activation de l'offre...\n";
        $offer->update(['is_active' => true]);
        echo "✅ Offre activée !\n\n";
    }

    // Test du produit associé
    echo "📦 Test du produit associé :\n";
    $product = $offer->product;
    echo "   → Produit : {$product->name}\n";
    echo "   → Prix : {$product->price}€\n";
    echo "   → hasActiveSpecialOffer() : " . ($product->hasActiveSpecialOffer() ? 'true' : 'false') . "\n";
    
    if ($product->hasActiveSpecialOffer()) {
        $activeOffer = $product->getActiveSpecialOffer();
        echo "   → Offre active récupérée : {$activeOffer->name}\n";
    }
    echo "\n";

    // Test de calcul
    echo "🧮 Test de calcul de remise :\n";
    $testQuantity = $offer->min_quantity;
    $discountResult = $offer->calculateDiscount($testQuantity, $product->price);
    
    echo "   → Quantité testée : {$testQuantity}\n";
    echo "   → Prix unitaire : {$product->price}€\n";
    echo "   → Qualifie pour l'offre : " . ($discountResult['qualifies'] ? 'Oui' : 'Non') . "\n";
    echo "   → Total original : {$discountResult['original_total']}€\n";
    echo "   → Montant de remise : {$discountResult['discount_amount']}€\n";
    echo "   → Total final : {$discountResult['final_total']}€\n";
    echo "   → Économies : {$discountResult['savings']}€\n\n";

    echo "✅ Diagnostic terminé !\n\n";

    if ($offer->isAvailable()) {
        echo "🎉 L'offre est maintenant active et fonctionnelle !\n";
        echo "Vous pouvez tester sur : http://127.0.0.1:8000/products/{$product->slug}\n\n";
    } else {
        echo "⚠️ L'offre n'est toujours pas disponible.\n";
        echo "Actions recommandées :\n";
        echo "1. Vérifiez les dates et heures\n";
        echo "2. Activez manuellement l'offre dans l'admin\n";
        echo "3. Vérifiez la configuration des timezones\n\n";
    }

} catch (Exception $e) {
    echo "❌ Erreur lors du diagnostic : " . $e->getMessage() . "\n";
    echo "Stack trace : " . $e->getTraceAsString() . "\n";
    exit(1);
}

echo "=== Fin du diagnostic ===\n";
