<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport d'Inspection - {{ $orderLocation->order_number }}</title>
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
        
        .status-badge {
            background-color: #d4edda;
            color: #155724;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
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
        
        .condition-good { color: #28a745; font-weight: 600; }
        .condition-damaged { color: #fd7e14; font-weight: 600; }
        .condition-lost { color: #dc3545; font-weight: 600; }
        
        .inspection-notes {
            margin-top: 10px;
            padding: 10px;
            background-color: #e3f2fd;
            border-left: 4px solid #2196f3;
            border-radius: 0 4px 4px 0;
            font-size: 14px;
            color: #1565c0;
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
        
        .financial-summary {
            background-color: #f8f9fa;
            border: 2px solid #4CAF50;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }
        
        .financial-summary h3 {
            color: #2E7D32;
            margin-bottom: 15px;
            font-size: 20px;
            text-align: center;
        }
        
        .financial-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #dee2e6;
        }
        
        .financial-row.total {
            border-bottom: none;
            border-top: 2px solid #4CAF50;
            padding-top: 15px;
            margin-top: 15px;
            font-weight: 700;
            font-size: 18px;
            background-color: #e8f5e9;
            margin-left: -20px;
            margin-right: -20px;
            padding-left: 20px;
            padding-right: 20px;
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
            
            .info-row, .financial-row {
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
            <h1>📋 Inspection Terminée</h1>
            <p>Rapport d'inspection de votre matériel agricole retourné</p>
        </div>
        
        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Bonjour <strong>{{ $user->first_name ?? $user->name }}</strong>,
            </div>
            
            <p>Nous avons terminé l'inspection de votre matériel agricole retourné. Voici le rapport détaillé de notre inspection professionnelle.</p>
            
            <!-- Order Information -->
            <div class="order-info">
                <h3>📦 Informations de la location</h3>
                <div class="info-row">
                    <span class="info-label">Numéro de commande :</span>
                    <span class="info-value"><strong>{{ $orderLocation->order_number }}</strong></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Période de location :</span>
                    <span class="info-value">Du {{ $orderLocation->start_date->format('d/m/Y') }} au {{ $orderLocation->end_date->format('d/m/Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Date de retour effective :</span>
                    <span class="info-value">{{ $orderLocation->actual_return_date ? $orderLocation->actual_return_date->format('d/m/Y à H:i') : 'À définir' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Statut :</span>
                    <span class="info-value">
                        <span class="status-badge">✅ Inspection Terminée</span>
                    </span>
                </div>
            </div>
            
            <!-- Products Section -->
            <div class="products-section">
                <h3>🛠️ Matériel inspecté</h3>
                @if($orderLocation->orderItemLocations && $orderLocation->orderItemLocations->count() > 0)
                    @foreach($orderLocation->orderItemLocations as $item)
                    <div class="product-item">
                        <div class="product-name">{{ $item->product->name ?? 'Produit non spécifié' }}</div>
                        <div class="product-details">
                            <div class="product-detail">
                                <span>Quantité :</span>
                                <span><strong>{{ $item->quantity }}</strong></span>
                            </div>
                            <div class="product-detail">
                                <span>État après retour :</span>
                                <span>
                                    @if($item->condition_at_return === 'excellent')
                                        <span class="condition-good">✅ Excellent état</span>
                                    @elseif($item->condition_at_return === 'good')
                                        <span class="condition-good">✅ Bon état</span>
                                    @elseif($item->condition_at_return === 'poor')
                                        <span class="condition-damaged">⚠️ Mauvais état</span>
                                    @else
                                        <span class="condition-good">✅ État non spécifié</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                        @if($item->item_inspection_notes)
                        <div class="inspection-notes">
                            <strong>Notes d'inspection :</strong> {{ $item->item_inspection_notes }}
                        </div>
                        @endif
                    </div>
                    @endforeach
                @else
                    <div class="product-item">
                        <div class="product-name">Articles de location</div>
                        <p style="color: #666; font-style: italic;">Détails des articles non disponibles dans ce rapport.</p>
                    </div>
                @endif
            </div>
            

            
            <!-- Financial Summary -->
            <div class="financial-summary">
                <h3>💰 Résumé financier</h3>
                <div class="financial-row">
                    <span>Montant initial de la location :</span>
                    <span><strong>{{ number_format($orderLocation->total_amount, 2) }}€</strong></span>
                </div>
                @if($orderLocation->deposit_amount)
                <div class="financial-row">
                    <span>Dépôt de garantie :</span>
                    <span><strong>{{ number_format($orderLocation->deposit_amount, 2) }}€</strong></span>
                </div>
                @endif
                @if($orderLocation->late_fees && $orderLocation->late_fees > 0 && $orderLocation->late_days > 0)
                <div class="financial-row" style="color: #fd7e14;">
                    <span>Frais de retard ({{ abs($orderLocation->late_days) }} jour{{ abs($orderLocation->late_days) > 1 ? 's' : '' }}) :</span>
                    <span><strong>{{ number_format($orderLocation->late_fees, 2) }}€</strong></span>
                </div>
                @endif
                @if($orderLocation->damage_cost && $orderLocation->damage_cost > 0)
                <div class="financial-row" style="color: #dc3545;">
                    <span>Frais de dégâts :</span>
                    <span><strong>{{ number_format($orderLocation->damage_cost, 2) }}€</strong></span>
                </div>
                @endif
                @if((($orderLocation->late_fees ?? 0) > 0 && $orderLocation->late_days > 0) || ($orderLocation->damage_cost ?? 0) > 0)
                <div class="financial-row total" style="color: #dc3545;">
                    <span>Total des pénalités :</span>
                    <span><strong>{{ number_format((($orderLocation->late_fees ?? 0) * ($orderLocation->late_days > 0 ? 1 : 0)) + ($orderLocation->damage_cost ?? 0), 2) }}€</strong></span>
                </div>
                @endif
                <div class="financial-row total">
                    <span>Libération de caution :</span>
                    <span><strong>{{ number_format($orderLocation->deposit_refund ?? 0, 2) }}€</strong></span>
                </div>
            </div>
            
            <!-- Call to Action -->
            <div class="cta-section">
                <h3>📱 Consulter mes locations</h3>
                <p>Accédez à votre espace client pour voir toutes vos locations</p>
                <a href="{{ config('app.url') }}/rental-orders" class="btn">
                    Voir mes locations
                </a>
            </div>
            
            <!-- Contact Info -->
            <div class="contact-info">
                <h4>� Besoin d'aide ?</h4>
                <p>Notre équipe est à votre disposition pour toute question concernant cette inspection.</p>
                <p><strong>Email :</strong> s.mef2703@gmail.com | <strong>Téléphone :</strong> 01 23 45 67 89</p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <h3>🌾 FarmShop - Location Matériel Agricole</h3>
            <p>Merci de votre confiance. Votre inspection est maintenant terminée !</p>
            <p style="font-size: 12px; margin-top: 15px; opacity: 0.7;">
                Cet email a été envoyé automatiquement depuis s.mef2703@gmail.com
            </p>
        </div>
    </div>
</body>
</html>
