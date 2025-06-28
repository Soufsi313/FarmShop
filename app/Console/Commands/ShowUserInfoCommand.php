<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ShowUserInfoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:info {email : The email of the user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show detailed user information';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("❌ Utilisateur avec l'email '{$email}' non trouvé.");
            return 1;
        }
        
        $this->info("👤 INFORMATIONS UTILISATEUR");
        $this->info("================================");
        $this->info("📧 Email: " . $user->email);
        $this->info("👤 Nom: " . $user->name);
        $this->info("🆔 Username: " . $user->username);
        $this->info("📅 Créé le: " . $user->created_at->format('d/m/Y H:i:s'));
        $this->info("✅ Email vérifié: " . ($user->email_verified_at ? 'Oui (' . $user->email_verified_at->format('d/m/Y H:i:s') . ')' : 'Non'));
        $this->info("📰 Newsletter: " . ($user->is_newsletter_subscribed ? 'Abonné' : 'Non abonné'));
        
        $this->info("\n🔑 RÔLES ET PERMISSIONS");
        $this->info("================================");
        $roles = $user->getRoleNames();
        if ($roles->count() > 0) {
            $this->info("🏷️  Rôles: " . $roles->implode(', '));
        } else {
            $this->warn("⚠️  Aucun rôle assigné");
        }
        
        $permissions = $user->getAllPermissions();
        if ($permissions->count() > 0) {
            $this->info("🔐 Permissions:");
            foreach ($permissions as $permission) {
                $this->info("   - " . $permission->name);
            }
        } else {
            $this->warn("⚠️  Aucune permission");
        }
        
        $this->info("\n🚀 STATUT");
        $this->info("================================");
        $this->info("🔒 Admin: " . ($user->hasRole('admin') ? 'OUI' : 'NON'));
        $this->info("👥 Utilisateur: " . ($user->hasRole('user') ? 'OUI' : 'NON'));
        $this->info("🛡️  Peut accéder panel admin: " . ($user->can('access admin panel') ? 'OUI' : 'NON'));
        
        return 0;
    }
}
