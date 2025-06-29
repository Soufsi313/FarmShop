<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Création des permissions
        $permissions = [
            // Permissions générales
            'view products',
            'buy products',
            'rent products',
            'view blog',
            'comment blog',
            'contact admin',
            'subscribe newsletter',
            'unsubscribe newsletter',
            'export personal data',
            
            // Permissions CRUD pour admin/superuser
            'manage products',
            'manage blog',
            'manage categories',
            'manage users',
            'delete users',
            'view admin messages',
            'manage admin messages',
            'manage contacts',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Création des rôles
        $userRole = Role::create(['name' => User::ROLE_USER]);
        $adminRole = Role::create(['name' => User::ROLE_ADMIN]);

        // Attribution des permissions aux rôles
        
        // Utilisateur standard
        $userRole->givePermissionTo([
            'view products',
            'buy products',
            'rent products',
            'view blog',
            'comment blog',
            'contact admin',
            'subscribe newsletter',
            'unsubscribe newsletter',
            'export personal data',
        ]);

        // Admin (hérite des permissions user + toutes les permissions administratives)
        $adminRole->givePermissionTo([
            'view products',
            'buy products',
            'rent products',
            'view blog',
            'comment blog',
            'contact admin',
            'subscribe newsletter',
            'unsubscribe newsletter',
            'export personal data',
            'manage products',
            'manage blog',
            'manage categories',
            'manage users',
            'delete users',
            'view admin messages',
            'manage admin messages',
            'manage contacts',
        ]);

        // Création d'un admin par défaut (superuser)
        $admin = User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@farmshop.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);

        $admin->assignRole(User::ROLE_ADMIN);

        // Création d'un utilisateur standard par défaut
        $user = User::create([
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'user@farmshop.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);

        $user->assignRole(User::ROLE_USER);
    }
}
