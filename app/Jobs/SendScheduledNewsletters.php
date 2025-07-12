<?php

namespace App\Jobs;

use App\Models\Newsletter;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendScheduledNewsletters implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Récupérer toutes les newsletters programmées qui doivent être envoyées
            $newsletters = Newsletter::readyToSend()->get();

            Log::info('Job SendScheduledNewsletters démarré', [
                'newsletters_count' => $newsletters->count()
            ]);

            foreach ($newsletters as $newsletter) {
                try {
                    Log::info('Envoi de la newsletter programmée', [
                        'newsletter_id' => $newsletter->id,
                        'title' => $newsletter->title,
                        'scheduled_at' => $newsletter->scheduled_at
                    ]);

                    $success = $newsletter->sendToSubscribers();

                    if ($success) {
                        Log::info('Newsletter envoyée avec succès', [
                            'newsletter_id' => $newsletter->id,
                            'recipients_count' => $newsletter->recipients_count
                        ]);
                    } else {
                        Log::warning('Échec envoi newsletter', [
                            'newsletter_id' => $newsletter->id,
                            'reason' => 'Aucun abonné trouvé'
                        ]);
                    }

                } catch (\Exception $e) {
                    Log::error('Erreur lors de l\'envoi d\'une newsletter programmée', [
                        'newsletter_id' => $newsletter->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }

            Log::info('Job SendScheduledNewsletters terminé');

        } catch (\Exception $e) {
            Log::error('Erreur critique dans SendScheduledNewsletters', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Job SendScheduledNewsletters a échoué', [
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
}
