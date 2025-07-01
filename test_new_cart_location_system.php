<?php
// Script de test pour le nouveau système de panier de location
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\CartLocation;
use App\Models\CartItemLocation;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

echo "=== Test du nouveau système de panier de location ===\n\n";

try {
    // 1. Trouver un utilisateur de test
    $user = User::where('email', 'like', '%test%')->first();
    if (!$user) {
        $user = User::first();
    }
    
    if (!$user) {
        echo "❌ Aucun utilisateur trouvé pour les tests\n";
        exit;
    }
    
    echo "👤 Utilisateur de test: {$user->name} (ID: {$user->id})\n\n";

    // 2. Nettoyer les paniers existants
    CartLocation::where('user_id', $user->id)->delete();
    echo "🧹 Paniers de location nettoyés\n\n";

    // 3. Trouver des produits de location
    $products = Product::where('is_rentable', true)->take(3)->get();
    if ($products->count() === 0) {
        // Créer quelques produits de test
        $products = collect([
            Product::create([
                'name' => 'Tracteur de test',
                'price' => 5000,
                'is_rentable' => true,
                'rental_price_per_day' => 50,
                'stock_quantity' => 2,
                'min_rental_days' => 1,
                'max_rental_days' => 30,
                'deposit_amount' => 500
            ]),
            Product::create([
                'name' => 'Charrue de test',
                'price' => 1500,
                'is_rentable' => true,
                'rental_price_per_day' => 25,
                'stock_quantity' => 3,
                'min_rental_days' => 1,
                'max_rental_days' => 15,
                'deposit_amount' => 200
            ])
        ]);
        echo "📦 Produits de test créés\n";
    }

    echo "📦 Produits disponibles pour location:\n";
    foreach ($products as $product) {
        echo "  - {$product->name} ({$product->rental_price_per_day}€/jour, stock: {$product->stock_quantity})\n";
        echo "    ID: {$product->id}, Dépôt: {$product->deposit_amount}€\n";
    }
    echo "\n";

    // 4. Créer un panier de location
    echo "🛒 Création du panier de location...\n";
    $cart = CartLocation::getActiveCartForUser($user->id);
    echo "✅ Panier créé (ID: {$cart->id}, statut: {$cart->status})\n\n";

    // 5. Ajouter des produits au panier
    $startDate = Carbon::tomorrow();
    $product1 = $products->first();
    $product2 = $products->skip(1)->first();

    echo "➕ Ajout du produit 1: {$product1->name} (stock: {$product1->stock_quantity})\n";
    $item1 = $cart->addItem(
        $product1->id,
        1, // quantité
        7, // durée en jours
        $startDate
    );

    if ($item1) {
        echo "✅ Produit 1 ajouté (ID: {$item1->id}, prix total: {$item1->total_price}€, caution: {$item1->deposit_amount}€)\n";
    } else {
        echo "❌ Échec de l'ajout du produit 1\n";
        echo "  Raison possible: stock insuffisant ({$product1->stock_quantity}) ou produit non trouvé\n";
    }

    if ($product2) {
        echo "➕ Ajout du produit 2: {$product2->name}\n";
        $item2 = $cart->addItem(
            $product2->id,
            2, // quantité
            5, // durée en jours
            $startDate
        );

        if ($item2) {
            echo "✅ Produit 2 ajouté (ID: {$item2->id}, prix total: {$item2->total_price}€, caution: {$item2->deposit_amount}€)\n";
        } else {
            echo "❌ Échec de l'ajout du produit 2\n";
        }
    }

    echo "\n";

    // 6. Vérifier le panier
    $cart->refresh();
    echo "📊 État du panier:\n";
    echo "  - Nombre d'articles: {$cart->total_items}\n";
    echo "  - Total location: {$cart->total_amount}€\n";
    echo "  - Total caution: {$cart->total_deposit}€\n";
    echo "  - Total général: {$cart->grand_total}€\n";
    echo "  - Statut: {$cart->status}\n\n";

    // 7. Lister les articles
    echo "📝 Articles dans le panier:\n";
    foreach ($cart->items as $item) {
        echo "  - {$item->product_name} x{$item->quantity} pour {$item->rental_duration_days} jours\n";
        echo "    Du {$item->rental_start_date->format('d/m/Y')} au {$item->rental_end_date->format('d/m/Y')}\n";
        echo "    Prix: {$item->total_price}€ + caution: {$item->deposit_amount}€\n";
        echo "    Statut: {$item->status}\n\n";
    }

    // 8. Modifier un article
    if (isset($item1)) {
        echo "✏️ Modification de la quantité du premier article...\n";
        if ($item1->updateQuantity(2)) {
            echo "✅ Quantité mise à jour: {$item1->quantity}\n";
            echo "  Nouveau prix total: {$item1->total_price}€\n";
        } else {
            echo "❌ Échec de la mise à jour\n";
        }
        echo "\n";

        echo "✏️ Prolongation de la durée du premier article...\n";
        if ($item1->extendRental(3)) {
            echo "✅ Durée prolongée: {$item1->rental_duration_days} jours\n";
            echo "  Nouveau prix total: {$item1->total_price}€\n";
        } else {
            echo "❌ Échec de la prolongation\n";
        }
        echo "\n";
    }

    // 9. Valider le panier
    echo "🔍 Validation du panier...\n";
    $issues = $cart->validate();
    if (empty($issues)) {
        echo "✅ Panier valide\n";
    } else {
        echo "❌ Problèmes détectés:\n";
        foreach ($issues as $issue) {
            echo "  - {$issue}\n";
        }
    }
    echo "\n";

    // 10. Soumettre le panier
    echo "📤 Soumission du panier...\n";
    if ($cart->submit()) {
        echo "✅ Panier soumis avec succès (nouveau statut: {$cart->status})\n";
        
        // Vérifier les statuts des articles
        foreach ($cart->items as $item) {
            echo "  - {$item->product_name}: {$item->status}\n";
        }
    } else {
        echo "❌ Échec de la soumission\n";
    }
    echo "\n";

    // 11. Tester la progression des statuts
    echo "🔄 Test de progression des statuts...\n";
    
    echo "  Confirmation du panier...\n";
    if ($cart->confirm()) {
        echo "  ✅ Panier confirmé (statut: {$cart->status})\n";
    } else {
        echo "  ❌ Échec de la confirmation\n";
    }

    echo "  Démarrage de la location...\n";
    if ($cart->start()) {
        echo "  ✅ Location démarrée (statut: {$cart->status})\n";
    } else {
        echo "  ❌ Échec du démarrage\n";
    }

    echo "  Finalisation de la location...\n";
    if ($cart->complete()) {
        echo "  ✅ Location finalisée (statut: {$cart->status})\n";
    } else {
        echo "  ❌ Échec de la finalisation\n";
    }

    echo "\n";

    // 12. Résumé final
    echo "📋 Résumé final:\n";
    echo "  - ID du panier: {$cart->id}\n";
    echo "  - Statut final: {$cart->status}\n";
    echo "  - Nombre d'articles: " . $cart->items()->count() . "\n";
    echo "  - Total final: {$cart->grand_total}€\n\n";

    echo "✅ Test du nouveau système de panier de location terminé avec succès!\n";

} catch (Exception $e) {
    echo "❌ Erreur durant le test: " . $e->getMessage() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
