<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rappel - Location se termine demain</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8fafc;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .header {
            background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .header p {
            font-size: 16px;
            opacity: 0.9;
        }
        
        .content {
            padding: 30px 25px;
        }
        
        .greeting {
            font-size: 18px;
            margin-bottom: 25px;
            color: #FF9800;
        }
        
        .alert-box {
            background: linear-gradient(135deg, #FFF3E0 0%, #FFE0B2 100%);
            border-left: 4px solid #FF9800;
            padding: 25px;
            margin: 25px 0;
            border-radius: 0 8px 8px 0;
            text-align: center;
        }
        
        .alert-box h2 {
            color: #E65100;
            margin-bottom: 15px;
            font-size: 24px;
        }
        
        .countdown {
            background-color: #FF9800;
            color: white;
            padding: 15px 25px;
            border-radius: 50px;
            display: inline-block;
            font-size: 18px;
            font-weight: 600;
            margin: 15px 0;
        }
        
        .order-info {
            background-color: #f1f8e9;
            border-left: 4px solid #4CAF50;
            padding: 20px;
            margin: 25px 0;
            border-radius: 0 8px 8px 0;
        }
        
        .order-info h3 {
            color: #2E7D32;
            margin-bottom: 15px;
            font-size: 20px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #e8f5e8;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: 600;
            color: #2E7D32;
        }
        
        .info-value {
            color: #333;
        }
        
        .checklist {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 25px;
            margin: 25px 0;
        }
        
        .checklist h3 {
            color: #FF9800;
            margin-bottom: 20px;
            font-size: 20px;
        }
        
        .checklist-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px;
            background-color: white;
            border-radius: 6px;
            border-left: 3px solid #FF9800;
        }
        
        .checklist-icon {
            background-color: #FF9800;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 14px;
            font-weight: bold;
        }
        
        .products-section {
            margin: 30px 0;
        }
        
        .products-section h3 {
            color: #2E7D32;
            margin-bottom: 20px;
            font-size: 20px;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
        }
        
        .product-item {
            background-color: #fafafa;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .product-name {
            font-weight: 600;
            color: #2E7D32;
            font-size: 16px;
            margin-bottom: 8px;
        }
        
        .cta-section {
            background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            margin: 30px 0;
        }
        
        .cta-section h3 {
            color: white;
            margin-bottom: 15px;
            font-size: 22px;
        }
        
        .cta-section p {
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 20px;
            font-size: 16px;
        }
        
        .btn {
            display: inline-block;
            background-color: #ffffff;
            color: #FF9800;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            background-color: #f5f5f5;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        
        .footer {
            background-color: #2E7D32;
            color: white;
            padding: 25px;
            text-align: center;
        }
        
        .footer h3 {
            margin-bottom: 10px;
            font-size: 20px;
        }
        
        .footer p {
            opacity: 0.9;
            font-size: 14px;
        }
        
        .contact-info {
            margin: 20px 0;
            padding: 20px;
            background-color: #fff3e0;
            border-radius: 8px;
            border-left: 4px solid #FF9800;
        }
        
        .contact-info h4 {
            color: #E65100;
            margin-bottom: 10px;
        }
        
        @media (max-width: 600px) {
            .email-container {
                margin: 0;
                border-radius: 0;
            }
            
            .content {
                padding: 20px 15px;
            }
            
            .info-row {
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>⏰ Rappel Important</h1>
            <p>Votre location se termine demain</p>
        </div>
        
        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Bonjour <strong>{{ $user->name }}</strong>,
            </div>
            
            <div class="alert-box">
                <h2>🚨 Fin de location demain !</h2>
                <p>Votre location <strong>#{{ $orderLocation->order_number }}</strong> se termine demain.</p>
                <div class="countdown">
                    {{ $endTomorrow }} (dans {{ $hoursRemaining }}h)
                </div>
                <p><strong>N'oubliez pas de préparer le retour de votre matériel !</strong></p>
            </div>
            
            <!-- Order Information -->
            <div class="order-info">
                <h3>� Détails de votre location</h3>
                <div class="info-row">
                    <span class="info-label">Numéro de commande :</span>
                    <span class="info-value">#{{ $orderLocation->order_number }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Période de location :</span>
                    <span class="info-value">Du {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Durée totale :</span>
                    <span class="info-value">{{ $totalDays }} jour{{ $totalDays > 1 ? 's' : '' }}</span>
                </div>
            </div>
            
            <!-- Products List -->
            <div class="products-section">
                <h3>📦 Matériel à retourner</h3>
                @foreach($items as $item)
                <div class="product-item">
                    <div class="product-name">{{ $item->product_name }}</div>
                    <div style="color: #666; font-size: 14px;">
                        Quantité : {{ $item->quantity }} | 
                        Prix/jour : {{ number_format($item->daily_rate, 2) }}€
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Checklist -->
            <div class="checklist">
                <h3>✅ Liste de vérification avant retour</h3>
                
                <div class="checklist-item">
                    <div class="checklist-icon">1</div>
                    <div>
                        <strong>Nettoyage du matériel</strong><br>
                        Nettoyez soigneusement tous les équipements
                    </div>
                </div>
                
                <div class="checklist-item">
                    <div class="checklist-icon">2</div>
                    <div>
                        <strong>Vérification de l'état</strong><br>
                        Inspectez le matériel pour détecter d'éventuels dommages
                    </div>
                </div>
                
                <div class="checklist-item">
                    <div class="checklist-icon">3</div>
                    <div>
                        <strong>Accessoires complets</strong><br>
                        Vérifiez que tous les accessoires sont présents
                    </div>
                </div>
                
                <div class="checklist-item">
                    <div class="checklist-icon">4</div>
                    <div>
                        <strong>Photos avant retour</strong><br>
                        Prenez des photos de l'état du matériel (recommandé)
                    </div>
                </div>
            </div>
            
            <!-- Call to Action -->
            <div class="cta-section">
                <h3>📱 Suivez votre location</h3>
                <p>Consultez l'état de votre location et préparez la clôture</p>
                <a href="{{ config('app.frontend_url') }}/rental-orders/{{ $orderLocation->id }}" class="btn">
                    Voir ma location
                </a>
            </div>
            
            <!-- Contact Info -->
            <div class="contact-info">
                <h4>💬 Questions sur le retour ?</h4>
                <p>Notre équipe est disponible pour vous accompagner dans le processus de retour.</p>
                <p><strong>Email :</strong> support@farmshop.com | <strong>Téléphone :</strong> +33 1 23 45 67 89</p>
            </div>
            
            <p style="margin-top: 30px; padding: 20px; background-color: #f8f9fa; border-radius: 8px; border-left: 4px solid #17a2b8;">
                <strong>📌 Rappel automatique :</strong> Vous recevrez un email demain lorsque votre location sera officiellement terminée. 
                Vous pourrez alors clôturer votre commande directement depuis votre espace client.
            </p>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <h3>🌾 FarmShop - Location Matériel Agricole</h3>
            <p>Merci de votre confiance. Bon retour avec votre matériel !</p>
            <p style="font-size: 12px; margin-top: 15px; opacity: 0.7;">
                Cet email de rappel a été envoyé automatiquement 24h avant la fin de votre location.
            </p>
        </div>
    </div>
</body>
</html>
