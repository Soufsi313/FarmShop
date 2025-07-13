<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Contact;
use App\Models\Message;

echo "Migration des contacts existants vers messages...\n";

$contacts = Contact::all();
echo "Contacts trouvés: " . $contacts->count() . "\n";

foreach ($contacts as $contact) {
    $adminMessage = Message::create([
        'user_id' => 1, // Admin user ID
        'sender_id' => $contact->user_id,
        'subject' => $contact->subject,
        'content' => $contact->message,
        'type' => 'contact',
        'status' => 'unread',  // Changé de 'pending' à 'unread'
        'priority' => $contact->priority === 'normal' ? 'normal' : 'normal', // Assurons-nous que c'est une valeur valide
        'metadata' => [
            'contact_reason' => $contact->reason ?? 'question',
            'sender_email' => $contact->email,
            'sender_name' => $contact->name,
            'sender_phone' => $contact->phone ?? null,
            'original_contact_id' => $contact->id,
            'migrated_from_contacts' => true
        ],
        'created_at' => $contact->created_at,
        'updated_at' => $contact->updated_at,
    ]);
    
    echo "Contact ID {$contact->id} migré vers Message ID {$adminMessage->id}\n";
}

echo "Migration terminée. Nombre total de messages: " . Message::count() . "\n";
