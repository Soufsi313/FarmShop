<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProtectionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer la catégorie "Protections"
        $protectionsCategory = Category::where('name', 'Protections')->first();
        
        if (!$protectionsCategory) {
            $this->command->error('Catégorie "Protections" non trouvée. Veuillez d\'abord exécuter ProtectionCategorySeeder.');
            return;
        }

        $protectionsProducts = [
            [
                'name' => 'Chaussures de Sécurité Agricoles S3 Cuir',
                'description' => 'Chaussures de sécurité agricoles S3 en cuir pleine fleur imperméable. Coque de protection composite, semelle anti-perforation kevlar. Résistantes aux hydrocarbures, antidérapantes. Confort optimal pour longues journées de travail.',
                'short_description' => 'Chaussures sécurité S3 cuir, coque composite agricole',
                'sku' => 'EPI-CHAUSSURES-S3-001',
                'price' => 89.90,
                'quantity' => 20,
                'unit_symbol' => 'pièce',
                'weight' => '1.5',
                'type' => 'sale',
                'category_id' => $protectionsCategory->id,
                'is_active' => true,
                'is_featured' => true,
                'critical_threshold' => 2,
                'low_stock_threshold' => 5,
                'out_of_stock_threshold' => 1,
                'meta_title' => 'Chaussures Sécurité Agricoles S3 Cuir - Protection EPI',
                'meta_description' => 'Chaussures de sécurité agricoles S3 cuir, coque composite. Résistantes et confortables pour agriculteurs.',
                'meta_keywords' => 'chaussures, sécurité, S3, cuir, agricole, EPI'
            ],
            [
                'name' => 'Pantalon de Travail Renforcé Multi-Poches',
                'description' => 'Pantalon de travail agricole en toile coton renforcée 300g/m². Genoux préformés avec renforts Cordura, multiples poches cargo. Ceinture élastiquée, coutures renforcées. Résistant déchirures et lavages répétés.',
                'short_description' => 'Pantalon travail renforcé multi-poches, genoux Cordura',
                'sku' => 'EPI-PANTALON-RENFORCE-002',
                'price' => 67.50,
                'quantity' => 25,
                'unit_symbol' => 'pièce',
                'weight' => '0.8',
                'type' => 'sale',
                'category_id' => $protectionsCategory->id,
                'is_active' => true,
                'is_featured' => false,
                'critical_threshold' => 3,
                'low_stock_threshold' => 6,
                'out_of_stock_threshold' => 2,
                'meta_title' => 'Pantalon Travail Agricole Renforcé - Multi-Poches',
                'meta_description' => 'Pantalon de travail agricole renforcé Cordura, multi-poches. Résistant et confortable pour agriculteurs.',
                'meta_keywords' => 'pantalon, travail, renforcé, cordura, agricole'
            ],
            [
                'name' => 'Lunettes de Protection Anti-Projection',
                'description' => 'Lunettes de protection avec verres polycarbonate anti-rayures et anti-buée. Protection latérale intégrée, branches ajustables. Filtre UV 99%, résistant aux projections de liquides et particules. Confort prolongé.',
                'short_description' => 'Lunettes protection polycarbonate, anti-buée UV',
                'sku' => 'EPI-LUNETTES-PROTECTION-003',
                'price' => 18.90,
                'quantity' => 40,
                'unit_symbol' => 'pièce',
                'weight' => '0.1',
                'type' => 'sale',
                'category_id' => $protectionsCategory->id,
                'is_active' => true,
                'is_featured' => true,
                'critical_threshold' => 4,
                'low_stock_threshold' => 10,
                'out_of_stock_threshold' => 3,
                'meta_title' => 'Lunettes Protection Anti-Projection - UV Agricole',
                'meta_description' => 'Lunettes de protection polycarbonate anti-buée, filtre UV 99%. Protection projections agricoles.',
                'meta_keywords' => 'lunettes, protection, polycarbonate, UV, agricole'
            ],
            [
                'name' => 'Gants de Travail Cuir Nitrile Grip',
                'description' => 'Gants de travail en cuir fleur de bovin avec paume enduite nitrile. Excellente préhension en milieu humide, résistance à l\'abrasion. Manchette sécurisée, confort et dextérité optimaux. Lavables en machine.',
                'short_description' => 'Gants cuir nitrile grip, préhension humide agricole',
                'sku' => 'EPI-GANTS-CUIR-NITRILE-004',
                'price' => 12.80,
                'quantity' => 50,
                'unit_symbol' => 'pièce',
                'weight' => '0.2',
                'type' => 'sale',
                'category_id' => $protectionsCategory->id,
                'is_active' => true,
                'is_featured' => false,
                'critical_threshold' => 5,
                'low_stock_threshold' => 12,
                'out_of_stock_threshold' => 3,
                'meta_title' => 'Gants Travail Cuir Nitrile - Grip Agricole',
                'meta_description' => 'Gants de travail cuir nitrile, excellente préhension humide. Résistants et confortables pour agriculteurs.',
                'meta_keywords' => 'gants, cuir, nitrile, grip, travail, agricole'
            ],
            [
                'name' => 'Casque de Protection Forestier Complet',
                'description' => 'Casque de protection forestier avec visière grillagée et protège-oreilles intégrés. Coque ABS haute résistance, harnais 6 points ajustable. Protection complète tête, visage, ouïe. Homologué EN 397.',
                'short_description' => 'Casque forestier complet visière + protège-oreilles',
                'sku' => 'EPI-CASQUE-FORESTIER-005',
                'price' => 78.90,
                'quantity' => 15,
                'unit_symbol' => 'pièce',
                'weight' => '0.6',
                'type' => 'sale',
                'category_id' => $protectionsCategory->id,
                'is_active' => true,
                'is_featured' => true,
                'critical_threshold' => 2,
                'low_stock_threshold' => 4,
                'out_of_stock_threshold' => 1,
                'meta_title' => 'Casque Protection Forestier - Visière Protège-Oreilles',
                'meta_description' => 'Casque de protection forestier complet ABS, visière grillagée et protège-oreilles. Homologué EN 397.',
                'meta_keywords' => 'casque, forestier, protection, visière, oreilles'
            ],
            [
                'name' => 'Combinaison de Protection Chimique Type 3',
                'description' => 'Combinaison de protection chimique Type 3 à usage limité. Protection contre projections de produits chimiques liquides. Coutures étanches, fermeture étanche, élastiques poignets/chevilles. Respirante et légère.',
                'short_description' => 'Combinaison protection chimique Type 3, étanche',
                'sku' => 'EPI-COMBINAISON-CHIMIQUE-006',
                'price' => 34.50,
                'quantity' => 30,
                'unit_symbol' => 'pièce',
                'weight' => '0.4',
                'type' => 'sale',
                'category_id' => $protectionsCategory->id,
                'is_active' => true,
                'is_featured' => false,
                'critical_threshold' => 3,
                'low_stock_threshold' => 8,
                'out_of_stock_threshold' => 2,
                'meta_title' => 'Combinaison Protection Chimique Type 3 - Étanche',
                'meta_description' => 'Combinaison protection chimique Type 3, coutures étanches. Protection projections produits phytosanitaires.',
                'meta_keywords' => 'combinaison, protection, chimique, type 3, étanche'
            ],
            [
                'name' => 'Bottes Néoprène Agriculture Haute Qualité',
                'description' => 'Bottes en néoprène 4mm spécial agriculture, hauteur 40cm. Semelle crantée antidérapante, doublure textile absorbante. Résistantes produits chimiques, hydrocarbures. Confort thermique optimal par tous temps.',
                'short_description' => 'Bottes néoprène 40cm agriculture, semelle crantée',
                'sku' => 'EPI-BOTTES-NEOPRENE-007',
                'price' => 45.60,
                'quantity' => 18,
                'unit_symbol' => 'pièce',
                'weight' => '1.8',
                'type' => 'sale',
                'category_id' => $protectionsCategory->id,
                'is_active' => true,
                'is_featured' => true,
                'critical_threshold' => 2,
                'low_stock_threshold' => 4,
                'out_of_stock_threshold' => 1,
                'meta_title' => 'Bottes Néoprène Agriculture 40cm - Antidérapantes',
                'meta_description' => 'Bottes néoprène 4mm agriculture, hauteur 40cm. Résistantes chimiques, semelle crantée antidérapante.',
                'meta_keywords' => 'bottes, néoprène, agriculture, 40cm, antidérapantes'
            ],
            [
                'name' => 'Masque Respiratoire FFP2 Agricole Réutilisable',
                'description' => 'Masque respiratoire FFP2 réutilisable avec filtres remplaçables. Protection contre poussières fines, pollens, particules. Soupape d\'expiration, harnais ajustable confortable. Homologué EN 149, usage agricole.',
                'short_description' => 'Masque FFP2 réutilisable filtres, soupape expiration',
                'sku' => 'EPI-MASQUE-FFP2-008',
                'price' => 24.90,
                'quantity' => 35,
                'unit_symbol' => 'pièce',
                'weight' => '0.3',
                'type' => 'sale',
                'category_id' => $protectionsCategory->id,
                'is_active' => true,
                'is_featured' => false,
                'critical_threshold' => 4,
                'low_stock_threshold' => 8,
                'out_of_stock_threshold' => 2,
                'meta_title' => 'Masque Respiratoire FFP2 Agricole - Réutilisable',
                'meta_description' => 'Masque respiratoire FFP2 réutilisable, filtres remplaçables. Protection poussières agricoles, homologué EN 149.',
                'meta_keywords' => 'masque, FFP2, respiratoire, réutilisable, agricole'
            ],
            [
                'name' => 'Veste Haute Visibilité Classe 3 Agriculture',
                'description' => 'Veste haute visibilité classe 3 spécial agriculture. Bandes rétroréfléchissantes 3M, tissu polyester respirant. Multi-poches fonctionnelles, fermeture zip, col montant. Lavable industriellement, homologuée EN ISO 20471.',
                'short_description' => 'Veste haute visibilité classe 3, bandes 3M agriculture',
                'sku' => 'EPI-VESTE-HAUTE-VISIBILITE-009',
                'price' => 52.30,
                'quantity' => 22,
                'unit_symbol' => 'pièce',
                'weight' => '0.6',
                'type' => 'sale',
                'category_id' => $protectionsCategory->id,
                'is_active' => true,
                'is_featured' => true,
                'critical_threshold' => 2,
                'low_stock_threshold' => 5,
                'out_of_stock_threshold' => 1,
                'meta_title' => 'Veste Haute Visibilité Classe 3 - Agriculture 3M',
                'meta_description' => 'Veste haute visibilité classe 3 agriculture, bandes 3M. Multi-poches, homologuée EN ISO 20471.',
                'meta_keywords' => 'veste, haute visibilité, classe 3, 3M, agriculture'
            ],
            [
                'name' => 'Genouillères Professionnelles Gel Ergonomiques',
                'description' => 'Genouillères professionnelles avec coussin gel ergonomique. Sangles ajustables antidérapantes, protection rotule renforcée. Idéales travaux à genoux prolongés : plantation, récolte, entretien. Lavables et réutilisables.',
                'short_description' => 'Genouillères gel ergonomiques, sangles ajustables pro',
                'sku' => 'EPI-GENOUILLERES-GEL-010',
                'price' => 28.70,
                'quantity' => 25,
                'unit_symbol' => 'pièce',
                'weight' => '0.4',
                'type' => 'sale',
                'category_id' => $protectionsCategory->id,
                'is_active' => true,
                'is_featured' => false,
                'critical_threshold' => 3,
                'low_stock_threshold' => 6,
                'out_of_stock_threshold' => 2,
                'meta_title' => 'Genouillères Gel Ergonomiques - Protection Agricole',
                'meta_description' => 'Genouillères professionnelles gel ergonomique, sangles ajustables. Idéales travaux plantation et récolte.',
                'meta_keywords' => 'genouillères, gel, ergonomiques, plantation, récolte'
            ],
        ];

        $created = 0;
        $skipped = 0;

        foreach ($protectionsProducts as $productData) {
            // Générer le slug
            $productData['slug'] = Str::slug($productData['name']);
            
            // Vérifier si le produit existe déjà
            $existing = Product::where('name', $productData['name'])
                              ->where('category_id', $protectionsCategory->id)
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

        $this->command->info("\n🦺 === ÉQUIPEMENTS DE PROTECTION INDIVIDUELLE (EPI) AJOUTÉS ===");
        $this->command->info("📊 Résumé de l'opération:");
        $this->command->info("✅ {$created} nouveaux EPI créés");
        $this->command->info("⚠️  {$skipped} produits déjà existants");
        
        if ($created > 0) {
            $this->command->info("\n👷 Gamme complète d'EPI agricoles:");
            $this->command->info("• Protection pieds: Chaussures sécurité S3, bottes néoprène");
            $this->command->info("• Vêtements: Pantalon renforcé, veste haute visibilité, combinaison chimique");
            $this->command->info("• Protection tête: Casque forestier complet, lunettes anti-projection");
            $this->command->info("• Protection respiratoire: Masque FFP2 réutilisable");
            $this->command->info("• Protection mains/genoux: Gants cuir nitrile, genouillères gel");
            $this->command->info("💰 Prix de 12,80€ à 89,90€ selon type d'EPI");
            $this->command->info("🏷️  Équipements homologués et certifiés CE");
            $this->command->info("👨‍� Spécialement conçus pour agriculteurs professionnels");
        }
    }
}
