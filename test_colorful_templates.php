<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use App\Models\OrderLocation;
use App\Models\OrderLocationItem;
use App\Mail\RentalStartedMail;
use App\Mail\RentalEndReminderMail;
use Illuminate\Support\Facades\Mail;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧹 Nettoyage du cache Laravel...\n";

// Clear all caches
try {
    Artisan::call('cache:clear');
    echo "✅ Cache application vidé\n";
    
    Artisan::call('view:clear');
    echo "✅ Cache des vues vidé\n";
    
    Artisan::call('config:clear');
    echo "✅ Cache de configuration vidé\n";
    
    Artisan::call('route:clear');
    echo "✅ Cache des routes vidé\n";
    
} catch (Exception $e) {
    echo "❌ Erreur lors du nettoyage du cache: " . $e->getMessage() . "\n";
}

echo "\n📧 Test des nouveaux templates d'email...\n";

try {
    // Get first user and order
    $user = User::first();
    $orderLocation = OrderLocation::with('items.product')->first();
    
    if (!$user || !$orderLocation) {
        echo "❌ Aucun utilisateur ou commande trouvé pour le test\n";
        exit;
    }
    
    $items = $orderLocation->items;
    
    echo "👤 Utilisateur: {$user->name} ({$user->email})\n";
    echo "📦 Commande: {$orderLocation->order_number}\n";
    echo "📅 Période: {$orderLocation->start_date->format('d/m/Y')} → {$orderLocation->end_date->format('d/m/Y')}\n";
    
    // Test 1: Email de démarrage de location
    echo "\n🚀 Test 1: Email de démarrage de location\n";
    
    $rentalStartedMail = new RentalStartedMail($orderLocation);
    
    // Save the email content to a file for inspection
    $startedEmailContent = $rentalStartedMail->render();
    file_put_contents('test_rental_started_email.html', $startedEmailContent);
    echo "✅ Template de démarrage généré et sauvé dans test_rental_started_email.html\n";
    
    // Test 2: Email de rappel de fin
    echo "\n⏰ Test 2: Email de rappel de fin de location\n";
    
    $reminderMail = new RentalEndReminderMail($orderLocation);
    
    // Save the email content to a file for inspection
    $reminderEmailContent = $reminderMail->render();
    file_put_contents('test_rental_reminder_email.html', $reminderEmailContent);
    echo "✅ Template de rappel généré et sauvé dans test_rental_reminder_email.html\n";
    
    // Try to send actual emails
    echo "\n📬 Envoi des emails de test...\n";
    
    try {
        Mail::to($user->email)->send($rentalStartedMail);
        echo "✅ Email de démarrage envoyé à {$user->email}\n";
        
        Mail::to($user->email)->send($reminderMail);
        echo "✅ Email de rappel envoyé à {$user->email}\n";
        
    } catch (Exception $e) {
        echo "⚠️ Erreur lors de l'envoi des emails: " . $e->getMessage() . "\n";
        echo "💡 Les templates ont été générés et sauvés dans des fichiers HTML pour inspection\n";
    }
    
    echo "\n🎨 Caractéristiques des nouveaux templates:\n";
    echo "  ✨ Design coloré avec CSS intégré (plus de dépendance Tailwind CDN)\n";
    echo "  🎯 Headers avec gradients dynamiques selon l'urgence\n";
    echo "  📋 Encadrement coloré pour le récapitulatif de location\n";
    echo "  🔧 Icônes et sections bien structurées\n";
    echo "  📱 Design responsive pour mobile\n";
    echo "  ⏰ Alertes adaptatives selon le temps restant\n";
    
    echo "\n🔍 Vous pouvez maintenant:\n";
    echo "  1. Ouvrir test_rental_started_email.html dans votre navigateur\n";
    echo "  2. Ouvrir test_rental_reminder_email.html dans votre navigateur\n";
    echo "  3. Vérifier vos emails pour voir les nouveaux designs\n";
    
} catch (Exception $e) {
    echo "❌ Erreur lors du test: " . $e->getMessage() . "\n";
    echo "📋 Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n🎉 Test terminé !\n";
