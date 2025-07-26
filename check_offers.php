<?php

require __DIR__ . '/vendor/autoload.php';

use App\Models\SpecialOffer;

// Initialiser Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== OFFRES SPÉCIALES ===\n\n";

$offers = SpecialOffer::all();

foreach ($offers as $offer) {
    echo "ID: {$offer->id}\n";
    echo "Titre: " . ($offer->title ?? 'NON DÉFINI') . "\n";
    echo "Description: {$offer->description}\n";
    echo "Réduction: {$offer->discount_percentage}%\n";
    echo "Quantité min: {$offer->minimum_quantity}\n";
    echo "Actif: " . ($offer->is_active ? 'Oui' : 'Non') . "\n";
    echo "---\n";
}
