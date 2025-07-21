<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use App\Models\Message;
use App\Mail\VisitorMessageReply;
use Illuminate\Support\Facades\Mail;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    echo "=== Test du système de réponse aux visiteurs ===\n\n";
    
    // Chercher un message de visiteur
    $visitorMessage = Message::where('type', 'visitor')
        ->where('metadata->sender_email', '!=', null)
        ->first();
    
    if (!$visitorMessage) {
        echo "❌ Aucun message de visiteur trouvé pour le test\n";
        exit(1);
    }
    
    echo "✅ Message de visiteur trouvé (ID: {$visitorMessage->id})\n";
    echo "   Email: " . ($visitorMessage->metadata['sender_email'] ?? 'N/A') . "\n";
    echo "   Nom: " . ($visitorMessage->metadata['sender_name'] ?? 'N/A') . "\n";
    echo "   Sujet: {$visitorMessage->subject}\n\n";
    
    // Test de création de l'email
    echo "🔧 Test de création de l'email de réponse...\n";
    $replyContent = "Merci pour votre message. Nous avons bien reçu votre demande et y donnons suite rapidement.";
    $adminName = "Support FarmShop";
    
    try {
        $mail = new VisitorMessageReply($visitorMessage, $replyContent, $adminName);
        echo "✅ Email de réponse créé avec succès\n";
        
        // Test de l'envelope
        $envelope = $mail->envelope();
        echo "✅ Envelope créé avec succès\n";
        echo "   To: " . $envelope->to[0]->address . "\n";
        echo "   Subject: {$envelope->subject}\n";
        if (!empty($envelope->replyTo)) {
            $replyTo = array_keys($envelope->replyTo)[0];
            echo "   ReplyTo: {$replyTo}\n";
        }
        echo "\n";
        
        // Test du contenu
        $content = $mail->content();
        echo "✅ Contenu de l'email créé avec succès\n";
        echo "   View: {$content->view}\n";
        echo "   Variables passées: " . implode(', ', array_keys($content->with)) . "\n\n";
        
    } catch (Exception $e) {
        echo "❌ Erreur lors de la création de l'email: " . $e->getMessage() . "\n";
        echo "   Ligne: " . $e->getLine() . "\n";
        echo "   Fichier: " . $e->getFile() . "\n";
        exit(1);
    }
    
    echo "🎉 Tous les tests sont passés ! Le système de réponse aux visiteurs devrait fonctionner.\n";
    
} catch (Exception $e) {
    echo "❌ Erreur générale: " . $e->getMessage() . "\n";
    echo "   Ligne: " . $e->getLine() . "\n";
    echo "   Fichier: " . $e->getFile() . "\n";
    exit(1);
}
