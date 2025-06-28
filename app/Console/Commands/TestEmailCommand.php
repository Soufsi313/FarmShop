<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class TestEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email functionality';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Testing email functionality...');
        
        try {
            // Créer un utilisateur de test
            $user = User::firstOrCreate(
                ['email' => 'test.email@farmshop.com'],
                [
                    'name' => 'Test Email User',
                    'username' => 'testemailuser',
                    'password' => Hash::make('password123'),
                ]
            );
            
            $this->info('✅ User created/found: ' . $user->email);
            
            // Envoyer l'email de vérification
            $user->notify(new VerifyEmailNotification());
            
            $this->info('✅ Email de vérification envoyé avec succès !');
            $this->info('📧 Configuration email utilisée:');
            $this->info('   - Host: ' . config('mail.mailers.smtp.host'));
            $this->info('   - Port: ' . config('mail.mailers.smtp.port'));
            $this->info('   - From: ' . config('mail.from.address'));
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors de l\'envoi d\'email: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
