<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateNavigationDiagrams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'farmshop:generate-navigation-diagrams';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Génère les diagrammes de navigation pour les processus d\'achat et de location';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Génération des Diagrammes de Navigation FarmShop ===');
        $this->newLine();

        try {
            // Configuration du gestionnaire d'images
            $manager = new ImageManager(new Driver());

            // Créer l'image de base
            $width = 1600;
            $height = 1200;
            $image = $manager->create($width, $height);

            // Couleurs
            $colors = [
                'background' => '#ffffff',
                'start' => '#4CAF50',      // Vert
                'process' => '#2196F3',    // Bleu
                'decision' => '#FF9800',   // Orange
                'endpoint' => '#9C27B0',   // Violet
                'payment' => '#F44336',    // Rouge
                'success' => '#8BC34A',    // Vert clair
                'text' => '#000000',       // Noir
                'arrow' => '#666666'       // Gris
            ];

            // Remplir le fond
            $image->fill($colors['background']);

            $this->info('📊 Création du canvas de base...');

            // Titre principal
            $image->text('DIAGRAMMES DE NAVIGATION FARMSHOP', $width/2, 40, function ($font) {
                $font->size(24);
                $font->color('#000000');
                $font->align('center');
                $font->weight('bold');
            });

            // ========== DIAGRAMME ACHAT ==========
            $this->info('🛒 Génération du diagramme d\'achat...');

            // Titre achat
            $image->text('PROCESSUS D\'ACHAT', $width/4, 80, function ($font) {
                $font->size(18);
                $font->color('#000000');
                $font->align('center');
                $font->weight('bold');
            });

            // Éléments du processus d'achat (côté gauche)
            $leftX = $width/4;
            $boxWidth = 160;
            $boxHeight = 50;

            $achatSteps = [
                ['y' => 140, 'text' => "Page d'Accueil\n/products", 'color' => $colors['start']],
                ['y' => 210, 'text' => "Catalogue\nProduits", 'color' => $colors['process']],
                ['y' => 280, 'text' => "Détail Produit\n/products/{slug}", 'color' => $colors['process']],
                ['y' => 350, 'text' => "Panier\n/cart", 'color' => $colors['process']],
                ['y' => 420, 'text' => "Connexion\n/login", 'color' => $colors['decision']],
                ['y' => 490, 'text' => "Checkout\n/checkout", 'color' => $colors['process']],
                ['y' => 560, 'text' => "Paiement Stripe\n/payment/{order}", 'color' => $colors['payment']],
                ['y' => 630, 'text' => "Confirmation\n/orders/{order}", 'color' => $colors['success']],
                ['y' => 700, 'text' => "Mes Commandes\n/orders", 'color' => $colors['endpoint']]
            ];

            foreach ($achatSteps as $index => $step) {
                $this->drawRoundedBox($image, $leftX, $step['y'], $boxWidth, $boxHeight, $step['text'], $step['color']);
                
                // Dessiner la flèche vers l'étape suivante
                if ($index < count($achatSteps) - 1) {
                    $this->drawArrow($image, $leftX, $step['y'] + $boxHeight/2, $leftX, $achatSteps[$index + 1]['y'] - $boxHeight/2);
                }
            }

            // ========== DIAGRAMME LOCATION ==========
            $this->info('🚜 Génération du diagramme de location...');

            // Titre location
            $image->text('PROCESSUS DE LOCATION', 3*$width/4, 80, function ($font) {
                $font->size(18);
                $font->color('#000000');
                $font->align('center');
                $font->weight('bold');
            });

            // Éléments du processus de location (côté droit)
            $rightX = 3*$width/4;

            $locationSteps = [
                ['y' => 140, 'text' => "Catalogue Location\n/rentals", 'color' => $colors['start']],
                ['y' => 210, 'text' => "Filtrage\nÉquipements", 'color' => $colors['process']],
                ['y' => 280, 'text' => "Détail Équipement\n/rentals/{slug}", 'color' => $colors['process']],
                ['y' => 350, 'text' => "Panier Location\n/cart-location", 'color' => $colors['process']],
                ['y' => 420, 'text' => "Sélection Dates\nLocation", 'color' => $colors['decision']],
                ['y' => 490, 'text' => "Checkout Location\n/checkout-rental", 'color' => $colors['process']],
                ['y' => 560, 'text' => "Paiement + Caution\n/payment-rental", 'color' => $colors['payment']],
                ['y' => 630, 'text' => "Location Confirmée\n/rental-orders/{order}", 'color' => $colors['success']],
                ['y' => 700, 'text' => "Mes Locations\n/rental-orders", 'color' => $colors['endpoint']],
                ['y' => 770, 'text' => "Retour Équipement\n+ Inspection", 'color' => $colors['endpoint']]
            ];

            foreach ($locationSteps as $index => $step) {
                $this->drawRoundedBox($image, $rightX, $step['y'], $boxWidth, $boxHeight, $step['text'], $step['color']);
                
                // Dessiner la flèche vers l'étape suivante
                if ($index < count($locationSteps) - 1) {
                    $this->drawArrow($image, $rightX, $step['y'] + $boxHeight/2, $rightX, $locationSteps[$index + 1]['y'] - $boxHeight/2);
                }
            }

            // Ligne de séparation
            $image->drawLine(function ($draw) use ($width, $height) {
                $draw->from($width/2, 100);
                $draw->to($width/2, $height - 100);
                $draw->color('#cccccc');
                $draw->width(3);
            });

            // ========== LÉGENDE ==========
            $this->info('🎨 Ajout de la légende...');

            $legendY = $height - 100;
            $image->text('LÉGENDE', $width/2, $legendY - 50, function ($font) {
                $font->size(16);
                $font->color('#000000');
                $font->align('center');
                $font->weight('bold');
            });

            $legendItems = [
                ['color' => $colors['start'], 'text' => 'Départ'],
                ['color' => $colors['process'], 'text' => 'Processus'],
                ['color' => $colors['decision'], 'text' => 'Décision'],
                ['color' => $colors['payment'], 'text' => 'Paiement'],
                ['color' => $colors['success'], 'text' => 'Succès'],
                ['color' => $colors['endpoint'], 'text' => 'Fin']
            ];

            $legendStartX = $width/2 - 300;
            foreach ($legendItems as $index => $item) {
                $x = $legendStartX + ($index * 100);
                
                // Carré de couleur
                $image->drawRectangle($x - 15, $legendY - 10, function ($draw) use ($item) {
                    $draw->background($item['color']);
                    $draw->border(2, '#000000');
                });
                
                // Texte
                $image->text($item['text'], $x + 25, $legendY, function ($font) {
                    $font->size(12);
                    $font->color('#000000');
                    $font->align('left');
                    $font->valign('middle');
                });
            }

            // Sauvegarder l'image
            $filename = public_path('storage/diagrammes_navigation_farmshop.png');
            $image->save($filename);

            $this->info("✅ Diagramme généré avec succès !");
            $this->info("📁 Fichier : {$filename}");
            $this->info("📊 Résolution : {$width}x{$height} pixels");
            $this->newLine();

            $this->comment("📦 ACHAT : Accueil → Catalogue → Produit → Panier → Connexion → Checkout → Paiement → Confirmation → Commandes");
            $this->comment("🚜 LOCATION : Catalogue → Filtrage → Équipement → Panier → Dates → Checkout → Paiement+Caution → Confirmation → Suivi → Retour");

        } catch (\Exception $e) {
            $this->error("❌ Erreur lors de la génération : " . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Dessine une boîte arrondie avec texte
     */
    private function drawRoundedBox($image, $x, $y, $width, $height, $text, $bgColor, $textColor = '#ffffff')
    {
        // Dessiner le rectangle
        $image->drawRectangle($x - $width/2, $y - $height/2, function ($draw) use ($bgColor, $width, $height) {
            $draw->background($bgColor);
            $draw->border(2, '#000000');
        });
        
        // Ajouter le texte
        $image->text($text, $x, $y, function ($font) use ($textColor) {
            $font->size(11);
            $font->color($textColor);
            $font->align('center');
            $font->valign('middle');
        });
    }

    /**
     * Dessine une flèche entre deux points
     */
    private function drawArrow($image, $startX, $startY, $endX, $endY)
    {
        // Dessiner la ligne
        $image->drawLine(function ($draw) use ($startX, $startY, $endX, $endY) {
            $draw->from($startX, $startY);
            $draw->to($endX, $endY);
            $draw->color('#666666');
            $draw->width(2);
        });
        
        // Dessiner la pointe de la flèche
        $angle = atan2($endY - $startY, $endX - $startX);
        $arrowLength = 8;
        $arrowAngle = pi() / 6;
        
        $x1 = $endX - $arrowLength * cos($angle - $arrowAngle);
        $y1 = $endY - $arrowLength * sin($angle - $arrowAngle);
        $x2 = $endX - $arrowLength * cos($angle + $arrowAngle);
        $y2 = $endY - $arrowLength * sin($angle + $arrowAngle);
        
        $image->drawLine(function ($draw) use ($endX, $endY, $x1, $y1) {
            $draw->from($endX, $endY);
            $draw->to($x1, $y1);
            $draw->color('#666666');
            $draw->width(2);
        });
        
        $image->drawLine(function ($draw) use ($endX, $endY, $x2, $y2) {
            $draw->from($endX, $endY);
            $draw->to($x2, $y2);
            $draw->color('#666666');
            $draw->width(2);
        });
    }
}
