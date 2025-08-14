use App\Models\Newsletter;

// Supprimer la newsletter de bienvenue existante si elle existe
Newsletter::where('title', 'Bienvenue sur FarmShop !')->delete();

// Contenu HTML amélioré avec mise en page optimisée
$welcomeContent = '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue sur FarmShop</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: "Segoe UI", sans-serif; line-height: 1.6; color: #333; background: #f8f9fa; }
        .email-container { max-width: 600px; margin: 0 auto; background: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #2d5a27 0%, #4a7c59 100%); padding: 40px 30px; text-align: center; color: white; }
        .logo { font-size: 32px; font-weight: bold; margin-bottom: 10px; text-shadow: 2px 2px 4px rgba(0,0,0,0.3); }
        .tagline { font-size: 16px; opacity: 0.9; font-style: italic; }
        .content { padding: 40px 30px; }
        .welcome-title { font-size: 28px; font-weight: bold; color: #2d5a27; text-align: center; margin-bottom: 20px; }
        .welcome-text { font-size: 16px; line-height: 1.8; color: #555; text-align: center; margin-bottom: 30px; }
        .features-grid { display: table; width: 100%; margin: 30px 0; }
        .feature-card { display: table-cell; width: 50%; padding: 20px; text-align: center; vertical-align: top; }
        .feature-icon { width: 60px; height: 60px; background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); border-radius: 50%; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; font-size: 24px; color: white; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .feature-title { font-size: 18px; font-weight: bold; color: #2d5a27; margin-bottom: 10px; }
        .feature-text { font-size: 14px; color: #666; line-height: 1.5; }
        .cta-section { background: #f8f9fa; padding: 30px; margin: 30px 0; border-radius: 8px; text-align: center; }
        .cta-title { font-size: 22px; font-weight: bold; color: #2d5a27; margin-bottom: 15px; }
        .cta-text { font-size: 16px; color: #555; margin-bottom: 25px; }
        .btn-primary { display: inline-block; padding: 15px 30px; background: linear-gradient(135deg, #2d5a27 0%, #4a7c59 100%); color: #ffffff !important; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 16px; margin: 10px; box-shadow: 0 4px 12px rgba(45, 90, 39, 0.3); }
        .btn-secondary { display: inline-block; padding: 15px 30px; background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); color: #ffffff !important; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 16px; margin: 10px; box-shadow: 0 4px 12px rgba(243, 156, 18, 0.3); }
        .stats-section { background: linear-gradient(135deg, #2d5a27 0%, #4a7c59 100%); color: white; padding: 30px; text-align: center; margin: 30px 0; }
        .stats-grid { display: table; width: 100%; margin-top: 20px; }
        .stat-item { display: table-cell; width: 33.33%; text-align: center; padding: 10px; }
        .stat-number { font-size: 32px; font-weight: bold; color: #f39c12; display: block; }
        .stat-label { font-size: 14px; opacity: 0.9; margin-top: 5px; }
        .footer { background: #2c3e50; color: #ecf0f1; padding: 30px; text-align: center; }
        .footer-title { font-size: 18px; font-weight: bold; margin-bottom: 15px; }
        .footer-text { font-size: 14px; line-height: 1.6; opacity: 0.8; margin-bottom: 20px; }
        .social-links { margin: 20px 0; }
        .social-link { display: inline-block; margin: 0 10px; padding: 10px 15px; background: #34495e; color: #ffffff !important; text-decoration: none; border-radius: 4px; font-size: 14px; }
        .unsubscribe { margin-top: 20px; padding-top: 20px; border-top: 1px solid #34495e; font-size: 12px; opacity: 0.7; }
        .unsubscribe a { color: #f39c12 !important; text-decoration: none; }
        @media only screen and (max-width: 600px) {
            .email-container { width: 100% !important; }
            .header, .content, .footer { padding: 20px !important; }
            .features-grid, .stats-grid { display: block !important; }
            .feature-card, .stat-item { display: block !important; width: 100% !important; margin-bottom: 20px; }
            .btn-primary, .btn-secondary { display: block !important; margin: 10px 0 !important; }
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
            <p class="welcome-text">Félicitations ! Votre compte a été créé avec succès. Nous sommes ravis de vous accueillir dans notre communauté d\'agriculteurs, de passionnés et de consommateurs responsables.</p>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">🛒</div>
                    <h3 class="feature-title">Achat Direct</h3>
                    <p class="feature-text">Achetez directement auprès des producteurs locaux et découvrez des produits frais de qualité.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🚜</div>
                    <h3 class="feature-title">Location Matériel</h3>
                    <p class="feature-text">Louez du matériel agricole professionnel pour vos projets, du simple outil aux machines spécialisées.</p>
                </div>
            </div>
            <div class="cta-section">
                <h2 class="cta-title">Prêt à commencer votre aventure ?</h2>
                <p class="cta-text">Explorez notre catalogue et découvrez tout ce que FarmShop a à vous offrir.</p>
                <a href="http://127.0.0.1:8000/products" class="btn-primary">🛍️ Découvrir les Produits</a>
                <a href="http://127.0.0.1:8000/rentals" class="btn-secondary">🚜 Explorer les Locations</a>
            </div>
        </div>
        <div class="stats-section">
            <h2>FarmShop en chiffres</h2>
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
                <p class="cta-text">Notre équipe est là pour vous accompagner dans vos premiers pas sur FarmShop.</p>
                <a href="http://127.0.0.1:8000/contact" class="btn-primary">📞 Nous Contacter</a>
                <a href="http://127.0.0.1:8000/blog" class="btn-secondary">📖 Lire le Blog</a>
            </div>
        </div>
        <div class="footer">
            <h3 class="footer-title">Merci de faire confiance à FarmShop</h3>
            <p class="footer-text">Ensemble, soutenons l\'agriculture locale et construisons un avenir plus durable. Votre succès est notre priorité !</p>
            <div class="social-links">
                <a href="#" class="social-link">📧 Newsletter</a>
                <a href="#" class="social-link">📱 Nous Suivre</a>
                <a href="http://127.0.0.1:8000/contact" class="social-link">💬 Support</a>
            </div>
            <div class="unsubscribe">
                <p>Vous recevez cet email car vous venez de créer un compte sur FarmShop.<br><a href="{{unsubscribe_url}}">Se désabonner des newsletters</a> | <a href="http://127.0.0.1:8000/privacy">Politique de confidentialité</a></p>
            </div>
        </div>
    </div>
</body>
</html>';

$newsletter = Newsletter::create([
    "title" => "Bienvenue sur FarmShop !",
    "subject" => "🌱 Bienvenue sur FarmShop - Votre aventure agricole commence !",
    "content" => $welcomeContent,
    "status" => "draft",
    "is_template" => true,
    "template_name" => "Bienvenue Nouveaux Utilisateurs",
    "tags" => ["bienvenue", "onboarding", "nouveaux-utilisateurs"]
]);

echo "✅ Newsletter de bienvenue créée avec succès !\n";
echo "ID: " . $newsletter->id . "\n";
echo "Titre: " . $newsletter->title . "\n";
echo "URL Admin: http://127.0.0.1:8000/admin/newsletters/" . $newsletter->id . "\n";

exit;
