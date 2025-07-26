<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cookie;
use App\Models\User;
use Carbon\Carbon;

class CookieConsentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Supprimer les anciens cookies de test
        Cookie::truncate();

        // Récupérer tous les utilisateurs
        $users = User::all();
        
        if ($users->count() < 100) {
            $this->command->warn("Il n'y a que {$users->count()} utilisateurs. Création de consentements pour tous.");
        }

        $statuses = ['accepted', 'rejected', 'pending', 'partial'];
        $userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:89.0) Gecko/20100101 Firefox/89.0',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.1.1 Safari/605.1.15',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Edge/91.0.864.59'
        ];

        $ips = [
            '192.168.1.100',
            '192.168.1.101',
            '10.0.0.15',
            '172.16.0.100',
            '127.0.0.1',
            '192.168.0.50',
            '10.1.1.25'
        ];

        $pages = [
            'https://farmshop.com',
            'https://farmshop.com/products',
            'https://farmshop.com/categories',
            'https://farmshop.com/cart',
            'https://farmshop.com/checkout',
            'https://farmshop.com/blog',
            'https://farmshop.com/contact'
        ];

        // Créer 100 consentements
        for ($i = 0; $i < 100; $i++) {
            // Choisir un utilisateur aléatoire (80% d'utilisateurs connectés, 20% de visiteurs)
            $user = (rand(1, 100) <= 80) ? $users->random() : null;
            
            // Choisir un statut avec des probabilités réalistes
            $statusRand = rand(1, 100);
            if ($statusRand <= 60) {
                $status = 'accepted';  // 60% acceptent
            } elseif ($statusRand <= 80) {
                $status = 'rejected';  // 20% rejettent tout
            } elseif ($statusRand <= 95) {
                $status = 'partial';   // 15% acceptent partiellement
            } else {
                $status = 'pending';   // 5% en attente
            }

            // Définir les préférences en fonction du statut
            $necessary = true; // Toujours vrai
            $analytics = false;
            $marketing = false;
            $preferences = false;
            $socialMedia = false;

            if ($status === 'accepted') {
                // Acceptation complète avec quelques variations
                $analytics = rand(1, 100) <= 85;  // 85% acceptent analytics
                $marketing = rand(1, 100) <= 60;  // 60% acceptent marketing
                $preferences = rand(1, 100) <= 90; // 90% acceptent preferences
                $socialMedia = rand(1, 100) <= 40; // 40% acceptent social media
            } elseif ($status === 'partial') {
                // Acceptation partielle - plus sélectif
                $analytics = rand(1, 100) <= 70;  // 70% acceptent analytics
                $marketing = rand(1, 100) <= 20;  // 20% acceptent marketing
                $preferences = rand(1, 100) <= 80; // 80% acceptent preferences
                $socialMedia = rand(1, 100) <= 10; // 10% acceptent social media
            }
            // Pour 'rejected' et 'pending', tout reste false sauf necessary

            // Dates réalistes (derniers 3 mois)
            $createdAt = Carbon::now()->subDays(rand(0, 90));
            $updatedAt = $createdAt->copy()->addMinutes(rand(0, 1440)); // Mis à jour dans les 24h

            // Définir accepted_at et rejected_at selon le statut
            $acceptedAt = null;
            $rejectedAt = null;
            $lastUpdatedAt = $updatedAt;

            if ($status === 'accepted' || $status === 'partial') {
                $acceptedAt = $updatedAt;
            } elseif ($status === 'rejected') {
                $rejectedAt = $updatedAt;
            }

            // Préférences détaillées
            $preferencesDetails = [
                'language' => ['fr', 'en', 'es'][rand(0, 2)],
                'theme' => ['light', 'dark'][rand(0, 1)],
                'notifications' => rand(1, 100) <= 70,
                'newsletter' => rand(1, 100) <= 50,
                'tracking_level' => ['minimal', 'standard', 'full'][rand(0, 2)]
            ];

            // Informations du navigateur
            $browserInfo = [
                'screen_resolution' => ['1920x1080', '1366x768', '1440x900', '2560x1440'][rand(0, 3)],
                'timezone' => 'Europe/Paris',
                'language' => 'fr-FR',
                'platform' => ['Windows', 'macOS', 'Linux'][rand(0, 2)],
                'mobile' => rand(1, 100) <= 30
            ];

            Cookie::create([
                'user_id' => $user?->id,
                'session_id' => $user ? null : 'session_' . uniqid(),
                'ip_address' => $ips[array_rand($ips)],
                'user_agent' => $userAgents[array_rand($userAgents)],
                'necessary' => $necessary,
                'analytics' => $analytics,
                'marketing' => $marketing,
                'preferences' => $preferences,
                'social_media' => $socialMedia,
                'accepted_at' => $acceptedAt,
                'rejected_at' => $rejectedAt,
                'last_updated_at' => $lastUpdatedAt,
                'preferences_details' => $preferencesDetails,
                'consent_version' => ['1.0', '1.1', '2.0'][rand(0, 2)],
                'status' => $status,
                'page_url' => $pages[array_rand($pages)],
                'referer' => rand(1, 100) <= 60 ? 'https://google.com' : null,
                'browser_info' => $browserInfo,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ]);
        }

        $this->command->info('100 consentements de cookies créés avec succès !');
        
        // Afficher les statistiques
        $stats = [
            'Total' => Cookie::count(),
            'Acceptés' => Cookie::where('status', 'accepted')->count(),
            'Rejetés' => Cookie::where('status', 'rejected')->count(),
            'Partiels' => Cookie::where('status', 'partial')->count(),
            'En attente' => Cookie::where('status', 'pending')->count(),
            'Avec utilisateur' => Cookie::whereNotNull('user_id')->count(),
            'Visiteurs' => Cookie::whereNull('user_id')->count(),
        ];

        $this->command->table(['Statut', 'Nombre'], collect($stats)->map(fn($count, $status) => [$status, $count])->toArray());
    }
}
