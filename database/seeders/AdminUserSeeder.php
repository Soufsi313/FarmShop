<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vérifier si l'admin existe déjà
        $existingAdmin = User::where('email', 's.mef2703@gmail.com')->first();
        
        if (!$existingAdmin) {
            User::create([
                'username' => 'Saurouk',
                'name' => 'Meftah Soufiane',
                'email' => 's.mef2703@gmail.com',
                'password' => Hash::make('blade313'),
                'role' => 'Admin',
                'newsletter_subscribed' => false,
                'email_verified_at' => now(),
            ]);
            
            $this->command->info('Compte administrateur créé avec succès !');
        } else {
            $this->command->info('Le compte administrateur existe déjà.');
        }
    }
}
