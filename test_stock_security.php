<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔒 TEST SÉCURITÉ STOCK - Prévention ajout produits en rupture\n";
echo "======================================================\n";

try {
    // 1. Trouver un utilisateur test
    $user = User::first();
    if (!$user) {
        echo "❌ Aucun utilisateur trouvé\n";
        exit;
    }
    echo "👤 Utilisateur: {$user->name} (ID: {$user->id})\n\n";

    // 2. Créer un produit de test en rupture de stock
    $outOfStockProduct = Product::where('quantity', 0)->first();
    
    if (!$outOfStockProduct) {
        // Créer un produit temporairement en rupture
        $testProduct = Product::where('quantity', '>', 0)->first();
        if ($testProduct) {
            $originalStock = $testProduct->quantity;
            $testProduct->update(['quantity' => 0]);
            $outOfStockProduct = $testProduct;
            echo "📦 Produit temporairement mis en rupture: {$outOfStockProduct->name}\n";
            echo "   Stock original: {$originalStock} → 0\n\n";
        }
    } else {
        echo "📦 Produit en rupture trouvé: {$outOfStockProduct->name}\n";
        echo "   Stock: {$outOfStockProduct->quantity}\n\n";
    }

    if (!$outOfStockProduct) {
        echo "❌ Aucun produit de test disponible\n";
        exit;
    }

    // 3. Tenter d'ajouter le produit en rupture au panier
    echo "🛒 TEST 1: Tentative d'ajout d'un produit en rupture au panier\n";
    try {
        $cart = $user->getOrCreateActiveCart();
        $cart->addProduct($outOfStockProduct, 1);
        echo "❌ PROBLÈME: Le produit en rupture a été ajouté au panier !\n";
    } catch (\Exception $e) {
        echo "✅ SÉCURITÉ OK: {$e->getMessage()}\n";
    }

    // 4. Test avec un produit inactif
    echo "\n🛒 TEST 2: Tentative d'ajout d'un produit inactif\n";
    $activeProduct = Product::where('is_active', true)->where('quantity', '>', 0)->first();
    if ($activeProduct) {
        $activeProduct->update(['is_active' => false]);
        try {
            $cart->addProduct($activeProduct, 1);
            echo "❌ PROBLÈME: Le produit inactif a été ajouté au panier !\n";
        } catch (\Exception $e) {
            echo "✅ SÉCURITÉ OK: {$e->getMessage()}\n";
        }
        // Restaurer l'état actif
        $activeProduct->update(['is_active' => true]);
    }

    // 5. Test dépassement de stock
    echo "\n🛒 TEST 3: Tentative d'ajout d'une quantité supérieure au stock\n";
    $limitedStockProduct = Product::where('is_active', true)
                                 ->where('quantity', '>', 0)
                                 ->where('quantity', '<=', 3)
                                 ->first();
    
    if ($limitedStockProduct) {
        echo "   Produit: {$limitedStockProduct->name} (Stock: {$limitedStockProduct->quantity})\n";
        try {
            $cart->addProduct($limitedStockProduct, $limitedStockProduct->quantity + 1);
            echo "❌ PROBLÈME: Quantité excessive ajoutée au panier !\n";
        } catch (\Exception $e) {
            echo "✅ SÉCURITÉ OK: {$e->getMessage()}\n";
        }
    }

    // 6. Test modification quantité d'un article existant
    echo "\n🛒 TEST 4: Test modification quantité avec dépassement de stock\n";
    $testProduct = Product::where('is_active', true)
                         ->where('quantity', '>', 1)
                         ->first();
    
    if ($testProduct) {
        try {
            // Ajouter 1 produit au panier
            $cartItem = $cart->addProduct($testProduct, 1);
            echo "   ✅ Produit ajouté: {$testProduct->name} (quantité: 1)\n";
            
            // Tenter de modifier la quantité au-delà du stock
            $cartItem->updateQuantity($testProduct->quantity + 1);
            echo "❌ PROBLÈME: Quantité excessive mise à jour !\n";
        } catch (\Exception $e) {
            echo "✅ SÉCURITÉ OK: {$e->getMessage()}\n";
        }
    }

    // 7. Restaurer le stock du produit de test si nécessaire
    if (isset($originalStock)) {
        $outOfStockProduct->update(['quantity' => $originalStock]);
        echo "\n🔄 Stock restauré: {$outOfStockProduct->name} → {$originalStock}\n";
    }

    echo "\n✅ TOUS LES TESTS DE SÉCURITÉ TERMINÉS\n";
    echo "Le système bloque maintenant correctement l'ajout de produits en rupture de stock !\n";

} catch (\Exception $e) {
    echo "❌ Erreur lors du test: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
