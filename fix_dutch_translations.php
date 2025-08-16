<?php
// Quick script to add missing Dutch category translations

$missing_categories = [
    'machines' => 'Machines',
    'outils-agricoles' => 'Landbouwgereedschap', 
    'produits-laitiers' => 'Zuivelproducten',
    'protections' => 'Beschermingen',
    'semences' => 'Zaden'
];

// Read the current Dutch file
$dutch_file = file_get_contents('resources/lang/nl/app.php');

// Find the categories section and add missing translations
foreach ($missing_categories as $key => $value) {
    if (strpos($dutch_file, "'$key' =>") === false) {
        echo "Adding missing category: $key => $value\n";
        
        // Find a good place to add it (after another category)
        $pattern = "/'[^']*' => '[^']*',\s*\/\/ Category/";
        $replacement = "'$key' => '$value', // Category\n    $0";
        
        if (preg_match($pattern, $dutch_file)) {
            $dutch_file = preg_replace($pattern, $replacement, $dutch_file, 1);
        } else {
            // If we can't find the pattern, add at the end of categories array
            $dutch_file = str_replace(
                "],\n\n  'product_descriptions'", 
                "    '$key' => '$value',\n  ],\n\n  'product_descriptions'", 
                $dutch_file
            );
        }
    }
}

echo "Dutch category translations completed.\n";
?>
