@php
    // Créer un faux utilisateur pour la prévisualisation
    $previewUser = new \App\Models\User([
        'name' => 'Utilisateur Test',
        'email' => 'test@example.com'
    ]);
    
    // Créer un faux NewsletterSend pour la prévisualisation
    $previewSend = new \App\Models\NewsletterSend([
        'tracking_token' => 'preview-token',
        'unsubscribe_token' => 'preview-token'
    ]);
    $previewSend->tracking_url = '#';
    $previewSend->unsubscribe_url = '#';
@endphp

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prévisualisation - {{ $newsletter->title }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
        }
        .preview-toolbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
            z-index: 1000;
        }
        .preview-toolbar h2 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
        }
        .preview-toolbar .buttons {
            display: flex;
            gap: 10px;
        }
        .preview-toolbar button, .preview-toolbar a {
            background: #10b981;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .preview-toolbar button:hover, .preview-toolbar a:hover {
            background: #059669;
            transform: translateY(-1px);
        }
        .preview-toolbar .btn-secondary {
            background: #6b7280;
        }
        .preview-toolbar .btn-secondary:hover {
            background: #4b5563;
        }
        .preview-content {
            margin-top: 70px;
            padding: 20px;
        }
        .device-selector {
            text-align: center;
            margin: 20px 0;
            background: #f3f4f6;
            padding: 15px;
            border-radius: 8px;
        }
        .device-selector button {
            background: white;
            border: 2px solid #e5e7eb;
            padding: 8px 16px;
            margin: 0 5px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .device-selector button.active {
            background: #10b981;
            color: white;
            border-color: #10b981;
        }
        .device-selector button:hover {
            border-color: #10b981;
        }
        .preview-frame {
            max-width: 100%;
            margin: 0 auto;
            transition: max-width 0.3s ease;
        }
        .preview-frame.desktop { max-width: 100%; }
        .preview-frame.tablet { max-width: 768px; }
        .preview-frame.mobile { max-width: 375px; }
    </style>
</head>
<body>
    <div class="preview-toolbar">
        <h2>📧 Prévisualisation : {{ $newsletter->title }}</h2>
        <div class="buttons">
            <a href="{{ route('admin.newsletters.show', $newsletter) }}" class="btn-secondary">← Retour</a>
            <button onclick="window.print()">🖨️ Imprimer</button>
        </div>
    </div>

    <div class="preview-content">
        <div class="device-selector">
            <strong>Affichage :</strong>
            <button class="desktop-btn active" onclick="changeDevice('desktop')">🖥️ Desktop</button>
            <button class="tablet-btn" onclick="changeDevice('tablet')">📱 Tablette</button>
            <button class="mobile-btn" onclick="changeDevice('mobile')">📱 Mobile</button>
        </div>

        <div class="preview-frame desktop" id="previewFrame">
            @include('emails.newsletter', [
                'newsletter' => $newsletter,
                'user' => $previewUser,
                'send' => $previewSend,
                'trackingUrl' => '#',
                'unsubscribeUrl' => '#',
                'websiteUrl' => url('/'),
                'preferencesUrl' => url('/profile/newsletter-preferences')
            ])
        </div>
    </div>

    <script>
        function changeDevice(device) {
            const frame = document.getElementById('previewFrame');
            const buttons = document.querySelectorAll('.device-selector button');
            
            buttons.forEach(btn => btn.classList.remove('active'));
            
            frame.className = 'preview-frame ' + device;
            
            if (device === 'desktop') {
                document.querySelector('.desktop-btn').classList.add('active');
            } else if (device === 'tablet') {
                document.querySelector('.tablet-btn').classList.add('active');
            } else if (device === 'mobile') {
                document.querySelector('.mobile-btn').classList.add('active');
            }
        }

        // Ajout du style d'impression
        window.addEventListener('beforeprint', function() {
            document.querySelector('.preview-toolbar').style.display = 'none';
            document.querySelector('.device-selector').style.display = 'none';
        });
        
        window.addEventListener('afterprint', function() {
            document.querySelector('.preview-toolbar').style.display = 'flex';
            document.querySelector('.device-selector').style.display = 'block';
        });
    </script>
</body>
</html>
