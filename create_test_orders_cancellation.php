<?php

require_once 'vendor/autoload.php';

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';

// Démarrer l'application
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;

echo "🧪 Création de commandes de test pour les annulations et retours...\n\n";

// Créer un utilisateur de test s'il n'existe pas
$testUser = User::firstOrCreate(
    ['email' => 'test.client@farmshop.com'],
    [
        'name' => 'Client Test',
        'username' => 'clienttest',
        'password' => bcrypt('password'),
        'email_verified_at' => now(),
    ]
);

// Récupérer quelques produits
$products = Product::where('quantity', '>', 5)->take(4)->get();

if ($products->count() < 4) {
    echo "❌ Pas assez de produits en stock pour créer les commandes de test\n";
    exit;
}

// Adresses par défaut pour les tests
$defaultAddresses = [
    'shipping_address' => json_encode([
        'name' => 'Client Test',
        'address' => '123 Rue de Test',
        'city' => 'Ville Test',
        'postal_code' => '12345',
        'country' => 'France'
    ]),
    'billing_address' => json_encode([
        'name' => 'Client Test',
        'address' => '123 Rue de Test',
        'city' => 'Ville Test',
        'postal_code' => '12345',
        'country' => 'France'
    ]),
    'payment_method' => 'card',
];

// 1. Commande confirmée (peut être annulée)
$order1 = Order::create(array_merge([
    'user_id' => $testUser->id,
    'order_number' => 'FS' . now()->format('Ymd') . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
    'status' => Order::STATUS_CONFIRMED,
    'confirmed_at' => now()->subMinutes(30),
    'subtotal' => 25.99,
    'tax_amount' => 5.20,
    'shipping_cost' => 4.99,
    'total_amount' => 36.18,
    'shipping_method' => 'standard',
    'payment_status' => Order::PAYMENT_PAID,
    'paid_at' => now()->subMinutes(35),
], $defaultAddresses));

// Ajouter des articles (mélange périssable/non périssable)
OrderItem::create([
    'order_id' => $order1->id,
    'product_id' => $products[0]->id,
    'product_name' => $products[0]->name,
    'quantity' => 2,
    'unit_price' => $products[0]->price,
    'total_price' => 2 * $products[0]->price,
    'is_perishable' => $products[0]->is_perishable ?? false,
    'is_returnable' => $products[0]->is_returnable ?? true,
]);

OrderItem::create([
    'order_id' => $order1->id,
    'product_id' => $products[1]->id,
    'product_name' => $products[1]->name,
    'quantity' => 1,
    'unit_price' => $products[1]->price,
    'total_price' => 1 * $products[1]->price,
    'is_perishable' => $products[1]->is_perishable ?? false,
    'is_returnable' => $products[1]->is_returnable ?? true,
]);

echo "✅ Commande confirmée créée: #{$order1->order_number} (peut être annulée)\n";

// 2. Commande en préparation (peut être annulée)
$order2 = Order::create(array_merge([
    'user_id' => $testUser->id,
    'order_number' => 'FS' . now()->format('Ymd') . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
    'status' => Order::STATUS_PREPARATION,
    'confirmed_at' => now()->subHours(2),
    'preparation_at' => now()->subMinutes(45),
    'subtotal' => 32.50,
    'tax_amount' => 6.50,
    'shipping_cost' => 4.99,
    'total_amount' => 43.99,
    'shipping_method' => 'express',
    'payment_status' => Order::PAYMENT_PAID,
    'paid_at' => now()->subHours(2)->subMinutes(5),
], $defaultAddresses));

OrderItem::create([
    'order_id' => $order2->id,
    'product_id' => $products[2]->id,
    'product_name' => $products[2]->name,
    'quantity' => 3,
    'price' => $products[2]->price,
    'total_price' => 3 * $products[2]->price,
]);

echo "✅ Commande en préparation créée: #{$order2->order_number} (peut être annulée)\n";

// 3. Commande expédiée (ne peut plus être annulée)
$order3 = Order::create(array_merge([
    'user_id' => $testUser->id,
    'order_number' => 'FS' . now()->format('Ymd') . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
    'status' => Order::STATUS_SHIPPED,
    'confirmed_at' => now()->subDays(2),
    'preparation_at' => now()->subDays(2)->addMinutes(90),
    'shipped_at' => now()->subDays(1),
    'subtotal' => 45.00,
    'tax_amount' => 9.00,
    'shipping_cost' => 6.99,
    'total_amount' => 60.99,
    'shipping_method' => 'express',
    'tracking_number' => 'TRK' . rand(100000, 999999),
    'payment_status' => Order::PAYMENT_PAID,
    'paid_at' => now()->subDays(2)->subMinutes(5),
], $defaultAddresses));

