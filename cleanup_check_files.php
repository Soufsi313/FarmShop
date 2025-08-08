<?php

// Script de nettoyage des fichiers check vides

$directory = __DIR__;
$checkFiles = glob($directory . '/check*.php');
$emptyFiles = [];
$nonEmptyFiles = [];

echo "=== NETTOYAGE DES FICHIERS CHECK ===\n\n";

foreach ($checkFiles as $file) {
    $filename = basename($file);
    $content = file_get_contents($file);
    $content = trim($content);
    
    // Considérer comme vide si le fichier fait moins de 50 caractères ou contient seulement <?php
    if (empty($content) || strlen($content) < 50 || $content === '<?php') {
        $emptyFiles[] = $file;
        echo "📄 VIDE: $filename (taille: " . strlen($content) . " chars)\n";
    } else {
        $nonEmptyFiles[] = $file;
        echo "✅ UTILE: $filename (taille: " . strlen($content) . " chars)\n";
    }
}

echo "\n=== RÉSUMÉ ===\n";
echo "Fichiers vides trouvés: " . count($emptyFiles) . "\n";
echo "Fichiers utiles: " . count($nonEmptyFiles) . "\n\n";

if (!empty($emptyFiles)) {
    echo "🗑️ SUPPRESSION DES FICHIERS VIDES:\n";
    foreach ($emptyFiles as $file) {
        if (unlink($file)) {
            echo "✅ Supprimé: " . basename($file) . "\n";
        } else {
            echo "❌ Erreur suppression: " . basename($file) . "\n";
        }
    }
    echo "\n✅ " . count($emptyFiles) . " fichiers vides supprimés!\n";
} else {
    echo "✅ Aucun fichier vide trouvé.\n";
}

echo "\n📁 Dossier nettoyé avec succès!\n";
