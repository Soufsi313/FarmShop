# Tests Unitaires - Controllers

## 📋 Description

Tests de validation des controllers HTTP du projet FarmShop. Ces tests vérifient la structure, les méthodes, les dépendances et les fonctionnalités de chaque controller.

## 🎯 Controllers testés

### 1. ProductController (`test_product_controller.php`)
- ✅ Méthodes CRUD (index, show, store, update, destroy)
- ✅ Filtrage (category_id, type, stock_status, search)
- ✅ Tri (name, price, created_at, popularity)
- ✅ Pagination
- ✅ Réponses JSON structurées

### 2. CartController (`test_cart_controller.php`)
- ✅ Gestion du panier (index, store, update, destroy, clear)
- ✅ Vérification disponibilité produits
- ✅ Calcul sous-total, frais de livraison, total
- ✅ Gestion des quantités
- ✅ Items indisponibles

### 3. OrderController (`test_order_controller.php`)
- ✅ Opérations commandes (index, show, store, showCheckout)
- ✅ Intégration StripeService
- ✅ Génération factures PDF (DomPDF)
- ✅ Gestion statuts (pending, confirmed, shipped, delivered, cancelled)
- ✅ Filtrage et tri des commandes
- ✅ Notifications email

### 4. Admin DashboardController (`test_admin_dashboard_controller.php`)
- ✅ Contrôle d'accès admin
- ✅ Statistiques stock (critiques, rupture, faible)
- ✅ Analytics (commandes, revenus, utilisateurs)
- ✅ Newsletter (abonnés, envois, taux ouverture)
- ✅ Rentals (locations, retours)
- ✅ Blog et messages

## 🚀 Utilisation

### Exécuter tous les tests Controllers
```bash
php TestUnit/03_Controllers/run_all_tests.php
```

### Exécuter un test spécifique
```bash
# Test ProductController
php TestUnit/03_Controllers/test_product_controller.php

# Test CartController
php TestUnit/03_Controllers/test_cart_controller.php

# Test OrderController
php TestUnit/03_Controllers/test_order_controller.php

# Test Admin DashboardController
php TestUnit/03_Controllers/test_admin_dashboard_controller.php
```

## 📊 Résultats attendus

Chaque test affiche:
- ✅ Tests réussis avec détails
- ⚠️  Avertissements si méthodes optionnelles manquantes
- ❌ Erreurs si problèmes critiques

Le runner affiche:
- Résumé de chaque controller
- Nombre de tests réussis/échoués
- Temps d'exécution total

## 🔍 Points vérifiés

### Structure
- Existence et instanciation du controller
- Méthodes publiques définies
- Injection de dépendances (services)

### Fonctionnalités
- Opérations CRUD complètes
- Filtrage et recherche
- Pagination et tri
- Validation des données

### Intégrations
- Services externes (Stripe, PDF)
- Middleware et authentification
- Réponses JSON formatées
- Gestion des erreurs

### Sécurité
- Contrôle d'accès (admin)
- Validation des entrées
- Protection CSRF
- Authentification requise

## 🛠️ Dépendances

- Laravel 11+
- Controllers HTTP
- StripeService (paiements)
- DomPDF (factures)
- Middleware d'authentification

## 📝 Notes

- Tests de structure uniquement (pas de tests fonctionnels complets)
- Pas d'interaction avec la base de données dans ces tests
- Bootstrap Laravel requis pour l'exécution
- Tests rapides et légers
