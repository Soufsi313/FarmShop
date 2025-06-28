<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateContactPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:create-contact';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create manage contacts permission and assign to admin role';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Créer la permission si elle n'existe pas
        $permission = Permission::firstOrCreate(['name' => 'manage contacts']);
        $this->info("Permission 'manage contacts' créée ou trouvée.");

        // Trouver le rôle admin et lui donner la permission
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            if (!$adminRole->hasPermissionTo('manage contacts')) {
                $adminRole->givePermissionTo('manage contacts');
                $this->info("Permission assignée au rôle admin.");
            } else {
                $this->info("Le rôle admin a déjà la permission 'manage contacts'.");
            }
        } else {
            $this->error("Rôle admin non trouvé.");
            return 1;
        }

        $this->info("Terminé !");
        return 0;
    }
}
