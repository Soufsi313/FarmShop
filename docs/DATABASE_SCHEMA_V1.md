# 08 - Schéma de Base de Données - FarmShop

**Communauté Française de Belgique**  
Institut des Carrières Commerciales  
Ville de Bruxelles  
Rue de la Fontaine 4  
1000 BRUXELLES  

---

**Épreuve intégrée réalisée en vue de l'obtention du titre de**  
**« Bachelier en Informatique de gestion, orientation développement d'applications »**

**MEFTAH Soufiane**  
**2024 – 2025**

---

## 📋 DESCRIPTION DU SCHÉMA

**FarmShop** est une plateforme e-commerce spécialisée dans la **location d'équipements agricoles**. Le système gère un processus de location complet avec gestion des **cautions**, **pénalités de retard**, **inspections post-location**, et **réservations temporaires** avec conversion automatique panier → commande → location active.

---

## 👥 RELATIONS UTILISATEURS

### 🔑 Relations principales
- **Un utilisateur peut créer plusieurs équipements** - Un équipement appartient à un utilisateur **[1-*]**
- **Un utilisateur peut passer plusieurs commandes de location** - Une commande de location appartient à un utilisateur **[1-*]**
- **Un utilisateur peut avoir plusieurs locations actives** - Une location appartient à un utilisateur **[1-*]**
- **Un utilisateur possède un panier de location** - Un panier appartient à un utilisateur **[1-1]**
- **Un utilisateur peut avoir plusieurs inspections** - Une inspection appartient à un utilisateur **[1-*]**

### 💝 Relations interactions
- **Un utilisateur peut contacter le support** - Un contact appartient à un utilisateur **[1-*]**
- **Un utilisateur peut aimer plusieurs équipements** - Un équipement peut être aimé par plusieurs utilisateurs **[*-*]**
- **Un utilisateur peut avoir plusieurs équipements en favoris** - Un équipement peut être dans plusieurs listes de favoris **[*-*]**

---

## 🚜 RELATIONS ÉQUIPEMENTS & CATÉGORIES

### 📦 Structure des équipements
- **Une catégorie contient plusieurs équipements** - Un équipement appartient à une catégorie **[1-*]**
- **Un équipement peut avoir plusieurs images** - Une image appartient à un équipement **[1-*]**
- **Un équipement peut avoir plusieurs contraintes de location** - Une contrainte appartient à un équipement **[1-*]**
- **Un équipement a un stock défini** - Un stock appartient à un équipement **[1-1]**

### 🔄 Interactions utilisateurs-équipements
- **Un équipement peut être aimé par plusieurs utilisateurs** - Un utilisateur peut aimer plusieurs équipements **[*-*]**
- **Un équipement peut être dans plusieurs listes de favoris** - Un utilisateur peut avoir plusieurs équipements en favoris **[*-*]**
- **Un équipement peut être ajouté dans plusieurs paniers** - Un article de panier référence un équipement **[1-*]**

---

## 🛒 RELATIONS PANIER & ARTICLES

### 📋 Panier de location
- **Un panier contient plusieurs articles de location** - Un article de location appartient à un panier **[1-*]**
- **Un utilisateur possède un panier de location** - Un panier appartient à un utilisateur **[1-1]**
- **Un équipement peut être dans plusieurs paniers** - Un article de panier référence un équipement **[1-*]**
- **Un article de panier a une période définie** - Une période appartient à un article de panier **[1-1]**

### ⏰ Gestion des réservations temporaires
- **Un article de panier peut réserver temporairement du stock** - Une réservation temporaire appartient à un article **[1-1]**
- **Une réservation temporaire a un timeout** - Un timeout appartient à une réservation **[1-1]**

---

## 📄 RELATIONS COMMANDES & VALIDATION

### 💰 Commandes de location
- **Une commande contient plusieurs articles de location** - Un article de commande appartient à une commande **[1-*]**
- **Un équipement peut être commandé plusieurs fois** - Un article de commande référence un équipement **[1-*]**
- **Une commande a un paiement associé** - Un paiement appartient à une commande **[1-1]**
- **Une commande a des détails de facturation** - Des détails de facturation appartiennent à une commande **[1-1]**

