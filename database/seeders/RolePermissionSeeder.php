<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Créer les permissions
        $permissions = [
            // Permissions produits
            'view_products',
            'create_products',
            'update_products',
            'delete_products',
            
            // Permissions blog
            'view_blog',
            'create_blog',
            'update_blog',
            'delete_blog',
            'comment_blog',
            
            // Permissions catégories
            'view_categories',
            'create_categories',
            'update_categories',
            'delete_categories',
            
            // Permissions utilisateurs
            'view_users',
            'create_users',
            'update_users',
            'delete_users',
            'restore_users',
            
            // Permissions newsletter
            'subscribe_newsletter',
            'unsubscribe_newsletter',
            
            // Permissions contact
            'contact_admin',
            'view_messages',
            'reply_messages',
            
            // Permissions achats
            'purchase_products',
            'rent_products',
            
            // Permissions données
            'export_data',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Créer les rôles
        $userRole = Role::firstOrCreate(['name' => User::ROLE_USER]);
        $adminRole = Role::firstOrCreate(['name' => User::ROLE_ADMIN]);
        $superuserRole = Role::firstOrCreate(['name' => User::ROLE_SUPERUSER]);

        // Assigner les permissions au rôle USER
        $userRole->givePermissionTo([
            'view_products',
            'view_blog',
            'comment_blog',
            'subscribe_newsletter',
            'unsubscribe_newsletter',
            'contact_admin',
            'purchase_products',
            'rent_products',
            'export_data',
        ]);

        // Assigner les permissions au rôle ADMIN (hérite de USER + CRUD)
        $adminRole->givePermissionTo([
            // Permissions USER
            'view_products',
            'view_blog',
            'comment_blog',
            'subscribe_newsletter',
            'unsubscribe_newsletter',
            'contact_admin',
            'purchase_products',
            'rent_products',
            'export_data',
            
            // Permissions ADMIN
            'create_products',
            'update_products',
            'delete_products',
            'create_blog',
            'update_blog',
            'delete_blog',
            'create_categories',
            'update_categories',
            'delete_categories',
            'view_users',
            'update_users',
            'delete_users',
            'restore_users',
            'view_messages',
            'reply_messages',
        ]);

        // Assigner les permissions au rôle SUPERUSER (toutes les permissions)
        $superuserRole->givePermissionTo(Permission::all());
    }
}
