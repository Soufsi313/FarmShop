<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CustomProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createFruits();
        $this->createVegetables();
        $this->createDairyProducts();
        $this->createNonFoodProducts();
        $this->createCereals();
        $this->createSeeds();
        $this->createFertilizers();
        $this->createStarchyFoods();
        $this->createIrrigation();
        $this->createMachines();
        $this->createProtection();
    }

    private function createFruits()
    {
        $this->command->info('🍎 Création des fruits...');
        
        // Récupérer la catégorie fruits
        $fruitCategory = Category::where('name', 'LIKE', '%Fruit%')->first();
        if (!$fruitCategory) {
            $fruitCategory = Category::create([
                'name' => 'Fruits',
                'slug' => 'fruits',
                'description' => 'Fruits frais et biologiques',
                'is_active' => true,
            ]);
        }

        $fruits = [
            // Pommes
            [
                'name' => 'Pommes Rouges Royal Gala',
                'description' => 'Pommes rouges Royal Gala, croquantes et sucrées. Issues de l\'agriculture biologique belge.',
                'short_description' => 'Pommes rouges Royal Gala biologiques',
                'price' => 2.50,
            ],
            [
                'name' => 'Pommes Vertes Granny Smith',
                'description' => 'Pommes vertes Granny Smith, acidulées et croquantes. Parfaites pour les tartes et la consommation.',
                'short_description' => 'Pommes vertes Granny Smith biologiques',
                'price' => 2.60,
            ],
            [
                'name' => 'Pommes Jaunes Golden Delicious',
                'description' => 'Pommes jaunes Golden Delicious, douces et parfumées. Idéales pour toute la famille.',
                'short_description' => 'Pommes jaunes Golden Delicious biologiques',
                'price' => 2.45,
            ],
            [
                'name' => 'Pommes Rouges Red Delicious',
                'description' => 'Pommes rouges Red Delicious, sucrées et juteuses. Variété classique appréciée.',
                'short_description' => 'Pommes rouges Red Delicious biologiques',
                'price' => 2.55,
            ],
            [
                'name' => 'Pommes Jonagold',
                'description' => 'Pommes Jonagold, équilibre parfait entre sucré et acidulé. Excellente conservation.',
                'short_description' => 'Pommes Jonagold biologiques',
                'price' => 2.70,
            ],

            // Poires
            [
                'name' => 'Poires Williams',
                'description' => 'Poires Williams, fondantes et parfumées. Chair fine et juteuse, idéales fraîches.',
                'short_description' => 'Poires Williams biologiques',
                'price' => 3.20,
            ],
            [
                'name' => 'Poires Conference',
                'description' => 'Poires Conference, allongées et sucrées. Variété belge traditionnelle de qualité.',
                'short_description' => 'Poires Conference biologiques',
                'price' => 3.10,
            ],
            [
                'name' => 'Poires Doyenné du Comice',
                'description' => 'Poires Doyenné du Comice, chair fondante et parfum délicat. Variété premium.',
                'short_description' => 'Poires Doyenné du Comice biologiques',
                'price' => 3.50,
            ],

            // Prunes
            [
                'name' => 'Prunes Reines-Claudes',
                'description' => 'Prunes Reines-Claudes vertes, sucrées et parfumées. Chair fondante et délicieuse.',
                'short_description' => 'Prunes Reines-Claudes biologiques',
                'price' => 4.20,
            ],
            [
                'name' => 'Prunes Quetsches',
                'description' => 'Prunes Quetsches violettes, fermes et sucrées. Parfaites pour tartes et confitures.',
                'short_description' => 'Prunes Quetsches biologiques',
                'price' => 3.80,
            ],
            [
                'name' => 'Prunes Mirabelles',
                'description' => 'Prunes Mirabelles jaunes, petites et très parfumées. Douceur exceptionnelle.',
                'short_description' => 'Prunes Mirabelles biologiques',
                'price' => 4.50,
            ],

            // Pêches
            [
                'name' => 'Pêches Jaunes',
                'description' => 'Pêches jaunes juteuses et parfumées. Chair fondante et goût authentique.',
                'short_description' => 'Pêches jaunes biologiques',
                'price' => 3.90,
            ],
            [
                'name' => 'Pêches Blanches',
                'description' => 'Pêches blanches délicates et sucrées. Variété fine et parfumée.',
                'short_description' => 'Pêches blanches biologiques',
                'price' => 4.10,
            ],
            [
                'name' => 'Pêches de Vigne',
                'description' => 'Pêches de vigne rouges, chair rouge et goût intense. Variété ancienne authentique.',
                'short_description' => 'Pêches de vigne biologiques',
                'price' => 4.80,
            ],

            // Kiwis
            [
                'name' => 'Kiwis Verts Hayward',
                'description' => 'Kiwis verts Hayward, riches en vitamine C. Chair verte acidulée et rafraîchissante.',
                'short_description' => 'Kiwis verts Hayward biologiques',
                'price' => 5.20,
            ],
            [
                'name' => 'Kiwis Jaunes Gold',
                'description' => 'Kiwis jaunes Gold, plus doux que les verts. Chair dorée et goût tropical.',
                'short_description' => 'Kiwis jaunes Gold biologiques',
                'price' => 6.50,
            ],

            // Abricots
            [
                'name' => 'Abricots Bergeron',
                'description' => 'Abricots Bergeron, gros et savoureux. Chair ferme et parfum délicat.',
                'short_description' => 'Abricots Bergeron biologiques',
                'price' => 4.60,
            ],
            [
                'name' => 'Abricots Rouge du Roussillon',
                'description' => 'Abricots Rouge du Roussillon, petits et concentrés en saveur. Variété traditionnelle.',
                'short_description' => 'Abricots Rouge du Roussillon biologiques',
                'price' => 5.10,
            ],

            // Bananes
            [
                'name' => 'Bananes Cavendish',
                'description' => 'Bananes Cavendish équitables, douces et crémeuses. Source naturelle de potassium.',
                'short_description' => 'Bananes Cavendish équitables',
                'price' => 2.80,
            ],
            [
                'name' => 'Bananes Plantain',
                'description' => 'Bananes Plantain à cuire, riches en amidon. Parfaites pour plats exotiques.',
                'short_description' => 'Bananes Plantain équitables',
                'price' => 3.20,
            ],

            // Raisins
            [
                'name' => 'Raisins Blancs Chasselas',
                'description' => 'Raisins blancs Chasselas, grains fins et sucrés. Variété de table appréciée.',
                'short_description' => 'Raisins blancs Chasselas biologiques',
                'price' => 4.90,
            ],
            [
                'name' => 'Raisins Noirs Muscat',
                'description' => 'Raisins noirs Muscat, parfumés et juteux. Goût musqué caractéristique.',
                'short_description' => 'Raisins noirs Muscat biologiques',
                'price' => 5.20,
            ],
            [
                'name' => 'Raisins Rouges Red Globe',
                'description' => 'Raisins rouges Red Globe, gros grains croquants. Excellente conservation.',
                'short_description' => 'Raisins rouges Red Globe biologiques',
                'price' => 4.70,
            ],

            // Autres fruits
            [
                'name' => 'Cerises Burlat',
                'description' => 'Cerises Burlat rouges, précoces et sucrées. Chair ferme et juteuse.',
                'short_description' => 'Cerises Burlat biologiques',
                'price' => 8.50,
            ],
            [
                'name' => 'Fraises Gariguette',
                'description' => 'Fraises Gariguette allongées, parfumées et sucrées. Variété française premium.',
                'short_description' => 'Fraises Gariguette biologiques',
                'price' => 6.80,
            ],
            [
                'name' => 'Framboises',
                'description' => 'Framboises rouges fraîches, délicates et parfumées. Riches en antioxydants.',
                'short_description' => 'Framboises biologiques',
                'price' => 9.50,
            ],
            [
                'name' => 'Myrtilles',
                'description' => 'Myrtilles sauvages, petites et concentrées en saveur. Super-fruit antioxydant.',
                'short_description' => 'Myrtilles sauvages biologiques',
                'price' => 12.80,
            ],
            [
                'name' => 'Mûres',
                'description' => 'Mûres noires sauvages, juteuses et parfumées. Cueillette traditionnelle.',
                'short_description' => 'Mûres sauvages biologiques',
                'price' => 8.90,
            ],
        ];

        foreach ($fruits as $fruitData) {
            $this->createProduct($fruitData, $fruitCategory, 500, 50);
        }

        $this->command->info("   ✅ " . count($fruits) . " fruits créés (500 unités chacun, seuil critique: 50)");
    }

    private function createVegetables()
    {
        $this->command->info('🥕 Création des légumes...');
        
        // Récupérer la catégorie légumes
        $vegetableCategory = Category::where('name', 'LIKE', '%Légume%')->first();
        if (!$vegetableCategory) {
            $vegetableCategory = Category::create([
                'name' => 'Légumes',
                'slug' => 'legumes',
                'description' => 'Légumes frais et biologiques',
                'is_active' => true,
            ]);
        }

        $vegetables = [
            [
                'name' => 'Carottes Bio',
                'description' => 'Carottes biologiques croquantes et sucrées, riches en bêta-carotène.',
                'short_description' => 'Carottes biologiques fraîches',
                'price' => 1.80,
            ],
            [
                'name' => 'Pommes de Terre Bintje',
                'description' => 'Pommes de terre Bintje, parfaites pour frites et purées. Variété belge traditionnelle.',
                'short_description' => 'Pommes de terre Bintje belges',
                'price' => 1.20,
            ],
            [
                'name' => 'Tomates Cerises',
                'description' => 'Tomates cerises sucrées et parfumées, cultivées sous serre belge.',
                'short_description' => 'Tomates cerises biologiques',
                'price' => 4.50,
            ],
            [
                'name' => 'Salade Iceberg',
                'description' => 'Salade Iceberg croquante et rafraîchissante, parfaite pour été.',
                'short_description' => 'Salade Iceberg fraîche',
                'price' => 1.50,
            ],
            [
                'name' => 'Courgettes Vertes',
                'description' => 'Courgettes vertes tendres, idéales grillées ou en ratatouille.',
                'short_description' => 'Courgettes vertes biologiques',
                'price' => 2.20,
            ],
            [
                'name' => 'Brocolis',
                'description' => 'Brocolis verts riches en vitamines, parfaits vapeur ou sautés.',
                'short_description' => 'Brocolis biologiques',
                'price' => 2.90,
            ],
            [
                'name' => 'Choux-fleurs',
                'description' => 'Choux-fleurs blancs et fermes, excellents gratinés ou en soupe.',
                'short_description' => 'Choux-fleurs biologiques',
                'price' => 2.70,
            ],
            [
                'name' => 'Épinards Frais',
                'description' => 'Épinards frais et tendres, riches en fer et vitamines.',
                'short_description' => 'Épinards frais biologiques',
                'price' => 3.20,
            ],
            [
                'name' => 'Haricots Verts',
                'description' => 'Haricots verts fins et croquants, cueillis à maturité optimale.',
                'short_description' => 'Haricots verts biologiques',
                'price' => 3.80,
            ],
            [
                'name' => 'Poireaux',
                'description' => 'Poireaux blancs et verts, base parfaite pour soupes et pot-au-feu.',
                'short_description' => 'Poireaux biologiques',
                'price' => 2.10,
            ],
        ];

        foreach ($vegetables as $vegetableData) {
            $this->createProduct($vegetableData, $vegetableCategory, 500, 50);
        }

        $this->command->info("   ✅ " . count($vegetables) . " légumes créés (500 unités chacun, seuil critique: 50)");
    }

    private function createDairyProducts()
    {
        $this->command->info('🥛 Création des produits laitiers...');
        
        // Récupérer la catégorie produits laitiers
        $dairyCategory = Category::where('name', 'LIKE', '%Laitier%')->first();
        if (!$dairyCategory) {
            $dairyCategory = Category::create([
                'name' => 'Produits Laitiers',
                'slug' => 'produits-laitiers',
                'description' => 'Produits laitiers frais et œufs',
                'is_active' => true,
            ]);
        }

        $dairyProducts = [
            [
                'name' => 'Œufs Fermiers Bio (x6)',
                'description' => 'Œufs fermiers biologiques, poules élevées au sol. Boîte de 6 œufs.',
                'short_description' => 'Œufs fermiers bio x6',
                'price' => 3.50,
            ],
            [
                'name' => 'Œufs Fermiers Bio (x12)',
                'description' => 'Œufs fermiers biologiques, poules élevées au sol. Boîte de 12 œufs.',
                'short_description' => 'Œufs fermiers bio x12',
                'price' => 6.80,
            ],
            [
                'name' => 'Lait Entier Bio 1L',
                'description' => 'Lait entier biologique de vaches belges, riche et crémeux.',
                'short_description' => 'Lait entier bio 1L',
                'price' => 1.80,
            ],
            [
                'name' => 'Lait Demi-Écrémé Bio 1L',
                'description' => 'Lait demi-écrémé biologique, équilibre parfait goût et légèreté.',
                'short_description' => 'Lait demi-écrémé bio 1L',
                'price' => 1.75,
            ],
            [
                'name' => 'Beurre Fermier Bio 250g',
                'description' => 'Beurre fermier biologique baratte traditionellement, goût authentique.',
                'short_description' => 'Beurre fermier bio 250g',
                'price' => 4.20,
            ],
            [
                'name' => 'Yaourt Nature Bio (x4)',
                'description' => 'Yaourts nature biologiques, texture crémeuse et goût authentique.',
                'short_description' => 'Yaourt nature bio x4',
                'price' => 2.90,
            ],
            [
                'name' => 'Fromage Blanc Bio 500g',
                'description' => 'Fromage blanc biologique, onctueux et riche en protéines.',
                'short_description' => 'Fromage blanc bio 500g',
                'price' => 3.40,
            ],
            [
                'name' => 'Crème Fraîche Bio 200ml',
                'description' => 'Crème fraîche biologique épaisse, parfaite pour cuisine et desserts.',
                'short_description' => 'Crème fraîche bio 200ml',
                'price' => 2.60,
            ],
        ];

        foreach ($dairyProducts as $dairyData) {
            $this->createProduct($dairyData, $dairyCategory, 50, 10);
        }

        $this->command->info("   ✅ " . count($dairyProducts) . " produits laitiers créés (50 unités chacun, seuil critique: 10)");
    }

    private function createNonFoodProducts()
    {
        $this->command->info('🔧 Création des produits non alimentaires...');
        
        // Récupérer/créer la catégorie outils agricoles
        $toolsCategory = Category::where('name', 'LIKE', '%Outil%')->first();
        if (!$toolsCategory) {
            $toolsCategory = Category::create([
                'name' => 'Outils Agricoles',
                'slug' => 'outils-agricoles',
                'description' => 'Outils et matériel agricole',
                'is_active' => true,
            ]);
        }

        $nonFoodProducts = [
            [
                'name' => 'Bêche Inox Manche Bois',
                'description' => 'Bêche en inox avec manche en bois, robuste et durable pour tous travaux de terre.',
                'short_description' => 'Bêche inox manche bois',
                'price' => 35.90,
            ],
            [
                'name' => 'Sécateur Professionnel',
                'description' => 'Sécateur professionnel lames forgées, parfait pour taille précise arbres et arbustes.',
                'short_description' => 'Sécateur professionnel',
                'price' => 45.50,
            ],
            [
                'name' => 'Arrosoir Galvanisé 10L',
                'description' => 'Arrosoir en métal galvanisé 10 litres, traditionnel et résistant.',
                'short_description' => 'Arrosoir galvanisé 10L',
                'price' => 28.90,
            ],
            [
                'name' => 'Gants Jardinage Cuir',
                'description' => 'Gants de jardinage en cuir véritable, protection optimale contre épines et ronces.',
                'short_description' => 'Gants jardinage cuir',
                'price' => 12.50,
            ],
            [
                'name' => 'Binette Manche Court',
                'description' => 'Binette manche court pour désherbage et aération du sol, maniable et efficace.',
                'short_description' => 'Binette manche court',
                'price' => 18.70,
            ],
            [
                'name' => 'Râteau 14 Dents',
                'description' => 'Râteau 14 dents en acier, parfait pour niveler et rassembler feuilles et débris.',
                'short_description' => 'Râteau 14 dents acier',
                'price' => 24.80,
            ],
            [
                'name' => 'Transplantoir Inox',
                'description' => 'Transplantoir en inox pour plantations précises, indispensable pour jardinier.',
                'short_description' => 'Transplantoir inox',
                'price' => 15.90,
            ],
            [
                'name' => 'Serfouette 2 Dents',
                'description' => 'Serfouette 2 dents pour binage et désherbage, outil polyvalent essentiel.',
                'short_description' => 'Serfouette 2 dents',
                'price' => 22.30,
            ],
        ];

        foreach ($nonFoodProducts as $productData) {
            $this->createProduct($productData, $toolsCategory, 25, 5);
        }

        $this->command->info("   ✅ " . count($nonFoodProducts) . " produits non alimentaires créés (25 unités chacun, seuil critique: 5)");
    }

    private function createCereals()
    {
        $this->command->info('🌾 Création des céréales...');
        
        // Récupérer ou créer la catégorie céréales
        $cerealCategory = Category::where('name', 'LIKE', '%Céréale%')->first();
        if (!$cerealCategory) {
            $cerealCategory = Category::create([
                'name' => 'Céréales',
                'slug' => 'cereales',
                'description' => 'Céréales biologiques et traditionnelles',
                'is_active' => true,
            ]);
        }

        $cereals = [
            [
                'name' => 'Avoine Bio 25kg',
                'description' => 'Avoine biologique de haute qualité, riche en fibres et nutriments. Idéale pour l\'alimentation animale et humaine.',
                'short_description' => 'Avoine biologique 25kg',
                'price' => 35.00,
            ],
            [
                'name' => 'Orge Perlé Bio 20kg',
                'description' => 'Orge perlé biologique, parfait pour les soupes, salades et l\'alimentation du bétail.',
                'short_description' => 'Orge perlé biologique 20kg',
                'price' => 28.50,
            ],
            [
                'name' => 'Blé Tendre Bio 25kg',
                'description' => 'Blé tendre biologique de première qualité, idéal pour la panification et l\'alimentation.',
                'short_description' => 'Blé tendre biologique 25kg',
                'price' => 32.00,
            ],
            [
                'name' => 'Maïs Grain Bio 30kg',
                'description' => 'Maïs grain biologique, excellent pour l\'alimentation animale et la transformation.',
                'short_description' => 'Maïs grain biologique 30kg',
                'price' => 29.00,
            ],
        ];

        foreach ($cereals as $cereal) {
            $this->createProduct($cereal, $cerealCategory, 100, 15); // Stock 100, seuil critique 15
        }

        $this->command->info("   ✅ " . count($cereals) . " céréales créées (100 unités chacune, seuil critique: 15)");
    }

    private function createSeeds()
    {
        $this->command->info('🌱 Création des semences...');
        
        // Récupérer ou créer la catégorie semences
        $seedCategory = Category::where('name', 'LIKE', '%Semence%')->first();
        if (!$seedCategory) {
            $seedCategory = Category::create([
                'name' => 'Semences',
                'slug' => 'semences',
                'description' => 'Semences biologiques et certifiées',
                'is_active' => true,
            ]);
        }

        $seeds = [
            [
                'name' => 'Graines de Radis Bio',
                'description' => 'Graines de radis biologiques, variété rouge ronde. Germination rapide, parfait pour débutants.',
                'short_description' => 'Graines de radis bio 100g',
                'price' => 8.50,
            ],
            [
                'name' => 'Graines de Carottes Bio',
                'description' => 'Graines de carottes biologiques, variété Nantaise. Production de carottes sucrées et croquantes.',
                'short_description' => 'Graines de carottes bio 50g',
                'price' => 12.00,
            ],
            [
                'name' => 'Graines de Tournesol Bio',
                'description' => 'Graines de tournesol biologiques pour plantation. Croissance rapide et fleurs spectaculaires.',
                'short_description' => 'Graines de tournesol bio 200g',
                'price' => 15.50,
            ],
            [
                'name' => 'Graines de Salade Bio Mix',
                'description' => 'Mélange de graines de salade biologiques : laitue, roquette, épinards. Récolte étalée.',
                'short_description' => 'Mix graines salade bio 75g',
                'price' => 18.00,
            ],
        ];

        foreach ($seeds as $seed) {
            $this->createProduct($seed, $seedCategory, 200, 20); // Stock 200, seuil critique 20
        }

        $this->command->info("   ✅ " . count($seeds) . " semences créées (200 unités chacune, seuil critique: 20)");
    }

    private function createFertilizers()
    {
        $this->command->info('🧪 Création des engrais...');
        
        // Récupérer ou créer la catégorie engrais
        $fertilizerCategory = Category::where('name', 'LIKE', '%Engrais%')->first();
        if (!$fertilizerCategory) {
            $fertilizerCategory = Category::create([
                'name' => 'Engrais',
                'slug' => 'engrais',
                'description' => 'Engrais biologiques et naturels',
                'is_active' => true,
            ]);
        }

        $fertilizers = [
            [
                'name' => 'Compost Bio 40L',
                'description' => 'Compost biologique enrichi, 100% naturel. Améliore la structure du sol et nourrit les plantes.',
                'short_description' => 'Compost biologique 40L',
                'price' => 24.00,
            ],
            [
                'name' => 'Fumier de Cheval 25kg',
                'description' => 'Fumier de cheval composté, riche en matière organique. Excellent amendement pour tous types de sols.',
                'short_description' => 'Fumier de cheval composté 25kg',
                'price' => 18.50,
            ],
            [
                'name' => 'Engrais Liquide Bio 5L',
                'description' => 'Engrais liquide biologique concentré, riche en NPK. Utilisation facile et résultats rapides.',
                'short_description' => 'Engrais liquide bio 5L',
                'price' => 32.00,
            ],
            [
                'name' => 'Corne Broyée Bio 10kg',
                'description' => 'Corne broyée biologique, engrais azoté à libération lente. Idéal pour les légumes feuilles.',
                'short_description' => 'Corne broyée bio 10kg',
                'price' => 45.00,
            ],
        ];

        foreach ($fertilizers as $fertilizer) {
            $this->createProduct($fertilizer, $fertilizerCategory, 75, 10); // Stock 75, seuil critique 10
        }

        $this->command->info("   ✅ " . count($fertilizers) . " engrais créés (75 unités chacun, seuil critique: 10)");
    }

    private function createStarchyFoods()
    {
        $this->command->info('🥔 Création des féculents...');
        
        // Récupérer ou créer la catégorie féculents
        $starchyCategory = Category::where('name', 'LIKE', '%Féculent%')->first();
        if (!$starchyCategory) {
            $starchyCategory = Category::create([
                'name' => 'Féculents',
                'slug' => 'feculents',
                'description' => 'Féculents frais et biologiques',
                'is_active' => true,
            ]);
        }

        $starchyFoods = [
            [
                'name' => 'Pommes de Terre Bintje 5kg',
                'description' => 'Pommes de terre Bintje biologiques, variété polyvalente. Parfaites pour frites, purée et cuisson.',
                'short_description' => 'Pommes de terre Bintje bio 5kg',
                'price' => 8.50,
            ],
            [
                'name' => 'Pommes de Terre Charlotte 3kg',
                'description' => 'Pommes de terre Charlotte biologiques, à chair ferme. Idéales pour les salades et la cuisson vapeur.',
                'short_description' => 'Pommes de terre Charlotte bio 3kg',
                'price' => 9.00,
            ],
            [
                'name' => 'Patates Douces Bio 2kg',
                'description' => 'Patates douces biologiques, riches en vitamines et fibres. Saveur sucrée et texture fondante.',
                'short_description' => 'Patates douces bio 2kg',
                'price' => 12.50,
            ],
            [
                'name' => 'Topinambours Bio 1.5kg',
                'description' => 'Topinambours biologiques, légume ancien au goût d\'artichaut. Riche en inuline et peu calorique.',
                'short_description' => 'Topinambours bio 1.5kg',
                'price' => 11.00,
            ],
        ];

        foreach ($starchyFoods as $starchy) {
            $this->createProduct($starchy, $starchyCategory, 300, 30); // Stock 300, seuil critique 30
        }

        $this->command->info("   ✅ " . count($starchyFoods) . " féculents créés (300 unités chacun, seuil critique: 30)");
    }

    private function createIrrigation()
    {
        $this->command->info('💧 Création du matériel d\'irrigation...');
        
        // Récupérer ou créer la catégorie irrigation
        $irrigationCategory = Category::where('name', 'LIKE', '%Irrigation%')->first();
        if (!$irrigationCategory) {
            $irrigationCategory = Category::create([
                'name' => 'Irrigation',
                'slug' => 'irrigation',
                'description' => 'Matériel d\'irrigation et arrosage',
                'is_active' => true,
            ]);
        }

        $irrigationItems = [
            [
                'name' => 'Tuyau d\'arrosage 25m',
                'description' => 'Tuyau d\'arrosage flexible 25 mètres, diamètre 19mm. Résistant aux UV et aux intempéries.',
                'short_description' => 'Tuyau arrosage flexible 25m',
                'price' => 45.00,
            ],
            [
                'name' => 'Arrosoir Galvanisé 10L',
                'description' => 'Arrosoir galvanisé traditionnel 10 litres avec pomme d\'arrosage. Robuste et durable.',
                'short_description' => 'Arrosoir galvanisé 10L',
                'price' => 35.50,
            ],
            [
                'name' => 'Système Goutte-à-Goutte 50m',
                'description' => 'Kit complet irrigation goutte-à-goutte 50m. Économise l\'eau et optimise l\'arrosage.',
                'short_description' => 'Kit goutte-à-goutte 50m',
                'price' => 85.00,
            ],
            [
                'name' => 'Programmateur d\'Arrosage',
                'description' => 'Programmateur automatique d\'arrosage étanche. 4 programmes différents, pile 9V incluse.',
                'short_description' => 'Programmateur arrosage automatique',
                'price' => 65.00,
            ],
            [
                'name' => 'Asperseur Rotatif Pro',
                'description' => 'Asperseur rotatif professionnel, portée 8-12m. Arrosage uniforme et réglable.',
                'short_description' => 'Asperseur rotatif professionnel',
                'price' => 28.00,
            ],
        ];

        foreach ($irrigationItems as $item) {
            $this->createProduct($item, $irrigationCategory, 50, 8); // Stock 50, seuil critique 8
        }

        $this->command->info("   ✅ " . count($irrigationItems) . " équipements d\'irrigation créés (50 unités chacun, seuil critique: 8)");
    }

    private function createMachines()
    {
        $this->command->info('🔧 Création des machines portatives...');
        
        // Récupérer ou créer la catégorie machines
        $machineCategory = Category::where('name', 'LIKE', '%Machine%')->first();
        if (!$machineCategory) {
            $machineCategory = Category::create([
                'name' => 'Machines',
                'slug' => 'machines',
                'description' => 'Machines et outils portables',
                'is_active' => true,
            ]);
        }

        $machines = [
            [
                'name' => 'Motoculteur 7CV',
                'description' => 'Motoculteur 7CV avec fraise rotative. Idéal pour préparer le sol et biner. Facile à manœuvrer.',
                'short_description' => 'Motoculteur 7CV avec fraise',
                'price' => 850.00,
            ],
            [
                'name' => 'Débroussailleuse Thermique',
                'description' => 'Débroussailleuse thermique 52cc, lame et fil. Parfaite pour l\'entretien des bordures et friches.',
                'short_description' => 'Débroussailleuse thermique 52cc',
                'price' => 320.00,
            ],
            [
                'name' => 'Tronçonneuse 45cm',
                'description' => 'Tronçonneuse thermique guide 45cm, 3.2CV. Système anti-vibration et démarrage facilité.',
                'short_description' => 'Tronçonneuse thermique 45cm',
                'price' => 485.00,
            ],
            [
                'name' => 'Broyeur de Végétaux',
                'description' => 'Broyeur de végétaux électrique 2500W. Système de coupe double, silencieux et efficace.',
                'short_description' => 'Broyeur végétaux électrique 2500W',
                'price' => 650.00,
            ],
            [
                'name' => 'Souffleur Thermique',
                'description' => 'Souffleur thermique dorsal 65cc. Vitesse air 270 km/h, harnais ergonomique inclus.',
                'short_description' => 'Souffleur thermique dorsal 65cc',
                'price' => 420.00,
            ],
        ];

        foreach ($machines as $machine) {
            $this->createProduct($machine, $machineCategory, 15, 3); // Stock 15, seuil critique 3
        }

        $this->command->info("   ✅ " . count($machines) . " machines créées (15 unités chacune, seuil critique: 3)");
    }

    private function createProtection()
    {
        $this->command->info('🛡️ Création des produits de protection...');
        
        // Récupérer ou créer la catégorie protection
        $protectionCategory = Category::where('name', 'LIKE', '%Protection%')->first();
        if (!$protectionCategory) {
            $protectionCategory = Category::create([
                'name' => 'Protection',
                'slug' => 'protection',
                'description' => 'Protection des cultures et équipements',
                'is_active' => true,
            ]);
        }

        $protectionItems = [
            [
                'name' => 'Voile de Forçage 10m²',
                'description' => 'Voile de forçage non tissé 17g/m², 10m². Protection contre le froid et les insectes.',
                'short_description' => 'Voile de forçage 10m²',
                'price' => 18.50,
            ],
            [
                'name' => 'Film Plastique Serre 4m',
                'description' => 'Film plastique transparent pour serre, largeur 4m. Résistant UV, épaisseur 200 microns.',
                'short_description' => 'Film plastique serre 4m',
                'price' => 25.00,
            ],
            [
                'name' => 'Filet Anti-Oiseaux 5x10m',
                'description' => 'Filet de protection anti-oiseaux 5x10m, maille 15mm. Protège fruits et légumes efficacement.',
                'short_description' => 'Filet anti-oiseaux 5x10m',
                'price' => 32.00,
            ],
            [
                'name' => 'Pièges à Limaces x10',
                'description' => 'Lot de 10 pièges à limaces écologiques. Méthode naturelle sans produits chimiques.',
                'short_description' => 'Pièges à limaces écologiques x10',
                'price' => 15.50,
            ],
        ];

        foreach ($protectionItems as $protection) {
            $this->createProduct($protection, $protectionCategory, 80, 12); // Stock 80, seuil critique 12
        }

        $this->command->info("   ✅ " . count($protectionItems) . " produits de protection créés (80 unités chacun, seuil critique: 12)");
    }

    private function createProduct($productData, $category, $quantity, $criticalThreshold)
    {
        Product::create([
            'name' => $productData['name'],
            'description' => $productData['description'],
            'short_description' => $productData['short_description'],
            'slug' => Str::slug($productData['name']) . '-' . strtolower(Str::random(4)),
            'sku' => 'PROD-' . strtoupper(Str::random(6)),
            'type' => 'sale', // Tous les produits sont en vente
            'price' => $productData['price'],
            'rental_price_per_day' => 0.00,
            'quantity' => $quantity,
            'rental_stock' => 0,
            'category_id' => $category->id,
            'is_active' => true,
            'is_featured' => false,
            'critical_threshold' => $criticalThreshold,
            'low_stock_threshold' => $criticalThreshold * 2, // Seuil faible = 2x critique
            'out_of_stock_threshold' => 1,
            'unit_symbol' => 'kg',
            'weight' => rand(100, 2000) / 1000, // Poids aléatoire entre 0.1 et 2kg
            'min_rental_days' => 1,
            'max_rental_days' => 7,
            'meta_title' => $productData['name'],
            'meta_description' => $productData['short_description'],
            'meta_keywords' => str_replace(' ', ', ', strtolower($productData['name'])),
        ]);
    }
}
