@component('mail::message')
# Annulation de votre commande de location

Bonjour {{ $user->name }},

Votre commande de location **#{{ $orderLocation->order_number }}** a été annulée.

## Détails de la commande annulée

**Période prévue :**
- Du {{ \Carbon\Carbon::parse($orderLocation->start_date)->format('d/m/Y') }}
- Au {{ \Carbon\Carbon::parse($orderLocation->end_date)->format('d/m/Y') }}

**Articles concernés :**
@foreach($items as $item)
- {{ $item->product_name }} ({{ $item->quantity }}x)
@endforeach

## Raison de l'annulation

{{ $cancellationReason }}

## Remboursement

Un remboursement de **{{ number_format($refundAmount, 2) }}€** sera effectué sur votre moyen de paiement original.

{{ $refundInfo }}

## Nouvelle location

Vous pouvez à tout moment passer une nouvelle commande de location sur notre plateforme.

@component('mail::button', ['url' => config('app.frontend_url') . '/rental'])
Nouvelle location
@endcomponent

Nous nous excusons pour la gêne occasionnée.

L'équipe {{ config('app.name') }}
@endcomponent
