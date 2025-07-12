<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commande annulée</title>
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
            background-color: #dc3545;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 30px;
        }
        .order-info {
            background-color: #f8d7da;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .refund-info {
            background-color: #d1ecf1;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #17a2b8;
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
            <h1>❌ Commande annulée</h1>
            <p>Votre commande a été annulée</p>
        </div>
        
        <div class="content">
            <p>Bonjour <strong>{{ $order->user->name }}</strong>,</p>
            
            <p>Nous vous informons que votre commande <strong>{{ $order->order_number }}</strong> a été annulée.</p>
            
            <div class="order-info">
                <h3>📋 Détails de l'annulation</h3>
                <p><strong>Numéro de commande :</strong> {{ $order->order_number }}</p>
                <p><strong>Date d'annulation :</strong> {{ $order->cancelled_at ? $order->cancelled_at->format('d/m/Y à H:i') : 'Maintenant' }}</p>
                @if($order->cancellation_reason)
                <p><strong>Raison :</strong> {{ $order->cancellation_reason }}</p>
                @endif
                <p><strong>Montant de la commande :</strong> {{ number_format($order->total_amount, 2, ',', ' ') }} €</p>
            </div>
            
            @if($order->payment_status === 'paid')
            <div class="refund-info">
                <h4>💰 Informations de remboursement</h4>
                <p>Comme votre commande était déjà payée, un remboursement sera automatiquement traité sous 3-5 jours ouvrés sur votre mode de paiement original.</p>
                <p><strong>Montant à rembourser :</strong> {{ number_format($order->total_amount, 2, ',', ' ') }} €</p>
            </div>
            @endif
            
            <p>Nous nous excusons pour tout désagrément que cette annulation pourrait causer. Si vous avez des questions, n'hésitez pas à nous contacter.</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="#" class="btn">Voir nos produits</a>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>FarmShop</strong> - E-commerce de produits agricoles</p>
            <p>Une question ? Contactez-nous : contact@farmshop.fr</p>
            <p>Nous espérons vous revoir bientôt !</p>
        </div>
    </div>
</body>
</html>
