<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class IrrigationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer la catégorie "Irrigation"
        $irrigationCategory = Category::where('name', 'Irrigation')->first();
        
        if (!$irrigationCategory) {
            $this->command->error('Catégorie "Irrigation" non trouvée. Veuillez d\'abord exécuter CategorySeeder.');
            return;
        }

        $irrigationProducts = [
            [
                'name' => 'Tuyau d\'Arrosage Résistant 25m Ø15mm',
                'description' => 'Tuyau d\'arrosage professionnel résistant aux UV et aux intempéries. Diamètre 15mm, longueur 25 mètres. Anti-vrille et anti-pli, pression maximale 20 bars. Idéal pour irrigation des parcelles maraîchères et serres.',
                'short_description' => 'Tuyau arrosage professionnel 25m, résistant UV',
                'sku' => 'IRRIGATION-TUYAU-25M-001',
                'price' => 45.90,
                'quantity' => 25,
                'unit_symbol' => 'pièce',
                'weight' => '3.5',
                'type' => 'sale',
                'category_id' => $irrigationCategory->id,
                'is_active' => true,
                'is_featured' => true,
                'critical_threshold' => 3,
                'low_stock_threshold' => 5,
                'out_of_stock_threshold' => 1,
                'meta_title' => 'Tuyau Arrosage 25m Professionnel - Irrigation Fermière',
                'meta_description' => 'Tuyau d\'arrosage professionnel 25m résistant UV. Anti-vrille, pression 20 bars pour irrigation maraîchère.',
                'meta_keywords' => 'tuyau, arrosage, 25m, professionnel, irrigation, UV'
            ],
            [
                'name' => 'Arrosoir Galvanisé Traditionnel 10L',
                'description' => 'Arrosoir traditionnel en acier galvanisé de 10 litres avec pomme d\'arrosage amovible. Fabrication artisanale française, robuste et durable. Anse renforcée, bec verseur précis. Parfait pour arrosage manuel des semis et plants délicats.',
                'short_description' => 'Arrosoir galvanisé 10L traditionnel français, pomme amovible',
                'sku' => 'IRRIGATION-ARROSOIR-10L-002',
                'price' => 32.50,
                'quantity' => 15,
                'unit_symbol' => 'pièce',
                'weight' => '1.8',
                'type' => 'sale',
                'category_id' => $irrigationCategory->id,
                'is_active' => true,
                'is_featured' => false,
                'critical_threshold' => 2,
                'low_stock_threshold' => 4,
                'out_of_stock_threshold' => 1,
                'meta_title' => 'Arrosoir Galvanisé 10L - Traditionnel Français',
                'meta_description' => 'Arrosoir traditionnel galvanisé 10L, fabrication française. Pomme amovible, idéal semis et plants délicats.',
                'meta_keywords' => 'arrosoir, galvanisé, 10L, traditionnel, français'
            ],
            [
                'name' => 'Système Micro-Irrigation Goutte à Goutte 50m',
                'description' => 'Kit complet de micro-irrigation goutte à goutte pour 50 mètres linéaires. Comprend tuyau micro-perforé, régulateurs de débit, raccords et support technique. Économie d\'eau 60%, irrigation précise au pied des plants.',
                'short_description' => 'Kit micro-irrigation goutte à goutte 50m, économie d\'eau',
                'sku' => 'IRRIGATION-MICROKIT-50M-003',
                'price' => 89.90,
                'quantity' => 12,
                'unit_symbol' => 'pièce',
                'weight' => '2.2',
                'type' => 'sale',
                'category_id' => $irrigationCategory->id,
                'is_active' => true,
                'is_featured' => true,
                'critical_threshold' => 1,
                'low_stock_threshold' => 3,
                'out_of_stock_threshold' => 1,
                'meta_title' => 'Kit Micro-Irrigation Goutte à Goutte 50m',
                'meta_description' => 'Système micro-irrigation goutte à goutte 50m. Économie d\'eau 60%, irrigation précise au pied des plants.',
                'meta_keywords' => 'micro-irrigation, goutte à goutte, 50m, économie, eau'
            ],
            [
                'name' => 'Asperseur Rotatif Longue Portée Fermier',
                'description' => 'Asperseur rotatif professionnel en bronze et laiton, portée 8-15 mètres selon pression. Rotation 360° réglable, jet uniforme. Construction robuste résistante aux intempéries. Idéal pour grandes surfaces maraîchères.',
                'short_description' => 'Asperseur rotatif bronze, portée 15m, rotation 360°',
                'sku' => 'IRRIGATION-ASPERSEUR-ROTATIF-004',
                'price' => 67.80,
                'quantity' => 8,
                'unit_symbol' => 'pièce',
                'weight' => '1.2',
                'type' => 'sale',
                'category_id' => $irrigationCategory->id,
                'is_active' => true,
                'is_featured' => false,
                'critical_threshold' => 1,
                'low_stock_threshold' => 2,
                'out_of_stock_threshold' => 1,
                'meta_title' => 'Asperseur Rotatif Bronze - Longue Portée 15m',
                'meta_description' => 'Asperseur rotatif professionnel bronze, portée 15m. Rotation 360° réglable pour grandes surfaces.',
                'meta_keywords' => 'asperseur, rotatif, bronze, 15m, rotation, professionnel'
            ],
            [
                'name' => 'Programmateur d\'Arrosage 4 Voies LCD',
                'description' => 'Programmateur d\'arrosage automatique 4 voies avec écran LCD. 8 programmes par voie, cycles personnalisables. Alimentation pile 9V, étanchéité IP65. Contrôle précis des horaires et durées d\'irrigation pour optimisation des cultures.',
                'short_description' => 'Programmateur 4 voies LCD, 8 programmes personnalisables',
                'sku' => 'IRRIGATION-PROGRAMMATEUR-4V-005',
                'price' => 125.00,
                'quantity' => 6,
                'unit_symbol' => 'pièce',
                'weight' => '0.8',
                'type' => 'sale',
                'category_id' => $irrigationCategory->id,
                'is_active' => true,
                'is_featured' => true,
                'critical_threshold' => 1,
                'low_stock_threshold' => 2,
                'out_of_stock_threshold' => 1,
                'meta_title' => 'Programmateur Arrosage 4 Voies LCD - Automatique',
                'meta_description' => 'Programmateur d\'arrosage 4 voies LCD, 8 programmes par voie. Contrôle précis irrigation automatique.',
                'meta_keywords' => 'programmateur, arrosage, 4 voies, LCD, automatique'
            ],
            [
                'name' => 'Enrouleur de Tuyau Métal sur Roues 60m',
                'description' => 'Enrouleur de tuyau métallique monté sur roues pour tuyau jusqu\'à 60 mètres. Structure en acier galvanisé, manivelle ergonomique. Déplacement facile dans les allées, protection du tuyau contre l\'usure. Indispensable pour grandes exploitations.',
                'short_description' => 'Enrouleur métal sur roues 60m, galvanisé mobile',
                'sku' => 'IRRIGATION-ENROULEUR-60M-006',
                'price' => 156.70,
                'quantity' => 4,
                'unit_symbol' => 'pièce',
                'weight' => '12.5',
                'type' => 'sale',
                'category_id' => $irrigationCategory->id,
                'is_active' => true,
                'is_featured' => false,
                'critical_threshold' => 1,
                'low_stock_threshold' => 2,
                'out_of_stock_threshold' => 1,
                'meta_title' => 'Enrouleur Tuyau Métal 60m sur Roues - Mobile',
                'meta_description' => 'Enrouleur métallique 60m sur roues, acier galvanisé. Déplacement facile, protection tuyau pour grandes exploitations.',
                'meta_keywords' => 'enrouleur, tuyau, 60m, roues, métal, galvanisé'
            ],
            [
                'name' => 'Lance d\'Arrosage Multijet Professionnelle',
                'description' => 'Lance d\'arrosage professionnelle multijet en aluminium avec 7 types de jets. Réglage progressif du débit, gâchette ergonomique avec blocage. Raccordement rapide, joints étanches. Parfaite pour tous travaux d\'arrosage de précision.',
                'short_description' => 'Lance multijet alu 7 positions, gâchette ergonomique',
                'sku' => 'IRRIGATION-LANCE-MULTIJET-007',
                'price' => 28.90,
                'quantity' => 20,
                'unit_symbol' => 'pièce',
                'weight' => '0.4',
                'type' => 'sale',
                'category_id' => $irrigationCategory->id,
                'is_active' => true,
                'is_featured' => true,
                'critical_threshold' => 2,
                'low_stock_threshold' => 5,
                'out_of_stock_threshold' => 1,
                'meta_title' => 'Lance Arrosage Multijet - 7 Positions Professionnelle',
                'meta_description' => 'Lance d\'arrosage multijet aluminium 7 positions. Gâchette ergonomique, arrosage de précision.',
                'meta_keywords' => 'lance, arrosage, multijet, 7 positions, aluminium'
            ],
            [
                'name' => 'Brumisateur Haute Pression pour Serres',
                'description' => 'Système de brumisation haute pression pour serres et tunnels. 10 buses inox réglables, pression 40 bars. Refroidissement et humidification optimaux. Kit complet avec raccords, supports et régulateur de pression intégré.',
                'short_description' => 'Brumisateur haute pression serres, 10 buses inox réglables',
                'sku' => 'IRRIGATION-BRUMISATEUR-HP-008',
                'price' => 189.50,
                'quantity' => 3,
                'unit_symbol' => 'pièce',
                'weight' => '3.8',
                'type' => 'sale',
                'category_id' => $irrigationCategory->id,
                'is_active' => true,
                'is_featured' => true,
                'critical_threshold' => 1,
                'low_stock_threshold' => 2,
                'out_of_stock_threshold' => 1,
                'meta_title' => 'Brumisateur Haute Pression Serres - 10 Buses Inox',
                'meta_description' => 'Système brumisation haute pression pour serres, 10 buses inox. Refroidissement et humidification optimaux.',
                'meta_keywords' => 'brumisateur, haute pression, serres, 10 buses, inox'
            ],
            [
                'name' => 'Cuve de Récupération d\'Eau 500L Vert',
                'description' => 'Cuve de récupération d\'eau de pluie 500 litres en polyéthylène alimentaire. Robinet de vidange intégré, couvercle sécurisé, traitement anti-UV. Idéale pour stockage d\'eau d\'irrigation écologique et économique.',
                'short_description' => 'Cuve récupération eau 500L, anti-UV avec robinet',
                'sku' => 'IRRIGATION-CUVE-500L-009',
                'price' => 245.00,
                'quantity' => 2,
                'unit_symbol' => 'pièce',
                'weight' => '25.0',
                'type' => 'sale',
                'category_id' => $irrigationCategory->id,
                'is_active' => true,
                'is_featured' => false,
                'critical_threshold' => 1,
                'low_stock_threshold' => 1,
                'out_of_stock_threshold' => 1,
                'meta_title' => 'Cuve Récupération Eau 500L - Anti-UV Écologique',
                'meta_description' => 'Cuve récupération eau pluie 500L polyéthylène alimentaire. Robinet intégré, traitement anti-UV.',
                'meta_keywords' => 'cuve, récupération, eau, 500L, anti-UV, écologique'
            ],
            [
                'name' => 'Pulvérisateur à Dos 16L Inox Professionnel',
                'description' => 'Pulvérisateur à dos professionnel 16 litres en inox 316L. Pompe haute pression, lance télescopique, bretelles ergonomiques rembourrées. Traitement phytosanitaire et fertilisation foliaire. Résistant aux produits chimiques.',
                'short_description' => 'Pulvérisateur à dos 16L inox, lance télescopique professionnel',
                'sku' => 'IRRIGATION-PULVERISATEUR-16L-010',
                'price' => 167.90,
                'quantity' => 5,
                'unit_symbol' => 'pièce',
                'weight' => '4.2',
                'type' => 'sale',
                'category_id' => $irrigationCategory->id,
                'is_active' => true,
                'is_featured' => true,
                'critical_threshold' => 1,
                'low_stock_threshold' => 2,
                'out_of_stock_threshold' => 1,
                'meta_title' => 'Pulvérisateur à Dos 16L Inox - Professionnel',
                'meta_description' => 'Pulvérisateur à dos 16L inox 316L professionnel. Lance télescopique, traitement phytosanitaire.',
                'meta_keywords' => 'pulvérisateur, dos, 16L, inox, professionnel, phytosanitaire'
            ],
        ];

        $created = 0;
        $skipped = 0;

        foreach ($irrigationProducts as $productData) {
            // Générer le slug
            $productData['slug'] = Str::slug($productData['name']);
            
            // Vérifier si le produit existe déjà
            $existing = Product::where('name', $productData['name'])
                              ->where('category_id', $irrigationCategory->id)
                              ->first();
            
            if (!$existing) {
                Product::create($productData);
                $created++;
                $this->command->info("✅ Créé: {$productData['name']} - {$productData['price']}€");
            } else {
                $skipped++;
                $this->command->warn("⚠️  Existe déjà: {$productData['name']}");
            }
        }

        $this->command->info("\n💧 === MATÉRIEL D'IRRIGATION AJOUTÉ ===");
        $this->command->info("📊 Résumé de l'opération:");
        $this->command->info("✅ {$created} nouveaux produits d'irrigation créés");
        $this->command->info("⚠️  {$skipped} produits déjà existants");
        
        if ($created > 0) {
            $this->command->info("\n🚿 Gamme complète d'irrigation fermière:");
            $this->command->info("• Arrosage manuel: Tuyaux, arrosoir galvanisé, lance multijet");
            $this->command->info("• Irrigation automatique: Micro-irrigation, asperseur rotatif, programmateur");
            $this->command->info("• Équipement mobile: Enrouleur sur roues, pulvérisateur à dos");
            $this->command->info("• Systèmes spécialisés: Brumisateur haute pression serres");
            $this->command->info("• Stockage: Cuve récupération eau 500L écologique");
            $this->command->info("💰 Prix de 28,90€ à 245,00€ selon complexité");
            $this->command->info("🏷️  Matériel professionnel résistant et durable");
            $this->command->info("💧 Solutions d'économie d'eau et irrigation précise");
        }
    }
}
