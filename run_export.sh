#!/bin/bash
# Export automatique des schémas FarmShop
# Généré le: 2025-07-14 10:03:30

mkdir -p database_schemas

echo "📦 Export: Produits & Catalogue..."
mysqldump -u root -p farmshop products categories rental_categories special_offers --routines --triggers --add-drop-table --single-transaction --lock-tables=false > database_schemas/01_products_schema.sql

echo "👥 Export: Utilisateurs & Authentification..."
mysqldump -u root -p farmshop users password_reset_tokens sessions product_likes wishlists cookies --routines --triggers --add-drop-table --single-transaction --lock-tables=false > database_schemas/02_users_schema.sql

echo "🛒 Export: Commandes & Achats..."
mysqldump -u root -p farmshop orders order_items order_returns carts cart_items --routines --triggers --add-drop-table --single-transaction --lock-tables=false > database_schemas/03_orders_schema.sql

echo "🏠 Export: Locations..."
mysqldump -u root -p farmshop order_locations order_item_locations cart_locations cart_item_locations --routines --triggers --add-drop-table --single-transaction --lock-tables=false > database_schemas/04_rentals_schema.sql

echo "📢 Export: Communication & Marketing..."
mysqldump -u root -p farmshop messages blog_categories blog_posts blog_comments blog_comment_reports newsletters newsletter_subscriptions newsletter_sends --routines --triggers --add-drop-table --single-transaction --lock-tables=false > database_schemas/05_communication_schema.sql

echo "⚙️ Export: Système & Infrastructure..."
mysqldump -u root -p farmshop migrations cache cache_locks jobs job_batches failed_jobs --routines --triggers --add-drop-table --single-transaction --lock-tables=false > database_schemas/06_system_schema.sql

echo "✅ Export terminé! Fichiers dans: database_schemas/"
ls -la database_schemas/
