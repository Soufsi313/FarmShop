<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Données de Navigation - {{ $user->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #27ae60;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section h3 {
            color: #27ae60;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .info-row {
            display: flex;
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: bold;
            width: 200px;
        }
        .info-value {
            flex: 1;
        }
        .preferences {
            background-color: #f8f9fa;
            padding: 15px;
            border-left: 3px solid #27ae60;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Données de Navigation et Préférences</h1>
        <h2>{{ $user->name }}</h2>
        <p>Exporté le {{ now()->format('d/m/Y à H:i:s') }}</p>
    </div>

    <div class="section">
        <h3>Informations de Compte</h3>
        <div class="info-row">
            <div class="info-label">Date de création du compte :</div>
            <div class="info-value">{{ $created_at->format('d/m/Y à H:i:s') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Dernière modification :</div>
            <div class="info-value">{{ $updated_at->format('d/m/Y à H:i:s') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Dernière connexion :</div>
            <div class="info-value">{{ $last_login }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Email vérifié :</div>
            <div class="info-value">{{ $email_verified_at ? 'Oui, le ' . $email_verified_at->format('d/m/Y à H:i:s') : 'Non' }}</div>
        </div>
    </div>

    <div class="section">
        <h3>Préférences et Paramètres</h3>
        <div class="preferences">
            <div class="info-row">
                <div class="info-label">Abonnement Newsletter :</div>
                <div class="info-value">{{ $newsletter_subscribed ? 'Activé' : 'Désactivé' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Rôle utilisateur :</div>
                <div class="info-value">{{ $user->role }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Langue préférée :</div>
                <div class="info-value">Français (par défaut)</div>
            </div>
        </div>
    </div>

    <div class="section">
        <h3>Données de Navigation</h3>
        <div class="info-row">
            <div class="info-label">Navigateur utilisé :</div>
            <div class="info-value">Information non collectée</div>
        </div>
        <div class="info-row">
            <div class="info-label">Adresse IP :</div>
            <div class="info-value">Information non stockée</div>
        </div>
        <div class="info-row">
            <div class="info-label">Cookies :</div>
            <div class="info-value">Cookies de session uniquement</div>
        </div>
    </div>

    <div class="section">
        <h3>Respect de la Vie Privée</h3>
        <div class="preferences">
            <p><strong>Politique de confidentialité :</strong></p>
            <ul>
                <li>Nous ne collectons que les données nécessaires au fonctionnement du service</li>
                <li>Aucune donnée personnelle n'est partagée avec des tiers</li>
                <li>Les données de navigation ne sont pas stockées de manière permanente</li>
                <li>Vous avez le droit de demander la suppression de vos données à tout moment</li>
            </ul>
        </div>
    </div>

    <div class="footer" style="margin-top: 40px; text-align: center; font-size: 10px; color: #666;">
        <p>Document généré automatiquement par FarmShop</p>
        <p>Conformément au Règlement Général sur la Protection des Données (RGPD)</p>
        <p>Pour toute question concernant vos données, contactez-nous à : privacy@farmshop.com</p>
    </div>
</body>
</html>
