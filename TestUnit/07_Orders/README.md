# Tests Unitaires - Orders System

## 📋 Description

Tests de validation du système de commandes du projet FarmShop. Ces tests vérifient les modèles Order (achats), OrderItem et OrderLocation (locations) avec leurs transitions automatiques, calculs et gestions des retours/retards/dégâts.

## 🎯 Tests du système Orders

### 1. Order Model (`test_order_model.php`)
- ✅ Structure et attributs fillable (46 attributs)
- ✅ Relations: user, items, returns, returnableItems
- ✅ Statuts: pending, confirmed, preparing, shipped, delivered, cancelled, returned
- ✅ Scopes: 11 scopes (pending, confirmed, paid, byUser, canBeCancelled, etc.)
- ✅ Accesseurs: statusLabel, paymentStatusLabel, formattedTotal, canBeCancelledNow
- ✅ Méthodes métier: updateStatus, onConfirmed, onPreparing, onShipped, onDelivered
- ✅ Transitions automatiques avec historique (status_history)
- ✅ Jobs programmés: ProcessSingleOrderStatusJob
- ✅ Génération facture (invoice_number)
- ✅ SoftDeletes, métadonnées JSON

### 2. OrderItem Model (`test_order_item_model.php`)
- ✅ Structure et attributs fillable (31 attributs)
- ✅ Relations: order, product, specialOffer, returns
- ✅ Snapshot produit: name, sku, description, image, category
- ✅ Offres spéciales: original_unit_price, discount_percentage, discount_amount
- ✅ Calculs: subtotal (HT), tax_amount (TVA), total_price (TTC)
- ✅ Gestion retours: is_returnable, is_returned, returned_quantity, return_deadline
- ✅ Scopes: 10 scopes (pending, confirmed, returnable, canBeReturned, etc.)
- ✅ Statuts: 7 statuts synchronisés avec Order
- ✅ Tracking: tracking_number, shipped_at, delivered_at
- ✅ SoftDeletes, métadonnées

### 3. OrderLocation Model (`test_order_location_model.php`)
- ✅ Structure et attributs fillable (65+ attributs)
- ✅ Calculs location: rental_days, daily_rate, total_rental_cost
- ✅ Gestion caution: deposit_amount, stripe_deposit_authorization_id, deposit_status
- ✅ Gestion retards: late_days, late_fees, late_fee_per_day
- ✅ Gestion dégâts: inspection_status, damage_cost, damage_photos, has_damages
- ✅ Calcul pénalités: total_penalties = late_fees + damage_cost
- ✅ Statuts: pending, confirmed, active, finished, completed, cancelled
- ✅ Dates: start_date, end_date, actual_return_date, 10 dates de tracking
- ✅ Intégration Stripe: payment_intent + deposit_authorization
- ✅ Jobs automatiques: start, reminder, end, overdue
- ✅ Frontend confirmation, SoftDeletes

## 🚀 Utilisation

### Exécuter tous les tests Orders
```bash
php TestUnit/07_Orders/run_all_tests.php
```

### Exécuter un test spécifique
```bash
# Test Order Model
php TestUnit/07_Orders/test_order_model.php

# Test OrderItem Model
php TestUnit/07_Orders/test_order_item_model.php

# Test OrderLocation Model
php TestUnit/07_Orders/test_order_location_model.php
```

## 📊 Points vérifiés

### Statuts Order (Achats)

| Statut | Description | Action automatique |
|--------|-------------|-------------------|
| `pending` | En attente paiement | - |
| `confirmed` | Confirmée et payée | Génère facture, job +15s → preparing |
| `preparing` | En préparation | Job programmé → shipped |
| `shipped` | Expédiée | Job programmé → delivered |
| `delivered` | Livrée | Activation période retour |
| `cancelled` | Annulée | Restoration stock |
| `returned` | Retournée | Remboursement |

### Statuts OrderLocation (Locations)

| Statut | Description | Jobs programmés |
|--------|-------------|----------------|
| `pending` | En attente paiement | - |
| `confirmed` | Confirmée et payée | StartRentalJob (start_date) |
| `active` | Location en cours | RentalEndReminderJob (J-1) |
| `finished` | Retournée | Inspection dégâts |
| `completed` | Inspection OK | Remboursement caution |
| `cancelled` | Annulée | Annulation caution |

### Calculs Order (Achats)

```
OrderItem:
  subtotal (HT) = unit_price × quantity
  tax_amount = subtotal × (tax_rate / 100)
  total_price (TTC) = subtotal + tax_amount

Order:
  subtotal = Σ(items.subtotal)
  tax_amount = Σ(items.tax_amount)
  total_amount = subtotal + tax_amount + shipping_cost - discount_amount
```

### Calculs OrderLocation (Locations)

```
Coûts de base:
  rental_days = (end_date - start_date).days
  total_rental_cost = daily_rate × rental_days
  tax_amount = total_rental_cost × (tax_rate / 100)
  total_amount = total_rental_cost + tax_amount

Retards:
  late_days = (actual_return_date - end_date).days
  late_fees = late_days × late_fee_per_day

Pénalités:
  total_penalties = late_fees + damage_cost + penalty_amount
  
Caution:
  deposit_refund = deposit_amount - total_penalties
  Si total_penalties > 0 → Capture caution Stripe
  Sinon → Annulation préautorisation
```

### Transitions automatiques Order

```
1. Paiement réussi → status = confirmed
2. onConfirmed():
   - Génère invoice_number
   - ProcessSingleOrderStatusJob +15s → preparing
3. onPreparing():
   - Items.status = preparing
   - Job programmé → shipped
4. onShipped():
   - shipped_at = now()
   - tracking_number
   - can_be_cancelled = false
   - Job programmé → delivered
5. onDelivered():
   - delivered_at = now()
   - can_be_returned = true
   - return_deadline = +14 jours
```

