<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\OrderLocation;
use App\Models\OrderItemLocation;
use App\Models\CartLocation;
use App\Models\CartItemLocation;

echo "🧹 Nettoyage des données de location...\n\n";

try {
    // Désactiver temporairement les vérifications de clés étrangères
    DB::statement('SET FOREIGN_KEY_CHECKS=0');

    // 1. Compter avant suppression
    $deletedOrderItems = OrderItemLocation::count();
    $deletedOrders = OrderLocation::count();
    $deletedCartItems = CartItemLocation::count();
    $deletedCarts = CartLocation::count();

    // 2. Supprimer tous les éléments des commandes de location
    OrderItemLocation::query()->delete();
    echo "✅ {$deletedOrderItems} éléments de commandes de location supprimés\n";

    // 3. Supprimer toutes les commandes de location
    OrderLocation::query()->delete();
    echo "✅ {$deletedOrders} commandes de location supprimées\n";

    // 4. Supprimer tous les éléments des paniers de location
    CartItemLocation::query()->delete();
    echo "✅ {$deletedCartItems} éléments de paniers de location supprimés\n";

    // 5. Supprimer tous les paniers de location
    CartLocation::query()->delete();
    echo "✅ {$deletedCarts} paniers de location supprimés\n";

    // 6. Remettre à zéro les séquences auto-increment
    DB::statement('ALTER TABLE order_locations AUTO_INCREMENT = 1');
    DB::statement('ALTER TABLE order_item_locations AUTO_INCREMENT = 1');
    DB::statement('ALTER TABLE cart_locations AUTO_INCREMENT = 1');
    DB::statement('ALTER TABLE cart_item_locations AUTO_INCREMENT = 1');

    // Réactiver les vérifications de clés étrangères
    DB::statement('SET FOREIGN_KEY_CHECKS=1');

    echo "\n🎉 Nettoyage terminé avec succès !\n";
    echo "📊 Résumé :\n";
    echo "   - {$deletedOrders} commandes de location supprimées\n";
    echo "   - {$deletedOrderItems} éléments de commandes supprimés\n";
    echo "   - {$deletedCarts} paniers de location supprimés\n";
    echo "   - {$deletedCartItems} éléments de paniers supprimés\n";
    echo "\n✨ Vous pouvez maintenant créer une nouvelle location de test !\n";

} catch (Exception $e) {
    // Réactiver les vérifications de clés étrangères en cas d'erreur
    DB::statement('SET FOREIGN_KEY_CHECKS=1');
    echo "❌ Erreur lors du nettoyage : " . $e->getMessage() . "\n";
    exit(1);
}
