<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Correction du status de la newsletter...\n";
$newsletter = App\Models\Newsletter::orderBy('id', 'desc')->first();
if ($newsletter) {
    $newsletter->update([
        'status' => 'draft',
        'scheduled_at' => null
    ]);
    echo "Newsletter ID {$newsletter->id} remise en status draft\n";
    echo "Titre: {$newsletter->title}\n";
    echo "Peut être envoyée: " . ($newsletter->canBeSent() ? 'OUI' : 'NON') . "\n";
} else {
    echo "Aucune newsletter trouvée\n";
}
