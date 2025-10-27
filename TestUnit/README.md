# 🧪 Tests Unitaires FarmShop

Ce dossier contient tous les tests unitaires pour diagnostiquer et vérifier le bon fonctionnement de chaque composant du site FarmShop.

## 📁 Structure des Tests

```
TestUnit/
├── README.md                          # Ce fichier
├── 01_Database/                       # Tests de connexion et tables
├── 02_Models/                         # Tests des modèles Eloquent
├── 03_Controllers/                    # Tests des contrôleurs
├── 04_Authentication/                 # Tests d'authentification
├── 05_Cart/                          # Tests du panier d'achat
├── 06_Payment/                       # Tests Stripe et paiements
├── 07_Orders/                        # Tests des commandes
├── 08_Rentals/                       # Tests des locations
├── 09_Email/                         # Tests d'envoi d'emails
├── 10_API/                           # Tests des routes API
└── RESULTS/                          # Résultats des tests
```

## 🚀 Comment Utiliser

### 1. Tests Manuels (Navigation)
Ouvrez les fichiers HTML dans `TestUnit/XX_Categorie/test_*.html` dans votre navigateur pour tester visuellement.

### 2. Tests Artisan (Commandes)
Exécutez les scripts PHP avec Artisan :
```bash
php artisan tinker < TestUnit/XX_Categorie/test_*.php
```

### 3. Tests Automatisés (PHPUnit)
Certains tests utilisent PHPUnit :
```bash
php artisan test --testsuite=TestUnit
```

## 📋 Checklist des Tests

### Niveau 1 : Infrastructure ✅
- [ ] Connexion base de données
- [ ] Vérification des tables
- [ ] Vérification des migrations
- [ ] Connexion Redis/Cache
- [ ] Variables d'environnement

### Niveau 2 : Modèles ✅
- [ ] Product Model
- [ ] User Model
- [ ] Order Model
- [ ] Cart Model
- [ ] Category Model

### Niveau 3 : Authentification ✅
- [ ] Login/Logout
- [ ] Registration
- [ ] Password Reset
- [ ] Sessions
- [ ] CSRF Protection

### Niveau 4 : Fonctionnalités ✅
- [ ] Ajout au panier
- [ ] Modification quantités
- [ ] Calcul des prix
- [ ] Application des offres spéciales
- [ ] Gestion du stock

### Niveau 5 : Paiement ✅
- [ ] Stripe API Connection
- [ ] Webhook Reception
- [ ] Payment Intent Creation
- [ ] Order Processing
- [ ] Email Confirmation

### Niveau 6 : Avancé ✅
- [ ] Queue Workers
- [ ] Background Jobs
- [ ] Scheduled Tasks
- [ ] API Endpoints
- [ ] Performance

## 📊 Rapports

Les résultats des tests sont automatiquement sauvegardés dans `TestUnit/RESULTS/` avec horodatage.

## ⚠️ Important

**CES TESTS NE MODIFIENT PAS LE CODE EXISTANT**

Ils servent uniquement à :
- ✅ Diagnostiquer les problèmes
- ✅ Vérifier le bon fonctionnement
- ✅ Identifier les erreurs
- ✅ Documenter l'état du système

---

*Créé le : {{ date('Y-m-d') }}*
*Version : 1.0*
