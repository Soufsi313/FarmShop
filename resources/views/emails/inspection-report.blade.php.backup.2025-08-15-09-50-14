<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport d'Inspection - FarmShop</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            background-color: #f8f9fa;
        }
        .container {
            background-color: #ffffff;
            margin: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #2d5a27 0%, #4a7c59 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        .header .logo {
            font-size: 32px;
            margin-bottom: 10px;
        }
        .content {
            padding: 30px;
        }
        .order-info {
            background-color: #f8f9fa;
            border-left: 4px solid #4a7c59;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .order-info h3 {
            margin-top: 0;
            color: #2d5a27;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }
        .info-label {
            font-weight: bold;
            color: #666;
        }
        .info-value {
            color: #333;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-finished {
            background-color: #d4edda;
            color: #155724;
        }
        .items-section {
            margin: 25px 0;
        }
        .item {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin: 10px 0;
            background-color: #fafafa;
        }
        .item-name {
            font-weight: bold;
            color: #2d5a27;
            margin-bottom: 8px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #ddd;
            font-size: 14px;
            color: #666;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #4a7c59 0%, #2d5a27 100%);
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            margin: 15px 0;
            transition: transform 0.2s;
        }
        .button:hover {
            transform: translateY(-2px);
        }
        .contact-info {
            background-color: #e8f5e8;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🚜</div>
            <h1>FarmShop</h1>
            <p>Rapport d'Inspection de Location</p>
        </div>
        
        <div class="content">
            <h2>Bonjour {{ $orderLocation->user->first_name }},</h2>
            
            <p>Nous avons terminé l'inspection de votre matériel agricole retourné. Voici le rapport détaillé :</p>
            
            <div class="order-info">
                <h3>📋 Informations de la Location</h3>
                <div class="info-row">
                    <span class="info-label">Numéro de commande :</span>
                    <span class="info-value"><strong>{{ $orderLocation->order_number }}</strong></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Période de location :</span>
                    <span class="info-value">Du {{ $orderLocation->start_date->format('d/m/Y') }} au {{ $orderLocation->end_date->format('d/m/Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Date de retour :</span>
                    <span class="info-value">{{ $orderLocation->actual_return_date ? $orderLocation->actual_return_date->format('d/m/Y à H:i') : 'En attente' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Statut :</span>
                    <span class="info-value">
                        <span class="status-badge status-finished">✅ Inspection Terminée</span>
                    </span>
                </div>
            </div>
            
            <div class="items-section">
                <h3>🛠️ Matériel Inspecté</h3>
                @foreach($orderLocation->items as $item)
                <div class="item">
                    <div class="item-name">{{ $item->product->name }}</div>
                    <div class="info-row">
                        <span class="info-label">Quantité :</span>
                        <span class="info-value">{{ $item->quantity }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">État après retour :</span>
                        <span class="info-value">
                            @if($item->condition_after_return)
                                {{ $item->condition_after_return }}
                            @else
                                ✅ Bon état
                            @endif
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            
            @if($orderLocation->penalty_amount > 0)
            <div class="order-info" style="border-left-color: #dc3545; background-color: #f8d7da;">
                <h3 style="color: #721c24;">⚠️ Pénalités Appliquées</h3>
                <div class="info-row">
                    <span class="info-label">Montant des pénalités :</span>
                    <span class="info-value"><strong>{{ number_format($orderLocation->penalty_amount, 2) }} €</strong></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Raison :</span>
                    <span class="info-value">{{ $orderLocation->penalty_reason ?? 'Retard ou dommages constatés' }}</span>
                </div>
            </div>
            @endif
            
            <div class="order-info">
                <h3>💰 Résumé Financier</h3>
                <div class="info-row">
                    <span class="info-label">Montant initial :</span>
                    <span class="info-value">{{ number_format($orderLocation->total_amount, 2) }} €</span>
                </div>
                @if($orderLocation->penalty_amount > 0)
                <div class="info-row">
                    <span class="info-label">Pénalités :</span>
                    <span class="info-value">{{ number_format($orderLocation->penalty_amount, 2) }} €</span>
                </div>
                @endif
                <div class="info-row" style="border-bottom: 2px solid #4a7c59; font-weight: bold;">
                    <span class="info-label">Montant final :</span>
                    <span class="info-value">{{ number_format($orderLocation->final_amount ?? $orderLocation->total_amount, 2) }} €</span>
                </div>
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('rental-orders.show', $orderLocation->id) }}" class="button">
                    📄 Voir le Détail Complet
                </a>
            </div>
            
            <div class="contact-info">
                <h4 style="margin-top: 0; color: #2d5a27;">📞 Besoin d'aide ?</h4>
                <p style="margin: 5px 0;">Notre équipe est à votre disposition pour toute question concernant cette inspection.</p>
                <p style="margin: 5px 0;"><strong>Email :</strong> s.mef2703@gmail.com</p>
                <p style="margin: 5px 0;"><strong>Téléphone :</strong> 01 23 45 67 89</p>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>FarmShop</strong> - Votre partenaire en matériel agricole 🚜</p>
            <p style="font-size: 12px; color: #999;">
                Cet email a été envoyé automatiquement, merci de ne pas y répondre.<br>
                © {{ date('Y') }} FarmShop. Tous droits réservés.
            </p>
        </div>
    </div>
</body>
</html>
