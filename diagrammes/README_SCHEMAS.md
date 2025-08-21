# Schémas de Base de Données - FarmShop (Style dbdiagram.io)

Ce dossier contient les schémas de base de données relationnelle au format **DBML** (Database Markup Language) pour une utilisation avec **dbdiagram.io**, excluant les tables système, GDPR et cookies.

## 🎯 Avantages du format DBML + dbdiagram.io

✅ **Diagrammes professionnels** - Rendu visuel de haute qualité  
✅ **Relations claires** - Lignes de connexion automatiques  
✅ **Types de données précis** - Contraintes et index affichés  
✅ **Export multiple** - PNG, PDF, SVG haute résolution  
✅ **Génération SQL** - DDL automatique pour MySQL, PostgreSQL, etc.  

## 📋 Plan d'itération - 5 schémas

### 1. Utilisateurs & Authentification (`dbml_users_auth.dbml`)
**Tables incluses :**
- `users` : Gestion des utilisateurs avec rôles (Admin/User), informations personnelles et adresses
- `password_reset_tokens` : Tokens de réinitialisation de mot de passe
- `messages` : Système de messagerie utilisateur-admin

### 2. Produits & Catégories (`dbml_products_categories.dbml`)
**Tables incluses :**
- `categories` : Catégories de produits avec SEO et types d'aliments
- `rental_categories` : Catégories spécifiques aux locations
- `products` : Produits avec gestion stock, location, SEO et métadonnées
- `product_likes` : Système de likes des produits
- `wishlists` : Listes de souhaits des utilisateurs
- `special_offers` : Offres spéciales et promotions

### 3. Commandes & Achats (`dbml_orders_purchases.dbml`)
**Tables incluses :**
- `carts` / `cart_items` : Paniers d'achat avec calculs TTC/HT
- `orders` / `order_items` : Commandes avec statuts, paiement Stripe, livraison
- `order_returns` : Gestion des retours produits
- `order_status_transitions` : Historique des changements de statut

### 4. Locations & Gestion (`dbml_rentals_management.dbml`)
**Tables incluses :**
- `cart_locations` / `cart_item_locations` : Paniers de location
- `order_locations` / `order_item_locations` : Commandes de location avec gestion complète
- Gestion des dépôts, pénalités, et état des équipements

### 5. Communication & Blog (`dbml_communication_blog.dbml`)
**Tables incluses :**
- `newsletters`, `newsletter_subscriptions`, `newsletter_sends` : Système newsletter complet
- `email_logs` : Logs de tous les emails
- `blog_categories`, `blog_posts`, `blog_comments` : Blog multilingue
- `blog_comment_reports`, `blog_post_translations` : Modération et traductions

## 🚀 Utilisation avec dbdiagram.io

### Méthode 1: Script PowerShell automatique
```powershell
.\open_dbdiagram.ps1
```
Le script :
- Affiche un menu interactif
- Copie automatiquement le schéma choisi dans le presse-papiers
- Ouvre dbdiagram.io dans le navigateur

### Méthode 2: Manuelle
1. Aller sur **https://dbdiagram.io/**
2. Cliquer sur "Create new diagram"
3. Ouvrir l'un des fichiers `.dbml`
4. **Copier tout le contenu** et le coller dans l'éditeur
5. Le diagramme s'affiche automatiquement
6. Exporter en PNG/PDF avec "Export"

## 📁 Structure des fichiers

```
diagrammes/
├── dbml_users_auth.dbml            # Schéma 1: Utilisateurs
├── dbml_products_categories.dbml   # Schéma 2: Produits  
├── dbml_orders_purchases.dbml      # Schéma 3: Commandes
├── dbml_rentals_management.dbml    # Schéma 4: Locations
├── dbml_communication_blog.dbml    # Schéma 5: Communication
├── dbml_complete_overview.dbml     # Vue d'ensemble (TOUS les schémas)
├── open_dbdiagram.ps1             # Script d'ouverture automatique
├── GUIDE_DBDIAGRAM.md             # Guide détaillé d'utilisation
└── README_SCHEMAS.md              # Cette documentation
```

## � Tables exclues

**Tables système :**
- `cache`, `cache_locks`, `jobs`, `sessions`, `personal_access_tokens`

**Tables GDPR/Cookies :**
- `cookies` et tables de conformité GDPR

## 🔧 Fonctionnalités DBML incluses

- **Types de données précis** : varchar(255), decimal(10,2), enum, etc.
- **Contraintes** : `[pk]`, `[unique]`, `[not null]`, `[default]`
- **Relations** : `[ref: > table.column]` pour les clés étrangères
- **Index composites** : Définis dans les sections `indexes {}`
- **Notes** : Documentation pour chaque table
- **Cardinalités** : 1:1, 1:N avec syntaxe claire

## 💡 Exemples de relations

```dbml
// Clé étrangère simple
user_id bigint [ref: > users.id]

// Relation nullable
special_offer_id bigint [ref: > special_offers.id, null]

// Index composite unique
indexes {
  (user_id, product_id) [unique]
}
```

## 🔄 Mise à jour

Pour modifier un schéma :
1. Éditer le fichier `.dbml` correspondant
2. Utiliser le script `open_dbdiagram.ps1` pour le visualiser
3. Exporter la nouvelle version PNG/PDF

---
*Schémas générés au format dbdiagram.io le 20 août 2025*
