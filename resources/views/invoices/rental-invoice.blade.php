<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture de Location {{ $orderLocation->invoice_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
            color: #333;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            border-bottom: 2px solid #28a745;
            padding-bottom: 20px;
        }
        
        .company-info {
            width: 50%;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
            margin-bottom: 10px;
        }
        
        .invoice-info {
            width: 45%;
            text-align: right;
        }
        
        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            color: #28a745;
            margin-bottom: 10px;
        }
        
        .invoice-details {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }
        
        .addresses {
            display: flex;
            justify-content: space-between;
            margin: 30px 0;
        }
        
        .address-box {
            width: 48%;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        
        .address-title {
            font-weight: bold;
            color: #28a745;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .rental-summary {
            background-color: #e3f2fd;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        
        .items-table th {
            background-color: #28a745;
            color: white;
            font-weight: bold;
        }
        
        .items-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .total-row {
            font-weight: bold;
            background-color: #e9ecef !important;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 11px;
            color: #666;
        }
        
        .payment-info {
            margin: 20px 0;
            padding: 15px;
            background-color: #e3f2fd;
            border-radius: 5px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-paid {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-failed {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .rental-period {
            background-color: #fff3cd;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #ffc107;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- En-tête de la facture -->
    <div class="header">
        <div class="company-info">
            <div class="company-name">{{ $company['name'] }}</div>
            <div>E-commerce de produits agricoles</div>
            <div>{{ $company['address'] }}</div>
            <div>{{ $company['postal_code'] }} {{ $company['city'] }}, {{ $company['country'] }}</div>
            <div>Tél: {{ $company['phone'] }}</div>
            <div>Email: {{ $company['email'] }}</div>
            <div>N° TVA: {{ $company['vat_number'] }}</div>
        </div>
        
        <div class="invoice-info">
            <div class="invoice-title">FACTURE DE LOCATION</div>
            <div class="invoice-details">
                <div><strong>N° Facture:</strong> {{ $orderLocation->invoice_number }}</div>
                <div><strong>N° Location:</strong> {{ $orderLocation->order_number }}</div>
                <div><strong>Date facture:</strong> {{ $orderLocation->created_at->format('d/m/Y') }}</div>
                <div><strong>Date échéance:</strong> {{ $orderLocation->created_at->addDays(30)->format('d/m/Y') }}</div>
                <div class="status-badge status-{{ $orderLocation->payment_status }}">
                    @switch($orderLocation->payment_status)
                        @case('paid')
                            Payé
                            @break
                        @case('pending')
                            En attente
                            @break
                        @case('partially_paid')
                            Partiellement payé
                            @break
                        @case('failed')
                            Échec
                            @break
                        @default
                            {{ ucfirst($orderLocation->payment_status) }}
                    @endswitch
                </div>
            </div>
        </div>
    </div>
    
    <!-- Période de location -->
    <div class="rental-period">
        <h3 style="margin-top: 0; color: #856404;">Période de Location</h3>
        <div class="summary-row">
            <span><strong>Date de début:</strong></span>
            <span>{{ $orderLocation->start_date->format('d/m/Y') }}</span>
        </div>
        <div class="summary-row">
            <span><strong>Date de fin:</strong></span>
            <span>{{ $orderLocation->end_date->format('d/m/Y') }}</span>
        </div>
        <div class="summary-row">
            <span><strong>Durée:</strong></span>
            <span>{{ $orderLocation->rental_days }} jour{{ $orderLocation->rental_days > 1 ? 's' : '' }}</span>
        </div>
        <div class="summary-row">
            <span><strong>Tarif journalier:</strong></span>
            <span>{{ number_format($orderLocation->daily_rate, 2, ',', ' ') }} €/jour</span>
        </div>
    </div>
    
    <!-- Adresses -->
    <div class="addresses">
        <div class="address-box">
            <div class="address-title">ADRESSE DE FACTURATION</div>
            @php $billing = json_decode($orderLocation->billing_address, true); @endphp
            <div>{{ $billing['name'] ?? $user->name }}</div>
            <div>{{ $billing['address'] ?? '' }}</div>
            <div>{{ $billing['postal_code'] ?? '' }} {{ $billing['city'] ?? '' }}</div>
            <div>{{ $billing['country'] ?? 'Belgique' }}</div>
        </div>
        
        <div class="address-box">
            <div class="address-title">ADRESSE DE LIVRAISON</div>
            @php $delivery = json_decode($orderLocation->delivery_address, true); @endphp
            <div>{{ $delivery['name'] ?? $user->name }}</div>
            <div>{{ $delivery['address'] ?? '' }}</div>
            <div>{{ $delivery['postal_code'] ?? '' }} {{ $delivery['city'] ?? '' }}</div>
            <div>{{ $delivery['country'] ?? 'Belgique' }}</div>
        </div>
    </div>
    
    <!-- Informations client -->
    <div class="rental-summary">
        <h3 style="margin-top: 0; color: #28a745;">Informations client</h3>
        <div class="summary-row">
            <span><strong>Client:</strong></span>
            <span>{{ $user->name }}</span>
        </div>
        <div class="summary-row">
            <span><strong>Email:</strong></span>
            <span>{{ $user->email }}</span>
        </div>
        <div class="summary-row">
            <span><strong>Téléphone:</strong></span>
            <span>{{ $user->phone ?? 'Non renseigné' }}</span>
        </div>
    </div>
    
    <!-- Informations de location -->
    <div class="rental-summary">
        <h3 style="margin-top: 0; color: #28a745;">Détails de la location</h3>
        <div class="summary-row">
            <span><strong>Statut:</strong></span>
            <span>
                @switch($orderLocation->status)
                    @case('confirmed')
                        Confirmée
                        @break
                    @case('in_progress')
                        En cours
                        @break
                    @case('returned')
                        Retournée
                        @break
                    @case('inspecting')
                        En inspection
                        @break
                    @case('finished')
                        Terminée
                        @break
                    @case('cancelled')
                        Annulée
                        @break
                    @default
                        {{ ucfirst($orderLocation->status) }}
                @endswitch
            </span>
        </div>
        <div class="summary-row">
            <span><strong>Mode de paiement:</strong></span>
            <span>
                @switch($orderLocation->payment_method)
                    @case('stripe')
                        Carte bancaire
                        @break
                    @case('paypal')
                        PayPal
                        @break
                    @case('bank_transfer')
                        Virement bancaire
                        @break
                    @default
                        {{ ucfirst($orderLocation->payment_method) }}
                @endswitch
            </span>
        </div>
        @if($orderLocation->payment_reference)
        <div class="summary-row">
            <span><strong>Référence de paiement:</strong></span>
            <span>{{ $orderLocation->payment_reference }}</span>
        </div>
        @endif
        @if($orderLocation->confirmed_at)
        <div class="summary-row">
            <span><strong>Date de confirmation:</strong></span>
            <span>{{ $orderLocation->confirmed_at->format('d/m/Y H:i') }}</span>
        </div>
        @endif
        @if($orderLocation->started_at)
        <div class="summary-row">
            <span><strong>Date de début effectif:</strong></span>
            <span>{{ $orderLocation->started_at->format('d/m/Y H:i') }}</span>
        </div>
        @endif
        @if($orderLocation->completed_at)
        <div class="summary-row">
            <span><strong>Date de fin effective:</strong></span>
            <span>{{ $orderLocation->completed_at->format('d/m/Y H:i') }}</span>
        </div>
        @endif
    </div>
    
    <!-- Tableau des articles -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 40%;">Produit</th>
                <th style="width: 10%;" class="text-center">Qté</th>
                <th style="width: 15%;" class="text-right">Prix/jour</th>
                <th style="width: 10%;" class="text-center">Jours</th>
                <th style="width: 15%;" class="text-right">Caution</th>
                <th style="width: 10%;" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>
                    <strong>{{ $item->product_name }}</strong>
                    @if($item->product && $item->product->category)
                    <br><small style="color: #666;">{{ $item->product->category->name }}</small>
                    @endif
                    @if($item->product && $item->product->sku)
                    <br><small style="color: #666;">Réf: {{ $item->product->sku }}</small>
                    @endif
                </td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-right">{{ number_format($item->unit_price_per_day, 2, ',', ' ') }} €</td>
                <td class="text-center">{{ $orderLocation->rental_days }}</td>
                <td class="text-right">{{ number_format($item->deposit_amount, 2, ',', ' ') }} €</td>
                <td class="text-right">{{ number_format($item->total_amount, 2, ',', ' ') }} €</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right"><strong>Sous-total location HT:</strong></td>
                <td class="text-right"><strong>{{ number_format($orderLocation->subtotal, 2, ',', ' ') }} €</strong></td>
            </tr>
            <tr>
                <td colspan="5" class="text-right"><strong>Caution totale:</strong></td>
                <td class="text-right"><strong>{{ number_format($orderLocation->deposit_amount, 2, ',', ' ') }} €</strong></td>
            </tr>
            @if($orderLocation->tax_amount > 0)
            <tr>
                <td colspan="5" class="text-right"><strong>TVA ({{ number_format($orderLocation->tax_rate, 0) }}%):</strong></td>
                <td class="text-right"><strong>{{ number_format($orderLocation->tax_amount, 2, ',', ' ') }} €</strong></td>
            </tr>
            @endif
            @if($orderLocation->late_fees > 0)
            <tr>
                <td colspan="5" class="text-right"><strong>Pénalités de retard ({{ $orderLocation->late_days }} jours):</strong></td>
                <td class="text-right"><strong>{{ number_format($orderLocation->late_fees, 2, ',', ' ') }} €</strong></td>
            </tr>
            @endif
            @if($orderLocation->damage_cost > 0)
            <tr>
                <td colspan="5" class="text-right"><strong>Frais de dommages:</strong></td>
                <td class="text-right"><strong>{{ number_format($orderLocation->damage_cost, 2, ',', ' ') }} €</strong></td>
            </tr>
            @endif
            <tr class="total-row">
                <td colspan="5" class="text-right"><strong>TOTAL TTC:</strong></td>
                <td class="text-right"><strong>{{ number_format($orderLocation->total_amount, 2, ',', ' ') }} €</strong></td>
            </tr>
            @if($orderLocation->deposit_refund !== null)
            <tr>
                <td colspan="5" class="text-right"><strong>Remboursement de caution:</strong></td>
                <td class="text-right"><strong>{{ number_format($orderLocation->deposit_refund, 2, ',', ' ') }} €</strong></td>
            </tr>
            @endif
        </tfoot>
    </table>
    
    <!-- Informations de paiement -->
    @if($orderLocation->payment_status === 'paid')
    <div class="payment-info">
        <h3 style="margin-top: 0; color: #28a745;">Paiement effectué</h3>
        <p>Le paiement de cette facture de location a été effectué le {{ $orderLocation->updated_at->format('d/m/Y à H:i') }} 
        par {{ $orderLocation->payment_method === 'stripe' ? 'carte bancaire' : ucfirst($orderLocation->payment_method) }}.</p>
        @if($orderLocation->deposit_amount > 0)
        <p><strong>Note :</strong> La caution de {{ number_format($orderLocation->deposit_amount, 2, ',', ' ') }} € sera remboursée après inspection du matériel retourné et déduction des éventuelles pénalités.</p>
        @endif
    </div>
    @else
    <div class="payment-info">
        <h3 style="margin-top: 0; color: #856404;">Paiement en attente</h3>
        <p>Cette facture de location est en attente de paiement. Merci de procéder au règlement dans les meilleurs délais.</p>
    </div>
    @endif
    
    <!-- Conditions spécifiques à la location -->
    @if($orderLocation->inspection_notes)
    <div class="payment-info">
        <h3 style="margin-top: 0; color: #28a745;">Notes d'inspection</h3>
        <p>{{ $orderLocation->inspection_notes }}</p>
        @if($orderLocation->inspection_completed_at)
        <p><small>Inspection réalisée le {{ $orderLocation->inspection_completed_at->format('d/m/Y à H:i') }}</small></p>
        @endif
    </div>
    @endif
    
    <!-- Pied de page -->
    <div class="footer">
        <div style="text-align: center;">
            <p><strong>Conditions de location:</strong></p>
            <p>
                Le matériel loué doit être retourné dans l'état où il a été livré. Tout dommage ou perte sera facturé au prix de remplacement.
                En cas de retard de restitution, des pénalités de {{ number_format($orderLocation->late_fee_per_day ?? 10, 0) }} €/jour seront appliquées.
                La caution sera remboursée dans un délai de 7 jours ouvrés après inspection du matériel et déduction des éventuelles pénalités.
            </p>
        </div>
        
        <div style="text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd;">
            <p><strong>{{ $company['name'] }} - E-commerce de produits agricoles</strong></p>
            <p>{{ $company['address'] }}, {{ $company['postal_code'] }} {{ $company['city'] }}, {{ $company['country'] }} | Tél: {{ $company['phone'] }} | Email: {{ $company['email'] }}</p>
            <p>N° TVA: {{ $company['vat_number'] }}</p>
        </div>
        
        <div style="text-align: center; margin-top: 10px; color: #999; font-size: 10px;">
            <p>Facture générée automatiquement le {{ now()->format('d/m/Y à H:i') }}</p>
        </div>
    </div>
</body>
</html>
