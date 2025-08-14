<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;

// Bootstrap de l'application Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\OrderLocation;

echo "🧹 Nettoyage des locations de test existantes...\n";

$deleted = OrderLocation::where('order_number', 'LIKE', 'LOC-TEST-%')->delete();

echo "✅ {$deleted} locations de test supprimées\n";