OrderItem::create([
    'order_id' => $order3->id,
    'product_id' => $products[3]->id,
    'product_name' => $products[3]->name,
    'quantity' => 2,
    'price' => $products[3]->price,
    'total_price' => 2 * $products[3]->price,
]);

echo "✅ Commande expédiée créée: #{$order3->order_number} (ne peut plus être annulée)\n";

// 4. Commande livrée (peut être retournée si dans les 14 jours)
$order4 = Order::create(array_merge([
    'user_id' => $testUser->id,
    'order_number' => 'FS' . now()->format('Ymd') . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
    'status' => Order::STATUS_DELIVERED,
    'confirmed_at' => now()->subDays(5),
    'preparation_at' => now()->subDays(5)->addMinutes(90),
    'shipped_at' => now()->subDays(4),
    'delivered_at' => now()->subDays(3),
    'subtotal' => 28.75,
    'tax_amount' => 5.75,
    'shipping_cost' => 4.99,
    'total_amount' => 39.49,
    'shipping_method' => 'standard',
    'tracking_number' => 'TRK' . rand(100000, 999999),
    'payment_status' => Order::PAYMENT_PAID,
    'paid_at' => now()->subDays(5)->subMinutes(5),
    'return_deadline' => now()->subDays(3)->addDays(14), // 14 jours à partir de la livraison
], $defaultAddresses));

OrderItem::create([
    'order_id' => $order4->id,
    'product_id' => $products[0]->id,
    'product_name' => $products[0]->name,
    'quantity' => 1,
    'price' => $products[0]->price,
    'total_price' => 1 * $products[0]->price,
]);

OrderItem::create([
    'order_id' => $order4->id,
    'product_id' => $products[1]->id,
    'product_name' => $products[1]->name,
    'quantity' => 2,
    'price' => $products[1]->price,
    'total_price' => 2 * $products[1]->price,
]);

echo "✅ Commande livrée créée: #{$order4->order_number} (peut être retournée)\n";

// 5. Commande livrée ancienne (délai de retour dépassé)
$order5 = Order::create(array_merge([
    'user_id' => $testUser->id,
    'order_number' => 'FS' . now()->format('Ymd') . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
    'status' => Order::STATUS_DELIVERED,
    'confirmed_at' => now()->subDays(20),
    'preparation_at' => now()->subDays(20)->addMinutes(90),
    'shipped_at' => now()->subDays(19),
    'delivered_at' => now()->subDays(18),
    'subtotal' => 35.20,
    'tax_amount' => 7.04,
    'shipping_cost' => 4.99,
    'total_amount' => 47.23,
    'shipping_method' => 'standard',
    'tracking_number' => 'TRK' . rand(100000, 999999),
    'payment_status' => Order::PAYMENT_PAID,
    'paid_at' => now()->subDays(20)->subMinutes(5),
    'return_deadline' => now()->subDays(18)->addDays(14), // Délai dépassé
], $defaultAddresses));

OrderItem::create([
    'order_id' => $order5->id,
    'product_id' => $products[2]->id,
    'product_name' => $products[2]->name,
    'quantity' => 2,
    'price' => $products[2]->price,
    'total_price' => 2 * $products[2]->price,
]);

echo "✅ Commande livrée ancienne créée: #{$order5->order_number} (délai de retour dépassé)\n";

echo "\n🎯 5 commandes de test créées avec succès !\n";
echo "📧 Client de test: {$testUser->email}\n";
echo "🔐 Mot de passe: password\n\n";

echo "📋 Résumé des tests possibles :\n";
echo "• #{$order1->order_number} - Confirmée → Peut être annulée\n";
echo "• #{$order2->order_number} - En préparation → Peut être annulée\n";
echo "• #{$order3->order_number} - Expédiée → Ne peut plus être annulée\n";
echo "• #{$order4->order_number} - Livrée → Peut être retournée\n";
echo "• #{$order5->order_number} - Livrée ancienne → Délai de retour dépassé\n";

echo "\n🚀 Rendez-vous sur /admin/orders/cancellation pour tester !\n";
