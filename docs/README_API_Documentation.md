# Documentation API FarmShop - Livrable 15

## Vue d'ensemble

Ce document présente la documentation complète de l'API FarmShop, comprenant :
- **540+ endpoints** couvrant toutes les fonctionnalités
- **Documentation interactive Swagger/OpenAPI** accessible en ligne
- **Document Word professionnel** suivant les standards académiques
- **Schémas de données complets** avec exemples

## Accès à la documentation

### 1. Documentation interactive Swagger UI

La documentation Swagger est accessible en temps réel à l'adresse :
```
http://127.0.0.1:8001/api/documentation
```

**Fonctionnalités disponibles :**
- Interface graphique pour explorer tous les endpoints
- Tests interactifs directs depuis le navigateur
- Exemples de requêtes et réponses automatiques
- Validation en temps reel des paramètres
- Export de la spécification OpenAPI (JSON/YAML)

### 2. Document Word professionnel

Le document Word complet est disponible dans :
```
docs/Livrable_15_Documentation_API_FarmShop.docx
```

**Contenu du document :**
- Architecture générale de l'API
- Standards et technologies utilisées
- Documentation détaillée par module
- Exemples d'intégration
- Schémas de données complets

## Structure de l'API

### Endpoints principaux

| Module | Base URL | Description |
|--------|----------|-------------|
| **Produits** | `/api/products` | Catalogue, recherche, gestion stocks |
| **Catégories** | `/api/categories` | Taxonomies produits et location |
| **Panier** | `/api/cart` | Gestion panier d'achat |
| **Location** | `/api/cart-location` | Panier de location avec contraintes |
| **Commandes** | `/api/orders` | Commandes d'achat et suivi |
| **Locations** | `/api/rental-orders` | Commandes de location et inspections |
| **Paiements** | `/api/stripe` | Système de paiement Stripe |
| **Utilisateurs** | `/api/profile` | Gestion profils et authentification |
| **Blog** | `/api/blog` | Articles, catégories, commentaires |
| **Messages** | `/api/messages` | Système de messagerie |
| **Administration** | `/api/admin` | Interface d'administration |

### Authentification

L'API utilise un système d'authentification hybride :

**1. Session Web (auth:web)**
- Authentification par sessions Laravel
- Protection CSRF automatique
- Idéal pour interfaces web

**2. Bearer Token (Sanctum)**
- Tokens API pour applications mobiles
- Support SPA (Single Page Applications)
- Gestion des portées et permissions

### Niveaux d'autorisation

- **Visiteur** : Accès lecture seule aux ressources publiques
- **Utilisateur** : Gestion profil, commandes, panier, wishlist
- **Administrateur** : Accès complet gestion plateforme

## Modules de l'API

### 1. Gestion des Produits
- **CRUD complet** avec différentiation selon permissions
- **Recherche avancée** avec filtres et tri
- **Gestion des stocks** vente et location séparés
- **Vérification disponibilité** en temps réel

### 2. Système de Panier
- **Panier d'achat** classique avec calculs automatiques
- **Panier de location** avec contraintes temporelles
- **Vérification stocks** et disponibilités
- **Calculs frais** de livraison et taxes

### 3. Commandes et Location
- **Workflow complet** de commandes d'achat
- **Gestion des locations** avec dates et inspections
- **Suivi des statuts** automatisé
- **Calcul des pénalités** et cautions

### 4. Paiements Stripe
- **Création paiements** sécurisés
- **Gestion cautions** pour locations
- **Webhooks** de synchronisation
- **Remboursements** automatisés

### 5. Blog et Contenu
- **Articles de blog** avec catégories
- **Système de commentaires** et modération
- **Signalements** et gestion communautaire
- **Statistiques** et analytics

### 6. Administration
- **Gestion utilisateurs** et rôles
- **Statistiques détaillées** et rapports
- **Configuration** système
- **Monitoring** et métriques

## Génération de la documentation

### Régénération Swagger
```bash
php artisan l5-swagger:generate
```

### Génération document Word
```bash
php docs/generate_livrable15_api.php
```

### Démarrage serveur de développement
```bash
php artisan serve --port=8001
```

## Standards respectés

- **OpenAPI 3.0** pour la documentation interactive
- **Architecture RESTful** avec ressources hiérarchiques
- **Codes de statut HTTP** standards
- **Format JSON** pour toutes les réponses
- **Pagination** standardisée
- **Gestion d'erreurs** cohérente

## Conformité et sécurité

- **Authentification** multi-niveaux
- **Autorisation** granulaire par endpoint
- **Validation** automatique des données
- **Protection CSRF** pour sessions web
- **Conformité GDPR** avec gestion des cookies
- **Audit** et traçabilité des actions

## Support et maintenance

La documentation est générée automatiquement depuis les annotations du code source, garantissant :
- **Synchronisation** documentation/implémentation
- **Mise à jour** automatique des exemples
- **Validation** des schémas de données
- **Tests** intégrés via Swagger UI

---

**Auteur :** MEFTAH Soufiane  
**Projet :** FarmShop - Plateforme e-commerce agricole  
**Année académique :** 2024-2025  
**Livrable :** 15 - Documentation API complète
