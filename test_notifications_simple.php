<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 DIAGNOSTIC SIMPLIFIÉ LOC-TEST-001\n";
echo "====================================\n\n";

// Récupérer la location LOC-TEST-001
$orderLocation = DB::table('order_locations')
    ->where('order_number', 'LOC-TEST-001-20250805')
    ->first();

if (!$orderLocation) {
    echo "❌ Location LOC-TEST-001-20250805 non trouvée\n";
    exit(1);
}

echo "📋 STATUT ACTUEL :\n";
echo "   Numéro : {$orderLocation->order_number}\n";
echo "   Statut : {$orderLocation->status}\n";
echo "   User ID : {$orderLocation->user_id}\n\n";

// Vérifier les messages Mr Clank
echo "📨 MESSAGES MR CLANK :\n";
$messages = DB::table('messages')
    ->where('user_id', $orderLocation->user_id)
    ->where('subject', 'like', '%' . $orderLocation->order_number . '%')
    ->orderBy('created_at', 'desc')
    ->get();

if ($messages->count() > 0) {
    foreach ($messages as $message) {
        echo "   ✅ Message trouvé (ID: {$message->id})\n";
        echo "      Sujet : {$message->subject}\n";
        echo "      Créé le : {$message->created_at}\n\n";
    }
} else {
    echo "   ❌ Aucun message Mr Clank trouvé\n\n";
}

// Test manuel d'envoi
echo "🧪 TESTS MANUELS :\n";

echo "1. Test création message Mr Clank :\n";
try {
    $messageId = DB::table('messages')->insertGetId([
        'user_id' => $orderLocation->user_id,
        'sender_id' => 1,
        'type' => 'system',
        'subject' => "🤖 TEST - Location #{$orderLocation->order_number} diagnostic",
        'content' => "Test de diagnostic des notifications - " . now(),
        'status' => 'unread',
        'priority' => 'normal',
        'is_important' => true,
        'created_at' => now(),
        'updated_at' => now()
    ]);
    
    echo "   ✅ Message test créé (ID: {$messageId})\n";
} catch (\Exception $e) {
    echo "   ❌ Erreur création message : " . $e->getMessage() . "\n";
}

echo "\n2. Test envoi email d'inspection :\n";
try {
    $orderLocationModel = \App\Models\OrderLocation::find($orderLocation->id);
    
    Mail::to($orderLocationModel->user->email)->send(new \App\Mail\RentalOrderInspection($orderLocationModel));
    echo "   ✅ Email d'inspection envoyé à : {$orderLocationModel->user->email}\n";
    
} catch (\Exception $e) {
    echo "   ❌ Erreur envoi email : " . $e->getMessage() . "\n";
}

echo "\n3. Simulation complète méthode controller :\n";
try {
    $orderLocationModel = \App\Models\OrderLocation::find($orderLocation->id);
    $damageFeesTotal = 0;
    $refundAmount = $orderLocation->deposit_amount;
    
    // Message Mr Clank
    $message = "🤖 **Mr Clank - Message Automatique**\n\n";
    $message .= "Bonjour {$orderLocationModel->user->name},\n\n";
    $message .= "Votre location #{$orderLocationModel->order_number} a été finalisée avec succès !\n\n";
    $message .= "📋 **Détails de l'inspection :**\n";
    $message .= "- Date de retour : " . now()->format('d/m/Y à H:i') . "\n";
    $message .= "- Statut : Inspection terminée\n";
    
    $message .= "\n💰 **Détails de la caution :**\n";
    $message .= "- Caution versée : " . number_format($orderLocationModel->deposit_amount, 2) . "€\n";
    $message .= "- **Caution intégralement remboursée : " . number_format($refundAmount, 2) . "€**\n";
    $message .= "\n✅ Aucun dommage constaté !\n";
    
    $message .= "\n🏦 Le remboursement sera effectué sous 3-5 jours ouvrés sur votre moyen de paiement original.\n";
    $message .= "\nMerci de votre confiance !\n\n";
    $message .= "---\n";
    $message .= "🤖 Message automatique généré par Mr Clank\n";
    $message .= "Système de gestion FarmShop";

    // Créer le message
    $messageId = DB::table('messages')->insertGetId([
        'user_id' => $orderLocationModel->user_id,
        'sender_id' => 1,
        'type' => 'system',
        'subject' => "🤖 Location #{$orderLocationModel->order_number} finalisée - Caution remboursée",
        'content' => $message,
        'status' => 'unread',
        'priority' => 'high',
        'is_important' => true,
        'created_at' => now(),
        'updated_at' => now()
    ]);
    
    echo "   ✅ Message Mr Clank créé (ID: {$messageId})\n";
    
    // Envoyer email Mr Clank
    Mail::send('emails.mr-clank-rental-finalized', [
        'orderLocation' => $orderLocationModel,
        'message' => $message,
        'depositAmount' => $orderLocationModel->deposit_amount,
        'damageFeesTotal' => $damageFeesTotal,
        'refundAmount' => $refundAmount
    ], function ($mail) use ($orderLocationModel) {
        $mail->to($orderLocationModel->user->email, $orderLocationModel->user->name)
             ->subject("🤖 Mr Clank - Location #{$orderLocationModel->order_number} finalisée");
    });
    
    echo "   ✅ Email Mr Clank envoyé\n";
    
} catch (\Exception $e) {
    echo "   ❌ Erreur simulation : " . $e->getMessage() . "\n";
}

echo "\n✅ Tests terminés ! Vérifiez maintenant :\n";
echo "1. Votre boîte de réception email : s.mef2703@gmail.com\n";
echo "2. Votre boîte de messages sur l'interface admin\n";
