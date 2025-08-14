<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Profil Utilisateur - {{ $user->name }}</title>
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
            width: 150px;
        }
        .info-value {
            flex: 1;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Données de Profil Utilisateur</h1>
        <h2>{{ $user->name }}</h2>
        <p>Exporté le {{ now()->format('d/m/Y à H:i:s') }}</p>
    </div>

    <div class="section">
        <h3>Informations Personnelles</h3>
        <div class="info-row">
            <div class="info-label">ID Utilisateur :</div>
            <div class="info-value">{{ $user->id }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Nom d'utilisateur :</div>
            <div class="info-value">{{ $user->username }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Nom complet :</div>
            <div class="info-value">{{ $user->name }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Email :</div>
            <div class="info-value">{{ $user->email }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Rôle :</div>
            <div class="info-value">{{ $user->role }}</div>
        </div>
    </div>

    <div class="section">
        <h3>Statut du Compte</h3>
        <div class="info-row">
            <div class="info-label">Email vérifié :</div>
            <div class="info-value">{{ $user->email_verified_at ? 'Oui (' . $user->email_verified_at->format('d/m/Y H:i') . ')' : 'Non' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Newsletter :</div>
            <div class="info-value">{{ $user->newsletter_subscribed ? 'Abonné' : 'Non abonné' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Date de création :</div>
            <div class="info-value">{{ $user->created_at->format('d/m/Y à H:i:s') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Dernière modification :</div>
            <div class="info-value">{{ $user->updated_at->format('d/m/Y à H:i:s') }}</div>
        </div>
    </div>

    <div class="footer">
        <p>Document généré automatiquement par FarmShop</p>
        <p>Conformément au Règlement Général sur la Protection des Données (RGPD)</p>
    </div>
</body>
</html>
