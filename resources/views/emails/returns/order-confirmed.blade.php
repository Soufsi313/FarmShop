@component('mail::message')
# Retour confirmé

Bonjour {{ $user->name }},

Votre demande de retour pour la commande **{{ $order->order_number }}** a été confirmée avec succès ! ✅

## Détails de votre retour

**Commande concernée :** #{{ $order->order_number }}  
**Date de confirmation :** {{ $order->return_requested_at->format('d/m/Y à H:i') }}  
**Statut :** Confirmé - Produits retournés et stock restauré

## Article(s) retourné(s)

@foreach($returnableItems as $item)
- **{{ $item->product->name }}**
  - Quantité : {{ $item->quantity }}
  - Prix unitaire : {{ number_format($item->unit_price, 2) }}€
  - Sous-total : {{ number_format($item->unit_price * $item->quantity, 2) }}€

@endforeach

## Montant du remboursement

**{{ number_format($returnableAmount, 2) }}€**

Le remboursement sera effectué sous **3-5 jours ouvrés** sur votre moyen de paiement original.

@if($order->return_reason)
## Votre commentaire

> {{ $order->return_reason }}
@endif

## Informations importantes

✅ Le stock des produits a été restauré  
✅ Le remboursement est en cours de traitement  
✅ Vous recevrez une confirmation une fois le remboursement effectué

## Besoin d'aide ?

Si vous avez des questions concernant votre remboursement, n'hésitez pas à contacter notre service client en répondant à cet email.

@component('mail::button', ['url' => config('app.frontend_url') . '/orders/' . $order->id])
Voir ma commande
@endcomponent

Merci de votre confiance,

L'équipe {{ config('app.name') }}
@endcomponent
