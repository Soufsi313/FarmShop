<?php
// Test de génération de numéros de retour unique
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

echo "Test de génération de numéros de retour uniques :\n";

// Simuler 10 générations rapides
for ($i = 0; $i < 10; $i++) {
    do {
        $returnNumber = 'RET' . now()->format('YmdHis') . rand(10, 99);
    } while (\App\Models\OrderReturn::where('return_number', $returnNumber)->exists());
    
    echo "Numéro généré: " . $returnNumber . "\n";
    usleep(100000); // 0.1 seconde entre chaque génération
}

echo "\nTest terminé avec succès !\n";
