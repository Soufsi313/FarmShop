<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== SIMULATION RUPTURE DE STOCK - CHAUSSURES DE SÉCURITÉ ===" . PHP_EOL . PHP_EOL;

// Récupérer le produit chaussures de sécurité en cuir
$product = \App\Models\Product::where('name', 'like', '%chaussures%')->where('name', 'like', '%sécurité%')->where('name', 'like', '%cuir%')->first();

if (!$product) {
    echo "❌ Produit chaussures de sécurité en cuir non trouvé !" . PHP_EOL;
    exit;
}

echo "👢 ÉTAT INITIAL DU STOCK" . PHP_EOL;
echo "Produit: " . $product->name . PHP_EOL;
echo "Stock actuel: " . $product->quantity . PHP_EOL;
echo "Seuil critique: " . $product->critical_threshold . PHP_EOL;
echo "Prix: " . $product->price . "€" . PHP_EOL;
echo PHP_EOL;

echo "🛒 SIMULATION DE VENTE COMPLÈTE" . PHP_EOL;
echo "Quantité vendue: " . $product->quantity . " paires" . PHP_EOL;
echo "Nouveau stock: 0 paires" . PHP_EOL;
echo "Statut prévu: 🔴 RUPTURE DE STOCK" . PHP_EOL;
echo PHP_EOL;

// Mettre à jour le stock à 0
echo "🔄 MISE À JOUR DU STOCK..." . PHP_EOL;
$product->update(['quantity' => 0]);
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

// Créer une alerte de rupture de stock avec Mr Clank
echo "🚨 CRÉATION D'ALERTE DE RUPTURE DE STOCK..." . PHP_EOL;

// Récupérer Mr Clank (utilisateur système)
$mrClank = \App\Models\User::where('email', 'system@farmshop.local')->first();
if (!$mrClank) {
    echo "⚠️ Mr Clank non trouvé, utilisation d'un admin..." . PHP_EOL;
    $mrClank = \App\Models\User::where('role', 'Admin')->first();
}

// Récupérer un admin destinataire
$admin = \App\Models\User::where('role', 'Admin')->where('id', '!=', $mrClank->id)->first();
if (!$admin) {
    $admin = \App\Models\User::first(); // Fallback
}

try {
    $alert = \App\Models\Message::create([
        'user_id' => $admin->id, // Destinataire (admin qui recevra l'alerte)
        'sender_id' => $mrClank->id, // Expéditeur : Mr Clank (système)
        'type' => 'stock_alert',
        'subject' => '🔴 RUPTURE DE STOCK - ' . $product->name,
        'content' => "ALERTE URGENTE ! Le produit '{$product->name}' est en rupture de stock complète.\n\n" .
                    "📊 Détails :\n" .
                    "• Stock actuel : {$product->quantity} unités\n" .
                    "• Seuil critique : {$product->critical_threshold} unités\n" .
                    "• Prix unitaire : {$product->price}€\n\n" .
                    "⚡ ACTION REQUISE :\n" .
                    "Réapprovisionnement immédiat nécessaire pour éviter toute perte de vente.\n\n" .
                    "Ce message a été généré automatiquement par le système de gestion de stock.",
        'metadata' => json_encode([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'current_stock' => $product->quantity,
            'critical_threshold' => $product->critical_threshold,
            'alert_type' => 'out_of_stock',
            'severity' => 'critical',
            'automated_message' => true,
            'system_sender' => true,
            'product_price' => $product->price,
            'category' => $product->category->name ?? 'Non catégorisé',
            'created_by_system' => 'Mr Clank',
            'requires_immediate_action' => true
        ]),
        'status' => 'unread',
        'priority' => 'high',
        'is_important' => true
    ]);
    
    echo "✅ Alerte de rupture créée (ID: " . $alert->id . ")" . PHP_EOL;
    echo "📧 Expéditeur: " . $mrClank->name . PHP_EOL;
    echo "📥 Destinataire: " . $admin->name . PHP_EOL;
} catch (Exception $e) {
    echo "❌ Erreur lors de la création de l'alerte: " . $e->getMessage() . PHP_EOL;
}

// Vérifier les alertes récentes
echo PHP_EOL . "🔍 VÉRIFICATION DES ALERTES" . PHP_EOL;
$alerts = \App\Models\Message::where('type', 'stock_alert')
    ->where('created_at', '>=', now()->subMinutes(5))
    ->orderBy('created_at', 'desc')
    ->get();

if ($alerts->count() > 0) {
    echo "✅ " . $alerts->count() . " alerte(s) récente(s):" . PHP_EOL;
    foreach ($alerts as $alert) {
        echo "  - " . $alert->subject . PHP_EOL;
        echo "    Priorité: " . $alert->priority . PHP_EOL;
        echo "    Créée à: " . $alert->created_at->format('d/m/Y H:i:s') . PHP_EOL;
        echo "    Expéditeur: " . ($alert->sender ? $alert->sender->name : 'Système') . PHP_EOL . PHP_EOL;
    }
} else {
    echo "⚠️ Aucune alerte trouvée dans les 5 dernières minutes" . PHP_EOL;
}

echo "🎯 SIMULATION DE RUPTURE TERMINÉE!" . PHP_EOL;
echo "✨ Consultez maintenant la section 'Gestion de Stock' dans l'admin !" . PHP_EOL;
echo "🌐 URLs à vérifier :" . PHP_EOL;
echo "   - Vue d'ensemble : http://localhost:8000/admin/stock" . PHP_EOL;
echo "   - Alertes : http://localhost:8000/admin/stock/alerts" . PHP_EOL;
echo "   - Messages : http://localhost:8000/admin/messages" . PHP_EOL;
