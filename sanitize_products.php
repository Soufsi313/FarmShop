#!/usr/bin/env php
<?php

echo "🧹 SANITISATION DE LA TABLE PRODUCTS\n";
echo "====================================\n\n";

echo "⚠️  ATTENTION: Cette opération va:\n";
echo "   - Supprimer TOUS les produits existants\n";
echo "   - Garder les catégories actives\n";
echo "   - Créer de nouveaux produits cohérents\n\n";

echo "Voulez-vous continuer? (o/N): ";
$handle = fopen("php://stdin", "r");
$line = fgets($handle);
$response = trim($line);

if (strtolower($response) !== 'o' && strtolower($response) !== 'oui') {
    echo "❌ Opération annulée.\n";
    exit(0);
}

echo "\n🚀 Démarrage de la sanitisation...\n\n";

// Exécuter la migration pour s'assurer que les colonnes sont bonnes
echo "📋 1. Mise à jour de la structure de la table...\n";
system('php artisan migrate --force');

echo "\n🗃️ 2. Exécution du seeder de nettoyage...\n";
system('php artisan db:seed --class=CleanProductsSeeder --force');

echo "\n🔄 3. Nettoyage des caches...\n";
system('php artisan cache:clear');

echo "\n✅ Sanitisation terminée avec succès!\n";
echo "📊 Vous pouvez maintenant vérifier les nouveaux produits dans l'interface admin.\n\n";