### 🔍 Validation et contraintes
- **Une commande doit respecter les contraintes métier** - Une validation appartient à une commande **[1-*]**
- **Un article de commande doit respecter les contraintes d'équipement** - Une contrainte peut bloquer un article **[1-*]**

---

## 🏠 RELATIONS LOCATIONS & GESTION

### 📅 Gestion des locations actives
- **Une location contient plusieurs articles** - Un article de location appartient à une location **[1-*]**
- **Un équipement peut être loué dans plusieurs locations** - Un article de location référence un équipement **[1-*]**
- **Une location a une période définie** - Une période appartient à une location **[1-1]**
- **Une location a un état de suivi** - Un état appartient à une location **[1-1]**

### 💸 Système de cautions et pénalités
- **Une location a une caution versée** - Une caution appartient à une location **[1-1]**
- **Un article de location peut générer plusieurs pénalités** - Une pénalité appartient à un article **[1-*]**
- **Une location peut avoir plusieurs pénalités de retard** - Une pénalité appartient à une location **[1-*]**
- **Une pénalité a un calcul automatique** - Un calcul appartient à une pénalité **[1-1]**

### 🔍 Inspections post-location
- **Une location a une inspection finale** - Une inspection appartient à une location **[1-1]**
- **Un article de location a un état d'inspection** - Un état d'inspection appartient à un article **[1-1]**
- **Une inspection peut révéler des dommages** - Des dommages appartiennent à une inspection **[1-*]**
- **Des dommages peuvent générer des frais** - Des frais appartiennent à des dommages **[1-*]**

---

## 🔄 RELATIONS DE CONVERSION (PROCESSUS MÉTIER)

### 🛒➡️📄 Transformation des paniers en commandes
- **Un panier se convertit en commande de location** - Une commande provient d'un panier **[1-1]**
- **Un article de panier se convertit en article de commande** - Un article de commande provient d'un article de panier **[1-1]**

### 📄➡️🏠 Transformation des commandes en locations
- **Une commande payée devient une location active** - Une location provient d'une commande **[1-1]**
- **Un article de commande devient un article de location** - Un article de location provient d'un article de commande **[1-1]**

### 🏠➡️✅ Finalisation des locations
- **Une location terminée génère une inspection** - Une inspection provient d'une location **[1-1]**
- **Une inspection validée libère la caution** - Une libération de caution provient d'une inspection **[1-1]**

---

## 💬 RELATIONS COMMUNICATION

### 📧 Messages et support
- **Un utilisateur peut envoyer plusieurs messages de support** - Un message appartient à un utilisateur **[1-*]**
- **Un message peut avoir plusieurs réponses** - Une réponse appartient à un message **[1-*]**
- **Un administrateur peut répondre à plusieurs messages** - Une réponse appartient à un administrateur **[1-*]**

### 📞 Gestion des contacts
- **Un administrateur peut être assigné à plusieurs contacts** - Un contact peut être assigné à un administrateur **[1-*]**
- **Un contact peut avoir un statut de traitement** - Un statut appartient à un contact **[1-1]**

---

## 📊 RELATIONS CONTENU

### 📝 Gestion du contenu (Administration)
- **Un administrateur peut créer plusieurs articles de blog** - Un article appartient à un administrateur **[1-*]**
- **Un administrateur peut créer plusieurs guides d'utilisation** - Un guide appartient à un administrateur **[1-*]**
- **Un administrateur peut créer plusieurs newsletters** - Une newsletter appartient à un administrateur **[1-*]**

### 💬 Interactions utilisateurs avec le contenu
- **Un article peut recevoir plusieurs commentaires** - Un commentaire appartient à un article **[1-*]**
- **Un utilisateur peut écrire plusieurs commentaires** - Un commentaire appartient à un utilisateur **[1-*]**
- **Un commentaire peut avoir plusieurs réponses** - Un commentaire peut répondre à un autre commentaire **[1-*]**

---

## 🔐 RELATIONS PERMISSIONS & SÉCURITÉ

