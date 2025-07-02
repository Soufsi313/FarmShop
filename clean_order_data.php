<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\OrderLocation;
use App\Models\OrderItemLocation;
use App\Models\CartLocation;
use App\Models\CartItemLocation;
use Illuminate\Support\Facades\DB;

echo "\n🧹 Nettoyage des données de commandes de location\n";
echo "=" . str_repeat("=", 60) . "\n";

echo "\n⚠️  ATTENTION: Ce script va supprimer TOUTES les données de commandes de location !\n";
echo "   - Commandes de location (order_locations)\n";
echo "   - Articles de commandes de location (order_item_locations)\n";
echo "   - Paniers de location (cart_locations)\n";
echo "   - Articles de paniers de location (cart_item_locations)\n";
echo "\n✅ Données PRÉSERVÉES :\n";
echo "   - Produits de location (products)\n";
echo "   - Utilisateurs (users)\n";
echo "   - Commandes d'achat normales (orders)\n";
echo "   - Paniers d'achat normaux (carts)\n";

echo "\n📊 État actuel de la base de données :\n";
echo "   - Commandes de location : " . OrderLocation::count() . "\n";
echo "   - Articles de commandes de location : " . OrderItemLocation::count() . "\n";
echo "   - Paniers de location : " . CartLocation::count() . "\n";
echo "   - Articles de paniers de location : " . CartItemLocation::count() . "\n";

echo "\nAppuyez sur ENTRÉE pour continuer ou CTRL+C pour annuler...";
fgets(STDIN);

try {
    DB::beginTransaction();
    
    echo "\n🗑️  Suppression en cours...\n";
    
    // Désactiver les contraintes de clés étrangères temporairement
    DB::statement('SET FOREIGN_KEY_CHECKS = 0');
    
    // 1. Supprimer les articles de commandes de location
    $deletedOrderItems = OrderItemLocation::count();
    OrderItemLocation::truncate();
    echo "   ✅ Supprimé $deletedOrderItems articles de commandes de location\n";
    
    // 2. Supprimer les commandes de location
    $deletedOrders = OrderLocation::count();
    OrderLocation::truncate();
    echo "   ✅ Supprimé $deletedOrders commandes de location\n";
    
    // 3. Supprimer les articles de paniers de location
    $deletedCartItems = CartItemLocation::count();
    CartItemLocation::truncate();
    echo "   ✅ Supprimé $deletedCartItems articles de paniers de location\n";
    
    // 4. Supprimer les paniers de location
    $deletedCarts = CartLocation::count();
    CartLocation::truncate();
    echo "   ✅ Supprimé $deletedCarts paniers de location\n";
    
    // 5. Remettre à zéro les auto-increment pour un redémarrage propre
    DB::statement('ALTER TABLE order_locations AUTO_INCREMENT = 1');
    DB::statement('ALTER TABLE order_item_locations AUTO_INCREMENT = 1');
    DB::statement('ALTER TABLE cart_locations AUTO_INCREMENT = 1');
    DB::statement('ALTER TABLE cart_item_locations AUTO_INCREMENT = 1');
    echo "   ✅ Auto-increment remis à zéro\n";
    
    // Réactiver les contraintes de clés étrangères
    DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    
    DB::commit();
    
    echo "\n🎉 Nettoyage terminé avec succès !\n";
    echo "\n📊 État final de la base de données :\n";
    echo "   - Commandes de location : " . OrderLocation::count() . "\n";
    echo "   - Articles de commandes de location : " . OrderItemLocation::count() . "\n";
    echo "   - Paniers de location : " . CartLocation::count() . "\n";
    echo "   - Articles de paniers de location : " . CartItemLocation::count() . "\n";
    
    // Vérifier que les autres données sont préservées
    echo "\n✅ Données préservées :\n";
    echo "   - Utilisateurs : " . \App\Models\User::count() . "\n";
    echo "   - Produits : " . \App\Models\Product::count() . "\n";
    
    // Vérifier s'il y a des commandes d'achat normales
    if (class_exists('\App\Models\Order')) {
        echo "   - Commandes d'achat : " . \App\Models\Order::count() . "\n";
    }
    if (class_exists('\App\Models\Cart')) {
        echo "   - Paniers d'achat : " . \App\Models\Cart::count() . "\n";
    }
    
} catch (\Exception $e) {
    DB::rollBack();
    // Assurer que les contraintes sont réactivées même en cas d'erreur
    try {
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    } catch (\Exception $e2) {
        // Ignorer les erreurs de réactivation si la transaction a échoué
    }
    echo "\n❌ Erreur lors du nettoyage : " . $e->getMessage() . "\n";
    echo "Les données n'ont pas été modifiées.\n";
    exit(1);
}

echo "\n🚀 Le système est maintenant prêt pour de nouveaux tests !\n";
echo "Vous pouvez créer de nouvelles commandes de location depuis zéro.\n\n";
