<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Génération de noms français/belges réalistes (sans accents pour éviter les problèmes de charset)
        $firstNames = [
            'Antoine', 'Marie', 'Pierre', 'Sophie', 'Jean', 'Claire', 'Michel', 'Anne', 'Francois', 'Catherine',
            'Philippe', 'Isabelle', 'Laurent', 'Sylvie', 'David', 'Nathalie', 'Christophe', 'Valerie', 'Nicolas', 'Caroline',
            'Sebastien', 'Sandrine', 'Julien', 'Celine', 'Stephane', 'Francoise', 'Guillaume', 'Veronique', 'Olivier', 'Martine',
            'Thomas', 'Brigitte', 'Alexandre', 'Patricia', 'Maxime', 'Christine', 'Frederic', 'Monique', 'Vincent', 'Dominique',
            'Benoit', 'Karine', 'Damien', 'Aurelie', 'Romain', 'Emilie', 'Anthony', 'Virginie', 'Fabrice', 'Corinne',
            'Baptiste', 'Laetitia', 'Benjamin', 'Delphine', 'Ludovic', 'Pascale', 'Matthieu', 'Florence', 'Jonathan', 'Muriel'
        ];
        
        $lastNames = [
            'Martin', 'Bernard', 'Dubois', 'Thomas', 'Robert', 'Petit', 'Durand', 'Leroy', 'Moreau', 'Simon',
            'Laurent', 'Lefebvre', 'Michel', 'Garcia', 'David', 'Bertrand', 'Roux', 'Vincent', 'Fournier', 'Morel',
            'Girard', 'Andre', 'Lefevre', 'Mercier', 'Dupont', 'Lambert', 'Bonnet', 'Francois', 'Martinez', 'Legrand',
            'Garnier', 'Faure', 'Rousseau', 'Blanc', 'Guerin', 'Muller', 'Henry', 'Roussel', 'Nicolas', 'Perrin',
            'Van de Berg', 'Janssen', 'Peeters', 'Claes', 'Maes', 'Jacobs', 'Mertens', 'Willems', 'Vandenberghe', 'Wouters'
        ];
        
        $firstName = $this->faker->randomElement($firstNames);
        $lastName = $this->faker->randomElement($lastNames);
        $fullName = $firstName . ' ' . $lastName;
        
        // Générer un email réaliste basé sur le nom
        $emailPrefix = strtolower($firstName . '.' . $lastName);
        $emailPrefix = str_replace(' ', '.', $emailPrefix);
        $emailPrefix = $this->removeAccents($emailPrefix);
        
        $domains = ['gmail.com', 'outlook.com', 'hotmail.com', 'yahoo.fr', 'orange.fr', 'free.fr', 'skynet.be', 'proximus.be'];
        $email = $emailPrefix . $this->faker->numberBetween(1, 999) . '@' . $this->faker->randomElement($domains);
        
        // Générer un username unique basé sur le nom
        $username = strtolower($firstName . $lastName . $this->faker->numberBetween(1, 999));
        $username = str_replace(' ', '', $username); // Supprimer les espaces
        $username = $this->removeAccents($username);
        
        return [
            'name' => $fullName,
            'username' => $username,
            'email' => $email,
            'email_verified_at' => $this->faker->randomElement([now(), now()->subDays(rand(1, 30)), null]),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'created_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
        ];
    }
    
    /**
     * Remove accents from string for email generation
     */
    private function removeAccents($string)
    {
        $accents = [
            'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A',
            'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a',
            'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E',
            'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e',
            'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
            'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
            'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O',
            'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o',
            'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U',
            'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u',
            'Ç' => 'C', 'ç' => 'c',
            'Ñ' => 'N', 'ñ' => 'n'
        ];
        
        return strtr($string, $accents);
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    /**
     * Indicate that the user should have a personal team.
     *
     * @return $this
     */
    public function withPersonalTeam()
    {
        if (! Features::hasTeamFeatures()) {
            return $this->state([]);
        }

        return $this->has(
            Team::factory()
                ->state(function (array $attributes, User $user) {
                    return ['name' => $user->name.'\'s Team', 'user_id' => $user->id, 'personal_team' => true];
                }),
            'ownedTeams'
        );
    }
}
