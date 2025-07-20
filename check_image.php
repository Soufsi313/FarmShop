<?php

require_once 'vendor/autoload.php';

use App\Models\BlogPost;
use Illuminate\Support\Facades\Storage;

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Rechercher l'article
$post = BlogPost::where('title', 'LIKE', '%intelligence cachée des plantes%')->first();

if ($post) {
    echo "Article trouvé: " . $post->title . PHP_EOL;
    echo "Featured image: " . ($post->featured_image ?? 'NULL') . PHP_EOL;
    
    if ($post->featured_image) {
        $fullPath = storage_path('app/public/' . $post->featured_image);
        echo "Chemin complet: " . $fullPath . PHP_EOL;
        echo "Fichier existe: " . (file_exists($fullPath) ? 'OUI' : 'NON') . PHP_EOL;
        echo "URL publique: " . asset('storage/' . $post->featured_image) . PHP_EOL;
        
        // Vérifier les permissions du dossier
        $dir = dirname($fullPath);
        echo "Dossier existe: " . (is_dir($dir) ? 'OUI' : 'NON') . PHP_EOL;
        echo "Dossier lisible: " . (is_readable($dir) ? 'OUI' : 'NON') . PHP_EOL;
    }
} else {
    echo "Article non trouvé" . PHP_EOL;
    
    // Lister quelques articles pour voir
    $posts = BlogPost::select('title')->take(5)->get();
    echo "Articles disponibles:" . PHP_EOL;
    foreach ($posts as $p) {
        echo "- " . $p->title . PHP_EOL;
    }
}
