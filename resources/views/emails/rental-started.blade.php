<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre location a démarré</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #28a745; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { background: #f8f9fa; padding: 30px; border-radius: 0 0 5px 5px; }
        .alert { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .item { background: white; padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 4px solid #28a745; }
        .footer { text-align: center; margin-top: 30px; color: #666; font-size: 0.9em; }
        .btn { display: inline-block; background: #28a745; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>� Votre location a démarré !</h1>
            <p>Commande {{ $orderLocation->order_number }}</p>
        </div>
        
        <div class="content">
            <p>Bonjour {{ $user->name }},</p>
            
            <div class="alert">
                <strong>🎉 Excellente nouvelle !</strong><br>
                Votre location a officiellement commencé aujourd'hui.
            </div>
            
            <h3>� Vos articles en location :</h3>
            @foreach($orderLocation->orderItemLocations as $item)
            <div class="item">
                <strong>{{ $item->product->name }}</strong><br>
                Quantité : {{ $item->quantity }}<br>
                Prix journalier : {{ number_format($item->daily_price, 2) }}€
            </div>
            @endforeach
            
            <div class="alert">
                <h4>⚠️ Rappel important :</h4>
                <p>Votre location se termine le <strong>{{ $orderLocation->end_date->format('d/m/Y') }}</strong>.</p>
                <p>Vous recevrez un rappel 24h avant la date de fin. Pensez à retourner le matériel en bon état pour éviter des frais supplémentaires.</p>
            </div>
            
            <div style="text-align: center;">
                <a href="{{ url('/my-rentals') }}" class="btn">📱 Gérer ma location</a>
            </div>
            
            <h3>📞 Besoin d'aide ?</h3>
            <p>Si vous avez des questions ou rencontrez un problème, n'hésitez pas à nous contacter :</p>
            <ul>
                <li>📧 Email : support@farmshop.com</li>
                <li>📱 WhatsApp : +33 6 XX XX XX XX</li>
            </ul>
        </div>
        
        <div class="footer">
            <p>Merci de faire confiance à FarmShop !</p>
            <p>Cet email a été envoyé automatiquement suite au démarrage de votre location.</p>
        </div>
    </div>
</body>
</html>
