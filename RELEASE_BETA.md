# FarmShop - Release v1.1.0

## Description du Projet

FarmShop est une application web dynamique de commerce électronique spécialisée dans la vente et location de produits agricoles biologiques. Cette release v1.1.0 apporte le système complet de location avec inspection et sanctions.

## Nouvelles Fonctionnalités v1.1.0

### 🏭 Système de Location Complet
- **Contraintes de location** : Durées min/max, jours disponibles, délais minimum
- **Calendrier intelligent** : Vérification disponibilité temps réel
- **Processus de retour** : Workflow automatisé pour retours matériel
- **Inspection matériel** : Système d'évaluation état des équipements retournés
- **Sanctions automatiques** : Calcul pénalités selon dommages et retards
- **Gestion dépôts** : Retenue/restitution automatique des cautions

### Interface d'Administration Professionnelle
- **Dashboard enrichi** : Calculs automatiques des dépôts de garantie
- **Recherche multicritères** : Filtres avancés pour commandes et locations  
- **Gestion des signalements** : Modération AJAX des commentaires blog

### Système de Cookies Conforme GDPR
- **Bannière intelligente** : Affichage contextuel selon statut utilisateur
- **5 catégories de cookies** : Nécessaires, Analytics, Marketing, Préférences, Social
- **Migration automatique** : Cookies visiteur → utilisateur connecté
- **Interface admin** : Historique complet et statistiques temps réel
- **Conformité totale** : Persistance, expiration, droits utilisateur

## Architecture Technique

- **Framework**: Laravel 11 LTS
- **Base de données**: MariaDB
- **Frontend**: Blade Templates + Tailwind CSS + Alpine.js
- **Paiements**: Stripe Integration complète
- **Notifications**: WebSocket temps réel
- **API**: RESTful Laravel

## Nouvelles Fonctionnalités Implémentées

### 1. Processus d'Achat Complet avec Stripe
- **Panier intelligent** : Gestion quantités, calculs TVA automatiques
- **Checkout sécurisé** : Formulaire d'adresses avec validation complète
- **Paiement Stripe** : Intégration native avec cartes, PayPal, virements
- **Webhooks sécurisés** : Gestion automatique des confirmations de paiement
- **Gestion d'états** : Progression automatique des commandes (pending → confirmed → preparing → shipped → delivered)
- **Suivi commandes** : Interface utilisateur avec historique complet
- **Gestion stock** : Décrément automatique après paiement confirmé
- **Annulation** : Système de remboursement avec restauration stock

### 2. Système de Gestion des Seuils de Stock
- **Surveillance automatique** : Observer Pattern pour monitoring temps réel
- **Seuils configurables** : Critique, bas stock, rupture par produit
- **Alertes multi-niveaux** : Notifications visuelles et email
- **Dashboard admin** : Interface de gestion des alertes avec actions rapides
- **Anti-spam** : Système de cooldown pour éviter les alertes redondantes
- **WebSocket** : Notifications temps réel pour les administrateurs
- **Historique** : Traçabilité complète des mouvements de stock

### 3. Système de Newsletters Avancé
- **Gestion campagnes** : Création, programmation, envoi de newsletters
- **Éditeur visuel** : Interface moderne pour création de contenu
- **Gestion abonnés** : Abonnement/désabonnement, filtres avancés
- **Statistiques** : Taux d'ouverture, clics, désabonnements
- **Templates** : Modèles prédéfinis personnalisables
- **Programmation** : Envoi différé avec gestion des fuseaux horaires

### 4. Système de Location avec Inspection et Sanctions
- **Contraintes intelligentes** : Système avancé de règles de location
  - Durées min/max configurables par produit (ex: 1-7 jours)
  - Jours disponibles personnalisables (ex: Lundi-Samedi uniquement)
  - Délai minimum configurable (ex: pas de location le jour même)
  - Validation automatique des périodes selon contraintes business

- **API de contraintes** : Services REST pour validation temps réel
  - `GET /api/rental-constraints/{product}` : Récupération contraintes
  - `POST /api/rental-constraints/{product}/validate` : Validation période
  - `GET /api/rental-constraints/{product}/calendar` : Calendrier disponibilité
  - `GET /api/rental-constraints/{product}/suggestions` : Suggestions dates optimales

- **Processus de retour matériel** : Workflow automatisé complet
  - Notification automatique fin de location
  - Interface utilisateur pour signaler retour
  - Changement d'état automatique vers "inspection_pending"
  - Alertes admin pour matériel en attente d'inspection

