<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirmation de suppression de compte</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #dc3545;
        }
        .header h1 {
            color: #dc3545;
            margin: 0;
            font-size: 28px;
        }
        .header p {
            color: #666;
            margin: 10px 0 0 0;
        }
        .content {
            margin-bottom: 30px;
        }
        .content p {
            margin-bottom: 15px;
        }
        .warning-box {
            background-color: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .warning-box h3 {
            color: #856404;
            margin-top: 0;
            font-size: 18px;
        }
        .btn {
            display: inline-block;
            padding: 15px 30px;
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin: 20px 0;
            font-weight: bold;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
            transition: all 0.3s ease;
        }
        .btn:hover {
            background: linear-gradient(135deg, #c82333 0%, #a71e2a 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
        }
        .btn-cancel {
            display: inline-block;
            padding: 12px 25px;
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin: 10px 10px 10px 0;
            font-weight: bold;
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
        }
        .action-buttons {
            text-align: center;
            margin: 30px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
        }
        .expiration-notice {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
        }
        .data-info {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚨 Confirmation requise</h1>
            <p>Demande de suppression de votre compte FarmShop</p>
        </div>
        
        <div class="content">
            <p>Bonjour <strong>{{ $user->name }}</strong>,</p>
            
            <p>Nous avons reçu une demande de suppression pour votre compte FarmShop associé à l'adresse email <strong>{{ $user->email }}</strong>.</p>
            
            <div class="warning-box">
                <h3>⚠️ ATTENTION - Action Irréversible</h3>
                <p>La suppression de votre compte entraînera la perte définitive de :</p>
                <ul style="text-align: left; max-width: 300px; margin: 0 auto;">
                    <li>Toutes vos données personnelles</li>
                    <li>Votre historique de commandes</li>
                    <li>Vos favoris et listes de souhaits</li>
                    <li>Tous vos messages</li>
                    <li>Votre abonnement newsletter</li>
                </ul>
            </div>
            
            <div class="data-info">
                <h4>📦 Téléchargement automatique de vos données (GDPR)</h4>
                <p>Conformément au RGPD, si vous confirmez la suppression, un fichier ZIP contenant toutes vos données sera automatiquement téléchargé et vous sera envoyé par email :</p>
                <ul>
                    <li>📄 Données personnelles au format PDF</li>
                    <li>📊 Historique de navigation par section</li>
                    <li>🛒 Historique des commandes</li>
                    <li>💌 Correspondances et messages</li>
                </ul>
            </div>
            
            <p><strong>Si vous souhaitez vraiment supprimer votre compte</strong>, cliquez sur le bouton rouge ci-dessous.</p>
            <p><strong>Si ce n'était pas votre intention</strong>, ignorez simplement cet email.</p>
            
            <div class="action-buttons">
                <a href="{{ $url }}" class="btn">
                    🗑️ CONFIRMER LA SUPPRESSION
                </a>
                <br>
                <a href="{{ config('app.url') }}" class="btn-cancel">
                    ↩️ Annuler et retourner sur FarmShop
                </a>
            </div>
            
            <div class="expiration-notice">
                <p><strong>⏰ Ce lien expire dans {{ $expiration }} minutes</strong></p>
                <p>Si le lien expire, vous devrez refaire une demande de suppression depuis votre profil.</p>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>FarmShop</strong> - Votre marketplace agricole de confiance</p>
            <p>Cet email a été envoyé automatiquement suite à une demande de suppression de compte.</p>
            <p>Si vous n'êtes pas à l'origine de cette demande, contactez-nous immédiatement.</p>
        </div>
    </div>
</body>
</html>