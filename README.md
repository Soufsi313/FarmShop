# 🌱 FarmShop - Plateforme E-commerce Agricole

**Application web Laravel pour la vente et location de produits agricoles biologiques**

![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.4+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MariaDB](https://img.shields.io/badge/MariaDB-11.5+-003545?style=for-the-badge&logo=mariadb&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Version](https://img.shields.io/badge/Version-1.1.0--beta-blue?style=for-the-badge)

## 🚀 Nouveautés v1.1.0-beta

### 🏭 Système de Location Complet
- **Contraintes intelligentes** : Durées min/max, jours disponibles, délais configurables
- **API de contraintes REST** : Validation temps réel et suggestions de dates
- **Processus de retour automatisé** : Workflow complet avec notifications
- **Inspection professionnelle** : Évaluation détaillée avec photos et notes
- **Sanctions automatiques** : Calcul pénalités retard et dommages
- **Gestion dépôts** : Retenue/restitution automatique via Stripe

### �️ Conformité GDPR Totale
- **Bannière cookies intelligente** : Affichage contextuel selon statut utilisateur
- **5 catégories configurables** : Nécessaires, Analytics, Marketing, Préférences, Social
- **Migration automatique** : Cookies visiteur → utilisateur connecté
- **Interface admin complète** : Historique, statistiques temps réel
- **Persistance 365 jours** avec renouvellement automatique

### � Administration Enrichie
- **Dashboard professionnel** : Calculs automatiques des dépôts de garantie
- **Recherche multicritères** : Filtres avancés pour toutes les entités
- **Gestion signalements AJAX** : Modération commentaires blog optimisée
- **Interface responsive** : Optimisée mobile et desktop

### � E-commerce Complet (versions précédentes)
- **Intégration Stripe native** avec cartes, PayPal, virements
- **Webhooks sécurisés** pour confirmation automatique
- **Surveillance stocks intelligente** avec alertes temps réel
- **Système newsletters professionnel** avec statistiques

## 📋 Table des Matières

- [À Propos](#à-propos)
- [Fonctionnalités](#fonctionnalités)
- [Prérequis](#prérequis)
- [Installation](#installation)
- [Configuration](#configuration)
- [Base de Données](#base-de-données)
- [Démarrage](#démarrage)
- [Comptes de Test](#comptes-de-test)
- [Structure du Projet](#structure-du-projet)
- [API Documentation](#api-documentation)
- [Troubleshooting](#troubleshooting)

## 🎯 À Propos

FarmShop est une application web dynamique développée avec Laravel 11 qui permet :
- **Vente de produits biologiques** (fruits, légumes, produits fermiers)
- **Location d'équipements agricoles** (outils, machines, matériel)
- **Gestion administrative complète** (commandes, utilisateurs, stock)
- **Interface responsive moderne** avec Tailwind CSS

**Version actuelle :** v1.1.0-beta (Système de location complet + GDPR)

## ✨ Fonctionnalités

### 🏷️ Version v1.1.0-beta (Location + GDPR Complet)

#### 🏭 Système de Location Professionnel
- **Contraintes intelligentes** par produit (durées, jours, délais)
- **API REST complète** pour validation temps réel
- **Processus retour automatisé** avec notifications
- **Inspection détaillée** avec photos et évaluation dommages
- **Sanctions automatiques** (retard, dommages, perte)
- **Gestion dépôts** avec retenue/restitution automatique

#### 🛡️ Conformité GDPR Totale
- **Bannière cookies intelligente** selon statut utilisateur
- **5 catégories configurables** (Nécessaires, Analytics, etc.)
- **Migration automatique** visiteur → utilisateur connecté
- **Interface admin** avec historique et statistiques
- **Persistance 365 jours** avec renouvellement

#### 🔧 Administration Enrichie
- **Dashboard professionnel** avec calculs automatiques
- **Recherche multicritères** avancée sur toutes entités
- **Gestion signalements AJAX** pour modération blog
- **Interface responsive** optimisée mobile/desktop

### 🏷️ Version BETA v1.0.0-beta (E-commerce Complet)

#### 💳 Processus d'Achat Complet
- **Panier intelligent** avec calculs TVA automatiques
- **Checkout sécurisé** avec validation d'adresses
- **Paiements Stripe** (cartes, PayPal, virements)
- **Webhooks sécurisés** pour confirmations automatiques
- **Gestion d'états** : progression automatique des commandes
- **Suivi des livraisons** en temps réel
- **Système de remboursement** avec restauration stock

#### 📊 Surveillance Intelligente des Stocks
- **Observer Pattern** pour monitoring temps réel
- **Seuils configurables** (critique, bas stock, rupture)
- **Alertes multi-niveaux** avec notifications WebSocket
- **Dashboard administrateur** avec actions rapides
- **Protection anti-spam** avec système de cooldown
- **Historique complet** des mouvements de stock

#### 📧 Système de Newsletters Professionnel
- **Campagnes email marketing** avec éditeur visuel
- **Gestion complète des abonnés** (filtres, actions en lot)
- **Statistiques détaillées** (ouvertures, clics, désabonnements)
- **Programmation d'envois** avec gestion des fuseaux horaires
- **Templates personnalisables** pour différents types de contenus

## 🔧 Prérequis

Avant d'installer FarmShop, assurez-vous d'avoir :

### Logiciels Requis
- **PHP 8.4+** avec extensions :
  - OpenSSL
  - PDO
  - Mbstring
  - Tokenizer
  - XML
  - Ctype
  - JSON
  - BCMath
  - Fileinfo
  - GD ou ImageMagick (pour les images)

- **Composer** (gestionnaire de dépendances PHP)
- **Node.js 18+** et **npm** (pour les assets frontend)
- **Serveur de base de données** :
  - MariaDB 10.3+ (recommandé)
  - ou MySQL 8.0+
- **Serveur web** (optionnel pour la production) :
  - Apache 2.4+
  - ou Nginx 1.18+

### Vérification PHP
```bash
php --version
php -m | grep -E "(pdo|mbstring|openssl|tokenizer|xml)"
```

### Installation des outils
```bash
# Windows (avec Chocolatey)
choco install php composer nodejs

# macOS (avec Homebrew)
brew install php composer node

# Ubuntu/Debian
sudo apt update
sudo apt install php8.4 php8.4-cli php8.4-common php8.4-mysql php8.4-mbstring php8.4-xml php8.4-curl php8.4-gd composer nodejs npm
```

## 📦 Installation

### 1. Cloner le Repository

```bash
# HTTPS
git clone https://github.com/Soufsi313/FarmShop.git

# SSH (si configuré)
git clone git@github.com:Soufsi313/FarmShop.git

# Aller dans le dossier
cd FarmShop
```

### 2. Installer les Dépendances PHP

```bash
# Installer les packages Laravel et dépendances
composer install

# Si vous rencontrez des erreurs de mémoire
composer install --no-dev --optimize-autoloader
```

### 3. Installer les Dépendances Frontend

```bash
# Installer les packages Node.js
npm install

# Alternative avec Yarn
yarn install
```

## ⚙️ Configuration

### 1. Configuration Environnement

```bash
# Copier le fichier d'environnement
cp .env.example .env

# Générer la clé d'application Laravel
php artisan key:generate
```

### 2. Configurer la Base de Données

Éditez le fichier `.env` avec vos paramètres de base de données :

```env
# Configuration base de données
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=farmshop
DB_USERNAME=votre_utilisateur
DB_PASSWORD=votre_mot_de_passe

# Configuration application
APP_NAME="FarmShop"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Configuration mail (optionnel pour les tests)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@farmshop.local"
MAIL_FROM_NAME="${APP_NAME}"

# Configuration Stripe (BETA)
STRIPE_KEY=pk_test_votre_cle_publique_stripe
STRIPE_SECRET=sk_test_votre_cle_secrete_stripe
STRIPE_WEBHOOK_SECRET=whsec_votre_secret_webhook_stripe

# Gestion des Stocks (BETA)
STOCK_ALERTS_ENABLED=true
STOCK_ALERT_COOLDOWN=1800
DEFAULT_CRITICAL_THRESHOLD=5
DEFAULT_LOW_STOCK_THRESHOLD=10

# Newsletter System (BETA)
NEWSLETTER_FROM_EMAIL=newsletter@farmshop.local
NEWSLETTER_FROM_NAME="FarmShop Newsletter"
```
```

### 3. Créer la Base de Données

```sql
-- MySQL/MariaDB
CREATE DATABASE farmshop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Créer un utilisateur dédié (recommandé)
CREATE USER 'farmshop_user'@'localhost' IDENTIFIED BY 'mot_de_passe_securise';
GRANT ALL PRIVILEGES ON farmshop.* TO 'farmshop_user'@'localhost';
FLUSH PRIVILEGES;
```

## 🗃️ Base de Données

### 1. Exécuter les Migrations

```bash
# Créer les tables
php artisan migrate

# Ou forcer en cas de problème
php artisan migrate --force
```

### 2. Charger les Données de Test

```bash
# Charger toutes les données de test (recommandé)
php artisan db:seed

# Ou charger des seeders spécifiques
php artisan db:seed --class=DatabaseSeeder
```

**Les données de test incluent :**
- 101 utilisateurs (dont administrateurs)
- 159 produits biologiques réalistes
- 11 catégories organisées
- Messages et commandes de démonstration

### 3. Optimiser la Base de Données (optionnel)

```bash
# Optimiser les performances
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🚀 Démarrage

### 1. Compiler les Assets

```bash
# Pour le développement (avec watch)
npm run dev

# Pour la production
npm run build
```

### 2. Lancer le Serveur de Développement

```bash
# Démarrer le serveur Laravel (port 8000 par défaut)
php artisan serve

# Ou spécifier un port personnalisé
php artisan serve --port=8080
```

### 3. Accéder à l'Application

🌐 **Frontend :** http://localhost:8000

- **Accueil :** `/`
- **Produits :** `/products`
- **Locations :** `/rentals`
- **Connexion :** `/login`
- **Inscription :** `/register`

🔧 **Administration :** http://localhost:8000/admin

## 👤 Comptes de Test

### Administrateur
```
Email: admin@farmshop.be
Mot de passe: password
Accès: Dashboard admin complet
```

### Utilisateur Standard
```
Email: user@farmshop.be
Mot de passe: password
Accès: Interface utilisateur
```

### Autres Utilisateurs
Les seeders créent 100+ utilisateurs de test avec des emails de format :
- `user1@example.com` à `user100@example.com`
- Mot de passe : `password`

## 📁 Structure du Projet

```
FarmShop/
├── app/
│   ├── Http/Controllers/
│   │   ├── Auth/              # Authentification
│   │   ├── Admin/             # Interface admin
│   │   ├── Web/               # Interface publique
│   │   └── RentalController.php
│   ├── Models/                # Modèles Eloquent
│   ├── Mail/                  # Classes d'email
│   └── Providers/             # Service providers
├── database/
│   ├── migrations/            # Structure base de données
│   ├── seeders/              # Données de test
│   └── factories/            # Factories pour les tests
├── resources/
│   ├── views/
│   │   ├── layouts/          # Templates de base
│   │   ├── web/              # Pages publiques
│   │   ├── admin/            # Interface admin
│   │   └── auth/             # Pages d'authentification
│   ├── css/                  # Styles Tailwind
│   └── js/                   # JavaScript (Alpine.js)
├── routes/
│   ├── web.php               # Routes web
│   └── api.php               # Routes API
├── public/
│   ├── storage/              # Images uploadées
│   └── index.php             # Point d'entrée
└── storage/
    ├── app/public/           # Stockage public
    └── logs/                 # Logs d'application
```

## 🔌 API Documentation

### Endpoints Principaux

#### Produits
```http
GET /api/products              # Liste des produits
GET /api/products/{id}         # Détail d'un produit
POST /api/products/{id}/add-to-cart  # Ajouter au panier
```

#### Locations
```http
GET /api/rentals/{product}/constraints     # Contraintes de location
POST /api/rentals/{product}/calculate-cost # Calcul du coût
```

#### Administration
```http
GET /api/admin/products        # Gestion produits
GET /api/admin/orders          # Gestion commandes
GET /api/admin/statistics      # Statistiques
```

### Authentification API

Les endpoints API utilisent la session Laravel pour l'authentification. Incluez le token CSRF :

```javascript
fetch('/api/products', {
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    }
})
```

## 🛠️ Troubleshooting

### Problèmes Courants

#### 1. Erreur "500 Internal Server Error"
```bash
# Vérifier les logs
tail -f storage/logs/laravel.log

# Vérifier les permissions
chmod -R 755 storage bootstrap/cache
```

#### 2. Assets non compilés
```bash
# Supprimer node_modules et réinstaller
rm -rf node_modules package-lock.json
npm install
npm run dev
```

#### 3. Erreur de base de données
```bash
# Vérifier la configuration
php artisan config:clear
php artisan cache:clear

# Re-créer la base de données
php artisan migrate:fresh --seed
```

#### 4. Problèmes de permissions (Linux/macOS)
```bash
# Donner les bonnes permissions
sudo chown -R $USER:www-data storage
sudo chown -R $USER:www-data bootstrap/cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

#### 5. Extensions PHP manquantes
```bash
# Ubuntu/Debian
sudo apt install php8.4-{bcmath,ctype,fileinfo,json,mbstring,openssl,pdo,tokenizer,xml}

# CentOS/RHEL
sudo yum install php-{bcmath,ctype,fileinfo,json,mbstring,openssl,pdo,tokenizer,xml}
```

### Commandes Utiles de Debug

```bash
# Vider tous les caches
php artisan optimize:clear

# Régénérer l'autoloader
composer dump-autoload

# Vérifier la configuration
php artisan config:show

# Tester la connexion base de données
php artisan tinker
>>> DB::connection()->getPdo();
```

### Support

Si vous rencontrez des problèmes :

1. 📖 Consultez les logs : `storage/logs/laravel.log`
2. � Vérifiez la documentation Laravel : https://laravel.com/docs
3. 🐛 Ouvrez une issue sur GitHub : https://github.com/Soufsi313/FarmShop/issues

---

## 📄 License

Ce projet est open-source sous licence [MIT](https://opensource.org/licenses/MIT).

---

**Développé avec ❤️ pour l'agriculture biologique belge**

*Projet personnel de développement web - FarmShop v1.0.0-alpha*