- **Système d'inspection professionnelle** :
  - Interface admin dédiée pour évaluation matériel retourné
  - Évaluation état : "excellent", "good", "fair", "poor", "damaged"
  - Notes d'inspection détaillées avec photos
  - Évaluation dommages : type, description, photos
  - Calcul automatique coût réparation/remplacement

- **Sanctions automatiques** : Algorithme de calcul des pénalités
  - **Retard** : Pénalité progressive (ex: 10% du prix/jour de retard)
  - **Dommages** : Calcul selon gravité et coût réparation
    - Dommages mineurs : 10-30% du dépôt
    - Dommages majeurs : 50-100% du dépôt
    - Remplacement : Retenue totale + coût supplémentaire
  - **Perte** : Facturation prix neuf + pénalité administrative

- **Gestion automatique des dépôts** :
  - Retenue automatique selon résultat inspection
  - Calcul restitution après déduction sanctions
  - Remboursement automatique via Stripe si applicable
  - Facturation supplémentaire si dépassement dépôt

- **Notifications et communication** :
  - Emails automatiques à chaque étape du processus
  - Templates personnalisés par type d'événement
  - Notifications admin pour actions requises
  - Historique complet des communications

- **Interface utilisateur "Mes Locations"** :
  - Suivi temps réel statut des locations
  - Historique complet avec détails inspections
  - Calcul sanctions et restitutions
  - Documents et factures téléchargeables

## Améliorations Techniques

### Performance et Sécurité
- **Queues Redis** : Traitement asynchrone des jobs lourds
- **Validation robuste** : Contrôles d'intégrité sur tous les formulaires
- **Gestion d'erreurs** : Logs structurés avec contexte métier
- **Rollback automatique** : En cas d'échec de transaction
- **CSRF Protection** : Sécurisation de tous les formulaires

### Architecture Observer
```php
// Surveillance automatique des stocks
class ProductStockObserver
{
    public function updated(Product $product)
    {
        $this->checkStockThresholds($product);
        $this->createStockAlerts($product);
        $this->broadcastStockUpdate($product);
    }
}
```

### Architecture Système de Location
```php
// Modèle principal de location avec contraintes
class OrderLocation extends Model
{
    public function validateRentalPeriod($startDate, $endDate)
    public function calculateTotalDeposit()
    public function markAsReturned()
    public function completeInspection($inspectionData)
    public function calculateSanctions($lateDays, $damageData)
    public function processDepositRefund()
}

// Validation des contraintes de location
class RentalDateValidation extends Rule
{
    public function passes($attribute, $value)
    {
        // Vérification jours disponibles
        // Validation durée min/max
        // Contrôle délai minimum
    }
}

// Service de gestion des retours
class RentalReturnService
{
    public function processReturn(OrderLocation $rental)
    public function conductInspection($rentalId, $inspectionData)
    public function calculatePenalties($rental, $inspectionResult)
    public function handleDepositRefund($rental, $refundAmount)
}
```

### Service Stripe Complet
```php
// Gestion complète des paiements
class StripeService
{
    public function createPaymentIntentForOrder(Order $order)
    public function handleWebhook($payload, $signature)
    public function handleSuccessfulPayment($paymentIntentId)
    public function processAutomaticRefund(Order $order)
}
```

## Interface Utilisateur

### Design Moderne
- **Tailwind CSS** : Framework CSS utility-first
- **Alpine.js** : Interactivité JavaScript légère
- **Responsive** : Optimisé mobile, tablette, desktop
- **Animations** : Transitions fluides et micro-interactions

### Dashboard Administrateur
- **Métriques temps réel** : Ventes, stocks, commandes
- **Actions en lot** : Gestion multiple de produits/commandes
- **Filtres avancés** : Recherche multicritères
- **Export données** : PDF, Excel pour rapports

## Tests et Validation

### Couverture Fonctionnelle
- **Tests d'intégration Stripe** : Paiements réussis/échoués
- **Tests Observer** : Surveillance stocks automatique
- **Tests Webhooks** : Réception et traitement Stripe
- **Tests Interface** : Parcours utilisateur complets
- **Tests Système Location** : Contraintes, inspection, sanctions
- **Tests Cookies GDPR** : Consentement, migration, persistance

### Scripts de Diagnostic
```bash
php test_complete_order_flow.php     # Test processus complet
php debug_stock_alerts.php           # Test alertes stock
php populate_cart.php                # Génération données test
php debug_rental_constraints.php     # Test contraintes location
php test_inspection_workflow.php     # Test workflow inspection
php debug_cookie_connexion.php       # Test système cookies
```

