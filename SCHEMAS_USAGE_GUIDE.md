# 🎯 Guide d'utilisation des schémas exportés

## ✅ Export réussi !

Vous avez maintenant **6 fichiers de schémas séparés** qui remplacent votre base de 33 tables :

```
📁 database_schemas/
├── 01_products_schema.sql     (225 KB) - 📦 Produits & Catalogue
├── 02_users_schema.sql        (38 KB)  - 👥 Utilisateurs & Auth
├── 03_orders_schema.sql       (15 KB)  - 🛒 Commandes & Achats  
├── 04_rentals_schema.sql      (11 KB)  - 🏠 Locations
├── 05_communication_schema.sql (65 KB) - 📢 Communication & Marketing
└── 06_system_schema.sql       (8 KB)   - ⚙️ Système & Infrastructure
```

## 🔄 Comment importer dans vos outils

### 1. MySQL Workbench
```
File > Reverse Engineer > From SQL Script
→ Sélectionner un fichier (ex: 01_products_schema.sql)
→ Créer un diagramme séparé pour chaque schéma
```

### 2. DBeaver
```
Nouveau Projet > Import > SQL Scripts
→ Sélectionner tous les fichiers .sql
→ Créer des connexions séparées ou des schémas distincts
```

### 3. phpMyAdmin
```
Import > Parcourir > Sélectionner un fichier
→ Créer une nouvelle base pour chaque schéma
→ Ex: farmshop_products, farmshop_users, etc.
```

### 4. HeidiSQL
```
Outils > Import SQL file
→ Importer chaque fichier dans une base séparée
```

## 📊 Ordre d'import recommandé

**Respectez cet ordre pour éviter les erreurs de dépendances :**

1. `01_products_schema.sql` - **Base** (aucune dépendance)
2. `02_users_schema.sql` - Dépend de Products
3. `03_orders_schema.sql` - Dépend de Users + Products  
4. `04_rentals_schema.sql` - Dépend de Users + Products
5. `05_communication_schema.sql` - Dépend de Users
6. `06_system_schema.sql` - **Indépendant** (tables Laravel)

## 🎨 Stratégies de visualisation

### Option A : Diagrammes séparés
- **Avantage :** Chaque schéma est clair et lisible
- **Usage :** Documentation, présentation, développement focused

### Option B : Vue d'ensemble puis détails
- **Étape 1 :** Créer un diagramme global avec les tables principales
- **Étape 2 :** Créer des diagrammes détaillés par domaine

### Option C : Diagrammes par fonctionnalité
- **E-commerce :** Products + Orders + Users
- **Location :** Products + Rentals + Users  
- **CMS :** Communication + Users
- **Système :** System seul

## 🔗 Gestion des relations cross-schéma

### Relations importantes à maintenir :
```sql
-- Users vers Products (likes, wishlists)
users.id → product_likes.user_id
users.id → wishlists.user_id

-- Orders vers Products & Users
orders.user_id → users.id
order_items.product_id → products.id

-- Rentals vers Products & Users  
order_locations.user_id → users.id
order_item_locations.product_id → products.id
```

## 📝 Prochaines étapes

1. **Tester l'import** d'un schéma (commencer par `01_products_schema.sql`)
2. **Créer vos diagrammes** dans votre outil préféré
3. **Documenter** les relations cross-schéma si nécessaire
4. **Partager** les diagrammes avec votre équipe

## ⚡ Régénération

Pour mettre à jour les schémas :
```powershell
# Relancer l'export
powershell -ExecutionPolicy Bypass -File "run_export.ps1"
```

---

**🎉 Félicitations !** Vous avez transformé une base de 33 tables en 6 schémas logiques et manageable pour vos diagrammes !

Pour plus de détails techniques, consultez `DATABASE_SCHEMA_GUIDE.md`.
