@component('mail::message')
# Confirmation de votre commande de location

Bonjour {{ $user->name }},

Votre commande de location **#{{ $orderLocation->order_number }}** a été confirmée avec succès !

## Détails de la location

**Période de location :**
- **Début :** {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}
- **Fin :** {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
- **Durée :** {{ $totalDays }} jour{{ $totalDays > 1 ? 's' : '' }}

**Adresses :**
- **Récupération :** {{ $pickupAddress }}
- **Retour :** {{ $returnAddress }}

## Articles loués

@foreach($items as $item)
- **{{ $item->product_name }}**
  - Quantité : {{ $item->quantity }}
  - Prix par jour : {{ number_format($item->daily_rate, 2) }}€
  - Durée : {{ $item->rental_days }} jour{{ $item->rental_days > 1 ? 's' : '' }}
  - Sous-total location : {{ number_format($item->subtotal, 2) }}€
  - Dépôt de garantie : {{ number_format($item->total_deposit, 2) }}€

@endforeach

## Récapitulatif financier

| Description | Montant |
|-------------|---------|
| Sous-total location | {{ number_format($subtotal, 2) }}€ |
| TVA (21%) | {{ number_format($taxAmount, 2) }}€ |
| Dépôt de garantie | {{ number_format($depositAmount, 2) }}€ |
| **Total à payer** | **{{ number_format($totalAmount, 2) }}€** |

## Important - Conditions d'annulation

⚠️ **Vous pouvez annuler votre commande jusqu'au {{ \Carbon\Carbon::parse($cancellationDeadline)->format('d/m/Y à 23:59') }}**

Passé cette date, l'annulation ne sera plus possible et les frais de location seront dus.

## Prochaines étapes

1. Vous recevrez un email 24h avant le début de votre location
2. Les équipements seront à récupérer à l'adresse indiquée
3. À la fin de la période, vous devrez fermer votre commande via notre plateforme
4. Une inspection sera effectuée et votre dépôt vous sera remboursé (déduction faite des éventuels dommages)

@if($orderLocation->notes)
## Notes spéciales
{{ $orderLocation->notes }}
@endif

@component('mail::button', ['url' => config('app.frontend_url') . '/rental-orders/' . $orderLocation->id])
Voir ma commande
@endcomponent

Merci de votre confiance !

L'équipe {{ config('app.name') }}
@endcomponent
