<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réponse à votre message</title>
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
        .greeting {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .original-message {
            background-color: #e9ecef;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #6c757d;
        }
        .response-box {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #28a745;
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
        .reference {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 10px;
            border-radius: 6px;
            margin: 20px 0;
            text-align: center;
            font-size: 14px;
        }
        .contact-info {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🌱 FarmShop</h1>
        <p>Réponse à votre message de contact</p>
    </div>

    <div class="content">
        <div class="reference">
            <strong>Référence :</strong> {{ $contactReference }}
        </div>

        <div class="greeting">
            <h2>Bonjour {{ $contact->name }},</h2>
            <p>Nous vous remercions d'avoir pris contact avec nous. Voici notre réponse à votre message concernant : <strong>{{ $contact->subject }}</strong></p>
        </div>

        <div class="response-box">
            <h3>💬 Notre réponse</h3>
            <div style="white-space: pre-wrap; line-height: 1.6;">{{ $contact->admin_response }}</div>
            
            @if($adminName)
            <p style="margin-top: 20px; font-style: italic; color: #6c757d;">
                — {{ $adminName }}, Équipe FarmShop
            </p>
            @endif
            
            <p style="margin-top: 15px; font-size: 14px; color: #6c757d;">
                Répondu le {{ $contact->responded_at->format('d/m/Y à H:i') }}
            </p>
        </div>

        <div class="original-message">
            <h4>📝 Votre message original ({{ $contact->created_at->format('d/m/Y à H:i') }})</h4>
            <p><strong>Raison :</strong> {{ $contact->reason_label }}</p>
            <p><strong>Objet :</strong> {{ $contact->subject }}</p>
            <div style="white-space: pre-wrap; margin-top: 10px;">{{ $contact->message }}</div>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ $websiteUrl }}" class="button">Visiter notre site</a>
        </div>

        <div class="contact-info">
            <h4>📞 Besoin d'aide supplémentaire ?</h4>
            <p>Si vous avez d'autres questions, n'hésitez pas à nous recontacter :</p>
            <ul>
                <li>📧 Email : s.mef2703@gmail.com</li>
                <li>🌐 Site web : <a href="{{ $websiteUrl }}">{{ $websiteUrl }}</a></li>
                <li>📋 Formulaire de contact : <a href="{{ $websiteUrl }}/contact">{{ $websiteUrl }}/contact</a></li>
            </ul>
        </div>
    </div>

    <div class="footer">
        <p><strong>FarmShop</strong> - Votre partenaire pour l'agriculture moderne</p>
        <p>Vente et location de matériel agricole de qualité</p>
        <hr style="margin: 20px 0; border: none; border-top: 1px solid #dee2e6;">
        <p style="font-size: 12px;">
            Ce message a été envoyé en réponse à votre demande de contact.<br>
            Si vous n'êtes pas à l'origine de cette demande, veuillez ignorer ce message.
        </p>
    </div>
</body>
</html>
