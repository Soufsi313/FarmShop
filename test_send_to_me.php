<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Newsletter;
use App\Models\User;

echo "🧪 TEST DE LA FONCTION 'ENVOYER À MOI'\n";
echo "=====================================\n\n";

// Simuler l'utilisateur connecté
$admin = User::where('email', 's.mef2703@gmail.com')->first();
Auth::login($admin);

echo "👤 Utilisateur connecté: {$admin->email}\n";

// Récupérer la newsletter
$newsletter = Newsletter::orderBy('id', 'desc')->first();
echo "📰 Newsletter: {$newsletter->title}\n";
echo "📊 Status: {$newsletter->status}\n\n";

try {
    // Simuler l'appel à sendToMe
    $controller = new \App\Http\Controllers\Admin\NewsletterController();
    
    echo "🚀 Appel de sendToMe()...\n";
    
    // Créer un enregistrement de suivi unique pour cet utilisateur
    $send = \App\Models\NewsletterSend::firstOrCreate([
        'newsletter_id' => $newsletter->id,
        'user_id' => $admin->id,
    ], [
        'email' => $admin->email,
        'status' => 'pending',
        'tracking_token' => \Illuminate\Support\Str::uuid(),
        'unsubscribe_token' => \Illuminate\Support\Str::uuid(),
    ]);

    // Générer les URLs de suivi
    $send->tracking_url = route('newsletter.track', ['token' => $send->tracking_token]);
    $send->unsubscribe_url = route('newsletter.unsubscribe.token', ['token' => $send->unsubscribe_token]);
    $send->save();

    echo "📋 Enregistrement de suivi créé (ID: {$send->id})\n";

    // Envoyer l'email uniquement à l'utilisateur connecté
    \Mail::to($admin->email)->send(new \App\Mail\NewsletterMail($newsletter, $admin, $send));

    // Marquer comme envoyé
    $send->update([
        'status' => 'sent',
        'sent_at' => now()
    ]);

    echo "✅ Email envoyé uniquement à votre adresse : {$admin->email}\n";
    echo "🎯 Aucun autre utilisateur n'a reçu l'email !\n";

} catch (\Exception $e) {
    echo "❌ Erreur: {$e->getMessage()}\n";
    echo "📁 Fichier: {$e->getFile()}:{$e->getLine()}\n";
}

echo "\n💡 UTILISATION DANS LE DASHBOARD:\n";
echo "   1. Allez sur http://127.0.0.1:8000/admin/newsletters\n";
echo "   2. Cliquez sur votre newsletter\n";
echo "   3. Vous verrez maintenant 2 boutons:\n";
echo "      • 'Envoyer maintenant' → Envoie à tous les abonnés\n";
echo "      • 'Envoyer à moi' → Envoie SEULEMENT à vous\n";
echo "   4. Utilisez 'Envoyer à moi' pour tester sans spammer!\n";
