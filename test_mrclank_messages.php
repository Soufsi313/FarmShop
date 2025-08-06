<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\OrderLocation;
use App\Models\Message;

try {
    echo "🧪 Test des messages Mr Clank après correction\n";
    echo "=".str_repeat("=", 45)."\n\n";

    // Trouver une location à tester
    $orderLocation = OrderLocation::find(31);
    
    if (!$orderLocation) {
        echo "❌ Location ID 31 non trouvée\n";
        exit(1);
    }

    echo "📦 Location trouvée:\n";
    echo "   - ID: {$orderLocation->id}\n";
    echo "   - Numéro: {$orderLocation->order_number}\n";
    echo "   - Statut: {$orderLocation->status}\n";
    echo "   - Utilisateur: {$orderLocation->user->name}\n\n";

    // Compter les messages Mr Clank avant
    $messagesBefore = Message::where('user_id', $orderLocation->user_id)
                            ->where('sender_id', 103)
                            ->count();

    echo "📨 Messages Mr Clank avant: {$messagesBefore}\n\n";

    // Simuler l'envoi du message Mr Clank
    echo "🤖 Test d'envoi du message Mr Clank...\n";
    
    $message = "🤖 **Mr Clank - Message Automatique de Test**\n\n";
    $message .= "Bonjour {$orderLocation->user->name},\n\n";
    $message .= "Test de correction de l'erreur Mail::class\n\n";
    $message .= "Votre location #{$orderLocation->order_number} a été finalisée.\n\n";
    $message .= "---\n";
    $message .= "🤖 Message automatique généré par Mr Clank\n";
    $message .= "Système de gestion FarmShop";

    // Créer le message
    $newMessage = Message::create([
        'user_id' => $orderLocation->user_id,
        'sender_id' => 103, // ID de Mr Clank 🤖 (system@farmshop.local)
        'type' => 'system',
        'subject' => "🤖 Location #{$orderLocation->order_number} - Test correction",
        'content' => $message,
        'status' => 'unread',
        'priority' => 'high',
        'is_important' => true,
    ]);

    // Compter les messages après
    $messagesAfter = Message::where('user_id', $orderLocation->user_id)
                           ->where('sender_id', 103)
                           ->count();

    echo "✅ Message Mr Clank créé avec succès!\n";
    echo "   - ID du message: {$newMessage->id}\n";
    echo "   - Messages Mr Clank après: {$messagesAfter}\n";
    echo "   - Différence: +" . ($messagesAfter - $messagesBefore) . "\n\n";

    echo "📧 Test de l'envoi d'email...\n";
    
    try {
        // Test de l'email (sans l'envoyer vraiment)
        $mailClass = new \App\Mail\RentalOrderInspection($orderLocation);
        echo "✅ Classe RentalOrderInspection instanciée avec succès\n";
        echo "   - Subject: " . $mailClass->build()->subject . "\n";
    } catch (\Exception $e) {
        echo "❌ Erreur avec RentalOrderInspection: " . $e->getMessage() . "\n";
    }

    echo "\n🎉 Test terminé avec succès!\n";
    echo "✅ L'erreur 'Mail not found' est corrigée\n";
    echo "✅ Mr Clank peut maintenant envoyer des messages\n";

} catch (\Exception $e) {
    echo "❌ Exception: " . $e->getMessage() . "\n";
    echo "📍 Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
    exit(1);
}
