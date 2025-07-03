<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Diagnostic du problème de paiement ===\n\n";

// 1. Vérifier les dernières commandes
echo "📊 Dernières commandes:\n";
$orders = App\Models\Order::latest()->take(5)->get();
foreach ($orders as $order) {
    echo "- {$order->order_number} | User {$order->user_id} | {$order->status} | {$order->total_amount}€ | {$order->created_at}\n";
}

echo "\n";

// 2. Vérifier les paniers existants
echo "🛒 Paniers actifs:\n";
$cartItems = App\Models\CartItem::with(['product'])->get();
foreach ($cartItems as $item) {
    $user = App\Models\User::find($item->user_id);
    echo "- User {$item->user_id} ({$user->name}) | Produit: {$item->product->name} | Qty: {$item->quantity} | Prix: {$item->total_price}€\n";
}

if ($cartItems->isEmpty()) {
    echo "- Aucun panier actif\n";
}

echo "\n";

// 3. Vérifier les sessions actives
echo "💾 Données de session actives:\n";
$sessionFiles = glob(storage_path('framework/sessions/*'));
echo "- Nombre de fichiers de session: " . count($sessionFiles) . "\n";

echo "\n";

// 4. Vérifier les PaymentIntents récents dans les logs
echo "💳 Vérification des logs de paiement:\n";
$logFile = storage_path('logs/laravel.log');
if (file_exists($logFile)) {
    $logContent = file_get_contents($logFile);
    if (strpos($logContent, 'Payment') !== false) {
        echo "- Des logs de paiement ont été trouvés\n";
        
        // Extraire les dernières lignes contenant "Payment"
        $lines = explode("\n", $logContent);
        $paymentLines = array_filter($lines, function($line) {
            return strpos($line, 'Payment') !== false;
        });
        
        $lastPaymentLines = array_slice($paymentLines, -5);
        foreach ($lastPaymentLines as $line) {
            echo "  " . substr($line, 0, 100) . "...\n";
        }
    } else {
        echo "- Aucun log de paiement trouvé\n";
    }
} else {
    echo "- Fichier de log non trouvé\n";
}

echo "\n";

// 5. Tester la génération d'un numéro de commande
echo "🔢 Test génération numéro de commande:\n";
try {
    $orderNumber = App\Models\Order::generateOrderNumber();
    echo "- Numéro généré: {$orderNumber}\n";
} catch (Exception $e) {
    echo "- Erreur: {$e->getMessage()}\n";
}

echo "\n";

// 6. Vérifier les routes de paiement
echo "🛣️ Vérification des routes de paiement:\n";
$routes = \Illuminate\Support\Facades\Route::getRoutes();
$paymentRoutes = [];
foreach ($routes as $route) {
    if (strpos($route->getName(), 'payment') !== false) {
        $paymentRoutes[] = $route->getName() . ' => ' . $route->uri();
    }
}

if (empty($paymentRoutes)) {
    echo "- Aucune route de paiement trouvée\n";
} else {
    foreach ($paymentRoutes as $route) {
        echo "- {$route}\n";
    }
}

echo "\n=== Fin du diagnostic ===\n";
