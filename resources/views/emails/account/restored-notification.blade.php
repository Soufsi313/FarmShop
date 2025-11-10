<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Compte restauré</title>
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
            border-bottom: 3px solid #28a745;
        }
        .header h1 {
            color: #28a745;
            margin: 0;
            font-size: 28px;
        }
        .content {
            margin-bottom: 30px;
        }
        .content p {
            margin-bottom: 15px;
        }
        .important-info {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }
        .important-info h3 {
            color: #155724;
            margin-top: 0;
        }
        .contact-info {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }
        .contact-info h3 {
            color: #0c5460;
            margin-top: 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
        }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
            text-align: center;
        }
        .data-summary {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }
        .data-summary h3 {
            color: #495057;
            margin-top: 0;
        }
        .data-list {
            list-style: none;
            padding: 0;
        }
        .data-list li {
            padding: 5px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .data-list li:last-child {
            border-bottom: none;
        }
        .security-warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }
        .security-warning h3 {
            color: #856404;
            margin-top: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Compte restauré</h1>
            <p>Bienvenue de retour sur FarmShop !</p>
        </div>
        
        <div class="content">
            <p>Bonjour <strong>{{ $user->name }}</strong>,</p>
            
            <p>Nous vous informons que votre compte FarmShop a été <strong>restauré avec succès</strong> le <strong>{{ now()->format('d/m/Y à H:i') }}</strong>.</p>
            
            <div class="important-info">
                <h3>✅ Votre compte est de nouveau actif</h3>
                <p>Toutes vos données ont été restaurées :</p>
                <ul>
                    <li>✓ Informations personnelles</li>
                    <li>✓ Historique des commandes d'achat</li>
                    <li>✓ Historique des locations</li>
                    <li>✓ Paniers d'achat et de location</li>
                    <li>✓ Produits favoris</li>
                    <li>✓ Préférences de newsletter</li>
                    <li>✓ Messages et communications</li>
                </ul>
            </div>
            
            <div class="data-summary">
                <h3>📊 Détails de votre compte restauré</h3>
                <ul class="data-list">
                    <li><strong>Nom d'utilisateur :</strong> {{ $user->username }}</li>
                    <li><strong>Email :</strong> {{ $user->email }}</li>
                    <li><strong>Rôle :</strong> {{ $user->role }}</li>
                    <li><strong>Compte créé le :</strong> {{ $user->created_at->format('d/m/Y') }}</li>
                    <li><strong>Newsletter :</strong> {{ $user->newsletter_subscribed ? 'Abonné' : 'Non abonné' }}</li>
                </ul>
            </div>
            
            <div class="security-warning">
                <h3>🔒 Sécurité de votre compte</h3>
                <p><strong>Cette restauration n'était pas de votre fait ?</strong></p>
                <p>Si vous n'avez pas demandé la restauration de votre compte, contactez-nous immédiatement :</p>
                <ul>
                    <li>📧 Email : security@farmshop.com</li>
                    <li>📞 Téléphone : 01 23 45 67 89</li>
                    <li>🕐 Horaires : 24h/24, 7j/7 (urgences sécurité)</li>
                </ul>
                <p><strong>Recommandations :</strong></p>
                <ul>
                    <li>Changez votre mot de passe dès votre prochaine connexion</li>
                    <li>Vérifiez vos paramètres de sécurité</li>
                    <li>Consultez l'historique de vos activités récentes</li>
                </ul>
            </div>
            
            <p>Vous pouvez maintenant vous connecter et profiter à nouveau de tous nos services !</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('login') }}" class="btn">Se connecter maintenant</a>
            </div>
            
            <div class="contact-info">
                <h3>💬 Besoin d'aide ?</h3>
                <p>Notre équipe est à votre disposition :</p>
                <ul>
                    <li>📧 Email : support@farmshop.com</li>
                    <li>📞 Téléphone : 01 23 45 67 89</li>
                    <li>🕐 Horaires : Lundi-Vendredi 9h-18h</li>
                </ul>
            </div>
            
            <p>Nous sommes ravis de vous revoir sur FarmShop ! 🎉</p>
        </div>
        
        <div class="footer">
            <p><strong>FarmShop</strong> - Votre marketplace agricole de confiance</p>
            <p>Cet email a été envoyé automatiquement suite à la restauration de votre compte.</p>
            <p>Si vous avez des questions, n'hésitez pas à nous contacter.</p>
        </div>
    </div>
</body>
</html>
