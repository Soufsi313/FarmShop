@component('mail::message')
# Rapport d'inspection de votre location

Bonjour {{ $user->name }},

L'inspection de votre commande de location **#{{ $orderLocation->order_number }}** est maintenant terminée.

## Résumé de l'inspection

**Articles inspectés :** {{ $inspectionSummary['total_items'] }} article{{ $inspectionSummary['total_items'] > 1 ? 's' : '' }}

@if($hasGoodItems)
✅ **{{ $inspectionSummary['good_items'] }}** article{{ $inspectionSummary['good_items'] > 1 ? 's' : '' }} en bon état
@endif

@if($hasDamagedItems)
⚠️ **{{ $inspectionSummary['damaged_items'] }}** article{{ $inspectionSummary['damaged_items'] > 1 ? 's' : '' }} endommagé{{ $inspectionSummary['damaged_items'] > 1 ? 's' : '' }}
@endif

@if($hasLostItems)
❌ **{{ $inspectionSummary['lost_items'] }}** article{{ $inspectionSummary['lost_items'] > 1 ? 's' : '' }} perdu{{ $inspectionSummary['lost_items'] > 1 ? 's' : '' }}
@endif

## Détail par article

@foreach($items as $item)
**{{ $item->product_name }}** ({{ $item->quantity }}x)
- **État au retour :** 
  @if($item->return_condition === 'good')
  ✅ Bon état
  @elseif($item->return_condition === 'damaged')
  ⚠️ Endommagé
  @elseif($item->return_condition === 'lost')
  ❌ Perdu
  @endif

@if($item->return_notes)
- **Notes :** {{ $item->return_notes }}
@endif

@if($item->damage_cost > 0)
- **Coût des dommages :** {{ number_format($item->damage_cost, 2) }}€
@endif

@if($item->penalty_amount > 0)
- **Pénalités totales :** {{ number_format($item->penalty_amount, 2) }}€
@endif

@endforeach

## Récapitulatif financier

| Description | Montant |
|-------------|---------|
| Dépôt de garantie initial | {{ number_format($orderLocation->deposit_amount, 2) }}€ |
@if($inspectionSummary['damage_costs'] > 0)
| Coûts des dommages | {{ number_format($inspectionSummary['damage_costs'], 2) }}€ |
@endif
@if($inspectionSummary['late_fees'] > 0)
| Frais de retard | {{ number_format($inspectionSummary['late_fees'], 2) }}€ |
@endif
@if($hasPenalties)
| **Total des pénalités** | **{{ number_format($totalPenalties, 2) }}€** |
@endif
| **Montant remboursé** | **{{ number_format($depositRefund, 2) }}€** |

@if($hasPenalties)
## Explication des pénalités

@if($inspectionSummary['damage_costs'] > 0)
- **Dommages :** Coûts de réparation/remplacement des équipements endommagés
@endif

@if($inspectionSummary['late_fees'] > 0)
- **Retard :** Frais de 10€ par jour de retard au-delà de la période prévue
@endif
@endif

## Remboursement

@if($depositRefund > 0)
Un remboursement de **{{ number_format($depositRefund, 2) }}€** sera effectué sur votre moyen de paiement original sous 3-5 jours ouvrés.
@else
Aucun remboursement n'est dû car le montant des pénalités correspond au dépôt de garantie.
@endif

@component('mail::button', ['url' => config('app.frontend_url') . '/rental-orders/' . $orderLocation->id])
Voir le détail complet
@endcomponent

Merci d'avoir choisi nos services de location !

L'équipe {{ config('app.name') }}
@endcomponent
