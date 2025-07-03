<?php

/**
 * Configuration des emails pour éviter le spam et gérer les erreurs
 * Script de configuration pour l'environnement de production
 */

require_once 'vendor/autoload.php';

// Démarrer l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CONFIGURATION DES EMAILS FARMSHOP ===\n\n";

// 1. Vérifier la configuration actuelle
echo "🔍 Configuration email actuelle:\n";
echo "   - MAIL_MAILER: " . env('MAIL_MAILER', 'smtp') . "\n";
echo "   - MAIL_HOST: " . env('MAIL_HOST', 'non défini') . "\n";
echo "   - MAIL_PORT: " . env('MAIL_PORT', 'non défini') . "\n";
echo "   - MAIL_FROM_ADDRESS: " . env('MAIL_FROM_ADDRESS', 'non défini') . "\n";
echo "   - MAIL_FROM_NAME: " . env('MAIL_FROM_NAME', 'non défini') . "\n\n";

// 2. Lister les emails de test à exclure
$testEmails = [
    'test@farmshop.com',
    'test.client@farmshop.com', 
    'admin@farmshop.com',
    'noreply@farmshop.com',
    's.mef2703@gmail.com' // Email de test du développeur
];

echo "📧 Emails de test exclus (ne recevront PAS d'emails):\n";
foreach ($testEmails as $email) {
    echo "   ❌ {$email}\n";
}
echo "\n";

// 3. Compter les utilisateurs avec des emails valides
$users = App\Models\User::all();
$validEmails = 0;
$testEmailsCount = 0;
$invalidEmails = 0;

echo "👥 Analyse des utilisateurs:\n";
foreach ($users as $user) {
    $email = $user->email;
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $invalidEmails++;
        echo "   ❌ INVALIDE: {$email}\n";
        continue;
    }
    
    if (in_array(strtolower($email), array_map('strtolower', $testEmails))) {
        $testEmailsCount++;
        echo "   🧪 TEST: {$email}\n";
        continue;
    }
    
    $validEmails++;
    echo "   ✅ VALIDE: {$email}\n";
}

echo "\n📊 Résumé:\n";
echo "   - Emails valides (recevront des notifications): {$validEmails}\n";
echo "   - Emails de test (exclus): {$testEmailsCount}\n";
echo "   - Emails invalides: {$invalidEmails}\n";
echo "   - Total utilisateurs: " . $users->count() . "\n\n";

// 4. Recommandations
echo "💡 Recommandations:\n";

if (env('MAIL_MAILER') === 'log') {
    echo "   ⚠️  MAIL_MAILER est en mode 'log' - les emails ne seront pas envoyés\n";
    echo "   ✅ Pour la production, changez vers 'smtp' ou 'sendmail'\n";
}

if (!env('MAIL_HOST')) {
    echo "   ❌ MAIL_HOST non configuré - configurez votre serveur SMTP\n";
}

if (env('APP_ENV') === 'local') {
    echo "   🧪 Environnement LOCAL détecté\n";
    echo "   ✅ Utilisez la commande avec --no-email pour tester sans spam\n";
    echo "   ✅ Ou configurez MAIL_MAILER=log dans .env\n";
}

if (env('APP_ENV') === 'production') {
    echo "   🚀 Environnement PRODUCTION détecté\n";
    echo "   ⚠️  Assurez-vous que MAIL_HOST et MAIL_PASSWORD sont corrects\n";
    echo "   ✅ Les emails de test seront automatiquement exclus\n";
}

echo "\n🚀 Commands recommandées:\n";
echo "   - Test sans emails: php artisan orders:safe-update-status --no-email\n";
echo "   - Test avec emails: php artisan orders:safe-update-status --dry-run\n";
echo "   - Production: php artisan orders:safe-update-status\n";
echo "   - Ancienne commande: php artisan orders:update-status\n\n";

echo "✅ Configuration terminée!\n";
