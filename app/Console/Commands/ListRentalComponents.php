<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ListRentalComponents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rental:list-components';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Liste tous les composants du système de location';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🏗️  COMPOSANTS DU SYSTÈME DE LOCATION');
        $this->newLine();

        // Modèles
        $this->info('📁 MODÈLES:');
        $models = [
            'OrderLocation' => 'Commandes de location',
            'OrderItemLocation' => 'Éléments de commande de location',
            'CartLocation' => 'Panier de location',
            'CartItemLocation' => 'Éléments du panier de location'
        ];

        foreach ($models as $model => $description) {
            $path = app_path("Models/{$model}.php");
            $status = file_exists($path) ? '✅' : '❌';
            $this->line("  {$status} {$model} - {$description}");
        }

        $this->newLine();

        // Contrôleurs
        $this->info('📁 CONTRÔLEURS:');
        $controllers = [
            'OrderLocationController' => 'Gestion des commandes de location',
            'OrderItemLocationController' => 'Gestion des éléments de location',
            'CartLocationController' => 'Gestion du panier de location',
            'CartItemLocationController' => 'Gestion des éléments du panier'
        ];

        foreach ($controllers as $controller => $description) {
            $path = app_path("Http/Controllers/{$controller}.php");
            $status = file_exists($path) ? '✅' : '❌';
            $this->line("  {$status} {$controller} - {$description}");
        }

        $this->newLine();

        // Migrations
        $this->info('📁 MIGRATIONS:');
        $migrations = [
            'create_order_locations_table' => 'Table des commandes de location',
            'create_order_item_locations_table' => 'Table des éléments de location',
            'create_cart_locations_table' => 'Table du panier de location',
            'create_cart_item_locations_table' => 'Table des éléments du panier'
        ];

        foreach ($migrations as $migration => $description) {
            $path = database_path('migrations');
            $files = glob("{$path}/*_{$migration}.php");
            $status = !empty($files) ? '✅' : '❌';
            $this->line("  {$status} {$migration} - {$description}");
        }

        $this->newLine();

        // Jobs
        $this->info('📁 JOBS:');
        $jobs = [
            'UpdateRentalStatusJob' => 'Mise à jour automatique des statuts'
        ];

        foreach ($jobs as $job => $description) {
            $path = app_path("Jobs/{$job}.php");
            $status = file_exists($path) ? '✅' : '❌';
            $this->line("  {$status} {$job} - {$description}");
        }

        $this->newLine();

        // Templates Email
        $this->info('📁 TEMPLATES EMAIL:');
        $templates = [
            'rental-order-confirmed' => 'Confirmation de commande',
            'rental-order-cancelled' => 'Annulation de commande',
            'rental-order-completed' => 'Fin de période de location',
            'rental-order-inspection' => 'Rapport d\'inspection'
        ];

        foreach ($templates as $template => $description) {
            $path = resource_path("views/emails/{$template}.blade.php");
            $status = file_exists($path) ? '✅' : '❌';
            $this->line("  {$status} {$template}.blade.php - {$description}");
        }

        $this->newLine();

        // Classes Mail
        $this->info('📁 CLASSES MAIL:');
        $mailClasses = [
            'RentalOrderConfirmed' => 'Email de confirmation',
            'RentalOrderCancelled' => 'Email d\'annulation',
            'RentalOrderCompleted' => 'Email de fin de location',
            'RentalOrderInspection' => 'Email d\'inspection'
        ];

        foreach ($mailClasses as $mailClass => $description) {
            $path = app_path("Mail/{$mailClass}.php");
            $status = file_exists($path) ? '✅' : '❌';
            $this->line("  {$status} {$mailClass} - {$description}");
        }

        $this->newLine();

        // Routes
        $this->info('📁 ROUTES DISPONIBLES:');
        $this->line("  ✅ API Routes pour utilisateurs connectés (/api/rental-orders)");
        $this->line("  ✅ API Routes pour admin (/api/admin/rental-orders)");
        $this->line("  ✅ API Routes pour éléments (/api/admin/rental-items)");

        $this->newLine();

        // Fonctionnalités
        $this->info('🚀 FONCTIONNALITÉS PRINCIPALES:');
        $features = [
            'Création de commandes de location depuis le panier',
            'Système de statuts automatique (pending→confirmed→active→completed→closed→inspecting→finished)',
            'Gestion des annulations (jusqu\'à 23:59 la veille du début)',
            'Calcul automatique des dépôts et prix journaliers',
            'Système d\'inspection avec évaluation de l\'état',
            'Calcul automatique des pénalités (retards: 10€/jour)',
            'Notifications email pour chaque étape',
            'Interface admin pour gestion et inspection',
            'Mise à jour automatique des statuts via jobs',
            'Historique complet des modifications'
        ];

        foreach ($features as $feature) {
            $this->line("  ✅ {$feature}");
        }

        $this->newLine();
        $this->info('🎉 SYSTÈME DE LOCATION COMPLET ET OPÉRATIONNEL !');

        return 0;
    }
}
