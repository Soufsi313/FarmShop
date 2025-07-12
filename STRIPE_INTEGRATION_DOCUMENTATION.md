# Intégration Stripe - Documentation

## Configuration

### Variables d'environnement
```env
STRIPE_KEY=pk_test_YOUR_STRIPE_PUBLISHABLE_KEY_HERE
STRIPE_SECRET=sk_test_YOUR_STRIPE_SECRET_KEY_HERE
STRIPE_WEBHOOK_SECRET=whsec_YOUR_WEBHOOK_SECRET_HERE
```

## API Endpoints

### 🛒 Paiements d'achat

#### 1. Créer une intention de paiement pour achat
```http
POST /api/stripe/payment-intent/purchase/{orderId}
Authorization: Bearer {token}
```

**Réponse :**
```json
{
    "success": true,
    "message": "Intention de paiement créée avec succès",
    "data": {
        "client_secret": "pi_3abc123_secret_xyz",
        "payment_intent_id": "pi_3abc123",
        "amount": 150.50,
        "currency": "eur",
        "order": {
            "id": 1,
            "order_number": "ORD-20250712-001",
            "total_amount": 150.50,
            "items_count": 3
        }
    }
}
```

### 🏠 Paiements de location

#### 2. Créer une intention de paiement pour location
```http
POST /api/stripe/payment-intent/rental/{orderLocationId}
Authorization: Bearer {token}
```

**Réponse :**
```json
{
    "success": true,
    "message": "Intention de paiement créée avec succès",
    "data": {
        "client_secret": "pi_3def456_secret_abc",
        "payment_intent_id": "pi_3def456",
        "amount": 275.00,
        "currency": "eur",
        "order": {
            "id": 2,
            "order_number": "LOC-20250712-001",
            "total_amount": 200.00,
            "deposit_amount": 75.00,
            "total_to_pay": 275.00,
            "start_date": "2025-07-15",
            "end_date": "2025-07-20",
            "items_count": 2
        }
    }
}
```

### ✅ Confirmation de paiement

#### 3. Confirmer le paiement
```http
POST /api/stripe/confirm-payment
Authorization: Bearer {token}
Content-Type: application/json

{
    "payment_intent_id": "pi_3abc123"
}
```

### 📋 Informations de paiement

#### 4. Obtenir les informations de paiement
```http
GET /api/stripe/payment-info
Authorization: Bearer {token}
Content-Type: application/json

{
    "order_type": "purchase", // ou "rental"
    "order_id": 1
}
```

### ❌ Annulations

#### 5. Annuler une commande d'achat
```http
POST /api/stripe/cancel/purchase/{orderId}
Authorization: Bearer {token}
```

#### 6. Annuler une commande de location
```http
POST /api/stripe/cancel/rental/{orderLocationId}
Authorization: Bearer {token}
```

#### 7. Marquer une location comme retournée (Admin)
```http
POST /api/stripe/rental/{orderLocationId}/return
Authorization: Bearer {adminToken}
```

### 🔔 Webhook Stripe

#### 8. Webhook pour événements Stripe (Public)
```http
POST /api/stripe/webhook
Stripe-Signature: {signature}
```

## Gestion du Stock

### ✅ Paiement Réussi
Quand un paiement est confirmé via Stripe :
1. **Achat** : Stock décrementé de la quantité achetée
2. **Location** : Stock décrementé pour réservation

### ❌ Annulation de Commande
Quand une commande est annulée :
1. Stock ré-incrémenté de la quantité annulée
2. Statut commande mis à `cancelled`

### 🔄 Retour de Location
Quand une location est terminée :
1. Stock ré-incrémenté (produits rendus)
2. Statut location mis à `returned`

## Cartes de Test Stripe

### 💳 Cartes valides
- **Visa**: `4242424242424242`
- **Visa (débit)**: `4000056655665556`
- **Mastercard**: `5555555555554444`
- **American Express**: `378282246310005`

