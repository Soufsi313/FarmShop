# 🎯 SCHEMAS ULTRA-REDUITS - Affichage Optimal

## ✅ 13 Schémas avec 2-4 Tables Maximum

```
📁 database_schemas_small/
├── 01_users_auth.sql           (4 KB)  - 👤 Users + Auth (3 tables)
├── 02_products_catalog.sql     (8 KB)  - 📦 Produits complets (4 tables)  
├── 03_users_preferences.sql    (8 KB)  - ❤️ Likes + Wishlists (4 tables)
├── 04_shopping_carts.sql       (9 KB)  - 🛒 Paniers d'achat (4 tables)
├── 05_orders_main.sql          (11 KB) - 📋 Commandes principales (4 tables)
├── 06_orders_returns.sql       (12 KB) - ↩️ Retours commandes (4 tables)
├── 07_rentals_main.sql         (11 KB) - 🏠 Locations principales (4 tables)
├── 08_rental_carts.sql         (9 KB)  - 🛒 Paniers location (4 tables)
├── 09_blog_system.sql          (9 KB)  - 📝 Blog + commentaires (4 tables)
├── 10_blog_moderation.sql      (7 KB)  - 🛡️ Modération blog (3 tables)
├── 11_newsletters.sql          (8 KB)  - 📧 Newsletters (4 tables)
├── 12_messages.sql             (4 KB)  - 💬 Messages (2 tables)
└── 13_cookies_gdpr.sql         (4 KB)  - 🍪 Cookies RGPD (2 tables)
```

## 🎨 AFFICHAGE OPTIMAL GARANTI !

### 👍 **Avantages des schémas réduits :**
- **2-4 tables maximum** par diagramme
- **Affichage clair** et lisible  
- **Relations visibles** sans encombrement
- **Texte lisible** même sur petits écrans
- **Focus thématique** par domaine métier

## 📊 Détail des Schémas

### 🔐 **01_users_auth.sql** - Authentification
**Tables :** `users`, `password_reset_tokens`, `sessions`
- Base de l'authentification
- Gestion des mots de passe  
- Sessions utilisateur

### 📦 **02_products_catalog.sql** - Catalogue
**Tables :** `products`, `categories`, `rental_categories`, `special_offers`
- Catalogue complet des produits
- Système de catégorisation
- Offres spéciales

### ❤️ **03_users_preferences.sql** - Préférences
**Tables :** `users`, `products`, `product_likes`, `wishlists`
- Likes utilisateur sur produits
- Listes de souhaits
- Préférences personnelles

### 🛒 **04_shopping_carts.sql** - Paniers Achat
**Tables :** `users`, `products`, `carts`, `cart_items`
- Workflow d'achat
- Gestion des paniers
- Calculs automatiques

### 📋 **05_orders_main.sql** - Commandes
**Tables :** `users`, `products`, `orders`, `order_items`
- Processus de commande principal
- Articles commandés
- Statuts et workflow

### ↩️ **06_orders_returns.sql** - Retours
**Tables :** `users`, `orders`, `order_items`, `order_returns`
- Gestion des retours
- Remboursements
- Statuts de retour

### 🏠 **07_rentals_main.sql** - Locations
**Tables :** `users`, `products`, `order_locations`, `order_item_locations`
- Processus de location principal
- Articles loués
- Contraintes de location

### 🛒 **08_rental_carts.sql** - Paniers Location  
**Tables :** `users`, `products`, `cart_locations`, `cart_item_locations`
- Workflow de location
- Paniers spécifiques location
- Calculs de coûts

### 📝 **09_blog_system.sql** - Blog
**Tables :** `users`, `blog_categories`, `blog_posts`, `blog_comments`
- Système de blog complet
- Catégories d'articles
- Commentaires utilisateur

### 🛡️ **10_blog_moderation.sql** - Modération
**Tables :** `users`, `blog_comments`, `blog_comment_reports`
- Signalements de commentaires
- Modération communautaire
- Gestion des rapports

### 📧 **11_newsletters.sql** - Newsletters
**Tables :** `users`, `newsletters`, `newsletter_subscriptions`, `newsletter_sends`
- Système de newsletters
- Abonnements utilisateur
- Historique d'envois

### 💬 **12_messages.sql** - Messages
**Tables :** `users`, `messages`
- Système de messaging simple
- Communication utilisateur

### 🍪 **13_cookies_gdpr.sql** - RGPD
**Tables :** `users`, `cookies`
- Conformité RGPD
- Gestion des consentements

## 🚀 Import dans dbdiagram.io

### Workflow recommandé :
1. **Commencer par les bases :**
   - `01_users_auth.sql` - Comprendre l'authentification
   - `02_products_catalog.sql` - Voir le catalogue

2. **Explorer les workflows :**
   - `04_shopping_carts.sql` → `05_orders_main.sql` (Achat)
   - `08_rental_carts.sql` → `07_rentals_main.sql` (Location)

3. **Fonctionnalités avancées :**
   - `03_users_preferences.sql` - Engagement utilisateur
   - `09_blog_system.sql` - Contenu
   - `11_newsletters.sql` - Marketing

### 🎯 **Résultat attendu :**
- **Diagrammes parfaitement lisibles**
- **Relations claires et visibles**
- **Aucun problème d'affichage**
- **Focus sur chaque domaine métier**

---

🎉 **PROBLÈME D'AFFICHAGE RÉSOLU !** Diagrammes optimaux garantis ! 🎯
