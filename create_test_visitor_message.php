<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use App\Models\Message;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    echo "=== Création d'un message de visiteur pour test ===\n\n";
    
    $message = Message::create([
        'user_id' => 1, // Admin user
        'sender_id' => 1,
        'type' => 'visitor',
        'subject' => 'Test de réponse admin',
        'content' => 'Ceci est un message de test pour vérifier le système de réponse de l\'admin.',
        'status' => 'unread',
        'priority' => 'normal',
        'metadata' => [
            'sender_email' => 'test@example.com',
            'sender_name' => 'Visiteur Test',
            'contact_reason' => 'support_technique',
            'ip' => '127.0.0.1'
        ]
    ]);
    
    echo "✅ Message de visiteur créé avec succès (ID: {$message->id})\n";
    echo "   Email: {$message->metadata['sender_email']}\n";
    echo "   Nom: {$message->metadata['sender_name']}\n";
    echo "   Sujet: {$message->subject}\n\n";
    
    echo "Vous pouvez maintenant tester la réponse d'admin sur : http://localhost:8000/admin/messages/{$message->id}\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "   Ligne: " . $e->getLine() . "\n";
    echo "   Fichier: " . $e->getFile() . "\n";
}
