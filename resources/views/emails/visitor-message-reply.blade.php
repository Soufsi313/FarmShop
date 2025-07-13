<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réponse à votre message - FarmShop</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            line-height: 1.6; 
            color: #333; 
            margin: 0; 
            padding: 0; 
            background-color: #f4f4f4; 
        }
        .container { 
            max-width: 600px; 
            margin: 20px auto; 
            background: white; 
            border-radius: 10px; 
            box-shadow: 0 0 20px rgba(0,0,0,0.1); 
            overflow: hidden; 
        }
        .header { 
            background: linear-gradient(135deg, #2e7d32, #4caf50); 
            color: white; 
            text-align: center; 
            padding: 30px 20px; 
        }
        .header h1 { 
            margin: 0; 
            font-size: 28px; 
            font-weight: 300; 
        }
        .header .subtitle { 
            margin: 5px 0 0 0; 
            opacity: 0.9; 
            font-size: 16px; 
        }
        .content { 
            padding: 30px; 
        }
        .message-info { 
            background: #f8f9fa; 
            border-left: 4px solid #4caf50; 
            padding: 15px; 
            margin: 20px 0; 
            border-radius: 0 5px 5px 0; 
        }
        .message-info h3 { 
            margin: 0 0 10px 0; 
            color: #2e7d32; 
            font-size: 16px; 
        }
        .message-info p { 
            margin: 5px 0; 
            font-size: 14px; 
            color: #666; 
        }
        .reply-content { 
            background: #ffffff; 
            border: 1px solid #e0e0e0; 
            border-radius: 8px; 
            padding: 20px; 
            margin: 20px 0; 
        }
        .reply-content h3 { 
            color: #2e7d32; 
            margin: 0 0 15px 0; 
            font-size: 18px; 
        }
        .original-message { 
            background: #f5f5f5; 
            border-radius: 8px; 
            padding: 15px; 
            margin: 20px 0; 
            border-left: 3px solid #ddd; 
        }
        .original-message h4 { 
            margin: 0 0 10px 0; 
            color: #666; 
            font-size: 14px; 
            text-transform: uppercase; 
            letter-spacing: 0.5px; 
        }
        .footer { 
            background: #f8f9fa; 
            text-align: center; 
            padding: 20px; 
            border-top: 1px solid #e0e0e0; 
            font-size: 14px; 
            color: #666; 
        }
        .footer a { 
            color: #4caf50; 
            text-decoration: none; 
        }
        .reference { 
            display: inline-block; 
            background: #e8f5e8; 
            color: #2e7d32; 
            padding: 5px 10px; 
            border-radius: 15px; 
            font-size: 12px; 
            font-weight: bold; 
            margin: 10px 0; 
        }
        .divider { 
            height: 1px; 
            background: linear-gradient(to right, transparent, #e0e0e0, transparent); 
            margin: 25px 0; 
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🌱 FarmShop</h1>
            <p class="subtitle">Réponse à votre message</p>
        </div>
        
        <div class="content">
            <h2>Bonjour {{ $visitorName }},</h2>
            
            <p>Nous avons bien reçu votre message et voici notre réponse :</p>
            
            <div class="message-info">
                <h3>📋 Informations de votre demande</h3>
                <p><strong>Sujet :</strong> {{ $originalSubject }}</p>
                <p><strong>Référence :</strong> <span class="reference">{{ $messageReference }}</span></p>
                <p><strong>Date :</strong> {{ $originalMessage->created_at->format('d/m/Y à H:i') }}</p>
                @if(isset($contactReason))
                <p><strong>Motif :</strong> {{ ucfirst(str_replace('_', ' ', $contactReason)) }}</p>
                @endif
            </div>

            <div class="reply-content">
                <h3>💬 Notre réponse</h3>
                <div>{!! nl2br(e($replyContent)) !!}</div>
            </div>

            <div class="divider"></div>

            <div class="original-message">
                <h4>📤 Votre message original</h4>
                <p>{{ $originalContent }}</p>
            </div>

            <div class="divider"></div>

            <p><strong>Besoin d'aide supplémentaire ?</strong></p>
            <p>Si vous avez d'autres questions, n'hésitez pas à nous contacter en répondant directement à cet email ou en visitant notre site web.</p>
            
            <p>Cordialement,<br>
            <strong>{{ $adminName }}</strong><br>
            <em>Service Client FarmShop</em></p>
        </div>
        
        <div class="footer">
            <p>
                <strong>🌱 FarmShop</strong> - Votre ferme locale en ligne<br>
                <a href="{{ config('app.url') }}">Visitez notre site web</a> | 
                <a href="{{ config('app.url') }}/contact">Nous contacter</a>
            </p>
            <p style="font-size: 12px; color: #999; margin-top: 15px;">
                Cet email a été envoyé automatiquement, merci de ne pas répondre à cette adresse.<br>
                Pour toute question, utilisez notre <a href="{{ config('app.url') }}/contact">formulaire de contact</a>.
            </p>
        </div>
    </div>
</body>
</html>
