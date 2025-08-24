# 🎯 Guide dbdiagram.io - Schémas avec Dépendances

## ✅ Nouveaux fichiers auto-suffisants

**Utilisez ces fichiers** pour dbdiagram.io (toutes les FK sont résolues) :

```
📁 database_schemas/
├── 01_products_base.sql        (8 KB)  - 📦 Produits & Catalogue seuls
├── 02_users_products.sql       (11 KB) - 👥 Utilisateurs + Produits (likes/wishlists)
├── 03_orders_complete.sql      (19 KB) - 🛒 Workflow Commandes complet
├── 04_rentals_complete.sql     (17 KB) - 🏠 Workflow Locations complet
└── 05_communication_users.sql  (19 KB) - 📢 Communication + Utilisateurs
```

## 🔗 Contenu de chaque schéma

### 1. **01_products_base.sql** (Base)
```
✅ products
✅ categories  
✅ rental_categories
✅ special_offers
→ AUCUNE dépendance externe
```

### 2. **02_users_products.sql** (Users + Relations)
```
✅ users
✅ password_reset_tokens
✅ sessions
✅ products (pour les FK)
✅ product_likes  
✅ wishlists
✅ cookies
→ FK users→products résolues
```

### 3. **03_orders_complete.sql** (E-commerce complet)
```
✅ users
✅ products
✅ categories
✅ orders
✅ order_items
✅ order_returns
✅ carts
✅ cart_items
→ Workflow e-commerce complet
```

### 4. **04_rentals_complete.sql** (Location complète)
```
✅ users
✅ products  
✅ categories
✅ rental_categories
✅ order_locations
✅ order_item_locations
✅ cart_locations
✅ cart_item_locations
→ Workflow location complet
```

### 5. **05_communication_users.sql** (CMS + Users)
```
✅ users
✅ messages
✅ blog_categories
✅ blog_posts
✅ blog_comments
✅ blog_comment_reports
✅ newsletters
✅ newsletter_subscriptions
✅ newsletter_sends
→ Système de communication complet
```

## 🚀 Import dans dbdiagram.io

### ✅ UTILISEZ CES FICHIERS :
- `01_products_base.sql` 
- `02_users_products.sql`
- `03_orders_complete.sql`
- `04_rentals_complete.sql`  
- `05_communication_users.sql`

### ❌ N'UTILISEZ PAS :
- `02_users_schema.sql` (FK manquantes)
- `03_orders_schema.sql` (FK manquantes)
- `04_rentals_schema.sql` (FK manquantes)
- `05_communication_schema.sql` (FK manquantes)

## 📊 Avantages

1. **✅ FK résolues** : Toutes les relations sont visibles
2. **✅ Import direct** : Aucune erreur dans dbdiagram.io
3. **✅ Workflows complets** : Vue d'ensemble de chaque domaine
4. **✅ Diagrammes cohérents** : Relations complètes affichées

## 🎨 Résultat attendu

### Diagramme Users + Products :
```
┌─────────────┐    ┌─────────────┐    ┌─────────────┐
│    users    │    │ product_likes│    │  products   │
├─────────────┤    ├─────────────┤    ├─────────────┤
│ id (PK)     │←───│ user_id     │    │ id (PK)     │
│ name        │    │ product_id  │───→│ name        │
│ email       │    │ created_at  │    │ price       │
└─────────────┘    └─────────────┘    └─────────────┘
```

**Relations visibles et fonctionnelles !** 🎯

---

🎉 **Maintenant vos imports dbdiagram.io vont fonctionner parfaitement !**
