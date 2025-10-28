# Tests Unitaires - Cart System

## 📋 Description

Tests de validation du système de panier d'achat du projet FarmShop. Ces tests vérifient les modèles Cart et CartItem, les calculs de prix, la gestion des stocks et la logique métier.

## 🎯 Tests du système Cart

### 1. Cart Model (`test_cart_model.php`)
- ✅ Structure et attributs fillable
- ✅ Relations: user, items
- ✅ Calculs: subtotal (HT), tax_amount (TVA), total (TTC)
- ✅ Gestion produits: addProduct, removeProduct, clear, isEmpty
- ✅ Livraison: getShippingCost, isFreeShipping (seuil 25€)
- ✅ Formatage: formatted_total, formatted_subtotal, formatted_total_tax
- ✅ Résumés: getCostSummary, getCompleteCartSummary
- ✅ Scope: notExpired()
- ✅ Méthode statique: getOrCreateForUser()

### 2. CartItem Model (`test_cart_item_model.php`)
- ✅ Structure et attributs fillable
- ✅ Relations: cart, product, specialOffer
- ✅ Type casting: decimals (prix, taxes), integer (quantité), boolean
- ✅ Calculs automatiques: recalculate() (subtotal, tax, total)
- ✅ Gestion quantité: updateQuantity, increaseQuantity, decreaseQuantity
- ✅ Offres spéciales: applySpecialOffer (automatic)
- ✅ Validations: stock, produit actif, rupture, quantité min
- ✅ Snapshot: product_name, product_category, product_metadata

### 3. Cart Business Logic (`test_cart_business_logic.php`)
- ✅ Règles livraison gratuite (seuil 25€, frais 2.50€)
- ✅ Calculs en cascade (item → cart → total_with_shipping)
- ✅ Gestion stocks: vérification avant ajout/modification
- ✅ Validations produits: actif, non en rupture, stock suffisant
- ✅ Offres spéciales: application automatique selon quantité
- ✅ Synchronisation cart ↔ items en temps réel
- ✅ Scénarios métier complets (8 scénarios)
- ✅ Messages d'erreur explicites
- ✅ Cohérence des données

## 🚀 Utilisation

### Exécuter tous les tests Cart
```bash
php TestUnit/05_Cart/run_all_tests.php
```

### Exécuter un test spécifique
```bash
# Test Cart Model
php TestUnit/05_Cart/test_cart_model.php

# Test CartItem Model
php TestUnit/05_Cart/test_cart_item_model.php

# Test Business Logic
php TestUnit/05_Cart/test_cart_business_logic.php
```

## 📊 Points vérifiés

### Modèles
- Structure et relations
- Attributs fillable et casts
- Méthodes de calcul
- Formatage des montants

### Calculs de prix
```
CartItem:
  subtotal (HT) = unit_price × quantity
  tax_amount = subtotal × (tax_rate / 100)
  total (TTC) = subtotal + tax_amount

Cart:
  subtotal = Σ(items.subtotal)
  tax_amount = Σ(items.tax_amount)
  total = Σ(items.total)
  total_items = Σ(items.quantity)

Final:
  shipping_cost = total < 25€ ? 2.50€ : 0€
  total_with_shipping = total + shipping_cost
```

### Validations
- Stock disponible suffisant
- Produit actif (is_active = true)
- Produit non en rupture (is_out_of_stock = false)
- Quantité minimum = 1
- Exceptions avec messages explicites

### Fonctionnalités

#### Livraison gratuite
- **Seuil**: 25.00 €
- **Frais standard**: 2.50 €
- **Calcul automatique** selon montant panier
- **Affichage** montant restant pour livraison gratuite

#### Offres spéciales
- Application automatique si conditions remplies
- Sauvegarde prix original
- Calcul pourcentage et montant réduction
- Impact sur totaux cart
- Retrait si non applicable

#### Gestion des stocks
- Vérification avant ajout
- Vérification avant modification
- Message avec stock disponible et quantité dans panier
- Exception si insuffisant

## 🛠️ Scénarios métier testés

1. **Ajout produit nouveau** → Création CartItem
2. **Ajout produit existant** → Augmentation quantité
3. **Modification quantité** → Recalcul + validation stock
4. **Suppression produit** → Suppression item + recalcul cart
5. **Vider panier** → Suppression tous items + reset totaux
6. **Application offre spéciale** → Recalcul automatique prix
7. **Calcul livraison** → Selon seuil 25€
8. **Vérification disponibilité** → Liste items problématiques

## 📝 Messages d'erreur

- **Stock insuffisant**: "Stock insuffisant. Stock disponible: X, déjà dans le panier: Y"
- **Produit inactif**: "Ce produit n'est plus disponible"
- **Rupture de stock**: "Ce produit est en rupture de stock et ne peut pas être acheté"
- **Quantité invalide**: Minimum 1 (automatique)

## 🔍 Cohérence des données

- ✅ Totaux cart = somme items
- ✅ Quantités >= 1
- ✅ Prix positifs
- ✅ TVA cohérente avec taux
- ✅ Total TTC = HT + TVA
- ✅ Snapshot produit préservé
- ✅ Métadonnées conservées

## 🎨 Formatage

Tous les montants peuvent être formatés:
- `formatted_total`: "XX,XX €"
- `formatted_subtotal`: "XX,XX €"
- `formatted_total_tax`: "XX,XX €"
- `getCompleteCartSummary()`: Objet complet avec tous montants formatés

## 🔄 Synchronisation

Toute modification d'un CartItem déclenche:
1. Recalcul du CartItem (subtotal, tax, total)
2. Recalcul du Cart parent
3. Mise à jour en temps réel
4. Validation des contraintes

## 📦 Dépendances

- Laravel 11+
- Models: Cart, CartItem, Product, User, SpecialOffer
- Relations Eloquent
- Type casting automatique
