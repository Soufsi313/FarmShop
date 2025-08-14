<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Création Newsletter de Bienvenue ===\n";

try {
    // Supprimer l'ancienne newsletter
    \App\Models\Newsletter::where('title', 'Bienvenue sur FarmShop !')->delete();
    
    // Créer la nouvelle newsletter
    $newsletter = \App\Models\Newsletter::create([
        'title' => 'Bienvenue sur FarmShop !',
        'subject' => '🌱 Bienvenue sur FarmShop - Votre aventure agricole commence !',
        'content' => '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Bienvenue</title><style>*{margin:0;padding:0;box-sizing:border-box}body{font-family:"Segoe UI",sans-serif;line-height:1.6;color:#333;background:#f8f9fa}.email-container{max-width:600px;margin:0 auto;background:#fff;box-shadow:0 4px 12px rgba(0,0,0,0.1)}.header{background:linear-gradient(135deg,#2d5a27 0%,#4a7c59 100%);padding:40px 30px;text-align:center;color:white}.logo{font-size:32px;font-weight:bold;margin-bottom:10px}.content{padding:40px 30px}.welcome-title{font-size:28px;font-weight:bold;color:#2d5a27;text-align:center;margin-bottom:20px}.welcome-text{font-size:16px;line-height:1.8;color:#555;text-align:center;margin-bottom:30px}.btn-primary{display:inline-block;padding:15px 30px;background:linear-gradient(135deg,#2d5a27 0%,#4a7c59 100%);color:#ffffff!important;text-decoration:none;border-radius:6px;font-weight:bold;font-size:16px;margin:10px;box-shadow:0 4px 12px rgba(45,90,39,0.3)}.btn-secondary{display:inline-block;padding:15px 30px;background:linear-gradient(135deg,#f39c12 0%,#e67e22 100%);color:#ffffff!important;text-decoration:none;border-radius:6px;font-weight:bold;font-size:16px;margin:10px;box-shadow:0 4px 12px rgba(243,156,18,0.3)}.footer{background:#2c3e50;color:#ecf0f1;padding:30px;text-align:center}</style></head><body><div class="email-container"><div class="header"><div class="logo">🌱 FarmShop</div><div>Votre marketplace agricole de confiance</div></div><div class="content"><h1 class="welcome-title">Bienvenue dans la communauté FarmShop ! 🎉</h1><p class="welcome-text">Félicitations ! Votre compte a été créé avec succès. Nous sommes ravis de vous accueillir dans notre communauté.</p><div style="text-align:center;margin:30px 0"><a href="http://127.0.0.1:8000/products" class="btn-primary">🛍️ Découvrir les Produits</a><a href="http://127.0.0.1:8000/rentals" class="btn-secondary">🚜 Explorer les Locations</a></div></div><div class="footer"><h3>Merci de faire confiance à FarmShop</h3><p>Ensemble, soutenons l\'agriculture locale !</p><p style="margin-top:20px;font-size:12px"><a href="{{unsubscribe_url}}" style="color:#f39c12">Se désabonner</a></p></div></div></body></html>',
        'status' => 'draft',
        'is_template' => true,
        'template_name' => 'Bienvenue Nouveaux Utilisateurs',
        'created_by' => 1 // Admin user
    ]);
    
    echo "✅ Newsletter créée avec succès !\n";
    echo "ID: {$newsletter->id}\n";
    echo "Titre: {$newsletter->title}\n";
    echo "URL: http://127.0.0.1:8000/admin/newsletters/{$newsletter->id}\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
