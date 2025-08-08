# FarmShop - Version History

## v1.0.0-beta.2 (2025-08-08)

### 🆕 Nouvelles Fonctionnalités

#### Interface d'Administration Avancée
- **Dashboard Admin amélioré** : Calculs automatiques des dépôts de garantie
- **Recherche avancée** : Filtres multiples pour les commandes et locations
- **Gestion des signalements** : Interface AJAX pour modération des commentaires

#### Système de Consentement aux Cookies (GDPR)
- **Bannière de consentement** : Affichage intelligent selon l'état utilisateur
- **Gestion des préférences** : 5 types de cookies (Nécessaires, Analytics, Marketing, Préférences, Réseaux sociaux)
- **Migration automatique** : Cookies visiteur → utilisateur connecté
- **Interface d'administration** : Historique complet des consentements
- **Conformité GDPR** : Persistance, expiration et droits utilisateur

### 🔧 Améliorations Techniques

#### Backend
- **Modèle Cookie** : Relations utilisateur et tracking avancé
- **API REST** : Endpoints pour gestion des consentements
- **Migration de données** : Système de migration cookies visiteur/utilisateur
- **Validation** : Contrôles stricts des préférences utilisateur

#### Frontend
- **Alpine.js** : Gestion réactive des interfaces admin
- **AJAX pur** : Élimination des conflits formulaire/JavaScript
- **LocalStorage** : Synchronisation avec état d'authentification
- **UX améliorée** : Notifications en temps réel et indicateurs de chargement

### 🐛 Corrections

#### Base de Données
- **Colonnes SQL** : Correction `blog_comment_id` vs `comment_id`
- **Relations** : Optimisation des requêtes avec eager loading
- **Indexes** : Performance améliorée pour les requêtes admin

#### Authentification
- **Roles** : Résolution problème casse Admin/admin
- **Sessions** : Nettoyage automatique du localStorage
- **Permissions** : Contrôles d'accès renforcés

### 📊 Données et Analytics
- **Statistiques cookies** : Graphiques en temps réel
- **Historique détaillé** : Tracking IP, User-Agent, préférences
- **Export de données** : Conformité demandes GDPR

---

## v1.0.0-beta.1 (2025-08-05)

### Fonctionnalités initiales
- Système de paiement Stripe complet
- Gestion intelligente des stocks
- Interface d'administration de base

---

**Changelog complet disponible dans RELEASE_BETA.md**
