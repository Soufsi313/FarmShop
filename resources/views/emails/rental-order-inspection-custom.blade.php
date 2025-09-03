<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport d'Inspection - {{ $orderLocation->order_number }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            background-color: #f8fafc;
        }
        .container {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: 20px;
        }
        .header {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .content {
            padding: 30px;
        }
        .inspection-summary {
            background-color: #f0f9ff;
            border: 2px solid #3b82f6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .inspection-summary h3 {
            color: #1e40af;
            margin-top: 0;
            display: flex;
            align-items: center;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin: 5px 5px 5px 0;
        }
        .status-good { background-color: #dcfce7; color: #166534; }
        .status-damaged { background-color: #fef3c7; color: #92400e; }
        .status-lost { background-color: #fee2e2; color: #991b1b; }
        .financial-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #f9fafb;
        }
        .financial-table th, .financial-table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #e5e7eb;
        }
        .financial-table th {
            background-color: #374151;
            color: white;
            font-weight: 600;
        }
        .financial-table .total-row {
            background-color: #10b981;
            color: white;
            font-weight: bold;
        }
        .item-details {
            background-color: #f9fafb;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #6b7280;
        }
        .item-good { border-left-color: #10b981; }
        .item-damaged { border-left-color: #f59e0b; }
        .item-lost { border-left-color: #ef4444; }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            text-align: center;
            margin: 20px 0;
        }
        .footer {
            background-color: #374151;
            color: #d1d5db;
            padding: 20px;
            text-align: center;
            font-size: 14px;
        }
        .icon {
            width: 20px;
            height: 20px;
            margin-right: 8px;
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📋 Rapport d'Inspection</h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">Location #{{ $orderLocation->order_number }}</p>
        </div>
        
        <div class="content">
            <p><strong>Bonjour {{ $user->name }},</strong></p>
            
            <p>L'inspection de votre commande de location <strong>#{{ $orderLocation->order_number }}</strong> est maintenant terminée.</p>
            
            <div class="inspection-summary">
                <h3>🔍 Résumé de l'inspection</h3>
                <p><strong>Articles inspectés :</strong> {{ $inspectionSummary['total_items'] }} article{{ $inspectionSummary['total_items'] > 1 ? 's' : '' }}</p>
                
                @if($hasGoodItems)
                <span class="status-badge status-good">✅ {{ $inspectionSummary['good_items'] }} en bon état</span>
                @endif
                
                @if($hasDamagedItems)
                <span class="status-badge status-damaged">⚠️ {{ $inspectionSummary['damaged_items'] }} endommagé{{ $inspectionSummary['damaged_items'] > 1 ? 's' : '' }}</span>
                @endif
                
                @if($hasLostItems)
                <span class="status-badge status-lost">❌ {{ $inspectionSummary['lost_items'] }} perdu{{ $inspectionSummary['lost_items'] > 1 ? 's' : '' }}</span>
                @endif
            </div>
            
            <h3>📦 Détail par article</h3>
            @foreach($items as $item)
            <div class="item-details {{ $item->condition_at_return === 'excellent' || $item->condition_at_return === 'good' ? 'item-good' : ($item->condition_at_return === 'poor' ? 'item-damaged' : 'item-lost') }}">
                <h4 style="margin-top: 0;">{{ $item->product_name }} ({{ $item->quantity }}x)</h4>
                
                <p><strong>État au retour :</strong>
                @if($item->condition_at_return === 'excellent')
                    <span class="status-badge status-good">✅ Excellent</span>
                @elseif($item->condition_at_return === 'good')
                    <span class="status-badge status-good">✅ Bon</span>
                @else
                    <span class="status-badge status-damaged">⚠️ Endommagé</span>
                @endif
                </p>
                
                @if($item->item_inspection_notes)
                <p><strong>Notes :</strong> {{ $item->item_inspection_notes }}</p>
                @endif
                
                @if($item->item_damage_cost > 0)
                <p><strong>Coût des dommages :</strong> <span style="color: #dc2626; font-weight: bold;">{{ number_format($item->item_damage_cost, 2) }}€</span></p>
                @endif
            </div>
            @endforeach
            
            <h3>💰 Récapitulatif financier</h3>
            <table class="financial-table">
                <tr>
                    <th>{{ __("app.forms.description") }}</th>
                    <th>Montant</th>
                </tr>
                <tr>
                    <td>Dépôt de garantie initial</td>
                    <td>{{ number_format($orderLocation->deposit_amount, 2) }}€</td>
                </tr>
                @if($inspectionSummary['damage_costs'] > 0)
                <tr>
                    <td>Coûts des dommages</td>
                    <td style="color: #dc2626;">-{{ number_format($inspectionSummary['damage_costs'], 2) }}€</td>
                </tr>
                @endif
                @if($inspectionSummary['late_fees'] > 0)
                <tr>
                    <td>Frais de retard</td>
                    <td style="color: #dc2626;">-{{ number_format($inspectionSummary['late_fees'], 2) }}€</td>
                </tr>
                @endif
                <tr class="total-row">
                    <td><strong>Montant remboursé</strong></td>
                    <td><strong>{{ number_format($depositRefund, 2) }}€</strong></td>
                </tr>
            </table>
            
            @if($hasPenalties)
            <div style="background-color: #fef3c7; border: 1px solid #f59e0b; border-radius: 8px; padding: 15px; margin: 20px 0;">
                <h4 style="color: #92400e; margin-top: 0;">⚠️ Explication des pénalités</h4>
                @if($inspectionSummary['damage_costs'] > 0)
                <p><strong>Dommages :</strong> Coûts de réparation/remplacement des équipements endommagés</p>
                @endif
                @if($inspectionSummary['late_fees'] > 0)
                <p><strong>Retard :</strong> Frais de 10€ par jour de retard au-delà de la période prévue</p>
                @endif
            </div>
            @endif
            
            <h3>🏦 Libération de caution</h3>
            @if($depositRefund > 0)
            <div style="background-color: #dcfce7; border: 2px solid #10b981; border-radius: 8px; padding: 20px; text-align: center;">
                <p style="margin: 0; color: #166534; font-size: 18px;"><strong>Une libération de {{ number_format($depositRefund, 2) }}€ sera effectuée sur votre moyen de paiement original sous 3-5 jours ouvrés.</strong></p>
            </div>
            @else
            <div style="background-color: #fee2e2; border: 2px solid #ef4444; border-radius: 8px; padding: 20px; text-align: center;">
                <p style="margin: 0; color: #991b1b; font-size: 16px;"><strong>Aucune libération n'est due car le montant des pénalités correspond au dépôt de garantie.</strong></p>
            </div>
            @endif
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ config('app.frontend_url') }}/rental-orders/{{ $orderLocation->id }}" class="cta-button">
                    Voir le détail complet
                </a>
            </div>
            
            <p style="margin-top: 30px;">Merci d'avoir choisi nos services de location !</p>
            <p><strong>L'équipe {{ config('app.name') }}</strong></p>
        </div>
        
        <div class="footer">
            <p style="margin: 0;">📧 Cet email a été envoyé automatiquement suite à l'inspection de votre location</p>
            <p style="margin: 5px 0 0 0;">Si vous avez des questions, n'hésitez pas à nous contacter</p>
        </div>
    </div>
</body>
</html>
