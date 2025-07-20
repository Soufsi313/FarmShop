<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\User;

class BlogPostBatch1Seeder extends Seeder
{
    /**
     * Seeder pour 4 catégories : Le saviez-vous, Trucs et astuces, Potager et Légumes, Fruits et Verger
     * 5 articles par catégorie = 20 articles au total
     */
    public function run()
    {
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

        $articles = $this->getArticles();
        $publishedCount = 0;
        $startDate = Carbon::now()->subMonths(6);

        foreach ($articles as $index => $article) {
            $category = $categories->get($article['category']);
            
            if (!$category) {
                $this->command->warn("Catégorie '{$article['category']}' non trouvée pour l'article '{$article['title']}'");
                continue;
            }

            $publishedAt = $startDate->copy()->addDays($index * 3); // Étaler sur 6 mois
            
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
                    'batch' => 1,
                    'featured' => $index < 5,
                    'priority' => $index < 3 ? 'high' : 'normal'
                ],
                'tags' => $article['tags'],
                'views_count' => rand(50, 800),
                'likes_count' => rand(5, 60),
                'shares_count' => rand(0, 25),
                'comments_count' => rand(0, 20),
                'reading_time' => $article['reading_time'],
                'allow_comments' => true,
                'is_featured' => $index < 3,
                'is_sticky' => $index < 1,
                'author_id' => $admin->id,
                'last_edited_by' => $admin->id,
                'last_edited_at' => $publishedAt,
            ]);
            
            $publishedCount++;
        }

        $this->command->info("✅ Batch 1 : {$publishedCount} articles créés pour 4 catégories !");
    }

    private function getArticles()
    {
        return [
            // Le saviez-vous (5 articles)
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
                'content' => '<h2>Les plantes communiquent entre elles</h2><p>Loin d\'être des êtres passifs, les plantes possèdent des systèmes de communication sophistiqués. Elles s\'échangent des informations et s\'entraident face aux dangers.</p><h3>Le réseau mycorhizien</h3><p>Grâce aux champignons mycorhiziens, les plantes créent un véritable "internet végétal". Ces réseaux souterrains permettent l\'échange de nutriments et d\'informations d\'alerte.</p><h3>Signaux chimiques</h3><p>Lorsqu\'une plante est attaquée par des parasites, elle émet des composés volatils pour prévenir ses voisines. Ces dernières peuvent alors renforcer préventivement leurs défenses naturelles.</p>',
                'excerpt' => 'Découvrez comment les plantes communiquent, s\'entraident et forment des réseaux d\'intelligence collective pour survivre.',
                'meta_title' => 'Intelligence des Plantes : Communication Secrète | FarmShop',
                'meta_description' => 'Les plantes communiquent et s\'entraident via des réseaux complexes. Découvrez l\'intelligence cachée du monde végétal.',
                'tags' => ['communication plantes', 'mycorhizes', 'intelligence végétale', 'écosystème', 'nature'],
                'reading_time' => 6
            ],
            [
                'category' => 'Le saviez-vous',
                'title' => 'Pourquoi les abeilles dansent-elles ?',
                'content' => '<h2>La danse des abeilles : un langage sophistiqué</h2><p>Les abeilles possèdent l\'un des systèmes de communication les plus fascinants du règne animal. Leur "danse frétillante" transmet des informations précises sur la localisation des sources de nectar.</p><h3>Décryptage de la chorégraphie</h3><p>L\'angle de la danse indique la direction par rapport au soleil, tandis que la durée du frétillement renseigne sur la distance. Une seconde de danse = environ 1 kilomètre de distance !</p><h3>Précision GPS naturelle</h3><p>Les abeilles peuvent ainsi guider leurs sœurs vers une source de nourriture située à plusieurs kilomètres avec une précision remarquable, surpassant nos technologies modernes.</p>',
                'excerpt' => 'Découvrez le langage secret des abeilles et comment leur danse transmet des informations géographiques précises.',
                'meta_title' => 'Danse des Abeilles : Langage Secret de la Ruche | FarmShop',
                'meta_description' => 'La danse des abeilles révèle un système de communication sophistiqué. Découvrez ce langage fascinant.',
                'tags' => ['abeilles', 'communication', 'danse', 'pollinisation', 'nature'],
                'reading_time' => 4
            ],
            [
                'category' => 'Le saviez-vous',
                'title' => 'Les champignons : ni plantes ni animaux',
                'content' => '<h2>Le troisième règne vivant</h2><p>Les champignons constituent un règne à part entière, distinct des plantes et des animaux. Ils possèdent des caractéristiques uniques qui en font des êtres fascinants.</p><h3>Plus proches des animaux</h3><p>Contrairement aux idées reçues, les champignons sont génétiquement plus proches des animaux que des plantes. Ils ne pratiquent pas la photosynthèse et doivent "chasser" leur nourriture.</p><h3>Réseaux souterrains géants</h3><p>Le plus grand organisme vivant de la planète est un champignon ! En Oregon, un Armillaria ostoyae s\'étend sur plus de 965 hectares et âgé de 2400 ans.</p>',
                'excerpt' => 'Les champignons forment un règne fascinant, ni plante ni animal. Découvrez leurs secrets et records étonnants.',
                'meta_title' => 'Champignons : Secrets du Troisième Règne Vivant | FarmShop',
                'meta_description' => 'Les champignons ne sont ni plantes ni animaux. Découvrez ce règne fascinant et ses caractéristiques uniques.',
                'tags' => ['champignons', 'règne vivant', 'mycologie', 'nature', 'records'],
                'reading_time' => 5
            ],
            [
                'category' => 'Le saviez-vous',
                'title' => 'La photosynthèse : miracle de la nature',
                'content' => '<h2>L\'usine à énergie solaire des plantes</h2><p>La photosynthèse est sans doute le processus biologique le plus important de notre planète. Sans elle, la vie telle que nous la connaissons n\'existerait pas.</p><h3>Transformation miraculeuse</h3><p>En combinant eau, gaz carbonique et lumière solaire, les plantes produisent glucose et oxygène. Ce processus alimente toute la chaîne alimentaire terrestre.</p><h3>Rendement énergétique</h3><p>Une feuille capture moins de 5% de l\'énergie solaire, mais cette efficacité "modeste" suffit à nourrir la planète entière. Les panneaux solaires les plus performants atteignent 20% de rendement.</p>',
                'excerpt' => 'La photosynthèse, processus vital qui transforme lumière en énergie. Découvrez ce miracle quotidien de la nature.',
                'meta_title' => 'Photosynthèse : Miracle Énergétique des Plantes | FarmShop',
                'meta_description' => 'La photosynthèse transforme lumière en énergie vitale. Découvrez ce processus fondamental de la nature.',
                'tags' => ['photosynthèse', 'énergie solaire', 'plantes', 'biologie', 'écologie'],
                'reading_time' => 4
            ],

            // Trucs et astuces (5 articles)
            [
                'category' => 'Trucs et astuces',
                'title' => 'Marc de café : 10 utilisations géniales au jardin',
                'content' => '<h2>Le marc de café, trésor du jardinier</h2><p>Ne jetez plus votre marc de café ! Ce résidu de notre boisson favorite cache de nombreuses vertus pour nos jardins. Découvrez 10 façons astucieuses de le recycler.</p><h3>Engrais naturel et compost</h3><p>Riche en azote, phosphore et potassium, le marc de café enrichit le compost. Mélangez-le avec des matières carbonées pour équilibrer le rapport C/N.</p><h3>Répulsif naturel</h3><p>Limaces et escargots détestent la caféine ! Étalez le marc autour de vos plants sensibles. Renouvelez après chaque pluie pour maintenir l\'efficacité.</p><h3>Activateur de germination</h3><p>Mélangé à la terre de semis, le marc améliore la structure du sol et favorise la germination des graines de radis et carottes.</p>',
                'excerpt' => '10 utilisations astucieuses du marc de café au jardin : engrais, répulsif, activateur. Recyclez malin !',
                'meta_title' => 'Marc de Café : 10 Utilisations Géniales au Jardin | FarmShop',
                'meta_description' => 'Découvrez 10 façons d\'utiliser le marc de café au jardin : engrais naturel, répulsif limaces, activateur compost.',
                'tags' => ['marc de café', 'recyclage', 'engrais naturel', 'répulsif', 'astuces jardin'],
                'reading_time' => 7
            ],
            [
                'category' => 'Trucs et astuces',
                'title' => 'Coquilles d\'œufs : 5 astuces de grand-mère',
                'content' => '<h2>Les coquilles d\'œufs, alliées du jardinier</h2><p>Riches en calcium et faciles à recycler, les coquilles d\'œufs offrent de multiples usages au jardin. Nos grands-mères connaissaient déjà leurs secrets !</p><h3>Amendement calcaire naturel</h3><p>Broyées finement, les coquilles apportent du calcium assimilable aux tomates et préviennent la maladie du cul noir. Incorporez-les au compost ou directement au pied des plants.</p><h3>Barrière anti-limaces</h3><p>Concassées grossièrement, elles forment une barrière rugueuse que détestent limaces et escargots. Renouvelez après les pluies importantes.</p><h3>Godets de semis biodégradables</h3><p>Utilisez les demi-coquilles comme godets naturels. Plantez directement en terre, la coquille se décomposera en enrichissant le sol.</p>',
                'excerpt' => '5 astuces de grand-mère avec les coquilles d\'œufs : calcium, anti-limaces, godets biodégradables.',
                'meta_title' => 'Coquilles d\'Œufs : 5 Astuces de Grand-mère | FarmShop',
                'meta_description' => '5 utilisations des coquilles d\'œufs au jardin : amendement calcaire, anti-limaces, godets biodégradables.',
                'tags' => ['coquilles œufs', 'calcium', 'anti-limaces', 'recyclage', 'astuces grand-mère'],
                'reading_time' => 5
            ],
            [
                'category' => 'Trucs et astuces',
                'title' => 'Arrosage malin : 7 techniques d\'économie d\'eau',
                'content' => '<h2>Économiser l\'eau sans sacrifier ses cultures</h2><p>Face aux restrictions d\'eau de plus en plus fréquentes, optimiser l\'arrosage devient crucial. Découvrez 7 techniques pour réduire votre consommation de 50%.</p><h3>Paillage généralisé</h3><p>Une couche de 5-10cm de paillis réduit l\'évaporation de 70%. Utilisez tontes, feuilles mortes, paille ou copeaux selon vos disponibilités.</p><h3>Arrosage au goutte-à-goutte</h3><p>System le plus efficace : 90% de l\'eau atteint les racines vs 50% pour l\'arrosoir. Investissement rentabilisé en une saison.</p><h3>Horaires stratégiques</h3><p>Arrosez tôt le matin ou en soirée. Évitez 10h-16h où 80% de l\'eau s\'évapore avant d\'atteindre les racines.</p>',
                'excerpt' => '7 techniques d\'arrosage malin pour économiser 50% d\'eau : paillage, goutte-à-goutte, horaires optimaux.',
                'meta_title' => 'Arrosage Malin : 7 Techniques d\'Économie d\'Eau | FarmShop',
                'meta_description' => '7 techniques pour économiser l\'eau au jardin : paillage, goutte-à-goutte, horaires. Réduisez votre consommation de 50%.',
                'tags' => ['arrosage', 'économie eau', 'paillage', 'goutte-à-goutte', 'techniques'],
                'reading_time' => 6
            ],
            [
                'category' => 'Trucs et astuces',
                'title' => 'Semis réussis : 8 erreurs à éviter absolument',
                'content' => '<h2>Les pièges classiques du semis</h2><p>Graines qui ne germent pas, plantules qui filent, fonte des semis... Découvrez les 8 erreurs les plus courantes et comment les éviter pour des semis réussis à coup sûr.</p><h3>Erreur n°1 : Semer trop profond</h3><p>Règle d\'or : profondeur = 2-3 fois la taille de la graine. Les graines fines (carottes, laitues) se sèment en surface, juste recouvertes de terreau fin.</p><h3>Erreur n°2 : Arrosage brutal</h3><p>Utilisez un pulvérisateur pour les premiers jours. L\'arrosoir fait "voler" les graines et peut les enterrer trop profondément.</p><h3>Erreur n°3 : Température inadaptée</h3><p>Respectez les besoins : radis germent à 5°C, tomates ont besoin de 20°C minimum. Un tapis chauffant peut s\'avérer indispensable.</p>',
                'excerpt' => '8 erreurs courantes en semis et leurs solutions : profondeur, arrosage, température. Réussir ses semis à coup sûr.',
                'meta_title' => 'Semis Réussis : 8 Erreurs à Éviter Absolument | FarmShop',
                'meta_description' => 'Évitez les 8 erreurs courantes en semis : profondeur, arrosage, température. Guide pour réussir vos semis.',
                'tags' => ['semis', 'erreurs', 'germination', 'techniques', 'conseils'],
                'reading_time' => 8
            ],
            [
                'category' => 'Trucs et astuces',
                'title' => 'Compost express : prêt en 3 mois au lieu de 12',
                'content' => '<h2>Accélérer la décomposition naturellement</h2><p>Transformer vos déchets verts en compost mûr en seulement 3 mois, c\'est possible ! Découvrez les techniques d\'activation naturelle pour un compost express.</p><h3>Technique du retournement</h3><p>Retournez votre tas toutes les 3 semaines au lieu de 2 mois. Cette aération accélère le processus de décomposition de façon spectaculaire.</p><h3>Activateurs naturels</h3><p>Ortie fraîche, consoude ou purin dilué activent les micro-organismes. Une poignée d\'orties tous les 20cm de hauteur suffit.</p><h3>Équilibre parfait</h3><p>Alternez couches vertes (azote) et brunes (carbone) dans un ratio 1/3 - 2/3. Maintenez l\'humidité d\'une éponge essorée.</p>',
                'excerpt' => 'Compost express en 3 mois : retournements fréquents, activateurs naturels, équilibre parfait. Accélérez la décomposition.',
                'meta_title' => 'Compost Express : Prêt en 3 Mois au lieu de 12 | FarmShop',
                'meta_description' => 'Techniques pour accélérer le compost : retournements, activateurs naturels. Compost mûr en 3 mois seulement.',
                'tags' => ['compost express', 'accélération', 'activateurs naturels', 'retournement', 'décomposition'],
                'reading_time' => 6
            ],

            // Potager et Legumes (5 articles)
            [
                'category' => 'Potager et Legumes',
                'title' => 'Tomates : 10 variétés anciennes à redécouvrir',
                'content' => '<h2>Retrouver les saveurs d\'antan</h2><p>Rose de Berne, Noire de Crimée, Ananas... Les variétés anciennes de tomates offrent une palette de goûts, couleurs et formes inégalée. Redécouvrez ces trésors du patrimoine potager.</p><h3>Tomates colorées exceptionnelles</h3><p><strong>Noire de Crimée :</strong> Chair rouge sombre, saveur intense et sucrée. Gros fruit de 300-500g, parfait en salade.</p><p><strong>Green Zebra :</strong> Rayures vertes et jaunes, acidité rafraîchissante. Originalité garantie sur la table.</p><h3>Variétés productives</h3><p><strong>Rose de Berne :</strong> Rose tendre, chair fondante, production abondante. Résistante au mildiou, idéale débutants.</p><p><strong>Cœur de Bœuf :</strong> Gros fruits côtelés jusqu\'à 800g. Chair dense, peu de graines, parfait farcies.</p>',
                'excerpt' => '10 variétés anciennes de tomates : Rose de Berne, Noire de Crimée, Ananas. Redécouvrez les saveurs authentiques.',
                'meta_title' => 'Tomates : 10 Variétés Anciennes à Redécouvrir | FarmShop',
                'meta_description' => 'Découvrez 10 variétés anciennes de tomates : Rose de Berne, Noire de Crimée. Saveurs authentiques du patrimoine.',
                'tags' => ['tomates anciennes', 'variétés', 'patrimoine', 'saveurs', 'potager'],
                'reading_time' => 8
            ],
            [
                'category' => 'Potager et Legumes',
                'title' => 'Radis : cultures express toute l\'année',
                'content' => '<h2>Le légume le plus rapide du potager</h2><p>18 jours de la graine à l\'assiette ! Le radis est parfait pour combler les vides au potager et initier les enfants au jardinage. Découvrez les secrets d\'une culture continue.</p><h3>Variétés selon les saisons</h3><p><strong>Printemps :</strong> Radis de 18 jours, Cherry Belle, Flamboyant. Croissance ultra-rapide, saveur douce.</p><p><strong>Été :</strong> Varieties résistantes à la montée en graines comme Sezanne ou National.</p><h3>Semis échelonnés</h3><p>Semez une ligne tous les 8-10 jours pour une récolte continue. Évitez les grosses quantités d\'un coup qui montent rapidement en graines.</p><h3>Astuce anti-vers</h3><p>Compagnonnage avec les carottes : les radis éloignent la mouche de la carotte, les carottes repoussent l\'altise du radis.</p>',
                'excerpt' => 'Culture express des radis toute l\'année : variétés saisonnières, semis échelonnés, compagnonnage intelligent.',
                'meta_title' => 'Radis : Cultures Express Toute l\'Année | FarmShop',
                'meta_description' => 'Guide culture radis : variétés rapides, semis échelonnés, compagnonnage. Récoltes en 18 jours.',
                'tags' => ['radis', 'culture rapide', 'semis échelonnés', 'compagnonnage', 'légumes'],
                'reading_time' => 5
            ],
            [
                'category' => 'Potager et Legumes',
                'title' => 'Courgettes : éviter la surproduction',
                'content' => '<h2>Maîtriser la générosité des courgettes</h2><p>Un pied de courgette peut produire 10-15 fruits ! Apprenez à contrôler cette abondance pour éviter le gaspillage et maintenir la productivité.</p><h3>Planification intelligente</h3><p>2-3 pieds suffisent pour une famille de 4 personnes. Échelonnez les plantations de 3 semaines pour étaler la production.</p><h3>Récolte régulière obligatoire</h3><p>Cueillez tous les 2-3 jours quand les fruits font 15-20cm. Les courgettes oubliées épuisent la plante et stoppent la production.</p><h3>Techniques de conservation</h3><p>Râpées et congelées, en pickles, séchées en chips... Multipliez les préparations pour valoriser l\'excédent sans lassitude.</p>',
                'excerpt' => 'Maîtriser la production de courgettes : planification, récolte régulière, conservation astucieuse pour éviter le gaspillage.',
                'meta_title' => 'Courgettes : Éviter la Surproduction | FarmShop',
                'meta_description' => 'Guide pour maîtriser la production de courgettes : planification, récolte, conservation. Évitez le gaspillage.',
                'tags' => ['courgettes', 'production', 'récolte', 'conservation', 'gaspillage'],
                'reading_time' => 6
            ],
            [
                'category' => 'Potager et Legumes',
                'title' => 'Carottes : réussir semis et éclaircissage',
                'content' => '<h2>Les secrets de la carotte parfaite</h2><p>Germination capricieuse, éclaircissage délicat : la carotte demande quelques attentions particulières. Maîtrisez ces étapes clés pour des racines droites et savoureuses.</p><h3>Préparation du sol optimale</h3><p>Sol meuble sur 30cm de profondeur, sans cailloux ni fumure fraîche. Les carottes fourchent dans un sol mal préparé ou trop riche.</p><h3>Semis en conditions</h3><p>Terre maintenue humide 15 jours minimum. Semis sous voile en début de saison, paillis fin pour éviter la formation de croûte.</p><h3>Éclaircissage progressif</h3><p>Premier éclaircissage à 3-4 feuilles, espacer de 3-4cm. Deuxième passage à 8cm pour les variétés longues. Arroser avant pour faciliter l\'arrachage.</p>',
                'excerpt' => 'Réussir les carottes : préparation sol, semis en conditions, éclaircissage progressif. Racines droites garanties.',
                'meta_title' => 'Carottes : Réussir Semis et Éclaircissage | FarmShop',
                'meta_description' => 'Guide culture carottes : préparation sol, semis, éclaircissage. Techniques pour des carottes droites et savoureuses.',
                'tags' => ['carottes', 'semis', 'éclaircissage', 'préparation sol', 'culture'],
                'reading_time' => 7
            ],
            [
                'category' => 'Potager et Legumes',
                'title' => 'Haricots verts : variétés naines vs grimpantes',
                'content' => '<h2>Choisir selon son espace et ses besoins</h2><p>Nains ou grimpants ? Chaque type de haricot a ses avantages. Découvrez lequel correspond le mieux à votre potager et vos habitudes de jardinage.</p><h3>Haricots nains : simplicité et précocité</h3><p><strong>Avantages :</strong> Pas de tuteurage, récolte groupée en 60 jours, résistance au vent. Idéals pour conserves et congélation.</p><p><strong>Variétés :</strong> Contender, Delinel, Purple Queen. Production concentrée sur 3 semaines.</p><h3>Haricots grimpants : productivité et durée</h3><p><strong>Avantages :</strong> Production étalée 2-3 mois, rendement supérieur/m², économie d\'espace. Parfaits pour consommation fraîche.</p><p><strong>Variétés :</strong> Blauhilde violette, Emerite, Saint Fiacre. Hauteur 2-3 mètres.</p><h3>Culture optimisée</h3><p>Semis après dernières gelées, sol réchauffé à 12°C minimum. Inoculation rhizobium recommandée pour la fixation d\'azote.</p>',
                'excerpt' => 'Haricots nains vs grimpants : avantages, variétés, culture. Choisir selon espace et besoins du jardinier.',
                'meta_title' => 'Haricots Verts : Variétés Naines vs Grimpantes | FarmShop',
                'meta_description' => 'Comparatif haricots nains et grimpants : avantages, variétés, culture. Guide pour bien choisir.',
                'tags' => ['haricots verts', 'nains', 'grimpants', 'variétés', 'comparatif'],
                'reading_time' => 6
            ],

            // Fruits et Verger (5 articles)
            [
                'category' => 'Fruits et Verger',
                'title' => 'Fraisiers : plantation et entretien pour gros rendements',
                'content' => '<h2>Optimiser sa fraisière</h2><p>1kg de fraises par pied et par an, c\'est possible ! Découvrez les secrets d\'une plantation réussie et d\'un entretien adapté pour maximiser vos récoltes.</p><h3>Plantation optimale</h3><p><strong>Période :</strong> Fin août à octobre pour une production dès le printemps suivant. Plants en godets plus chers mais reprise garantie.</p><p><strong>Espacement :</strong> 30cm entre plants, 40cm entre rangs. Prévoir allées de 60cm pour faciliter récolte et entretien.</p><h3>Sol et exposition</h3><p>Sol drainant, légèrement acide (pH 6-6,5), riche en humus. Exposition ensoleillée mais mi-ombre tolérée en région chaude.</p><h3>Entretien productif</h3><p>Paillage obligatoire : garde fruits propres, conserve humidité, limite adventices. Suppression stolons pour concentrer énergie sur production.</p>',
                'excerpt' => 'Plantation et entretien fraisiers pour gros rendements : période, espacement, sol, paillage. 1kg/pied possible.',
                'meta_title' => 'Fraisiers : Plantation et Entretien pour Gros Rendements | FarmShop',
                'meta_description' => 'Guide plantation fraisiers : période, espacement, entretien. Techniques pour maximiser rendements jusqu\'à 1kg/pied.',
                'tags' => ['fraisiers', 'plantation', 'rendements', 'entretien', 'fruits'],
                'reading_time' => 7
            ],
            [
                'category' => 'Fruits et Verger',
                'title' => 'Taille des fruitiers : quand et comment s\'y prendre',
                'content' => '<h2>L\'art de la taille fruitière</h2><p>Taille d\'hiver, taille verte, taille de formation : chaque intervention a son moment et ses objectifs. Apprenez à tailler comme un pro pour des arbres productifs et sains.</p><h3>Taille d\'hiver (décembre-février)</h3><p><strong>Objectifs :</strong> Formation charpente, élimination bois mort, aération couronne. Intervention sur bois dormant, cicatrisation optimale.</p><p><strong>Technique :</strong> Coupes nettes au sécateur affûté, biseau opposé au bourgeon. Mastiquer plaies >3cm de diamètre.</p><h3>Taille verte (mai-août)</h3><p><strong>Pommiers/Poiriers :</strong> Pincement gourmands, suppression rejets. Favorise mise à fruit et calibre.</p><p><strong>Pêchers :</strong> Taille après récolte, renouvellement branches fructifères. Évite gommose hivernale.</p>',
                'excerpt' => 'Taille fruitiers : taille d\'hiver, taille verte, techniques. Quand et comment tailler pour productivité optimale.',
                'meta_title' => 'Taille des Fruitiers : Quand et Comment s\'y Prendre | FarmShop',
                'meta_description' => 'Guide taille fruitiers : taille d\'hiver, taille verte, techniques. Arbres productifs et sains.',
                'tags' => ['taille fruitiers', 'taille hiver', 'taille verte', 'techniques', 'arboriculture'],
                'reading_time' => 9
            ],
            [
                'category' => 'Fruits et Verger',
                'title' => 'Groseilliers : variétés et multiplication',
                'content' => '<h2>Petits fruits, grandes satisfactions</h2><p>Faciles à cultiver, productifs, résistants : les groseilliers méritent une place dans chaque jardin. Découvrez les meilleures variétés et comment les multiplier facilement.</p><h3>Variétés recommandées</h3><p><strong>Rouge :</strong> Jonkheer van Tets (précoce), Rovada (tardive productive). Grappes longues, gros fruits acidulés.</p><p><strong>Blanc :</strong> Blanka, Versaillaise blanche. Saveur plus douce, parfait enfants et confitures délicates.</p><h3>Multiplication par bouturage</h3><p><strong>Période :</strong> Novembre-décembre, bois aoûté de l\'année. Rameaux de 20-25cm, enterrés aux 2/3.</p><p><strong>Technique :</strong> Suppression bourgeons enterrés sauf 2-3 en base. Reprise 80% en sol drainant.</p>',
                'excerpt' => 'Groseilliers : meilleures variétés rouges et blanches, multiplication par bouturage. Culture facile et productive.',
                'meta_title' => 'Groseilliers : Variétés et Multiplication | FarmShop',
                'meta_description' => 'Guide groseilliers : variétés recommandées, multiplication bouturage. Petits fruits faciles à cultiver.',
                'tags' => ['groseilliers', 'variétés', 'bouturage', 'multiplication', 'petits fruits'],
                'reading_time' => 6
            ],
            [
                'category' => 'Fruits et Verger',
                'title' => 'Pommiers colonnaires : verger en petit espace',
                'content' => '<h2>Des pommes dans 1m²</h2><p>Balcons, terrasses, petits jardins : les pommiers colonnaires permettent de créer un verger minimal. Production surprenante dans un encombrement réduit !</p><h3>Principe et avantages</h3><p><strong>Forme naturelle :</strong> Croissance verticale sans branches latérales développées. Hauteur 2-3m, largeur 50cm maximum.</p><p><strong>Production :</strong> 5-10kg de pommes dès la 3ème année. Récolte facile, entretien minimal.</p><h3>Variétés adaptées</h3><p><strong>Rondo :</strong> Pommes rouges, résistante maladies. Floraison tardive évite gelées printanières.</p><p><strong>Bolero :</strong> Pommes jaune-rouge, croquantes. Excellente conservation jusqu\'en mars.</p><h3>Culture en pot possible</h3><p>Bac 40cm minimum, arrosage suivi, engrais régulier. Hivernage hors gel en région froide.</p>',
                'excerpt' => 'Pommiers colonnaires : verger en petit espace, variétés adaptées, culture en pot. Des pommes dans 1m².',
                'meta_title' => 'Pommiers Colonnaires : Verger en Petit Espace | FarmShop',
                'meta_description' => 'Guide pommiers colonnaires : culture petit espace, variétés, production. Verger sur balcon possible.',
                'tags' => ['pommiers colonnaires', 'petit espace', 'verger', 'balcon', 'variétés'],
                'reading_time' => 6
            ],
            [
                'category' => 'Fruits et Verger',
                'title' => 'Cassissiers : plantation et récolte optimales',
                'content' => '<h2>L\'or noir du jardin</h2><p>Riches en vitamine C, parfumés, polyvalents : les cassis méritent leur place au verger. Apprenez à optimiser plantation et récolte pour profiter pleinement de ces baies d\'exception.</p><h3>Plantation réussie</h3><p><strong>Période :</strong> Octobre-novembre ou février-mars. Éviter périodes de gel et sol détrempé.</p><p><strong>Sol :</strong> Frais, humifère, légèrement acide. Paillage épais obligatoire, racines superficielles sensibles sécheresse.</p><h3>Variétés performantes</h3><p><strong>Blackdown :</strong> Gros fruits, résistant maladies. Maturité étalée, récolte facilitée.</p><p><strong>Andega :</strong> Très productif, baies fermes. Excellente résistance à l\'oïdium.</p><h3>Récolte et conservation</h3><p>Cueillette grappes entières à pleine maturité. Congélation directe ou transformation immédiate, conservation fraîche limitée à 3 jours.</p>',
                'excerpt' => 'Cassissiers : plantation optimale, variétés performantes, récolte et conservation. L\'or noir du jardin.',
                'meta_title' => 'Cassissiers : Plantation et Récolte Optimales | FarmShop',
                'meta_description' => 'Guide cassissiers : plantation, variétés résistantes, récolte. Culture optimisée pour baies d\'exception.',
                'tags' => ['cassissiers', 'plantation', 'variétés', 'récolte', 'cassis'],
                'reading_time' => 7
            ]
        ];
    }
}
