# 🗄️ Tests Database - FarmShop

Ce dossier contient tous les tests unitaires pour la base de données MariaDB.

## 📋 Liste des Tests

### 1️⃣ test_connection.php
**Objectif :** Vérifier la connexion à la base de données

**Ce qui est testé :**
- ✅ Connexion PDO directe
- ✅ Existence des tables principales
- ✅ Comptage des enregistrements
- ✅ Requêtes complexes avec JOIN
- ✅ Vérification des indexes

**Commande :**
```bash
php TestUnit/01_Database/test_connection.php
```

---

### 2️⃣ test_tables.php
**Objectif :** Vérifier la structure des tables

**Ce qui est testé :**
- ✅ Existence de toutes les tables requises
- ✅ Présence des colonnes essentielles
- ✅ Types de données corrects
- ✅ Clés primaires
- ✅ Clés étrangères

**Commande :**
```bash
php TestUnit/01_Database/test_tables.php
```

---

### 3️⃣ test_migrations.php
**Objectif :** Vérifier les migrations Laravel

**Ce qui est testé :**
- ✅ Table 'migrations' existe
- ✅ Toutes les migrations exécutées
- ✅ Batches de migrations
- ✅ Migrations critiques présentes

**Commande :**
```bash
php TestUnit/01_Database/test_migrations.php
```

---

### 4️⃣ test_indexes.php
**Objectif :** Vérifier les indexes et la performance

**Ce qui est testé :**
- ✅ Indexes sur colonnes critiques
- ✅ Indexes sur clés étrangères
- ✅ Performance des requêtes
- ✅ Indexes uniques

**Commande :**
```bash
php TestUnit/01_Database/test_indexes.php
```

---

### 5️⃣ test_data_integrity.php
**Objectif :** Vérifier l'intégrité des données

**Ce qui est testé :**
- ✅ Pas de références orphelines
- ✅ Cohérence des prix
- ✅ Stocks valides (pas négatifs)
- ✅ Emails uniques
- ✅ Totaux de commandes cohérents

**Commande :**
```bash
php TestUnit/01_Database/test_data_integrity.php
```

---

## 🚀 Exécution Rapide

### Exécuter tous les tests
```bash
php TestUnit/01_Database/run_all_tests.php
```

### Exécuter un test spécifique
```bash
php TestUnit/01_Database/test_connection.php
php TestUnit/01_Database/test_tables.php
php TestUnit/01_Database/test_migrations.php
php TestUnit/01_Database/test_indexes.php
php TestUnit/01_Database/test_data_integrity.php
```

## 📊 Interprétation des Résultats

### ✅ TEST RÉUSSI
Tout fonctionne parfaitement, aucune action requise.

### ⚠️ TEST PARTIELLEMENT RÉUSSI
Le système fonctionne mais avec des avertissements. Lisez les recommandations.

### ❌ TEST ÉCHOUÉ
Un problème critique a été détecté. Corrigez avant de continuer.

## 🔧 Actions Recommandées

Si des tests échouent :

1. **Vérifier la connexion :**
   ```bash
   mysql -u root -p farmshop
   ```

2. **Exécuter les migrations :**
   ```bash
   php artisan migrate
   ```

3. **Vérifier le fichier .env :**
   ```
   DB_CONNECTION=mariadb
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=farmshop
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Nettoyer les données incohérentes :**
   Consultez les résultats de `test_data_integrity.php` pour identifier les problèmes.

## 📝 Notes

- Ces tests **NE MODIFIENT PAS** les données
- Ils peuvent être exécutés en production sans risque
- Temps d'exécution moyen : 5-10 secondes

---

*Dernière mise à jour : 2025-01-11*
