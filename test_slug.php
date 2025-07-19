<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\BlogPost;

// Test de la génération de slug unique
echo "=== Test de génération de slug unique ===\n";

// Vérifier les slugs existants
$existingSlugs = BlogPost::pluck('slug')->toArray();
echo "Slugs existants: " . implode(', ', $existingSlugs) . "\n";

// Créer un article test avec le slug 'test' s'il n'existe pas
if (!in_array('test', $existingSlugs)) {
    echo "Création d'un article avec le slug 'test'...\n";
    $testPost = new BlogPost();
    $testPost->title = 'Article Test';
    $testPost->slug = 'test';
    $testPost->content = 'Contenu de test';
    $testPost->excerpt = 'Excerpt de test';
    $testPost->blog_category_id = 1; // Assurez-vous qu'une catégorie avec ID 1 existe
    $testPost->author_id = 1; // Assurez-vous qu'un utilisateur avec ID 1 existe
    $testPost->save();
    echo "Article créé avec le slug: {$testPost->slug}\n";
}

// Maintenant testons la méthode generateUniqueSlug
function generateUniqueSlug($title, $id = null)
{
    $slug = Str::slug($title);
    $originalSlug = $slug;
    $counter = 1;

    while (true) {
        $query = BlogPost::where('slug', $slug);
        
        if ($id) {
            $query->where('id', '!=', $id);
        }
        
        if (!$query->exists()) {
            break;
        }
        
        $slug = $originalSlug . '-' . $counter;
        $counter++;
    }

    return $slug;
}

// Test avec le titre "test"
$newSlug = generateUniqueSlug('test');
echo "Nouveau slug généré pour 'test': $newSlug\n";

// Test avec un autre titre
$newSlug2 = generateUniqueSlug('test article');
echo "Nouveau slug généré pour 'test article': $newSlug2\n";

echo "=== Test terminé ===\n";