### Validation Système Location
```bash
# Test des contraintes de location
curl -X POST http://localhost:8000/api/rental-constraints/1/validate \
  -H "Content-Type: application/json" \
  -d '{"start_date":"2025-08-09","end_date":"2025-08-15"}'

# Test du processus d'inspection
php artisan rental:test-inspection 123 \
  --condition=good \
  --damage='{"type":"minor","description":"Rayure légère"}'

# Test calcul sanctions
php artisan rental:calculate-penalties 123 \
  --late-days=2 \
  --damage-cost=50.00
```

## Base de Données

### Nouvelles Tables
- `newsletter_sends` : Tracking envois newsletters
- `stock_alerts` : Historique alertes stock
- `payment_intents` : Suivi paiements Stripe
- `order_locations` : Commandes de location avec contraintes
- `order_item_locations` : Articles loués avec inspection détaillée
- `order_returns` : Retours matériel avec évaluation
- `cookies` : Gestion consentements GDPR avec migration utilisateur

### Tables de Location Enrichies
```sql
-- Table principale des locations
CREATE TABLE order_locations (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT,
    status ENUM('pending','confirmed','active','returned','completed','cancelled'),
    inspection_status ENUM('pending','completed'),
    start_date DATE,
    end_date DATE,
    total_deposit DECIMAL(10,2),
    late_penalty DECIMAL(10,2) DEFAULT 0,
    damage_penalty DECIMAL(10,2) DEFAULT 0,
    refund_amount DECIMAL(10,2),
    inspection_notes TEXT,
    inspection_completed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Articles loués avec inspection détaillée
CREATE TABLE order_item_locations (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    order_location_id BIGINT,
    product_id BIGINT,
    quantity INT,
    unit_price DECIMAL(10,2),
    deposit_per_item DECIMAL(10,2),
    return_condition ENUM('excellent','good','fair','poor','damaged'),
    damage_details JSON,
    item_inspection_notes TEXT,
    penalty_amount DECIMAL(10,2) DEFAULT 0
);

-- Contraintes de location par produit
ALTER TABLE products ADD COLUMN min_rental_days INT DEFAULT 1;
ALTER TABLE products ADD COLUMN max_rental_days INT DEFAULT 7;
ALTER TABLE products ADD COLUMN available_days JSON DEFAULT '[1,2,3,4,5,6]';
ALTER TABLE products ADD COLUMN rental_deposit DECIMAL(10,2);
```

### Migrations Ajoutées
```sql
-- Colonnes tracking newsletters
ALTER TABLE newsletter_sends ADD COLUMN opened_at TIMESTAMP NULL;
ALTER TABLE newsletter_sends ADD COLUMN clicked_at TIMESTAMP NULL;
ALTER TABLE newsletter_sends ADD COLUMN unsubscribed_at TIMESTAMP NULL;

-- Système de cookies GDPR
CREATE TABLE cookies (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NULL,
    session_id VARCHAR(255) NULL,
    ip_address VARCHAR(45),
    status ENUM('pending','accepted','rejected') DEFAULT 'pending',
    necessary BOOLEAN DEFAULT true,
    analytics BOOLEAN DEFAULT false,
    marketing BOOLEAN DEFAULT false,
    preferences BOOLEAN DEFAULT false,
    social_media BOOLEAN DEFAULT false,
    migrated_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Index performance
CREATE INDEX idx_products_stock_status ON products(quantity, critical_threshold);
CREATE INDEX idx_orders_payment_status ON orders(payment_status, status);
CREATE INDEX idx_order_locations_status ON order_locations(status, inspection_status);
CREATE INDEX idx_cookies_user_session ON cookies(user_id, session_id);
```

## Configuration Requise

### Variables d'Environnement
```env
# Stripe Configuration
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...

# Stock Management
STOCK_ALERTS_ENABLED=true
STOCK_ALERT_COOLDOWN=1800
DEFAULT_CRITICAL_THRESHOLD=5

# Newsletter System
MAIL_MAILER=smtp
NEWSLETTER_FROM_EMAIL=noreply@farmshop.com
```

### Nouvelles Dépendances
```json
{
    "stripe/stripe-php": "^10.0",
    "barryvdh/laravel-dompdf": "^2.0",
    "pusher/pusher-php-server": "^7.2"
}
```

## Installation et Migration

### Mise à Jour depuis Alpha
```bash
# Récupérer les changements
git pull origin main
git checkout v1.0.0-beta

# Installer dépendances
composer install
npm install && npm run build

# Migrer base de données
php artisan migrate
php artisan config:cache

# Démarrer les services
php artisan queue:work
php artisan serve
```

