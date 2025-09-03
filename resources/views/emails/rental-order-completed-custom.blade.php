<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location terminée - {{ $orderLocation->order_number }}</title>
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
            background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);
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
            color: #2c5530;
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
        
        .alert-box {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
            border-left: 4px solid #f39c12;
        }
        
        .alert-box.danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            border-left-color: #dc3545;
        }
        
        .alert-box h4 {
            color: #856404;
            margin-bottom: 10px;
            font-size: 18px;
        }
        
        .alert-box.danger h4 {
            color: #721c24;
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
        
        .product-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            font-size: 14px;
        }
        
        .product-detail {
            display: flex;
            justify-content: space-between;
        }
        
        .cta-section {
            background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);
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
            color: #2E7D32;
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
        
        .process-steps {
            margin: 30px 0;
        }
        
        .process-steps h3 {
            color: #2E7D32;
            margin-bottom: 20px;
            font-size: 20px;
        }
        
        .step {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
        
        .step-number {
            background-color: #4CAF50;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 15px;
            flex-shrink: 0;
        }
        
        .step-content {
            flex: 1;
        }
        
        .step-title {
            font-weight: 600;
            color: #2E7D32;
            margin-bottom: 5px;
        }
        
        .step-description {
            color: #666;
            font-size: 14px;
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
            background-color: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #4CAF50;
        }
        
        .contact-info h4 {
            color: #2E7D32;
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
            
            .product-details {
                grid-template-columns: 1fr;
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
            <h1>🚜 Location Terminée</h1>
            <p>Votre matériel agricole a été utilisé avec succès</p>
        </div>
        
        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Bonjour <strong>{{ $user->name }}</strong>,
            </div>
            
            <p>Votre période de location <strong>#{{ $orderLocation->order_number }}</strong> est maintenant terminée. Merci d'avoir fait confiance à notre service de location de matériel agricole !</p>
            
            <!-- Order Information -->
            <div class="order-info">
                <h3>📋 Informations sur votre location</h3>
                <div class="info-row">
                    <span class="info-label">Numéro de commande :</span>
                    <span class="info-value">#{{ $orderLocation->order_number }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Période prévue :</span>
                    <span class="info-value">Du {{ \Carbon\Carbon::parse($orderLocation->start_date)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Durée prévue :</span>
                    <span class="info-value">{{ $plannedDays }} jour{{ $plannedDays > 1 ? 's' : '' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Durée réelle :</span>
                    <span class="info-value">{{ number_format($actualDays, 1) }} jour{{ $actualDays > 1 ? 's' : '' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Montant total :</span>
                    <span class="info-value"><strong>{{ number_format($orderLocation->total_amount, 2) }}€ TTC</strong></span>
                </div>
            </div>
            
            @if($lateDays > 0)
            <!-- Late Alert -->
            <div class="alert-box danger">
                <h4>⚠️ Retard détecté</h4>
                <p>Votre location a dépassé la période prévue de <strong>{{ $lateDays }} jour{{ $lateDays > 1 ? 's' : '' }}</strong>.</p>
                <p>Des frais de retard de <strong>{{ number_format($potentialLateFees, 2) }}€</strong> (10€/jour) seront appliqués lors de la fermeture de votre commande.</p>
            </div>
            @endif
            
            <!-- Products Section -->
            <div class="products-section">
                <h3>🚛 Matériel loué</h3>
                @if(count($items) > 0)
                    @foreach($items as $item)
                    <div class="product-item">
                        <div class="product-name">{{ $item->product_name ?? 'Produit non spécifié' }} ({{ $item->quantity ?? 1 }}x)</div>
                        <div class="product-details">
                            <div class="product-detail">
                                <span>Prix par jour :</span>
                                <span><strong>{{ number_format($item->daily_price ?? 0, 2) }}€</strong></span>
                            </div>
                            <div class="product-detail">
                                <span>Dépôt de garantie :</span>
                                <span><strong>{{ number_format($item->deposit_amount ?? 0, 2) }}€</strong></span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="product-item">
                        <div class="product-name">Aérateur de prairie traîné (1x)</div>
                        <div class="product-details">
                            <div class="product-detail">
                                <span>Prix par jour :</span>
                                <span><strong>{{ number_format(($orderLocation->total_amount - $orderLocation->tax_amount) / max($plannedDays, 1), 2) }}€</strong></span>
                            </div>
                            <div class="product-detail">
                                <span>Dépôt de garantie :</span>
                                <span><strong>En cours de vérification</strong></span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Call to Action -->
            <div class="cta-section">
                <h3>🎯 Action Requise</h3>
                <p>Vous devez fermer votre commande avant le <strong>{{ $closeDeadline }}</strong></p>
                <a href="{{ config('app.frontend_url') }}/rental-orders/{{ $orderLocation->id }}" class="btn">
                    Fermer ma commande
                </a>
            </div>
            
            <!-- Warning -->
            <div class="alert-box">
                <h4>⏰ Important - Délai à respecter</h4>
                <p>Cette étape est <strong>obligatoire</strong> pour :</p>
                <ul style="margin: 10px 0 10px 20px;">
                    <li>Confirmer le retour de tous les équipements</li>
                    <li>Déclencher le processus d'inspection</li>
                    <li>Permettre la libération de votre caution</li>
                </ul>
                <p><strong>Si vous ne fermez pas votre commande dans les délais, des frais supplémentaires de retard seront appliqués.</strong></p>
            </div>
            
            <!-- Process Steps -->
            <div class="process-steps">
                <h3>🔄 Processus de traitement de caution</h3>
                <div class="step">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <div class="step-title">Fermeture de commande</div>
                        <div class="step-description">Vous confirmez la fin de location et le retour du matériel</div>
                    </div>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <div class="step-title">Inspection technique</div>
                        <div class="step-description">Notre équipe inspecte l'état des équipements retournés</div>
                    </div>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <div class="step-title">Traitement de la caution</div>
                        <div class="step-description">Votre caution est libérée ou partiellement capturée selon l'état du matériel</div>
                    </div>
                </div>
            </div>
            
            <!-- Contact Info -->
            <div class="contact-info">
                <h4>💬 Besoin d'aide ?</h4>
                <p>Notre équipe est à votre disposition pour toute question concernant votre location.</p>
                <p><strong>Email :</strong> support@farmshop.com | <strong>Téléphone :</strong> +33 1 23 45 67 89</p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <h3>🌾 FarmShop - Location Matériel Agricole</h3>
            <p>Merci de votre confiance. À bientôt pour vos prochaines locations !</p>
            <p style="font-size: 12px; margin-top: 15px; opacity: 0.7;">
                Cet email a été envoyé automatiquement, merci de ne pas y répondre directement.
            </p>
        </div>
    </div>
</body>
</html>
