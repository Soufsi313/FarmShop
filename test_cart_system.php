<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    echo "=== Test du système de panier ===\n\n";
    
    // Trouver un utilisateur pour tester
    $user = User::first();
    if (!$user) {
        echo "❌ Aucun utilisateur trouvé pour le test\n";
        exit(1);
    }
    
    echo "✅ Utilisateur trouvé: {$user->email}\n";
    
    // Trouver un produit pour tester
    $product = Product::where('is_active', true)->first();
    if (!$product) {
        echo "❌ Aucun produit actif trouvé pour le test\n";
        exit(1);
    }
    
    echo "✅ Produit trouvé: {$product->name} - {$product->price}€\n";
    
    // Obtenir ou créer un panier pour l'utilisateur
    $cart = Cart::getOrCreateForUser($user);
    echo "✅ Panier obtenu/créé (ID: {$cart->id})\n";
    
    // Vider le panier pour avoir un test propre
    $cart->clear();
    echo "✅ Panier vidé\n";
    
    // Ajouter le produit au panier
    $cartItem = $cart->addProduct($product, 2);
    echo "✅ Produit ajouté au panier (quantité: 2)\n";
    
    // Vérifier les calculs
    echo "\n📊 Détails du panier:\n";
    echo "   Produit: {$cartItem->product_name}\n";
    echo "   Prix unitaire HT: {$cartItem->formatted_unit_price}\n";
    echo "   Quantité: {$cartItem->quantity}\n";
    echo "   Sous-total HT: {$cartItem->formatted_subtotal}\n";
    echo "   TVA ({$cartItem->tax_rate}%): {$cartItem->formatted_tax_amount}\n";
    echo "   Total TTC: {$cartItem->formatted_total}\n";
    
    echo "\n📈 Résumé du panier:\n";
    echo "   Nombre d'articles: {$cart->total_items}\n";
    echo "   Sous-total HT: {$cart->formatted_subtotal}\n";
    echo "   Total TVA: {$cart->formatted_total_tax}\n";
    echo "   Frais de livraison: {$cart->formatted_shipping_cost}\n";
    echo "   Total TTC: {$cart->formatted_total}\n";
    
    // Test de mise à jour de quantité
    echo "\n🔄 Test de mise à jour de quantité...\n";
    $cartItem->updateQuantity(3);
    echo "   Nouvelle quantité: {$cartItem->quantity}\n";
    echo "   Nouveau total TTC: {$cartItem->formatted_total}\n";
    echo "   Nouveau total panier: {$cart->fresh()->formatted_total}\n";
    
    // Test de suppression
    echo "\n🗑️ Test de suppression...\n";
    $cartItem->delete();
    $cart = $cart->fresh();
    echo "   Articles restants: {$cart->total_items}\n";
    echo "   Panier vide: " . ($cart->isEmpty() ? 'Oui' : 'Non') . "\n";
    
    echo "\n🎉 Tous les tests du panier sont passés avec succès!\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "   Ligne: " . $e->getLine() . "\n";
    echo "   Fichier: " . $e->getFile() . "\n";
    exit(1);
}
