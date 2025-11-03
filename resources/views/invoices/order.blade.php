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
            white-space: nowrap;
        }
        
        .items-table th {
            background-color: #28a745;
            color: white;
            font-weight: bold;
        }
        
        .items-table td:first-child {
            white-space: normal;
            min-width: 200px;
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
            <div>Matériel agricole - Achat et location</div>
            <div>{{ __('app.footer.address_line1') }}</div>
            <div>{{ __('app.footer.address_line2') }}</div>
            <div>{{ __('app.footer.tel') }}</div>
            <div>{{ __('app.footer.email') }}</div>
            <div>N° TVA: BE0123456789</div>
        </div>
        
        <div class="invoice-info">
            <div class="invoice-title">{{ __('invoices.invoice') }}</div>
            <div class="invoice-details">
                <div><strong>{{ __('invoices.invoice_number') }}:</strong> {{ $order->invoice_number }}</div>
                <div><strong>{{ __('invoices.order_number') }}:</strong> {{ $order->order_number }}</div>
                <div><strong>{{ __('invoices.invoice_date') }}:</strong> {{ $order->created_at->format('d/m/Y') }}</div>
                <div><strong>{{ __('invoices.due_date') }}:</strong> {{ $order->created_at->addDays(30)->format('d/m/Y') }}</div>
                <div class="status-badge status-{{ $order->payment_status }}">
                    {{ ucfirst($order->payment_status_label) }}
                </div>
            </div>
        </div>
    </div>
    
    <!-- Adresses -->
    <div class="addresses">
        <div class="address-box">
            <div class="address-title">{{ __('invoices.billing_address') }}</div>
            <div>{{ $order->billing_address['name'] }}</div>
            <div>{{ $order->billing_address['address'] }}</div>
            <div>{{ $order->billing_address['postal_code'] }} {{ $order->billing_address['city'] }}</div>
            <div>{{ $order->billing_address['country'] }}</div>
        </div>
        
        <div class="address-box">
            <div class="address-title">{{ __('invoices.shipping_address') }}</div>
            <div>{{ $order->shipping_address['name'] }}</div>
            <div>{{ $order->shipping_address['address'] }}</div>
            <div>{{ $order->shipping_address['postal_code'] }} {{ $order->shipping_address['city'] }}</div>
            <div>{{ $order->shipping_address['country'] }}</div>
        </div>
    </div>
    
    <!-- Informations client -->
    <div class="order-summary">
        <h3 style="margin-top: 0; color: #28a745;">{{ __('invoices.customer_info') }}</h3>
        <div class="summary-row">
            <span><strong>{{ __('invoices.client') }}:</strong></span>
            <span>{{ $order->user->name }}</span>
        </div>
        <div class="summary-row">
            <span><strong>{{ __('invoices.email') }}:</strong></span>
            <span>{{ $order->user->email }}</span>
        </div>
        <div class="summary-row">
            <span><strong>{{ __('invoices.phone') }}:</strong></span>
            <span>{{ $order->user->phone ?? __('invoices.not_provided') }}</span>
        </div>
    </div>
    
    <!-- Informations de commande -->
    <div class="order-summary">
        <h3 style="margin-top: 0; color: #28a745;">{{ __('invoices.order_details') }}</h3>
        <div class="summary-row">
            <span><strong>{{ __('invoices.status') }}:</strong></span>
            <span>{{ $order->status_label }}</span>
        </div>
        <div class="summary-row">
            <span><strong>{{ __('invoices.payment_method') }}:</strong></span>
            <span>{{ $order->payment_method_label }}</span>
        </div>
        @if($order->tracking_number)
        <div class="summary-row">
            <span><strong>{{ __('invoices.tracking_number') }}:</strong></span>
            <span>{{ $order->tracking_number }}</span>
        </div>
        @endif
        @if($order->shipped_at)
        <div class="summary-row">
            <span><strong>{{ __('invoices.shipping_date') }}:</strong></span>
            <span>{{ $order->shipped_at->format('d/m/Y H:i') }}</span>
        </div>
        @endif
        @if($order->delivered_at)
        <div class="summary-row">
            <span><strong>{{ __('invoices.delivery_date') }}:</strong></span>
            <span>{{ $order->delivered_at->format('d/m/Y H:i') }}</span>
        </div>
        @endif
    </div>
    
    <!-- Tableau des articles -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 50%;">{{ __("invoices.product") }}</th>
                <th style="width: 15%;" class="text-center">{{ __("invoices.quantity") }}</th>
                <th style="width: 15%;" class="text-right">{{ __("invoices.unit_price") }}</th>
                <th style="width: 20%;" class="text-right">{{ __("invoices.total") }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>
                    <strong>{{ trans_product($item->product, 'name') }}</strong>
                    @if($item->product->category)
                    <br><small style="color: #666;">{{ trans_category($item->product->category, 'name') }}</small>
                    @endif
                    @if($item->product->sku)
                    <br><small style="color: #666;">{{ __('invoices.ref_label') }}: {{ $item->product->sku }}</small>
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
                <td colspan="3" class="text-right"><strong>{{ __('invoices.subtotal_ht') }}:</strong></td>
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
                    <td colspan="3" class="text-right"><strong>{{ __('invoices.vat') }} ({{ number_format($taxDetail['rate'], 0) }}%) {{ __('app.orders.on') }} {{ number_format($taxDetail['subtotal_ht'], 2, ',', ' ') }} €:</strong></td>
                    <td class="text-right"><strong>{{ number_format($taxDetail['tax_amount'], 2, ',', ' ') }} €</strong></td>
                </tr>
                @endforeach
            @else
                {{-- Affichage simple si un seul taux TVA --}}
                @php $singleTaxDetail = reset($taxDetails); @endphp
                <tr>
                    <td colspan="3" class="text-right"><strong>{{ __('invoices.vat') }} ({{ number_format($singleTaxDetail['rate'], 0) }}%):</strong></td>
                    <td class="text-right"><strong>{{ number_format($order->tax_amount, 2, ',', ' ') }} €</strong></td>
                </tr>
            @endif
            @endif
            @if($order->shipping_cost > 0)
            <tr>
                <td colspan="3" class="text-right"><strong>{{ __('invoices.shipping_cost') }}:</strong></td>
                <td class="text-right"><strong>{{ number_format($order->shipping_cost, 2, ',', ' ') }} €</strong></td>
            </tr>
            @endif
            @if($order->discount_amount > 0)
            <tr>
                <td colspan="3" class="text-right"><strong>{{ __('invoices.discount') }}:</strong></td>
                <td class="text-right"><strong>-{{ number_format($order->discount_amount, 2, ',', ' ') }} €</strong></td>
            </tr>
            @endif
            <tr class="total-row">
                <td colspan="3" class="text-right"><strong>{{ __('invoices.total_ttc') }}:</strong></td>
                <td class="text-right"><strong>{{ number_format($order->total_amount, 2, ',', ' ') }} €</strong></td>
            </tr>
        </tfoot>
    </table>
    
    <!-- Informations de paiement -->
    @if($order->payment_status === 'paid')
    <div class="payment-info">
        <h3 style="margin-top: 0; color: #28a745;">{{ __('invoices.payment_completed') }}</h3>
        <p>{{ __('invoices.payment_completed_message', [
            'date' => $order->updated_at->format('d/m/Y à H:i'),
            'name' => $order->user->name,
            'method' => match($order->payment_method) {
                'stripe', 'card' => __('invoices.payment_card'),
                'paypal' => 'PayPal',
                'bank_transfer' => __('invoices.payment_transfer'),
                default => ucfirst($order->payment_method)
            }
        ]) }}</p>
    </div>
    @else
    <div class="payment-info">
        <h3 style="margin-top: 0; color: #856404;">{{ __('invoices.payment_pending') }}</h3>
        <p>{{ __('invoices.payment_pending_message') }}</p>
    </div>
    @endif
    
    <!-- Pied de page -->
    <div class="footer">
        <div style="text-align: center;">
            <p><strong>{{ __('invoices.sale_conditions') }}:</strong></p>
            <p>{{ __('invoices.sale_conditions_text') }}</p>
        </div>
        
        <div style="text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd;">
            <p><strong>FarmShop - {{ __('invoices.company_title') }}</strong></p>
            <p>{{ __('app.footer.address_line1') }}, {{ __('app.footer.address_line2') }} | {{ __('app.footer.tel') }} | {{ __('app.footer.email') }}</p>
            <p>{{ __('invoices.vat_number') }}: BE0123456789</p>
        </div>
        
        <div style="text-align: center; margin-top: 10px; color: #999; font-size: 10px;">
            <p>{{ __('invoices.invoice_generated') }} {{ now()->format('d/m/Y à H:i') }}</p>
        </div>
    </div>
</body>
</html>
