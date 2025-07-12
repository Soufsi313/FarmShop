<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Noms et prénoms par origine
        $users_data = [
            // 25 Européens
            ['first_name' => 'Jean', 'last_name' => 'Dupont', 'origin' => 'european'],
            ['first_name' => 'Marie', 'last_name' => 'Martin', 'origin' => 'european'],
            ['first_name' => 'Pierre', 'last_name' => 'Bernard', 'origin' => 'european'],
            ['first_name' => 'Sophie', 'last_name' => 'Dubois', 'origin' => 'european'],
            ['first_name' => 'Antoine', 'last_name' => 'Moreau', 'origin' => 'european'],
            ['first_name' => 'Camille', 'last_name' => 'Laurent', 'origin' => 'european'],
            ['first_name' => 'Nicolas', 'last_name' => 'Simon', 'origin' => 'european'],
            ['first_name' => 'Emma', 'last_name' => 'Michel', 'origin' => 'european'],
            ['first_name' => 'Lucas', 'last_name' => 'Leroy', 'origin' => 'european'],
            ['first_name' => 'Clara', 'last_name' => 'Roux', 'origin' => 'european'],
            ['first_name' => 'Thomas', 'last_name' => 'David', 'origin' => 'european'],
            ['first_name' => 'Julie', 'last_name' => 'Bertrand', 'origin' => 'european'],
            ['first_name' => 'Alexandre', 'last_name' => 'Petit', 'origin' => 'european'],
            ['first_name' => 'Manon', 'last_name' => 'Garcia', 'origin' => 'european'],
            ['first_name' => 'Maxime', 'last_name' => 'Rodriguez', 'origin' => 'european'],
            ['first_name' => 'Laura', 'last_name' => 'Fernandez', 'origin' => 'european'],
            ['first_name' => 'Hugo', 'last_name' => 'Lopez', 'origin' => 'european'],
            ['first_name' => 'Lea', 'last_name' => 'Martinez', 'origin' => 'european'],
            ['first_name' => 'Arthur', 'last_name' => 'Gonzalez', 'origin' => 'european'],
            ['first_name' => 'Chloe', 'last_name' => 'Perez', 'origin' => 'european'],
            ['first_name' => 'Louis', 'last_name' => 'Sanchez', 'origin' => 'european'],
            ['first_name' => 'Sarah', 'last_name' => 'Ramirez', 'origin' => 'european'],
            ['first_name' => 'Gabriel', 'last_name' => 'Torres', 'origin' => 'european'],
            ['first_name' => 'Lina', 'last_name' => 'Flores', 'origin' => 'european'],
            ['first_name' => 'Nathan', 'last_name' => 'Rivera', 'origin' => 'european'],

            // 25 Maghrébins
            ['first_name' => 'Ahmed', 'last_name' => 'Benali', 'origin' => 'maghreb'],
            ['first_name' => 'Fatima', 'last_name' => 'Alaoui', 'origin' => 'maghreb'],
            ['first_name' => 'Mohammed', 'last_name' => 'Bouali', 'origin' => 'maghreb'],
            ['first_name' => 'Aicha', 'last_name' => 'Mansouri', 'origin' => 'maghreb'],
            ['first_name' => 'Omar', 'last_name' => 'Hassani', 'origin' => 'maghreb'],
            ['first_name' => 'Khadija', 'last_name' => 'Benkirane', 'origin' => 'maghreb'],
            ['first_name' => 'Youssef', 'last_name' => 'Tounsi', 'origin' => 'maghreb'],
            ['first_name' => 'Zineb', 'last_name' => 'Amrani', 'origin' => 'maghreb'],
            ['first_name' => 'Karim', 'last_name' => 'Belkacem', 'origin' => 'maghreb'],
            ['first_name' => 'Samira', 'last_name' => 'Cherif', 'origin' => 'maghreb'],
            ['first_name' => 'Amine', 'last_name' => 'Benjelloun', 'origin' => 'maghreb'],
            ['first_name' => 'Leila', 'last_name' => 'Kadiri', 'origin' => 'maghreb'],
            ['first_name' => 'Mehdi', 'last_name' => 'Sefrioui', 'origin' => 'maghreb'],
            ['first_name' => 'Nadia', 'last_name' => 'Bouazza', 'origin' => 'maghreb'],
            ['first_name' => 'Samir', 'last_name' => 'Lamrani', 'origin' => 'maghreb'],
            ['first_name' => 'Souad', 'last_name' => 'Bennani', 'origin' => 'maghreb'],
            ['first_name' => 'Rachid', 'last_name' => 'Zemmouri', 'origin' => 'maghreb'],
            ['first_name' => 'Hafsa', 'last_name' => 'Mimouni', 'origin' => 'maghreb'],
            ['first_name' => 'Tarik', 'last_name' => 'Chraibi', 'origin' => 'maghreb'],
            ['first_name' => 'Malika', 'last_name' => 'Fassi', 'origin' => 'maghreb'],
            ['first_name' => 'Abdel', 'last_name' => 'Filali', 'origin' => 'maghreb'],
            ['first_name' => 'Jamila', 'last_name' => 'Tahiri', 'origin' => 'maghreb'],
            ['first_name' => 'Khalid', 'last_name' => 'Amellal', 'origin' => 'maghreb'],
            ['first_name' => 'Rajae', 'last_name' => 'Bensouda', 'origin' => 'maghreb'],
            ['first_name' => 'Driss', 'last_name' => 'Berrada', 'origin' => 'maghreb'],

            // 25 Africains subsahariens et Afrique centrale
            ['first_name' => 'Kwame', 'last_name' => 'Asante', 'origin' => 'subsaharan'],
            ['first_name' => 'Ama', 'last_name' => 'Osei', 'origin' => 'subsaharan'],
            ['first_name' => 'Kofi', 'last_name' => 'Mensah', 'origin' => 'subsaharan'],
            ['first_name' => 'Akosua', 'last_name' => 'Boateng', 'origin' => 'subsaharan'],
            ['first_name' => 'Sekou', 'last_name' => 'Traore', 'origin' => 'subsaharan'],
            ['first_name' => 'Aminata', 'last_name' => 'Keita', 'origin' => 'subsaharan'],
            ['first_name' => 'Ibrahim', 'last_name' => 'Diallo', 'origin' => 'subsaharan'],
            ['first_name' => 'Mariama', 'last_name' => 'Sow', 'origin' => 'subsaharan'],
            ['first_name' => 'Moussa', 'last_name' => 'Camara', 'origin' => 'subsaharan'],
            ['first_name' => 'Fatoumata', 'last_name' => 'Kone', 'origin' => 'subsaharan'],
            ['first_name' => 'Amadou', 'last_name' => 'Sidibe', 'origin' => 'subsaharan'],
            ['first_name' => 'Rokia', 'last_name' => 'Sangare', 'origin' => 'subsaharan'],
            ['first_name' => 'Ousmane', 'last_name' => 'Coulibaly', 'origin' => 'subsaharan'],
            ['first_name' => 'Salimata', 'last_name' => 'Diabate', 'origin' => 'subsaharan'],
            ['first_name' => 'Lamine', 'last_name' => 'Toure', 'origin' => 'subsaharan'],
            ['first_name' => 'Adama', 'last_name' => 'Konate', 'origin' => 'subsaharan'],
            ['first_name' => 'Bakary', 'last_name' => 'Doucoure', 'origin' => 'subsaharan'],
            ['first_name' => 'Ramata', 'last_name' => 'Sissoko', 'origin' => 'subsaharan'],
            ['first_name' => 'Yaya', 'last_name' => 'Berthe', 'origin' => 'subsaharan'],
            ['first_name' => 'Awa', 'last_name' => 'Dembele', 'origin' => 'subsaharan'],
            ['first_name' => 'Mamadou', 'last_name' => 'Barry', 'origin' => 'subsaharan'],
            ['first_name' => 'Hawa', 'last_name' => 'Bah', 'origin' => 'subsaharan'],
            ['first_name' => 'Souleymane', 'last_name' => 'Kaba', 'origin' => 'subsaharan'],
            ['first_name' => 'Mawa', 'last_name' => 'Fofana', 'origin' => 'subsaharan'],
            ['first_name' => 'Boubacar', 'last_name' => 'Sylla', 'origin' => 'subsaharan'],

            // 25 Asiatiques
            ['first_name' => 'Wei', 'last_name' => 'Chen', 'origin' => 'asian'],
            ['first_name' => 'Li', 'last_name' => 'Wang', 'origin' => 'asian'],
            ['first_name' => 'Ming', 'last_name' => 'Zhang', 'origin' => 'asian'],
            ['first_name' => 'Mei', 'last_name' => 'Liu', 'origin' => 'asian'],
            ['first_name' => 'Hiroshi', 'last_name' => 'Tanaka', 'origin' => 'asian'],
            ['first_name' => 'Yuki', 'last_name' => 'Suzuki', 'origin' => 'asian'],
            ['first_name' => 'Takeshi', 'last_name' => 'Yamamoto', 'origin' => 'asian'],
            ['first_name' => 'Akiko', 'last_name' => 'Watanabe', 'origin' => 'asian'],
            ['first_name' => 'Raj', 'last_name' => 'Patel', 'origin' => 'asian'],
            ['first_name' => 'Priya', 'last_name' => 'Sharma', 'origin' => 'asian'],
            ['first_name' => 'Arjun', 'last_name' => 'Kumar', 'origin' => 'asian'],
            ['first_name' => 'Anita', 'last_name' => 'Singh', 'origin' => 'asian'],
            ['first_name' => 'Min', 'last_name' => 'Kim', 'origin' => 'asian'],
            ['first_name' => 'Soo', 'last_name' => 'Park', 'origin' => 'asian'],
            ['first_name' => 'Jun', 'last_name' => 'Lee', 'origin' => 'asian'],
            ['first_name' => 'Hye', 'last_name' => 'Choi', 'origin' => 'asian'],
            ['first_name' => 'Thanh', 'last_name' => 'Nguyen', 'origin' => 'asian'],
            ['first_name' => 'Linh', 'last_name' => 'Tran', 'origin' => 'asian'],
            ['first_name' => 'Duc', 'last_name' => 'Le', 'origin' => 'asian'],
            ['first_name' => 'Mai', 'last_name' => 'Pham', 'origin' => 'asian'],
            ['first_name' => 'Budi', 'last_name' => 'Santoso', 'origin' => 'asian'],
            ['first_name' => 'Sari', 'last_name' => 'Wijaya', 'origin' => 'asian'],
            ['first_name' => 'Andi', 'last_name' => 'Susanto', 'origin' => 'asian'],
            ['first_name' => 'Dewi', 'last_name' => 'Pratama', 'origin' => 'asian'],
            ['first_name' => 'Rizki', 'last_name' => 'Utama', 'origin' => 'asian']
        ];

        // Adresses en Belgique et France
        $addresses = [
            // Belgique
            ['street' => 'Rue de la Loi 16', 'city' => 'Bruxelles', 'postal_code' => '1000', 'country' => 'BE'],
            ['street' => 'Avenue Louise 54', 'city' => 'Bruxelles', 'postal_code' => '1050', 'country' => 'BE'],
            ['street' => 'Chaussee de Wavre 112', 'city' => 'Bruxelles', 'postal_code' => '1050', 'country' => 'BE'],
            ['street' => 'Rue Neuve 89', 'city' => 'Bruxelles', 'postal_code' => '1000', 'country' => 'BE'],
            ['street' => 'Boulevard Anspach 45', 'city' => 'Bruxelles', 'postal_code' => '1000', 'country' => 'BE'],
            ['street' => 'Place Sainte-Catherine 12', 'city' => 'Bruxelles', 'postal_code' => '1000', 'country' => 'BE'],
            ['street' => 'Rue des Bouchers 23', 'city' => 'Bruxelles', 'postal_code' => '1000', 'country' => 'BE'],
            ['street' => 'Avenue de Tervueren 78', 'city' => 'Bruxelles', 'postal_code' => '1040', 'country' => 'BE'],
            ['street' => 'Chaussee de Louvain 234', 'city' => 'Bruxelles', 'postal_code' => '1210', 'country' => 'BE'],
            ['street' => 'Rue du Marche aux Herbes 67', 'city' => 'Bruxelles', 'postal_code' => '1000', 'country' => 'BE'],
            ['street' => 'Meir 145', 'city' => 'Anvers', 'postal_code' => '2000', 'country' => 'BE'],
            ['street' => 'Groenplaats 34', 'city' => 'Anvers', 'postal_code' => '2000', 'country' => 'BE'],
            ['street' => 'Lange Nieuwstraat 89', 'city' => 'Anvers', 'postal_code' => '2000', 'country' => 'BE'],
            ['street' => 'Koningin Astridplein 12', 'city' => 'Anvers', 'postal_code' => '2018', 'country' => 'BE'],
            ['street' => 'Frankrijklei 56', 'city' => 'Anvers', 'postal_code' => '2000', 'country' => 'BE'],
            ['street' => 'Grote Markt 23', 'city' => 'Gand', 'postal_code' => '9000', 'country' => 'BE'],
            ['street' => 'Korenlei 45', 'city' => 'Gand', 'postal_code' => '9000', 'country' => 'BE'],
            ['street' => 'Veldstraat 78', 'city' => 'Gand', 'postal_code' => '9000', 'country' => 'BE'],
            ['street' => 'Sint-Baafsplein 12', 'city' => 'Gand', 'postal_code' => '9000', 'country' => 'BE'],
            ['street' => 'Vrijdagmarkt 34', 'city' => 'Gand', 'postal_code' => '9000', 'country' => 'BE'],
            ['street' => 'Markt 67', 'city' => 'Bruges', 'postal_code' => '8000', 'country' => 'BE'],
            ['street' => 'Steenstraat 89', 'city' => 'Bruges', 'postal_code' => '8000', 'country' => 'BE'],
            ['street' => 'Wollestraat 23', 'city' => 'Bruges', 'postal_code' => '8000', 'country' => 'BE'],
            ['street' => 'Simon Stevinplein 45', 'city' => 'Bruges', 'postal_code' => '8000', 'country' => 'BE'],
            ['street' => 'Katelijnestraat 12', 'city' => 'Bruges', 'postal_code' => '8000', 'country' => 'BE'],
            ['street' => 'Place Saint-Lambert 34', 'city' => 'Liège', 'postal_code' => '4000', 'country' => 'BE'],
            ['street' => 'Rue de la Regence 78', 'city' => 'Liège', 'postal_code' => '4000', 'country' => 'BE'],
            ['street' => 'Boulevard de la Sauveniere 123', 'city' => 'Liège', 'postal_code' => '4000', 'country' => 'BE'],
            ['street' => 'Rue Pont d\'Avroy 56', 'city' => 'Liège', 'postal_code' => '4000', 'country' => 'BE'],
            ['street' => 'Place du Marche 89', 'city' => 'Liège', 'postal_code' => '4000', 'country' => 'BE'],

            // France
            ['street' => 'Rue de Rivoli 123', 'city' => 'Paris', 'postal_code' => '75001', 'country' => 'FR'],
            ['street' => 'Avenue des Champs-Elysees 456', 'city' => 'Paris', 'postal_code' => '75008', 'country' => 'FR'],
            ['street' => 'Boulevard Saint-Germain 789', 'city' => 'Paris', 'postal_code' => '75006', 'country' => 'FR'],
            ['street' => 'Rue de la Paix 34', 'city' => 'Paris', 'postal_code' => '75002', 'country' => 'FR'],
            ['street' => 'Place Vendome 12', 'city' => 'Paris', 'postal_code' => '75001', 'country' => 'FR'],
            ['street' => 'Rue du Faubourg Saint-Honore 67', 'city' => 'Paris', 'postal_code' => '75008', 'country' => 'FR'],
            ['street' => 'Avenue Montaigne 23', 'city' => 'Paris', 'postal_code' => '75008', 'country' => 'FR'],
            ['street' => 'Rue Saint-Antoine 89', 'city' => 'Paris', 'postal_code' => '75004', 'country' => 'FR'],
            ['street' => 'Boulevard Haussmann 145', 'city' => 'Paris', 'postal_code' => '75009', 'country' => 'FR'],
            ['street' => 'Rue de la Roquette 56', 'city' => 'Paris', 'postal_code' => '75011', 'country' => 'FR'],
            ['street' => 'Cours Mirabeau 78', 'city' => 'Aix-en-Provence', 'postal_code' => '13100', 'country' => 'FR'],
            ['street' => 'Place des Cardeurs 23', 'city' => 'Aix-en-Provence', 'postal_code' => '13100', 'country' => 'FR'],
            ['street' => 'Rue Espariat 45', 'city' => 'Aix-en-Provence', 'postal_code' => '13100', 'country' => 'FR'],
            ['street' => 'Avenue Victor Hugo 67', 'city' => 'Aix-en-Provence', 'postal_code' => '13100', 'country' => 'FR'],
            ['street' => 'Place de la Rotonde 12', 'city' => 'Aix-en-Provence', 'postal_code' => '13100', 'country' => 'FR'],
            ['street' => 'La Canebiere 123', 'city' => 'Marseille', 'postal_code' => '13001', 'country' => 'FR'],
            ['street' => 'Cours Julien 45', 'city' => 'Marseille', 'postal_code' => '13006', 'country' => 'FR'],
            ['street' => 'Rue de la Republique 89', 'city' => 'Marseille', 'postal_code' => '13002', 'country' => 'FR'],
            ['street' => 'Avenue du Prado 234', 'city' => 'Marseille', 'postal_code' => '13008', 'country' => 'FR'],
            ['street' => 'Corniche Kennedy 67', 'city' => 'Marseille', 'postal_code' => '13007', 'country' => 'FR'],
            ['street' => 'Place Bellecour 34', 'city' => 'Lyon', 'postal_code' => '69002', 'country' => 'FR'],
            ['street' => 'Rue de la Republique 78', 'city' => 'Lyon', 'postal_code' => '69002', 'country' => 'FR'],
            ['street' => 'Cours Lafayette 123', 'city' => 'Lyon', 'postal_code' => '69003', 'country' => 'FR'],
            ['street' => 'Avenue Jean Jaures 56', 'city' => 'Lyon', 'postal_code' => '69007', 'country' => 'FR'],
            ['street' => 'Place des Terreaux 12', 'city' => 'Lyon', 'postal_code' => '69001', 'country' => 'FR'],
            ['street' => 'Place du Capitole 45', 'city' => 'Toulouse', 'postal_code' => '31000', 'country' => 'FR'],
            ['street' => 'Rue de Metz 89', 'city' => 'Toulouse', 'postal_code' => '31000', 'country' => 'FR'],
            ['street' => 'Allees Jean Jaures 23', 'city' => 'Toulouse', 'postal_code' => '31000', 'country' => 'FR'],
            ['street' => 'Boulevard de Strasbourg 67', 'city' => 'Toulouse', 'postal_code' => '31000', 'country' => 'FR'],
            ['street' => 'Rue Saint-Rome 34', 'city' => 'Toulouse', 'postal_code' => '31000', 'country' => 'FR']
        ];

        // Domaines email populaires
        $email_domains = ['gmail.com', 'yahoo.fr', 'hotmail.com', 'outlook.com', 'orange.fr', 'wanadoo.fr', 'free.fr', 'laposte.net'];

        // Numéros de téléphone réalistes
        $phone_prefixes_be = ['+32 2', '+32 3', '+32 4', '+32 9', '+32 10', '+32 11'];
        $phone_prefixes_fr = ['+33 1', '+33 2', '+33 3', '+33 4', '+33 5', '+33 6', '+33 7'];

        // Créer les utilisateurs
        foreach ($users_data as $index => $user_data) {
            $address = $addresses[$index % count($addresses)];
            $email_domain = $email_domains[array_rand($email_domains)];
            
            // Générer un username unique
            $username = strtolower($user_data['first_name'] . '_' . $user_data['last_name'] . '_' . ($index + 1));
            
            // Générer l'email
            $email = strtolower($user_data['first_name'] . '.' . $user_data['last_name'] . '.' . ($index + 1) . '@' . $email_domain);
            
            // Générer le téléphone selon le pays
            if ($address['country'] === 'BE') {
                $phone_prefix = $phone_prefixes_be[array_rand($phone_prefixes_be)];
                $phone = $phone_prefix . ' ' . rand(10, 99) . ' ' . rand(10, 99) . ' ' . rand(10, 99);
            } else {
                $phone_prefix = $phone_prefixes_fr[array_rand($phone_prefixes_fr)];
                $phone = $phone_prefix . ' ' . rand(10, 99) . ' ' . rand(10, 99) . ' ' . rand(10, 99) . ' ' . rand(10, 99);
            }

            User::create([
                'username' => $username,
                'name' => $user_data['first_name'] . ' ' . $user_data['last_name'],
                'email' => $email,
                'email_verified_at' => Carbon::now()->subDays(rand(1, 365)), // Email vérifié entre 1 et 365 jours
                'password' => Hash::make('password123'), // Mot de passe par défaut
                'phone' => $phone,
                'address' => $address['street'],
                'address_line_2' => rand(1, 999) . '/' . rand(1, 99), // Numéro d'appartement/boîte
                'city' => $address['city'],
                'postal_code' => $address['postal_code'],
                'country' => $address['country'],
                'role' => 'User', // Tous sont des utilisateurs normaux
                'newsletter_subscribed' => true, // Tous abonnés à la newsletter
                'created_at' => Carbon::now()->subDays(rand(1, 730)), // Créé entre 1 et 730 jours
                'updated_at' => Carbon::now()->subDays(rand(0, 30)), // Mis à jour dans les 30 derniers jours
            ]);
        }

        echo "✅ 100 utilisateurs créés avec succès !\n";
        echo "📊 Répartition :\n";
        echo "   - 25 utilisateurs d'origine européenne\n";
        echo "   - 25 utilisateurs d'origine maghrébine\n";
        echo "   - 25 utilisateurs d'origine subsaharienne/Afrique centrale\n";
        echo "   - 25 utilisateurs d'origine asiatique\n";
        echo "🌍 Adresses réparties entre la Belgique et la France\n";
        echo "📧 Tous les emails sont vérifiés\n";
        echo "📬 Tous les utilisateurs sont abonnés à la newsletter\n";
    }
}
