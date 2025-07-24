# Configuration du Webhook Stripe avec ngrok

## 1. URL de votre application via ngrok
https://3e387fa86e61.ngrok-free.app

## 2. URL du webhook à configurer dans Stripe
https://3e387fa86e61.ngrok-free.app/api/stripe/webhook

## 3. Événements à écouter dans Stripe Dashboard
- payment_intent.succeeded
- payment_intent.payment_failed
- payment_intent.requires_action

## 4. Étapes à suivre :

### A. Aller sur le Dashboard Stripe :
https://dashboard.stripe.com/test/webhooks

### B. Créer un nouveau endpoint :
1. Cliquer sur "Add endpoint"
2. URL d'endpoint : https://3e387fa86e61.ngrok-free.app/api/stripe/webhook
3. Description : FarmShop Test Webhook
4. Version de l'API : 2023-10-16 (ou la plus récente)

### C. Sélectionner les événements :
- payment_intent.succeeded
- payment_intent.payment_failed
- payment_intent.requires_action

### D. Copier la clé de signature du webhook
Une fois le webhook créé, copier la "Signing secret" qui commence par "whsec_"

### E. Mettre à jour le fichier .env avec la nouvelle clé :
STRIPE_WEBHOOK_SECRET=whsec_VOTRE_NOUVELLE_CLE_ICI

## 5. Test du webhook
Vous pouvez tester le webhook directement depuis le Dashboard Stripe en cliquant sur "Send test webhook"

## 6. Accès à votre application
- Application web : https://3e387fa86e61.ngrok-free.app
- Interface ngrok : http://127.0.0.1:4040 (pour voir les requêtes)
