#!/bin/bash

# 🗄️ Script d'export des schémas FarmShop
# Usage: ./export_schemas.sh [username] [password] [database]

# Configuration
DB_USER=${1:-root}
DB_PASS=${2:-""}
DB_NAME=${3:-farmshop}
EXPORT_DIR="database_schemas"

# Créer le dossier d'export
mkdir -p $EXPORT_DIR

echo "🚀 Export des schémas FarmShop Database"
echo "Base de données: $DB_NAME"
echo "Dossier de sortie: $EXPORT_DIR"
echo ""

# Options communes mysqldump
DUMP_OPTIONS="--routines --triggers --add-drop-table --single-transaction --lock-tables=false"

# 1. SCHÉMA PRODUITS (À FAIRE EN PREMIER - Base)
echo "📦 Export Schéma Produits..."
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME \
  products categories rental_categories special_offers \
  $DUMP_OPTIONS > $EXPORT_DIR/01_schema_products.sql

# 2. SCHÉMA UTILISATEURS  
echo "👥 Export Schéma Utilisateurs..."
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME \
  users password_reset_tokens sessions product_likes wishlists cookies \
  $DUMP_OPTIONS > $EXPORT_DIR/02_schema_users.sql

# 3. SCHÉMA COMMANDES & ACHATS
echo "🛒 Export Schéma Commandes..."
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME \
  orders order_items order_returns carts cart_items \
  $DUMP_OPTIONS > $EXPORT_DIR/03_schema_orders.sql

# 4. SCHÉMA LOCATIONS
echo "🏠 Export Schéma Locations..."
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME \
  order_locations order_item_locations cart_locations cart_item_locations \
  $DUMP_OPTIONS > $EXPORT_DIR/04_schema_rentals.sql

# 5. SCHÉMA COMMUNICATION & MARKETING
echo "📢 Export Schéma Communication..."
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME \
  messages blog_categories blog_posts blog_comments blog_comment_reports \
  newsletters newsletter_subscriptions newsletter_sends \
  $DUMP_OPTIONS > $EXPORT_DIR/05_schema_communication.sql

# 6. SCHÉMA SYSTÈME
echo "⚙️ Export Schéma Système..."
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME \
  migrations cache cache_locks jobs job_batches failed_jobs \
  $DUMP_OPTIONS > $EXPORT_DIR/06_schema_system.sql

# Ajouter des en-têtes à chaque fichier
echo "📝 Ajout des en-têtes et documentation..."

for file in $EXPORT_DIR/*.sql; do
    # Nom du schéma basé sur le nom de fichier
    schema_name=$(basename "$file" .sql | sed 's/[0-9]*_schema_//' | tr '_' ' ' | sed 's/.*/\u&/')
    
    # Créer un fichier temporaire avec l'en-tête
    cat > temp_header.sql << EOF
-- ================================================================
-- 🗄️ FARMSHOP DATABASE SCHEMA: $schema_name
-- ================================================================
-- Généré le: $(date)
-- Version: Alpha v1.0.0
-- 
-- ⚠️ IMPORTANT: Ce schéma fait partie d'un ensemble de 6 schémas.
-- Consultez DATABASE_SCHEMA_GUIDE.md pour les dépendances.
-- ================================================================

EOF
    
    # Concaténer l'en-tête avec le contenu existant
    cat temp_header.sql "$file" > temp_combined.sql
    mv temp_combined.sql "$file"
    rm temp_header.sql
done

echo ""
echo "✅ Export terminé avec succès!"
echo "📁 Fichiers générés dans: $EXPORT_DIR/"
echo ""
echo "📋 Fichiers créés:"
ls -la $EXPORT_DIR/

echo ""
echo "📖 Consultez DATABASE_SCHEMA_GUIDE.md pour utiliser ces schémas."
echo "🎯 Ordre d'import recommandé: 01 → 02 → 03 → 04 → 05 → 06"
