# 📊 GUIDE DE SCHÉMATISATION - FarmShop Database

## 🎯 Stratégie de Schématisation

Votre base de données de 33 tables peut être divisée en **6 schémas logiques** indépendants, chacun représentant un domaine métier distinct.

## 📋 Schémas Recommandés

### 1️⃣ **SCHÉMA UTILISATEURS & AUTHENTIFICATION**
```sql
-- Tables principales
users
password_reset_tokens
sessions

-- Tables liées (interactions utilisateur)
product_likes
wishlists
cookies
```

**Relations internes :** 
- `product_likes.user_id` → `users.id`
- `wishlists.user_id` → `users.id`

**Dépendances externes :** 
- `product_likes.product_id` → `products.id` (Schéma Produits)
- `wishlists.product_id` → `products.id` (Schéma Produits)

---

### 2️⃣ **SCHÉMA PRODUITS & CATALOGUE**
```sql
-- Tables principales
products
categories
rental_categories

-- Tables liées
special_offers
```

**Relations internes :**
- `products.category_id` → `categories.id`
- `products.rental_category_id` → `rental_categories.id`
- `special_offers.product_id` → `products.id`

**Dépendances externes :** Aucune (schéma de base)

---

### 3️⃣ **SCHÉMA COMMANDES & ACHATS**
```sql
-- Tables principales
orders
order_items
order_returns

-- Tables panier
carts
cart_items
```

**Relations internes :**
- `order_items.order_id` → `orders.id`
- `order_returns.order_id` → `orders.id`
- `cart_items.cart_id` → `carts.id`

**Dépendances externes :**
- `orders.user_id` → `users.id` (Schéma Utilisateurs)
- `order_items.product_id` → `products.id` (Schéma Produits)
- `cart_items.product_id` → `products.id` (Schéma Produits)

---

### 4️⃣ **SCHÉMA LOCATIONS**
```sql
-- Tables principales
order_locations
order_item_locations

-- Tables panier location
cart_locations
cart_item_locations
```

**Relations internes :**
- `order_item_locations.order_location_id` → `order_locations.id`
- `cart_item_locations.cart_location_id` → `cart_locations.id`

**Dépendances externes :**
- `order_locations.user_id` → `users.id` (Schéma Utilisateurs)
- `order_item_locations.product_id` → `products.id` (Schéma Produits)
- `cart_item_locations.product_id` → `products.id` (Schéma Produits)

---

### 5️⃣ **SCHÉMA COMMUNICATION & MARKETING**
```sql
-- Communication
messages

-- Blog
blog_categories
blog_posts
blog_comments
blog_comment_reports

-- Newsletter
newsletters
newsletter_subscriptions
newsletter_sends
```

**Relations internes :**
- `blog_posts.category_id` → `blog_categories.id`
- `blog_comments.post_id` → `blog_posts.id`
- `blog_comment_reports.comment_id` → `blog_comments.id`
- `newsletter_sends.newsletter_id` → `newsletters.id`

**Dépendances externes :**
- `messages.user_id` → `users.id` (Schéma Utilisateurs)
- `blog_comments.user_id` → `users.id` (Schéma Utilisateurs)
- `newsletter_subscriptions.user_id` → `users.id` (Schéma Utilisateurs)

---

### 6️⃣ **SCHÉMA SYSTÈME & INFRASTRUCTURE**
```sql
-- Laravel système
migrations
cache
cache_locks
jobs
job_batches
failed_jobs
```

**Relations internes :** Aucune (tables techniques)
**Dépendances externes :** Aucune

---

## 🔧 Méthodes de Création des Schémas

### Méthode 1 : Export Sélectif avec mysqldump

