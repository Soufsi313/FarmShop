# 📊 Livrable 08 - Schéma de Base de Données

<div style="font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;">

---

## 📋 Informations du Projet

**Communauté Française de Belgique**  
**Institut des Carrières Commerciales**  
**Ville de Bruxelles**  
**Rue de la Fontaine 4**  
**1000 BRUXELLES**

---

**Épreuve intégrée réalisée en vue de l'obtention du titre de « Bachelier en Informatique de gestion, orientation développement d'applications ».**

**MEFTAH Soufiane**  
**2024 – 2025**

---

## 🎯 Description du Schéma

**FarmShop** est une plateforme e-commerce innovante spécialisée dans la **vente et la location d'équipements agricoles**. Le système gère un double flux métier : les **achats traditionnels** et un **système de location unique** avec gestion sophistiquée des cautions, pénalités et contraintes temporelles.

---

## 👥 Relations Utilisateurs

### 🔗 Relations Principales

<div style="background-color: #f8f9fa; padding: 15px; border-left: 4px solid #007bff; margin: 10px 0;">

- **Un utilisateur peut créer plusieurs produits** - Un produit appartient à un utilisateur **[1-*]**
- **Un utilisateur peut passer plusieurs commandes** - Une commande appartient à un utilisateur **[1-*]**
- **Un utilisateur peut avoir plusieurs commandes de location** - Une commande de location appartient à un utilisateur **[1-*]**
- **Un utilisateur peut avoir plusieurs locations actives** - Une location appartient à un utilisateur **[1-*]**
- **Un utilisateur possède un panier d'achat** - Un panier appartient à un utilisateur **[1-1]**
- **Un utilisateur peut avoir plusieurs paniers de location** - Un panier de location appartient à un utilisateur **[1-*]**

</div>

### 🤝 Relations Interactions

<div style="background-color: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 10px 0;">

- **Un utilisateur peut envoyer plusieurs contacts** - Un contact appartient à un utilisateur **[1-*]**
- **Un utilisateur peut aimer plusieurs produits** - Un produit peut être aimé par plusieurs utilisateurs **[*-*]**
- **Un utilisateur peut avoir plusieurs produits en wishlist** - Un produit peut être dans plusieurs wishlists **[*-*]**

</div>

---

## 📦 Relations Produits & Catégories

### 🏗️ Structure des Produits

<div style="background-color: #d4edda; padding: 15px; border-left: 4px solid #28a745; margin: 10px 0;">

- **Une catégorie contient plusieurs produits** - Un produit appartient à une catégorie **[1-*]**
- **Un produit peut avoir plusieurs images** - Une image appartient à un produit **[1-*]**
- **Un produit peut avoir plusieurs offres spéciales** - Une offre spéciale appartient à un produit **[1-*]**

</div>

### 🔄 Interactions Utilisateurs-Produits

<div style="background-color: #d1ecf1; padding: 15px; border-left: 4px solid #17a2b8; margin: 10px 0;">

- **Un produit peut être aimé par plusieurs utilisateurs** - Un utilisateur peut aimer plusieurs produits **[*-*]**
- **Un produit peut être dans plusieurs wishlists** - Un utilisateur peut avoir plusieurs produits en wishlist **[*-*]**
- **Un produit peut être ajouté dans plusieurs paniers** - Un article de panier référence un produit **[1-*]**
- **Un produit peut être loué dans plusieurs paniers de location** - Un article de location référence un produit **[1-*]**

</div>

---

## 🛒 Relations Panier & Articles

### 🛍️ Panier d'Achat Classique

<div style="background-color: #f8d7da; padding: 15px; border-left: 4px solid #dc3545; margin: 10px 0;">

- **Un panier contient plusieurs articles** - Un article de panier appartient à un panier **[1-*]**
- **Un utilisateur peut avoir plusieurs articles dans son panier** - Un article de panier appartient à un utilisateur **[1-*]**
- **Un produit peut être dans plusieurs paniers** - Un article de panier référence un produit **[1-*]**

</div>

### 🚜 Panier de Location

<div style="background-color: #e2e3e5; padding: 15px; border-left: 4px solid #6c757d; margin: 10px 0;">

- **Un panier de location contient plusieurs articles de location** - Un article de location appartient à un panier de location **[1-*]**
- **Un produit peut être dans plusieurs paniers de location** - Un article de location référence un produit **[1-*]**

</div>

---

## 📋 Relations Commandes & Articles

### 💳 Commandes d'Achat

<div style="background-color: #f8f9fa; padding: 15px; border-left: 4px solid #007bff; margin: 10px 0;">

- **Une commande contient plusieurs articles** - Un article de commande appartient à une commande **[1-*]**
- **Un produit peut être commandé plusieurs fois** - Un article de commande référence un produit **[1-*]**
- **Un article de commande peut avoir un retour** - Un retour appartient à un article de commande **[1-1]**
- **Une commande peut avoir plusieurs retours** - Un retour appartient à une commande **[1-*]**

