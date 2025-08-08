# Changelog - FarmShop

## [1.1.0-beta] - 2025-08-08

### Système de Location Complet
- **Contraintes de location intelligentes**
  - Durées min/max configurables par produit
  - Jours disponibles personnalisables (ex: Lundi-Samedi)
  - Délai minimum configurable
  - Validation automatique des périodes

- **API de contraintes REST**
  - GET /api/rental-constraints/{product} - Récupération contraintes
  - POST /api/rental-constraints/{product}/validate - Validation période
  - GET /api/rental-constraints/{product}/calendar - Calendrier disponibilité
  - GET /api/rental-constraints/{product}/suggestions - Suggestions dates

- **Processus de retour automatisé**
  - Notifications fin de location
  - Interface utilisateur signalement retour
  - Changement d'état automatique vers inspection
  - Alertes admin matériel en attente

- **Système d'inspection professionnel**
  - Interface admin évaluation matériel
  - États : excellent, good, fair, poor, damaged
  - Notes détaillées avec photos
  - Évaluation dommages avec coût réparation

- **Sanctions automatiques**
  - Pénalités retard progressives
  - Calcul dommages selon gravité
  - Retenue dépôt automatique
  - Facturation supplémentaire si nécessaire

- **Interface utilisateur "Mes Locations"**
  - Suivi temps réel statut locations
  - Historique avec détails inspections
  - Calcul sanctions et restitutions
  - Documents téléchargeables

### 🛡️ Ajouté - Système Cookies GDPR Complet
- **Bannière intelligente contextuelle**
  - Affichage selon statut utilisateur
  - Nettoyage localStorage sur connexion/déconnexion
  - Forçage nouvelle vérification état auth

- **5 catégories de cookies**
  - Nécessaires, Analytics, Marketing, Préférences, Social Media
  - Configuration granulaire par utilisateur
  - Persistance 365 jours avec renouvellement

- **Migration automatique visiteur → utilisateur**
  - Transfert cookies session vers compte utilisateur
  - Fenêtre migration 24h avec IP/session matching
  - Tracking migrated_at pour audit

- **Interface admin complète**
  - Historique détaillé tous consentements
  - Statistiques temps réel
  - Identification utilisateurs connectés
  - Actions de gestion (suppression, mise à jour)

### 🔧 Amélioré - Administration
- **Dashboard dépôts de garantie**
  - Calcul automatique montants dépôts
  - Attribut calculé getTotalDepositAttribute()
  - Affichage correct dans interface admin

- **Recherche multicritères avancée**
  - Filtres par statut, utilisateur, dates
  - Recherche textuelle sur numéros commande
  - Pagination optimisée

- **Gestion signalements AJAX**
  - Interface pure AJAX sans conflits formulaire
  - AdminBlogCommentController dédié
  - Actions : modérer, supprimer, rejeter
  - Corrections SQL blog_comment_id

### 🗄️ Base de Données
- **Nouvelles tables**
  - order_locations : Commandes location avec contraintes
  - order_item_locations : Articles loués avec inspection
  - order_returns : Retours avec évaluation
  - cookies : Consentements GDPR avec migration

- **Colonnes ajoutées**
  - products : min_rental_days, max_rental_days, available_days, rental_deposit
  - order_locations : inspection_status, inspection_notes, penalties
  - cookies : migrated_at pour tracking migration

### 🧪 Tests et Validation
- Scripts diagnostic système location
- Tests contraintes et workflow inspection
- Validation système cookies multi-états
- Tests API contraintes temps réel

### 🛠️ Corrections
- ✅ Signalements blog : Actions modérer/supprimer fonctionnelles
- ✅ Dépôts admin : Montants calculés correctement
- ✅ Cookies : Bannière apparaît pour tous utilisateurs
- ✅ localStorage : Nettoyage sur changements état auth

---

## [1.0.0-beta.2] - 2025-08-06

### 🛒 Ajouté - E-commerce Complet
- Système panier avec Stripe
- Gestion stock et alertes automatiques
- Newsletters avec statistiques
- Interface admin professionnelle

### 🔐 Sécurité
- Protection CSRF complète
- Validation robuste formulaires
- Gestion erreurs structurée

---

## [1.0.0-alpha] - 2025-08-01

### 🎯 Fondations
- Architecture Laravel 11 LTS
- Authentification utilisateur
- Catalogue produits de base
- Interface responsive Tailwind CSS

---

**Légende:**
- 🏭 Fonctionnalité majeure
- 🛡️ Sécurité/Conformité
- 🔧 Amélioration
- 🗄️ Base de données
- 🧪 Tests
- 🛠️ Correction de bug
