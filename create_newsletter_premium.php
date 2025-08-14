<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Création Newsletter de Bienvenue AMÉLIORÉE ===\n";

try {
    // Supprimer l'ancienne newsletter
    \App\Models\Newsletter::where('title', 'Bienvenue sur FarmShop !')->delete();
    
    $improvedTemplate = '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue sur FarmShop</title>
    <style>
        /* Reset et base */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            line-height: 1.6; 
            color: #2c3e50; 
            background-color: #f8fafc;
        }
        
        /* Container principal */
        .email-container { 
            max-width: 600px; 
            margin: 0 auto; 
            background: #ffffff; 
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
        }
        
        /* Header avec gradient */
        .header { 
            background: linear-gradient(135deg, #1e3a2e 0%, #2d5a27 50%, #4a7c59 100%);
            padding: 50px 30px;
            text-align: center;
            position: relative;
        }
        .header::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZGVmcz48cGF0dGVybiBpZD0iZ3JpZCIgd2lkdGg9IjYwIiBoZWlnaHQ9IjYwIiBwYXR0ZXJuVW5pdHM9InVzZXJTcGFjZU9uVXNlIj48cGF0aCBkPSJNIDYwIDAgTCAwIDAgMCA2MCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSJyZ2JhKDI1NSwyNTUsMjU1LDAuMDUpIiBzdHJva2Utd2lkdGg9IjEiLz48L3BhdHRlcm4+PC9kZWZzPjxyZWN0IHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZ3JpZCkiLz48L3N2Zz4=") repeat;
            opacity: 0.1;
        }
        .logo { 
            font-size: 36px; 
            font-weight: 800; 
            color: #ffffff;
            margin-bottom: 12px;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 1;
        }
        .tagline { 
            font-size: 18px; 
            color: rgba(255, 255, 255, 0.95);
            font-weight: 300;
            position: relative;
            z-index: 1;
        }
        
        /* Content principal */
        .content { padding: 50px 40px; }
        .welcome-title { 
            font-size: 32px; 
            font-weight: 700; 
            color: #1e3a2e;
            text-align: center; 
            margin-bottom: 24px;
            line-height: 1.2;
        }
        .welcome-text { 
            font-size: 18px; 
            line-height: 1.8; 
            color: #4a5568;
            text-align: center; 
            margin-bottom: 40px;
            font-weight: 400;
        }
        
        /* Grille de fonctionnalités */
        .features-section {
            margin: 40px 0;
        }
        .features-grid {
            display: table;
            width: 100%;
            table-layout: fixed;
        }
        .feature-card {
            display: table-cell;
            width: 50%;
            padding: 20px;
            text-align: center;
            vertical-align: top;
        }
        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: white;
            box-shadow: 0 8px 24px rgba(245, 158, 11, 0.3);
            transition: transform 0.3s ease;
        }
        .feature-title {
            font-size: 20px;
            font-weight: 600;
            color: #1e3a2e;
            margin-bottom: 12px;
        }
        .feature-text {
            font-size: 15px;
            color: #6b7280;
            line-height: 1.6;
        }
        
        /* Section CTA */
        .cta-section {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            padding: 40px;
            margin: 40px 0;
            border-radius: 16px;
            text-align: center;
            border: 1px solid #e2e8f0;
        }
        .cta-title {
            font-size: 26px;
            font-weight: 700;
            color: #1e3a2e;
            margin-bottom: 16px;
        }
        .cta-text {
            font-size: 17px;
            color: #4a5568;
            margin-bottom: 32px;
            line-height: 1.6;
        }
        
        /* Boutons améliorés */
        .btn-primary {
            display: inline-block;
            padding: 16px 32px;
            background: linear-gradient(135deg, #1e3a2e 0%, #2d5a27 100%);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin: 8px;
            box-shadow: 0 4px 16px rgba(30, 58, 46, 0.3);
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(30, 58, 46, 0.4);
        }
        .btn-secondary {
            display: inline-block;
            padding: 16px 32px;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin: 8px;
            box-shadow: 0 4px 16px rgba(245, 158, 11, 0.3);
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(245, 158, 11, 0.4);
        }
        
        /* Section statistiques */
        .stats-section {
            background: linear-gradient(135deg, #1e3a2e 0%, #2d5a27 100%);
            color: white;
            padding: 50px 40px;
            text-align: center;
            position: relative;
        }
        .stats-section::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZGVmcz48cGF0dGVybiBpZD0iZ3JpZCIgd2lkdGg9IjYwIiBoZWlnaHQ9IjYwIiBwYXR0ZXJuVW5pdHM9InVzZXJTcGFjZU9uVXNlIj48cGF0aCBkPSJNIDYwIDAgTCAwIDAgMCA2MCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSJyZ2JhKDI1NSwyNTUsMjU1LDAuMDUpIiBzdHJva2Utd2lkdGg9IjEiLz48L3BhdHRlcm4+PC9kZWZzPjxyZWN0IHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZ3JpZCkiLz48L3N2Zz4=") repeat;
            opacity: 0.1;
        }
        .stats-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 32px;
            position: relative;
            z-index: 1;
        }
        .stats-grid {
            display: table;
            width: 100%;
            margin-top: 20px;
            position: relative;
            z-index: 1;
        }
        .stat-item {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            padding: 16px;
        }
        .stat-number {
            font-size: 40px;
            font-weight: 800;
            color: #fbbf24;
            display: block;
            margin-bottom: 8px;
        }
        .stat-label {
            font-size: 16px;
            opacity: 0.9;
            font-weight: 400;
        }
        
        /* Footer amélioré */
        .footer {
            background: #1f2937;
            color: #f9fafb;
            padding: 50px 40px;
            text-align: center;
        }
        .footer-title {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 16px;
            color: #ffffff;
        }
        .footer-text {
            font-size: 16px;
            line-height: 1.6;
            opacity: 0.8;
            margin-bottom: 32px;
        }
        .social-links {
            margin: 24px 0;
        }
        .social-link {
            display: inline-block;
            margin: 0 12px;
            padding: 12px 20px;
            background: #374151;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: 1px solid #4b5563;
        }
        .social-link:hover {
            background: #4a7c59;
            border-color: #4a7c59;
            transform: translateY(-1px);
        }
        .unsubscribe {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid #374151;
            font-size: 13px;
            opacity: 0.7;
        }
        .unsubscribe a {
            color: #fbbf24 !important;
            text-decoration: none;
            font-weight: 500;
        }
        .unsubscribe a:hover {
            color: #f59e0b !important;
        }
        
        /* Responsive */
        @media only screen and (max-width: 600px) {
            .email-container { margin: 0 16px !important; }
            .header, .content, .footer, .stats-section { padding: 32px 24px !important; }
            .features-grid, .stats-grid { display: block !important; }
            .feature-card, .stat-item { 
                display: block !important; 
                width: 100% !important; 
                margin-bottom: 24px; 
            }
            .btn-primary, .btn-secondary { 
                display: block !important; 
                margin: 12px 0 !important; 
                text-align: center;
            }
            .welcome-title { font-size: 28px !important; }
            .cta-title { font-size: 22px !important; }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="logo">🌱 FarmShop</div>
            <div class="tagline">Votre marketplace agricole de confiance</div>
        </div>
        
        <!-- Content principal -->
        <div class="content">
            <h1 class="welcome-title">Bienvenue dans la communauté FarmShop ! 🎉</h1>
            
            <p class="welcome-text">
                Félicitations ! Votre compte a été créé avec succès. Nous sommes ravis de vous accueillir dans notre communauté d\'agriculteurs, de passionnés et de consommateurs responsables qui croient en une agriculture durable et locale.
            </p>
            
            <!-- Features -->
            <div class="features-section">
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">🛒</div>
                        <h3 class="feature-title">Achat Direct</h3>
                        <p class="feature-text">Achetez directement auprès des producteurs locaux et découvrez des produits frais de qualité exceptionnelle.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">🚜</div>
                        <h3 class="feature-title">Location Matériel</h3>
                        <p class="feature-text">Louez du matériel agricole professionnel pour tous vos projets, du simple outil aux machines spécialisées.</p>
                    </div>
                </div>
            </div>
            
            <!-- CTA principal -->
            <div class="cta-section">
                <h2 class="cta-title">Prêt à commencer votre aventure agricole ?</h2>
                <p class="cta-text">Explorez notre vaste catalogue et découvrez tout ce que FarmShop a à vous offrir pour réussir vos projets.</p>
                
                <a href="http://127.0.0.1:8000/products" class="btn-primary">
                    🛍️ Découvrir les Produits
                </a>
                <a href="http://127.0.0.1:8000/rentals" class="btn-secondary">
                    🚜 Explorer les Locations
                </a>
            </div>
        </div>
        
        <!-- Statistiques -->
        <div class="stats-section">
            <h2 class="stats-title">FarmShop en chiffres</h2>
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-number">500+</span>
                    <div class="stat-label">Produits disponibles</div>
                </div>
                <div class="stat-item">
                    <span class="stat-number">150+</span>
                    <div class="stat-label">Producteurs partenaires</div>
                </div>
                <div class="stat-item">
                    <span class="stat-number">1000+</span>
                    <div class="stat-label">Clients satisfaits</div>
                </div>
            </div>
        </div>
        
        <!-- Support -->
        <div class="content">
            <div class="cta-section">
                <h2 class="cta-title">Besoin d\'aide pour commencer ?</h2>
                <p class="cta-text">Notre équipe d\'experts est là pour vous accompagner à chaque étape de votre parcours sur FarmShop.</p>
                
                <a href="http://127.0.0.1:8000/contact" class="btn-primary">
                    📞 Nous Contacter
                </a>
                <a href="http://127.0.0.1:8000/blog" class="btn-secondary">
                    📖 Guide d\'Utilisation
                </a>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <h3 class="footer-title">Merci de faire confiance à FarmShop</h3>
            <p class="footer-text">
                Ensemble, soutenons l\'agriculture locale et construisons un avenir plus durable. 
                Votre succès est notre priorité absolue !
            </p>
            
            <div class="social-links">
                <a href="http://127.0.0.1:8000/newsletter" class="social-link">📧 Newsletter</a>
                <a href="http://127.0.0.1:8000/blog" class="social-link">📱 Blog</a>
                <a href="http://127.0.0.1:8000/contact" class="social-link">💬 Support 24/7</a>
            </div>
            
            <div class="unsubscribe">
                <p>
                    Vous recevez cet email car vous venez de créer un compte sur FarmShop.<br>
                    <a href="{{unsubscribe_url}}">Se désabonner des newsletters</a> | 
                    <a href="http://127.0.0.1:8000/privacy">Politique de confidentialité</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>';
    
    // Créer la nouvelle newsletter améliorée
    $newsletter = \App\Models\Newsletter::create([
        'title' => 'Bienvenue sur FarmShop !',
        'subject' => '🌱 Bienvenue sur FarmShop - Votre aventure agricole commence maintenant !',
        'content' => $improvedTemplate,
        'status' => 'draft',
        'is_template' => true,
        'template_name' => 'Bienvenue Premium - Nouveaux Utilisateurs',
        'created_by' => 1
    ]);
    
    echo "✅ Newsletter de bienvenue AMÉLIORÉE créée avec succès !\n";
    echo "ID: {$newsletter->id}\n";
    echo "Titre: {$newsletter->title}\n";
    echo "Template: {$newsletter->template_name}\n";
    echo "URL Admin: http://127.0.0.1:8000/admin/newsletters/{$newsletter->id}\n\n";
    
    echo "🎨 Améliorations visuelles :\n";
    echo "✅ Contrastes optimisés (texte foncé sur fond clair)\n";
    echo "✅ Boutons avec effet hover et ombres prononcées\n";
    echo "✅ Typography moderne avec poids variables\n";
    echo "✅ Gradients sophistiqués et motifs subtils\n";
    echo "✅ Icônes plus grandes et colorées\n";
    echo "✅ Espacement généreux et grille responsive\n";
    echo "✅ Footer structuré avec liens contrastés\n";
    echo "✅ Design mobile-first optimisé\n\n";
    
    echo "🚀 Prêt à tester l\'envoi !\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
