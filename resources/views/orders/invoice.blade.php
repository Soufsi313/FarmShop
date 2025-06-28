<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture #{{ $order->order_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 20px;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 10px;
        }
        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .invoice-details, .customer-details {
            width: 48%;
        }
        .invoice-details h3, .customer-details h3 {
            color: #2563eb;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .invoice-details p, .customer-details p {
            margin: 5px 0;
            line-height: 1.4;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            background-color: #2563eb;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }
        .items-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e5e7eb;
        }
        .items-table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .totals {
            width: 50%;
            margin-left: auto;
            margin-bottom: 30px;
        }
        .totals table {
            width: 100%;
            border-collapse: collapse;
        }
        .totals td {
            padding: 8px 15px;
            border-bottom: 1px solid #e5e7eb;
        }
        .totals .total-row {
            background-color: #2563eb;
            color: white;
            font-weight: bold;
            font-size: 16px;
        }
        .footer {
            text-align: center;
            color: #6b7280;
            font-size: 12px;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
            margin-top: 30px;
        }
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-confirmed { background-color: #059669; color: white; }
        .status-shipped { background-color: #dc2626; color: white; }
        .status-delivered { background-color: #16a34a; color: white; }
        .status-pending { background-color: #d97706; color: white; }
        .status-cancelled { background-color: #6b7280; color: white; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">FarmShop</div>
        <p>Votre boutique agricole de confiance</p>
    </div>

    <div class="invoice-info">
        <div class="invoice-details">
            <h3>Détails de la facture</h3>
            <p><strong>Numéro de facture:</strong> {{ $order->order_number }}</p>
            <p><strong>Date de commande:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
            @if($order->confirmed_at)
                <p><strong>Date de confirmation:</strong> {{ $order->confirmed_at->format('d/m/Y H:i') }}</p>
            @endif
            <p><strong>Statut:</strong> 
                <span class="status-badge status-{{ strtolower($order->status) }}">
                    {{ $order->status_label }}
                </span>
            </p>
            <p><strong>Mode de paiement:</strong> {{ $order->payment_method_label }}</p>
        </div>

        <div class="customer-details">
            <h3>Informations client</h3>
            <p><strong>{{ $order->user->name }}</strong></p>
            <p>{{ $order->user->email }}</p>
            <p>{{ $order->user->phone ?? 'N/A' }}</p>
            
            <h4 style="margin-top: 20px; color: #2563eb;">Adresse de facturation</h4>
            <p>{{ $order->billing_address }}</p>
            <p>{{ $order->billing_postal_code }} {{ $order->billing_city }}</p>
            <p>{{ $order->billing_country }}</p>

            @if($order->shipping_address !== $order->billing_address)
                <h4 style="margin-top: 20px; color: #2563eb;">Adresse de livraison</h4>
                <p>{{ $order->shipping_address }}</p>
                <p>{{ $order->shipping_postal_code }} {{ $order->shipping_city }}</p>
                <p>{{ $order->shipping_country }}</p>
            @endif
        </div>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>Produit</th>
                <th class="text-center">Quantité</th>
                <th class="text-right">Prix unitaire</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
                <tr>
                    <td>
                        <strong>{{ $item->product_name }}</strong>
                        @if($item->product_description)
                            <br><small style="color: #6b7280;">{{ Str::limit($item->product_description, 100) }}</small>
                        @endif
                    </td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->price, 2) }} €</td>
                    <td class="text-right">{{ number_format($item->subtotal, 2) }} €</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <table>
            <tr>
                <td>Sous-total:</td>
                <td class="text-right">{{ number_format($order->subtotal, 2) }} €</td>
            </tr>
            @if($order->shipping_cost > 0)
                <tr>
                    <td>Frais de livraison:</td>
                    <td class="text-right">{{ number_format($order->shipping_cost, 2) }} €</td>
                </tr>
            @endif
            @if($order->tax_amount > 0)
                <tr>
                    <td>TVA (20%):</td>
                    <td class="text-right">{{ number_format($order->tax_amount, 2) }} €</td>
                </tr>
            @endif
            <tr class="total-row">
                <td>TOTAL:</td>
                <td class="text-right">{{ number_format($order->total_amount, 2) }} €</td>
            </tr>
        </table>
    </div>

    @if($order->notes)
        <div style="margin-bottom: 30px;">
            <h3 style="color: #2563eb;">Notes de commande</h3>
            <p style="background-color: #f9fafb; padding: 15px; border-left: 4px solid #2563eb;">
                {{ $order->notes }}
            </p>
        </div>
    @endif

    <div class="footer">
        <p>FarmShop - Votre partenaire agricole de confiance</p>
        <p>Email: contact@farmshop.fr | Téléphone: +33 1 23 45 67 89</p>
        <p>Merci de votre confiance et à bientôt !</p>
    </div>
</body>
</html>
