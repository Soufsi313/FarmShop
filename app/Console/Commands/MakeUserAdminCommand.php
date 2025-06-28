<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class MakeUserAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:make-admin {email : The email of the user to make admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a user admin by email';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        // Trouver l'utilisateur
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("❌ Utilisateur avec l'email '{$email}' non trouvé.");
            return 1;
        }
        
        // Créer les rôles s'ils n'existent pas
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);
        
        // Créer les permissions de base
        $permissions = [
            'access admin panel',
            'manage users',
            'manage products',
            'manage orders',
            'manage categories',
            'manage blog',
            'view analytics',
        ];
        
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        
        // Assigner toutes les permissions au rôle admin
        $adminRole->syncPermissions($permissions);
        
        // Assigner le rôle admin à l'utilisateur
        $user->assignRole('admin');
        
        $this->info("✅ Utilisateur '{$user->name}' ({$user->email}) est maintenant administrateur !");
        $this->info("✅ Rôles assignés : " . $user->getRoleNames()->implode(', '));
        $this->info("✅ Permissions : " . $user->getAllPermissions()->pluck('name')->implode(', '));
        
        return 0;
    }
}
