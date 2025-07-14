# 🌱 FarmShop - Plateforme E-commerce Agricole

**Application web Laravel pour la vente et location de produits agricoles biologiques**

![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.4+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MariaDB](https://img.shields.io/badge/MariaDB-11.5+-003545?style=for-the-badge&logo=mariadb&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)

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

**Version actuelle :** Alpha v1.0.0 (MVP fonctionnel)

## ✨ Fonctionnalités

### 🔐 Authentification
- Inscription et connexion sécurisée
- Gestion de profil utilisateur
- Suppression de compte (RGPD)
- Export des données personnelles

### 🛒 E-commerce
- Catalogue produits avec filtres avancés
- Séparation achat/location
- Panier d'achats
- Système de commandes

### 👨‍💼 Administration
- Dashboard avec statistiques
- Gestion produits (CRUD complet)
- Gestion des commandes
- Système de messages
- Gestion des catégories

### 🎨 Interface
- Design responsive (mobile-first)
- Navigation intuitive
- Animations fluides
- Thème moderne et professionnel

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
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@farmshop.local"
MAIL_FROM_NAME="${APP_NAME}"
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

## 🤝 Contribution

Les contributions sont les bienvenues ! N'hésitez pas à :
- Ouvrir des issues pour signaler des bugs
- Proposer des améliorations
- Soumettre des pull requests

---

**Développé avec ❤️ pour l'agriculture biologique belge**

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