```bash
# 1. Schéma Utilisateurs
mysqldump -u username -p farmshop \
  users password_reset_tokens sessions product_likes wishlists cookies \
  --routines --triggers --add-drop-table > schema_users.sql

# 2. Schéma Produits (À FAIRE EN PREMIER - Base)
mysqldump -u username -p farmshop \
  products categories rental_categories special_offers \
  --routines --triggers --add-drop-table > schema_products.sql

# 3. Schéma Commandes
mysqldump -u username -p farmshop \
  orders order_items order_returns carts cart_items \
  --routines --triggers --add-drop-table > schema_orders.sql

# 4. Schéma Locations  
mysqldump -u username -p farmshop \
  order_locations order_item_locations cart_locations cart_item_locations \
  --routines --triggers --add-drop-table > schema_rentals.sql

# 5. Schéma Communication
mysqldump -u username -p farmshop \
  messages blog_categories blog_posts blog_comments blog_comment_reports \
  newsletters newsletter_subscriptions newsletter_sends \
  --routines --triggers --add-drop-table > schema_communication.sql

# 6. Schéma Système
mysqldump -u username -p farmshop \
  migrations cache cache_locks jobs job_batches failed_jobs \
  --routines --triggers --add-drop-table > schema_system.sql
```

### Méthode 2 : Outil de Modélisation avec Filtres

Pour **MySQL Workbench**, **phpMyAdmin**, ou **DBeaver** :

1. **Connecter à la base**
2. **Reverse Engineering** → Sélectionner tables par groupe
3. **Filtrer par regex** :
   ```regex
   # Groupe Produits
   ^(products|categories|rental_categories|special_offers)$
   
   # Groupe Commandes  
   ^(orders|order_items|order_returns|carts|cart_items)$
   
   # Groupe Locations
   ^(order_locations|order_item_locations|cart_locations|cart_item_locations)$
   ```

### Méthode 3 : Script PHP Personnalisé

```php
// Créer des vues temporaires pour chaque schéma
foreach ($schemas as $name => $tables) {
    $tableList = implode("', '", $tables);
    DB::statement("
        CREATE VIEW schema_{$name}_tables AS 
        SELECT * FROM information_schema.tables 
        WHERE table_schema = 'farmshop' 
        AND table_name IN ('$tableList')
    ");
}
```

---

## 🎨 Ordre de Création Recommandé

### Phase 1 : Schémas Indépendants
1. **Système** (aucune dépendance)
2. **Produits** (base pour tous les autres)

### Phase 2 : Schémas avec Utilisateurs
3. **Utilisateurs** (dépend de Produits pour likes/wishlist)

### Phase 3 : Schémas Métier
4. **Commandes** (dépend de Utilisateurs + Produits)
5. **Locations** (dépend de Utilisateurs + Produits)
6. **Communication** (dépend de Utilisateurs)

---

## 🛠️ Résolution des Dépendances

### Option A : Schémas avec Tables de Référence
Inclure les tables référencées comme "fantômes" :

```sql
-- Dans schema_orders.sql
-- Tables principales
CREATE TABLE orders (...);
CREATE TABLE order_items (...);

-- Tables de référence (structure seulement)
CREATE TABLE users (
    id BIGINT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255)
) COMMENT 'Référence depuis schema_users';

CREATE TABLE products (
    id BIGINT PRIMARY KEY, 
    name VARCHAR(255)
) COMMENT 'Référence depuis schema_products';
```

### Option B : Légende des Relations Externes
Ajouter des commentaires visuels :

```sql
-- ⚠️ DÉPENDANCES EXTERNES :
-- orders.user_id → users.id (Voir: schema_users)
-- order_items.product_id → products.id (Voir: schema_products)
```

---

## 📱 Outils Recommandés

### Pour la Visualisation
1. **MySQL Workbench** - Idéal pour les relations
2. **DBeaver** - Multi-plateforme, gratuit
3. **Lucidchart** - Diagrammes professionnels
4. **Draw.io** - Gratuit, intégration web

### Pour l'Export
1. **mysqldump** avec filtres
2. **phpMyAdmin** export sélectif  
3. **Sequel Pro** (macOS)
4. **HeidiSQL** (Windows)

---

## ✅ Checklist de Validation

- [ ] Schéma Produits créé (BASE)
- [ ] Schéma Utilisateurs créé
- [ ] Schéma Commandes créé avec dépendances
- [ ] Schéma Locations créé avec dépendances  
- [ ] Schéma Communication créé
- [ ] Schéma Système créé
- [ ] Relations externes documentées
- [ ] Légende des dépendances ajoutée

Cette approche vous permettra d'avoir 6 schémas lisibles et cohérents !
