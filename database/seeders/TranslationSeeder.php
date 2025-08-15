<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;

class TranslationSeeder extends Seeder
{
    public function run()
    {
        $this->seedProductTranslations();
        $this->seedCategoryTranslations();
        $this->seedGeneralTranslations();
    }

    private function seedProductTranslations()
    {
        $translations = json_decode(file_get_contents(__DIR__ . '/product_translations.json'), true);
        
        $products = Product::all();
        foreach ($products as $product) {
            // Traduction EN
            DB::table('product_translations')->updateOrInsert(
                ['product_id' => $product->id, 'locale' => 'en'],
                [
                    'name' => $this->translateProductName($product->name, 'en', $translations),
                    'description' => $this->translateProductDescription($product->description, 'en', $translations),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            // Traduction NL
            DB::table('product_translations')->updateOrInsert(
                ['product_id' => $product->id, 'locale' => 'nl'],
                [
                    'name' => $this->translateProductName($product->name, 'nl', $translations),
                    'description' => $this->translateProductDescription($product->description, 'nl', $translations),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }

    private function translateProductName($name, $locale, $translations)
    {
        foreach ($translations['fr'] as $french => $trans) {
            if (stripos($name, $french) !== false) {
                return isset($trans[$locale]) ? $trans[$locale] : $name;
            }
        }
        return $name;
    }

    private function translateProductDescription($description, $locale, $translations)
    {
        if (!$description) return null;
        
        foreach ($translations['descriptions'] as $french => $trans) {
            if (stripos($description, $french) !== false) {
                return isset($trans[$locale]) ? $trans[$locale] : $description;
            }
        }
        return $description;
    }

    private function seedCategoryTranslations()
    {
        $categoryTranslations = [
            'Semences' => ['en' => 'Seeds', 'nl' => 'Zaden'],
            'Engrais' => ['en' => 'Fertilizers', 'nl' => 'Meststoffen'],
            'Machines agricoles' => ['en' => 'Agricultural Machines', 'nl' => 'Landbouwmachines'],
            'Outils' => ['en' => 'Tools', 'nl' => 'Gereedschap'],
            'Équipements' => ['en' => 'Equipment', 'nl' => 'Uitrusting'],
        ];

        $categories = Category::all();
        foreach ($categories as $category) {
            foreach (['en', 'nl'] as $locale) {
                $translatedName = $categoryTranslations[$category->name][$locale] ?? $category->name;
                
                DB::table('category_translations')->updateOrInsert(
                    ['category_id' => $category->id, 'locale' => $locale],
                    [
                        'name' => $translatedName,
                        'description' => $category->description,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }

    private function seedGeneralTranslations()
    {
        $generalTranslations = [
            'interface' => [
                'fr' => [
                    'voir_plus' => 'Voir plus',
                    'ajouter_panier' => 'Ajouter au panier',
                    'louer_maintenant' => 'Louer maintenant',
                    'prix_jour' => 'Prix par jour',
                    'prix_unite' => 'Prix unitaire',
                    'stock_disponible' => 'En stock',
                    'rupture_stock' => 'Rupture de stock',
                    'livraison_gratuite' => 'Livraison gratuite',
                    'retour_gratuit' => 'Retour gratuit',
                ],
                'en' => [
                    'voir_plus' => 'See more',
                    'ajouter_panier' => 'Add to cart',
                    'louer_maintenant' => 'Rent now',
                    'prix_jour' => 'Price per day',
                    'prix_unite' => 'Unit price',
                    'stock_disponible' => 'In stock',
                    'rupture_stock' => 'Out of stock',
                    'livraison_gratuite' => 'Free delivery',
                    'retour_gratuit' => 'Free return',
                ],
                'nl' => [
                    'voir_plus' => 'Meer bekijken',
                    'ajouter_panier' => 'Toevoegen aan winkelwagen',
                    'louer_maintenant' => 'Nu huren',
                    'prix_jour' => 'Prijs per dag',
                    'prix_unite' => 'Eenheidsprijs',
                    'stock_disponible' => 'Op voorraad',
                    'rupture_stock' => 'Niet op voorraad',
                    'livraison_gratuite' => 'Gratis levering',
                    'retour_gratuit' => 'Gratis retour',
                ],
            ]
        ];

        foreach ($generalTranslations as $group => $locales) {
            foreach ($locales as $locale => $translations) {
                foreach ($translations as $key => $value) {
                    DB::table('translations')->updateOrInsert(
                        ['group' => $group, 'key' => $key, 'locale' => $locale],
                        [
                            'value' => $value,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            }
        }
    }
}