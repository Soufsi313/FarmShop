<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\OrderLocation;
use App\Mail\RentalOrderConfirmed;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

echo "=== TEST MANUEL EMAIL CONFIRMATION ===\n\n";

// Prendre la dernière commande confirmée
$rental = OrderLocation::where('status', 'confirmed')
    ->orWhere('status', 'active')
    ->orderBy('created_at', 'desc')
    ->first();

if (!$rental) {
    echo "❌ Aucune commande confirmée trouvée\n";
    exit;
}

echo "Commande trouvée: {$rental->order_number}\n";
echo "Statut: {$rental->status}\n";
echo "Email client: {$rental->user->email}\n";
echo "Confirmée le: " . ($rental->confirmed_at ?? 'Non défini') . "\n\n";

// Nettoyer le cache pour cet email
$cacheKey = "email_confirmed_{$rental->id}";
Cache::forget($cacheKey);
echo "✅ Cache nettoyé pour cette commande\n\n";

// Tenter d'envoyer l'email manuellement
echo "🧪 TEST 1: Envoi synchrone direct...\n";
try {
    Mail::to($rental->user->email)->send(new RentalOrderConfirmed($rental));
    echo "✅ Email envoyé avec succès (synchrone)\n";
} catch (\Exception $e) {
    echo "❌ Erreur envoi synchrone: " . $e->getMessage() . "\n";
}

echo "\n🧪 TEST 2: Envoi asynchrone via queue...\n";
try {
    Mail::to($rental->user->email)->queue(new RentalOrderConfirmed($rental));
    echo "✅ Email mis en queue avec succès (asynchrone)\n";
    echo "   → Vérifiez que le worker de queue tourne: php artisan queue:work\n";
} catch (\Exception $e) {
    echo "❌ Erreur mise en queue: " . $e->getMessage() . "\n";
}

echo "\n🧪 TEST 3: Vérification du template email...\n";
try {
    $mailable = new RentalOrderConfirmed($rental);
    $envelope = $mailable->envelope();
    echo "✅ Template valide\n";
    echo "   Sujet: " . $envelope->subject . "\n";
    echo "   De: " . $envelope->from . "\n";
} catch (\Exception $e) {
    echo "❌ Erreur template: " . $e->getMessage() . "\n";
}

echo "\n=== RÉSULTATS ===\n";
echo "Si le TEST 1 fonctionne → Problème avec le listener asynchrone\n";
echo "Si le TEST 2 fonctionne → Problème avec le worker de queue\n";
echo "Si le TEST 3 échoue → Problème avec le template email\n";
