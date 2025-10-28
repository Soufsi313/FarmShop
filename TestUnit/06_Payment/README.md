# Tests Unitaires - Payment System

## 📋 Description

Tests de validation du système de paiement du projet FarmShop. Ces tests vérifient l'intégration Stripe (achats et locations), la gestion des webhooks, les cautions et la synchronisation des stocks.

## 🎯 Tests du système Payment

### 1. Stripe Service (`test_stripe_service.php`)
- ✅ Structure et configuration Stripe
- ✅ Création PaymentIntent achat
- ✅ Création PaymentIntent location
- ✅ Préautorisation caution (capture_method: manual)
- ✅ Gestion webhooks (succeeded, failed, created)
- ✅ Conversions EUR ↔ centimes
- ✅ Remboursements automatiques
- ✅ Annulation commande + restoration stock
- ✅ Retour location + restoration stock
- ✅ Programmation jobs automatiques (locations)
- ✅ Logs et traçabilité

### 2. Payment Controller (`test_payment_controller.php`)
- ✅ Structure et injection StripeService
- ✅ Méthodes: showPayment, processPayment, success, cancel
- ✅ Validations de sécurité (user_id, status)
- ✅ Calcul détails avec offres spéciales
- ✅ Réponses JSON appropriées
- ✅ Vues associées
- ✅ Gestion d'erreurs robuste
- ✅ Intégration avec Order/OrderLocation

### 3. Payment Business Logic (`test_payment_logic.php`)
- ✅ Flux paiement achat (9 étapes)
- ✅ Flux paiement location (11 étapes avec caution)
- ✅ Synchronisation stock (décrément/restoration)
- ✅ Statuts de paiement (pending, paid, failed, refunded)
- ✅ Statuts de caution (authorized, captured, cancelled)
- ✅ Métadonnées PaymentIntent
- ✅ Webhooks Stripe avec validation signature
- ✅ Jobs programmés (start, reminder, end, overdue)
- ✅ Remboursements automatiques
- ✅ Sécurité et validations
- ✅ Transitions automatiques de statut

## 🚀 Utilisation

### Exécuter tous les tests Payment
```bash
php TestUnit/06_Payment/run_all_tests.php
```

### Exécuter un test spécifique
```bash
# Test Stripe Service
php TestUnit/06_Payment/test_stripe_service.php

# Test Payment Controller
php TestUnit/06_Payment/test_payment_controller.php

# Test Business Logic
php TestUnit/06_Payment/test_payment_logic.php
```

## 📊 Points vérifiés

### Flux de paiement - Achat

```
1. Création commande (status: pending)
2. Création PaymentIntent via StripeService
3. Sauvegarde stripe_payment_intent_id
4. Paiement frontend avec Stripe.js
5. Webhook payment_intent.succeeded
6. MAJ: payment_status = paid, status = confirmed
7. Décrément stock des produits
8. Transitions automatiques: confirmed → processing → shipped
9. Email de confirmation
```

### Flux de paiement - Location

```
1. Création location (status: pending)
2. PaymentIntent location (paiement immédiat)
3. PaymentIntent caution (capture_method: manual)
4. Sauvegarde stripe_payment_intent_id + stripe_deposit_authorization_id
5. Paiement frontend (2 PaymentIntents)
6. Webhook: payment_intent.succeeded (location)
7. Webhook: payment_intent.succeeded (caution préautorisée)
8. MAJ: payment_status = paid, deposit_status = authorized
9. Confirmation frontend → Décrément stock
10. Programmation jobs automatiques
11. Email de confirmation
```

### Gestion du stock

| Action | Stock |
|--------|-------|
| Achat payé | ⬇️ Décrément APRÈS paiement confirmé |
| Location payée | ⬇️ Décrément APRÈS confirmation frontend |
| Annulation (confirmed) | ⬆️ Restoration (stock prélevé) |
| Annulation (pending) | ➖ PAS de restoration (stock jamais prélevé) |
| Retour location | ⬆️ Restoration à la fin |

### Statuts de paiement

- **pending**: En attente de paiement
- **paid**: Payé avec succès
- **failed**: Paiement échoué
- **refunded**: Remboursé
- **partially_refunded**: Partiellement remboursé

### Statuts de caution (locations)

- **pending**: Préautorisation en attente
- **authorized**: Préautorisé (non capturé) ✅
- **captured**: Capturé (dégâts/retard)
- **cancelled**: Annulé (retour OK)
- **expired**: Expiré (>7 jours)

### Conversions de montants

