# Tests Unitaires - Rentals System

## Description

Tests de validation du systeme de location du projet FarmShop. Ces tests verifient les modeles de panier de location (CartLocation, CartItemLocation) et les items de commande de location (OrderItemLocation) avec leurs calculs, validations et gestion des penalites.

## Tests du systeme Rentals

### 1. CartLocation Model (test_cart_location_model.php)
- Structure et attributs fillable
- Relations: user, items
- Methodes: addProduct, removeProduct, updateProductQuantity, updateProductDates, clear
- Calculs: total_amount, total_deposit, total_tva, total_with_tax
- Validations: stock, disponibilite periode, chevauchements
- Gestion dates: default_start_date, default_end_date, default_duration_days

### 2. CartItemLocation Model (test_cart_item_location_model.php)
- Structure et attributs fillable
- Relations: cartLocation, product
- Calculs automatiques: subtotal_amount, subtotal_deposit, tva_amount, total_amount
- TVA: 20% sur montant location uniquement (pas sur caution)
- Events: recalcul auto lors creation/modification
- Snapshot: product_name, product_sku, rental_category_name

### 3. OrderItemLocation Model (test_order_item_location_model.php)
- Structure et attributs fillable
- Relations: orderLocation, product
- Calculs: daily_rate, rental_days, deposit_per_item
- Inspection: condition_at_pickup, condition_at_return (excellent/good/fair/poor)
- Degats: item_damage_cost, damage_details, item_inspection_notes
- Retards: item_late_days, item_late_fees
- Remboursement: item_deposit_refund = total_deposit - damage_cost - late_fees
- Accesseurs: labels traduits et montants formates

## Utilisation

### Executer tous les tests Rentals
```bash
php TestUnit/08_Rentals/run_all_tests.php
```

### Executer un test specifique
```bash
php TestUnit/08_Rentals/test_cart_location_model.php
php TestUnit/08_Rentals/test_cart_item_location_model.php
php TestUnit/08_Rentals/test_order_item_location_model.php
```

## Points verifie

### Calculs CartItemLocation

```
Montants de base:
  subtotal_amount = unit_price_per_day × quantity × duration_days
  subtotal_deposit = unit_deposit × quantity
  
Taxes:
  tva_amount = subtotal_amount × 0.20 (TVA 20%)
  
Total TTC:
  total_amount = subtotal_amount + tva_amount
```

**Important**: La TVA est appliquee uniquement sur le montant de location, pas sur la caution.

### Calculs OrderItemLocation

```
Location:
  subtotal = daily_rate × quantity × rental_days
  tax_amount = subtotal × (tax_rate / 100)
  total_amount = subtotal + tax_amount
  
Caution:
  total_deposit = deposit_per_item × quantity
  
Penalites:
  penalites_item = item_damage_cost + item_late_fees
  
Remboursement:
  item_deposit_refund = total_deposit - penalites_item
```

### Validation disponibilite (CartLocation)

La methode checkProductAvailability verifie:
1. Produit type 'rental' ou 'both'
2. Produit actif (is_active)
3. Pas en rupture de stock (is_out_of_stock)
4. Stock global suffisant
5. Pas de chevauchement avec autres paniers actifs
6. Pas de conflit avec locations confirmees

### Gestion des dates

```
Duration calculation:
  duration_days = (end_date - start_date) + 1

Inclusion jour debut et fin:
  start_date: 2024-01-15
  end_date: 2024-01-17
  duration_days: 3 jours (15, 16, 17)
```

### Etats produit (inspection)

| Valeur | Label | Description |
|--------|-------|-------------|
| excellent | Excellent etat | Aucun defaut |
| good | Bon etat | Leger usage normal |
| fair | Etat correct | Usage visible |
| poor | Mauvais etat | Degats importants |

### Fonctionnalites validees

#### CartLocation
- Panier specifique location (separe achat)
- Ajout produit avec validation disponibilite
- Verification chevauchement periodes
- Mise a jour quantite/dates item
- Recalcul automatique totaux
- Support notes et metadonnees

#### CartItemLocation
- Calculs automatiques via events
- TVA 20% sur location uniquement
- Snapshot informations produit
- Support quantites multiples
- Periode de location flexible
- Recalcul si modification

#### OrderItemLocation
- Calculs granulaires par item
- Inspection etat depart/retour
- Gestion degats avec cout
- Calcul frais retard
- Remboursement caution automatique
- Formatage montants

### Validations integrees

**CartLocation**:
- Exception si produit type != rental/both
- Exception si stock insuffisant
- Exception si produit inactif
- Exception si rupture de stock
- Exception si produit deja dans panier
- Exception si chevauchement periode

**CartItemLocation**:
- Recalcul auto lors creation
- Recalcul auto si quantite/prix/duree change
- TVA fixe a 20%

**OrderItemLocation**:
- Snapshot produit preserve
- Labels traduits pour conditions
- Formatage automatique montants

## Regles metier

1. **TVA**: Appliquee uniquement sur montant location (20%)
2. **Caution**: Pas de TVA sur caution
3. **Duree**: Inclut jour debut ET jour fin (+1)
4. **Disponibilite**: Verification chevauchement avec autres utilisateurs
5. **Penalites**: Degats + retards deduits de caution
6. **Remboursement**: Peut etre 0 si penalites >= caution
7. **Snapshot**: Infos produit preservees meme si modifie

## Type casting

**Decimaux (2 decimales)**:
- Prix, tarifs, montants
- Cautions, taxes
- Couts degats, frais retard

**Dates**:
- start_date, end_date
- default_start_date, default_end_date

**Arrays**:
- metadata, damage_details

## Relations

```
CartLocation
  - belongsTo: User
  - hasMany: CartItemLocation

CartItemLocation
  - belongsTo: CartLocation
  - belongsTo: Product

OrderItemLocation
  - belongsTo: OrderLocation
  - belongsTo: Product
```

## Dependances

- Laravel 11+
- Models: CartLocation, CartItemLocation, OrderItemLocation, Product, User, OrderLocation
- Carbon pour gestion dates
- Eloquent events pour recalculs automatiques
