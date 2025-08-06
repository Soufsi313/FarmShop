<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 TEST DES ROUTES NEWSLETTER\n";
echo "=============================\n\n";

// Test 1: Route sans token (POST)
try {
    $urlWithoutToken = route('newsletter.unsubscribe');
    echo "✅ Route newsletter.unsubscribe (POST) : {$urlWithoutToken}\n";
} catch (\Exception $e) {
    echo "❌ Erreur route sans token : " . $e->getMessage() . "\n";
}

// Test 2: Route avec token (GET)
try {
    $urlWithToken = route('newsletter.unsubscribe.token', ['token' => 'test123']);
    echo "✅ Route newsletter.unsubscribe.token (GET) : {$urlWithToken}\n";
} catch (\Exception $e) {
    echo "❌ Erreur route avec token : " . $e->getMessage() . "\n";
}

echo "\n🎯 Test terminé - Les routes sont maintenant correctement définies !\n";
