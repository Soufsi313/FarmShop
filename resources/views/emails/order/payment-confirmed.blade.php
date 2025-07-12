<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement confirmé</title>
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
        .payment-info {
            background-color: #d4edda;
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
            <h1>✅ Paiement confirmé</h1>
            <p>Votre paiement a été traité avec succès</p>
        </div>
        
        <div class="content">
            <p>Bonjour <strong>{{ $order->user->name }}</strong>,</p>
            
            <p>Nous avons bien reçu votre paiement pour la commande <strong>{{ $order->order_number }}</strong>. Merci !</p>
            
            <div class="payment-info">
                <h3>💳 Détails du paiement</h3>
                <p><strong>Montant payé :</strong> {{ number_format($order->total_amount, 2, ',', ' ') }} €</p>
                <p><strong>Mode de paiement :</strong> {{ $order->payment_method_label }}</p>
                <p><strong>Date du paiement :</strong> {{ $order->updated_at->format('d/m/Y à H:i') }}</p>
                @if($order->invoice_number)
                <p><strong>Numéro de facture :</strong> {{ $order->invoice_number }}</p>
                @endif
            </div>
            
            <p>Votre commande va maintenant être traitée et préparée. Vous recevrez bientôt un email de confirmation avec les détails de l'expédition.</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="#" class="btn">Télécharger la facture</a>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>FarmShop</strong> - E-commerce de produits agricoles</p>
            <p>Une question ? Contactez-nous : contact@farmshop.fr</p>
        </div>
    </div>
</body>
</html>