### 🚫 Cartes refusées
- **Fonds insuffisants**: `4000000000000002`
- **Carte expirée**: `4000000000000069`
- **Carte déclinée**: `4000000000000127`

### 📅 Dates d'expiration
- Utilisez n'importe quelle date future (ex: 12/30)

### 🔐 CVC
- Utilisez n'importe quel CVC (ex: 123)

## Flow de Paiement

### 1. Frontend - Création de l'intention
```javascript
// Côté client
const response = await fetch('/api/stripe/payment-intent/purchase/1', {
    method: 'POST',
    headers: {
        'Authorization': 'Bearer ' + token,
        'Content-Type': 'application/json'
    }
});

const { data } = await response.json();
const { client_secret } = data;
```

### 2. Frontend - Traitement avec Stripe Elements
```javascript
// Initialiser Stripe
const stripe = Stripe('pk_test_YOUR_STRIPE_PUBLISHABLE_KEY_HERE...');

// Confirmer le paiement
const { error, paymentIntent } = await stripe.confirmCardPayment(client_secret, {
    payment_method: {
        card: cardElement,
        billing_details: {
            name: 'Client Name',
            email: 'client@example.com'
        }
    }
});

if (error) {
    console.error('Erreur paiement:', error);
} else if (paymentIntent.status === 'succeeded') {
    // Confirmer côté serveur
    await fetch('/api/stripe/confirm-payment', {
        method: 'POST',
        headers: {
            'Authorization': 'Bearer ' + token,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            payment_intent_id: paymentIntent.id
        })
    });
}
```

## Statuts des Commandes

### Orders & OrderLocations
- `pending` : En attente de paiement
- `confirmed` : Payée et confirmée  
- `cancelled` : Annulée (stock restauré)
- `returned` : Retournée (location uniquement)

### Payment Status
- `pending` : Paiement en attente
- `paid` : Paiement réussi
- `failed` : Paiement échoué
- `refunded` : Paiement remboursé

## Logs et Surveillance

Tous les événements sont loggés :
- ✅ Paiements réussis avec détails
- ❌ Échecs de paiement avec raisons
- 📦 Modifications de stock avec contexte
- 🔔 Réception et traitement des webhooks

## Sécurité

### Vérification Webhook
- Signature Stripe vérifiée automatiquement
- Endpoint `/api/stripe/webhook` protégé contre les fausses requêtes

### Autorisations
- Utilisateur ne peut payer que ses propres commandes
- Admin requis pour marquer les retours de location
- Tokens d'authentification obligatoires (sauf webhook)

## Exemple Complet - Page de Paiement

```html
<!DOCTYPE html>
<html>
<head>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
    <div id="card-element">
        <!-- Stripe Elements créera le formulaire de carte ici -->
    </div>
    <button id="submit-button">Payer</button>

    <script>
        const stripe = Stripe('pk_test_YOUR_STRIPE_PUBLISHABLE_KEY_HERE...');
        const elements = stripe.elements();
        const cardElement = elements.create('card');
        cardElement.mount('#card-element');

        document.getElementById('submit-button').onclick = async () => {
            // 1. Créer l'intention de paiement
            const intentResponse = await fetch('/api/stripe/payment-intent/purchase/1', {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + userToken,
                    'Content-Type': 'application/json'
                }
            });
            
            const { data } = await intentResponse.json();
            
            // 2. Confirmer le paiement avec Stripe
            const { error, paymentIntent } = await stripe.confirmCardPayment(data.client_secret, {
                payment_method: {
                    card: cardElement
                }
            });
            
            if (paymentIntent && paymentIntent.status === 'succeeded') {
                // 3. Confirmer côté serveur
                await fetch('/api/stripe/confirm-payment', {
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + userToken,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        payment_intent_id: paymentIntent.id
                    })
                });
                
                alert('Paiement réussi !');
                // Rediriger vers page de confirmation
            }
        };
    </script>
</body>
</html>
```
