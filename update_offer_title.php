<?php

require __DIR__ . '/vendor/autoload.php';

use App\Models\SpecialOffer;

// Initialiser Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== MISE À JOUR TITRE OFFRE ===\n\n";

$offer = SpecialOffer::find(1);
if ($offer) {
    $offer->title = "Opération Pommes Vertes";
    $offer->save();
    
    echo "✅ Titre mis à jour: {$offer->title}\n";
    echo "Description: {$offer->description}\n";
} else {
    echo "❌ Offre non trouvée\n";
}
