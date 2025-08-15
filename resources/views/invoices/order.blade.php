<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture {{ $order->invoice_number }}</title>
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
        
        .order-summary {
            background-color: #f8f9fa;
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
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- En-tête de la facture -->
    <div class="header">
        <div class="company-info">
            <div class="company-name">FarmShop</div>
            <div>E-commerce de produits agricoles</div>
            <div>123 Avenue des Champs</div>
            <div>75001 Paris, France</div>
            <div>Tél: +33 1 23 45 67 89</div>
            <div>Email: contact@farmshop.fr</div>
            <div>SIRET: 12345678901234</div>
            <div>TVA: FR12345678901</div>
        </div>
        
        <div class="invoice-info">
            <div class="invoice-title">FACTURE</div>
            <div class="invoice-details">
                <div><strong>N° Facture:</strong> {{ $order->invoice_number }}</div>
                <div><strong>N° Commande:</strong> {{ $order->order_number }}</div>
                <div><strong>Date facture:</strong> {{ $order->created_at->format('d/m/Y') }}</div>
                <div><strong>Date échéance:</strong> {{ $order->created_at->addDays(30)->format('d/m/Y') }}</div>
                <div class="status-badge status-{{ $order->payment_status }}">
                    {{ ucfirst($order->payment_status_label) }}
                </div>
            </div>
        </div>
    </div>
    
    <!-- Adresses -->
    <div class="addresses">
        <div class="address-box">
            <div class="address-title">ADRESSE DE FACTURATION</div>
            <div>{{ $order->billing_address['name'] }}</div>
            <div>{{ $order->billing_address['address'] }}</div>
            <div>{{ $order->billing_address['postal_code'] }} {{ $order->billing_address['city'] }}</div>
            <div>{{ $order->billing_address['country'] }}</div>
        </div>
        
        <div class="address-box">
            <div class="address-title">ADRESSE DE LIVRAISON</div>
            <div>{{ $order->shipping_address['name'] }}</div>
            <div>{{ $order->shipping_address['address'] }}</div>
            <div>{{ $order->shipping_address['postal_code'] }} {{ $order->shipping_address['city'] }}</div>
            <div>{{ $order->shipping_address['country'] }}</div>
        </div>
    </div>
    
    <!-- Informations client -->
    <div class="order-summary">
        <h3 style="margin-top: 0; color: #28a745;">Informations client</h3>
        <div class="summary-row">
            <span><strong>Client:</strong></span>
            <span>{{ $order->user->name }}</span>
        </div>
        <div class="summary-row">
            <span><strong>Email:</strong></span>
            <span>{{ $order->user->email }}</span>
        </div>
        <div class="summary-row">
            <span><strong>Téléphone:</strong></span>
            <span>{{ $order->user->phone ?? 'Non renseigné' }}</span>
        </div>
    </div>
    
    <!-- Informations de commande -->
    <div class="order-summary">
        <h3 style="margin-top: 0; color: #28a745;">Détails de la commande</h3>
        <div class="summary-row">
            <span><strong>Statut:</strong></span>
            <span>{{ $order->status_label }}</span>
        </div>
        <div class="summary-row">
            <span><strong>Mode de paiement:</strong></span>
            <span>{{ $order->payment_method_label }}</span>
        </div>
        @if($order->tracking_number)
        <div class="summary-row">
            <span><strong>N° de suivi:</strong></span>
            <span>{{ $order->tracking_number }}</span>
        </div>
        @endif
        @if($order->shipped_at)
        <div class="summary-row">
            <span><strong>Date d'expédition:</strong></span>
            <span>{{ $order->shipped_at->format('d/m/Y H:i') }}</span>
        </div>
        @endif
        @if($order->delivered_at)
        <div class="summary-row">
            <span><strong>Date de livraison:</strong></span>
            <span>{{ $order->delivered_at->format('d/m/Y H:i') }}</span>
        </div>
        @endif
    </div>
    
    <!-- Tableau des articles -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 50%;">{{ __("app.ecommerce.product") }}</th>
                <th style="width: 15%;" class="text-center">{{ __("app.ecommerce.quantity") }}</th>
                <th style="width: 15%;" class="text-right">Prix unitaire</th>
                <th style="width: 20%;" class="text-right">{{ __("app.ecommerce.total") }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>
                    <strong>{{ $item->product->name }}</strong>
                    @if($item->product->category)
                    <br><small style="color: #666;">{{ $item->product->category->name }}</small>
                    @endif
                    @if($item->product->sku)
                    <br><small style="color: #666;">Réf: {{ $item->product->sku }}</small>
                    @endif
                </td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-right">{{ number_format($item->unit_price, 2, ',', ' ') }} €</td>
                <td class="text-right">{{ number_format($item->subtotal, 2, ',', ' ') }} €</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-right"><strong>Sous-total HT:</strong></td>
                <td class="text-right"><strong>{{ number_format($order->subtotal, 2, ',', ' ') }} €</strong></td>
            </tr>
            @if($order->tax_amount > 0)
            @php
                // Calculer les détails TVA par taux pour l'affichage
                $taxDetails = [];
                foreach($order->items as $item) {
                    $taxRate = $item->tax_rate ?? 21; // Default to 21% if not set
                    if (!isset($taxDetails[$taxRate])) {
                        $taxDetails[$taxRate] = [
                            'rate' => $taxRate,
                            'subtotal_ht' => 0,
                            'tax_amount' => 0
                        ];
                    }
                    $taxDetails[$taxRate]['subtotal_ht'] += $item->subtotal;
                    $taxDetails[$taxRate]['tax_amount'] += $item->tax_amount;
                }
            @endphp
            
            @if(count($taxDetails) > 1)
                {{-- Affichage détaillé si plusieurs taux TVA --}}
                @foreach($taxDetails as $taxDetail)
                <tr>
                    <td colspan="3" class="text-right"><strong>TVA ({{ number_format($taxDetail['rate'], 0) }}%) sur {{ number_format($taxDetail['subtotal_ht'], 2, ',', ' ') }} €:</strong></td>
                    <td class="text-right"><strong>{{ number_format($taxDetail['tax_amount'], 2, ',', ' ') }} €</strong></td>
                </tr>
                @endforeach
            @else
                {{-- Affichage simple si un seul taux TVA --}}
                @php $singleTaxDetail = reset($taxDetails); @endphp
                <tr>
                    <td colspan="3" class="text-right"><strong>TVA ({{ number_format($singleTaxDetail['rate'], 0) }}%):</strong></td>
                    <td class="text-right"><strong>{{ number_format($order->tax_amount, 2, ',', ' ') }} €</strong></td>
                </tr>
            @endif
            @endif
            @if($order->shipping_cost > 0)
            <tr>
                <td colspan="3" class="text-right"><strong>Frais de livraison:</strong></td>
                <td class="text-right"><strong>{{ number_format($order->shipping_cost, 2, ',', ' ') }} €</strong></td>
            </tr>
            @endif
            @if($order->discount_amount > 0)
            <tr>
                <td colspan="3" class="text-right"><strong>Remise:</strong></td>
                <td class="text-right"><strong>-{{ number_format($order->discount_amount, 2, ',', ' ') }} €</strong></td>
            </tr>
            @endif
            <tr class="total-row">
                <td colspan="3" class="text-right"><strong>TOTAL TTC:</strong></td>
                <td class="text-right"><strong>{{ number_format($order->total_amount, 2, ',', ' ') }} €</strong></td>
            </tr>
        </tfoot>
    </table>
    
    <!-- Informations de paiement -->
    @if($order->payment_status === 'paid')
    <div class="payment-info">
        <h3 style="margin-top: 0; color: #28a745;">Paiement effectué</h3>
        <p>Le paiement de cette facture a été effectué le {{ $order->updated_at->format('d/m/Y à H:i') }} 
        par {{ $order->payment_method_label }}.</p>
    </div>
    @else
    <div class="payment-info">
        <h3 style="margin-top: 0; color: #856404;">Paiement en attente</h3>
        <p>Cette facture est en attente de paiement. Merci de procéder au règlement dans les meilleurs délais.</p>
    </div>
    @endif
    
    <!-- Pied de page -->
    <div class="footer">
        <div style="text-align: center;">
            <p><strong>Conditions de vente:</strong></p>
            <p>
                Les produits alimentaires ne sont pas échangeables ni remboursables pour des raisons d'hygiène et de sécurité alimentaire.
                Les produits non alimentaires peuvent être retournés dans un délai de 14 jours après livraison, dans leur emballage d'origine et en parfait état.
                Les frais de retour sont à la charge du client sauf en cas de défaut du produit.
            </p>
        </div>
        
        <div style="text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd;">
            <p><strong>FarmShop - E-commerce de produits agricoles</strong></p>
            <p>123 Avenue des Champs, 75001 Paris, France | Tél: +33 1 23 45 67 89 | Email: contact@farmshop.fr</p>
            <p>SIRET: 12345678901234 | TVA: FR12345678901</p>
        </div>
        
        <div style="text-align: center; margin-top: 10px; color: #999; font-size: 10px;">
            <p>Facture générée automatiquement le {{ now()->format('d/m/Y à H:i') }}</p>
        </div>
    </div>
</body>
</html>