### Configuration Stripe
1. Créer compte Stripe (mode test)
2. Récupérer clés API et webhook secret
3. Configurer webhook endpoint : `/webhook/stripe`
4. Tester avec cartes de test Stripe

## Données de Test

### Comptes Utilisateurs
- **Admin** : `admin@farmshop.com` / `admin123`
- **Client** : `test@farmshop.com` / `password`

### Produits Stock Test
- **Produits critiques** : Stock ≤ 2 unités
- **Produits bas stock** : Stock ≤ 10 unités
- **Produits normaux** : Stock > 10 unités

### Cartes Test Stripe
- **Visa réussie** : `4242424242424242`
- **Visa échouée** : `4000000000000002`
- **Visa 3D Secure** : `4000002760003184`

## Prochaines Étapes (Roadmap)

### Version Stable (v1.0.0)
- Tests utilisateurs finaux
- Optimisations performance
- Documentation API complète
- Monitoring avancé

### Fonctionnalités Futures
- Intégration transporteurs
- Système de fidélité
- Marketing automation
- App mobile

---

**Version BETA - Build 2025.08.05**
*Prêt pour tests utilisateurs et feedback*
- Gestion avancée des quantités
- Calculs automatiques TVA selon type de produit (6% alimentaire, 21% non-alimentaire)
- Frais de livraison dynamiques
- Validation de stock en temps réel
- Sauvegarde persistante par utilisateur

#### Checkout Sécurisé
- Formulaire d'adresses avec validation
- Adresses de facturation et livraison séparées
- Méthodes de paiement multiples (Stripe, PayPal, Virement)
- Validation côté client et serveur
- Protection CSRF intégrée

#### Intégration Stripe Payment
- PaymentIntent automatique avec metadata complètes
- Webhooks sécurisés pour confirmation de paiement
- Gestion des erreurs et tentatives de paiement
- Support cartes test et production
- Remboursements automatiques intégrés

#### États de Commande Automatiques
- `pending` - Commande créée, en attente de paiement
- `confirmed` - Paiement validé, stock décrémenté
- `preparing` - En cours de préparation (transition auto 45min)
- `shipped` - Expédié avec date estimée (transition auto 15sec)
- `delivered` - Livré avec confirmation

#### Gestion du Stock Intelligente
- Réservation temporaire pendant checkout
- Décrément automatique après paiement confirmé
- Restauration en cas d'annulation ou échec
- Synchronisation avec alertes de stock

### 2. Système de Gestion des Seuils de Stock

#### Surveillance Automatique
- Observer Pattern pour monitoring temps réel
- Détection automatique des seuils critiques/bas
- Notifications instantanées via système de messages
- Protection anti-spam avec cooldown configurable

#### Alertes Multi-niveaux
- **Stock critique** (`critical_threshold`) - Alerte rouge urgente
- **Stock bas** (`low_stock_threshold`) - Alerte orange préventive  
- **Rupture de stock** (`quantity = 0`) - Alerte bloquante
- **Stock normal** - Indicateur vert de santé

#### Système de Notifications
- Messages automatiques avec détails produit
- WebSocket pour notifications temps réel
- Interface d'administration dédiée
- Historique et traçabilité complète

#### Dashboard Administration
- Vue d'ensemble des alertes actives
- Filtrage par niveau de criticité
- Actions rapides de réapprovisionnement
- Configuration des seuils par produit
- Métriques et statistiques de stock

### 3. Système de Newsletters Avancé

#### Gestion Complète des Campagnes
- Création avec éditeur WYSIWYG
- Programmation d'envois différés
- Suivi des ouvertures et clics
- Templates personnalisables
- Prévisualisation multi-format

#### Gestion des Abonnés
- Interface d'administration dédiée
- Abonnement/désabonnement en masse
- Filtrage et recherche avancée
- Export des listes d'abonnés
- Conformité RGPD intégrée

## Données de Test

La base de données contient des datafixtures complètes :

- **101 utilisateurs** avec profils variés
- **159 produits biologiques** avec seuils configurés
- **11 catégories** avec types alimentaires/non-alimentaires
- **Commandes de test** avec différents états
- **Messages d'alertes** de stock simulées
- **Newsletters** avec différents statuts

## Structure de Base de Données

### Tables Principales
- `orders` : Commandes avec états et paiements
- `order_items` : Détails des articles commandés
- `products` : Produits avec seuils de stock
- `messages` : Système de notifications unifié

