<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Message;

echo "Messages actuels:\n";

$messages = Message::all(['id', 'user_id', 'sender_id', 'subject', 'type']);

foreach($messages as $m) {
    echo "ID: {$m->id}, User: {$m->user_id}, Sender: {$m->sender_id}, Type: {$m->type}, Subject: {$m->subject}\n";
}

// Vérifions aussi les métadonnées pour voir les informations des expéditeurs
echo "\n--- Métadonnées ---\n";
$messagesWithMeta = Message::all();
foreach($messagesWithMeta as $m) {
    echo "ID: {$m->id}, Metadata: " . json_encode($m->metadata) . "\n";
}
