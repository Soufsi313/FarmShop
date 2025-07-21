<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de réception - FarmShop</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #16a34a 0%, #ea580c 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px 20px;
        }
        .confirmation-box {
            background-color: #f0f9ff;
            border-left: 4px solid #0ea5e9;
            padding: 15px;
            margin: 20px 0;
        }
        .message-details {
            background-color: #f9fafb;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .label {
            font-weight: bold;
            color: #374151;
        }
        .value {
            color: #6b7280;
        }
        .reference {
            font-family: monospace;
            background-color: #f3f4f6;
            padding: 2px 6px;
            border-radius: 4px;
        }
        .footer {
            background-color: #f3f4f6;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #6b7280;
        }
        .contact-info {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
        }
        .btn {
            display: inline-block;
            background-color: #16a34a;
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: bold;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- En-tête -->
        <div class="header">
            <h1>✅ Message reçu avec succès</h1>
            <p>Merci pour votre message, {{ $visitorName }} !</p>
        </div>

        <!-- Contenu principal -->
        <div class="content">
            <div class="confirmation-box">
                <strong>📧 Confirmation de réception</strong><br>
                Nous avons bien reçu votre message et nous vous répondrons dans les <strong>{{ $estimatedResponseTime }}</strong>.
            </div>

            <!-- Détails du message -->
            <div class="message-details">
                <h3>📋 Détails de votre demande</h3>
                
                <div class="detail-row">
                    <span class="label">Référence :</span>
                    <span class="value reference">{{ $reference }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="label">Sujet :</span>
                    <span class="value">{{ $messageSubject }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="label">Motif :</span>
                    <span class="value">{{ ucfirst(str_replace('_', ' ', $reason)) }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="label">Priorité :</span>
                    <span class="value">{{ ucfirst($priority) }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="label">Date d'envoi :</span>
                    <span class="value">{{ now()->format('d/m/Y à H:i') }}</span>
                </div>
            </div>

            <!-- Message original -->
            <div class="message-details">
                <h3>💬 Votre message</h3>
                <p style="font-style: italic; color: #6b7280;">{{ $messageContent }}</p>
            </div>

            <!-- Informations de contact urgent -->
            <div class="contact-info">
                <strong>🚨 Besoin d'une réponse urgente ?</strong><br>
                Pour les demandes urgentes, contactez-nous directement :<br>
                📞 <strong>+32 2 123 45 67</strong><br>
                <small>Lun-Ven : 8h-18h | Sam : 9h-12h</small>
            </div>

            <!-- Bouton d'action -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ config('app.url') }}" class="btn">🏠 Retour sur le site</a>
            </div>

            <p>
                <strong>Que va-t-il se passer maintenant ?</strong><br>
                • Notre équipe va examiner votre demande<br>
                • Vous recevrez une réponse personnalisée par email<br>
                • En cas de besoin, nous vous recontacterons par téléphone
            </p>
        </div>

        <!-- Pied de page -->
        <div class="footer">
            <p><strong>FarmShop SPRL</strong></p>
            <p>Avenue de la ferme 123, 1000 Bruxelles, Belgique</p>
            <p>📧 s.mef2703@gmail.com | 📞 +32 2 123 45 67</p>
            <p style="margin-top: 15px; font-size: 12px;">
                Cet email a été envoyé automatiquement, merci de ne pas y répondre directement.
            </p>
        </div>
    </div>
</body>
</html>
