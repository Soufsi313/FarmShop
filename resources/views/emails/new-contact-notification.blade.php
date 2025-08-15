<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau message de contact</title>
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
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .info-box {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #28a745;
        }
        .label {
            font-weight: bold;
            color: #28a745;
            display: inline-block;
            width: 120px;
        }
        .priority {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .priority-urgent { background-color: #dc3545; color: white; }
        .priority-high { background-color: #fd7e14; color: white; }
        .priority-medium { background-color: #0dcaf0; color: white; }
        .priority-low { background-color: #198754; color: white; }
        .message-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            white-space: pre-wrap;
        }
        .button {
            display: inline-block;
            background-color: #28a745;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 10px 0 0;
            font-weight: bold;
        }
        .button:hover {
            background-color: #218838;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 14px;
        }
        .metadata {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 6px;
            margin-top: 20px;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📩 Nouveau Message de Contact</h1>
        <p>Reçu le {{ $contact->created_at->format('d/m/Y à H:i') }}</p>
    </div>

    <div class="content">
        <div class="info-box">
            <h2>Informations du contact</h2>
            <p><span class="label">Nom :</span> {{ $contact->name }}</p>
            <p><span class="label">Email :</span> <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></p>
            @if($contact->phone)
            <p><span class="label">Téléphone :</span> {{ $contact->phone }}</p>
            @endif
            <p><span class="label">Raison :</span> {{ $contact->reason_label }}</p>
            <p><span class="label">Priorité :</span> 
                <span class="priority priority-{{ $contact->priority }}">{{ $contact->priority_label }}</span>
            </p>
        </div>

        <div class="info-box">
            <h3>Objet du message</h3>
            <p><strong>{{ $contact->subject }}</strong></p>
        </div>

        <div class="info-box">
            <h3>{{ __("app.forms.message") }}</h3>
            <div class="message-content">{{ $contact->message }}</div>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ $contactUrl }}" class="button">Voir le contact</a>
            <a href="{{ $adminDashboardUrl }}" class="button">Dashboard Admin</a>
        </div>

        @if($contact->metadata)
        <div class="metadata">
            <h4>Métadonnées techniques</h4>
            @if(isset($contact->metadata['ip_address']))
            <p><strong>Adresse IP :</strong> {{ $contact->metadata['ip_address'] }}</p>
            @endif
            @if(isset($contact->metadata['user_agent']))
            <p><strong>Navigateur :</strong> {{ $contact->metadata['user_agent'] }}</p>
            @endif
            <p><strong>Référence :</strong> CONTACT-{{ str_pad($contact->id, 6, '0', STR_PAD_LEFT) }}</p>
        </div>
        @endif
    </div>

    <div class="footer">
        <p>Ce message a été envoyé automatiquement par le système FarmShop.</p>
        <p>Pour répondre au visiteur, utilisez l'interface d'administration.</p>
    </div>
</body>
</html>
