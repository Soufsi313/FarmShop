<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Mapping des traductions pour les catégories
$categoryTranslations = [
    'Protections' => [
        'en' => 'Protections',
        'nl' => 'Bescherming',
        'description' => [
            'en' => 'Protection Products Category',
            'nl' => 'Categorie Beschermingsproducten'
        ]
    ],
    'Fruits' => [
        'en' => 'Fruits',
        'nl' => 'Fruit',
        'description' => [
            'en' => 'Fresh Fruits Category',
            'nl' => 'Categorie Vers Fruit'
        ]
    ],
    'Légumes' => [
        'en' => 'Vegetables',
        'nl' => 'Groenten',
        'description' => [
            'en' => 'Fresh Vegetables Category',
            'nl' => 'Categorie Verse Groenten'
        ]
    ],
    'Céréales' => [
        'en' => 'Cereals',
        'nl' => 'Granen',
        'description' => [
            'en' => 'Cereals Category',
            'nl' => 'Categorie Granen'
        ]
    ],
    'Féculents' => [
        'en' => 'Starches',
        'nl' => 'Zetmeel',
        'description' => [
            'en' => 'Starchy Foods Category',
            'nl' => 'Categorie Zetmeelrijke Voedingsmiddelen'
        ]
    ],
    'Produits Laitiers' => [
        'en' => 'Dairy Products',
        'nl' => 'Zuivelproducten',
        'description' => [
            'en' => 'Dairy Products Category',
            'nl' => 'Categorie Zuivelproducten'
        ]
    ],
    'Outils Agricoles' => [
        'en' => 'Agricultural Tools',
        'nl' => 'Landbouwgereedschap',
        'description' => [
            'en' => 'Agricultural Tools Category',
            'nl' => 'Categorie Landbouwgereedschap'
        ]
    ],
    'Machines' => [
        'en' => 'Machines',
        'nl' => 'Machines',
        'description' => [
            'en' => 'Agricultural Machines Category',
            'nl' => 'Categorie Landbouwmachines'
        ]
    ],
    'Équipement' => [
        'en' => 'Equipment',
        'nl' => 'Uitrusting',
        'description' => [
            'en' => 'Agricultural Equipment Category',
            'nl' => 'Categorie Landbouwuitrusting'
        ]
    ],
    'Semences' => [
        'en' => 'Seeds',
        'nl' => 'Zaden',
        'description' => [
            'en' => 'Seeds Category',
            'nl' => 'Categorie Zaden'
        ]
    ],
    'Engrais' => [
        'en' => 'Fertilizers',
        'nl' => 'Meststoffen',
        'description' => [
            'en' => 'Fertilizers Category',
            'nl' => 'Categorie Meststoffen'
        ]
    ],
    'Irrigation' => [
        'en' => 'Irrigation',
        'nl' => 'Irrigatie',
        'description' => [
            'en' => 'Irrigation Category',
            'nl' => 'Categorie Irrigatie'
        ]
    ]
];

echo "Updating category translations...\n\n";

$categories = \App\Models\Category::all();

foreach ($categories as $category) {
    $frenchName = $category->getTranslation('name', 'fr');
    
    if (isset($categoryTranslations[$frenchName])) {
        $translations = $categoryTranslations[$frenchName];
        
        echo "Updating category: $frenchName\n";
        
        // Update name translations
        $category->setTranslation('name', 'en', $translations['en']);
        $category->setTranslation('name', 'nl', $translations['nl']);
        
        // Update description translations  
        $frenchDesc = $category->getTranslation('description', 'fr');
        $category->setTranslation('description', 'en', $translations['description']['en']);
        $category->setTranslation('description', 'nl', $translations['description']['nl']);
        
        $category->save();
        
        echo "  FR: $frenchName -> EN: {$translations['en']} -> NL: {$translations['nl']}\n";
        echo "  Descriptions updated\n\n";
    } else {
        echo "No translation found for: $frenchName\n\n";
    }
}

echo "Translation update complete!\n";