</div>

### 📅 Commandes de Location

<div style="background-color: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 10px 0;">

- **Une commande de location contient plusieurs articles** - Un article de location appartient à une commande de location **[1-*]**
- **Un produit peut être loué plusieurs fois** - Un article de location référence un produit **[1-*]**

</div>

---

## 🏠 Relations Locations & Gestion

### 🔄 Gestion des Locations Actives

<div style="background-color: #d4edda; padding: 15px; border-left: 4px solid #28a745; margin: 10px 0;">

- **Une location contient plusieurs articles** - Un article de location appartient à une location **[1-*]**
- **Un produit peut être loué dans plusieurs locations** - Un article de location référence un produit **[1-*]**

</div>

### ⚠️ Système de Pénalités

<div style="background-color: #f8d7da; padding: 15px; border-left: 4px solid #dc3545; margin: 10px 0;">

- **Un article de location peut générer plusieurs pénalités** - Une pénalité appartient à un article de location **[1-*]**
- **Une location peut avoir plusieurs pénalités** - Une pénalité appartient à une location **[1-*]**

</div>

---

## 🔄 Relations de Conversion (Processus Métier)

### 🔁 Transformation des Paniers en Commandes

<div style="background-color: #d1ecf1; padding: 15px; border-left: 4px solid #17a2b8; margin: 10px 0;">

- **Un panier de location se convertit en commande de location** - Une commande de location provient d'un panier de location **[1-1]**
- **Un article de panier de location se convertit en article de commande de location** - Un article de commande de location provient d'un article de panier **[1-1]**

</div>

### 🎯 Transformation des Commandes en Locations

<div style="background-color: #e2e3e5; padding: 15px; border-left: 4px solid #6c757d; margin: 10px 0;">

- **Une commande de location devient une location** - Une location provient d'une commande de location **[1-1]**

</div>

---

## 💬 Relations Communication

### 📧 Messages et Support

<div style="background-color: #f8f9fa; padding: 15px; border-left: 4px solid #007bff; margin: 10px 0;">

- **Un utilisateur peut envoyer plusieurs messages admin** - Un message admin appartient à un utilisateur **[1-*]**
- **Un message admin peut avoir plusieurs réponses** - Une réponse appartient à un message admin **[1-*]**
- **Un utilisateur peut écrire plusieurs réponses** - Une réponse appartient à un utilisateur **[1-*]**

</div>

### 📞 Gestion des Contacts

<div style="background-color: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 10px 0;">

- **Un admin peut être assigné à plusieurs contacts** - Un contact peut être assigné à un admin **[1-*]**

</div>

---

## 📝 Relations Contenu

### 📰 Gestion du Blog (Administration)

<div style="background-color: #d4edda; padding: 15px; border-left: 4px solid #28a745; margin: 10px 0;">

- **Un admin peut créer plusieurs articles de blog** - Un article de blog appartient à un admin **[1-*]**
- **Un admin peut créer plusieurs newsletters** - Une newsletter appartient à un admin **[1-*]**

</div>

### 💭 Interactions Utilisateurs avec le Contenu

<div style="background-color: #d1ecf1; padding: 15px; border-left: 4px solid #17a2b8; margin: 10px 0;">

- **Un blog peut recevoir plusieurs commentaires** - Un commentaire appartient à un blog **[1-*]**
- **Un utilisateur peut écrire plusieurs commentaires** - Un commentaire appartient à un utilisateur **[1-*]**
- **Un commentaire peut avoir plusieurs réponses** - Un commentaire peut répondre à un autre commentaire **[1-*]**

</div>

### 🛡️ Système de Modération

<div style="background-color: #f8d7da; padding: 15px; border-left: 4px solid #dc3545; margin: 10px 0;">

- **Un commentaire peut être signalé plusieurs fois** - Un signalement appartient à un commentaire **[1-*]**
- **Un utilisateur peut faire plusieurs signalements** - Un signalement appartient à un utilisateur **[1-*]**
- **Un admin peut traiter plusieurs signalements** - Un signalement peut être traité par un admin **[1-*]**

</div>

### 📨 Newsletter

<div style="background-color: #e2e3e5; padding: 15px; border-left: 4px solid #6c757d; margin: 10px 0;">

- **Un utilisateur peut avoir un abonnement newsletter** - Un abonnement newsletter appartient à un utilisateur **[1-1]**

</div>

---

## 🔒 Relations Confidentialité

### 🍪 Gestion des Cookies et Consentements

<div style="background-color: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 10px 0;">

- **Un utilisateur peut donner plusieurs consentements de cookies** - Un consentement appartient à un utilisateur **[1-*]**
- **Un cookie peut être consenti par plusieurs utilisateurs** - Un consentement peut concerner plusieurs cookies **[*-*]**

</div>

---

## 🔐 Relations Permissions (Spatie Laravel-Permission)

### 👤 Système de Rôles et Permissions

