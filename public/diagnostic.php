<?php
// Script de diagnostic d'urgence pour identifier le problème ReflectionException

echo "=== DIAGNOSTIC URGENCE ===" . PHP_EOL;
echo "Tentative de chargement minimal de Laravel..." . PHP_EOL;

try {
    // Charger seulement les dépendances de base
    require __DIR__ . '/../vendor/autoload.php';
    echo "✅ Autoloader chargé" . PHP_EOL;
    
    // Créer une instance minimale
    $app = new \Illuminate\Foundation\Application(__DIR__ . '/..');
    echo "✅ Application créée" . PHP_EOL;
    
    // Tester l'accès à l'environnement
    echo "Environment: " . $app->environment() . PHP_EOL;
    echo "✅ Environnement accessible" . PHP_EOL;
    
} catch (Exception $e) {
    echo "❌ ERREUR: " . $e->getMessage() . PHP_EOL;
    echo "Ligne: " . $e->getLine() . PHP_EOL;
    echo "Fichier: " . $e->getFile() . PHP_EOL;
}

echo "=== FIN DIAGNOSTIC ===" . PHP_EOL;
