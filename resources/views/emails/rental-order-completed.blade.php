@component('mail::message')
# Votre location est terminée - Action requise

Bonjour {{ $user->name }},

Votre période de location **#{{ $orderLocation->order_number }}** est maintenant terminée.

## Informations sur la location

**Période prévue :** Du {{ \Carbon\Carbon::parse($orderLocation->start_date)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }} ({{ $plannedDays }} jours)

**Durée réelle :** {{ $actualDays }} jour{{ $actualDays > 1 ? 's' : '' }}

@if($lateDays > 0)
⚠️ **Retard de {{ $lateDays }} jour{{ $lateDays > 1 ? 's' : '' }}**

Des frais de retard de **{{ number_format($potentialLateFees, 2) }}€** (10€/jour) seront appliqués.
@endif

## Articles loués

@foreach($items as $item)
- **{{ $item->product_name }}** ({{ $item->quantity }}x)
  - Prix par jour : {{ number_format($item->daily_price, 2) }}€
  - Dépôt : {{ number_format($item->deposit_amount, 2) }}€

@endforeach

## ⚠️ ACTION REQUISE - Fermeture obligatoire

**Vous devez fermer votre commande avant le {{ $closeDeadline }}**

Cette étape est obligatoire pour :
1. Confirmer le retour de tous les équipements
2. Déclencher le processus d'inspection
3. Permettre le remboursement de votre dépôt

Si vous ne fermez pas votre commande dans les délais, des frais supplémentaires de retard seront appliqués.

@component('mail::button', ['url' => config('app.frontend_url') . '/rental-orders/' . $orderLocation->id])
Fermer ma commande
@endcomponent

## Processus de remboursement

1. **Fermeture** : Vous fermez la commande
2. **Inspection** : Notre équipe inspecte les équipements retournés
3. **Remboursement** : Votre dépôt vous est remboursé (déduction faite des éventuels dommages ou frais de retard)

L'équipe {{ config('app.name') }}
@endcomponent
