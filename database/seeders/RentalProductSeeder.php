<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\RentalCategory;
use Illuminate\Support\Str;

class RentalProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer les catégories de location
        $outilsRentalCategory = RentalCategory::where('name', 'Outils agricoles')->first();
        $machinesRentalCategory = RentalCategory::where('name', 'Machines')->first();
        $equipementsRentalCategory = RentalCategory::where('name', 'Équipements')->first();

        if (!$outilsRentalCategory || !$machinesRentalCategory || !$equipementsRentalCategory) {
            throw new \Exception('Les catégories de location doivent être créées avant ce seeder');
        }

        // Récupérer les catégories normales correspondantes
        $outilsNormalCategory = \App\Models\Category::where('name', 'Outils agricoles')->first();
        $machinesNormalCategory = \App\Models\Category::where('name', 'Machines')->first();
        $equipementsNormalCategory = \App\Models\Category::where('name', 'Équipement')->first(); // Note: "Équipement" sans s

        if (!$outilsNormalCategory || !$machinesNormalCategory || !$equipementsNormalCategory) {
            throw new \Exception('Les catégories normales correspondantes doivent exister');
        }

        // Définition des produits par catégorie
        $this->createOutilsAgricoles($outilsRentalCategory->id, $outilsNormalCategory->id);
        $this->createMachines($machinesRentalCategory->id, $machinesNormalCategory->id);
        $this->createEquipements($equipementsRentalCategory->id, $equipementsNormalCategory->id);

        $this->command->info('✅ 100 produits de location agricoles créés avec succès !');
        $this->command->info('📊 Répartition : 35 outils, 35 machines, 30 équipements');
    }

    private function createOutilsAgricoles($rentalCategoryId, $normalCategoryId)
    {
        $outils = [
            // Outils manuels agricoles (35 produits)
            [
                'name' => 'Bêche agricole professionnelle',
                'short_description' => 'Bêche robuste pour travaux de terre agricoles',
                'rental_price_per_day' => 3.50,
                'deposit_amount' => 15.00,
                'min_rental_days' => 1,
                'max_rental_days' => 7,
                'weight' => 1.8,
                'dimensions' => '120 x 20 x 5 cm'
            ],
            [
                'name' => 'Houe maraîchère 3 dents',
                'short_description' => 'Houe pour binage et sarclage cultures légumières',
                'rental_price_per_day' => 2.80,
                'deposit_amount' => 12.00,
                'min_rental_days' => 1,
                'max_rental_days' => 7,
                'weight' => 1.2,
                'dimensions' => '35 x 15 x 8 cm'
            ],
            [
                'name' => 'Fourche à fumier longue',
                'short_description' => 'Fourche spéciale pour manipulation fumier et paille',
                'rental_price_per_day' => 3.20,
                'deposit_amount' => 14.00,
                'min_rental_days' => 1,
                'max_rental_days' => 7,
                'weight' => 1.6,
                'dimensions' => '130 x 25 x 8 cm'
            ],
            [
                'name' => 'Râteau andaineur agricole',
                'short_description' => 'Râteau pour andainage et retournement foin',
                'rental_price_per_day' => 4.50,
                'deposit_amount' => 18.00,
                'min_rental_days' => 1,
                'max_rental_days' => 7,
                'weight' => 2.1,
                'dimensions' => '180 x 35 x 5 cm'
            ],
            [
                'name' => 'Serfouette viticole',
                'short_description' => 'Outil pour travail du sol en viticulture',
                'rental_price_per_day' => 2.90,
                'deposit_amount' => 12.50,
                'min_rental_days' => 1,
                'max_rental_days' => 7,
                'weight' => 0.9,
                'dimensions' => '40 x 12 x 6 cm'
            ],
            [
                'name' => 'Pioche agricole lourde',
                'short_description' => 'Pioche pour défonçage sols durs et défrichage',
                'rental_price_per_day' => 4.20,
                'deposit_amount' => 18.00,
                'min_rental_days' => 1,
                'max_rental_days' => 5,
                'weight' => 2.5,
                'dimensions' => '90 x 25 x 8 cm'
            ],
            [
                'name' => 'Transplantoir agricole renforcé',
                'short_description' => 'Transplantoir pour repiquage plants maraîchers',
                'rental_price_per_day' => 1.80,
                'deposit_amount' => 8.00,
                'min_rental_days' => 1,
                'max_rental_days' => 7,
                'weight' => 0.3,
                'dimensions' => '25 x 5 x 3 cm'
            ],
            [
                'name' => 'Binette triangle maraîchage',
                'short_description' => 'Binette de précision pour cultures serrées',
                'rental_price_per_day' => 2.20,
                'deposit_amount' => 9.00,
                'min_rental_days' => 1,
                'max_rental_days' => 7,
                'weight' => 0.7,
                'dimensions' => '35 x 8 x 4 cm'
            ],
            [
                'name' => 'Faux agricole traditionnelle',
                'short_description' => 'Faux pour fauchage prairies et bordures',
                'rental_price_per_day' => 5.50,
                'deposit_amount' => 25.00,
                'min_rental_days' => 1,
                'max_rental_days' => 3,
                'weight' => 2.8,
                'dimensions' => '180 x 30 x 10 cm'
            ],
            [
                'name' => 'Sécateur arboricole professionnel',
                'short_description' => 'Sécateur haute qualité pour taille fruitiers',
                'rental_price_per_day' => 6.80,
                'deposit_amount' => 28.00,
                'min_rental_days' => 1,
                'max_rental_days' => 7,
                'weight' => 0.5,
                'dimensions' => '25 x 8 x 3 cm'
            ],
            [
                'name' => 'Ébrancheur télescopique 3m',
                'short_description' => 'Coupe-branches extensible pour élagage vergers',
                'rental_price_per_day' => 8.50,
                'deposit_amount' => 35.00,
                'min_rental_days' => 1,
                'max_rental_days' => 5,
                'weight' => 2.8,
                'dimensions' => '300 x 15 x 8 cm'
            ],
            [
                'name' => 'Scie arboricole courbée',
                'short_description' => 'Scie spécialisée pour taille branches épaisses',
                'rental_price_per_day' => 4.20,
                'deposit_amount' => 18.00,
                'min_rental_days' => 1,
                'max_rental_days' => 7,
                'weight' => 0.8,
                'dimensions' => '45 x 12 x 3 cm'
            ],
            [
                'name' => 'Plantoir à bulbes agricole',
                'short_description' => 'Plantoir professionnel pour bulbes et tubercules',
                'rental_price_per_day' => 2.10,
                'deposit_amount' => 9.50,
                'min_rental_days' => 1,
                'max_rental_days' => 7,
                'weight' => 0.6,
                'dimensions' => '30 x 6 x 6 cm'
            ],
            [
                'name' => 'Hache forestière 2kg',
                'short_description' => 'Hache lourde pour abattage et débitage bois',
                'rental_price_per_day' => 6.80,
                'deposit_amount' => 30.00,
                'min_rental_days' => 1,
                'max_rental_days' => 3,
                'weight' => 2.2,
                'dimensions' => '60 x 18 x 8 cm'
            ],
            [
                'name' => 'Cultivateur manuel 5 dents',
                'short_description' => 'Cultivateur pour ameublissement sol léger',
                'rental_price_per_day' => 3.70,
                'deposit_amount' => 16.00,
                'min_rental_days' => 1,
                'max_rental_days' => 7,
                'weight' => 1.4,
                'dimensions' => '50 x 25 x 8 cm'
            ],
            [
                'name' => 'Coupe-légumes agricole',
                'short_description' => 'Couteau spécialisé récolte légumes feuilles',
                'rental_price_per_day' => 2.50,
                'deposit_amount' => 11.00,
                'min_rental_days' => 1,
                'max_rental_days' => 7,
                'weight' => 0.4,
                'dimensions' => '20 x 5 x 2 cm'
            ],
            [
                'name' => 'Pelle carrée agricole',
                'short_description' => 'Pelle robuste pour manipulation terre et matériaux',
                'rental_price_per_day' => 2.80,
                'deposit_amount' => 12.00,
                'min_rental_days' => 1,
                'max_rental_days' => 7,
                'weight' => 1.3,
                'dimensions' => '110 x 25 x 5 cm'
            ],
            [
                'name' => 'Fourche à bêcher professionnelle',
                'short_description' => 'Fourche-bêche pour décompactage sans retournement',
                'rental_price_per_day' => 3.90,
                'deposit_amount' => 17.00,
                'min_rental_days' => 1,
                'max_rental_days' => 7,
                'weight' => 1.7,
                'dimensions' => '120 x 20 x 8 cm'
            ],
            [
                'name' => 'Serpette vigneronne',
                'short_description' => 'Serpette traditionnelle pour taille vigne',
                'rental_price_per_day' => 3.20,
                'deposit_amount' => 14.00,
                'min_rental_days' => 1,
                'max_rental_days' => 7,
                'weight' => 0.3,
                'dimensions' => '15 x 5 x 2 cm'
            ],
            [
                'name' => 'Grelinette 5 dents',
                'short_description' => 'Outil biodynamique pour aération du sol',
                'rental_price_per_day' => 5.80,
                'deposit_amount' => 25.00,
                'min_rental_days' => 1,
                'max_rental_days' => 7,
                'weight' => 2.2,
                'dimensions' => '60 x 50 x 8 cm'
            ],
            [
                'name' => 'Houe oscillante',
                'short_description' => 'Houe à lame oscillante pour désherbage précis',
                'rental_price_per_day' => 4.50,
                'deposit_amount' => 20.00,
                'min_rental_days' => 1,
                'max_rental_days' => 7,
                'weight' => 1.1,
                'dimensions' => '140 x 15 x 5 cm'
            ],
            [
                'name' => 'Scie passe-partout 2 personnes',
                'short_description' => 'Grande scie pour abattage arbres moyens',
                'rental_price_per_day' => 8.90,
                'deposit_amount' => 38.00,
                'min_rental_days' => 1,
                'max_rental_days' => 3,
                'weight' => 3.5,
                'dimensions' => '150 x 15 x 2 cm'
            ],
            [
                'name' => 'Couteau à désherber',
                'short_description' => 'Couteau spécialisé extraction mauvaises herbes',
                'rental_price_per_day' => 1.90,
                'deposit_amount' => 8.50,
                'min_rental_days' => 1,
                'max_rental_days' => 7,
                'weight' => 0.2,
                'dimensions' => '25 x 3 x 1 cm'
            ],
            [
                'name' => 'Croc de jardin 4 dents',
                'short_description' => 'Croc pour ameublissement et préparation semis',
                'rental_price_per_day' => 3.40,
                'deposit_amount' => 15.00,
                'min_rental_days' => 1,
                'max_rental_days' => 7,
                'weight' => 1.0,
                'dimensions' => '35 x 20 x 8 cm'
            ],
            [
                'name' => 'Émondoir sur perche 4m',
                'short_description' => 'Émondoir télescopique pour élagage hauteur',
                'rental_price_per_day' => 9.50,
                'deposit_amount' => 42.00,
                'min_rental_days' => 1,
                'max_rental_days' => 3,
                'weight' => 3.2,
                'dimensions' => '400 x 10 x 10 cm'
            ],
            [
                'name' => 'Bêche à drainer',
                'short_description' => 'Bêche étroite pour création rigoles drainage',
                'rental_price_per_day' => 3.80,
                'deposit_amount' => 16.50,
                'min_rental_days' => 1,
                'max_rental_days' => 7,
                'weight' => 1.9,
                'dimensions' => '125 x 12 x 5 cm'
            ],
            [
                'name' => 'Faucille traditionnelle',
                'short_description' => 'Faucille pour récolte céréales et herbes',
                'rental_price_per_day' => 2.70,
                'deposit_amount' => 12.00,
                'min_rental_days' => 1,
                'max_rental_days' => 7,
                'weight' => 0.5,
                'dimensions' => '35 x 15 x 2 cm'
            ],
            [
                'name' => 'Grattoir à sabots',
                'short_description' => 'Outil spécialisé entretien sabots bovins',
                'rental_price_per_day' => 2.30,
                'deposit_amount' => 10.00,
                'min_rental_days' => 1,
                'max_rental_days' => 7,
                'weight' => 0.6,
                'dimensions' => '25 x 8 x 3 cm'
            ],
            [
                'name' => 'Pelle à grain',
                'short_description' => 'Pelle spécialisée manipulation céréales',
                'rental_price_per_day' => 2.90,
                'deposit_amount' => 13.00,
                'min_rental_days' => 1,
                'max_rental_days' => 7,
                'weight' => 0.8,
                'dimensions' => '80 x 30 x 8 cm'
            ],
            [
                'name' => 'Sarcloir oscillant',
                'short_description' => 'Sarcloir à lame mobile pour inter-rangs',
                'rental_price_per_day' => 4.20,
                'deposit_amount' => 18.50,
                'min_rental_days' => 1,
                'max_rental_days' => 7,
                'weight' => 1.3,
                'dimensions' => '130 x 12 x 6 cm'
            ],
            [
                'name' => 'Échenilloir 5m',
                'short_description' => 'Outil pour destruction nids chenilles',
                'rental_price_per_day' => 6.80,
                'deposit_amount' => 30.00,
                'min_rental_days' => 1,
                'max_rental_days' => 5,
                'weight' => 2.1,
                'dimensions' => '500 x 8 x 8 cm'
            ],
            [
                'name' => 'Plantoir forestier',
                'short_description' => 'Plantoir robuste pour reboisement',
                'rental_price_per_day' => 3.50,
                'deposit_amount' => 15.50,
                'min_rental_days' => 1,
                'max_rental_days' => 7,
                'weight' => 1.1,
                'dimensions' => '90 x 8 x 8 cm'
            ],
            [
                'name' => 'Houe plate sarclage',
                'short_description' => 'Houe plate pour sarclage entre rangs',
                'rental_price_per_day' => 3.10,
                'deposit_amount' => 14.00,
                'min_rental_days' => 1,
                'max_rental_days' => 7,
                'weight' => 0.9,
                'dimensions' => '130 x 18 x 3 cm'
            ],
            [
                'name' => 'Écorçoir forestier',
                'short_description' => 'Outil pour écorçage arbres abattus',
                'rental_price_per_day' => 4.90,
                'deposit_amount' => 22.00,
                'min_rental_days' => 1,
                'max_rental_days' => 5,
                'weight' => 1.8,
                'dimensions' => '60 x 15 x 8 cm'
            ],
            [
                'name' => 'Tire-sève vigneronne',
                'short_description' => 'Outil extraction sève pour greffage',
                'rental_price_per_day' => 5.20,
                'deposit_amount' => 23.00,
                'min_rental_days' => 1,
                'max_rental_days' => 7,
                'weight' => 0.4,
                'dimensions' => '20 x 3 x 2 cm'
            ]
        ];

        foreach ($outils as $index => $outil) {
            $name = $outil['name'];
            $slug = 'location-' . Str::slug($name);
            $sku = 'AGRI-TOOL-' . strtoupper(Str::random(4));

            Product::create([
                'name' => $name,
                'slug' => $slug,
                'description' => $outil['short_description'] . '. Outil agricole professionnel de qualité, parfait pour les travaux de ferme et d\'exploitation. Location avec prise en charge flexible et conseils d\'utilisation.',
                'short_description' => $outil['short_description'],
                'type' => 'rental',
                'sku' => $sku,
                'price' => 0.00, // Produit de location uniquement
                'rental_price_per_day' => $outil['rental_price_per_day'],
                'deposit_amount' => $outil['deposit_amount'],
                'min_rental_days' => $outil['min_rental_days'],
                'max_rental_days' => $outil['max_rental_days'],
                'quantity' => 100, // Stock défini par l'utilisateur pour outils
                'critical_threshold' => 10,
                'low_stock_threshold' => 20,
                'out_of_stock_threshold' => 5,
                'weight' => $outil['weight'],
                'dimensions' => $outil['dimensions'],
                'unit_symbol' => 'pièce',
                'is_active' => true,
                'is_featured' => $index < 5, // Les 5 premiers sont mis en avant
                'category_id' => $normalCategoryId, // Catégorie produit normale correspondante
                'rental_category_id' => $rentalCategoryId, // Catégorie de location spécialisée
                'meta_title' => $name . ' - Location d\'outils agricoles - FarmShop',
                'meta_description' => 'Louez ' . strtolower($name) . ' de qualité professionnelle. ' . $outil['short_description'] . '. Tarif ' . $outil['rental_price_per_day'] . '€/jour avec caution ' . $outil['deposit_amount'] . '€.',
                'meta_keywords' => 'location, ' . strtolower($name) . ', outil agricole, ferme, exploitation, FarmShop'
            ]);
        }

        $this->command->info("✅ 35 outils agricoles créés avec 100 unités de stock chacun");
    }

    private function createMachines($rentalCategoryId, $normalCategoryId)
    {
        $machines = [
            // Machines agricoles (35 produits)
            [
                'name' => 'Motoculteur thermique 7CV',
                'short_description' => 'Motoculteur puissant pour préparation sols agricoles',
                'rental_price_per_day' => 45.00,
                'deposit_amount' => 200.00,
                'min_rental_days' => 1,
                'max_rental_days' => 5,
                'weight' => 85.0,
                'dimensions' => '150 x 70 x 100 cm'
            ],
            [
                'name' => 'Débroussailleuse thermique professionnelle',
                'short_description' => 'Débroussailleuse pour entretien prairies et bordures',
                'rental_price_per_day' => 28.50,
                'deposit_amount' => 125.00,
                'min_rental_days' => 1,
                'max_rental_days' => 5,
                'weight' => 8.5,
                'dimensions' => '180 x 40 x 30 cm'
            ],
            [
                'name' => 'Tondeuse autoportée agricole',
                'short_description' => 'Tondeuse robuste pour grandes surfaces d\'exploitation',
                'rental_price_per_day' => 65.00,
                'deposit_amount' => 290.00,
                'min_rental_days' => 1,
                'max_rental_days' => 7,
                'weight' => 180.0,
                'dimensions' => '200 x 120 x 110 cm'
            ],
            [
                'name' => 'Rotavator thermique tractable',
                'short_description' => 'Rotavator pour préparation fine sols maraîchers',
                'rental_price_per_day' => 55.00,
                'deposit_amount' => 250.00,
                'min_rental_days' => 1,
                'max_rental_days' => 3,
                'weight' => 120.0,
                'dimensions' => '180 x 80 x 110 cm'
            ],
            [
                'name' => 'Faucheuse rotative portée',
                'short_description' => 'Faucheuse pour entretien prairies et fourrages',
                'rental_price_per_day' => 78.00,
                'deposit_amount' => 350.00,
                'min_rental_days' => 1,
                'max_rental_days' => 3,
                'weight' => 220.0,
                'dimensions' => '200 x 140 x 90 cm'
            ],
            [
                'name' => 'Broyeur agricole à fléaux',
                'short_description' => 'Broyeur pour destruction résidus de culture',
                'rental_price_per_day' => 68.00,
                'deposit_amount' => 310.00,
                'min_rental_days' => 1,
                'max_rental_days' => 5,
                'weight' => 185.0,
                'dimensions' => '180 x 100 x 80 cm'
            ],
            [
                'name' => 'Épandeur centrifuge 500L',
                'short_description' => 'Épandeur pour engrais et semences agricoles',
                'rental_price_per_day' => 35.50,
                'deposit_amount' => 160.00,
                'min_rental_days' => 1,
                'max_rental_days' => 7,
                'weight' => 95.0,
                'dimensions' => '180 x 130 x 100 cm'
            ],
            [
                'name' => 'Pulvérisateur thermique 200L',
                'short_description' => 'Pulvérisateur pour traitements phytosanitaires',
                'rental_price_per_day' => 42.00,
                'deposit_amount' => 190.00,
                'min_rental_days' => 1,
                'max_rental_days' => 7,
                'weight' => 110.0,
                'dimensions' => '160 x 80 x 120 cm'
            ],
            [
                'name' => 'Motopompe agricole 3 pouces',
                'short_description' => 'Pompe thermique pour irrigation cultures',
                'rental_price_per_day' => 38.50,
                'deposit_amount' => 175.00,
                'min_rental_days' => 1,
                'max_rental_days' => 15,
                'weight' => 32.0,
                'dimensions' => '60 x 45 x 50 cm'
            ],
            [
                'name' => 'Fendeuse de bûches thermique',
                'short_description' => 'Fendeuse pour bois de chauffage exploitation',
                'rental_price_per_day' => 52.00,
                'deposit_amount' => 240.00,
                'min_rental_days' => 1,
                'max_rental_days' => 3,
                'weight' => 150.0,
                'dimensions' => '220 x 60 x 110 cm'
            ],
            [
                'name' => 'Cultivateur vibrant tracté',
                'short_description' => 'Cultivateur pour préparation superficielle sols',
                'rental_price_per_day' => 48.00,
                'deposit_amount' => 220.00,
                'min_rental_days' => 1,
                'max_rental_days' => 5,
                'weight' => 135.0,
                'dimensions' => '170 x 120 x 80 cm'
            ],
            [
                'name' => 'Semoir pneumatique de précision',
                'short_description' => 'Semoir pour cultures en ligne précises',
                'rental_price_per_day' => 85.00,
                'deposit_amount' => 380.00,
                'min_rental_days' => 1,
                'max_rental_days' => 7,
                'weight' => 195.0,
                'dimensions' => '200 x 150 x 90 cm'
            ],
            [
                'name' => 'Herse rotative agricole',
                'short_description' => 'Herse pour ameublissement et préparation semis',
                'rental_price_per_day' => 62.00,
                'deposit_amount' => 280.00,
                'min_rental_days' => 1,
                'max_rental_days' => 5,
                'weight' => 165.0,
                'dimensions' => '180 x 110 x 85 cm'
            ],
            [
                'name' => 'Rouleau agricole lestable 2m',
                'short_description' => 'Rouleau pour tassement sols et prairies',
                'rental_price_per_day' => 32.00,
                'deposit_amount' => 145.00,
                'min_rental_days' => 1,
                'max_rental_days' => 7,
                'weight' => 120.0,
                'dimensions' => '200 x 60 x 80 cm'
            ],
            [
                'name' => 'Bineuse mécanique 4 rangs',
                'short_description' => 'Bineuse pour entretien cultures en ligne',
                'rental_price_per_day' => 58.00,
                'deposit_amount' => 265.00,
                'min_rental_days' => 1,
                'max_rental_days' => 5,
                'weight' => 145.0,
                'dimensions' => '160 x 120 x 90 cm'
            ],
            [
                'name' => 'Faneur à tambours',
                'short_description' => 'Faneur pour retournement et aération foin',
                'rental_price_per_day' => 72.00,
                'deposit_amount' => 325.00,
                'min_rental_days' => 1,
                'max_rental_days' => 3,
                'weight' => 185.0,
                'dimensions' => '220 x 160 x 100 cm'
            ],
            [
                'name' => 'Distributeur fumier tractable',
                'short_description' => 'Épandeur pour fumier et compost agricole',
                'rental_price_per_day' => 95.00,
                'deposit_amount' => 425.00,
                'min_rental_days' => 1,
                'max_rental_days' => 5,
                'weight' => 280.0,
                'dimensions' => '250 x 180 x 120 cm'
            ],
            [
                'name' => 'Aérateur de prairie traîné',
                'short_description' => 'Aérateur pour régénération prairies',
                'rental_price_per_day' => 45.00,
                'deposit_amount' => 205.00,
                'min_rental_days' => 1,
                'max_rental_days' => 5,
                'weight' => 125.0,
                'dimensions' => '180 x 140 x 60 cm'
            ],
            [
                'name' => 'Tronçonneuse thermique professionnelle',
                'short_description' => 'Tronçonneuse pour élagage et abattage',
                'rental_price_per_day' => 32.00,
                'deposit_amount' => 145.00,
                'min_rental_days' => 1,
                'max_rental_days' => 3,
                'weight' => 6.8,
                'dimensions' => '55 x 30 x 25 cm'
            ],
            [
                'name' => 'Butteur hydraulique ajustable',
                'short_description' => 'Butteur pour formation billons cultures',
                'rental_price_per_day' => 55.00,
                'deposit_amount' => 250.00,
                'min_rental_days' => 1,
                'max_rental_days' => 5,
                'weight' => 140.0,
                'dimensions' => '150 x 100 x 85 cm'
            ],
            [
                'name' => 'Décavaillonneuse viticole',
                'short_description' => 'Machine pour travail du sol en viticulture',
                'rental_price_per_day' => 68.00,
                'deposit_amount' => 310.00,
                'min_rental_days' => 1,
                'max_rental_days' => 5,
                'weight' => 175.0,
                'dimensions' => '140 x 80 x 100 cm'
            ],
            [
                'name' => 'Remorque agricole basculante 2T',
                'short_description' => 'Remorque pour transport matériaux agricoles',
                'rental_price_per_day' => 42.00,
                'deposit_amount' => 190.00,
                'min_rental_days' => 1,
                'max_rental_days' => 7,
                'weight' => 320.0,
                'dimensions' => '300 x 160 x 80 cm'
            ],
            [
                'name' => 'Souffleur thermique dorsal',
                'short_description' => 'Souffleur pour nettoyage cours de ferme',
                'rental_price_per_day' => 25.50,
                'deposit_amount' => 115.00,
                'min_rental_days' => 1,
                'max_rental_days' => 5,
                'weight' => 9.2,
                'dimensions' => '40 x 35 x 60 cm'
            ],
            [
                'name' => 'Tarière thermique agricole',
                'short_description' => 'Tarière pour plantation et poteaux clôture',
                'rental_price_per_day' => 38.00,
                'deposit_amount' => 175.00,
                'min_rental_days' => 1,
                'max_rental_days' => 3,
                'weight' => 12.5,
                'dimensions' => '120 x 30 x 30 cm'
            ],
            [
                'name' => 'Effeuilleuse pneumatique',
                'short_description' => 'Machine pour effeuillage mécanique vignes',
                'rental_price_per_day' => 78.00,
                'deposit_amount' => 350.00,
                'min_rental_days' => 1,
                'max_rental_days' => 5,
                'weight' => 185.0,
                'dimensions' => '120 x 80 x 120 cm'
            ],
            [
                'name' => 'Sous-soleuse agricole',
                'short_description' => 'Sous-soleuse pour décompactage profond',
                'rental_price_per_day' => 85.00,
                'deposit_amount' => 385.00,
                'min_rental_days' => 1,
                'max_rental_days' => 3,
                'weight' => 245.0,
                'dimensions' => '200 x 80 x 120 cm'
            ],
            [
                'name' => 'Planteuse pommes de terre',
                'short_description' => 'Planteuse spécialisée tubercules',
                'rental_price_per_day' => 95.00,
                'deposit_amount' => 425.00,
                'min_rental_days' => 1,
                'max_rental_days' => 7,
                'weight' => 220.0,
                'dimensions' => '180 x 120 x 100 cm'
            ],
            [
                'name' => 'Andaineur à tapis',
                'short_description' => 'Andaineur pour regroupement foin coupé',
                'rental_price_per_day' => 72.00,
                'deposit_amount' => 325.00,
                'min_rental_days' => 1,
                'max_rental_days' => 3,
                'weight' => 195.0,
                'dimensions' => '220 x 180 x 90 cm'
            ],
            [
                'name' => 'Déchaumeur à disques',
                'short_description' => 'Déchaumeur pour travail superficiel post-récolte',
                'rental_price_per_day' => 65.00,
                'deposit_amount' => 295.00,
                'min_rental_days' => 1,
                'max_rental_days' => 5,
                'weight' => 175.0,
                'dimensions' => '180 x 120 x 80 cm'
            ],
            [
                'name' => 'Écimeuse maïs',
                'short_description' => 'Machine pour écimage plants de maïs',
                'rental_price_per_day' => 58.00,
                'deposit_amount' => 265.00,
                'min_rental_days' => 1,
                'max_rental_days' => 7,
                'weight' => 145.0,
                'dimensions' => '160 x 100 x 120 cm'
            ],
            [
                'name' => 'Presse à balles rondes',
                'short_description' => 'Presse pour confection balles de foin',
                'rental_price_per_day' => 125.00,
                'deposit_amount' => 560.00,
                'min_rental_days' => 1,
                'max_rental_days' => 3,
                'weight' => 385.0,
                'dimensions' => '300 x 200 x 180 cm'
            ],
            [
                'name' => 'Trieur graines vibrant',
                'short_description' => 'Machine de tri et calibrage graines',
                'rental_price_per_day' => 48.00,
                'deposit_amount' => 220.00,
                'min_rental_days' => 1,
                'max_rental_days' => 7,
                'weight' => 125.0,
                'dimensions' => '150 x 80 x 120 cm'
            ],
            [
                'name' => 'Faneuse 4 toupies',
                'short_description' => 'Faneuse pour aération optimale fourrages',
                'rental_price_per_day' => 68.00,
                'deposit_amount' => 310.00,
                'min_rental_days' => 1,
                'max_rental_days' => 3,
                'weight' => 165.0,
                'dimensions' => '200 x 160 x 100 cm'
            ],
            [
                'name' => 'Retourneur d\'andains',
                'short_description' => 'Machine pour retournement andains de foin',
                'rental_price_per_day' => 55.00,
                'deposit_amount' => 250.00,
                'min_rental_days' => 1,
                'max_rental_days' => 3,
                'weight' => 145.0,
                'dimensions' => '180 x 140 x 90 cm'
            ],
            [
                'name' => 'Semoir céréales combiné',
                'short_description' => 'Semoir combiné préparation sol et semis',
                'rental_price_per_day' => 105.00,
                'deposit_amount' => 475.00,
                'min_rental_days' => 1,
                'max_rental_days' => 7,
                'weight' => 285.0,
                'dimensions' => '220 x 180 x 100 cm'
            ]
        ];

        foreach ($machines as $index => $machine) {
            $name = $machine['name'];
            $slug = 'location-' . Str::slug($name);
            $sku = 'AGRI-MACH-' . strtoupper(Str::random(4));

            Product::create([
                'name' => $name,
                'slug' => $slug,
                'description' => $machine['short_description'] . '. Machine agricole professionnelle entretenue régulièrement. Formation à l\'utilisation incluse. Livraison possible selon secteur géographique.',
                'short_description' => $machine['short_description'],
                'type' => 'rental',
                'sku' => $sku,
                'price' => 0.00, // Produit de location uniquement
                'rental_price_per_day' => $machine['rental_price_per_day'],
                'deposit_amount' => $machine['deposit_amount'],
                'min_rental_days' => $machine['min_rental_days'],
                'max_rental_days' => $machine['max_rental_days'],
                'quantity' => 25, // Stock défini par l'utilisateur pour machines
                'critical_threshold' => 3,
                'low_stock_threshold' => 5,
                'out_of_stock_threshold' => 1,
                'weight' => $machine['weight'],
                'dimensions' => $machine['dimensions'],
                'unit_symbol' => 'pièce',
                'is_active' => true,
                'is_featured' => $index < 5, // Les 5 premières sont mises en avant
                'category_id' => $normalCategoryId, // Catégorie produit normale correspondante
                'rental_category_id' => $rentalCategoryId, // Catégorie de location spécialisée
                'meta_title' => $name . ' - Location de machines agricoles - FarmShop',
                'meta_description' => 'Location ' . strtolower($name) . ' professionnel. ' . $machine['short_description'] . '. À partir de ' . $machine['rental_price_per_day'] . '€/jour, caution ' . $machine['deposit_amount'] . '€.',
                'meta_keywords' => 'location, ' . strtolower($name) . ', machine agricole, équipement motorisé, exploitation, FarmShop'
            ]);
        }

        $this->command->info("✅ 35 machines agricoles créées avec 25 unités de stock chacune");
    }

    private function createEquipements($rentalCategoryId, $normalCategoryId)
    {
        $equipements = [
            // Équipements agricoles (30 produits)
            [
                'name' => 'Serre tunnel agricole 6x12m',
                'short_description' => 'Grande serre pour production maraîchère',
                'rental_price_per_day' => 25.50,
                'deposit_amount' => 280.00,
                'min_rental_days' => 30,
                'max_rental_days' => 365,
                'weight' => 185.0,
                'dimensions' => '1200 x 600 x 300 cm'
            ],
            [
                'name' => 'Système irrigation goutte-à-goutte 1ha',
                'short_description' => 'Kit complet micro-irrigation cultures',
                'rental_price_per_day' => 18.50,
                'deposit_amount' => 195.00,
                'min_rental_days' => 14,
                'max_rental_days' => 180,
                'weight' => 45.0,
                'dimensions' => '120 x 80 x 40 cm'
            ],
            [
                'name' => 'Bâche plastique agricole 200m²',
                'short_description' => 'Bâche protection cultures et stockage',
                'rental_price_per_day' => 8.50,
                'deposit_amount' => 85.00,
                'min_rental_days' => 7,
                'max_rental_days' => 180,
                'weight' => 25.0,
                'dimensions' => '500 x 100 x 20 cm'
            ],
            [
                'name' => 'Filet anti-grêle agricole 500m²',
                'short_description' => 'Protection vergers contre intempéries',
                'rental_price_per_day' => 15.20,
                'deposit_amount' => 165.00,
                'min_rental_days' => 30,
                'max_rental_days' => 180,
                'weight' => 35.0,
                'dimensions' => '200 x 250 x 15 cm'
            ],
            [
                'name' => 'Cuve de stockage eau 5000L',
                'short_description' => 'Réservoir souple pour irrigation',
                'rental_price_per_day' => 22.00,
                'deposit_amount' => 240.00,
                'min_rental_days' => 14,
                'max_rental_days' => 365,
                'weight' => 85.0,
                'dimensions' => '300 x 250 x 180 cm'
            ],
            [
                'name' => 'Balance agricole 1 tonne',
                'short_description' => 'Balance professionnelle pour pesées commerciales',
                'rental_price_per_day' => 28.50,
                'deposit_amount' => 315.00,
                'min_rental_days' => 3,
                'max_rental_days' => 90,
                'weight' => 145.0,
                'dimensions' => '150 x 100 x 25 cm'
            ],
            [
                'name' => 'Clôture électrique mobile 500m',
                'short_description' => 'Système clôturage temporaire pâturage',
                'rental_price_per_day' => 12.80,
                'deposit_amount' => 140.00,
                'min_rental_days' => 7,
                'max_rental_days' => 180,
                'weight' => 45.0,
                'dimensions' => '100 x 60 x 80 cm'
            ],
            [
                'name' => 'Séchoir grains ventilé 10m³',
                'short_description' => 'Installation séchage céréales mobile',
                'rental_price_per_day' => 45.00,
                'deposit_amount' => 485.00,
                'min_rental_days' => 7,
                'max_rental_days' => 90,
                'weight' => 285.0,
                'dimensions' => '300 x 200 x 250 cm'
            ],
            [
                'name' => 'Pulvérisateur à dos 20L',
                'short_description' => 'Pulvérisateur manuel pour traitements localisés',
                'rental_price_per_day' => 6.50,
                'deposit_amount' => 65.00,
                'min_rental_days' => 1,
                'max_rental_days' => 30,
                'weight' => 4.5,
                'dimensions' => '50 x 30 x 70 cm'
            ],
            [
                'name' => 'Abreuvoir mobile 1000L',
                'short_description' => 'Abreuvoir tractable pour bétail au pâturage',
                'rental_price_per_day' => 18.00,
                'deposit_amount' => 195.00,
                'min_rental_days' => 7,
                'max_rental_days' => 180,
                'weight' => 125.0,
                'dimensions' => '200 x 120 x 150 cm'
            ],
            [
                'name' => 'Big-bags agricoles x20',
                'short_description' => 'Lot de sacs big-bag pour stockage grains',
                'rental_price_per_day' => 8.90,
                'deposit_amount' => 195.00,
                'min_rental_days' => 7,
                'max_rental_days' => 365,
                'weight' => 45.0,
                'dimensions' => '100 x 100 x 110 cm'
            ],
            [
                'name' => 'Thermomètre enregistreur stockage',
                'short_description' => 'Monitoring température silos et stockage',
                'rental_price_per_day' => 12.50,
                'deposit_amount' => 135.00,
                'min_rental_days' => 7,
                'max_rental_days' => 365,
                'weight' => 2.5,
                'dimensions' => '25 x 15 x 10 cm'
            ],
            [
                'name' => 'Presse fruits hydraulique',
                'short_description' => 'Presse pour extraction jus fruits',
                'rental_price_per_day' => 32.00,
                'deposit_amount' => 345.00,
                'min_rental_days' => 1,
                'max_rental_days' => 15,
                'weight' => 85.0,
                'dimensions' => '80 x 60 x 120 cm'
            ],
            [
                'name' => 'Étiqueteuse produits fermiers',
                'short_description' => 'Machine étiquetage produits de la ferme',
                'rental_price_per_day' => 15.80,
                'deposit_amount' => 170.00,
                'min_rental_days' => 3,
                'max_rental_days' => 90,
                'weight' => 12.0,
                'dimensions' => '40 x 30 x 35 cm'
            ],
            [
                'name' => 'Tunnel de stockage démontable 6x12m',
                'short_description' => 'Abri temporaire pour matériel agricole',
                'rental_price_per_day' => 28.50,
                'deposit_amount' => 315.00,
                'min_rental_days' => 30,
                'max_rental_days' => 365,
                'weight' => 165.0,
                'dimensions' => '1200 x 600 x 400 cm'
            ],
            [
                'name' => 'Distributeur aliment bétail 500kg',
                'short_description' => 'Distributeur automatique alimentation',
                'rental_price_per_day' => 22.00,
                'deposit_amount' => 240.00,
                'min_rental_days' => 7,
                'max_rental_days' => 180,
                'weight' => 95.0,
                'dimensions' => '150 x 100 x 180 cm'
            ],
            [
                'name' => 'Mélangeur aliments 1m³',
                'short_description' => 'Mélangeur vertical pour rations bétail',
                'rental_price_per_day' => 38.00,
                'deposit_amount' => 415.00,
                'min_rental_days' => 1,
                'max_rental_days' => 90,
                'weight' => 185.0,
                'dimensions' => '120 x 120 x 200 cm'
            ],
            [
                'name' => 'Station météo agricole',
                'short_description' => 'Station météorologique pour suivi cultures',
                'rental_price_per_day' => 18.50,
                'deposit_amount' => 200.00,
                'min_rental_days' => 30,
                'max_rental_days' => 365,
                'weight' => 8.5,
                'dimensions' => '40 x 30 x 150 cm'
            ],
            [
                'name' => 'Compteur grains volumétrique',
                'short_description' => 'Compteur pour quantification récoltes',
                'rental_price_per_day' => 25.00,
                'deposit_amount' => 275.00,
                'min_rental_days' => 3,
                'max_rental_days' => 60,
                'weight' => 35.0,
                'dimensions' => '80 x 50 x 100 cm'
            ],
            [
                'name' => 'Humidimètre céréales portable',
                'short_description' => 'Mesureur humidité grains et fourrages',
                'rental_price_per_day' => 12.00,
                'deposit_amount' => 130.00,
                'min_rental_days' => 3,
                'max_rental_days' => 90,
                'weight' => 1.8,
                'dimensions' => '25 x 15 x 8 cm'
            ],
            [
                'name' => 'Aspirateur grains pneumatique',
                'short_description' => 'Système aspiration transport céréales',
                'rental_price_per_day' => 42.00,
                'deposit_amount' => 460.00,
                'min_rental_days' => 1,
                'max_rental_days' => 30,
                'weight' => 125.0,
                'dimensions' => '200 x 60 x 120 cm'
            ],
            [
                'name' => 'Chauffage serre mobile 15kW',
                'short_description' => 'Chauffage temporaire serres et abris',
                'rental_price_per_day' => 28.00,
                'deposit_amount' => 305.00,
                'min_rental_days' => 7,
                'max_rental_days' => 180,
                'weight' => 45.0,
                'dimensions' => '80 x 50 x 60 cm'
            ],
            [
                'name' => 'Brumisateur haute pression 100 buses',
                'short_description' => 'Système brumisation serres et élevage',
                'rental_price_per_day' => 35.50,
                'deposit_amount' => 385.00,
                'min_rental_days' => 7,
                'max_rental_days' => 180,
                'weight' => 65.0,
                'dimensions' => '150 x 40 x 30 cm'
            ],
            [
                'name' => 'Conteneurs isothermes 500L x4',
                'short_description' => 'Transport produits frais ferme-consommateur',
                'rental_price_per_day' => 18.50,
                'deposit_amount' => 200.00,
                'min_rental_days' => 1,
                'max_rental_days' => 30,
                'weight' => 85.0,
                'dimensions' => '120 x 80 x 85 cm'
            ],
            [
                'name' => 'Désinfecteur matériel UV',
                'short_description' => 'Station désinfection outils et équipements',
                'rental_price_per_day' => 22.50,
                'deposit_amount' => 245.00,
                'min_rental_days' => 7,
                'max_rental_days' => 90,
                'weight' => 25.0,
                'dimensions' => '60 x 40 x 80 cm'
            ],
            [
                'name' => 'Extracteur miel électrique',
                'short_description' => 'Extracteur centrifuge pour apiculture',
                'rental_price_per_day' => 28.50,
                'deposit_amount' => 315.00,
                'min_rental_days' => 1,
                'max_rental_days' => 15,
                'weight' => 45.0,
                'dimensions' => '80 x 80 x 100 cm'
            ],
            [
                'name' => 'Tapis de tri légumes 3m',
                'short_description' => 'Tapis roulant pour tri et conditionnement',
                'rental_price_per_day' => 32.00,
                'deposit_amount' => 350.00,
                'min_rental_days' => 3,
                'max_rental_days' => 90,
                'weight' => 95.0,
                'dimensions' => '300 x 80 x 100 cm'
            ],
            [
                'name' => 'Incubateur œufs 200 places',
                'short_description' => 'Incubateur automatique pour élevage',
                'rental_price_per_day' => 18.00,
                'deposit_amount' => 195.00,
                'min_rental_days' => 21,
                'max_rental_days' => 180,
                'weight' => 25.0,
                'dimensions' => '60 x 50 x 40 cm'
            ],
            [
                'name' => 'Ventilateur brassage air élevage',
                'short_description' => 'Ventilateur circulation air bâtiments élevage',
                'rental_price_per_day' => 15.50,
                'deposit_amount' => 170.00,
                'min_rental_days' => 7,
                'max_rental_days' => 365,
                'weight' => 35.0,
                'dimensions' => '120 x 120 x 40 cm'
            ],
            [
                'name' => 'Kit test qualité eau agricole',
                'short_description' => 'Ensemble analyse qualité eau irrigation',
                'rental_price_per_day' => 12.50,
                'deposit_amount' => 135.00,
                'min_rental_days' => 3,
                'max_rental_days' => 30,
                'weight' => 5.0,
                'dimensions' => '40 x 30 x 15 cm'
            ]
        ];

        foreach ($equipements as $index => $equipement) {
            $name = $equipement['name'];
            $slug = 'location-' . Str::slug($name);
            $sku = 'AGRI-EQUIP-' . strtoupper(Str::random(4));

            Product::create([
                'name' => $name,
                'slug' => $slug,
                'description' => $equipement['short_description'] . '. Équipement agricole professionnel en excellent état. Instructions d\'utilisation et support technique inclus. Idéal pour optimiser votre production agricole.',
                'short_description' => $equipement['short_description'],
                'type' => 'rental',
                'sku' => $sku,
                'price' => 0.00, // Produit de location uniquement
                'rental_price_per_day' => $equipement['rental_price_per_day'],
                'deposit_amount' => $equipement['deposit_amount'],
                'min_rental_days' => $equipement['min_rental_days'],
                'max_rental_days' => $equipement['max_rental_days'],
                'quantity' => 25, // Stock défini par l'utilisateur pour équipements
                'critical_threshold' => 3,
                'low_stock_threshold' => 5,
                'out_of_stock_threshold' => 1,
                'weight' => $equipement['weight'],
                'dimensions' => $equipement['dimensions'],
                'unit_symbol' => 'pièce',
                'is_active' => true,
                'is_featured' => $index < 5, // Les 5 premiers sont mis en avant
                'category_id' => $normalCategoryId, // Catégorie produit normale correspondante
                'rental_category_id' => $rentalCategoryId, // Catégorie de location spécialisée
                'meta_title' => $name . ' - Location d\'équipements agricoles - FarmShop',
                'meta_description' => 'Location ' . strtolower($name) . ' de qualité professionnelle. ' . $equipement['short_description'] . '. Dès ' . $equipement['rental_price_per_day'] . '€/jour, caution ' . $equipement['deposit_amount'] . '€.',
                'meta_keywords' => 'location, ' . strtolower($name) . ', équipement agricole, matériel professionnel, exploitation, FarmShop'
            ]);
        }

        $this->command->info("✅ 30 équipements agricoles créés avec 25 unités de stock chacun");
    }
}
