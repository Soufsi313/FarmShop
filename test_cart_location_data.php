<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use App\Models\CartItemLocation;
use App\Models\CartLocation;
use App\Models\User;
use Carbon\Carbon;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Diagnostic des données de panier de location ===\n\n";

// Trouver les articles de panier de location
$cartItems = CartItemLocation::with(['product', 'cartLocation.user'])->get();

if ($cartItems->isEmpty()) {
    echo "❌ Aucun article de panier de location trouvé.\n";
    exit;
}

echo "✅ Articles de panier de location trouvés: " . $cartItems->count() . "\n\n";

foreach ($cartItems as $item) {
    echo "--- Article ID: {$item->id} ---\n";
    echo "Produit: {$item->product->name}\n";
    echo "Utilisateur: {$item->cartLocation->user->name}\n";
    echo "Date de début: {$item->start_date} (" . Carbon::parse($item->start_date)->format('d/m/Y') . ")\n";
    echo "Date de fin: {$item->end_date} (" . Carbon::parse($item->end_date)->format('d/m/Y') . ")\n";
    echo "Durée: {$item->duration_days} jour(s)\n";
    echo "Prix quotidien: {$item->daily_price}€\n";
    echo "Prix total: {$item->total_price}€\n";
    echo "Caution: {$item->deposit_amount}€\n";
    echo "Statut: {$item->status}\n";
    echo "Créé le: {$item->created_at}\n";
    echo "Modifié le: {$item->updated_at}\n";
    
    // Vérifier si les dates sont problématiques
    $today = Carbon::today();
    $startDate = Carbon::parse($item->start_date);
    
    if ($startDate->isToday()) {
        echo "⚠️  PROBLÈME: La date de début est aujourd'hui!\n";
    } elseif ($startDate->isPast()) {
        echo "⚠️  PROBLÈME: La date de début est dans le passé!\n";
    } else {
        echo "✅ Date de début OK (dans le futur)\n";
    }
    
    echo "\n";
}

echo "=== Test de modification d'un article ===\n";

$firstItem = $cartItems->first();
if ($firstItem) {
    echo "Test de modification de l'article ID: {$firstItem->id}\n";
    
    // Sauvegarder les anciennes valeurs
    $oldStartDate = $firstItem->start_date;
    $oldEndDate = $firstItem->end_date;
    $oldDuration = $firstItem->duration_days;
    
    // Nouvelles dates (demain et après-demain)
    $newStartDate = Carbon::tomorrow();
    $newEndDate = Carbon::tomorrow()->addDays(2); // 3 jours au total
    $newDuration = 3;
    
    echo "Anciennes valeurs:\n";
    echo "  Start: {$oldStartDate}\n";
    echo "  End: {$oldEndDate}\n";
    echo "  Duration: {$oldDuration}\n";
    
    echo "Nouvelles valeurs:\n";
    echo "  Start: {$newStartDate->format('Y-m-d')}\n";
    echo "  End: {$newEndDate->format('Y-m-d')}\n";
    echo "  Duration: {$newDuration}\n";
    
    try {
        // Mettre à jour
        $result = $firstItem->updateDuration($newDuration, $newStartDate);
        
        if ($result) {
            echo "✅ Mise à jour réussie via updateDuration()\n";
            
            // Recharger depuis la base
            $firstItem->refresh();
            
            echo "Valeurs après mise à jour:\n";
            echo "  Start: {$firstItem->start_date}\n";
            echo "  End: {$firstItem->end_date}\n";
            echo "  Duration: {$firstItem->duration_days}\n";
            echo "  Prix total: {$firstItem->total_price}€\n";
            
        } else {
            echo "❌ Échec de la mise à jour via updateDuration()\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Erreur lors de la mise à jour: " . $e->getMessage() . "\n";
    }
    
} else {
    echo "❌ Aucun article à tester\n";
}

echo "\n=== Diagnostic terminé ===\n";
