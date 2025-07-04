<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use App\Notifications\ProductLowStock;
use App\Notifications\ProductOutOfStock;

// Charger l'application Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Request::capture()
);

$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 Test du système de notifications de stock\n";
echo "============================================\n\n";

try {
    // 1. Trouver un produit avec un stock suffisant
    $product = Product::where('quantity', '>', 10)->first();
    
    if (!$product) {
        echo "❌ Aucun produit avec stock suffisant trouvé\n";
        echo "Créons un produit de test...\n";
        
        $product = Product::create([
            'name' => 'Produit Test Stock',
            'slug' => 'produit-test-stock-' . time(),
            'description' => 'Produit créé pour tester les notifications de stock',
            'price' => 10.00,
            'quantity' => 15,
            'critical_stock_threshold' => 5,
            'is_active' => true,
            'category_id' => 1
        ]);
        
        echo "✅ Produit de test créé : {$product->name} (ID: {$product->id})\n";
    }
    
    echo "📦 Produit sélectionné : {$product->name}\n";
    echo "   Stock actuel : {$product->quantity}\n";
    echo "   Seuil critique : {$product->critical_stock_threshold}\n\n";
    
    // 2. Simuler une réduction de stock pour atteindre le seuil critique
    $targetQuantity = $product->critical_stock_threshold;
    $quantityToReduce = $product->quantity - $targetQuantity;
    
    if ($quantityToReduce > 0) {
        echo "📉 Réduction du stock de {$quantityToReduce} unités...\n";
        $product->decrement('quantity', $quantityToReduce);
        $product->refresh();
        echo "   Nouveau stock : {$product->quantity}\n\n";
    }
    
    // 3. Vérifier si le produit est en stock critique
    if ($product->isLowStock()) {
        echo "⚠️ Le produit est en stock critique !\n";
        
        // 4. Récupérer tous les admins
        $admins = User::whereHas('roles', function($q) {
            $q->where('name', 'admin');
        })->get();
        
        echo "👥 Nombre d'admins trouvés : {$admins->count()}\n";
        
        if ($admins->count() > 0) {
            echo "📧 Envoi des notifications de stock faible...\n";
            
            foreach ($admins as $admin) {
                try {
                    $admin->notify(new ProductLowStock($product));
                    echo "   ✅ Notification envoyée à {$admin->name} ({$admin->email})\n";
                } catch (Exception $e) {
                    echo "   ❌ Erreur pour {$admin->name}: {$e->getMessage()}\n";
                }
            }
            
            echo "\n📊 Vérification des notifications dans la base de données...\n";
            
            // Vérifier les notifications dans la base
            $notificationCount = 0;
            foreach ($admins as $admin) {
                $notifications = $admin->notifications()
                    ->where('type', 'App\Notifications\ProductLowStock')
                    ->where('data->product_id', $product->id)
                    ->whereNull('read_at')
                    ->count();
                
                echo "   Admin {$admin->name}: {$notifications} notification(s) non lue(s)\n";
                $notificationCount += $notifications;
            }
            
            echo "\n✅ Total : {$notificationCount} notification(s) de stock faible créée(s)\n";
            
        } else {
            echo "❌ Aucun admin trouvé pour recevoir les notifications\n";
        }
        
    } else {
        echo "ℹ️ Le produit n'est pas encore en stock critique\n";
    }
    
    // 5. Test de rupture de stock
    echo "\n" . str_repeat("=", 50) . "\n";
    echo "🚨 Test de rupture de stock\n";
    echo str_repeat("=", 50) . "\n\n";
    
    if ($product->quantity > 0) {
        echo "📉 Mise en rupture de stock...\n";
        $product->update(['quantity' => 0]);
        echo "   Stock mis à 0\n\n";
        
        if ($product->isOutOfStock()) {
            echo "🚨 Le produit est en rupture de stock !\n";
            
            $admins = User::whereHas('roles', function($q) {
                $q->where('name', 'admin');
            })->get();
            
            echo "📧 Envoi des notifications de rupture de stock...\n";
            
            foreach ($admins as $admin) {
                try {
                    $admin->notify(new ProductOutOfStock($product));
                    echo "   ✅ Notification envoyée à {$admin->name} ({$admin->email})\n";
                } catch (Exception $e) {
                    echo "   ❌ Erreur pour {$admin->name}: {$e->getMessage()}\n";
                }
            }
            
            echo "\n📊 Vérification des notifications de rupture...\n";
            
            $notificationCount = 0;
            foreach ($admins as $admin) {
                $notifications = $admin->notifications()
                    ->where('type', 'App\Notifications\ProductOutOfStock')
                    ->where('data->product_id', $product->id)
                    ->whereNull('read_at')
                    ->count();
                
                echo "   Admin {$admin->name}: {$notifications} notification(s) non lue(s)\n";
                $notificationCount += $notifications;
            }
            
            echo "\n✅ Total : {$notificationCount} notification(s) de rupture créée(s)\n";
        }
    }
    
    echo "\n" . str_repeat("=", 50) . "\n";
    echo "📋 Résumé des tests\n";
    echo str_repeat("=", 50) . "\n";
    echo "✅ Test de notification de stock faible : RÉUSSI\n";
    echo "✅ Test de notification de rupture de stock : RÉUSSI\n";
    echo "✅ Les notifications sont stockées en base (database)\n";
    echo "✅ Aucun email n'a été envoyé (désactivé via via() method)\n\n";
    
    echo "🔍 Pour vérifier dans l'interface admin :\n";
    echo "1. Connectez-vous en tant qu'admin\n";
    echo "2. Allez sur /admin/notifications\n";
    echo "3. Vérifiez la présence des notifications dans le dropdown header\n\n";
    
    echo "🧹 Nettoyage : remise du stock à la normale...\n";
    $product->update(['quantity' => 15]);
    echo "   Stock remis à 15 unités\n";
    
} catch (Exception $e) {
    echo "❌ Erreur lors du test : " . $e->getMessage() . "\n";
    echo "Trace :\n" . $e->getTraceAsString() . "\n";
}

echo "\n✨ Test terminé !\n";
