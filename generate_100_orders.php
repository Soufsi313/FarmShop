<?php

/**
 * Script pour générer 100 commandes d'achat réalistes
 * avec les produits et utilisateurs existants en base de données
 * sans affecter le stock
 */

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Str;

// Initialiser Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🚀 Génération de 100 commandes d'achat réalistes...\n\n";

// Récupérer les utilisateurs et produits existants
$users = User::whereNotNull('email_verified_at')->get();
$products = Product::where('quantity', '>', 0)->get();

if ($users->count() === 0) {
    echo "❌ Aucun utilisateur vérifié trouvé en base de données.\n";
    exit;
}

if ($products->count() === 0) {
    echo "❌ Aucun produit actif trouvé en base de données.\n";
    exit;
}

echo "👥 {$users->count()} utilisateurs trouvés\n";
echo "📦 {$products->count()} produits trouvés\n\n";

// Statuts possibles pour les commandes
$orderStatuses = [
    'delivered' => 60,    // 60% livrées
    'cancelled' => 20,    // 20% annulées
    'returned' => 15,     // 15% retournées
    'shipped' => 3,       // 3% en cours de livraison
    'preparing' => 2      // 2% en préparation
];

// Méthodes de paiement
$paymentMethods = ['stripe', 'paypal', 'bank_transfer', 'cash_on_delivery'];

// Méthodes de livraison
$shippingMethods = ['standard', 'express', 'pickup'];

$generatedOrders = 0;
$totalAmount = 0;

