<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Problème de paiement</title>
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
        .payment-info {
            background-color: #f8d7da;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .help-info {
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
            background-color: #dc3545;
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
            <h1>❌ Problème de paiement</h1>
            <p>Le paiement n'a pas pu être traité</p>
        </div>
        
        <div class="content">
            <p>Bonjour <strong>{{ $order->user->name }}</strong>,</p>
            
            <p>Nous rencontrons un problème avec le paiement de votre commande <strong>{{ $order->order_number }}</strong>.</p>
            
            <div class="payment-info">
                <h3>⚠️ Détails du problème</h3>
                <p><strong>Commande :</strong> {{ $order->order_number }}</p>
                <p><strong>Montant :</strong> {{ number_format($order->total_amount, 2, ',', ' ') }} €</p>
                <p><strong>Statut :</strong> Paiement échoué</p>
                <p><strong>Date de la tentative :</strong> {{ $order->updated_at->format('d/m/Y à H:i') }}</p>
            </div>
            
            <div class="help-info">
                <h4>🔧 Que faire maintenant ?</h4>
                <p><strong>1. Vérifiez vos informations de paiement</strong><br>
                Assurez-vous que votre carte bancaire est valide et dispose de fonds suffisants.</p>
                
                <p><strong>2. Réessayez le paiement</strong><br>
                Vous pouvez retenter le paiement en cliquant sur le bouton ci-dessous.</p>
                
                <p><strong>3. Utilisez un autre mode de paiement</strong><br>
                Essayez avec une autre carte ou méthode de paiement.</p>
                
                <p><strong>4. Contactez votre banque</strong><br>
                Si le problème persiste, contactez votre banque pour vérifier si la transaction n'a pas été bloquée.</p>
            </div>
            
            <p><strong>Important :</strong> Votre commande est temporairement suspendue. Elle sera automatiquement annulée si le paiement n'est pas effectué dans les 24 heures.</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="#" class="btn">Réessayer le paiement</a>
                <a href="#" class="btn btn-secondary">Nous contacter</a>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>FarmShop</strong> - E-commerce de produits agricoles</p>
            <p>Besoin d'aide ? Contactez-nous : contact@farmshop.fr | Tél: +33 1 23 45 67 89</p>
        </div>
    </div>
</body>
</html>
