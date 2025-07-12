<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $newsletter->title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            margin: 20px auto;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #28a745;
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
        }
        .featured-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .content {
            padding: 30px;
        }
        .excerpt {
            background-color: #f8f9fa;
            padding: 20px;
            border-left: 4px solid #28a745;
            margin: 20px 0;
            font-style: italic;
            font-size: 16px;
        }
        .newsletter-content {
            line-height: 1.8;
            font-size: 15px;
        }
        .newsletter-content h2 {
            color: #28a745;
            margin-top: 30px;
            margin-bottom: 15px;
        }
        .newsletter-content h3 {
            color: #495057;
            margin-top: 25px;
            margin-bottom: 10px;
        }
        .newsletter-content img {
            max-width: 100%;
            height: auto;
            border-radius: 6px;
            margin: 15px 0;
        }
        .newsletter-content a {
            color: #28a745;
            text-decoration: none;
        }
        .newsletter-content a:hover {
            text-decoration: underline;
        }
        .tags {
            margin: 30px 0;
            text-align: center;
        }
        .tag {
            display: inline-block;
            background-color: #e9ecef;
            color: #495057;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            margin: 0 5px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #dee2e6;
        }
        .footer-links {
            margin: 20px 0;
        }
        .footer-links a {
            color: #28a745;
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
        }
        .footer-links a:hover {
            text-decoration: underline;
        }
        .unsubscribe {
            margin-top: 20px;
            font-size: 12px;
            color: #6c757d;
        }
        .unsubscribe a {
            color: #6c757d;
            text-decoration: none;
        }
        .personalization {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            font-size: 14px;
        }
        
        /* Responsive */
        @media (max-width: 600px) {
            .container {
                margin: 0;
                border-radius: 0;
            }
            .header, .content, .footer {
                padding: 20px;
            }
            .header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <!-- Pixel de tracking invisible -->
    <img src="{{ $trackingUrl }}" width="1" height="1" style="display:none;" alt="">
    
    <div class="container">
        <div class="header">
            <h1>🌱 {{ $newsletter->title }}</h1>
            <p>Newsletter FarmShop - {{ $newsletter->created_at->format('d/m/Y') }}</p>
        </div>

        @if($newsletter->featured_image)
        <img src="{{ $newsletter->featured_image }}" alt="{{ $newsletter->title }}" class="featured-image">
        @endif

        <div class="content">
            <div class="personalization">
                <strong>Bonjour {{ $user->name }} !</strong><br>
                Voici les dernières nouvelles de FarmShop, votre partenaire pour l'agriculture moderne.
            </div>

            @if($newsletter->excerpt)
            <div class="excerpt">
                {{ $newsletter->excerpt }}
            </div>
            @endif

            <div class="newsletter-content">
                {!! $newsletter->content !!}
            </div>

            @if($newsletter->tags && count($newsletter->tags) > 0)
            <div class="tags">
                @foreach($newsletter->tags as $tag)
                <span class="tag">{{ $tag }}</span>
                @endforeach
            </div>
            @endif
        </div>

        <div class="footer">
            <h3>🌱 FarmShop</h3>
            <p>Votre partenaire pour l'agriculture moderne</p>
            <p>Vente et location de matériel agricole de qualité</p>
            
            <div class="footer-links">
                <a href="{{ $websiteUrl }}">Visiter notre site</a>
                <a href="{{ $websiteUrl }}/products">Nos produits</a>
                <a href="{{ $websiteUrl }}/contact">Nous contacter</a>
                <a href="{{ $preferencesUrl }}">Mes préférences</a>
            </div>
            
            <hr style="margin: 20px 0; border: none; border-top: 1px solid #dee2e6;">
            
            <div class="unsubscribe">
                <p>
                    Vous recevez cette newsletter car vous êtes abonné(e) à nos actualités.<br>
                    <a href="{{ $unsubscribeUrl }}">Se désabonner</a> | 
                    <a href="{{ $preferencesUrl }}">Gérer mes préférences</a>
                </p>
                <p style="margin-top: 15px; font-size: 11px;">
                    Newsletter envoyée le {{ now()->format('d/m/Y à H:i') }}<br>
                    FarmShop - Matériel agricole de qualité
                </p>
            </div>
        </div>
    </div>
</body>
</html>
