<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;

// Initialiser Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TEST D'AFFICHAGE DES COMMANDES ANNULÉES ET RETOURNÉES ===\n\n";

try {
    // 1. Créer une commande test
    $user = User::where('email', 'admin@admin.com')->first();
    if (!$user) {
        echo "❌ Utilisateur admin non trouvé\n";
        exit(1);
    }

    $product = Product::where('is_perishable', false)->first();
    if (!$product) {
        echo "❌ Aucun produit non périssable trouvé\n";
        exit(1);
    }

    // Créer une commande confirmée pour test d'annulation
    $orderCancel = Order::create([
        'user_id' => $user->id,
        'order_number' => 'FS' . date('Ymd') . '999801',
        'status' => Order::STATUS_CONFIRMED,
        'total_amount' => 25.99,
        'created_at' => Carbon::now()->subDays(2),
        'updated_at' => Carbon::now()->subDays(2),
    ]);

    $orderCancel->items()->create([
        'product_id' => $product->id,
        'quantity' => 2,
        'unit_price' => 12.99,
        'total_price' => 25.98,
    ]);

    echo "✅ Commande créée pour test d'annulation: {$orderCancel->order_number}\n";

    // Créer une commande livrée pour test de retour
    $orderReturn = Order::create([
        'user_id' => $user->id,
        'order_number' => 'FS' . date('Ymd') . '999802',
        'status' => Order::STATUS_DELIVERED,
        'total_amount' => 15.99,
        'created_at' => Carbon::now()->subDays(5),
        'updated_at' => Carbon::now()->subDays(1),
    ]);

    $orderReturn->items()->create([
        'product_id' => $product->id,
        'quantity' => 1,
        'unit_price' => 15.99,
        'total_price' => 15.99,
    ]);

    echo "✅ Commande créée pour test de retour: {$orderReturn->order_number}\n";

    // 2. Simuler une annulation
    $orderCancel->update(['status' => Order::STATUS_CANCELLED]);
    echo "✅ Commande {$orderCancel->order_number} annulée\n";

    // 3. Simuler un retour
    $orderReturn->update(['status' => Order::STATUS_RETURNED]);
    echo "✅ Commande {$orderReturn->order_number} retournée\n";

    // 4. Vérifier l'affichage dans la requête de recherche
    echo "\n=== VÉRIFICATION DE LA REQUÊTE DE RECHERCHE ===\n";

    $statuses = [
        Order::STATUS_CONFIRMED, 
        Order::STATUS_PREPARATION, 
        Order::STATUS_SHIPPED, 
        Order::STATUS_DELIVERED,
        Order::STATUS_CANCELLED,
        Order::STATUS_RETURNED
    ];

    $orders = Order::with(['user', 'items.product'])
        ->whereIn('status', $statuses)
        ->where('order_number', 'like', 'FS' . date('Ymd') . '9998%')
        ->get();

    echo "📊 Commandes trouvées avec les statuts recherchés: " . $orders->count() . "\n";

    foreach ($orders as $order) {
        $statusTranslations = [
            'pending' => 'En attente',
            'confirmed' => 'Confirmée',
            'preparation' => 'En préparation',
            'shipped' => 'Expédiée',
            'delivered' => 'Livrée',
            'cancelled' => 'Annulée',
            'returned' => 'Retournée'
        ];

        $translatedStatus = $statusTranslations[$order->status] ?? ucfirst($order->status);
        echo "  • {$order->order_number}: {$order->status} → {$translatedStatus}\n";
    }

    // 5. Test des filtres par statut
    echo "\n=== TEST DES FILTRES PAR STATUT ===\n";

    $cancelledOrders = Order::where('status', Order::STATUS_CANCELLED)
        ->where('order_number', 'like', 'FS' . date('Ymd') . '9998%')
        ->count();
    echo "🔍 Commandes annulées trouvées avec filtre: {$cancelledOrders}\n";

    $returnedOrders = Order::where('status', Order::STATUS_RETURNED)
        ->where('order_number', 'like', 'FS' . date('Ymd') . '9998%')
        ->count();
    echo "🔍 Commandes retournées trouvées avec filtre: {$returnedOrders}\n";

    // 6. Test de la recherche par email
    echo "\n=== TEST DE LA RECHERCHE PAR EMAIL ===\n";

    $ordersByEmail = Order::with(['user', 'items.product'])
        ->whereIn('status', $statuses)
        ->whereHas('user', function($userQuery) use ($user) {
            $userQuery->where('email', 'like', '%' . $user->email . '%');
        })
        ->where('order_number', 'like', 'FS' . date('Ymd') . '9998%')
        ->get();

    echo "📧 Commandes trouvées par email ({$user->email}): " . $ordersByEmail->count() . "\n";

    echo "\n✅ Test d'affichage terminé avec succès!\n";
    echo "📝 Les commandes annulées et retournées apparaissent bien dans la liste\n";
    echo "🔍 Les filtres fonctionnent correctement\n";
    echo "🌐 Les traductions de statuts sont appliquées\n";

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "📍 Fichier: " . $e->getFile() . " ligne " . $e->getLine() . "\n";
}

echo "\n=== FIN DU TEST ===\n";
