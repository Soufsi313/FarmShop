<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\OrderLocation;
use App\Models\User;
use App\Mail\RentalOrderConfirmed;
use App\Mail\RentalStartedMail;
use App\Mail\RentalEndReminderMail;
use Illuminate\Support\Facades\Mail;

// Initialiser Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TEST DE TOUS LES TEMPLATES D'EMAIL DE LOCATION ===\n\n";

try {
    // Récupérer une commande de location pour les tests
    $orderLocation = OrderLocation::with(['user', 'items.product'])->first();
    
    if (!$orderLocation) {
        echo "❌ Aucune commande de location trouvée dans la base de données.\n";
        exit;
    }
    
    echo "✅ Commande de test trouvée: #{$orderLocation->id}\n";
    echo "👤 Utilisateur: {$orderLocation->user->first_name} {$orderLocation->user->last_name}\n";
    echo "📧 Email: {$orderLocation->user->email}\n\n";
    
    // Liste des templates à tester
    $templates = [
        [
            'name' => 'CONFIRMATION DE COMMANDE',
            'class' => RentalOrderConfirmed::class,
            'description' => 'Email envoyé après la validation de la commande'
        ],
        [
            'name' => 'DÉMARRAGE DE LOCATION',
            'class' => RentalStartedMail::class,
            'description' => 'Email envoyé quand la location commence'
        ],
        [
            'name' => 'RAPPEL FIN DE LOCATION',
            'class' => RentalEndReminderMail::class,
            'description' => 'Email de rappel avant la fin de location'
        ]
    ];
    
    foreach ($templates as $index => $template) {
        echo str_repeat("=", 70) . "\n";
        echo "📧 TEMPLATE " . ($index + 1) . ": {$template['name']}\n";
        echo "📝 Description: {$template['description']}\n";
        echo str_repeat("=", 70) . "\n\n";
        
        try {
            // Créer l'instance du mail
            if ($template['class'] === RentalOrderConfirmed::class) {
                $mail = new $template['class']($orderLocation);
            } else {
                $mail = new $template['class']($orderLocation);
            }
            
            // Générer le contenu HTML
            $content = $mail->render();
            
            // Sauvegarder le template dans un fichier pour visualisation
            $filename = "template_test_" . ($index + 1) . "_" . strtolower(str_replace(' ', '_', $template['name'])) . ".html";
            file_put_contents($filename, $content);
            
            echo "✅ Template généré avec succès !\n";
            echo "📄 Fichier créé: {$filename}\n";
            echo "🔍 Longueur du contenu: " . strlen($content) . " caractères\n";
            
            // Afficher un aperçu du début du template
            $preview = strip_tags(substr($content, 0, 200));
            echo "👀 Aperçu: " . trim($preview) . "...\n\n";
            
        } catch (Exception $e) {
            echo "❌ Erreur lors de la génération du template: " . $e->getMessage() . "\n\n";
            
            // Essayer de voir le fichier blade directement
            $bladePaths = [
                'resources/views/emails/rental-order-confirmed.blade.php',
                'resources/views/emails/rental-started.blade.php',
                'resources/views/emails/rental-end-reminder.blade.php'
            ];
            
            if (isset($bladePaths[$index]) && file_exists($bladePaths[$index])) {
                $bladeContent = file_get_contents($bladePaths[$index]);
                echo "📝 Contenu du fichier Blade ({$bladePaths[$index]}):\n";
                echo "📏 Taille: " . strlen($bladeContent) . " caractères\n";
                if (strlen($bladeContent) === 0) {
                    echo "⚠️  ATTENTION: Le fichier est VIDE !\n";
                } else {
                    echo "✅ Le fichier contient du contenu\n";
                    // Aperçu des premières lignes
                    $lines = explode("\n", $bladeContent);
                    echo "🔍 Premières lignes:\n";
                    for ($i = 0; $i < min(5, count($lines)); $i++) {
                        echo "   " . ($i + 1) . ": " . trim($lines[$i]) . "\n";
                    }
                }
            } else {
                echo "❌ Fichier Blade non trouvé: {$bladePaths[$index]}\n";
            }
            echo "\n";
        }
    }
    
    echo str_repeat("=", 70) . "\n";
    echo "📊 RÉSUMÉ DES FICHIERS BLADE TEMPLATES\n";
    echo str_repeat("=", 70) . "\n";
    
    $bladeFiles = [
        'resources/views/emails/rental-order-confirmed.blade.php' => 'Confirmation de commande',
        'resources/views/emails/rental-started.blade.php' => 'Démarrage de location',
        'resources/views/emails/rental-end-reminder.blade.php' => 'Rappel fin de location'
    ];
    
    foreach ($bladeFiles as $file => $description) {
        echo "📄 {$description}:\n";
        echo "   Fichier: {$file}\n";
        
        if (file_exists($file)) {
            $size = filesize($file);
            echo "   ✅ Existe - Taille: {$size} octets\n";
            
            if ($size === 0) {
                echo "   ⚠️  PROBLÈME: Fichier VIDE !\n";
            } else {
                $content = file_get_contents($file);
                $lines = count(explode("\n", $content));
                echo "   📏 Lignes: {$lines}\n";
                
                // Vérifier le type de contenu
                if (strpos($content, '<!DOCTYPE html>') !== false) {
                    echo "   🌐 Type: HTML complet\n";
                } elseif (strpos($content, '<html') !== false) {
                    echo "   🏷️  Type: Contenu HTML\n";
                } elseif (strpos($content, '@') !== false) {
                    echo "   🔧 Type: Template Blade avec directives\n";
                } else {
                    echo "   ❓ Type: Contenu indéterminé\n";
                }
            }
        } else {
            echo "   ❌ FICHIER MANQUANT !\n";
        }
        echo "\n";
    }
    
    echo "🎯 Pour voir les templates, ouvrez les fichiers .html générés dans votre navigateur\n";
    echo "📁 Fichiers créés dans le répertoire courant\n";
    
} catch (Exception $e) {
    echo "❌ Erreur générale: " . $e->getMessage() . "\n";
    echo "📍 Trace: " . $e->getTraceAsString() . "\n";
}
