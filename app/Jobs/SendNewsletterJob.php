<?php

namespace App\Jobs;

use App\Mail\NewsletterMail;
use App\Models\Newsletter;
use App\Models\NewsletterSend;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SendNewsletterJob implements ShouldQueue
{
    use Queueable;

    public Newsletter $newsletter;

    /**
     * Create a new job instance.
     */
    public function __construct(Newsletter $newsletter)
    {
        $this->newsletter = $newsletter;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Vérifier que la newsletter n'a pas déjà été envoyée
        if ($this->newsletter->status === 'sent') {
            return;
        }

        // Récupérer tous les abonnés
        $subscribers = User::where('newsletter_subscribed', true)->get();

        $successCount = 0;
        $failureCount = 0;

        foreach ($subscribers as $subscriber) {
            try {
                // Créer un enregistrement de suivi
                $send = NewsletterSend::create([
                    'newsletter_id' => $this->newsletter->id,
                    'user_id' => $subscriber->id,
                    'email' => $subscriber->email,
                    'status' => 'pending',
                    'tracking_token' => Str::uuid(),
                    'unsubscribe_token' => Str::uuid(),
                ]);

                // Générer les URLs de suivi
                $send->tracking_url = route('newsletter.track', ['token' => $send->tracking_token]);
                $send->unsubscribe_url = route('newsletter.unsubscribe.token', ['token' => $send->unsubscribe_token]);
                $send->save();

                // Envoyer l'email
                Mail::to($subscriber->email)->send(new NewsletterMail($this->newsletter, $subscriber, $send));

                // Marquer comme envoyé
                $send->update([
                    'status' => 'sent',
                    'sent_at' => now()
                ]);

                $successCount++;

            } catch (\Exception $e) {
                // Marquer comme échoué
                if (isset($send)) {
                    $send->update([
                        'status' => 'failed',
                        'error_message' => $e->getMessage(),
                        'sent_at' => now()
                    ]);
                }

                $failureCount++;
                \Log::error('Erreur envoi newsletter', [
                    'newsletter_id' => $this->newsletter->id,
                    'user_id' => $subscriber->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Mettre à jour le statut de la newsletter
        $this->newsletter->update([
            'status' => 'sent',
            'sent_at' => now(),
            'recipients_count' => $successCount + $failureCount,
            'success_count' => $successCount,
            'failure_count' => $failureCount
        ]);
    }
}
