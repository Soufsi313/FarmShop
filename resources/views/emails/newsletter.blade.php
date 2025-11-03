<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $newsletter->title }}</title>
    <style>
        /* Reset CSS */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.7;
            color: #2d3748;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .email-wrapper {
            max-width: 680px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        /* Header avec gradient moderne */
        .header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 50px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 15s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        .header-content {
            position: relative;
            z-index: 2;
        }
        
        .logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 50%;
            margin-bottom: 20px;
            font-size: 40px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        .header h1 {
            font-size: 32px;
            font-weight: 800;
            margin: 0 0 12px 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            letter-spacing: -0.5px;
        }
        
        .header .subtitle {
            font-size: 16px;
            opacity: 0.95;
            font-weight: 500;
            letter-spacing: 0.5px;
        }
        
        /* Image featured avec overlay */
        .featured-image-container {
            position: relative;
            width: 100%;
            height: 320px;
            overflow: hidden;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
        
        .featured-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .featured-image-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.6) 0%, transparent 100%);
            padding: 30px;
            color: white;
        }
        
        /* Contenu principal */
        .content {
            padding: 50px 40px;
        }
        
        .greeting-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px 30px;
            border-radius: 12px;
            margin-bottom: 35px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }
        
        .greeting-card strong {
            font-size: 20px;
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
        }
        
        .greeting-card p {
            margin: 0;
            opacity: 0.95;
            line-height: 1.6;
        }
        
        .excerpt {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            padding: 30px;
            border-left: 5px solid #10b981;
            border-radius: 8px;
            margin: 30px 0;
            font-size: 18px;
            font-style: italic;
            color: #065f46;
            line-height: 1.8;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.1);
        }
        
        .newsletter-content {
            font-size: 16px;
            line-height: 1.9;
            color: #374151;
        }
        
        .newsletter-content h2 {
            color: #10b981;
            font-size: 26px;
            margin: 45px 0 20px 0;
            font-weight: 700;
            letter-spacing: -0.5px;
            padding-bottom: 15px;
            border-bottom: 3px solid #d1fae5;
        }
        
        .newsletter-content h3 {
            color: #1f2937;
            font-size: 20px;
            margin: 35px 0 15px 0;
            font-weight: 600;
        }
        
        .newsletter-content p {
            margin: 20px 0;
        }
        
        .newsletter-content img {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            margin: 25px 0;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }
        
        .newsletter-content a {
            color: #10b981;
            text-decoration: none;
            font-weight: 600;
            border-bottom: 2px solid transparent;
            transition: border-color 0.2s ease;
        }
        
        .newsletter-content a:hover {
            border-bottom-color: #10b981;
        }
        
        .newsletter-content ul, .newsletter-content ol {
            margin: 20px 0;
            padding-left: 25px;
        }
        
        .newsletter-content li {
            margin: 10px 0;
        }
        
        /* Tags modernes */
        .tags {
            margin: 40px 0;
            text-align: center;
        }
        
        .tag {
            display: inline-block;
            background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
            color: #4338ca;
            padding: 8px 18px;
            border-radius: 25px;
            font-size: 13px;
            font-weight: 600;
            margin: 5px;
            box-shadow: 0 2px 8px rgba(67, 56, 202, 0.15);
        }
        
        /* Call to action button */
        .cta-section {
            text-align: center;
            margin: 45px 0;
            padding: 40px 30px;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border-radius: 12px;
        }
        
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white !important;
            padding: 16px 40px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            font-size: 16px;
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(16, 185, 129, 0.4);
        }
        
        /* Footer moderne */
        .footer {
            background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
            color: #d1d5db;
            padding: 50px 40px;
            text-align: center;
        }
        
        .footer-logo {
            font-size: 32px;
            margin-bottom: 15px;
        }
        
        .footer h3 {
            color: white;
            font-size: 24px;
            margin: 0 0 10px 0;
            font-weight: 700;
        }
        
        .footer-description {
            font-size: 15px;
            color: #9ca3af;
            margin: 15px 0 35px 0;
            line-height: 1.6;
        }
        
        .footer-links {
            margin: 30px 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 25px;
        }
        
        .footer-links a {
            color: #10b981;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            transition: color 0.2s ease;
        }
        
        .footer-links a:hover {
            color: #34d399;
        }
        
        .social-links {
            margin: 35px 0;
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        
        .social-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            background: rgba(16, 185, 129, 0.1);
            border-radius: 50%;
            color: #10b981;
            text-decoration: none;
            font-size: 20px;
            transition: all 0.3s ease;
        }
        
        .social-link:hover {
            background: #10b981;
            color: white;
            transform: translateY(-3px);
        }
        
        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, rgba(255,255,255,0.1), transparent);
            margin: 35px 0;
        }
        
        .unsubscribe {
            font-size: 13px;
            color: #6b7280;
            line-height: 1.8;
        }
        
        .unsubscribe a {
            color: #9ca3af;
            text-decoration: underline;
        }
        
        .unsubscribe a:hover {
            color: #10b981;
        }
        
        /* Responsive Design */
        @media (max-width: 640px) {
            .email-wrapper {
                margin: 0;
                border-radius: 0;
            }
            
            .header {
                padding: 40px 25px;
            }
            
            .header h1 {
                font-size: 26px;
            }
            
            .content {
                padding: 35px 25px;
            }
            
            .footer {
                padding: 40px 25px;
            }
            
            .greeting-card {
                padding: 20px;
            }
            
            .footer-links {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Pixel de tracking invisible -->
    <img src="{{ $trackingUrl }}" width="1" height="1" style="display:none;" alt="">
    
    <div class="email-wrapper">
        <!-- Header avec gradient moderne -->
        <div class="header">
            <div class="header-content">
                <div class="logo">🌱</div>
                <h1>{{ $newsletter->title }}</h1>
                <p class="subtitle">{{ $newsletter->created_at->format('d F Y') }}</p>
            </div>
        </div>

        <!-- Image featured avec overlay -->
        @if($newsletter->featured_image)
        <div class="featured-image-container">
            <img src="{{ asset('storage/' . $newsletter->featured_image) }}" alt="{{ $newsletter->title }}" class="featured-image">
            <div class="featured-image-overlay">
                <p style="margin: 0; font-size: 14px; opacity: 0.9;">📸 Image à la une</p>
            </div>
        </div>
        @endif

        <!-- Contenu principal -->
        <div class="content">
            <!-- Carte de salutation personnalisée -->
            <div class="greeting-card">
                <strong>👋 Bonjour {{ $user->name }} !</strong>
                <p>Découvrez les dernières actualités de FarmShop, votre partenaire de confiance pour l'agriculture moderne et durable.</p>
            </div>

            <!-- Excerpt mis en avant -->
            @if($newsletter->excerpt)
            <div class="excerpt">
                💡 {{ $newsletter->excerpt }}
            </div>
            @endif

            <!-- Contenu de la newsletter -->
            <div class="newsletter-content">
                {!! $newsletter->content !!}
            </div>

            <!-- Call to Action -->
            <div class="cta-section">
                <h3 style="color: #065f46; margin-bottom: 20px; font-size: 22px;">Prêt à en découvrir plus ?</h3>
                <a href="{{ $websiteUrl }}/products" class="cta-button">
                    🛒 Découvrir nos produits
                </a>
            </div>

            <!-- Tags -->
            @if($newsletter->tags && count($newsletter->tags) > 0)
            <div class="tags">
                @foreach($newsletter->tags as $tag)
                <span class="tag">#{{ $tag }}</span>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Footer moderne -->
        <div class="footer">
            <div class="footer-logo">🌱</div>
            <h3>FarmShop</h3>
            <p class="footer-description">
                Votre partenaire de confiance pour l'agriculture moderne<br>
                Vente et location de matériel agricole de qualité professionnelle
            </p>
            
            <div class="footer-links">
                <a href="{{ $websiteUrl }}">🏠 Accueil</a>
                <a href="{{ $websiteUrl }}/products">🛍️ Boutique</a>
                <a href="{{ $websiteUrl }}/rental">🚜 Location</a>
                <a href="{{ $websiteUrl }}/blog">📰 Blog</a>
                <a href="{{ $websiteUrl }}/contact">✉️ Contact</a>
            </div>

            <!-- Réseaux sociaux (optionnel) -->
            <div class="social-links">
                <a href="#" class="social-link" title="Facebook">📘</a>
                <a href="#" class="social-link" title="Instagram">📷</a>
                <a href="#" class="social-link" title="Twitter">🐦</a>
                <a href="#" class="social-link" title="LinkedIn">💼</a>
            </div>
            
            <div class="divider"></div>
            
            <!-- Désabonnement -->
            <div class="unsubscribe">
                <p>
                    📧 Vous recevez cette newsletter car vous êtes abonné(e) à nos actualités.<br>
                    <a href="{{ $unsubscribeUrl }}">Se désabonner</a> • 
                    <a href="{{ $preferencesUrl }}">Gérer mes préférences</a>
                </p>
                <p style="margin-top: 15px; font-size: 12px; color: #4b5563;">
                    Newsletter envoyée le {{ now()->format('d/m/Y à H:i') }}<br>
                    © {{ date('Y') }} FarmShop - Tous droits réservés
                </p>
            </div>
        </div>
    </div>
</body>
</html>
