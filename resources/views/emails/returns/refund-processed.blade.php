@component('mail::message')
# Remboursement effectué ✅

Bonjour {{ $user->name }},

Bonne nouvelle ! Votre remboursement pour la commande **{{ $order->order_number }}** a été traité avec succès.

## Détails du remboursement

**Commande concernée :** #{{ $order->order_number }}  
**Date du remboursement :** {{ now()->format('d/m/Y à H:i') }}  
**Méthode de remboursement :** Moyen de paiement original  
**Statut :** ✅ Remboursement effectué

## Récapitulatif

@foreach($returnableItems as $item)
- **{{ $item->product->name }}**
  - Quantité : {{ $item->quantity }}
  - Prix unitaire : {{ number_format($item->unit_price, 2) }}€
  - Sous-total : {{ number_format($item->unit_price * $item->quantity, 2) }}€

@endforeach

---

## Montant remboursé

| Description | Montant |
|-------------|---------|
| Articles retournés | {{ number_format($refundAmount, 2) }}€ |
| **Total remboursé** | **{{ number_format($refundAmount, 2) }}€** |

## Important

💳 Le remboursement apparaîtra sur votre compte bancaire sous **3-5 jours ouvrés** selon votre établissement bancaire.

📧 Conservez cet email comme preuve de remboursement.

🔍 Le délai peut varier selon votre banque et le type de carte utilisée.

## Détails de la transaction

- **Numéro de commande :** {{ $order->order_number }}
- **Date de retour :** {{ $order->return_requested_at->format('d/m/Y') }}
- **Date de remboursement :** {{ now()->format('d/m/Y') }}

@if($order->return_reason)
## Raison du retour

> {{ $order->return_reason }}
@endif

## Besoin d'aide ?

Si vous ne voyez pas le remboursement sur votre compte après 5 jours ouvrés, ou si vous avez des questions, n'hésitez pas à contacter notre service client.

@component('mail::button', ['url' => config('app.frontend_url') . '/orders/' . $order->id])
Voir ma commande
@endcomponent

Merci de votre confiance et à bientôt sur {{ config('app.name') }} !

L'équipe {{ config('app.name') }}
@endcomponent
