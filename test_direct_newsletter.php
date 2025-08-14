<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Newsletter;
use App\Models\User;

echo "🧪 TEST ENVOI DIRECT NEWSLETTER\n";
echo "===============================\n\n";

// Récupérer la newsletter
$newsletter = Newsletter::orderBy('id', 'desc')->first();
if (!$newsletter) {
    echo "❌ Aucune newsletter trouvée\n";
    exit;
}

echo "📰 Newsletter: {$newsletter->title}\n";
echo "📊 Status: {$newsletter->status}\n";

// Récupérer l'admin pour le test
$admin = User::where('role', 'admin')->first();
if (!$admin) {
    echo "❌ Aucun admin trouvé\n";
    exit;
}

echo "👤 Test sur l'admin: {$admin->email}\n";
echo "📧 Abonné newsletter: " . ($admin->newsletter_subscribed ? 'OUI' : 'NON') . "\n\n";

// S'assurer que l'admin est abonné
if (!$admin->newsletter_subscribed) {
    $admin->update(['newsletter_subscribed' => true]);
    echo "✅ Admin abonné à la newsletter\n";
}

echo "🚀 Simulation d'envoi direct...\n";

try {
    // Créer un enregistrement de suivi
    $send = \App\Models\NewsletterSend::create([
        'newsletter_id' => $newsletter->id,
        'user_id' => $admin->id,
        'email' => $admin->email,
        'status' => 'pending',
        'tracking_token' => \Illuminate\Support\Str::uuid(),
        'unsubscribe_token' => \Illuminate\Support\Str::uuid(),
    ]);

    // Générer les URLs
    $send->tracking_url = route('newsletter.track', ['token' => $send->tracking_token]);
    $send->unsubscribe_url = route('newsletter.unsubscribe.token', ['token' => $send->unsubscribe_token]);
    $send->save();

    echo "📋 Enregistrement de suivi créé (ID: {$send->id})\n";

    // Envoyer l'email directement
    \Mail::to($admin->email)->send(new \App\Mail\NewsletterMail($newsletter, $admin, $send));

    // Marquer comme envoyé
    $send->update([
        'status' => 'sent',
        'sent_at' => now()
    ]);

    echo "✅ Email envoyé avec succès !\n";
    echo "📧 Vérifiez votre boîte mail: {$admin->email}\n";
    echo "🔗 Token de suivi: {$send->tracking_token}\n";

} catch (\Exception $e) {
    echo "❌ Erreur lors de l'envoi: {$e->getMessage()}\n";
    echo "📁 Fichier: {$e->getFile()}:{$e->getLine()}\n";
}

echo "\n🎯 PROCHAINE ÉTAPE:\n";
echo "   Allez sur votre dashboard admin\n";
echo "   → Newsletters → Voir la newsletter\n";
echo "   → Cliquez sur 'Envoyer maintenant'\n";
echo "   → L'envoi sera immédiat (plus de queue) !\n";
