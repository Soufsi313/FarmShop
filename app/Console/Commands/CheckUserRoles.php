<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CheckUserRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:user-roles {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifier les rôles et permissions d\'un utilisateur';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info("Vérification des rôles pour : {$email}");
        $this->info(str_repeat('=', 50));
        
        // Trouver l'utilisateur
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error('❌ Utilisateur non trouvé !');
            return 1;
        }
        
        $this->info("✅ Utilisateur trouvé : {$user->name}");
        $this->info("📧 Email : {$user->email}");
        $this->info("👤 Username : {$user->username}");
        $this->info("🆔 ID : {$user->id}");
        
        // Vérifier les rôles
        $this->info("\n🎭 RÔLES ASSIGNÉS :");
        if ($user->roles->count() > 0) {
            foreach ($user->roles as $role) {
                $this->info("   ✅ {$role->name}");
            }
        } else {
            $this->warn("   ⚠️  Aucun rôle assigné");
        }
        
        // Vérifier les permissions
        $this->info("\n🔑 PERMISSIONS :");
        $permissions = $user->getAllPermissions();
        if ($permissions->count() > 0) {
            foreach ($permissions as $permission) {
                $this->info("   ✅ {$permission->name}");
            }
        } else {
            $this->warn("   ⚠️  Aucune permission");
        }
        
        // Afficher tous les rôles disponibles
        $this->info("\n📋 RÔLES DISPONIBLES DANS LE SYSTÈME :");
        Role::all()->each(function($role) {
            $this->info("   - {$role->name}");
        });
        
        // Vérifier spécifiquement le rôle admin
        $this->info("\n🔍 VÉRIFICATION ADMIN :");
        if ($user->hasRole('admin')) {
            $this->info("   ✅ Utilisateur a le rôle ADMIN");
        } else {
            $this->warn("   ❌ Utilisateur N'A PAS le rôle ADMIN");
        }
        
        return 0;
    }
}
