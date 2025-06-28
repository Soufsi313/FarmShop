<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CheckAuthStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:auth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifier le statut d\'authentification';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Vérification du statut d\'authentification...');
        $this->info(str_repeat('=', 50));
        
        // Compter les utilisateurs
        $userCount = User::count();
        $this->info("👥 Total utilisateurs : {$userCount}");
        
        // Utilisateurs récents
        $recentUsers = User::orderBy('created_at', 'desc')->take(3)->get();
        $this->info("\n📋 Derniers utilisateurs créés :");
        foreach ($recentUsers as $user) {
            $verified = $user->email_verified_at ? '✅' : '❌';
            $this->info("   {$verified} {$user->name} ({$user->email}) - {$user->created_at->format('d/m/Y H:i')}");
        }
        
        // Vérifier votre utilisateur spécifiquement
        $yourUser = User::where('email', 's.mef2703@gmail.com')->first();
        if ($yourUser) {
            $this->info("\n🎯 VOTRE COMPTE :");
            $this->info("   ✅ Nom : {$yourUser->name}");
            $this->info("   ✅ Email : {$yourUser->email}");
            $this->info("   ✅ Username : {$yourUser->username}");
            $verified = $yourUser->email_verified_at ? 'Vérifié ✅' : 'Non vérifié ❌';
            $this->info("   📧 Email : {$verified}");
            $admin = $yourUser->hasRole('admin') ? 'Oui ✅' : 'Non ❌';
            $this->info("   🔑 Admin : {$admin}");
        }
        
        return 0;
    }
}
