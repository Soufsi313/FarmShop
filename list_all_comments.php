<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$comments = \App\Models\BlogComment::all(['id', 'content']);

echo "=== TOUS LES COMMENTAIRES ===\n";
foreach ($comments as $comment) {
    echo $comment->id . ": " . $comment->content . "\n";
}
