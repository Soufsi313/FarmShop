# 🌱 FarmShop - Plateforme E-commerce Agricole

**Application web Laravel pour la vente et location de produits agricoles biologiques**

![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.4+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MariaDB](https://img.shields.io/badge/MariaDB-11.5+-003545?style=for-the-badge&logo=mariadb&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)

## 🚀 Installation Rapide

```bash
# 1. Cloner le projet
git clone https://github.com/Soufsi313/FarmShop.git
cd FarmShop

# 2. Installer les dépendances
composer install
npm install

# 3. Configuration
cp .env.example .env
php artisan key:generate

# 4. Base de données (créer d'abord la DB MySQL/MariaDB)
php artisan migrate --seed

# 5. Compiler les assets et démarrer
npm run dev
php artisan serve
```

**🎯 Accès rapide :** http://localhost:8000  
**👤 Admin :** admin@farmshop.local / password  

---

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

**Version actuelle :** BETA v1.0.0-beta (Fonctionnalités e-commerce complètes)

## ✨ Fonctionnalités

### 🏷️ Version ALPHA v1.0.0 (MVP Fonctionnel)
- **🔐 Authentification** : Inscription, connexion, gestion profil
- **🛒 Catalogue produits** : 159 produits avec filtres avancés
- **👨‍💼 Interface admin** : Dashboard, gestion produits, messages
- **🎨 Design moderne** : Responsive, Tailwind CSS, animations

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
- **Accueil :** `/` - Page d'accueil avec carrousel de produits
- **Produits :** `/products` - Catalogue des produits à l'achat
- **Locations :** `/rentals` - Équipements à louer
- **Connexion :** `/login` - Authentification utilisateur
- **Inscription :** `/register` - Création de compte

🔧 **Administration :** http://localhost:8000/admin
- **Dashboard :** Statistiques et alertes de stock
- **Produits :** Gestion complète du catalogue
- **Commandes :** Suivi des ventes et locations
- **Utilisateurs :** Administration des comptes

## 🎯 Premiers Pas

1. **Connectez-vous en admin :** `admin@farmshop.be` / `password`
2. **Explorez le dashboard :** Vérifiez les notifications de stock
3. **Testez les achats :** Créez un compte utilisateur et passez commande
4. **Configurez les emails :** Modifiez `.env` pour les notifications
5. **Personnalisez :** Ajoutez vos propres produits via l'admin

## 👤 Comptes de Test

### 🔑 Administrateur Principal
```
Email: admin@farmshop.be
Mot de passe: password
Rôle: Super Admin
Accès: Dashboard complet, gestion des produits, commandes, utilisateurs
```

### 🛒 Utilisateur Client
```
Email: user@farmshop.be
Mot de passe: password
Rôle: Client
Accès: Achats, locations, wishlist, profil
```

### 📊 Données de Test Incluses
- **159 produits** réalistes (légumes, outils, équipements)
- **11 catégories** organisées (Légumes, Machines, Équipements, etc.)
- **100+ utilisateurs** générés automatiquement
- **Commandes d'exemple** pour tester les workflows
- **Notifications de stock** pré-configurées

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

## 📋 Informations du Projet

**Version :** v1.2.0 (Version Finale)  
**Laravel :** 11.45.1  
**PHP :** 8.4+  
**Base de données :** MySQL/MariaDB  
**Frontend :** Tailwind CSS, Alpine.js  

## 🤝 Contribution

Ce projet est développé comme application de démonstration. Les contributions sont les bienvenues !

---

**🌱 FarmShop - Cultiver l'avenir du commerce agricole**

*Développé avec ❤️ pour l'agriculture biologique belge*
