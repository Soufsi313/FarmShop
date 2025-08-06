<?php

namespace App\Console\Commands;

use App\Mail\NewsletterMail;
use App\Models\Newsletter;
use App\Models\NewsletterSend;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class TestNewsletterEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newsletter:test-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test newsletter email sending with RFC 2822 compliance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Trouver une newsletter et un utilisateur
        $newsletter = Newsletter::first();
        $user = User::where('newsletter_subscribed', true)->first();
        
        if (!$newsletter) {
            $this->error('Aucune newsletter trouvée');
            return 1;
        }
        
        if (!$user) {
            $this->error('Aucun utilisateur abonné trouvé');
            return 1;
        }
        
        // Trouver ou créer un send 
        $send = NewsletterSend::where('newsletter_id', $newsletter->id)
                              ->where('user_id', $user->id)
                              ->first();
        
        if (!$send) {
            $send = NewsletterSend::create([
                'newsletter_id' => $newsletter->id,
                'user_id' => $user->id,
                'email' => $user->email,
                'status' => 'pending',
                'tracking_token' => Str::uuid(),
                'unsubscribe_token' => Str::uuid(),
            ]);
        }
        
        // Générer les URLs si elles n'existent pas
        if (!$send->tracking_url) {
            $send->tracking_url = route('newsletter.track', ['token' => $send->tracking_token]);
            $send->unsubscribe_url = route('newsletter.unsubscribe.token', ['token' => $send->unsubscribe_token]);
            $send->save();
        }
        
        try {
            $this->info("Test d'envoi à: {$user->email} (Nom: {$user->name})");
            
            Mail::to($user->email)->send(new NewsletterMail($newsletter, $user, $send));
            
            $this->info('✅ Email envoyé avec succès !');
        } catch (\Exception $e) {
            $this->error("❌ Erreur d'envoi: " . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
