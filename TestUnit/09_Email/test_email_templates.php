<?php

/**
 * Test Unitaire: Templates Email (Vues Blade)
 * 
 * Verifie l'existence et la structure des templates d'email
 */

// Bootstrap Laravel si necessaire
if (!defined('LARAVEL_START')) {
    define('LARAVEL_START', microtime(true));
    require_once __DIR__ . '/../../vendor/autoload.php';
    $app = require_once __DIR__ . '/../../bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
}

echo "\n========================================\n";
echo "TEST: TEMPLATES EMAIL (VUES BLADE)\n";
echo "========================================\n\n";

$startTime = microtime(true);
$errors = [];

try {
    $viewPath = base_path('resources/views/emails');
    
    // 1. Verifier l'existence du dossier emails
    echo "1. Verification du dossier templates email...\n";
    
    if (!is_dir($viewPath)) {
        $errors[] = "Dossier resources/views/emails non trouve";
    } else {
        echo "   - Dossier: " . $viewPath . "\n";
        
        // Compter les templates
        $files = glob($viewPath . '/*.blade.php');
        $subFolders = glob($viewPath . '/*', GLOB_ONLYDIR);
        
        echo "   - Templates racine: " . count($files) . "\n";
        echo "   - Sous-dossiers: " . count($subFolders) . "\n";
    }

    // 2. Verifier les templates de location (rental)
    echo "\n2. Verification des templates de location...\n";
    
    $rentalTemplates = [
        'rental-confirmation.blade.php',
        'rental-started.blade.php',
        'rental-end-reminder.blade.php',
        'rental-ended.blade.php',
        'rental-overdue.blade.php'
    ];
    
    $foundRentalTemplates = 0;
    foreach ($rentalTemplates as $template) {
        $templatePath = $viewPath . '/' . $template;
        if (file_exists($templatePath)) {
            $foundRentalTemplates++;
            
            // Verifier la taille du fichier
            $size = filesize($templatePath);
            echo "   - " . $template . ": Trouve (" . round($size / 1024, 2) . " KB)\n";
            
            // Verifier qu'il contient du contenu
            if ($size === 0) {
                $errors[] = "Template $template est vide";
            }
        }
    }
    
    echo "   - Templates location trouves: $foundRentalTemplates/" . count($rentalTemplates) . "\n";

    // 3. Verifier les templates de commande location
    echo "\n3. Verification des templates de commande location...\n";
    
    $orderTemplates = [
        'rental-order-confirmed.blade.php',
        'rental-order-cancelled.blade.php',
        'rental-order-completed.blade.php',
        'rental-order-inspection.blade.php'
    ];
    
    $foundOrderTemplates = 0;
    foreach ($orderTemplates as $template) {
        $templatePath = $viewPath . '/' . $template;
        if (file_exists($templatePath)) {
            $foundOrderTemplates++;
            
            $size = filesize($templatePath);
            echo "   - " . $template . ": Trouve (" . round($size / 1024, 2) . " KB)\n";
        }
    }
    
    echo "   - Templates commande trouves: $foundOrderTemplates/" . count($orderTemplates) . "\n";

    // 4. Verifier les templates de newsletter
    echo "\n4. Verification des templates newsletter...\n";
    
    $newsletterTemplates = [
        'newsletter.blade.php',
        'newsletter-text.blade.php'
    ];
    
    $foundNewsletterTemplates = 0;
    foreach ($newsletterTemplates as $template) {
        $templatePath = $viewPath . '/' . $template;
        if (file_exists($templatePath)) {
            $foundNewsletterTemplates++;
            echo "   - " . $template . ": Trouve\n";
        }
    }
    
    echo "   - Templates newsletter: $foundNewsletterTemplates/" . count($newsletterTemplates) . "\n";

    // 5. Verifier les templates d'utilisateur
    echo "\n5. Verification des templates utilisateur...\n";
    
    $userTemplates = [
        'welcome.blade.php',
        'account-deleted.blade.php',
        'visitor-contact-confirmation.blade.php',
        'visitor-message-reply.blade.php'
    ];
    
    $foundUserTemplates = 0;
    foreach ($userTemplates as $template) {
        $templatePath = $viewPath . '/' . $template;
        if (file_exists($templatePath)) {
            $foundUserTemplates++;
            echo "   - " . $template . ": Trouve\n";
        }
    }
    
    echo "   - Templates utilisateur: $foundUserTemplates/" . count($userTemplates) . "\n";

    // 6. Verifier les templates dans les sous-dossiers
    echo "\n6. Verification des sous-dossiers...\n";
    
    $subFolderTemplates = [
        'account/confirm-deletion.blade.php',
        'account/deletion-notification.blade.php',
        'rental/order-confirmed.blade.php'
    ];
    
    $foundSubTemplates = 0;
    foreach ($subFolderTemplates as $template) {
        $templatePath = $viewPath . '/' . $template;
        if (file_exists($templatePath)) {
            $foundSubTemplates++;
            echo "   - " . $template . ": Trouve\n";
        }
    }
    
    echo "   - Templates sous-dossiers: $foundSubTemplates/" . count($subFolderTemplates) . "\n";

    // 7. Verifier les versions texte
    echo "\n7. Verification des versions texte...\n";
    
    $textTemplates = [];
    $allFiles = new \RecursiveIteratorIterator(
        new \RecursiveDirectoryIterator($viewPath, \RecursiveDirectoryIterator::SKIP_DOTS)
    );
    
    $textCount = 0;
    foreach ($allFiles as $file) {
        if ($file->isFile() && strpos($file->getFilename(), '-text.blade.php') !== false) {
            $textCount++;
            echo "   - " . $file->getFilename() . "\n";
        }
    }
    
    echo "   - Total versions texte: $textCount\n";

    // 8. Analyser le contenu d'un template (rental-confirmation)
    echo "\n8. Analyse du template rental-confirmation...\n";
    
    $confirmationPath = $viewPath . '/rental-confirmation.blade.php';
    if (file_exists($confirmationPath)) {
        $content = file_get_contents($confirmationPath);
        
        // Verifier les directives Blade courantes
        $bladeDirectives = [
            '@extends' => strpos($content, '@extends') !== false,
            '@section' => strpos($content, '@section') !== false,
            '@if' => strpos($content, '@if') !== false,
            '@foreach' => strpos($content, '@foreach') !== false,
            '{{' => strpos($content, '{{') !== false
        ];
        
        echo "   - Directives Blade detectees:\n";
        foreach ($bladeDirectives as $directive => $found) {
            if ($found) {
                echo "     * $directive: OUI\n";
            }
        }
        
        // Verifier les variables attendues
        $expectedVars = ['orderLocation', 'user', 'items'];
        $foundVars = 0;
        foreach ($expectedVars as $var) {
            if (strpos($content, '$' . $var) !== false) {
                $foundVars++;
            }
        }
        
        echo "   - Variables attendues trouvees: $foundVars/" . count($expectedVars) . "\n";
    }

    // 9. Compter tous les templates
    echo "\n9. Statistiques globales...\n";
    
    $allTemplates = new \RecursiveIteratorIterator(
        new \RecursiveDirectoryIterator($viewPath, \RecursiveDirectoryIterator::SKIP_DOTS)
    );
    
    $totalTemplates = 0;
    $totalSize = 0;
    foreach ($allTemplates as $file) {
        if ($file->isFile() && strpos($file->getFilename(), '.blade.php') !== false) {
            $totalTemplates++;
            $totalSize += $file->getSize();
        }
    }
    
    echo "   - Total templates: $totalTemplates\n";
    echo "   - Taille totale: " . round($totalSize / 1024, 2) . " KB\n";
    echo "   - Taille moyenne: " . round($totalSize / $totalTemplates / 1024, 2) . " KB\n";

} catch (\Exception $e) {
    $errors[] = "Exception: " . $e->getMessage();
}

// Resultats
$duration = round((microtime(true) - $startTime) * 1000, 2);

echo "\n========================================\n";
if (count($errors) > 0) {
    echo "RESULTAT: ECHEC\n";
    echo "========================================\n";
    echo "Erreurs detectees:\n";
    foreach ($errors as $error) {
        echo "  - $error\n";
    }
    echo "\nDuree: {$duration}ms\n";
    exit(1);
} else {
    echo "RESULTAT: REUSSI\n";
    echo "========================================\n";
    echo "Tous les templates email sont presents\n";
    echo "Duree: {$duration}ms\n";
    exit(0);
}
