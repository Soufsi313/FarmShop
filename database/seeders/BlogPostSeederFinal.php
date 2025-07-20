<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\User;

class BlogPostSeederFinal extends Seeder
{
    public function run()
    {
        // Supprimer les articles existants
        BlogPost::withTrashed()->forceDelete();

        // Récupérer les catégories et l'utilisateur admin
        $categories = BlogCategory::all()->keyBy('name');
        $admin = User::where('role', 'Admin')->first();

        if (!$admin) {
            $admin = User::first();
        }

        if (!$admin) {
            $this->command->error('Aucun utilisateur trouvé. Veuillez créer un utilisateur d\'abord.');
            return;
        }

        $allArticles = array_merge(
            $this->getArticlesPart1(),
            $this->getArticlesPart2(),
            $this->getArticlesPart3(),
            $this->getArticlesPart4()
        );

        // Générer des dates de publication étalées sur les 6 derniers mois
        $startDate = Carbon::now()->subMonths(6);
        $publishedCount = 0;

        foreach ($allArticles as $index => $article) {
            $category = $categories->get($article['category']);
            
            if (!$category) {
                $this->command->warn("Catégorie '{$article['category']}' non trouvée pour l'article '{$article['title']}'");
                continue;
            }

            $publishedAt = $startDate->copy()->addDays($index * 1.1);
            
            BlogPost::create([
                'blog_category_id' => $category->id,
                'title' => $article['title'],
                'slug' => Str::slug($article['title']),
                'excerpt' => $article['excerpt'],
                'content' => $article['content'],
                'featured_image' => null,
                'gallery' => null,
                'status' => 'published',
                'published_at' => $publishedAt,
                'scheduled_for' => null,
                'meta_title' => $article['meta_title'],
                'meta_description' => $article['meta_description'],
                'meta_keywords' => implode(', ', $article['tags']),
                'metadata' => [
                    'featured' => $index < 10,
                    'priority' => $index < 5 ? 'high' : 'normal',
                    'author_bio' => 'Expert en agriculture durable et jardinage bio'
                ],
                'tags' => $article['tags'],
                'views_count' => rand(50, 1500),
                'likes_count' => rand(5, 80),
                'shares_count' => rand(0, 30),
                'comments_count' => rand(0, 25),
                'reading_time' => $article['reading_time'],
                'allow_comments' => true,
                'is_featured' => $index < 8,
                'is_sticky' => $index < 3,
                'author_id' => $admin->id,
                'last_edited_by' => $admin->id,
                'last_edited_at' => $publishedAt,
            ]);
            
            $publishedCount++;
        }

        $this->command->info("✅ {$publishedCount} articles de blog créés avec succès !");
        $this->command->info("📊 Répartition par catégorie :");
        
        // Afficher le nombre d'articles par catégorie
        $counts = BlogPost::join('blog_categories', 'blog_posts.blog_category_id', '=', 'blog_categories.id')
                    ->selectRaw('blog_categories.name, COUNT(*) as count')
                    ->groupBy('blog_categories.name')
                    ->get();
        
        foreach ($counts as $count) {
            $this->command->info("   {$count->name}: {$count->count} articles");
        }
    }

    private function getArticlesPart1()
    {
        return [
            // Le saviez-vous (6 articles)
            [
                'category' => 'Le saviez-vous',
                'title' => 'Les vers de terre : architectes invisibles de nos sols',
                'content' => '<h2>Le rôle méconnu des vers de terre</h2><p>Saviez-vous qu\'un seul ver de terre peut ingérer son propre poids en terre chaque jour ? Ces petits ouvriers infatigables transforment notre sol en véritable écosystème fertile.</p><h3>Des chiffres impressionnants</h3><p>Dans un mètre carré de prairie, on peut compter jusqu\'à 400 vers de terre ! Ils creusent jusqu\'à 7 000 km de galeries par hectare, soit plus que la distance Paris-New York.</p><h3>Un impact écologique majeur</h3><p>Les vers de terre produisent leurs propres enzymes digestives et enrichissent le sol de leurs déjections, créant un humus de qualité exceptionnelle. Ils aèrent également la terre, facilitant la pénétration de l\'eau et des racines.</p>',
                'excerpt' => 'Découvrez pourquoi les vers de terre sont considérés comme les meilleurs alliés du jardinier et leur impact surprenant sur la fertilité des sols.',
                'meta_title' => 'Vers de Terre : Secrets des Architectes du Sol | FarmShop',
                'meta_description' => 'Les vers de terre transforment nos sols en écosystèmes fertiles. Découvrez leurs secrets et leur impact méconnu sur l\'agriculture.',
                'tags' => ['vers de terre', 'sol', 'fertilité', 'écosystème', 'nature'],
                'reading_time' => 5
            ],
            [
                'category' => 'Le saviez-vous',
                'title' => 'L\'intelligence cachée des plantes : communication et entraide',
                'content' => '<h2>Les plantes communiquent entre elles</h2><p>Loin d\'être des êtres passifs, les plantes possèdent des systèmes de communication sophistiqués. Elles s\'échangent des informations et s\'entraident face aux dangers.</p><h3>Le réseau mycorhizien</h3><p>Grâce aux champignons mycorhiziens, les plantes créent un véritable "internet végétal". Ces réseaux souterrains permettent l\'échange de nutriments et d\'informations d\'alerte.</p><h3>Signaux chimiques</h3><p>Lorsqu\'une plante est attaquée par des parasites, elle émet des composés volatils pour prévenir ses voisines. Ces dernières peuvent alors renforcer préventivement leurs défenses naturelles.</p><h3>Solidarité intergénérationnelle</h3><p>Les arbres adultes nourrissent littéralement leurs jeunes pousses via le réseau mycorhizien, assurant la survie de leur descendance même dans l\'ombre de la canopée.</p>',
                'excerpt' => 'Découvrez comment les plantes communiquent, s\'entraident et forment des réseaux d\'intelligence collective pour survivre.',
                'meta_title' => 'Intelligence des Plantes : Communication Secrète | FarmShop',
                'meta_description' => 'Les plantes communiquent et s\'entraident via des réseaux complexes. Découvrez l\'intelligence cachée du monde végétal.',
                'tags' => ['communication plantes', 'mycorhizes', 'intelligence végétale', 'écosystème', 'nature'],
                'reading_time' => 6
            ]
        ];
    }