### Jobs automatiques OrderLocation

```
StartRentalJob (start_date):
  - status = active
  - started_at = now()

RentalEndReminderJob (end_date - 1 jour):
  - Email rappel retour

EndRentalJob (end_date):
  - Email demande retour
  - Attente retour réel

RentalOverdueJob (end_date + 1 jour):
  - Notification retard
  - Calcul late_fees
```

### Snapshot produit (OrderItem)

Sauvegarde des données produit au moment de la commande:
- `product_name`: Nom
- `product_sku`: Référence
- `product_description`: Description
- `product_image`: URL image
- `product_category`: Catégorie (array)
- `metadata`: Métadonnées supplémentaires

**But**: Préserver l'état du produit même si modifié/supprimé ultérieurement.

### Offres spéciales (OrderItem)

```php
original_unit_price: 50.00 €   // Prix avant offre
discount_percentage: 20 %       // -20%
discount_amount: 10.00 €        // 50 × 0.20
unit_price: 40.00 €             // 50 - 10
special_offer_id: 123           // Lien vers l'offre
```

### Gestion retours (Order/OrderItem)

**Critères éligibilité**:
- Order: `status = delivered`
- Order: `can_be_returned = true`
- Order: `return_deadline > now()`
- OrderItem: `is_returnable = true`
- OrderItem: `is_returned = false`

**Deadline**: Générée à la livraison (+14 jours par défaut)

**Quantité**: Support retours partiels (`returned_quantity`)

### Gestion caution (OrderLocation)

**Préautorisation Stripe**:
```php
stripe_deposit_authorization_id  // PaymentIntent ID
deposit_status: authorized        // Non capturé
capture_method: manual            // Stripe
```

**Scénarios**:
1. **Retour OK**: Annulation préautorisation → Aucun débit
2. **Dégâts/Retard**: Capture partielle/totale → Débit effectif
3. **Expiration**: >7 jours → Auto-annulation Stripe

### Inspection retour location

```php
inspection_status: pending|in_progress|completed
product_condition: excellent|good|fair|damaged
has_damages: boolean
damage_notes: "Description dégâts"
damage_photos: ["photo1.jpg", "photo2.jpg"]
damage_cost: 150.00 €
auto_calculate_damages: boolean
inspection_completed_at: datetime
inspected_by: user_id
```

## 🛠️ Fonctionnalités validées

### Order (Achats)
- ✅ Transitions automatiques (7 statuts)
- ✅ Historique complet (status_history)
- ✅ Génération facture automatique
- ✅ Gestion annulation (avant expédition)
- ✅ Système retours avec deadline
- ✅ Emails automatiques
- ✅ 11 scopes de requête

### OrderItem
- ✅ Snapshot produit préservé
- ✅ Offres spéciales calculées
- ✅ Calculs HT/TVA/TTC
- ✅ Retours partiels supportés
- ✅ Tracking individuel
- ✅ 10 scopes de requête

### OrderLocation (Locations)
- ✅ Calculs automatiques (jours, tarifs)
- ✅ Caution Stripe (préautorisation)
- ✅ Gestion retards avec pénalités
- ✅ Inspection dégâts complète
- ✅ 4 jobs automatiques programmés
- ✅ Frontend confirmation
- ✅ Remboursement automatique caution

## 📝 Historique et traçabilité

### status_history (Order)
```json
[
  {
    "from": "pending",
    "to": "confirmed",
    "timestamp": "2024-01-15T10:30:00Z",
    "automatic": true
  },
  {
    "from": "confirmed",
    "to": "preparing",
    "timestamp": "2024-01-15T10:30:15Z",
    "automatic": true
  }
]
```

### Dates importantes
- `created_at`: Création
- `paid_at`: Paiement
- `confirmed_at`: Confirmation
- `shipped_at`: Expédition
- `delivered_at`: Livraison
- `cancelled_at`: Annulation
- `invoice_generated_at`: Facture

## 📦 Dépendances

- Laravel 11+
- Models: Order, OrderItem, OrderLocation, OrderReturn, User, Product, SpecialOffer
- Jobs: ProcessSingleOrderStatusJob, StartRentalJob, RentalEndReminderJob, EndRentalJob, RentalOverdueJob
- Events: OrderLocationStatusChanged
- Services: StripeService (cautions)
- SoftDeletes trait

## 🔒 Règles métier

1. **Annulation Order**: Possible uniquement si `status IN (pending, confirmed, preparing)`
2. **Retour Order**: Possible uniquement si `status = delivered` ET `return_deadline > now()`
3. **Caution Location**: Préautorisée mais non capturée (capture manuelle si pénalités)
4. **Stock**: Décrémenté à la confirmation, restauré à l'annulation/retour
5. **Facture**: Générée automatiquement à la confirmation
6. **Transitions**: Automatiques via jobs programmés (non-bloquant)
7. **Retards**: Calcul automatique et notification J+1

## 🎨 Valeurs par défaut

**Order**:
- `status`: pending
- `payment_status`: pending
- `can_be_cancelled`: true
- `can_be_returned`: false
- `tax_amount`: 0
- `shipping_cost`: 0

**OrderItem**:
- `status`: pending
- `is_returnable`: false
- `is_returned`: false
- `returned_quantity`: 0
- `can_be_cancelled`: true

**OrderLocation**:
- Pas de valeurs par défaut spécifiées
- Calculs dynamiques à la création
