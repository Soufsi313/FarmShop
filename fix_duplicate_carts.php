<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Cart;
use App\Models\User;

echo "🔧 Correction des paniers en double...\n";

// Trouver les utilisateurs avec plusieurs paniers actifs
$usersWithDuplicateCarts = Cart::where('status', 'active')
    ->select('user_id', \DB::raw('COUNT(*) as cart_count'))
    ->groupBy('user_id')
    ->having('cart_count', '>', 1)
    ->get();

if ($usersWithDuplicateCarts->isEmpty()) {
    echo "✅ Aucun panier en double trouvé.\n";
    exit(0);
}

echo "🔍 Trouvé " . $usersWithDuplicateCarts->count() . " utilisateur(s) avec des paniers en double.\n";

foreach ($usersWithDuplicateCarts as $userData) {
    $userId = $userData->user_id;
    echo "🧹 Nettoyage des paniers pour l'utilisateur $userId...\n";
    
    // Récupérer tous les paniers actifs de cet utilisateur
    $userCarts = Cart::where('user_id', $userId)
        ->where('status', 'active')
        ->orderBy('updated_at', 'desc')
        ->get();
    
    // Garder le plus récent, supprimer les autres
    $keepCart = $userCarts->first();
    $cartsToDelete = $userCarts->slice(1);
    
    echo "  📦 Garde le panier ID {$keepCart->id} (dernière activité: {$keepCart->updated_at})\n";
    
    foreach ($cartsToDelete as $cartToDelete) {
        echo "  🗑️ Suppression du panier ID {$cartToDelete->id}...\n";
        
        // Transférer les articles vers le panier principal si nécessaire
        if ($cartToDelete->items()->count() > 0) {
            echo "    📦 Transfert de {$cartToDelete->items()->count()} article(s)...\n";
            
            foreach ($cartToDelete->items as $item) {
                // Vérifier si un article similaire existe déjà
                $existingItem = $keepCart->items()
                    ->where('product_id', $item->product_id)
                    ->first();
                
                if ($existingItem) {
                    // Fusionner les quantités
                    $existingItem->quantity += $item->quantity;
                    $existingItem->recalculate();
                    $existingItem->save();
                    echo "    ➕ Fusionné avec l'article existant (nouvelle quantité: {$existingItem->quantity})\n";
                } else {
                    // Transférer l'article
                    $item->update(['cart_id' => $keepCart->id]);
                    echo "    ↗️ Article transféré\n";
                }
            }
        }
        
        // Supprimer le panier vide
        $cartToDelete->delete();
    }
    
    // Recalculer le total du panier principal
    $keepCart->calculateTotal();
    echo "  ✅ Nettoyage terminé pour l'utilisateur $userId\n\n";
}

echo "🎉 Correction terminée !\n";
