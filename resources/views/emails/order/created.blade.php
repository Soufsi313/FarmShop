<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de commande</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #28a745;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 30px;
        }
        .order-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
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
            background-color: #f8f9fa;
        }
        .total {
            font-weight: bold;
            font-size: 18px;
            color: #28a745;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✅ Commande confirmée</h1>
            <p>Merci pour votre commande !</p>
        </div>
        
        <div class="content">
            <p>Bonjour <strong>{{ $order->user->name }}</strong>,</p>
            
            <p>Nous avons bien reçu votre commande et nous vous en remercions. Voici le récapitulatif :</p>
            
            <div class="order-info">
                <h3>Informations de commande</h3>
                <p><strong>Numéro de commande :</strong> {{ $order->order_number }}</p>
                <p><strong>Date :</strong> {{ $order->created_at->format('d/m/Y à H:i') }}</p>
                <p><strong>Statut :</strong> {{ $order->status_label }}</p>
                @if($order->invoice_number)
                <p><strong>Numéro de facture :</strong> {{ $order->invoice_number }}</p>
                @endif
            </div>
            
            <h3>Articles commandés</h3>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Quantité</th>
                        <th>Prix unitaire</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->unit_price, 2, ',', ' ') }} €</td>
                        <td>{{ number_format($item->subtotal, 2, ',', ' ') }} €</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"><strong>Total</strong></td>
                        <td class="total">{{ number_format($order->total_amount, 2, ',', ' ') }} €</td>
                    </tr>
                </tfoot>
            </table>
            
            <div class="order-info">
                <h3>Adresse de livraison</h3>
                <p>
                    {{ $order->shipping_address['name'] }}<br>
                    {{ $order->shipping_address['address'] }}<br>
                    {{ $order->shipping_address['postal_code'] }} {{ $order->shipping_address['city'] }}<br>
                    {{ $order->shipping_address['country'] }}
                </p>
            </div>
            
            <p>Votre commande sera traitée dans les plus brefs délais. Vous recevrez un email de confirmation dès que votre commande sera expédiée.</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="#" class="btn">Suivre ma commande</a>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>FarmShop</strong> - E-commerce de produits agricoles</p>
            <p>123 Avenue des Champs, 75001 Paris, France</p>
            <p>Tél: +33 1 23 45 67 89 | Email: contact@farmshop.fr</p>
        </div>
    </div>
</body>
</html>
