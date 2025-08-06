🚜 LOCATION TERMINÉE - ACTION REQUISE

Bonjour {{ $user->name }},

Votre période de location #{{ $orderLocation->order_number }} est maintenant terminée.
Merci d'avoir fait confiance à notre service de location de matériel agricole !

📋 INFORMATIONS SUR VOTRE LOCATION
===============================================
Numéro de commande : #{{ $orderLocation->order_number }}
Période prévue : Du {{ \Carbon\Carbon::parse($orderLocation->start_date)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
Durée prévue : {{ $plannedDays }} jour{{ $plannedDays > 1 ? 's' : '' }}
Durée réelle : {{ number_format($actualDays, 1) }} jour{{ $actualDays > 1 ? 's' : '' }}
Montant total : {{ number_format($orderLocation->total_amount, 2) }}€ TTC

@if($lateDays > 0)
⚠️ RETARD DÉTECTÉ
================
Votre location a dépassé la période prévue de {{ $lateDays }} jour{{ $lateDays > 1 ? 's' : '' }}.
Des frais de retard de {{ number_format($potentialLateFees, 2) }}€ (10€/jour) seront appliqués 
lors de la fermeture de votre commande.
@endif

🚛 MATÉRIEL LOUÉ
================
@if(count($items) > 0)
@foreach($items as $item)
- {{ $item->product_name ?? 'Produit non spécifié' }} ({{ $item->quantity ?? 1 }}x)
  Prix par jour : {{ number_format($item->daily_price ?? 0, 2) }}€
  Dépôt de garantie : {{ number_format($item->deposit_amount ?? 0, 2) }}€

@endforeach
@else
- Aérateur de prairie traîné (1x)
  Prix par jour : {{ number_format(($orderLocation->total_amount - $orderLocation->tax_amount) / max($plannedDays, 1), 2) }}€
  Dépôt de garantie : En cours de vérification
@endif

🎯 ACTION REQUISE - FERMETURE OBLIGATOIRE
==========================================
Vous devez fermer votre commande avant le {{ $closeDeadline }}

Cette étape est OBLIGATOIRE pour :
• Confirmer le retour de tous les équipements
• Déclencher le processus d'inspection
• Permettre le remboursement de votre dépôt

⚠️ Si vous ne fermez pas votre commande dans les délais, 
des frais supplémentaires de retard seront appliqués.

👉 FERMER MA COMMANDE : {{ config('app.frontend_url') }}/rental-orders/{{ $orderLocation->id }}

🔄 PROCESSUS DE REMBOURSEMENT
=============================
1. FERMETURE DE COMMANDE
   Vous confirmez la fin de location et le retour du matériel

2. INSPECTION TECHNIQUE  
   Notre équipe inspecte l'état des équipements retournés

3. REMBOURSEMENT DU DÉPÔT
   Votre dépôt vous est remboursé (déduction faite des éventuels 
   dommages ou frais)

💬 BESOIN D'AIDE ?
==================
Notre équipe est à votre disposition pour toute question 
concernant votre location.

Email : support@farmshop.com
Téléphone : +33 1 23 45 67 89

🌾 FarmShop - Location Matériel Agricole
Merci de votre confiance. À bientôt pour vos prochaines locations !

---
Cet email a été envoyé automatiquement, merci de ne pas y répondre directement.
