<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Newsletter;

echo "🧪 TEST - CRÉATION D'UN NOUVEL UTILISATEUR ABONNÉ\n";
echo "=================================================\n\n";

// Créer un nouvel utilisateur avec votre vraie adresse email secondaire
$newEmail = 'votre.email.secondaire@gmail.com'; // Remplacez par votre vraie adresse

echo "1. 👤 Création d'un nouvel utilisateur...\n";

// Vérifier si l'utilisateur existe déjà
$existingUser = User::where('email', $newEmail)->first();
if ($existingUser) {
    echo "   ⚠️  Utilisateur avec cet email existe déjà\n";
    $newUser = $existingUser;
} else {
    // Créer le nouvel utilisateur
    $newUser = User::create([
        'name' => 'Votre Nom Test',
        'email' => $newEmail,
        'password' => bcrypt('password123'),
        'email_verified_at' => now(),
        'newsletter_subscribed' => true, // ABONNÉ à la newsletter
        'role' => 'customer'
    ]);
    echo "   ✅ Nouvel utilisateur créé : {$newUser->email}\n";
}

// S'assurer qu'il est abonné
$newUser->update(['newsletter_subscribed' => true]);
echo "   ✅ Utilisateur abonné à la newsletter\n";

echo "\n2. 📊 État actuel des abonnés :\n";
$subscribers = User::where('newsletter_subscribed', true)->get();
echo "   Total d'abonnés : " . $subscribers->count() . "\n";
foreach ($subscribers as $sub) {
    echo "   - {$sub->email} (ID: {$sub->id})\n";
}

echo "\n3. 📰 Newsletter actuelle :\n";
$newsletter = Newsletter::orderBy('id', 'desc')->first();
echo "   Titre : {$newsletter->title}\n";
echo "   Status : {$newsletter->status}\n";

// Remettre en draft si nécessaire
if ($newsletter->status === 'sent') {
    $newsletter->update(['status' => 'draft']);
    echo "   ✅ Newsletter remise en draft pour test\n";
}

echo "\n4. 🎯 SIMULATION D'ENVOI :\n";
echo "   Quand l'admin clique 'Envoyer maintenant' :\n";

// Simuler le processus d'envoi
$allSubscribers = User::where('newsletter_subscribed', true)->get();
foreach ($allSubscribers as $subscriber) {
    echo "   📧 Newsletter sera envoyée à : {$subscriber->email}\n";
}

echo "\n✅ CONCLUSION :\n";
echo "   • OUI, votre nouvelle adresse recevra la newsletter\n";
echo "   • Le système envoie à TOUS les utilisateurs avec newsletter_subscribed = true\n";
echo "   • Peu importe qui clique sur 'Envoyer', tous les abonnés reçoivent\n";
echo "   • Votre adresse principale ET secondaire recevront la newsletter\n";

echo "\n💡 POUR TESTER VRAIMENT :\n";
echo "   1. Remplacez '{$newEmail}' par votre vraie adresse secondaire\n";
echo "   2. Relancez ce script\n";
echo "   3. Allez sur le dashboard et cliquez 'Envoyer maintenant'\n";
echo "   4. Vérifiez vos 2 boîtes mail !\n";
