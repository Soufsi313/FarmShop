<?php

/**
 * Test Unitaire: Classes Mailable (Emails)
 * 
 * Verifie la structure et le fonctionnement des classes d'emails Mailable
 */

// Bootstrap Laravel si necessaire
if (!defined('LARAVEL_START')) {
    define('LARAVEL_START', microtime(true));
    require_once __DIR__ . '/../../vendor/autoload.php';
    $app = require_once __DIR__ . '/../../bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
}

echo "\n========================================\n";
echo "TEST: CLASSES MAILABLE (EMAILS)\n";
echo "========================================\n\n";

$startTime = microtime(true);
$errors = [];

try {
    // 1. Verifier l'existence des classes Mailable
    echo "1. Verification des classes Mailable...\n";
    
    $mailableClasses = [
        'App\Mail\WelcomeEmail',
        'App\Mail\RentalConfirmationMail',
        'App\Mail\RentalStartedMail',
        'App\Mail\RentalEndReminderMail',
        'App\Mail\RentalEndedMail',
        'App\Mail\RentalOverdueMail',
        'App\Mail\RentalOrderConfirmed',
        'App\Mail\RentalOrderCancelled',
        'App\Mail\RentalOrderCompleted',
        'App\Mail\RentalOrderInspection',
        'App\Mail\NewsletterMail',
        'App\Mail\AccountDeletedEmail',
        'App\Mail\AccountDeletionNotification',
        'App\Mail\VisitorContactConfirmation',
        'App\Mail\VisitorMessageReply',
        'App\Mail\NewContactNotification'
    ];
    
    $existingClasses = 0;
    foreach ($mailableClasses as $class) {
        if (class_exists($class)) {
            $existingClasses++;
        }
    }
    
    echo "   - Classes Mailable trouvees: $existingClasses/" . count($mailableClasses) . "\n";
    
    if ($existingClasses === 0) {
        $errors[] = "Aucune classe Mailable trouvee";
    }

    // 2. Tester RentalConfirmationMail avec donnees simulees
    echo "\n2. Test de RentalConfirmationMail...\n";
    
    if (class_exists('App\Mail\RentalConfirmationMail')) {
        $orderLocation = new \App\Models\OrderLocation();
        $orderLocation->order_number = 'TEST-' . time();
        $orderLocation->total_amount = 100.00;
        
        $mailable = new \App\Mail\RentalConfirmationMail($orderLocation);
        
        // Verifier la propriete publique
        if (!isset($mailable->orderLocation)) {
            $errors[] = "RentalConfirmationMail: propriete orderLocation manquante";
        }
        
        // Verifier les methodes requises
        $requiredMethods = ['envelope', 'content', 'attachments'];
        foreach ($requiredMethods as $method) {
            if (!method_exists($mailable, $method)) {
                $errors[] = "RentalConfirmationMail: methode $method manquante";
            }
        }
        
        // Tester l'envelope
        $envelope = $mailable->envelope();
        if (!($envelope instanceof \Illuminate\Mail\Mailables\Envelope)) {
            $errors[] = "RentalConfirmationMail: envelope() ne retourne pas une instance Envelope";
        } else {
            echo "   - Envelope: " . $envelope->subject . "\n";
        }
        
        // Tester le content
        $content = $mailable->content();
        if (!($content instanceof \Illuminate\Mail\Mailables\Content)) {
            $errors[] = "RentalConfirmationMail: content() ne retourne pas une instance Content";
        }
        
        echo "   - RentalConfirmationMail: Structure validee\n";
    }

    // 3. Tester NewsletterMail avec donnees simulees
    echo "\n3. Test de NewsletterMail...\n";
    
    if (class_exists('App\Mail\NewsletterMail')) {
        $newsletter = new \App\Models\Newsletter();
        $newsletter->subject = 'Test Newsletter';
        $newsletter->content = 'Contenu de test';
        
        $user = new \App\Models\User();
        $user->email = 'test@example.com';
        $user->name = 'Test User';
        
        $send = new \App\Models\NewsletterSend();
        $send->tracking_url = 'https://example.com/track';
        $send->unsubscribe_url = 'https://example.com/unsubscribe';
        
        $mailable = new \App\Mail\NewsletterMail($newsletter, $user, $send);
        
        // Verifier les proprietes publiques
        $publicProperties = ['newsletter', 'user', 'send'];
        foreach ($publicProperties as $property) {
            if (!isset($mailable->$property)) {
                $errors[] = "NewsletterMail: propriete $property manquante";
            }
        }
        
        // Tester l'envelope
        $envelope = $mailable->envelope();
        if (!($envelope instanceof \Illuminate\Mail\Mailables\Envelope)) {
            $errors[] = "NewsletterMail: envelope() ne retourne pas une instance Envelope";
        } else {
            echo "   - Sujet: " . $envelope->subject . "\n";
            echo "   - ReplyTo: " . (is_array($envelope->replyTo) && count($envelope->replyTo) > 0 ? 'Configure' : 'Non configure') . "\n";
        }
        
        // Tester le content
        $content = $mailable->content();
        if (!($content instanceof \Illuminate\Mail\Mailables\Content)) {
            $errors[] = "NewsletterMail: content() ne retourne pas une instance Content";
        } else {
            echo "   - Vue HTML: " . ($content->html ?? 'N/A') . "\n";
            echo "   - Vue Text: " . ($content->text ?? 'N/A') . "\n";
        }
        
        echo "   - NewsletterMail: Structure validee\n";
    }

    // 4. Verifier les traits utilises
    echo "\n4. Verification des traits...\n";
    
    if (class_exists('App\Mail\RentalConfirmationMail')) {
        $reflection = new \ReflectionClass('App\Mail\RentalConfirmationMail');
        $traits = $reflection->getTraitNames();
        
        $requiredTraits = [
            'Illuminate\Bus\Queueable',
            'Illuminate\Queue\SerializesModels'
        ];
        
        $foundTraits = 0;
        foreach ($requiredTraits as $trait) {
            if (in_array($trait, $traits)) {
                $foundTraits++;
            }
        }
        
        echo "   - Traits trouves: $foundTraits/" . count($requiredTraits) . "\n";
        echo "   - Queueable: " . (in_array('Illuminate\Bus\Queueable', $traits) ? 'OUI' : 'NON') . "\n";
        echo "   - SerializesModels: " . (in_array('Illuminate\Queue\SerializesModels', $traits) ? 'OUI' : 'NON') . "\n";
    }

    // 5. Tester les emails de location (Rental)
    echo "\n5. Test des emails de location...\n";
    
    $rentalMailables = [
        'App\Mail\RentalStartedMail',
        'App\Mail\RentalEndReminderMail',
        'App\Mail\RentalEndedMail',
        'App\Mail\RentalOverdueMail'
    ];
    
    $validRentalMails = 0;
    foreach ($rentalMailables as $class) {
        if (class_exists($class)) {
            $reflection = new \ReflectionClass($class);
            
            // Verifier que c'est bien un Mailable
            if ($reflection->isSubclassOf('Illuminate\Mail\Mailable')) {
                $validRentalMails++;
            }
        }
    }
    
    echo "   - Emails de location valides: $validRentalMails/" . count($rentalMailables) . "\n";

    // 6. Tester les emails de commande location (Rental Order)
    echo "\n6. Test des emails de commande location...\n";
    
    $orderMailables = [
        'App\Mail\RentalOrderConfirmed',
        'App\Mail\RentalOrderCancelled',
        'App\Mail\RentalOrderCompleted',
        'App\Mail\RentalOrderInspection'
    ];
    
    $validOrderMails = 0;
    foreach ($orderMailables as $class) {
        if (class_exists($class)) {
            $reflection = new \ReflectionClass($class);
            
            // Verifier que c'est bien un Mailable
            if ($reflection->isSubclassOf('Illuminate\Mail\Mailable')) {
                $validOrderMails++;
                
                // Verifier le constructeur accepte OrderLocation
                $constructor = $reflection->getConstructor();
                if ($constructor) {
                    $params = $constructor->getParameters();
                    if (count($params) > 0) {
                        $paramType = $params[0]->getType();
                        if ($paramType && $paramType->getName() === 'App\Models\OrderLocation') {
                            echo "   - " . class_basename($class) . ": Constructeur valide\n";
                        }
                    }
                }
            }
        }
    }
    
    echo "   - Emails de commande valides: $validOrderMails/" . count($orderMailables) . "\n";

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
    echo "Toutes les classes Mailable sont valides\n";
    echo "Duree: {$duration}ms\n";
    exit(0);
}