### 👤 Système de rôles et permissions
- **Un utilisateur peut avoir plusieurs rôles** - Un rôle peut être attribué à plusieurs utilisateurs **[*-*]**
- **Un utilisateur peut avoir plusieurs permissions** - Une permission peut être attribuée à plusieurs utilisateurs **[*-*]**
- **Un rôle peut avoir plusieurs permissions** - Une permission peut appartenir à plusieurs rôles **[*-*]**

### 🍪 Gestion des cookies et consentements
- **Un utilisateur peut donner plusieurs consentements** - Un consentement appartient à un utilisateur **[1-*]**
- **Un cookie peut être consenti par plusieurs utilisateurs** - Un consentement peut concerner plusieurs cookies **[*-*]**

---

## 📋 TABLES PRINCIPALES IDENTIFIÉES

### 👥 Tables utilisateurs et authentification
- **`users`** (table principale des utilisateurs)
- **`roles`** (rôles du système)
- **`permissions`** (permissions du système)
- **`model_has_roles`** (table pivot utilisateurs-rôles)
- **`model_has_permissions`** (table pivot utilisateurs-permissions)
- **`role_has_permissions`** (table pivot rôles-permissions)

### 🚜 Tables équipements et catalogue
- **`categories`** (catégories d'équipements agricoles)
- **`equipments`** (équipements agricoles)
- **`equipment_images`** (images des équipements)
- **`equipment_constraints`** (contraintes de location par équipement)
- **`equipment_stock`** (gestion du stock)

### 🛒 Tables panier et réservations
- **`carts`** (paniers de location)
- **`cart_items`** (articles dans les paniers)
- **`temporary_reservations`** (réservations temporaires de stock)

### 📄 Tables commandes et paiements
- **`rental_orders`** (commandes de location)
- **`rental_order_items`** (articles des commandes de location)
- **`payments`** (paiements et cautions)
- **`billing_details`** (détails de facturation)

### 🏠 Tables locations actives
- **`active_rentals`** (locations en cours)
- **`rental_items`** (articles en location)
- **`rental_penalties`** (pénalités de retard)
- **`rental_deposits`** (cautions versées)

### 🔍 Tables inspections et états
- **`rental_inspections`** (inspections post-location)
- **`equipment_damages`** (dommages constatés)
- **`damage_costs`** (coûts des réparations)
- **`rental_states`** (états des locations)

### 💬 Tables communication
- **`support_messages`** (messages de support)
- **`message_replies`** (réponses aux messages)
- **`contacts`** (formulaires de contact)

### 📝 Tables contenu
- **`blog_articles`** (articles de blog)
- **`usage_guides`** (guides d'utilisation)
- **`newsletters`** (newsletters)
- **`newsletter_subscriptions`** (abonnements newsletter)

### 💝 Tables interactions
- **`equipment_likes`** (likes sur les équipements)
- **`user_favorites`** (listes de favoris)

### 🍪 Tables confidentialité
- **`cookies`** (définition des cookies)
- **`cookie_consents`** (consentements des utilisateurs)

---

## ⚙️ NOTES TECHNIQUES

### 📝 Conventions de nommage
- **Clés primaires** : `id` (BIGINT UNSIGNED)
- **Clés étrangères** : `{table}_id` (BIGINT UNSIGNED)
- **Timestamps** : `created_at`, `updated_at`
- **Soft deletes** : `deleted_at` (sur tables sensibles)
- **UUID** : pour les références publiques sensibles

### 🔗 Index et contraintes
- **Contraintes de clés étrangères** activées
- **Index sur les clés étrangères** pour optimiser les performances
- **Index composites** sur les relations many-to-many
- **Contraintes d'unicité** sur les couples critiques
- **Index sur les champs de recherche** fréquents

### 🚀 Particularités métier FarmShop
- **Système de location avec cautions** automatisées
- **Gestion des pénalités de retard** (10€/jour)
- **Conversion automatique** panier → commande → location
- **Inspections post-location** obligatoires
- **Réservations temporaires** avec timeout
- **Gestion des contraintes** par équipement (durée min/max, saisonnalité)
- **Système de notifications** en temps réel
- **Calcul automatique** des frais et remboursements

---

**© 2024-2025 - MEFTAH Soufiane - Institut des Carrières Commerciales**
