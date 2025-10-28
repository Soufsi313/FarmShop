# Tests Unitaires - Models Eloquent

## 📋 Description

Tests de validation des modèles Eloquent du projet FarmShop. Ces tests vérifient la structure, les relations, les méthodes métier et les scopes de chaque modèle.

## 🎯 Modèles testés

### 1. User Model (`test_user_model.php`)
- ✅ Structure et fillable attributes
- ✅ Hidden attributes (password, remember_token)
- ✅ Méthodes métier: `isAdmin()`, `isUser()`, `hasRole()`
- ✅ Scopes: `admins()`, `users()`
- ✅ Relations: carts, orders, messages
- ✅ Type casting (newsletter_subscribed, dates)

### 2. Product Model (`test_product_model.php`)
- ✅ Constantes TYPE_SALE et TYPE_RENTAL
- ✅ Attributs fillable et translatables
- ✅ Relations: category, rentalCategory, wishlists, likes
- ✅ Génération automatique du slug
- ✅ Type casting (is_active, gallery_images, price)
- ✅ Répartition sale vs rental

### 3. Order Model (`test_order_model.php`)
- ✅ Attributs fillable et valeurs par défaut
- ✅ Scopes de statut: pending, confirmed, shipped, delivered, cancelled
- ✅ Relations: user, items, returns, returnableItems
- ✅ Type casting (boolean, arrays, dates, decimals)
- ✅ Cohérence des relations

### 4. Category Model (`test_category_model.php`)
- ✅ Attributs fillable et translatables
- ✅ Relation avec les produits
- ✅ Génération automatique du slug
- ✅ Type casting (is_active, is_returnable)
- ✅ Catégories actives vs inactives

## 🚀 Utilisation

### Exécuter tous les tests Models
```bash
php TestUnit/02_Models/run_all_tests.php
```

### Exécuter un test spécifique
```bash
# Test du modèle User
php TestUnit/02_Models/test_user_model.php

# Test du modèle Product
php TestUnit/02_Models/test_product_model.php

# Test du modèle Order
php TestUnit/02_Models/test_order_model.php

# Test du modèle Category
php TestUnit/02_Models/test_category_model.php
```

## 📊 Résultats attendus

Chaque test affiche:
- ✅ Tests réussis avec détails
- ⚠️  Avertissements si anomalies détectées
- ❌ Erreurs si problèmes critiques

Le runner affiche:
- Résumé de chaque test
- Nombre de tests réussis/échoués
- Temps d'exécution total

## 🔍 Points vérifiés

### Structure
- Existence et accessibilité du modèle
- Attributs fillable correctement définis
- Attributs hidden pour données sensibles

### Relations
- Relations définies et fonctionnelles
- Chargement eager loading
- Cohérence des données relationnelles

### Fonctionnalités
- Méthodes métier opérationnelles
- Scopes fonctionnels
- Type casting correct
- Génération automatique (slugs, etc.)

### Traductions
- Attributs translatables configurés
- Spatie Translatable fonctionnel

## 🛠️ Dépendances

- Laravel 11+
- Eloquent ORM
- Spatie Translatable
- Base de données configurée

## 📝 Notes

- Les tests utilisent les données réelles de la base de données
- Aucune modification n'est effectuée (lecture seule)
- Bootstrap Laravel requis pour l'exécution
