<?php
// Test direct de la création de retour pour la commande FS202507015879
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TEST DIRECT DE CRÉATION DE RETOUR ===\n\n";

// Trouver la commande
$order = \App\Models\Order::where('order_number', 'FS202507015879')->first();

if (!$order) {
    echo "Commande non trouvée.\n";
    exit;
}

echo "Commande trouvée: {$order->order_number} (ID: {$order->id})\n";

// Simuler les données de retour
$returnData = [
    'return_items' => [
        [
            'item_id' => $order->items->first()->id,
            'quantity' => 1
        ]
    ],
    'return_reason' => 'Test de retour',
    'admin_notes' => 'Test automatique'
];

echo "Données de retour préparées.\n";

// Créer le retour directement
try {
    $returnNumber = '';
    $totalRefundAmount = 0;
    
    \Illuminate\Support\Facades\DB::transaction(function() use ($returnData, $order, &$returnNumber, &$totalRefundAmount) {
        // Générer un numéro de retour unique
        $maxAttempts = 10;
        $attempt = 0;
        do {
            $attempt++;
            $microtime = str_replace('.', '', microtime(true));
            $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $returnNumber = 'RET' . substr($microtime, 0, 14) . $random;
        } while (\App\Models\OrderReturn::where('return_number', $returnNumber)->exists() && $attempt < $maxAttempts);
        
        if ($attempt >= $maxAttempts) {
            throw new \Exception('Impossible de générer un numéro de retour unique.');
        }
        
        echo "Numéro de retour généré: {$returnNumber}\n";

        foreach ($returnData['return_items'] as $returnItemData) {
            $orderItem = $order->items()->find($returnItemData['item_id']);
            
            if (!$orderItem) {
                echo "Article non trouvé: {$returnItemData['item_id']}\n";
                continue;
            }
            
            echo "Traitement de l'article: {$orderItem->product->name}\n";
            
            // Vérifier que le produit n'est pas périssable
            $isPerishable = $orderItem->product ? $orderItem->product->isPerishable() : $orderItem->is_perishable;
            if ($isPerishable) {
                echo "  - Article périssable, ignoré.\n";
                continue;
            }

            // Vérifier la quantité
            $returnQuantity = min($returnItemData['quantity'], $orderItem->quantity);
            $refundAmount = $returnQuantity * $orderItem->unit_price;
            $totalRefundAmount += $refundAmount;
            
            echo "  - Quantité retournée: {$returnQuantity}\n";
            echo "  - Montant remboursé: {$refundAmount}€\n";

            // Créer l'enregistrement de retour
            $returnRecord = \App\Models\OrderReturn::create([
                'order_id' => $order->id,
                'order_item_id' => $orderItem->id,
                'user_id' => $order->user_id,
                'return_number' => $returnNumber,
                'quantity_returned' => $returnQuantity,
                'refund_amount' => $refundAmount,
                'return_reason' => $returnData['return_reason'],
                'admin_notes' => $returnData['admin_notes'],
                'status' => 'approved',
                'refund_status' => 'pending',
                'requested_at' => now(),
                'approved_at' => now(),
                'is_within_return_period' => true,
                'return_deadline' => $order->return_deadline ?? \Carbon\Carbon::parse($order->delivered_at)->addDays(14),
            ]);
            
            echo "  - Enregistrement créé: ID {$returnRecord->id}\n";

            // Remettre en stock
            $orderItem->product->increment('quantity', $returnQuantity);
            echo "  - Stock remis à jour\n";
        }

        // Changer le statut de la commande
        $order->update(['status' => \App\Models\Order::STATUS_RETURNED]);
        echo "Statut de la commande mis à jour: returned\n";
        
        echo "Montant total remboursé: {$totalRefundAmount}€\n";
    });
    
    echo "\n✅ RETOUR CRÉÉ AVEC SUCCÈS !\n";
    echo "Numéro de retour: {$returnNumber}\n";
    
} catch (\Exception $e) {
    echo "\n❌ ERREUR: " . $e->getMessage() . "\n";
}

echo "\nTest terminé.\n";
