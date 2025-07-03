<?php

/**
 * Script pour activer/désactiver les emails d'automatisation
 */

require_once 'vendor/autoload.php';

echo "=== GESTION DES EMAILS D'AUTOMATISATION ===\n\n";

$automationFile = 'automation_continuous.php';

if (!file_exists($automationFile)) {
    echo "❌ Fichier {$automationFile} non trouvé\n";
    exit(1);
}

$content = file_get_contents($automationFile);

// Vérifier l'état actuel
$emailsEnabled = strpos($content, '--no-email') === false;

echo "📧 État actuel des emails: " . ($emailsEnabled ? "✅ ACTIVÉS" : "❌ DÉSACTIVÉS") . "\n\n";

echo "Que voulez-vous faire ?\n";
echo "1. ✅ Activer les emails\n";
echo "2. ❌ Désactiver les emails\n";
echo "3. 🔍 Voir la configuration\n";
echo "4. ❌ Annuler\n\n";

$choice = readline("Votre choix (1-4): ");

switch ($choice) {
    case '1':
        if ($emailsEnabled) {
            echo "✅ Les emails sont déjà activés\n";
        } else {
            // Activer les emails (retirer --no-email)
            $newContent = str_replace(
                'php artisan orders:safe-update-status --no-email',
                'php artisan orders:safe-update-status',
                $content
            );
            file_put_contents($automationFile, $newContent);
            echo "✅ Emails ACTIVÉS avec succès\n";
            echo "⚠️  Les notifications seront envoyées aux vrais utilisateurs\n";
            echo "🔄 Redémarrez l'automatisation pour appliquer les changements\n";
        }
        break;
        
    case '2':
        if (!$emailsEnabled) {
            echo "❌ Les emails sont déjà désactivés\n";
        } else {
            // Désactiver les emails (ajouter --no-email)
            $newContent = str_replace(
                'php artisan orders:safe-update-status',
                'php artisan orders:safe-update-status --no-email',
                $content
            );
            file_put_contents($automationFile, $newContent);
            echo "❌ Emails DÉSACTIVÉS avec succès\n";
            echo "✅ L'automatisation continuera sans envoyer d'emails\n";
            echo "🔄 Redémarrez l'automatisation pour appliquer les changements\n";
        }
        break;
        
    case '3':
        echo "🔍 Configuration actuelle:\n";
        echo "   - Fichier: {$automationFile}\n";
        echo "   - Emails: " . ($emailsEnabled ? "✅ ACTIVÉS" : "❌ DÉSACTIVÉS") . "\n";
        echo "   - Commande: " . (strpos($content, 'orders:safe-update-status') !== false ? 
                                  "orders:safe-update-status (SÉCURISÉE)" : 
                                  "orders:update-status (ANCIENNE)") . "\n";
        
        // Analyser les utilisateurs
        $app = require_once 'bootstrap/app.php';
        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
        
        $users = App\Models\User::all();
        $realUsers = $users->filter(function($user) {
            $testEmails = ['test@farmshop.com', 'test.client@farmshop.com', 's.mef2703@gmail.com'];
            return !in_array(strtolower($user->email), $testEmails) && 
                   filter_var($user->email, FILTER_VALIDATE_EMAIL);
        });
        
        echo "   - Utilisateurs réels qui recevraient des emails: {$realUsers->count()}\n";
        echo "   - Total utilisateurs: {$users->count()}\n";
        break;
        
    case '4':
        echo "❌ Annulé\n";
        break;
        
    default:
        echo "❌ Choix invalide\n";
        break;
}

echo "\n🎯 Terminé!\n";
