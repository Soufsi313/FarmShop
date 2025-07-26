<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== STATUT ACTUEL DES CATÉGORIES ===\n\n";

$categories = \App\Models\Category::select('id', 'name', 'food_type', 'is_returnable')
    ->orderBy('food_type')
    ->orderBy('name')
    ->get();

echo "Catégories ALIMENTAIRES :\n";
echo "ID | Nom                | Type         | Retournable\n";
echo "---|--------------------|--------------|-----------\n";

foreach ($categories->where('food_type', 'alimentaire') as $cat) {
    echo sprintf("%2d | %-18s | %-12s | %s\n", 
        $cat->id, 
        $cat->name, 
        $cat->food_type, 
        $cat->is_returnable ? '✅ OUI' : '❌ NON'
    );
}

echo "\nCatégories NON-ALIMENTAIRES :\n";
echo "ID | Nom                | Type         | Retournable\n";
echo "---|--------------------|--------------|-----------\n";

foreach ($categories->where('food_type', 'non_alimentaire') as $cat) {
    echo sprintf("%2d | %-18s | %-12s | %s\n", 
        $cat->id, 
        $cat->name, 
        $cat->food_type, 
        $cat->is_returnable ? '✅ OUI' : '❌ NON'
    );
}

// Compter les totaux
$totalCategories = $categories->count();
$returnableCategories = $categories->where('is_returnable', true)->count();
$alimentaireCount = $categories->where('food_type', 'alimentaire')->count();
$nonAlimentaireCount = $categories->where('food_type', 'non_alimentaire')->count();

echo "\n=== RÉSUMÉ ===\n";
echo "Total catégories : {$totalCategories}\n";
echo "Catégories retournables : {$returnableCategories}\n";
echo "Catégories alimentaires : {$alimentaireCount}\n";
echo "Catégories non-alimentaires : {$nonAlimentaireCount}\n";

if ($returnableCategories == 0) {
    echo "\n⚠️  PROBLÈME : Aucune catégorie n'est marquée comme retournable !\n";
    echo "   Pour tester les retours, vous devez rendre certaines catégories retournables.\n";
} else {
    echo "\n✅ {$returnableCategories} catégorie(s) sont retournables.\n";
}
