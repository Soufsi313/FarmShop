<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commande livrée</title>
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
            background-color: #d4edda;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .return-info {
            background-color: #fff3cd;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #ffc107;
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
            margin: 10px 5px;
        }
        .btn-secondary {
            background-color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Commande livrée</h1>
            <p>Votre commande est arrivée !</p>
        </div>
        
        <div class="content">
            <p>Bonjour <strong>{{ $order->user->name }}</strong>,</p>
            
            <p>Parfait ! Votre commande <strong>{{ $order->order_number }}</strong> a été livrée avec succès{{ $order->delivered_at ? ' le ' . $order->delivered_at->format('d/m/Y à H:i') : '' }}.</p>
            
            <div class="order-info">
                <h3>✅ Livraison confirmée</h3>
                <p><strong>Date de livraison :</strong> {{ $order->delivered_at ? $order->delivered_at->format('d/m/Y à H:i') : 'En cours de traitement' }}</p>
                <p><strong>Adresse de livraison :</strong></p>
                <p>
                    {{ $order->shipping_address['name'] }}<br>
                    {{ $order->shipping_address['address'] }}<br>
                    {{ $order->shipping_address['postal_code'] }} {{ $order->shipping_address['city'] }}
                </p>
            </div>
            
            <p>Nous espérons que vous êtes satisfait(e) de vos produits ! N'hésitez pas à nous faire part de vos commentaires.</p>
            
            <div class="return-info">
                <h4>ℹ️ Informations importantes sur les retours</h4>
                <p><strong>Produits non alimentaires :</strong> Vous disposez de 14 jours à compter de la livraison pour retourner les articles non alimentaires en parfait état.</p>
                <p><strong>Produits alimentaires :</strong> Pour des raisons d'hygiène et de sécurité alimentaire, les produits alimentaires ne peuvent pas être retournés.</p>
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="#" class="btn">Laisser un avis</a>
                <a href="#" class="btn btn-secondary">Historique des commandes</a>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>Merci d'avoir choisi FarmShop !</strong></p>
            <p>Nous espérons vous revoir bientôt pour de nouveaux produits agricoles de qualité.</p>
            <p>Une question ? Contactez-nous : contact@farmshop.fr</p>
        </div>
    </div>
</body>
</html>
