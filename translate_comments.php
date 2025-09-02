<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

// Récupération des commentaires
$comments = \App\Models\BlogComment::all(['id', 'content', 'guest_name']);

echo "=== COMMENTAIRES À TRADUIRE ===\n";
echo "Total: " . $comments->count() . " commentaires\n\n";

foreach ($comments as $index => $comment) {
    echo "ID: {$comment->id}\n";
    echo "Auteur: " . ($comment->guest_name ?? 'Anonyme') . "\n";
    echo "Contenu: " . $comment->content . "\n";
    echo "---\n";
    
    if ($index >= 9) { // Afficher seulement les 10 premiers pour commencer
        echo "... et " . ($comments->count() - 10) . " autres commentaires\n";
        break;
    }
}
