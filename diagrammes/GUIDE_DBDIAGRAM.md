# Guide d'utilisation des schémas DBDiagram.io

## 🎯 Objectif
Ces fichiers DBML (Database Markup Language) sont conçus pour être utilisés avec **dbdiagram.io**, un outil en ligne professionnel pour créer des diagrammes de base de données.

## 📋 Les 5 schémas générés

1. **`dbml_users_auth.dbml`** - Utilisateurs & Authentification
2. **`dbml_products_categories.dbml`** - Produits & Catégories  
3. **`dbml_orders_purchases.dbml`** - Commandes & Achats
4. **`dbml_rentals_management.dbml`** - Locations & Gestion
5. **`dbml_communication_blog.dbml`** - Communication & Blog

## 🚀 Comment utiliser avec dbdiagram.io

### Étape 1: Accéder à dbdiagram.io
1. Aller sur **https://dbdiagram.io/**
2. Créer un compte gratuit ou se connecter
3. Cliquer sur "Create new diagram"

### Étape 2: Importer un schéma
1. Ouvrir l'un des fichiers `.dbml` dans VS Code
2. **Copier tout le contenu** du fichier
3. Dans dbdiagram.io, **coller le contenu** dans l'éditeur de gauche
4. Le diagramme s'affiche automatiquement à droite

### Étape 3: Exporter en PNG/PDF
1. Une fois le diagramme affiché, cliquer sur **"Export"** en haut à droite
2. Choisir le format : **PNG**, **PDF**, ou **SVG**
3. Télécharger l'image haute qualité

## 🎨 Avantages de dbdiagram.io

✅ **Interface professionnelle** - Beaux diagrammes ERD  
✅ **Relations visuelles** - Lignes de connexion claires  
✅ **Types de données** - Affichage des types et contraintes  
✅ **Export haute qualité** - PNG, PDF, SVG  
✅ **Collaboration** - Partage de liens publics  
✅ **Code SQL** - Génération automatique de DDL  

## 📊 Fonctionnalités incluses dans nos schémas

- **Clés primaires et étrangères** - Définies avec `[pk]` et `[ref]`
- **Types de données précis** - varchar, bigint, decimal, enum, etc.
- **Contraintes** - unique, not null, default values
- **Index** - Index composites définis
- **Notes** - Documentation de chaque table
- **Relations** - Cardinalités 1:1, 1:N, N:M

## 🔧 Personnalisation possible

Dans dbdiagram.io, tu peux :
- **Modifier les couleurs** des tables
- **Réorganiser** la disposition des tables
- **Ajouter des groupes** pour organiser visuellement
- **Modifier la taille** des éléments
- **Ajouter des notes** supplémentaires

## 💡 Conseils d'utilisation

1. **Commencer par un schéma** - Ne pas tout coller d'un coup
2. **Vérifier les relations** - S'assurer que les lignes sont correctes
3. **Réorganiser si nécessaire** - Faire glisser les tables pour une meilleure lisibilité
4. **Exporter en haute résolution** - Pour les présentations
5. **Sauvegarder le projet** - Pour modifications futures

## 🔄 Mise à jour des schémas

Pour mettre à jour un schéma :
1. Modifier le fichier `.dbml` correspondant
2. Copier le nouveau contenu dans dbdiagram.io
3. Réexporter l'image

---

**Note** : dbdiagram.io offre bien plus de fonctionnalités que PlantUML pour les diagrammes de base de données et produit des résultats beaucoup plus professionnels et lisibles.
