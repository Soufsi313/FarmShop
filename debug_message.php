<?php

require_once __DIR__ . '/vendor/autoload.php';

// Initialiser Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Message;
use Illuminate\Support\Facades\Log;

echo "=== Debug Message 117 ===\n";

$message = Message::find(117);

if (!$message) {
    echo "❌ Message 117 introuvable\n";
    exit;
}

echo "✅ Message trouvé\n";
echo "ID: " . $message->id . "\n";
echo "Type: " . $message->type . "\n";
echo "Status actuel: " . $message->status . "\n";
echo "Sujet: " . $message->subject . "\n";

if ($message->metadata) {
    echo "Métadonnées:\n";
    if (isset($message->metadata['sender_email'])) {
        echo "  - Email visiteur: " . $message->metadata['sender_email'] . "\n";
    }
    if (isset($message->metadata['sender_name'])) {
        echo "  - Nom visiteur: " . $message->metadata['sender_name'] . "\n";
    }
}

// Test de mise à jour du statut
echo "\n=== Test de mise à jour ===\n";
try {
    $message->update(['status' => 'read']);
    echo "✅ Mise à jour réussie vers 'read'\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

echo "\nStatut final: " . $message->fresh()->status . "\n";
