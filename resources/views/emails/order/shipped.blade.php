<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commande expédiée</title>
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
            background-color: #17a2b8;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 30px;
        }
        .order-info {
            background-color: #d1ecf1;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .tracking-box {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            margin: 20px 0;
        }
        .tracking-number {
            font-size: 20px;
            font-weight: bold;
            color: #17a2b8;
            margin: 10px 0;
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
            background-color: #17a2b8;
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
            <h1>🚚 Commande expédiée</h1>
            <p>Votre colis est en route !</p>
        </div>
        
        <div class="content">
            <p>Bonjour <strong>{{ $order->user->name }}</strong>,</p>
            
            <p>Excellente nouvelle ! Votre commande <strong>{{ $order->order_number }}</strong> a été expédiée et est maintenant en route vers vous.</p>
            
            @if($order->tracking_number)
            <div class="tracking-box">
                <h3>📋 Numéro de suivi</h3>
                <div class="tracking-number">{{ $order->tracking_number }}</div>
                <p>Utilisez ce numéro pour suivre votre colis en temps réel</p>
            </div>
            @endif
            
            <div class="order-info">
                <h3>📦 Informations de livraison</h3>
                <p><strong>Adresse de livraison :</strong></p>
                <p>
                    {{ $order->shipping_address['name'] }}<br>
                    {{ $order->shipping_address['address'] }}<br>
                    {{ $order->shipping_address['postal_code'] }} {{ $order->shipping_address['city'] }}<br>
                    {{ $order->shipping_address['country'] }}
                </p>
                @if($order->estimated_delivery)
                <p><strong>Livraison estimée :</strong> {{ $order->estimated_delivery->format('d/m/Y') }}</p>
                @endif
            </div>
            
            <div class="order-info">
                <h3>📋 Statut de livraison</h3>
                <p>✅ <strong>Commande confirmée</strong></p>
                <p>✅ <strong>Préparation terminée</strong></p>
                <p>🚚 <strong>Expédition en cours</strong> - Maintenant</p>
                <p>🏠 <strong>Livraison</strong> - Dans 1-2 jours ouvrés</p>
            </div>
            
            <p>Votre colis devrait arriver dans les prochains jours. Vous recevrez un email de confirmation dès la livraison.</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="#" class="btn">Suivre mon colis</a>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>FarmShop</strong> - E-commerce de produits agricoles</p>
            <p>Une question sur votre livraison ? Contactez-nous : contact@farmshop.fr</p>
        </div>
    </div>
</body>
</html>
