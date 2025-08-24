# FarmShop - Release Alpha v1.0.0

## Description du Projet

FarmShop est une application web dynamique de commerce électronique spécialisée dans la vente et location de produits agricoles biologiques. Cette release Alpha constitue un Produit Minimum Viable (MVP) fonctionnel.

## Architecture Technique

- **Framework**: Laravel 11 LTS
- **Base de données**: MariaDB
- **Frontend**: Blade Templates + Tailwind CSS + Alpine.js
- **Authentification**: Laravel Auth
- **API**: RESTful Laravel

## Fonctionnalités Implémentées

### 1. Authentification
- Inscription utilisateur avec validation
- Connexion/déconnexion sécurisée
- Sessions Laravel natives
- Protection CSRF

### 2. Gestion Profil Utilisateur
- Affichage et modification du profil
- Demande de suppression de compte (conformité RGPD)
- Export des données personnelles
- Gestion des préférences newsletter

### 3. Navigation Principale
- Menu responsive multi-niveaux
- Navigation séparée produits/locations
- Interface admin distincte
- Breadcrumbs contextuels

### 4. Design et Interface
- Interface moderne avec Tailwind CSS
- Design responsive (mobile-first)
- Composants interactifs Alpine.js
- UX optimisée avec animations

### 5. Catalogue Produits
- Affichage en grille responsive
- Filtres avancés (catégorie, prix, recherche)
- Tri multiple (nom, prix, popularité)
- Pagination performante
- Images produits optimisées

## Données de Test

La base de données contient des datafixtures complètes :

- **101 utilisateurs** de test
- **159 produits biologiques** répartis sur :
  - Fruits biologiques belges
  - Légumes de saison
  - Équipements agricoles
- **11 catégories** organisées hiérarchiquement
- **Messages de test** pour l'interface admin

## Structure de Base de Données

### Tables Principales
- `users` - Gestion des utilisateurs
- `products` - Catalogue produits (achat/location)
- `categories` - Organisation des produits
- `rental_categories` - Catégories spécifiques location
- `orders` / `order_locations` - Gestion des commandes
- `messages` - Système de communication

## Installation et Configuration

### Prérequis
- PHP 8.4+
- Composer
- MariaDB/MySQL
- Node.js (pour assets)

### Installation
```bash
# Cloner le repository
git clone https://github.com/Soufsi313/FarmShop.git
cd FarmShop

# Installer dépendances
composer install
npm install

# Configuration environnement
cp .env.example .env
php artisan key:generate

# Base de données
php artisan migrate --seed

# Assets
npm run build

# Serveur de développement
php artisan serve
```

## Routes Principales

### Interface Publique
- `/` - Page d'accueil
- `/products` - Catalogue produits d'achat
- `/rentals` - Catalogue produits de location
- `/login` / `/register` - Authentification

### Interface Utilisateur
- `/profile` - Gestion du profil
- `/orders` - Historique commandes (API)

### Interface Admin
- `/admin` - Dashboard administrateur
- `/admin/products` - Gestion produits
- `/admin/orders` - Gestion commandes

## API Endpoints

### Produits
- `GET /api/products` - Liste des produits
- `POST /api/products/{id}/add-to-cart` - Ajout panier

### Locations
- `GET /api/rentals/{product}/constraints` - Contraintes location
- `POST /api/rentals/{product}/calculate-cost` - Calcul coût

## Fonctionnalités Avancées

### Séparation Achat/Location
- Parcours utilisateur distincts
- Logique métier séparée
- Contraintes spécifiques location
- Calculs de coût dynamiques

### Interface Admin
- Dashboard avec statistiques
- CRUD complet produits
- Gestion des commandes
- Système de messages

### Conformité RGPD
- Export données utilisateur
- Suppression de compte
- Gestion consentements
- Anonymisation

## État de Développement

### Fonctionnalités Opérationnelles
- Authentification complète
- Catalogue produits fonctionnel
- Navigation intuitive
- Interface responsive
- Base de données structurée

### Limitations Connues
- Paiements non implémentés
- Gestion stock simplifiée
- Notifications limitées
- Tests automatisés partiels

## Prochaines Étapes

1. Intégration système de paiement
2. Amélioration gestion stock
3. Système de notifications
4. Tests automatisés complets
5. Optimisations performance

## Support et Documentation

- **Repository**: https://github.com/Soufsi313/FarmShop
- **Version**: v1.0.0-alpha
- **Laravel**: 11.x
- **License**: MIT

## Crédits

Développé dans le cadre d'un projet académique de développement web.
Utilise des datafixtures réalistes de produits biologiques belges.
