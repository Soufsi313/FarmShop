<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== SIMULATION SIMPLE POUR DÉCLENCHER ALERTE CRITIQUE ===" . PHP_EOL . PHP_EOL;

// Récupérer le produit pommes vertes bio
$product = \App\Models\Product::where('name', 'like', '%pommes%')->where('name', 'like', '%bio%')->first();

if (!$product) {
    echo "❌ Produit pommes vertes bio non trouvé !" . PHP_EOL;
    exit;
}

echo "📦 ÉTAT INITIAL DU STOCK" . PHP_EOL;
echo "Produit: " . $product->name . PHP_EOL;
echo "Stock actuel: " . $product->quantity . PHP_EOL;
echo "Seuil critique: " . $product->critical_threshold . PHP_EOL;
echo "Seuil stock bas: " . ($product->low_stock_threshold ?? 'Non défini') . PHP_EOL;
echo PHP_EOL;

// Calculer la nouvelle quantité pour déclencher l'alerte critique
$currentStock = $product->quantity;
$criticalThreshold = $product->critical_threshold;
$newStock = $criticalThreshold - 5; // 5 unités en dessous du seuil critique

echo "🛒 SIMULATION DE VENTE" . PHP_EOL;
echo "Quantité vendue: " . ($currentStock - $newStock) . " kg" . PHP_EOL;
echo "Nouveau stock: " . $newStock . " kg" . PHP_EOL;
echo "Statut prévu: " . ($newStock < $criticalThreshold ? "🚨 CRITIQUE" : "✅ Normal") . PHP_EOL;
echo PHP_EOL;

// Mettre à jour le stock
echo "🔄 MISE À JOUR DU STOCK..." . PHP_EOL;
$product->update(['quantity' => $newStock]);
echo "✅ Stock mis à jour" . PHP_EOL . PHP_EOL;

// Vérifier l'état final
$product = $product->fresh();
echo "📊 ÉTAT FINAL DU STOCK" . PHP_EOL;
echo "Stock actuel: " . $product->quantity . PHP_EOL;
echo "Seuil critique: " . $product->critical_threshold . PHP_EOL;

$status = '';
if ($product->quantity == 0) {
    $status = "🔴 RUPTURE DE STOCK";
} elseif ($product->quantity <= $product->critical_threshold) {
    $status = "🚨 STOCK CRITIQUE";
} elseif ($product->low_stock_threshold && $product->quantity <= $product->low_stock_threshold) {
    $status = "⚠️ STOCK BAS";
} else {
    $status = "✅ STOCK NORMAL";
}

echo "Statut: " . $status . PHP_EOL . PHP_EOL;

// Créer manuellement une alerte de stock
if ($product->quantity <= $product->critical_threshold) {
    echo "🚨 CRÉATION D'ALERTE MANUELLE..." . PHP_EOL;
    
    // Récupérer Mr Clank (utilisateur système)
    $mrClank = \App\Models\User::where('email', 'system@farmshop.local')->first();
    if (!$mrClank) {
        // Fallback sur le premier admin si Mr Clank n'existe pas
        $mrClank = \App\Models\User::where('role', 'Admin')->first();
    }
    
    try {
        $alert = \App\Models\Message::create([
            'user_id' => $mrClank->id, // Destinataire (admin qui recevra l'alerte)
            'sender_id' => $mrClank->id, // Expéditeur : Mr Clank (système)
            'type' => 'stock_alert',
            'subject' => 'Stock Critique - ' . $product->name,
            'content' => "Le stock du produit '{$product->name}' est critique. Stock actuel: {$product->quantity} (seuil: {$product->critical_threshold})",
            'metadata' => json_encode([
                'product_id' => $product->id,
                'product_name' => $product->name,
                'current_stock' => $product->quantity,
                'critical_threshold' => $product->critical_threshold,
                'alert_type' => 'critical_stock',
                'automated_message' => true, // Marquer comme message automatique
                'system_sender' => true
            ]),
            'status' => 'unread',
            'priority' => 'high',
            'is_important' => true
        ]);
        
        echo "✅ Alerte créée (ID: " . $alert->id . ") - Expéditeur: " . $mrClank->name . PHP_EOL;
    } catch (Exception $e) {
        echo "❌ Erreur lors de la création de l'alerte: " . $e->getMessage() . PHP_EOL;
    }
}

// Vérifier les alertes récentes
echo PHP_EOL . "🔍 VÉRIFICATION DES ALERTES" . PHP_EOL;
$alerts = \App\Models\Message::where('type', 'stock_alert')
    ->where('created_at', '>=', now()->subMinutes(5))
    ->orderBy('created_at', 'desc')
    ->get();

if ($alerts->count() > 0) {
    echo "✅ " . $alerts->count() . " alerte(s) trouvée(s):" . PHP_EOL;
    foreach ($alerts as $alert) {
        echo "  - " . $alert->title . PHP_EOL;
        echo "    Contenu: " . $alert->content . PHP_EOL;
        echo "    Créée à: " . $alert->created_at->format('d/m/Y H:i:s') . PHP_EOL;
        echo "    Priorité: " . $alert->priority . PHP_EOL . PHP_EOL;
    }
} else {
    echo "⚠️ Aucune alerte trouvée dans les 5 dernières minutes" . PHP_EOL;
}

echo "🎯 SIMULATION TERMINÉE!" . PHP_EOL;
echo "✨ Consultez maintenant la section 'Gestion de Stock' dans l'admin pour voir l'alerte !" . PHP_EOL;
echo "🌐 URL: http://localhost:8000/admin/stock/alerts" . PHP_EOL;
