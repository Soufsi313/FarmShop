#!/bin/bash

echo "🧹 Nettoyage du projet FarmShop..."

# Supprimer tous les fichiers de test
echo "Suppression des fichiers test_*.php..."
find . -maxdepth 1 -name "test_*.php" -type f -delete

# Supprimer tous les fichiers check_*.php
echo "Suppression des fichiers check_*.php..."
find . -maxdepth 1 -name "check_*.php" -type f -delete

# Supprimer tous les fichiers debug_*.php
echo "Suppression des fichiers debug_*.php..."
find . -maxdepth 1 -name "debug_*.php" -type f -delete

# Supprimer tous les fichiers *_test.php
echo "Suppression des fichiers *_test.php..."
find . -maxdepth 1 -name "*_test.php" -type f -delete

# Supprimer tous les fichiers migrate_*.php (sauf les migrations officielles)
echo "Suppression des fichiers migrate_*.php temporaires..."
find . -maxdepth 1 -name "migrate_*.php" -type f -delete

# Supprimer tous les fichiers analyze_*.php
echo "Suppression des fichiers analyze_*.php..."
find . -maxdepth 1 -name "analyze_*.php" -type f -delete

# Supprimer tous les fichiers list_*.php temporaires
echo "Suppression des fichiers list_*.php temporaires..."
rm -f list_tables_test.php
rm -f list_tables.php
rm -f list_order_tables.php

# Supprimer d'autres fichiers temporaires spécifiques
echo "Suppression d'autres fichiers temporaires..."
rm -f create_*.php
rm -f delete_*.php
rm -f clean_*.php
rm -f cleanup_*.php
rm -f fix_*.php
rm -f populate_*.php
rm -f process_*.php
rm -f run_*.php
rm -f setup_*.php
rm -f find_*.php
rm -f generate_*.php

# Supprimer les fichiers temporaires dans le dossier docs s'ils existent
if [ -d "docs" ]; then
    echo "Nettoyage du dossier docs..."
    rm -f docs/analyze_*.php
    rm -f docs/list_*.php
fi

echo "✅ Nettoyage terminé !"
echo "📊 Fichiers supprimés. Vérifiez avec 'git status' pour voir les changements."
