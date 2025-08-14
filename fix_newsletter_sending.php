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

echo "🔧 CORRECTION DU SYSTÈME NEWSLETTER\n";
echo "===================================\n\n";

// 1. Remettre la newsletter en draft
echo "1. 📰 Correction du status de la newsletter...\n";
$newsletter = Newsletter::orderBy('id', 'desc')->first();
if ($newsletter && $newsletter->status === 'scheduled') {
    $newsletter->update([
        'status' => 'draft',
        'scheduled_at' => null
    ]);
    echo "   ✅ Newsletter remise en status 'draft'\n";
} else {
    echo "   ⚠️  Newsletter déjà en bon status ou non trouvée\n";
}

// 2. Nettoyer les jobs en queue obsolètes
echo "\n2. 🧹 Nettoyage de la queue...\n";
$jobsDeleted = DB::table('jobs')->where('created_at', '<', now()->subHours(2))->delete();
echo "   ✅ {$jobsDeleted} anciens jobs supprimés\n";

// 3. Créer une méthode d'envoi manuel (sans queue)
echo "\n3. 📧 Envoi manuel de la newsletter...\n";

if ($newsletter) {
    try {
        // Récupérer les abonnés
        $subscribers = User::where('newsletter_subscribed', true)->get();
        echo "   Envoi à {$subscribers->count()} abonnés...\n";
        
        $successCount = 0;
        $failureCount = 0;
        
        foreach ($subscribers as $subscriber) {
            try {
                // Créer un enregistrement de suivi
                $send = NewsletterSend::create([
                    'newsletter_id' => $newsletter->id,
                    'user_id' => $subscriber->id,
                    'email' => $subscriber->email,
                    'status' => 'pending',
                    'tracking_token' => \Illuminate\Support\Str::uuid(),
                    'unsubscribe_token' => \Illuminate\Support\Str::uuid(),
                ]);

                // Générer les URLs de suivi
                $send->tracking_url = route('newsletter.track', ['token' => $send->tracking_token]);
                $send->unsubscribe_url = route('newsletter.unsubscribe.token', ['token' => $send->unsubscribe_token]);
                $send->save();

                // Envoyer l'email directement (sans queue)
                Mail::to($subscriber->email)->send(new NewsletterMail($newsletter, $subscriber, $send));

                // Marquer comme envoyé
                $send->update([
                    'status' => 'sent',
                    'sent_at' => now()
                ]);

                $successCount++;
                echo "     ✅ Envoyé à {$subscriber->email}\n";
                
                // Pause pour éviter de spammer
                usleep(500000); // 0.5 seconde
                
            } catch (\Exception $e) {
                $failureCount++;
                echo "     ❌ Échec pour {$subscriber->email}: {$e->getMessage()}\n";
                
                // Marquer comme échoué
                if (isset($send)) {
                    $send->update([
                        'status' => 'failed',
                        'error_message' => $e->getMessage(),
                        'sent_at' => now()
                    ]);
                }
            }
        }

        // Mettre à jour le statut de la newsletter
        $newsletter->update([
            'status' => 'sent',
            'sent_at' => now(),
            'recipients_count' => $successCount + $failureCount,
            'sent_count' => $successCount,
            'failed_count' => $failureCount
        ]);

        echo "\n   📊 RÉSULTATS:\n";
        echo "     ✅ Succès: {$successCount}\n";
        echo "     ❌ Échecs: {$failureCount}\n";
        echo "     📧 Total: " . ($successCount + $failureCount) . "\n";
        
        if ($successCount > 0) {
            echo "\n   🎉 Newsletter envoyée avec succès !\n";
            echo "   📬 Vérifiez votre boîte mail pour la recevoir\n";
        }
        
    } catch (\Exception $e) {
        echo "   ❌ Erreur générale: {$e->getMessage()}\n";
    }
} else {
    echo "   ❌ Aucune newsletter à envoyer\n";
}

echo "\n=== CORRECTION TERMINÉE ===\n";
echo "\n💡 POUR L'AVENIR:\n";
echo "   - Pour démarrer le queue worker: php artisan queue:work\n";
echo "   - Pour surveiller la queue: php artisan queue:monitor\n";
echo "   - Les prochaines newsletters passeront par la queue automatiquement\n";
