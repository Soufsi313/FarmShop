<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commande en préparation</title>
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
            background-color: #fd7e14;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 30px;
        }
        .order-info {
            background-color: #fff3cd;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
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
            background-color: #fd7e14;
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
            <h1>📦 Préparation en cours</h1>
            <p>Votre commande est en préparation</p>
        </div>
        
        <div class="content">
            <p>Bonjour <strong>{{ $order->user->name }}</strong>,</p>
            
            <p>Votre commande <strong>{{ $order->order_number }}</strong> est actuellement en cours de préparation dans nos entrepôts.</p>
            
            <div class="order-info">
                <h3>📋 Statut actuel</h3>
                <p>✅ <strong>Commande confirmée</strong></p>
                <p>📦 <strong>Préparation en cours</strong> - Maintenant</p>
                <p>🚚 <strong>Expédition</strong> - Prochainement</p>
                <p>🏠 <strong>{{ __("app.ecommerce.shipping") }}</strong> - Estimée dans 2-3 jours ouvrés</p>
            </div>
            
            <p>Nos équipes préparent soigneusement votre commande pour vous garantir des produits frais et de qualité. Vous recevrez un nouvel email dès que votre colis sera expédié avec votre numéro de suivi.</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="#" class="btn">Suivre ma commande</a>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>FarmShop</strong> - E-commerce de produits agricoles</p>
            <p>Une question ? Contactez-nous : contact@farmshop.fr</p>
        </div>
    </div>
</body>
</html>
