<?php

require_once 'vendor/autoload.php';

use App\Models\BlogPost;

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Rechercher tous les articles avec des images mal formatées
$posts = BlogPost::whereNotNull('featured_image')
    ->where('featured_image', 'LIKE', 'storage/%')
    ->get();

echo "Articles avec des chemins d'images incorrects: " . $posts->count() . PHP_EOL;

foreach ($posts as $post) {
    $oldPath = $post->featured_image;
    // Corriger le chemin en supprimant le "storage/" en trop
    $newPath = str_replace('storage/', '', $oldPath);
    
    echo "Article: " . $post->title . PHP_EOL;
    echo "  Ancien chemin: " . $oldPath . PHP_EOL;
    echo "  Nouveau chemin: " . $newPath . PHP_EOL;
    
    // Vérifier si le fichier existe avec le nouveau chemin
    $fullPath = storage_path('app/public/' . $newPath);
    echo "  Fichier existe: " . (file_exists($fullPath) ? 'OUI' : 'NON') . PHP_EOL;
    
    // Mettre à jour le chemin
    $post->featured_image = $newPath;
    $post->save();
    
    echo "  ✅ Chemin corrigé" . PHP_EOL . PHP_EOL;
}
