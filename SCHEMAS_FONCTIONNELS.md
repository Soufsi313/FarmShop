# ✅ SCHEMAS FONCTIONNELS POUR DBDIAGRAM.IO

## 🎯 Fichiers à utiliser (AVEC TOUTES LES DÉPENDANCES)

```
📁 database_schemas/
├── 01_products_base.sql        (8 KB)  - 📦 Produits & Catalogue
├── 02_users_products.sql       (11 KB) - 👥 Users + Products (likes/wishlists)  
├── 03_orders_complete.sql      (20 KB) - 🛒 Orders + Users + Products
├── 04_rentals_complete.sql     (17 KB) - 🏠 Rentals + Users + Products
└── 05_communication_users.sql  (20 KB) - 📢 Communication + Users
```

## ✅ GARANTIE : Toutes les références sont résolues !

### 📦 01_products_base.sql
**Tables incluses :**
- `products`, `categories`, `rental_categories`, `special_offers`
- **Aucune dépendance externe**
- ✅ **Fonctionne parfaitement dans dbdiagram.io**

### 👥 02_users_products.sql  
**Tables incluses :**
- `users`, `password_reset_tokens`, `sessions`, `cookies`
- `products` (pour résoudre les FK)
- `product_likes`, `wishlists` (avec FK vers products)
- ✅ **Toutes les références `products` sont résolues**

### 🛒 03_orders_complete.sql
**Tables incluses :**
- `users`, `products`, `categories` (dépendances)
- `orders`, `order_items`, `order_returns`
- `carts`, `cart_items`
- ✅ **Workflow complet d'achat avec toutes les FK**

### 🏠 04_rentals_complete.sql
**Tables incluses :**
- `users`, `products`, `categories`, `rental_categories` (dépendances)
- `order_locations`, `order_item_locations`
- `cart_locations`, `cart_item_locations`  
- ✅ **Workflow complet de location avec toutes les FK**

### 📢 05_communication_users.sql
**Tables incluses :**
- `users` (pour les auteurs/destinataires)
- `messages`, `blog_categories`, `blog_posts`
- `blog_comments`, `blog_comment_reports`
- `newsletters`, `newsletter_subscriptions`, `newsletter_sends`
- ✅ **Toutes les références `users` sont résolues**

## 🚀 Import dans dbdiagram.io

### Pour chaque fichier :
1. **Aller sur** https://dbdiagram.io/
2. **Create new diagram**
3. **Import** → MySQL
4. **Sélectionner un fichier** (ex: `01_products_base.sql`)
5. **✅ SUCCÈS garanti !** Toutes les FK seront reconnues

### Résultat attendu :
- **Relations automatiques** entre tables
- **Diagrammes propres** et lisibles
- **Aucune erreur** "undefined table"
- **Export PNG/PDF** possible

## 🎨 Suggestions de nommage des diagrammes

- `01_products_base.sql` → **"FarmShop - Catalogue Produits"**
- `02_users_products.sql` → **"FarmShop - Utilisateurs & Favoris"**  
- `03_orders_complete.sql` → **"FarmShop - Processus d'Achat"**
- `04_rentals_complete.sql` → **"FarmShop - Processus de Location"**
- `05_communication_users.sql` → **"FarmShop - Communication & Blog"**

---

🎉 **PROBLÈME RÉSOLU !** Plus d'erreurs "undefined table" dans dbdiagram.io !
