<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Newsletter;

echo "=== Statistiques des newsletters ===\n\n";

$total = Newsletter::count();
$sent = Newsletter::where('status', 'sent')->count();
$scheduled = Newsletter::where('status', 'scheduled')->count();
$draft = Newsletter::where('status', 'draft')->count();

echo "📊 Total newsletters : {$total}\n";
echo "✅ Envoyées : {$sent}\n";
echo "📅 Programmées : {$scheduled}\n"; 
echo "📝 Brouillons : {$draft}\n\n";

// Vérifier les catégories
echo "📋 Répartition par catégorie :\n";
$categories = Newsletter::whereNotNull('metadata')->get()->pluck('metadata')->map(function($meta) {
    return $meta['category'] ?? 'unknown';
})->countBy();

foreach ($categories as $category => $count) {
    echo "- {$category} : {$count} newsletters\n";
}

echo "\n✅ Système de newsletters configuré avec fonction de renvoi !\n";
