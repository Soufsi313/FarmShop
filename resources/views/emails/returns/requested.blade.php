@component('mail::message')
# Retour confirmé

Bonjour {{ $user->name }},

Votre demande de retour **{{ $orderReturn->return_number }}** a été confirmée avec succès ! ✅

## Détails de votre retour

**Commande concernée :** #{{ $order->order_number }}  
**Date de confirmation :** {{ $orderReturn->created_at->format('d/m/Y à H:i') }}  
**Statut :** Confirmé - Prêt à expédier

## Article(s) à retourner

- **{{ $product->name }}**
  - Quantité : {{ $orderReturn->quantity }}
  - Prix unitaire : {{ number_format($orderItem->unit_price, 2) }}€
  - Motif : {{ $returnTypeLabel }}

## Montant du remboursement

**{{ number_format($orderReturn->refund_amount, 2) }}€**

_Le montant final pourra être ajusté après inspection selon l'état du produit retourné._

## Instructions de retour

### 1. Préparez votre colis
- Remballez le(s) produit(s) dans leur emballage d'origine si possible
- Assurez-vous que le(s) produit(s) soit(ent) en bon état
- Incluez tous les accessoires et documents fournis

### 2. Expédiez votre colis
- Rendez-vous dans un point de dépôt de votre choix
- Ou contactez notre service client pour organiser un enlèvement
- **Conservez bien votre preuve d'envoi**

### 3. Suivi et remboursement
- Une fois reçu, nous inspecterons le(s) produit(s) sous 2-3 jours ouvrés
- Le remboursement sera effectué sous 3-5 jours ouvrés après validation
- Vous recevrez une confirmation par email à chaque étape

@if($orderReturn->reason)
## Votre commentaire

> {{ $orderReturn->reason }}
@endif

## Besoin d'aide ?

Si vous avez des questions concernant votre demande de retour, n'hésitez pas à contacter notre service client en répondant à cet email.

@component('mail::button', ['url' => config('app.frontend_url') . '/orders/' . $order->id])
Voir ma commande
@endcomponent

Merci de votre patience,

L'équipe {{ config('app.name') }}
@endcomponent
