<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 TEST DE FINALISATION D'INSPECTION\n";
echo "====================================\n\n";

// Récupérer une location de test en cours d'inspection
$orderLocation = DB::table('order_locations')
    ->where('order_number', 'like', 'LOC-TEST-%-20250805')
    ->where('status', 'completed')
    ->first();

if (!$orderLocation) {
    echo "❌ Aucune location de test trouvée avec statut 'completed'\n";
    echo "Recherche de locations disponibles...\n";
    
    $orders = DB::table('order_locations')
        ->where('order_number', 'like', 'LOC-TEST-%')
        ->orderBy('created_at', 'desc')
        ->get(['id', 'order_number', 'status']);
    
    foreach ($orders as $order) {
        echo "- {$order->order_number} : {$order->status}\n";
    }
    exit(1);
}

echo "📋 Location trouvée : {$orderLocation->order_number}\n";
echo "📊 Statut actuel : {$orderLocation->status}\n\n";

// D'abord, marquer comme retournée (closed) puis en inspection
if ($orderLocation->status === 'completed') {
    echo "1. 📦 Changement de statut : completed → closed\n";
    DB::table('order_locations')
        ->where('id', $orderLocation->id)
        ->update([
            'status' => 'closed',
            'actual_return_date' => now(),
            'updated_at' => now()
        ]);
    
    echo "2. 🔍 Changement de statut : closed → inspecting\n";
    DB::table('order_locations')
        ->where('id', $orderLocation->id)
        ->update([
            'status' => 'inspecting',
            'inspection_status' => 'in_progress',
            'inspection_started_at' => now(),
            'updated_at' => now()
        ]);
}

// Maintenant simuler la finalisation de l'inspection
echo "3. ✅ Simulation de la finalisation d'inspection...\n";

// Récupérer les items de la location
$items = DB::table('order_item_locations')
    ->where('order_location_id', $orderLocation->id)
    ->get();

echo "   Items trouvés : " . $items->count() . "\n";

$totalDamageCosts = 0;

// Simuler l'inspection de chaque item (condition: good pour le test)
foreach ($items as $item) {
    $damageCost = 0; // Pas de dégâts pour le test
    $totalDamageCosts += $damageCost;
    
    DB::table('order_item_locations')
        ->where('id', $item->id)
        ->update([
            'condition_at_return' => 'good',
            'item_inspection_notes' => 'Test d\'inspection automatique - Aucun dommage constaté',
            'item_damage_cost' => $damageCost,
            'updated_at' => now()
        ]);
    
    echo "   ✅ Item {$item->product_name} : good (0€ dégâts)\n";
}

// Calculer le remboursement
$depositRefund = max(0, $orderLocation->deposit_amount - $totalDamageCosts);

echo "\n4. 💰 Calculs financiers :\n";
echo "   Caution initiale : " . number_format($orderLocation->deposit_amount, 2) . "€\n";
echo "   Frais de dommages : " . number_format($totalDamageCosts, 2) . "€\n";
echo "   Remboursement : " . number_format($depositRefund, 2) . "€\n\n";

// Finaliser la location avec le nouveau système
echo "5. 🏁 Finalisation de l'inspection...\n";

try {
    DB::table('order_locations')
        ->where('id', $orderLocation->id)
        ->update([
            'status' => 'finished',
            'inspection_status' => 'completed',
            'inspection_finished_at' => now(),
            'penalty_amount' => $totalDamageCosts,
            'deposit_refund' => $depositRefund,
            'inspection_notes' => 'Test d\'inspection automatique via script',
            'updated_at' => now()
        ]);
    
    echo "   ✅ Statut mis à jour : finished\n";
    
    // Maintenant déclencher manuellement les notifications comme le ferait le controller
    $orderLocationModel = \App\Models\OrderLocation::find($orderLocation->id);
    
    echo "6. 📧 Envoi des notifications...\n";
    
    // Simuler la méthode sendMrClankMessage du controller
    try {
        $message = "🤖 **Mr Clank - Message Automatique**\n\n";
        $message .= "Bonjour {$orderLocationModel->user->name},\n\n";
        $message .= "Votre location #{$orderLocationModel->order_number} a été finalisée avec succès !\n\n";
        $message .= "📋 **Détails de l'inspection :**\n";
        $message .= "- Date de retour : " . now()->format('d/m/Y à H:i') . "\n";
        $message .= "- Statut : Inspection terminée\n";
        
        $message .= "\n💰 **Détails de la caution :**\n";
        $message .= "- Caution versée : " . number_format($orderLocationModel->deposit_amount, 2) . "€\n";
        
        if ($totalDamageCosts > 0) {
            $message .= "- Frais de dommages : " . number_format($totalDamageCosts, 2) . "€\n";
            $message .= "- **Montant à vous rembourser : " . number_format($depositRefund, 2) . "€**\n";
            $message .= "\n⚠️ Des frais ont été appliqués suite à l'inspection.\n";
        } else {
            $message .= "- **Caution intégralement remboursée : " . number_format($depositRefund, 2) . "€**\n";
            $message .= "\n✅ Aucun dommage constaté !\n";
        }
        
        $message .= "\n🏦 Le remboursement sera effectué sous 3-5 jours ouvrés sur votre moyen de paiement original.\n";
        $message .= "\nMerci de votre confiance !\n\n";
        $message .= "---\n";
        $message .= "🤖 Message automatique généré par Mr Clank\n";
        $message .= "Système de gestion FarmShop";

        // Envoyer le message dans la boîte de réception utilisateur
        $messageId = DB::table('messages')->insertGetId([
            'sender_id' => 1, // ID de Mr Clank
            'recipient_id' => $orderLocationModel->user_id,
            'subject' => "🤖 Location #{$orderLocationModel->order_number} finalisée - Caution remboursée",
            'message' => $message,
            'is_system' => true,
            'sent_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        echo "   ✅ Message Mr Clank envoyé (ID: {$messageId})\n";
        
        // Envoyer l'email d'inspection
        Mail::to($orderLocationModel->user->email)->send(new \App\Mail\RentalOrderInspection($orderLocationModel));
        echo "   ✅ Email d'inspection envoyé\n";
        
        // Envoyer l'email Mr Clank
        Mail::send('emails.mr-clank-rental-finalized', [
            'orderLocation' => $orderLocationModel,
            'message' => $message,
            'depositAmount' => $orderLocationModel->deposit_amount,
            'damageFeesTotal' => $totalDamageCosts,
            'refundAmount' => $depositRefund
        ], function ($mail) use ($orderLocationModel) {
            $mail->to($orderLocationModel->user->email, $orderLocationModel->user->name)
                 ->subject("🤖 Mr Clank - Location #{$orderLocationModel->order_number} finalisée");
        });
        
        echo "   ✅ Email Mr Clank envoyé\n";
        
    } catch (\Exception $e) {
        echo "   ❌ Erreur notifications : " . $e->getMessage() . "\n";
    }
    
    echo "\n🎉 Test terminé avec succès !\n";
    echo "Vérifiez votre boîte de réception email et messages sur l'interface.\n";
    
} catch (\Exception $e) {
    echo "❌ Erreur lors de la finalisation : " . $e->getMessage() . "\n";
}
