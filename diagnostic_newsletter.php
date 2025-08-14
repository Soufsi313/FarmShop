<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Newsletter;
use App\Models\User;
use App\Models\NewsletterSend;
use App\Mail\NewsletterMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;

echo "🔍 DIAGNOSTIC SYSTÈME NEWSLETTER\n";
echo "================================\n\n";

// 1. Vérifier la configuration email
echo "1. 📧 CONFIGURATION EMAIL\n";
echo "   Mailer par défaut: " . config('mail.default') . "\n";
echo "   Host SMTP: " . config('mail.mailers.smtp.host') . "\n";
echo "   Port SMTP: " . config('mail.mailers.smtp.port') . "\n";
echo "   Encryption: " . config('mail.mailers.smtp.encryption') . "\n";
echo "   Username: " . (config('mail.mailers.smtp.username') ? '***configuré***' : 'NON CONFIGURÉ') . "\n";
echo "   Password: " . (config('mail.mailers.smtp.password') ? '***configuré***' : 'NON CONFIGURÉ') . "\n";
echo "   From Address: " . config('mail.from.address') . "\n";
echo "   From Name: " . config('mail.from.name') . "\n\n";

// 2. Vérifier la newsletter
echo "2. 📰 NEWSLETTER DE BIENVENUE\n";
$newsletter = Newsletter::orderBy('id', 'desc')->first();
if ($newsletter) {
    echo "   ID: {$newsletter->id}\n";
    echo "   Titre: {$newsletter->title}\n";
    echo "   Status: {$newsletter->status}\n";
    echo "   Créé par: {$newsletter->created_by}\n";
    echo "   Créé le: {$newsletter->created_at}\n";
    echo "   Peut être envoyée: " . ($newsletter->canBeSent() ? 'OUI' : 'NON') . "\n\n";
} else {
    echo "   ❌ Aucune newsletter trouvée\n\n";
}

// 3. Vérifier les abonnés
echo "3. 👥 ABONNÉS NEWSLETTER\n";
$subscribers = User::where('newsletter_subscribed', true)->get();
echo "   Nombre total d'abonnés: " . $subscribers->count() . "\n";

// Vérifier si l'utilisateur admin est abonné
$admin = User::where('role', 'admin')->first();
if ($admin) {
    echo "   Admin ({$admin->email}) abonné: " . ($admin->newsletter_subscribed ? 'OUI' : 'NON') . "\n";
} else {
    echo "   ❌ Aucun admin trouvé\n";
}

// Afficher quelques abonnés
echo "   Premiers abonnés:\n";
foreach ($subscribers->take(5) as $sub) {
    echo "     - {$sub->email} (ID: {$sub->id})\n";
}
echo "\n";

// 4. Vérifier les envois précédents
echo "4. 📬 HISTORIQUE D'ENVOIS\n";
$sends = NewsletterSend::orderBy('created_at', 'desc')->take(10)->get();
echo "   Nombre d'envois enregistrés: " . NewsletterSend::count() . "\n";
if ($sends->count() > 0) {
    echo "   Derniers envois:\n";
    foreach ($sends as $send) {
        echo "     - Newsletter {$send->newsletter_id} → {$send->email} ({$send->status}) le {$send->created_at}\n";
    }
} else {
    echo "   ⚠️  Aucun envoi enregistré\n";
}
echo "\n";

// 5. Vérifier la queue
echo "5. ⚙️  SYSTÈME DE QUEUE\n";
echo "   Driver de queue: " . config('queue.default') . "\n";

// Compter les jobs en attente
$pendingJobs = DB::table('jobs')->count();
echo "   Jobs en attente: {$pendingJobs}\n";

if ($pendingJobs > 0) {
    echo "   Jobs en queue:\n";
    $jobs = DB::table('jobs')->orderBy('created_at', 'desc')->take(5)->get();
    foreach ($jobs as $job) {
        $payload = json_decode($job->payload, true);
        $jobName = $payload['displayName'] ?? 'Job inconnu';
        echo "     - {$jobName} (créé: " . date('Y-m-d H:i:s', $job->created_at) . ")\n";
    }
}

// Vérifier les jobs échoués
$failedJobs = DB::table('failed_jobs')->count();
echo "   Jobs échoués: {$failedJobs}\n";
if ($failedJobs > 0) {
    echo "   ⚠️  Des jobs ont échoué - vérifiez avec 'php artisan queue:failed'\n";
}
echo "\n";

// 6. Test d'envoi
echo "6. 🧪 TEST D'ENVOI\n";
if ($newsletter && $admin) {
    try {
        echo "   Test d'envoi à l'admin ({$admin->email})...\n";
        
        // Créer un send de test
        $testSend = NewsletterSend::create([
            'newsletter_id' => $newsletter->id,
            'user_id' => $admin->id,
            'email' => $admin->email,
            'status' => 'pending',
            'tracking_token' => \Illuminate\Support\Str::uuid(),
            'unsubscribe_token' => \Illuminate\Support\Str::uuid(),
        ]);

        // Générer les URLs
        $testSend->tracking_url = route('newsletter.track', ['token' => $testSend->tracking_token]);
        $testSend->unsubscribe_url = route('newsletter.unsubscribe.token', ['token' => $testSend->unsubscribe_token]);
        $testSend->save();

        // Test d'envoi synchrone (direct)
        echo "   Tentative d'envoi synchrone...\n";
        Mail::to($admin->email)->send(new NewsletterMail($newsletter, $admin, $testSend));
        
        echo "   ✅ Email de test envoyé avec succès !\n";
        echo "   📧 Vérifiez votre boîte mail: {$admin->email}\n";
        
        // Marquer comme envoyé
        $testSend->update(['status' => 'sent', 'sent_at' => now()]);
        
    } catch (\Exception $e) {
        echo "   ❌ Erreur lors du test d'envoi: {$e->getMessage()}\n";
        echo "   📁 Fichier: {$e->getFile()}:{$e->getLine()}\n";
    }
} else {
    echo "   ⚠️  Impossible de tester - newsletter ou admin manquant\n";
}
echo "\n";

// 7. Conseils de diagnostic
echo "7. 💡 CONSEILS DE DIAGNOSTIC\n";
echo "   Pour résoudre les problèmes d'envoi:\n";
echo "   \n";
echo "   🔧 Configuration email:\n";
echo "   - Vérifiez votre fichier .env pour MAIL_MAILER, MAIL_HOST, etc.\n";
echo "   - Si vous utilisez Gmail, activez les mots de passe d'application\n";
echo "   - Testez avec 'php artisan tinker' puis Mail::raw('Test', function(\$m) { \$m->to('your@email.com')->subject('Test'); });\n";
echo "   \n";
echo "   ⚙️  Queue worker:\n";
echo "   - Démarrez le worker: php artisan queue:work\n";
echo "   - Vérifiez les logs: storage/logs/laravel.log\n";
echo "   - Nettoyez les jobs échoués: php artisan queue:flush\n";
echo "   \n";
echo "   📰 Newsletter:\n";
echo "   - Status doit être 'draft' pour pouvoir être envoyée\n";
echo "   - Assurez-vous d'être abonné à la newsletter\n";
echo "   - Vérifiez que le contenu n'est pas vide\n";
echo "\n";

echo "=== DIAGNOSTIC TERMINÉ ===\n";