**Relations optimisées** :
```sql
-- Indexes de performance
CREATE INDEX idx_products_stock_status ON products(quantity, critical_threshold, low_stock_threshold);
CREATE INDEX idx_orders_status_payment ON orders(status, payment_status);
CREATE INDEX idx_messages_type_created ON messages(type, created_at);
```

### 🔧 Services & Jobs
**Traitement asynchrone** :
- `ProcessOrderStatusProgression` : Progression automatique des commandes
- `ProcessStockAlerts` : Traitement des alertes de stock
- `SendStockNotification` : Envoi des notifications

**Services métier** :
- `StripeService` : Gestion complète des paiements
- `StockManagementService` : Logique de gestion des stocks
- `NotificationService` : Distribution des alertes

### 🚀 Performance & Scalabilité
**Optimisations** :
- **Queues Redis** : Traitement asynchrone des jobs
- **Cache intelligent** : Mise en cache des calculs de stock
- **Bulk updates** : Traitement par lots des alertes
- **Indexes stratégiques** : Requêtes optimisées

---

## 🧪 Tests & Validation

### ✅ Couverture Fonctionnelle
**Tests d'intégration Stripe** :
```php
class StripeIntegrationTest extends TestCase {
    public function test_successful_payment_flow() {
        // Test complet du workflow de paiement
    }
    
    public function test_stock_decrement_after_payment() {
        // Validation de la cohérence stock/paiement
    }
}
```

**Tests d'alertes de stock** :
```php
class StockAlertTest extends TestCase {
    public function test_critical_stock_alert_creation() {
        // Test des seuils critiques
    }
    
    public function test_alert_anti_spam_protection() {
        // Test de la protection anti-redondance
    }
}
```

### 🔍 Scripts de Diagnostic
**Outils de test inclus** :
- `test_complete_order_flow.php` : Test end-to-end des commandes
- `debug_stock_alerts.php` : Diagnostic des alertes de stock
- `populate_cart.php` : Génération de données de test
- `check_stock_and_cancellations.php` : Vérification de cohérence

---

## 🎨 Interface Utilisateur

### 💻 Design Responsive
**Framework CSS** : Tailwind CSS avec composants Alpine.js
**Compatibilité** : Desktop, Tablet, Mobile

### 🎭 Expérience Utilisateur
**Panier & Checkout** :
- Interface intuitive avec calculs temps réel
- Validation progressive des données
- États visuels clairs (loading, erreur, succès)

**Administration** :
- Dashboard moderne avec métriques
- Filtres et recherche avancée
- Actions en lot pour efficacité

### 🔔 Notifications
**Types de notifications** :
- Toast notifications pour actions immédiates
- Alertes persistantes pour informations critiques
- Emails automatiques pour confirmations

---

## 🚀 Déploiement & Configuration

### ⚙️ Variables d'Environnement
```env
# Configuration Stripe
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...

# Configuration Stock Alerts
STOCK_ALERTS_ENABLED=true
STOCK_ALERT_COOLDOWN=1800  # 30 minutes
DEFAULT_CRITICAL_THRESHOLD=5
DEFAULT_LOW_STOCK_THRESHOLD=10
```

### 📦 Dépendances
**Packages PHP** :
- `stripe/stripe-php` : Intégration paiements
- `barryvdh/laravel-dompdf` : Génération factures
- `pusher/pusher-php-server` : WebSocket temps réel

**Assets Frontend** :
- Alpine.js : Interactivité JavaScript
- Tailwind CSS : Framework CSS
- Stripe Elements : Interface de paiement sécurisée

---

## 📋 Prochaines Étapes

### 🎯 Roadmap Version Stable
1. **Tests utilisateurs** : Validation UX/UI
2. **Optimisations performance** : Cache Redis avancé
3. **Monitoring avancé** : Métriques business détaillées
4. **Intégrations externes** : APIs transporteurs, comptabilité

### 🔧 Améliorations Techniques
- Migration vers PostgreSQL pour performances
- Implémentation GraphQL pour API flexible
- Containerisation Docker pour déploiement
- CI/CD avec tests automatisés

---

## 📞 Support & Documentation

### 📚 Documentation Technique
- **API Documentation** : Endpoints REST complets
- **Database Schema** : Diagrammes et relations
- **Deployment Guide** : Instructions de mise en production

### 🛠️ Outils de Debug
- Logs structurés avec contexte métier
- Interface d'administration pour diagnostic
- Scripts de maintenance et vérification

---

**🎉 Cette version BETA démontre la maturité technique de FarmShop avec deux piliers essentiels d'un e-commerce professionnel : un système de paiement robuste et une gestion intelligente des stocks.**

*Version BETA - Build {{ date('Y.m.d') }}*