<div style="background-color: #f8d7da; padding: 15px; border-left: 4px solid #dc3545; margin: 10px 0;">

- **Un utilisateur peut avoir plusieurs rôles** - Un rôle peut être attribué à plusieurs utilisateurs **[*-*]**
- **Un utilisateur peut avoir plusieurs permissions** - Une permission peut être attribuée à plusieurs utilisateurs **[*-*]**
- **Un rôle peut avoir plusieurs permissions** - Une permission peut appartenir à plusieurs rôles **[*-*]**

</div>

---

## 🗃️ Tables Principales Identifiées

### 👥 Tables Utilisateurs et Authentification

<div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 10px 0;">

- **`users`** (table principale des utilisateurs)
- **`roles`** (rôles du système)
- **`permissions`** (permissions du système)
- **`model_has_roles`** (table pivot utilisateurs-rôles)
- **`model_has_permissions`** (table pivot utilisateurs-permissions)
- **`role_has_permissions`** (table pivot rôles-permissions)

</div>

### 📦 Tables Produits et Catalogue

<div style="background-color: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0;">

- **`categories`** (catégories de produits)
- **`products`** (produits)
- **`product_images`** (images des produits)
- **`special_offers`** (offres spéciales)

</div>

### 🛒 Tables Panier et Navigation

<div style="background-color: #d1ecf1; padding: 15px; border-radius: 5px; margin: 10px 0;">

- **`carts`** (paniers d'achat)
- **`cart_items`** (articles dans les paniers)
- **`cart_locations`** (paniers de location)
- **`cart_item_locations`** (articles de location dans les paniers)

</div>

### 📋 Tables Commandes

<div style="background-color: #fff3cd; padding: 15px; border-radius: 5px; margin: 10px 0;">

- **`orders`** (commandes d'achat)
- **`order_items`** (articles des commandes)
- **`order_returns`** (retours de commandes)
- **`order_locations`** (commandes de location)
- **`order_item_locations`** (articles des commandes de location)

</div>

### 🏠 Tables Locations

<div style="background-color: #f8d7da; padding: 15px; border-radius: 5px; margin: 10px 0;">

- **`rentals`** (locations actives)
- **`rental_items`** (articles en location)
- **`rental_penalties`** (pénalités de location)

</div>

### 💬 Tables Communication

<div style="background-color: #e2e3e5; padding: 15px; border-radius: 5px; margin: 10px 0;">

- **`contacts`** (formulaires de contact)
- **`admin_messages`** (messages utilisateur vers admin)
- **`admin_message_replies`** (réponses aux messages)

</div>

### 📝 Tables Contenu

<div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 10px 0;">

- **`blogs`** (articles de blog)
- **`blog_comments`** (commentaires de blog)
- **`blog_comment_reports`** (signalements de commentaires)
- **`newsletters`** (newsletters)
- **`newsletter_subscriptions`** (abonnements newsletter)

</div>

### 🤝 Tables Interactions

<div style="background-color: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0;">

- **`product_likes`** (likes sur les produits)
- **`wishlists`** (listes de souhaits)

</div>

### 🔒 Tables Confidentialité

<div style="background-color: #fff3cd; padding: 15px; border-radius: 5px; margin: 10px 0;">

- **`cookies`** (définition des cookies)
- **`cookie_consents`** (consentements des utilisateurs)

</div>

---

## ⚙️ Notes Techniques

### 📋 Conventions de Nommage

<div style="background-color: #f8f9fa; padding: 15px; border-left: 4px solid #007bff; margin: 10px 0;">

- **Clés primaires** : `id` (BIGINT UNSIGNED)
- **Clés étrangères** : `{table}_id` (BIGINT UNSIGNED)
- **Timestamps** : `created_at`, `updated_at`
- **Soft deletes** : `deleted_at` (sur certaines tables sensibles)

</div>

### 🚀 Index et Contraintes

<div style="background-color: #d4edda; padding: 15px; border-left: 4px solid #28a745; margin: 10px 0;">

- Contraintes de clés étrangères activées
- Index sur les clés étrangères pour optimiser les performances
- Index composites sur les relations many-to-many
- Contraintes d'unicité sur les couples (user_id, product_id) pour likes et wishlists

</div>

### 🎯 Particularités Métier

<div style="background-color: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 10px 0;">

- **Système dual** achat/location avec des flux séparés
- **Gestion des cautions** pour les locations
- **Système de pénalités** automatisé (10€/jour de retard)
- **Conversion automatique** panier → commande → location
- **Gestion des retours** pour les achats
- **Système de notifications** intégrées

</div>

---

<div style="text-align: center; margin-top: 50px; padding: 20px; background-color: #f8f9fa; border-radius: 10px;">

**📊 Document généré le 15 juillet 2025**  
**🎓 MEFTAH Soufiane - Bachelier en Informatique de gestion**  
**🏛️ Institut des Carrières Commerciales - Bruxelles**

</div>

</div>