    private function getArticlesPart2()
    {
        return [
            // Trucs et astuces (15 articles) - échantillon
            [
                'category' => 'Trucs et astuces',
                'title' => 'Marc de café : 10 utilisations géniales au jardin',
                'content' => '<h2>Le marc de café, trésor du jardinier</h2><p>Ne jetez plus votre marc de café ! Ce résidu de notre boisson favorite cache de nombreuses vertus pour nos jardins. Découvrez 10 façons astucieuses de le recycler.</p><h3>Engrais naturel et compost</h3><p>Riche en azote, phosphore et potassium, le marc de café enrichit le compost. Mélangez-le avec des matières carbonées pour équilibrer le rapport C/N.</p><h3>Répulsif naturel</h3><p>Limaces et escargots détestent la caféine ! Étalez le marc autour de vos plants sensibles. Renouvelez après chaque pluie pour maintenir l\'efficacité.</p><h3>Activateur de germination</h3><p>Mélangé à la terre de semis, le marc améliore la structure du sol et favorise la germination des graines de radis et carottes.</p>',
                'excerpt' => '10 utilisations astucieuses du marc de café au jardin : engrais, répulsif, activateur. Recyclez malin !',
                'meta_title' => 'Marc de Café : 10 Utilisations Géniales au Jardin | FarmShop',
                'meta_description' => 'Découvrez 10 façons d\'utiliser le marc de café au jardin : engrais naturel, répulsif limaces, activateur compost.',
                'tags' => ['marc de café', 'recyclage', 'engrais naturel', 'répulsif', 'astuces jardin'],
                'reading_time' => 7
            ]
        ];
    }

    private function getArticlesPart3()
    {
        return [
            // Agriculture Durable (5 articles) - échantillon
            [
                'category' => 'Agriculture Durable',
                'title' => 'Agroécologie : principes et mise en pratique',
                'content' => '<h2>Révolution douce des campagnes</h2><p>Biodiversité fonctionnelle, cycles fermés, autonomie énergétique : l\'agroécologie réconcilie productivité et respect environnemental.</p>',
                'excerpt' => 'Agroécologie : principes biodiversité, sol vivant, autonomie. Transition vers agriculture productive et respectueuse.',
                'meta_title' => 'Agroécologie : Principes et Mise en Pratique | FarmShop',
                'meta_description' => 'Guide agroécologie : principes, pratiques concrètes, transition. Agriculture durable productive et écologique.',
                'tags' => ['agroécologie', 'agriculture durable', 'biodiversité', 'sol vivant', 'transition'],
                'reading_time' => 11
            ]
        ];
    }

    private function getArticlesPart4()
    {
        return [
            // Autres catégories - échantillon final pour test
            [
                'category' => 'Matériel et Outils',
                'title' => 'Outils manuels : choisir l\'essentiel',
                'content' => '<h2>Prolongements de nos mains</h2><p>Bêche qualité, serfouette polyvalente, sécateur affûté : bien choisir ses outils transforme jardinage.</p>',
                'excerpt' => 'Outils manuels jardinage : bêche, serfouette, sécateur. Choisir qualité, entretien, budget optimisé.',
                'meta_title' => 'Outils Manuels : Choisir l\'Essentiel | FarmShop',
                'meta_description' => 'Guide outils manuels jardinage : critères qualité, entretien, budget. Choisir l\'essentiel pour efficacité.',
                'tags' => ['outils manuels', 'jardinage', 'bêche', 'serfouette', 'qualité'],
                'reading_time' => 7
            ]
        ];
    }
}
