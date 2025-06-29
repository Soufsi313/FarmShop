<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Créer 100 utilisateurs avec des données réalistes
        User::factory()
            ->count(100)
            ->create();

        // Créer quelques utilisateurs spécifiques avec des données fixes pour les tests
        User::factory()->create([
            'name' => 'Jean Dupont',
            'username' => 'jeandupont',
            'email' => 'jean.dupont@farmshop.be',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        User::factory()->create([
            'name' => 'Marie Martin',
            'username' => 'mariemartin',
            'email' => 'marie.martin@farmshop.be',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        User::factory()->create([
            'name' => 'Pierre Leroy',
            'username' => 'pierreleroy',
            'email' => 'pierre.leroy@farmshop.be',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        $this->command->info('100 utilisateurs avec données réalistes créés avec succès !');
        $this->command->info('3 utilisateurs de test supplémentaires créés (mot de passe: password123)');
    }
}
