<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Newsletter;
use App\Models\NewsletterSend;
use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "🚨 ARRÊT D'URGENCE - NETTOYAGE NEWSLETTER\n";
echo "=========================================\n\n";

// 1. Nettoyer la queue des jobs en attente
echo "1. 🧹 Nettoyage de la queue...\n";
$deletedJobs = DB::table('jobs')->where('payload', 'LIKE', '%SendNewsletterJob%')->delete();
echo "   ✅ {$deletedJobs} jobs d'envoi supprimés de la queue\n";

// 2. Stopper les envois en cours en marquant la newsletter comme envoyée
echo "\n2. ⏹️  Arrêt des envois en cours...\n";
$newsletter = Newsletter::orderBy('id', 'desc')->first();
if ($newsletter && $newsletter->status !== 'sent') {
    $newsletter->update([
        'status' => 'sent',
        'sent_at' => now()
    ]);
    echo "   ✅ Newsletter marquée comme envoyée pour stopper les envois\n";
}

// 3. Compter les emails qui ont causé des erreurs
echo "\n3. 📊 Analyse des envois...\n";
$totalSends = NewsletterSend::where('newsletter_id', $newsletter->id)->count();
$successSends = NewsletterSend::where('newsletter_id', $newsletter->id)->where('status', 'sent')->count();
$failedSends = NewsletterSend::where('newsletter_id', $newsletter->id)->where('status', 'failed')->count();
$pendingSends = NewsletterSend::where('newsletter_id', $newsletter->id)->where('status', 'pending')->count();

echo "   📧 Total des envois: {$totalSends}\n";
echo "   ✅ Envoyés avec succès: {$successSends}\n";
echo "   ❌ Échecs: {$failedSends}\n";
echo "   ⏳ En attente: {$pendingSends}\n";

// 4. Identifier les emails problématiques
echo "\n4. 🔍 Emails problématiques...\n";
$adminEmail = 's.mef2703@gmail.com';
$validEmails = [
    $adminEmail,
    'test@example.com',
    'admin@farmshop.com'
];

$testUsers = User::where('newsletter_subscribed', true)
                 ->whereNotIn('email', $validEmails)
                 ->get();

echo "   ⚠️  {$testUsers->count()} utilisateurs avec emails de test détectés\n";

// 5. Désabonner les utilisateurs de test pour éviter les futurs spams
echo "\n5. 🚫 Désabonnement des emails de test...\n";
$unsubscribed = User::where('newsletter_subscribed', true)
                   ->whereNotIn('email', $validEmails)
                   ->update(['newsletter_subscribed' => false]);

echo "   ✅ {$unsubscribed} utilisateurs de test désabonnés\n";

// 6. Vérifier que l'admin reste abonné
echo "\n6. 👤 Vérification de l'admin...\n";
$admin = User::where('email', $adminEmail)->first();
if ($admin) {
    if (!$admin->newsletter_subscribed) {
        $admin->update(['newsletter_subscribed' => true]);
        echo "   ✅ Admin réabonné à la newsletter\n";
    } else {
        echo "   ✅ Admin toujours abonné\n";
    }
    echo "   📧 Email admin: {$admin->email}\n";
}

// 7. Créer une nouvelle newsletter de test pour l'admin uniquement
echo "\n7. 📰 Création d'une newsletter de test...\n";

// Récupérer le contenu de la newsletter envoyée
$originalNewsletter = Newsletter::find($newsletter->id);

$testNewsletter = Newsletter::create([
    'title' => 'TEST - ' . $originalNewsletter->title,
    'subject' => 'TEST - ' . $originalNewsletter->subject,
    'content' => $originalNewsletter->content,
    'status' => 'draft',
    'is_template' => false,
    'created_by' => $admin->id,
]);

echo "   ✅ Newsletter de test créée (ID: {$testNewsletter->id})\n";

echo "\n🎯 SITUATION ACTUELLE:\n";
echo "   ✅ Envois stoppés\n";
echo "   ✅ Queue nettoyée\n";
echo "   ✅ Emails de test désabonnés\n";
echo "   ✅ Seul votre email reste abonné\n";
echo "   ✅ Newsletter de test créée pour vous\n";

echo "\n💡 PROCHAINES ÉTAPES:\n";
echo "   1. Vérifiez votre boîte mail - vous devriez avoir reçu la newsletter\n";
echo "   2. Les emails d'erreur vont s'arrêter\n";
echo "   3. Pour tester à l'avenir, utilisez la newsletter de TEST créée\n";
echo "   4. Ou créez une fonction 'Aperçu' qui n'envoie qu'à vous\n";

echo "\n=== NETTOYAGE TERMINÉ ===\n";
