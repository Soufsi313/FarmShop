<?php
require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 VÉRIFICATION ROUTE CONTACT\n";
echo "============================\n";

try {
    // Vérifier la route contact
    $contactUrl = route('contact');
    echo "✅ Route 'contact': {$contactUrl}\n";
    
    $contactConfirmationUrl = route('contact.confirmation');
    echo "✅ Route 'contact.confirmation': {$contactConfirmationUrl}\n";
    
    echo "\n✅ CORRECTION APPLIQUÉE:\n";
    echo "- account-deletion-requested.blade.php: route('contact.index') → route('contact')\n";
    echo "\n🎯 Le système de suppression de compte devrait maintenant fonctionner.\n";

} catch (\Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