for ($i = 1; $i <= 100; $i++) {
    try {
        // Sélectionner un utilisateur aléatoire
        $user = $users->random();
        
        // Déterminer le statut de la commande selon la distribution
        $statusRand = rand(1, 100);
        $orderStatus = 'delivered';
        $cumulative = 0;
        foreach ($orderStatuses as $status => $percentage) {
            $cumulative += $percentage;
            if ($statusRand <= $cumulative) {
                $orderStatus = $status;
                break;
            }
        }
        
        // Générer une date de commande réaliste (3 derniers mois)
        $orderDate = Carbon::now()->subDays(rand(1, 90));
        
        // Créer la commande
        $order = new Order();
        $order->order_number = 'ORD-' . date('Y') . '-' . str_pad($i + 1000, 6, '0', STR_PAD_LEFT);
        $order->user_id = $user->id;
        $order->status = $orderStatus;
        $order->payment_method = $paymentMethods[array_rand($paymentMethods)];
        $order->payment_status = in_array($orderStatus, ['delivered', 'returned', 'shipped']) ? 'paid' : 'pending';
        $order->shipping_method = $shippingMethods[array_rand($shippingMethods)];
        
        // Adresses de facturation et livraison
        $billingAddress = [
            'first_name' => $user->first_name ?? 'John',
            'last_name' => $user->last_name ?? 'Doe',
            'email' => $user->email,
            'phone' => '+33 6 ' . rand(10, 99) . ' ' . rand(10, 99) . ' ' . rand(10, 99) . ' ' . rand(10, 99),
            'address_line_1' => rand(1, 999) . ' rue de la ' . ['Paix', 'Liberté', 'République', 'Mairie'][array_rand(['Paix', 'Liberté', 'République', 'Mairie'])],
            'city' => ['Paris', 'Lyon', 'Marseille', 'Toulouse', 'Nice', 'Nantes', 'Bordeaux'][array_rand(['Paris', 'Lyon', 'Marseille', 'Toulouse', 'Nice', 'Nantes', 'Bordeaux'])],
            'postal_code' => rand(10000, 99999),
            'country' => 'France'
        ];
        
        $order->billing_address = $billingAddress;
        $order->shipping_address = $billingAddress; // Même adresse pour simplifier
        
        // Générer les dates selon le statut
        switch ($orderStatus) {
            case 'delivered':
                $order->paid_at = $orderDate->copy()->addHours(rand(1, 6));
                $order->shipped_at = $orderDate->copy()->addDays(rand(1, 3));
                $order->delivered_at = $orderDate->copy()->addDays(rand(3, 10));
                $order->tracking_number = 'TR' . strtoupper(Str::random(8));
                $order->can_be_cancelled = false;
                $order->can_be_returned = true;
                $order->return_deadline = $order->delivered_at->copy()->addDays(14);
                break;
                
            case 'cancelled':
                $order->cancelled_at = $orderDate->copy()->addHours(rand(2, 48));
                $order->cancellation_reason = ['stock_shortage', 'customer_request', 'payment_failed'][array_rand(['stock_shortage', 'customer_request', 'payment_failed'])];
                $order->can_be_cancelled = false;
                break;
                
            case 'returned':
                $order->paid_at = $orderDate->copy()->addHours(rand(1, 6));
                $order->shipped_at = $orderDate->copy()->addDays(rand(1, 3));
                $order->delivered_at = $orderDate->copy()->addDays(rand(3, 10));
                $order->return_requested_at = $order->delivered_at->copy()->addDays(rand(1, 10));
                $order->tracking_number = 'TR' . strtoupper(Str::random(8));
                $order->can_be_cancelled = false;
                $order->can_be_returned = false;
                $order->payment_status = 'refunded';
                break;
                
            case 'shipped':
                $order->paid_at = $orderDate->copy()->addHours(rand(1, 6));
                $order->shipped_at = $orderDate->copy()->addDays(rand(1, 3));
                $order->estimated_delivery = $orderDate->copy()->addDays(rand(4, 7));
                $order->tracking_number = 'TR' . strtoupper(Str::random(8));
                $order->can_be_cancelled = false;
                break;
                
            case 'preparing':
                $order->paid_at = $orderDate->copy()->addHours(rand(1, 6));
                $order->estimated_delivery = $orderDate->copy()->addDays(rand(2, 5));
                $order->can_be_cancelled = true;
                break;
        }
        
        $order->created_at = $orderDate;
        $order->updated_at = $orderDate;
        
        // Montants temporaires (seront mis à jour après les items)
        $order->subtotal = 0;
        $order->tax_amount = 0;
        $order->total_amount = 0;
        
        // Sauvegarder la commande d'abord
        $order->save();
        
        // Générer les items de commande (1 à 5 produits par commande)
        $itemCount = rand(1, 5);
        $orderProducts = $products->random($itemCount);
        
        $subtotal = 0;
        $taxAmount = 0;
        $shippingCost = rand(0, 1) ? 0 : rand(5, 15); // Parfois gratuit
        
        foreach ($orderProducts as $product) {
            $quantity = rand(1, 3);
            $unitPrice = $product->price;
            
            // Appliquer parfois une remise
            $discountPercentage = rand(0, 100) < 20 ? rand(5, 25) : 0; // 20% de chance d'avoir une remise
            $discountAmount = $discountPercentage > 0 ? ($unitPrice * $discountPercentage / 100) : 0;
            $finalUnitPrice = $unitPrice - $discountAmount;
            
            $itemTotal = $finalUnitPrice * $quantity;
            $itemTax = $itemTotal * 0.20; // TVA 20%
            
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $product->id;
            $orderItem->product_name = $product->name;
            $orderItem->product_sku = $product->sku;
            $orderItem->product_description = substr($product->description ?? '', 0, 255);
            $orderItem->product_image = $product->featured_image;
            $orderItem->original_unit_price = $unitPrice;
            $orderItem->discount_percentage = $discountPercentage;
            $orderItem->discount_amount = $discountAmount;
            $orderItem->quantity = $quantity;
            $orderItem->unit_price = $finalUnitPrice;
            $orderItem->total_price = $itemTotal;
            $orderItem->tax_rate = 20.00;
            $orderItem->subtotal = $itemTotal;
            $orderItem->tax_amount = $itemTax;
            $orderItem->status = $orderStatus;
            $orderItem->is_returnable = in_array($orderStatus, ['delivered', 'returned']);
            $orderItem->is_returned = $orderStatus === 'returned';
            
            if ($orderStatus === 'returned') {
                $orderItem->returned_quantity = $quantity;
            }
            
            $orderItem->save();
            
            $subtotal += $itemTotal;
            $taxAmount += $itemTax;
        }
        
        // Mettre à jour les montants de la commande
        $order->update([
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'shipping_cost' => $shippingCost,
            'total_amount' => $subtotal + $taxAmount + $shippingCost,
            'invoice_number' => 'INV-' . date('Y') . '-' . str_pad($i + 2000, 6, '0', STR_PAD_LEFT),
            'invoice_generated_at' => $orderDate->copy()->addHours(1)
        ]);
        
        $generatedOrders++;
        echo "✅ Commande #{$order->order_number} créée - Statut: {$orderStatus} - Montant: {$order->total_amount}€ - Client: {$user->email}\n";
        
    } catch (Exception $e) {
        echo "❌ Erreur lors de la création de la commande #$i: " . $e->getMessage() . "\n";
    }
}

echo "\n🎉 Génération terminée!\n";
echo "📊 Résumé:\n";
echo "   - Commandes générées: $generatedOrders/100\n";

// Calculer le montant total
$totalRevenue = Order::sum('total_amount');
echo "   - Montant total des commandes: " . number_format($totalRevenue, 2) . "€\n";

// Afficher la répartition des statuts
echo "\n📈 Répartition des statuts:\n";
$statusCounts = Order::selectRaw('status, COUNT(*) as count')->groupBy('status')->get();
foreach ($statusCounts as $statusCount) {
    echo "   - {$statusCount->status}: {$statusCount->count} commandes\n";
}

echo "\n🔗 Consultez votre dashboard: http://127.0.0.1:8000/admin/orders\n";
