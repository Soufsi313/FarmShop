<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Suppression de compte confirmée</title>
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
        .content {
            margin-bottom: 30px;
        }
        .content p {
            margin-bottom: 15px;
        }
        .important-info {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }
        .important-info h3 {
            color: #856404;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🗑️ Compte supprimé</h1>
            <p>Confirmation de suppression de votre compte FarmShop</p>
        </div>
        
        <div class="content">
            <p>Bonjour <strong>{{ $user->name }}</strong>,</p>
            
            <p>Nous vous confirmons que votre compte FarmShop a été supprimé avec succès le <strong>{{ $deletionDate }}</strong>.</p>
            
            <div class="important-info">
                <h3>⚠️ Informations importantes</h3>
                <p><strong>Données supprimées :</strong></p>
                <ul>
                    <li>Informations personnelles (nom, email, adresse)</li>
                    <li>Historique des commandes d'achat et de location</li>
                    <li>Paniers d'achat et de location</li>
                    <li>Produits favoris et liste de souhaits</li>
                    <li>Abonnement newsletter</li>
                    <li>Tous les messages et communications</li>
                </ul>
                
                <p><strong>Données conservées (obligations légales) :</strong></p>
                <ul>
                    <li>Factures et documents comptables (7 ans)</li>
                    <li>Données de paiement pour les autorités fiscales</li>
                </ul>
            </div>
            
            <div class="data-summary">
                <h3>📊 Résumé de votre activité supprimée</h3>
                <ul class="data-list">
                    <li><strong>Compte créé le :</strong> {{ $user->created_at->format('d/m/Y') }}</li>
                    <li><strong>Dernière connexion :</strong> {{ $user->updated_at->format('d/m/Y') }}</li>
                    <li><strong>Email :</strong> {{ $user->email }}</li>
                    <li><strong>Rôle :</strong> {{ $user->role }}</li>
                    <li><strong>Newsletter :</strong> {{ $user->newsletter_subscribed ? 'Abonné' : 'Non abonné' }}</li>
                </ul>
            </div>
            
            <p>Si vous avez téléchargé vos données avant la suppression, vous conservez une copie complète de toutes vos informations.</p>
            
            <div class="contact-info">
                <h3>💬 Besoin d'aide ?</h3>
                <p>Si cette suppression n'était pas intentionnelle ou si vous avez des questions :</p>
                <ul>
                    <li>📧 Email : support@farmshop.com</li>
                    <li>📞 Téléphone : 01 23 45 67 89</li>
                    <li>🕐 Horaires : Lundi-Vendredi 9h-18h</li>
                </ul>
                <p><strong>Important :</strong> Une fois supprimé, votre compte ne peut pas être récupéré. Vous pouvez créer un nouveau compte à tout moment.</p>
            </div>
            
            <p>Nous regrettons de vous voir partir et espérons vous revoir bientôt sur FarmShop !</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ config('app.url') }}" class="btn">Revenir sur FarmShop</a>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>FarmShop</strong> - Votre marketplace agricole de confiance</p>
            <p>Cet email a été envoyé automatiquement suite à la suppression de votre compte.</p>
            <p>Si vous n'êtes pas à l'origine de cette action, contactez-nous immédiatement.</p>
        </div>
    </div>
</body>
</html>
