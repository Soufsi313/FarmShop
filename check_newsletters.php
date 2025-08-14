<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Vérification des newsletters en base de données ===\n\n";

try {
    // Récupérer toutes les newsletters
    $newsletters = \App\Models\Newsletter::all();
    
    echo "📊 Nombre total de newsletters : " . $newsletters->count() . "\n\n";
    
    if ($newsletters->count() === 0) {
        echo "❌ Aucune newsletter trouvée en base de données\n";
        echo "Créons une newsletter de test...\n\n";
        
        // Créer une newsletter simple
        $newsletter = \App\Models\Newsletter::create([
            'title' => 'Newsletter de Test',
            'subject' => 'Test Subject',
            'content' => '<h1>Test Content</h1>',
            'status' => 'draft',
            'created_by' => 1
        ]);
        
        echo "✅ Newsletter de test créée (ID: {$newsletter->id})\n\n";
    }
    
    // Afficher toutes les newsletters
    foreach ($newsletters as $newsletter) {
        echo "📧 Newsletter ID: {$newsletter->id}\n";
        echo "   Titre: {$newsletter->title}\n";
        echo "   Sujet: {$newsletter->subject}\n";
        echo "   Statut: {$newsletter->status}\n";
        echo "   Créé par: {$newsletter->created_by}\n";
        echo "   Template: " . ($newsletter->is_template ? 'Oui' : 'Non') . "\n";
        echo "   Créé le: {$newsletter->created_at}\n";
        echo "   URL Admin: http://127.0.0.1:8000/admin/newsletters/{$newsletter->id}\n\n";
    }
    
    // Vérifier spécifiquement la newsletter de bienvenue
    $welcomeNewsletters = \App\Models\Newsletter::where('title', 'Bienvenue sur FarmShop !')->get();
    
    echo "🎯 Newsletters de bienvenue trouvées : " . $welcomeNewsletters->count() . "\n";
    
    foreach ($welcomeNewsletters as $newsletter) {
        echo "   - ID: {$newsletter->id}, Statut: {$newsletter->status}, Template: {$newsletter->template_name}\n";
    }
    
    // Vérifier la structure de la table
    echo "\n📋 Structure de la table newsletters :\n";
    $tableInfo = \Illuminate\Support\Facades\DB::select("DESCRIBE newsletters");
    
    foreach ($tableInfo as $column) {
        echo "   - {$column->Field} ({$column->Type})\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
