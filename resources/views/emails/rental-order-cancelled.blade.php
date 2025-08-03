@component('mail::message')
# Annulation de votre commande de location

Bonjour {{ $orderLocation->user->name }},

Votre commande de location **#{{ $orderLocation->order_number }}** a été annulée avec succès.

## Détails de la commande annulée

**Période prévue :**
- Du {{ \Carbon\Carbon::parse($orderLocation->start_date)->format('d/m/Y') }}
- Au {{ \Carbon\Carbon::parse($orderLocation->end_date)->format('d/m/Y') }}

**Articles concernés :**
@foreach($items as $item)
- {{ $item->product_name }} ({{ $item->quantity }}x) - {{ number_format($item->unit_price, 2) }}€/jour
@endforeach

**Montant total :** {{ number_format($orderLocation->total_amount, 2) }}€

@if($orderLocation->cancellation_reason)
## Raison de l'annulation

{{ $orderLocation->cancellation_reason }}
@endif

## Remboursement

@if($orderLocation->payment_status === 'paid')
Un remboursement de **{{ number_format($orderLocation->total_amount, 2) }}€** sera effectué sur votre moyen de paiement original dans les 5-10 jours ouvrés.
@else
Aucun paiement n'ayant été effectué, aucun remboursement n'est nécessaire.
@endif

## Nouvelle location

Vous pouvez à tout moment passer une nouvelle commande de location sur notre plateforme.

@component('mail::button', ['url' => route('cart-location.index')])
Nouvelle location
@endcomponent

Nous nous excusons pour la gêne occasionnée et restons à votre disposition.

Cordialement,  
L'équipe {{ config('app.name') }}
@endcomponent