```php
// EUR → Centimes (pour Stripe)
convertToStripeAmount(25.50) → 2550

// Centimes → EUR (depuis Stripe)
convertFromStripeAmount(2550) → 25.50
```

### Métadonnées PaymentIntent

```json
{
  "order_id": "123",
  "order_type": "purchase|rental",
  "payment_type": "rental_payment|deposit_authorization",
  "order_number": "ORD-2024-0001",
  "user_id": "456",
  "user_email": "user@example.com",
  "deposit_amount": "50.00"
}
```

### Webhooks Stripe

| Événement | Action |
|-----------|--------|
| `payment_intent.succeeded` | Paiement confirmé → MAJ statut + stock |
| `payment_intent.payment_failed` | Paiement échoué → Log + notification |
| `payment_intent.created` | Intention créée → Pas d'action |
| `charge.refunded` | Remboursement → MAJ statut (si géré) |

**Sécurité**: Validation signature avec `webhook.secret`

### Jobs programmés (locations)

| Job | Déclencheur | Action |
|-----|-------------|--------|
| `StartRentalJob` | `start_date` | status = active |
| `RentalEndReminderJob` | `end_date - 1 jour` | Email rappel |
| `EndRentalJob` | `end_date` | Demande retour |
| `RentalOverdueJob` | `end_date + 1 jour` | Notification retard |

## 🛠️ Fonctionnalités validées

### Stripe Service

- ✅ Création PaymentIntent achat
- ✅ Création PaymentIntent location
- ✅ Préautorisation caution (`capture_method: manual`)
- ✅ Gestion webhooks avec validation signature
- ✅ Conversions EUR ↔ centimes
- ✅ Remboursements automatiques
- ✅ Restauration stock sur annulation
- ✅ Programmation jobs automatiques
- ✅ Logs détaillés

### Payment Controller

- ✅ Injection StripeService
- ✅ Affichage page paiement avec détails
- ✅ Traitement paiement (création PaymentIntent)
- ✅ Pages succès et annulation
- ✅ Validations sécurité (user_id, status)
- ✅ Gestion erreurs avec JSON
- ✅ Calcul offres spéciales

### Business Logic

- ✅ Flux complets achat/location
- ✅ Synchronisation stock automatique
- ✅ Transitions de statut
- ✅ Webhooks sécurisés
- ✅ Jobs programmés
- ✅ Remboursements cohérents
- ✅ Cautions préautorisées

## 🔒 Sécurité

- ✅ Vérification `user_id` avant paiement
- ✅ Vérification `status` de la commande
- ✅ Validation signature webhooks Stripe
- ✅ Clés API stockées en `.env`
- ✅ Webhook secret distinct de API secret
- ✅ Pas de données sensibles dans logs
- ✅ Codes HTTP appropriés (403, 400, 500)
- ✅ Try-catch sur méthodes critiques

## 🔄 Transitions automatiques

### Order (Achat)
```
pending → paid → confirmed → processing → shipped → delivered
```

### OrderLocation (Location)
```
pending → paid → confirmed → active → finished
```

**Mécanismes**:
- Observers/Events Laravel
- Jobs programmés pour chaque étape
- Emails automatiques à chaque transition
- Logs de chaque changement

## 📝 Logs

Tous les événements sont tracés:
- ✅ Création PaymentIntent
- ✅ Paiement réussi/échoué
- ✅ Webhooks reçus
- ✅ Décrément/restoration stock
- ✅ Programmation jobs
- ✅ Erreurs détaillées

## 📦 Dépendances

- Laravel 11+
- Stripe PHP SDK
- Models: Order, OrderLocation, Product, User
- Services: StripeService, QueueWorkerService
- Jobs: StartRentalJob, RentalEndReminderJob, EndRentalJob, RentalOverdueJob
- Events/Observers pour transitions automatiques

## 🎨 Configuration Stripe

```env
STRIPE_SECRET=sk_test_...
STRIPE_PUBLIC_KEY=pk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...
```

**Important**: Le webhook secret doit être distinct de la clé API secrète.

## 🚨 Notes importantes

1. **Stock**: Décrément SEULEMENT après paiement confirmé
2. **Caution**: Préautorisation (pas de capture immédiate)
3. **Webhooks**: Validation signature obligatoire
4. **Montants**: Toujours en centimes pour Stripe
5. **Logs**: Traçabilité complète de tous événements
6. **Jobs**: Programmation automatique pour locations
7. **Sécurité**: Validations strictes à chaque étape
