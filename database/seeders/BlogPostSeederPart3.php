<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\User;

class BlogPostSeederPart3 extends Seeder
{
    public function run()
    {
        $categories = BlogCategory::pluck('id', 'name');
        $adminUser = User::where('role', 'admin')->first();

        $articles = $this->getArticles();

        foreach ($articles as $articleData) {
            BlogPost::create([
                'title' => $articleData['title'],
                'content' => $articleData['content'],
                'excerpt' => $articleData['excerpt'],
                'meta_title' => $articleData['meta_title'],
                'meta_description' => $articleData['meta_description'],
                'meta_keywords' => implode(',', $articleData['tags']),
                'blog_category_id' => $categories[$articleData['category']],
                'author_id' => $adminUser->id,
                'reading_time' => $articleData['reading_time'],
                'views_count' => rand(50, 500),
                'likes_count' => rand(5, 50),
                'comments_count' => rand(0, 15),
                'is_featured' => rand(0, 4) == 0,
                'status' => 'published',
                'published_at' => now()->subDays(rand(1, 180)),
                'created_at' => now()->subDays(rand(1, 200)),
                'updated_at' => now()->subDays(rand(0, 30))
            ]);
        }
    }

    private function getArticles()
    {
        return [
            // Recettes de Saison (5 articles)
            [
                'category' => 'Recettes de Saison',
                'title' => 'Recettes automne : 10 légumes oubliés à redécouvrir',
                'content' => '<h2>Trésors culinaires d\'automne</h2><p>Panais, rutabaga, crosne, topinambour : ces légumes anciens regorgent de saveurs et nutriments. Redécouvrons ensemble leurs secrets culinaires pour varier nos assiettes.</p><h3>Légumes-racines savoureux</h3><p><strong>Panais gratiné au miel :</strong> Tranches fines, miel acacia, thym frais. Cuisson 25min à 180°C, dorure parfaite.</p><p><strong>Purée rutabaga-pomme :</strong> Moitié rutabaga, moitié pomme, beurre fermier. Onctuosité incroyable, goût délicat.</p><h3>Tubercules originaux</h3><p><strong>Topinambours sautés :</strong> Lamelles à la poêle, ail, persil. Texture croquante, saveur artichaut-noisette unique.</p><p><strong>Crosnes au lard :</strong> Blanchir 5min, sauter avec lardons. Petites perles croquantes, délicatement parfumées.</p><h3>Courges déclinées</h3><p><strong>Pâtisson farci :</strong> Evidé, farce riz-champignons-noisettes. Cuisson vapeur 45min, présentation spectaculaire.</p><p><strong>Courge spaghetti gratinée :</strong> Chair effilée, sauce tomate, parmesan. Alternative pâtes originale et légère.</p>',
                'excerpt' => '10 recettes légumes oubliés automne : panais, rutabaga, topinambour, crosnes. Redécouvrir saveurs anciennes.',
                'meta_title' => 'Recettes Automne : 10 Légumes Oubliés à Redécouvrir | FarmShop',
                'meta_description' => 'Recettes automne avec légumes oubliés : panais, rutabaga, topinambour. 10 idées pour redécouvrir saveurs anciennes.',
                'tags' => ['recettes automne', 'légumes oubliés', 'panais', 'rutabaga', 'cuisine saison'],
                'reading_time' => 7
            ],
            [
                'category' => 'Recettes de Saison',
                'title' => 'Confitures maison : 12 associations originales',
                'content' => '<h2>Sublimer la récolte estivale</h2><p>Figue-lavande, abricot-romarin, fraise-basilic : sortir des sentiers battus révèle des mariages surprenants. Techniques et dosages pour confitures d\'exception.</p><h3>Mariages fruits-herbes</h3><p><strong>Abricot-romarin :</strong> 1kg abricots, 700g sucre, 3 branches romarin. Infusion 30min, cuisson classique. Parfum méditerranéen subtil.</p><p><strong>Pêche-thym citron :</strong> Pêches blanches, thym citronné frais. Association délicate, parfum estival intense.</p><h3>Fruits rouges revisités</h3><p><strong>Fraise-basilic :</strong> Feuilles ciselées ajoutées en fin cuisson. Note anisée surprenante, couleur préservée.</p><p><strong>Groseille-menthe :</strong> Acidité groseille, fraîcheur menthe. Parfaite desserts chocolat, fromages blancs.</p><h3>Associations audacieuses</h3><p><strong>Figue-lavande :</strong> Fleurs séchées infusées, retirées avant mise en pot. Provence en pot, raffinement garanti.</p><p><strong>Tomate verte-gingembre :</strong> Fin saison, tomates immatures. Épice orientale réchauffe acidité naturelle.</p><h3>Techniques de réussite</h3><p>Cuisson douce préserve saveurs, test assiette froide. Stérilisation 10min, retournement pots assure conservation.</p>',
                'excerpt' => '12 confitures originales : figue-lavande, abricot-romarin, fraise-basilic. Mariages surprenants et techniques.',
                'meta_title' => 'Confitures Maison : 12 Associations Originales | FarmShop',
                'meta_description' => 'Recettes confitures originales : figue-lavande, abricot-romarin, fraise-basilic. 12 mariages surprenants.',
                'tags' => ['confitures maison', 'recettes originales', 'fruits', 'herbes', 'conservation'],
                'reading_time' => 8
            ],
            [
                'category' => 'Recettes de Saison',
                'title' => 'Soupes d\'hiver : 15 recettes réconfortantes',
                'content' => '<h2>Chaleur et réconfort en bol</h2><p>Velouté butternut, soupe paysanne, bouillon de poule : l\'hiver appelle ces plats mijotés qui réchauffent corps et âme. 15 recettes pour traverser la saison froide.</p><h3>Veloutés onctueux</h3><p><strong>Velouté butternut-châtaigne :</strong> Courge rôtie au four, châtaignes vapeur, crème fraîche. Mixage fin, liaison parfaite.</p><p><strong>Crème de topinambour :</strong> Lait entier, noix grillées concassées. Texture soyeuse, saveur raffinée inattendue.</p><h3>Soupes rustiques</h3><p><strong>Soupe paysanne :</strong> Légumes racines, lard fumé, bouquet garni. Mijotage 2h, authenticité garantie campagne.</p><p><strong>Potage poireaux-pommes de terre :</strong> Blanc poireaux, pommes de terre charlotte. Classique indémodable, économique nourrissant.</p><h3>Bouillons fortifiants</h3><p><strong>Bouillon de poule aux vermicelles :</strong> Carcasse mijotée 3h, légumes aromatiques. Réconfort suprême jour maladie.</p><p><strong>Consommé légumes d\'hiver :</strong> Carottes, navets, céleri-rave. Clarté parfaite, goût concentré intense.</p><h3>Soupes du monde</h3><p><strong>Minestrone italien :</strong> Haricots blancs, pâtes, basilic frais. Générosité transalpine, complétude nutritionnelle.</p>',
                'excerpt' => '15 soupes d\'hiver réconfortantes : veloutés, soupes rustiques, bouillons. Chaleur et réconfort en bol.',
                'meta_title' => 'Soupes d\'Hiver : 15 Recettes Réconfortantes | FarmShop',
                'meta_description' => '15 recettes soupes hiver : veloutés butternut, soupes paysannes, bouillons. Réconfort et chaleur.',
                'tags' => ['soupes hiver', 'veloutés', 'recettes réconfortantes', 'plats chauds', 'légumes hiver'],
                'reading_time' => 9
            ],
            [
                'category' => 'Recettes de Saison',
                'title' => 'Herbes aromatiques : 20 façons de les conserver',
                'content' => '<h2>Préserver les saveurs du jardin</h2><p>Séchage, congélation, huiles aromatisées, sels parfumés : multiplier les techniques de conservation garantit herbes fraîches toute l\'année. 20 méthodes éprouvées.</p><h3>Conservation par séchage</h3><p><strong>Séchage classique :</strong> Bouquets suspendus lieu sec, aéré, sombre. Thym, romarin, sarriette s\'y prêtent parfaitement.</p><p><strong>Déshydrateur :</strong> 35°C, 8-12h selon épaisseur. Préserve couleurs, concentre arômes efficacement.</p><h3>Congélation préservant fraîcheur</h3><p><strong>Glaçons aromatiques :</strong> Herbes ciselées, eau ou huile olive. Portions individuelles, utilisation directe cuisson.</p><p><strong>Congélation nature :</strong> Basilic, persil, ciboulette en sachets. Utilisation sans décongélation plats chauds.</p><h3>Préparations aromatisées</h3><p><strong>Huiles parfumées :</strong> Macération 15 jours, filtration fine. Thym-citron, romarin-ail subliment grillades.</p><p><strong>Vinaigres aromatiques :</strong> Estragon, échalote dans vinaigre blanc. Vinaigrettes exceptionnelles, condiments raffinés.</p><h3>Mélanges créatifs</h3><p><strong>Sel aux herbes :</strong> Gros sel, herbes séchées broyées. Assaisonnement instantané, conservation excellente.</p><p><strong>Beurres composés :</strong> Beurre mou, herbes fraîches. Portions filmées, congélation 6 mois.</p>',
                'excerpt' => '20 façons conserver herbes aromatiques : séchage, congélation, huiles, sels. Préserver saveurs du jardin.',
                'meta_title' => 'Herbes Aromatiques : 20 Façons de les Conserver | FarmShop',
                'meta_description' => '20 méthodes conservation herbes : séchage, congélation, huiles aromatisées. Préserver saveurs jardin toute année.',
                'tags' => ['conservation herbes', 'aromates', 'séchage', 'congélation', 'huiles parfumées'],
                'reading_time' => 8
            ],
            [
                'category' => 'Recettes de Saison',
                'title' => 'Pickles et lacto-fermentation : guide complet',
                'content' => '<h2>Révolution des légumes fermentés</h2><p>Cornichons, choucroute, kimchi : la fermentation sublime légumes tout en multipliant leurs bienfaits. Technique ancestrale, renaissance moderne méritée.</p><h3>Bases de la lacto-fermentation</h3><p><strong>Principe :</strong> Bactéries lactiques consomment sucres, produisent acide lactique. Acidité préserve, probiotiques enrichissent microbiote.</p><p><strong>Sel nécessaire :</strong> 2-3% poids légumes, sel sans additif. Freine putréfaction, favorise bonnes bactéries.</p><h3>Légumes fermentés classiques</h3><p><strong>Choucroute maison :</strong> Chou émincé fin, sel 25g/kg, tassage hermétique. Fermentation 3 semaines, goût acidulé développé.</p><p><strong>Cornichons express :</strong> Petits concombres, saumure 30g/L, aromates. Croquant préservé, acidité contrôlée.</p><h3>Créations modernes</h3><p><strong>Kimchi français :</strong> Chou chinois, radis, carottes, piment doux. Adaptation occidentale recette coréenne traditionnelle.</p><p><strong>Betteraves fermentées :</strong> Râpées grossièrement, cumin, coriandre. Couleur flamboyante, saveur terre-acidulé.</p><h3>Matériel et hygiène</h3><p>Bocaux verre stérilisés, joint neuf. Légumes sous saumure, fermentation anaérobie. Dégustation après 1 semaine minimum.</p>',
                'excerpt' => 'Guide pickles et lacto-fermentation : choucroute, cornichons, kimchi. Technique ancestrale, bienfaits modernes.',
                'meta_title' => 'Pickles et Lacto-fermentation : Guide Complet | FarmShop',
                'meta_description' => 'Guide lacto-fermentation : choucroute, cornichons, kimchi. Techniques, bienfaits et recettes fermentation.',
                'tags' => ['lacto-fermentation', 'pickles', 'choucroute', 'kimchi', 'probiotiques'],
                'reading_time' => 9
            ],

            // Conservation et Transformation (4 articles)
            [
                'category' => 'Conservation et Transformation',
                'title' => 'Séchage légumes : techniques et équipements',
                'content' => '<h2>Préserver la récolte naturellement</h2><p>Tomates séchées, courgettes chips, champignons déshydratés : le séchage concentre saveurs et permet conservation longue durée. Techniques artisanales et modernes.</p><h3>Séchage traditionnel</h3><p><strong>Séchage solaire :</strong> Claies exposées sud, protection pluie. Tomates cerises, herbes, piments s\'y prêtent idéalement.</p><p><strong>Séchoir maison :</strong> Caisse bois, grillages étagés, ventilation naturelle. Construction simple, efficacité prouvée.</p><h3>Équipements modernes</h3><p><strong>Déshydrateur électrique :</strong> Température contrôlée 35-70°C, ventilation forcée. Résultats constants, capacité importante possible.</p><p><strong>Four traditionnel :</strong> Température minimale, porte entrouverte. Solution économique, surveillance nécessaire.</p><h3>Légumes adaptés</h3><p><strong>Tomates :</strong> Variétés charnues, sel léger, temps long. Concentration arômes exceptionnelle.</p><p><strong>Courgettes :</strong> Lamelles fines, déshydratation complète. Chips croustillantes, apéritif original.</p><p><strong>Champignons :</strong> Cèpes, shiitakés, temps court. Réhydratation rapide, goût intensifié.</p><h3>Conservation optimale</h3><p>Bocaux hermétiques, atmosphère sèche. Vérification absence humidité résiduelle. Conservation 1-2 ans conditions optimales.</p>',
                'excerpt' => 'Techniques séchage légumes : solaire, déshydrateur, four. Tomates, courgettes, champignons. Conservation naturelle.',
                'meta_title' => 'Séchage Légumes : Techniques et Équipements | FarmShop',
                'meta_description' => 'Guide séchage légumes : techniques traditionnelles et modernes, équipements. Conservation naturelle récolte.',
                'tags' => ['séchage légumes', 'déshydratation', 'conservation', 'tomates séchées', 'techniques'],
                'reading_time' => 8
            ],
            [
                'category' => 'Conservation et Transformation',
                'title' => 'Stérilisation : bocaux et conserves maison',
                'content' => '<h2>Sécurité et saveur préservées</h2><p>Ratatouille, haricots verts, compotes : la stérilisation permet conserves maison saines. Techniques, températures, durées pour préservation optimale sans risque.</p><h3>Principe de stérilisation</h3><p><strong>Destruction microorganismes :</strong> Chaleur 100°C minimum, temps adapté contenu. Élimination germes pathogènes, enzymes dégradation.</p><p><strong>Importance pH :</strong> Légumes peu acides nécessitent stérilisation rigoureuse. Fruits acides se contentent pasteurisation.</p><h3>Matériel indispensable</h3><p><strong>Bocaux spécialisés :</strong> Verre épais, couvercles neufs, joints intègres. Marques reconnues garantissent sécurité.</p><p><strong>Stérilisateur :</strong> Autocuiseur, stérilisateur électrique ou bain-marie. Contrôle température précis obligatoire.</p><h3>Technique pas à pas</h3><p><strong>Préparation :</strong> Légumes blanchis, bocaux stérilisés, remplissage liquide chaud. Élimination bulles air, fermeture étanche.</p><p><strong>Traitement thermique :</strong> 100°C, durée selon contenu : 1h légumes, 25min fruits. Refroidissement lent natural.</p><h3>Contrôles qualité</h3><p>Vérification étanchéité couvercles, aspect normal contenu. Conservation lieu frais, sombre. Consommation dans 2-3 ans maximum.</p>',
                'excerpt' => 'Guide stérilisation conserves maison : techniques, sécurité, durées. Bocaux légumes et fruits en toute sûreté.',
                'meta_title' => 'Stérilisation : Bocaux et Conserves Maison | FarmShop',
                'meta_description' => 'Guide stérilisation conserves : techniques sûres, matériel, durées. Bocaux maison légumes et fruits.',
                'tags' => ['stérilisation', 'conserves maison', 'bocaux', 'sécurité alimentaire', 'conservation'],
                'reading_time' => 9
            ],
            [
                'category' => 'Conservation et Transformation',
                'title' => 'Congélation intelligente : optimiser l\'espace',
                'content' => '<h2>Maximiser capacité de stockage</h2><p>Blanchiment préalable, portions individuelles, étiquetage rigoureux : optimiser congélation préserve qualité nutritionnelle et organise efficacement réserves alimentaires.</p><h3>Préparation avant congélation</h3><p><strong>Blanchiment légumes :</strong> Ébouillanter 2-5min, refroidissement brutal. Préserve couleurs, textures, vitamines hydrosolubles.</p><p><strong>Portions adaptées :</strong> Sacs famille, portions individuelles. Évite décongélations partielles répétées.</p><h3>Techniques d\'optimisation</h3><p><strong>Congélation IQF :</strong> Étalage plateau, congélation individuelle puis ensachage. Évite blocs compacts, portions variables.</p><p><strong>Mise sous vide :</strong> Élimination air, gain place 30%. Protection oxydation, durée conservation doublée.</p><h3>Organisation du congélateur</h3><p><strong>Étiquetage systématique :</strong> Contenu, date, quantité. Rotation FIFO (premier entré, premier sorti).</p><p><strong>Zones spécialisées :</strong> Légumes, fruits, plats cuisinés séparés. Température homogène -18°C minimum.</p><h3>Légumes spécifiques</h3><p><strong>Courgettes :</strong> Cubes blanchis 3min, excellent ratatouilles. Éviter congélation crue (texture dégradée).</p><p><strong>Haricots verts :</strong> Équeutés, blanchis 4min. Conservation parfaite 12 mois, goût préservé.</p>',
                'excerpt' => 'Congélation optimisée : blanchiment, portions, organisation. Maximiser espace, préserver qualité nutritionnelle.',
                'meta_title' => 'Congélation Intelligente : Optimiser l\'Espace | FarmShop',
                'meta_description' => 'Guide congélation optimisée : techniques, organisation, préparation. Maximiser capacité congélateur efficacement.',
                'tags' => ['congélation', 'optimisation', 'blanchiment', 'organisation', 'conservation'],
                'reading_time' => 7
            ],
            [
                'category' => 'Conservation et Transformation',
                'title' => 'Huiles et vinaigres aromatisés maison',
                'content' => '<h2>Sublimer condiments ordinaires</h2><p>Huile ail-romarin, vinaigre estragon-échalote : ces préparations artisanales transforment salades banales en créations gastronomiques. Techniques et mariages réussis.</p><h3>Huiles parfumées</h3><p><strong>Huile piment-ail :</strong> Huile olive vierge, gousses émincées, piments séchés. Macération 15 jours, filtration fine. Puissance contrôlée.</p><p><strong>Huile herbes de Provence :</strong> Thym, romarin, lavande séchés. Assemblage traditionnel, évocation méditerranéenne immédiate.</p><h3>Vinaigres créatifs</h3><p><strong>Vinaigre framboise :</strong> Fruits frais écrasés, vinaigre blanc qualité. Macération 1 mois, couleur rosée délicate.</p><p><strong>Vinaigre balsamique aux figues :</strong> Figues séchées, vinaigre balsamique. Mariage sucré-acide sophistiqué.</p><h3>Techniques de macération</h3><p><strong>Macération à froid :</strong> Préserve arômes délicats, herbes fraîches. Durée longue nécessaire, résultat subtil.</p><p><strong>Infusion tiède :</strong> Chauffage léger 60°C, accélère extraction. Attention volatilité huiles essentielles.</p><h3>Conservation et usages</h3><p>Bouteilles teintées, étiquetage précis. Conservation 6-12 mois optimal. Assaisonnements, marinades, cadeaux gourmands appréciés.</p>',
                'excerpt' => 'Huiles et vinaigres aromatisés maison : techniques macération, mariages réussis. Condiments gastronomiques artisanaux.',
                'meta_title' => 'Huiles et Vinaigres Aromatisés Maison | FarmShop',
                'meta_description' => 'Guide huiles et vinaigres aromatisés : techniques, recettes, conservation. Condiments gastronomiques maison.',
                'tags' => ['huiles aromatisées', 'vinaigres parfumés', 'condiments maison', 'macération', 'gastronomie'],
                'reading_time' => 8
            ],

            // Produits du Terroir (4 articles)
            [
                'category' => 'Produits du Terroir',
                'title' => 'Fromages fermiers : débuter la fabrication',
                'content' => '<h2>L\'art fromager à portée de main</h2><p>Fromage blanc, faisselle, crottin frais : débuter par des fabrications simples révèle plaisirs authentiques. Techniques de base, matériel minimal pour premiers pas réussis.</p><h3>Équipement de départ</h3><p><strong>Matériel indispensable :</strong> Thermomètre précis, passoire fine, torchons propres. Casserole inox, louche, moules égouttoirs.</p><p><strong>Ingrédients :</strong> Lait cru fermier idéal, présure animale, ferments lactiques. Qualité lait détermine réussite finale.</p><h3>Fromage blanc onctueux</h3><p><strong>Technique :</strong> Lait 25°C, ferments 30min, présure 12h repos. Égouttage lent torchon, texture contrôlée.</p><p><strong>Variations :</strong> Ajout crème fraîche, herbes ciselées, poivre concassé. Personnalisation selon goûts familiaux.</p><h3>Crottin de chèvre</h3><p><strong>Préparation :</strong> Lait chèvre 18°C, ferments spécifiques, caillage 24h. Moulage manuel, égouttage naturel.</p><p><strong>Affinage :</strong> Cave humide 15°C, retournement quotidien. Croûte naturelle 8-15 jours selon goût.</p><h3>Secrets de réussite</h3><p>Hygiène rigoureuse, température stable, patience nécessaire. Observer évolution, goûter régulièrement. Chaque fabrication enseigne subtilités.</p>',
                'excerpt' => 'Débuter fabrication fromages fermiers : fromage blanc, crottin. Techniques simples, matériel minimal, plaisirs authentiques.',
                'meta_title' => 'Fromages Fermiers : Débuter la Fabrication | FarmShop',
                'meta_description' => 'Guide fabrication fromages fermiers : fromage blanc, crottin. Techniques simples pour débuter.',
                'tags' => ['fromages fermiers', 'fabrication fromage', 'crottin', 'fromage blanc', 'artisanal'],
                'reading_time' => 9
            ],
            [
                'category' => 'Produits du Terroir',
                'title' => 'Pain au levain : maîtriser la fermentation',
                'content' => '<h2>Renaissance du pain vivant</h2><p>Levain naturel, fermentation lente, croûte croustillante : le vrai pain revient dans nos cuisines. Créer son levain, l\'entretenir, réussir ses premiers pains.</p><h3>Créer son levain chef</h3><p><strong>Jour 1-2 :</strong> Farine T65 + eau tiède 1:1, couverture humide. Température 25°C stable, première bulle apparition.</p><p><strong>Jour 3-7 :</strong> Rafraîchi quotidien, élimination moitié, ajout farine-eau. Force progressive, odeur acidulée développée.</p><h3>Entretien du levain</h3><p><strong>Conservation :</strong> Réfrigérateur si utilisation rare, rafraîchi hebdomadaire. Température ambiante panification régulière.</p><p><strong>Signes vitalité :</strong> Doublement volume 8h, odeur aigre-douce. Flottaison test eau confirme maturité.</p><h3>Première panification</h3><p><strong>Autolyse :</strong> Farine + eau 30min, développement gluten naturel. Ajout levain actif, sel dissolution.</p><p><strong>Pétrissage doux :</strong> Étirements-replis, respect structure pâte. Pointage 4-6h, façonnage délicat.</p><h3>Cuisson optimale</h3><p>Four préchauffé 250°C, vapeur initiale, buée création. Température descente progressive, croûte dorée parfaite. Patience refroidissement avant découpe.</p>',
                'excerpt' => 'Pain au levain maison : créer levain chef, entretien, panification. Maîtriser fermentation naturelle étape par étape.',
                'meta_title' => 'Pain au Levain : Maîtriser la Fermentation | FarmShop',
                'meta_description' => 'Guide pain au levain : création levain chef, entretien, panification. Fermentation naturelle maîtrisée.',
                'tags' => ['pain levain', 'levain naturel', 'fermentation', 'panification', 'boulangerie'],
                'reading_time' => 10
            ],
            [
                'category' => 'Produits du Terroir',
                'title' => 'Charcuterie artisanale : saucisses et terrines',
                'content' => '<h2>Retrouver goûts authentiques</h2><p>Saucisse fraîche, terrine campagne, rillettes maison : ces préparations traditionnelles révèlent saveurs oubliées. Techniques de base, hygiène rigoureuse pour débuter sereinement.</p><h3>Saucisse fraîche classique</h3><p><strong>Composition :</strong> Épaule porc 70%, poitrine 30%, sel 18g/kg. Mélange à froid, boyau naturel mouton.</p><p><strong>Assaisonnement :</strong> Poivre noir, muscade, ail frais optional. Goût personnel, épices qualité indispensable.</p><h3>Terrine de campagne</h3><p><strong>Base :</b> Porc-veau-foie 50/30/20, œufs liaison, cognac parfum. Cuisson bain-marie, température cœur 68°C.</p><p><strong>Garniture :</strong> Pistaches, noisettes, herbes fraîches. Présentation soignée, coupe nette appétissante.</p><h3>Rillettes du Mans</h3><p><strong>Cuisson confite :</strong> Échine porc, saindoux, cuisson 4h feu doux. Effilochage tiède, assaisonnement final précis.</p><p><strong>Conservation :</strong> Pots stérilisés, couverture graisse protectrice. Maturation 48h minimum développement arômes.</p><h3>Hygiène et sécurité</h3><p>Chaîne froid respectée, matériel désinfecté, température contrôlée. Formation recommandée, réglementation sanitaire respectée.</p>',
                'excerpt' => 'Charcuterie artisanale : saucisses, terrines, rillettes. Techniques traditionnelles, hygiène rigoureuse, goûts authentiques.',
                'meta_title' => 'Charcuterie Artisanale : Saucisses et Terrines | FarmShop',
                'meta_description' => 'Guide charcuterie artisanale : saucisses, terrines, rillettes. Techniques traditionnelles et hygiène.',
                'tags' => ['charcuterie artisanale', 'saucisses', 'terrines', 'rillettes', 'traditionnel'],
                'reading_time' => 9
            ],
            [
                'category' => 'Produits du Terroir',
                'title' => 'Vins et cidres fermiers : initiation vinification',
                'content' => '<h2>Transformer sa récolte en nectar</h2><p>Vin de table, cidre fermier, hydromel : valoriser fruits en boissons fermentées prolonge plaisirs récolte. Principes fermentation, matériel de base, premières cuvées.</p><h3>Vinification raisins de table</h3><p><strong>Vendange :</strong> Maturité optimale, tri rigoureux, sulfitage léger. Foulage manuel, macération pelliculaire contrôlée.</p><p><strong>Fermentation :</strong> Levures indigènes ou sélectionnées, température 20-25°C. Remontages quotidiens, densité surveillée.</p><h3>Cidre fermier traditionnel</h3><p><strong>Matière première :</strong> Mélange pommes douces-amères-acides. Pressage délicat, débourbage naturel 24h.</p><p><strong>Fermentation :</strong> Spontanée ou levures cidricoles, 15-18°C optimal. Soutirage clarification, prise mousse naturelle.</p><h3>Hydromel découverte</h3><p><strong>Base miel :</strong> Miel toutes fleurs 1,5kg/4L eau, nutriments levures. Dilution tiède, refroidissement complet.</p><p><strong>Conduite :</strong> Fermentation primaire 10 jours, secondaire 2 mois. Clarification, élevage patience récompensée.</p><h3>Matériel et hygiène</h3><p>Cuves inox-plastique alimentaire, densimètre, barboteur. Désinfection potasse, rinçage soigné. Contrôles réguliers, notes dégustation.</p>',
                'excerpt' => 'Vins et cidres fermiers : initiation vinification raisins, pommes, miel. Transformer récolte en boissons fermentées.',
                'meta_title' => 'Vins et Cidres Fermiers : Initiation Vinification | FarmShop',
                'meta_description' => 'Guide vinification fermière : vins, cidres, hydromel. Transformer fruits en boissons fermentées.',
                'tags' => ['vinification', 'cidre fermier', 'vin maison', 'hydromel', 'fermentation'],
                'reading_time' => 10
            ]
        ];
    }
}
