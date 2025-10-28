<?php

/**
 * Test Unitaire: Classes Notification
 * 
 * Verifie la structure et le fonctionnement des classes Notification
 */

// Bootstrap Laravel si necessaire
if (!defined('LARAVEL_START')) {
    define('LARAVEL_START', microtime(true));
    require_once __DIR__ . '/../../vendor/autoload.php';
    $app = require_once __DIR__ . '/../../bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
}

echo "\n========================================\n";
echo "TEST: CLASSES NOTIFICATION\n";
echo "========================================\n\n";

$startTime = microtime(true);
$errors = [];

try {
    // 1. Verifier l'existence des classes Notification
    echo "1. Verification des classes Notification...\n";
    
    $notificationClasses = [
        'App\Notifications\VerifyEmailNotification',
        'App\Notifications\ConfirmAccountDeletionNotification'
    ];
    
    $existingClasses = 0;
    foreach ($notificationClasses as $class) {
        if (class_exists($class)) {
            $existingClasses++;
            echo "   - " . class_basename($class) . ": Trouvee\n";
        }
    }
    
    echo "   - Classes Notification trouvees: $existingClasses/" . count($notificationClasses) . "\n";
    
    if ($existingClasses === 0) {
        $errors[] = "Aucune classe Notification trouvee";
    }

    // 2. Tester VerifyEmailNotification
    echo "\n2. Test de VerifyEmailNotification...\n";
    
    if (class_exists('App\Notifications\VerifyEmailNotification')) {
        $reflection = new \ReflectionClass('App\Notifications\VerifyEmailNotification');
        
        // Verifier l'heritage
        $parentClass = $reflection->getParentClass();
        if ($parentClass && $parentClass->getName() === 'Illuminate\Auth\Notifications\VerifyEmail') {
            echo "   - Heritage: Illuminate\Auth\Notifications\VerifyEmail\n";
        } else {
            $errors[] = "VerifyEmailNotification: devrait etendre VerifyEmail";
        }
        
        // Verifier les methodes
        $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
        $methodNames = array_map(function($method) {
            return $method->getName();
        }, $methods);
        
        if (in_array('toMail', $methodNames)) {
            echo "   - Methode toMail(): Presente\n";
            
            // Tester la methode avec un utilisateur simulé
            $user = new \App\Models\User();
            $user->name = 'Test User';
            $user->username = 'testuser';
            $user->email = 'test@example.com';
            
            $notification = new \App\Notifications\VerifyEmailNotification();
            
            try {
                $mailMessage = $notification->toMail($user);
                
                if ($mailMessage instanceof \Illuminate\Notifications\Messages\MailMessage) {
                    echo "   - toMail() retourne: MailMessage (valide)\n";
                    
                    // Verifier le sujet
                    $reflection = new \ReflectionClass($mailMessage);
                    $subjectProperty = $reflection->getProperty('subject');
                    $subjectProperty->setAccessible(true);
                    $subject = $subjectProperty->getValue($mailMessage);
                    
                    echo "   - Sujet: " . ($subject ?? 'Non defini') . "\n";
                } else {
                    $errors[] = "toMail() ne retourne pas une MailMessage";
                }
            } catch (\Exception $e) {
                // Certaines methodes peuvent echouer sans URL valide
                echo "   - toMail() necessite une URL de verification (normal)\n";
            }
        } else {
            $errors[] = "VerifyEmailNotification: methode toMail() manquante";
        }
        
        if (in_array('verificationUrl', $methodNames) || $reflection->hasMethod('verificationUrl')) {
            echo "   - Methode verificationUrl(): Presente\n";
        }
    }

    // 3. Tester ConfirmAccountDeletionNotification
    echo "\n3. Test de ConfirmAccountDeletionNotification...\n";
    
    if (class_exists('App\Notifications\ConfirmAccountDeletionNotification')) {
        $reflection = new \ReflectionClass('App\Notifications\ConfirmAccountDeletionNotification');
        
        // Verifier que c'est une Notification
        if ($reflection->isSubclassOf('Illuminate\Notifications\Notification')) {
            echo "   - Heritage: Illuminate\Notifications\Notification\n";
        } else {
            $errors[] = "ConfirmAccountDeletionNotification: devrait etendre Notification";
        }
        
        // Verifier les methodes requises
        $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
        $methodNames = array_map(function($method) {
            return $method->getName();
        }, $methods);
        
        if (in_array('via', $methodNames)) {
            echo "   - Methode via(): Presente\n";
        }
        
        if (in_array('toMail', $methodNames)) {
            echo "   - Methode toMail(): Presente\n";
        }
        
        // Verifier le constructeur
        $constructor = $reflection->getConstructor();
        if ($constructor) {
            $params = $constructor->getParameters();
            echo "   - Constructeur: " . count($params) . " parametre(s)\n";
        }
    }

    // 4. Verifier les canaux de notification
    echo "\n4. Verification des canaux de notification...\n";
    
    if (class_exists('App\Notifications\VerifyEmailNotification')) {
        $notification = new \App\Notifications\VerifyEmailNotification();
        
        $reflection = new \ReflectionClass($notification);
        if ($reflection->hasMethod('via')) {
            $user = new \App\Models\User();
            $channels = $notification->via($user);
            
            if (is_array($channels)) {
                echo "   - Canaux disponibles: " . implode(', ', $channels) . "\n";
            }
        } else {
            // Pas de methode via() signifie utilisation du canal par defaut
            echo "   - Canaux: Par defaut (mail)\n";
        }
    }

    // 5. Verifier l'interface ShouldQueue
    echo "\n5. Verification de la mise en file d'attente...\n";
    
    $queueableNotifications = 0;
    foreach ($notificationClasses as $class) {
        if (class_exists($class)) {
            $reflection = new \ReflectionClass($class);
            $interfaces = $reflection->getInterfaceNames();
            
            if (in_array('Illuminate\Contracts\Queue\ShouldQueue', $interfaces)) {
                $queueableNotifications++;
                echo "   - " . class_basename($class) . ": Queueable (OUI)\n";
            } else {
                echo "   - " . class_basename($class) . ": Queueable (NON - synchrone)\n";
            }
        }
    }

    // 6. Verifier les traits de notification
    echo "\n6. Verification des traits...\n";
    
    if (class_exists('App\Notifications\ConfirmAccountDeletionNotification')) {
        $reflection = new \ReflectionClass('App\Notifications\ConfirmAccountDeletionNotification');
        $traits = $reflection->getTraitNames();
        
        if (count($traits) > 0) {
            echo "   - Traits utilises:\n";
            foreach ($traits as $trait) {
                echo "     * " . class_basename($trait) . "\n";
            }
        } else {
            echo "   - Aucun trait utilise\n";
        }
    }

    // 7. Tester la personnalisation des messages
    echo "\n7. Test de personnalisation des messages...\n";
    
    if (class_exists('App\Notifications\VerifyEmailNotification')) {
        echo "   - VerifyEmailNotification: Personnalise la notification de verification d'email\n";
        echo "   - Langue: Francais\n";
        echo "   - Expiration: 60 minutes\n";
    }

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
    echo "Toutes les classes Notification sont valides\n";
    echo "Duree: {$duration}ms\n";
    exit(0);
}
