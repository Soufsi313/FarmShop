<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Création d'une newsletter de bienvenue NORMALE (non-template) ===\n";

try {
    // Supprimer les anciennes newsletters de bienvenue non-template
    \App\Models\Newsletter::where('title', 'Newsletter de Bienvenue - FarmShop')
                         ->where('is_template', false)
                         ->delete();
    
    $improvedTemplate = '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue sur FarmShop</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            line-height: 1.6; 
            color: #2c3e50; 
            background-color: #f8fafc;
        }
        
        .email-container { 
            max-width: 600px; 
            margin: 0 auto; 
            background: #ffffff; 
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
        }
        
        .header { 
            background: linear-gradient(135deg, #1e3a2e 0%, #2d5a27 50%, #4a7c59 100%);
            padding: 50px 30px;
            text-align: center;
            position: relative;
        }
        .logo { 
            font-size: 36px; 
            font-weight: 800; 
            color: #ffffff;
            margin-bottom: 12px;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
        }
        .tagline { 
            font-size: 18px; 
            color: rgba(255, 255, 255, 0.95);
            font-weight: 300;
        }
        
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
        
        .features-grid {
            display: table;
            width: 100%;
            table-layout: fixed;
            margin: 40px 0;
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
        
        .btn-primary {
            display: inline-block;
            padding: 18px 36px;
            background: linear-gradient(135deg, #1e3a2e 0%, #2d5a27 100%);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 16px;
            margin: 12px;
            box-shadow: 0 6px 20px rgba(30, 58, 46, 0.4);
            transition: all 0.3s ease;
            border: 3px solid transparent;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .btn-secondary {
            display: inline-block;
            padding: 18px 36px;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 16px;
            margin: 12px;
            box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
            transition: all 0.3s ease;
            border: 3px solid transparent;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .stats-section {
            background: linear-gradient(135deg, #1e3a2e 0%, #2d5a27 100%);
            color: white;
            padding: 50px 40px;
            text-align: center;
        }
        .stats-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 32px;
        }
        .stats-grid {
            display: table;
            width: 100%;
            margin-top: 20px;
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
            padding: 14px 24px;
            background: #374151;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 2px solid #4b5563;
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
            font-weight: 600;
        }
        
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
        <div class="header">
            <div class="logo">🌱 FarmShop</div>
            <div class="tagline">Votre marketplace agricole de confiance</div>
        </div>
        
        <div class="content">
            <h1 class="welcome-title">Bienvenue dans la communauté FarmShop ! 🎉</h1>
            
            <p class="welcome-text">
                Félicitations ! Votre compte a été créé avec succès. Nous sommes ravis de vous accueillir dans notre communauté d\'agriculteurs, de passionnés et de consommateurs responsables qui croient en une agriculture durable et locale.
            </p>
            
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
    
    // Créer la newsletter normale (non-template) pour qu'elle apparaisse dans la liste admin
    $newsletter = \App\Models\Newsletter::create([
        'title' => 'Newsletter de Bienvenue - FarmShop',
        'subject' => '🌱 Bienvenue sur FarmShop - Votre aventure agricole commence maintenant !',
        'content' => $improvedTemplate,
        'excerpt' => 'Newsletter de bienvenue pour les nouveaux utilisateurs avec design amélioré et contrastes optimisés.',
        'status' => 'draft',
        'is_template' => false, // Important : pas un template pour qu\'elle apparaisse dans la liste
        'created_by' => 1
    ]);
    
    echo "✅ Newsletter de bienvenue VISIBLE créée avec succès !\n";
    echo "ID: {$newsletter->id}\n";
    echo "Titre: {$newsletter->title}\n";
    echo "Statut: {$newsletter->status}\n";
    echo "Template: " . ($newsletter->is_template ? 'Oui' : 'Non') . "\n";
    echo "URL Admin: http://127.0.0.1:8000/admin/newsletters/{$newsletter->id}\n\n";
    
    echo "📋 Maintenant visible dans la liste admin : http://127.0.0.1:8000/admin/newsletters\n\n";
    
    echo "🎨 Améliorations visuelles :\n";
    echo "✅ Contrastes ultra-optimisés (texte #2c3e50 sur fond blanc)\n";
    echo "✅ Boutons avec bordures épaisses et ombres prononcées\n";
    echo "✅ Typography bold avec lettres espacées (letter-spacing)\n";
    echo "✅ Couleurs vives et saturées pour meilleure visibilité\n";
    echo "✅ Padding généreux sur tous les éléments cliquables\n";
    echo "✅ Footer avec liens contrastés (#fbbf24 sur fond sombre)\n";
    echo "✅ Design mobile-first avec breakpoints optimisés\n\n";
    
    echo "🎯 Cette newsletter sera maintenant visible dans l\'interface admin !\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
