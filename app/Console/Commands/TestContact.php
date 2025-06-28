<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Contact;

class TestContact extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:contact';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test contact creation system';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Test de création d\'un contact...');

        try {
            $contact = Contact::createFromRequest([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'phone' => '0123456789',
                'subject' => 'Test de contact',
                'reason' => 'information_general',
                'message' => 'Ceci est un message de test pour vérifier le système de contact.'
            ]);

            $this->info("✅ Contact créé avec succès ! ID: {$contact->id}");
            $this->info("   - Nom: {$contact->name}");
            $this->info("   - Email: {$contact->email}");
            $this->info("   - Statut: '{$contact->status}'");
            $this->info("   - Raison: {$contact->reason}");
            $this->info("   - Urgent: " . ($contact->is_urgent ? 'Oui' : 'Non'));
            
            // Test de changement de statut
            $contact->markInProgress(1);
            $this->info("   - Nouveau statut après markInProgress: '{$contact->status}'");

            return 0;
        } catch (\Exception $e) {
            $this->error("❌ Erreur lors de la création du contact: " . $e->getMessage());
            return 1;
        }
    }
}
