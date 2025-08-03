@component('mail::message')

<div style="text-align: center; margin-bottom: 30px;">
    <div style="font-size: 48px; margin-bottom: 10px;">🤖</div>
    <h1 style="color: #2d3748; margin: 0;">Mr Clank</h1>
    <p style="color: #718096; margin: 5px 0;">Assistant Automatique FarmShop</p>
</div>

# Location Finalisée avec Succès

Bonjour **{{ $orderLocation->user->name }}**,

Votre location **#{{ $orderLocation->order_number }}** a été finalisée avec succès !

## 📋 Détails de l'inspection

<table style="width: 100%; margin: 20px 0;">
    <tr style="background-color: #f7fafc;">
        <td style="padding: 10px; border: 1px solid #e2e8f0;"><strong>Période de location</strong></td>
        <td style="padding: 10px; border: 1px solid #e2e8f0;">{{ $orderLocation->start_date->format('d/m/Y') }} - {{ $orderLocation->end_date->format('d/m/Y') }}</td>
    </tr>
    <tr>
        <td style="padding: 10px; border: 1px solid #e2e8f0;"><strong>Date de retour</strong></td>
        <td style="padding: 10px; border: 1px solid #e2e8f0;">{{ now()->format('d/m/Y à H:i') }}</td>
    </tr>
    <tr style="background-color: #f7fafc;">
        <td style="padding: 10px; border: 1px solid #e2e8f0;"><strong>État du matériel</strong></td>
        <td style="padding: 10px; border: 1px solid #e2e8f0;">
            @if($orderLocation->condition === 'good')
                ✅ Excellent état
            @elseif($orderLocation->condition === 'damaged')
                ⚠️ Légèrement endommagé
            @else
                ❌ Perdu/Très endommagé
            @endif
        </td>
    </tr>
    @if($orderLocation->damage_fee > 0)
    <tr>
        <td style="padding: 10px; border: 1px solid #e2e8f0;"><strong>Frais de dommages</strong></td>
        <td style="padding: 10px; border: 1px solid #e2e8f0; color: #e53e3e;">{{ number_format($orderLocation->damage_fee, 2) }}€</td>
    </tr>
    @endif
</table>

@if($orderLocation->inspection_notes)
## 📝 Notes d'inspection

{{ $orderLocation->inspection_notes }}
@endif

## 💰 Caution et remboursement

<table style="width: 100%; margin: 20px 0; background-color: #f0fff4; border: 2px solid #38a169;">
    <tr>
        <td style="padding: 15px; text-align: center;">
            @if(isset($damageFeesTotal) && $damageFeesTotal > 0)
                <h3 style="margin: 0; color: #2d3748;">Remboursement de caution</h3>
                <p style="margin: 10px 0; color: #4a5568;">
                    Caution versée : <strong>{{ number_format($depositAmount, 2) }}€</strong><br>
                    Frais de dommages : <strong style="color: #e53e3e;">{{ number_format($damageFeesTotal, 2) }}€</strong>
                </p>
                <p style="margin: 15px 0; font-size: 24px; color: #38a169; font-weight: bold;">
                    💰 Montant à rembourser : {{ number_format($refundAmount, 2) }}€
                </p>
                <p style="margin: 10px 0; color: #718096; font-size: 14px;">
                    ⚠️ Des frais ont été appliqués suite à l'inspection
                </p>
            @else
                <h3 style="margin: 0; color: #2d3748;">Remboursement intégral !</h3>
                <p style="margin: 15px 0; font-size: 24px; color: #38a169; font-weight: bold;">
                    🎉 {{ number_format($depositAmount ?? $orderLocation->deposit_amount, 2) }}€
                </p>
                <p style="margin: 10px 0; color: #718096; font-size: 14px;">
                    ✅ Aucun dommage constaté - Caution intégralement remboursée
                </p>
            @endif
            <p style="margin: 15px 0; color: #4a5568; font-size: 14px;">
                🏦 Le remboursement sera effectué sous 3-5 jours ouvrés<br>sur votre moyen de paiement original
            </p>
        </td>
    </tr>
</table>

## 🎉 Merci de votre confiance !

Nous espérons que cette location a répondu à vos attentes. N'hésitez pas à nous faire part de vos commentaires.

@component('mail::button', ['url' => route('rental-orders.show', $orderLocation)])
Voir ma location
@endcomponent

---

<div style="text-align: center; margin-top: 30px; padding: 20px; background-color: #f7fafc; border-radius: 8px;">
    <p style="margin: 0; color: #718096; font-size: 14px;">
        🤖 <strong>Message automatique généré par Mr Clank</strong><br>
        Assistant IA du système de gestion FarmShop<br>
        <em>Ce message a été envoyé automatiquement suite à la finalisation de votre location</em>
    </p>
</div>

Cordialement,<br>
**Mr Clank** 🤖<br>
{{ config('app.name') }}

@endcomponent
