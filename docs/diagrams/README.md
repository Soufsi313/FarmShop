# 📊 Diagrammes UML Thématiques - FarmShop

## 🎯 Vue d'ensemble

Ce dossier contient **6 diagrammes UML thématiques** qui décomposent le système FarmShop en modules fonctionnels centrés autour de la classe `User`. Cette approche permet une meilleure compréhension et maintenance du système.

## 📋 Liste des diagrammes

### 🔐 1. User & Authentication
**Fichiers :** `user_authentication.puml` / `user_authentication.png`  
**Classes :** User, Cookie, Message  
**Description :** Gestion de l'authentification, sessions utilisateur et système de contact.

### 🛍️ 2. User & Product Catalog  
**Fichiers :** `user_product_catalog.puml` / `user_product_catalog.png`  
**Classes :** User, Product, Category, RentalCategory, SpecialOffer, Wishlist, ProductLike  
**Description :** Navigation dans le catalogue, gestion des préférences et interactions produits.

### 🛒 3. User & Cart Systems
**Fichiers :** `user_cart_systems.puml` / `user_cart_systems.png`  
**Classes :** User, Cart, CartItem, CartLocation, CartItemLocation, Product  
**Description :** Systèmes de panier pour les achats et les locations.

### 📦 4. User & Order Management
**Fichiers :** `user_order_management.puml` / `user_order_management.png`  
**Classes :** User, Order, OrderItem, OrderLocation, OrderItemLocation, OrderReturn, Product  
**Description :** Cycle de vie complet des commandes (achat et location) avec gestion des retours.

### 📝 5. User & Blog
**Fichiers :** `user_blog.puml` / `user_blog.png`  
**Classes :** User, BlogCategory, BlogPost, BlogComment, BlogCommentReport  
**Description :** Système de blog communautaire avec commentaires et modération.

### 📧 6. User & Newsletter
**Fichiers :** `user_newsletter.puml` / `user_newsletter.png`  
**Classes :** User, Newsletter, NewsletterSubscription, NewsletterSend  
**Description :** Système de communication par newsletters avec analytics.

## 🔧 Génération automatique

Pour régénérer tous les diagrammes :
```powershell
.\generate_diagrams_fixed.ps1
```

## 📐 Utilisation recommandée

- **Développeurs Frontend** → Diagrammes 2 (Catalog) et 3 (Cart)
- **Développeurs Backend** → Diagrammes 1 (Auth) et 4 (Order)  
- **Équipe Contenu** → Diagrammes 5 (Blog) et 6 (Newsletter)
- **Architectes** → Vue d'ensemble avec tous les diagrammes

## ✅ Avantages de cette approche

✅ **Lisibilité améliorée** : Chaque diagramme focalisé sur un domaine  
✅ **Maintenance facilitée** : Modifications localisées par thème  
✅ **Compréhension progressive** : Apprentissage par module  
✅ **Documentation modulaire** : Chaque équipe peut se concentrer sur son domaine  
✅ **Évolutivité** : Ajout de nouvelles classes plus simple  

## 🔗 Liens utiles

- [PlantUML Documentation](https://plantuml.com/)
- [Diagramme complet original](../farmshop_class_diagram.puml)
- [Script de génération](../../generate_diagrams_fixed.ps1)

---
*Dernière mise à jour : 17 août 2025*
