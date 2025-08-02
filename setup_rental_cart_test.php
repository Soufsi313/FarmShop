<?php
require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Product;
use App\Models\CartLocation;
use Carbon\Carbon;

echo "=== Préparation du panier de location pour test ===\n\n";

// Trouver un utilisateur
$user = User::first();
if (!$user) {
    echo "❌ Aucun utilisateur trouvé\n";
    exit;
}

echo "👤 Utilisateur: {$user->name} (ID: {$user->id})\n";

// Trouver des produits de location
$products = Product::where('is_rental', true)
    ->where('rental_price_per_day', '>', 0)
    ->take(2)
    ->get();

if ($products->isEmpty()) {
    echo "❌ Aucun produit de location trouvé\n";
    exit;
}

echo "📦 Produits trouvés: " . $products->count() . "\n";

// Créer ou récupérer le panier de location
$cartLocation = $user->activeCartLocation;
if (!$cartLocation) {
    $cartLocation = CartLocation::create([
        'user_id' => $user->id,
        'default_start_date' => Carbon::now()->addDay(),
        'default_end_date' => Carbon::now()->addDays(8),
        'default_duration_days' => 7
    ]);
    echo "🛒 Nouveau panier de location créé\n";
} else {
    echo "🛒 Panier de location existant trouvé\n";
}

// Vider le panier s'il contient déjà des items
if ($cartLocation->items->count() > 0) {
    $cartLocation->items()->delete();
    echo "🧹 Panier vidé\n";
}

// Ajouter des produits au panier
foreach ($products as $product) {
    $startDate = Carbon::now()->addDays(2);
    $endDate = Carbon::now()->addDays(9);
    
    $cartLocation->addProduct($product, 1, $startDate, $endDate);
    echo "✅ Ajouté: {$product->name} ({$product->rental_price_per_day}€/jour)\n";
}

// Recalculer le total
$cartLocation->recalculateTotal();

// Afficher le résumé
$summary = $cartLocation->getSummary();
echo "\n📊 Résumé du panier:\n";
echo "   - Items: {$summary['total_items']}\n";
echo "   - Quantité totale: {$summary['total_quantity']}\n";
echo "   - Sous-total: " . number_format($summary['total_amount'], 2) . "€\n";
echo "   - Caution: " . number_format($summary['total_deposit'], 2) . "€\n";
echo "   - TVA: " . number_format($summary['total_tva'], 2) . "€\n";
echo "   - Total avec TVA: " . number_format($summary['total_with_tax'], 2) . "€\n";

echo "\n✅ Panier prêt pour le test de checkout !\n";
echo "🌐 Visitez: http://127.0.0.1:8000/cart-location\n";
echo "🌐 Puis: http://127.0.0.1:8000/checkout-rental\n";
