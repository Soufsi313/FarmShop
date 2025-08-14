<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Newsletter;
use App\Models\NewsletterSend;
use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "🛠️  PRÉPARATION SYSTÈME NEWSLETTER PERSONNEL\n";
echo "============================================\n\n";

// 1. Arrêter les emails en cours
echo "1. 🚫 Arrêt des envois en cours...\n";
$newsletter = Newsletter::orderBy('id', 'desc')->first();
if ($newsletter && $newsletter->status !== 'sent') {
    $newsletter->update(['status' => 'draft']);
    echo "   ✅ Newsletter remise en draft\n";
}

// 2. Nettoyer la queue
echo "\n2. 🧹 Nettoyage de la queue...\n";
$deletedJobs = DB::table('jobs')->where('payload', 'LIKE', '%Newsletter%')->delete();
echo "   ✅ {$deletedJobs} jobs de newsletter supprimés\n";

// 3. Garder seulement votre email comme abonné
echo "\n3. 👤 Configuration des abonnés...\n";
$adminEmail = 's.mef2703@gmail.com';

// Désabonner tous les autres
$unsubscribed = User::where('newsletter_subscribed', true)
                   ->where('email', '!=', $adminEmail)
                   ->update(['newsletter_subscribed' => false]);
echo "   📧 {$unsubscribed} autres utilisateurs désabonnés\n";

// S'assurer que vous restez abonné
$admin = User::where('email', $adminEmail)->first();
if ($admin) {
    $admin->update(['newsletter_subscribed' => true]);
    echo "   ✅ Votre email ({$adminEmail}) reste abonné\n";
}

// 4. Nettoyer les anciens envois
echo "\n4. 🗑️  Nettoyage des anciens envois...\n";
$deletedSends = NewsletterSend::truncate();
echo "   ✅ Anciens enregistrements d'envoi supprimés\n";

echo "\n✨ SYSTÈME CONFIGURÉ !\n";
echo "==============================\n";
echo "🎯 Maintenant dans votre dashboard :\n";
echo "   • 'Envoyer' → Envoie à TOUS les abonnés (actuellement vous seul)\n";
echo "   • 'Envoyer à moi' → Nouvelle option qui n'envoie QU'À VOUS\n";
echo "   • 'Test' → Envoie à une adresse spécifique\n\n";

echo "📧 Abonnés actuels à la newsletter:\n";
$subscribers = User::where('newsletter_subscribed', true)->get();
foreach ($subscribers as $sub) {
    echo "   - {$sub->email}\n";
}

echo "\n🚀 Prêt à utiliser ! Plus de spam vers des emails invalides.\n";
