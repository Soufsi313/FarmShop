<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de Location</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #28a745;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 0 0 8px 8px;
        }
        .rental-details {
            background-color: white;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            border-left: 4px solid #28a745;
        }
        .product-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .total {
            font-weight: bold;
            font-size: 1.2em;
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎉 Location Confirmée</h1>
        <p>Votre location #{{ $orderLocation->order_number }} a été confirmée avec succès !</p>
    </div>
    
    <div class="content">
        <h2>Bonjour {{ $user->name }},</h2>
        
        <p>Nous avons bien reçu votre paiement et votre location est maintenant confirmée.</p>
        
        <div class="rental-details">
            <h3>📅 Détails de la location</h3>
            <p><strong>Numéro de location :</strong> {{ $orderLocation->order_number }}</p>
            <p><strong>Période :</strong> Du {{ $orderLocation->start_date->format('d/m/Y') }} au {{ $orderLocation->end_date->format('d/m/Y') }}</p>
            <p><strong>Durée :</strong> {{ $orderLocation->rental_days }} jour{{ $orderLocation->rental_days > 1 ? 's' : '' }}</p>
        </div>
        
        <div class="rental-details">
            <h3>📦 Produits loués</h3>
            @foreach($items as $item)
            <div class="product-item">
                <strong>{{ $item->product_name }}</strong><br>
                Quantité: {{ $item->quantity }} | Prix/jour: {{ number_format($item->unit_price_per_day, 2) }}€
            </div>
            @endforeach
        </div>
        
        <div class="rental-details">
            <h3>💰 Récapitulatif financier</h3>
            <p>Sous-total location : {{ number_format($orderLocation->subtotal, 2) }}€</p>
            <p>Caution : {{ number_format($orderLocation->deposit_amount, 2) }}€</p>
            <p>TVA : {{ number_format($orderLocation->tax_amount, 2) }}€</p>
            <p class="total">Total payé : {{ number_format($orderLocation->total_amount, 2) }}€</p>
        </div>
        
        <div class="rental-details">
            <h3>📋 Prochaines étapes</h3>
            <ul>
                <li>Vous recevrez un email le {{ $orderLocation->start_date->format('d/m/Y') }} pour confirmer le début de votre location</li>
                <li>Un rappel vous sera envoyé la veille de la fin de location</li>
                <li>N'oubliez pas de retourner le matériel à la date prévue pour éviter les frais de retard</li>
            </ul>
        </div>
        
        <p>Vous pouvez consulter et gérer vos locations à tout moment dans votre <a href="{{ route('my-rentals.index') }}">espace client</a>.</p>
        
        <p>Merci de votre confiance !</p>
        <p><strong>L'équipe FarmShop</strong></p>
    </div>
</body>
</html>
