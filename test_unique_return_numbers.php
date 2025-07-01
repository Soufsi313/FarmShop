<?php
// Test de la nouvelle génération de numéros de retour
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TEST DE GÉNÉRATION DE NUMÉROS DE RETOUR UNIQUES ===\n\n";

// Simuler la génération de plusieurs numéros
$generatedNumbers = [];

for ($i = 0; $i < 5; $i++) {
    $maxAttempts = 10;
    $attempt = 0;
    do {
        $attempt++;
        $microtime = str_replace('.', '', microtime(true)); // Timestamp avec microsecondes
        $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT); // 4 chiffres random
        $returnNumber = 'RET' . substr($microtime, 0, 14) . $random;
    } while (in_array($returnNumber, $generatedNumbers) && $attempt < $maxAttempts);
    
    if ($attempt >= $maxAttempts) {
        echo "ERREUR: Impossible de générer un numéro unique après $maxAttempts tentatives.\n";
        break;
    }
    
    $generatedNumbers[] = $returnNumber;
    echo "Numéro généré: $returnNumber (tentative $attempt)\n";
    
    // Petite pause pour voir la différence de timestamp
    usleep(50000); // 0.05 seconde
}

echo "\nTous les numéros générés sont uniques: " . (count($generatedNumbers) === count(array_unique($generatedNumbers)) ? "OUI" : "NON") . "\n";

// Tester aussi avec la base de données
echo "\n=== TEST AVEC LA BASE DE DONNÉES ===\n";
$dbUnique = true;
do {
    $microtime = str_replace('.', '', microtime(true));
    $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
    $testNumber = 'RET' . substr($microtime, 0, 14) . $random;
} while (\App\Models\OrderReturn::where('return_number', $testNumber)->exists());

echo "Numéro unique trouvé: $testNumber\n";
echo "Ce numéro n'existe pas dans la base: " . (\App\Models\OrderReturn::where('return_number', $testNumber)->exists() ? "NON" : "OUI") . "\n";

echo "\nTest terminé avec succès !\n";
