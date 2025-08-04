<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

// Créer un utilisateur test ou l'abonner s'il existe déjà
$email = 's.mef2703@gmail.com'; // Votre email depuis le .env
$name = 'Admin Test';

$user = User::where('email', $email)->first();

if (!$user) {
    // Créer un utilisateur si il n'existe pas
    $user = User::create([
        'name' => $name,
        'email' => $email,
        'password' => bcrypt('password123'),
        'newsletter_subscribed' => true,
        'newsletter_subscribed_at' => now(),
        'email_verified_at' => now()
    ]);
    echo "✅ Utilisateur créé et abonné : {$user->name} ({$user->email})\n";
} else {
    // Abonner l'utilisateur existant
    $user->update([
        'newsletter_subscribed' => true,
        'newsletter_subscribed_at' => now()
    ]);
    echo "✅ Utilisateur existant abonné : {$user->name} ({$user->email})\n";
}

echo "📧 Email configuré : {$user->email}\n";
echo "📊 Total abonnés newsletter : " . User::where('newsletter_subscribed', true)->count() . "\n";
echo "\n🎯 Vous pouvez maintenant tester l'envoi de newsletter !\n";
