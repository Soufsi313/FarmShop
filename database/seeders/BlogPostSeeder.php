<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BlogPostSeeder extends Seeder
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

        $articles = $this->getArticles();

        // Générer des dates de publication étalées sur les 6 derniers mois
        $startDate = Carbon::now()->subMonths(6);
        $publishedCount = 0;

        foreach ($articles as $index => $article) {
            $category = $categories->get($article['category']);
            
            if (!$category) {
                $this->command->warn("Catégorie '{$article['category']}' non trouvée pour l'article '{$article['title']}'");
                continue;
            }

            $publishedAt = $startDate->copy()->addDays(rand(0, 180));
            
            BlogPost::create([
                'title' => $article['title'],
                'content' => $article['content'],
                'excerpt' => $article['excerpt'],
                'meta_title' => $article['meta_title'],
                'meta_description' => $article['meta_description'],
                'meta_keywords' => implode(',', $article['tags']),
                'blog_category_id' => $category->id,
                'author_id' => $admin->id,
                'reading_time' => $article['reading_time'],
                'views_count' => rand(50, 800),
                'comments_count' => rand(0, 25),
                'is_featured' => $index < 10, // Les 10 premiers sont mis en avant
                'status' => 'published',
                'published_at' => $publishedAt,
                'created_at' => $publishedAt->copy()->subDays(rand(1, 30)),
                'updated_at' => $publishedAt->copy()->addDays(rand(0, 30)),
            ]);
            
            $publishedCount++;
        }

        $this->command->info("✅ {$publishedCount} articles de blog créés avec succès !");

        // Exécuter les autres parties du seeder pour compléter à 100 articles
        $this->call([
            BlogPostSeederPart2::class,
            BlogPostSeederPart3::class,
            BlogPostSeederPart4::class
        ]);
    }

    private function getArticles()
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
                'title' => 'Pourquoi les carottes étaient-elles violettes à l\'origine ?',
                'content' => '<h2>L\'histoire colorée de la carotte</h2><p>Les premières carottes cultivées il y a 5000 ans en Afghanistan étaient violettes, blanches ou jaunes, mais jamais oranges ! La carotte orange que nous connaissons aujourd\'hui est le résultat d\'une sélection hollandaise au 17ème siècle.</p><h3>Une révolution orange</h3><p>Les horticulteurs hollandais ont développé la carotte orange en l\'honneur de la Maison d\'Orange-Nassau. Cette couleur provient du bêta-carotène, précurseur de la vitamine A.</p><h3>Le retour des variétés anciennes</h3><p>Aujourd\'hui, les jardiniers redécouvrent les carottes violettes, blanches et jaunes. Ces variétés anciennes offrent des saveurs uniques et une richesse nutritionnelle différente.</p>',
                'excerpt' => 'L\'histoire fascinante de la carotte orange et pourquoi nos ancêtres cultivaient des carottes violettes et blanches.',
                'meta_title' => 'Histoire de la Carotte : De Violette à Orange | FarmShop',
                'meta_description' => 'Découvrez pourquoi les carottes étaient violettes avant d\'être oranges. Histoire fascinante d\'un légume aux origines surprenantes.',
                'tags' => ['carotte', 'histoire', 'variétés anciennes', 'légumes', 'culture'],
                'reading_time' => 4
            ],
            [
                'category' => 'Le saviez-vous',
                'title' => 'Les champignons : ni plantes ni animaux, mais quoi alors ?',
                'content' => '<h2>Le mystérieux royaume des champignons</h2><p>Contrairement aux idées reçues, les champignons ne sont ni des plantes ni des animaux. Ils constituent leur propre règne du vivant avec des caractéristiques fascinantes qui défient notre compréhension habituelle.</p><h3>Plus proches des animaux que des plantes</h3><p>Génétiquement, les champignons sont plus apparentés aux animaux qu\'aux plantes. Comme nous, ils respirent l\'oxygène et rejettent du CO2. Ils ne pratiquent pas la photosynthèse et doivent trouver leur nourriture à l\'extérieur.</p><h3>Le plus grand organisme du monde</h3><p>Dans l\'Oregon, un champignon Armillaria ostoyae s\'étend sur 965 hectares ! Ses filaments souterrains forment un réseau unique vieux de 2400 ans, faisant de lui le plus grand organisme vivant connu.</p>',
                'excerpt' => 'Ni plantes ni animaux, les champignons forment un règne mystérieux. Découvrez leurs secrets et le plus grand organisme du monde.',
                'meta_title' => 'Champignons : Ni Plantes ni Animaux, Quel Mystère ! | FarmShop',
                'meta_description' => 'Les champignons ne sont ni plantes ni animaux. Découvrez ce règne mystérieux et le plus grand organisme vivant au monde.',
                'tags' => ['champignons', 'mycologie', 'nature', 'biologie', 'mystère'],
                'reading_time' => 5
            ],
            [
                'category' => 'Le saviez-vous',
                'title' => 'Pourquoi les oignons nous font-ils pleurer ?',
                'content' => '<h2>Le mécanisme de défense de l\'oignon</h2><p>Quand vous coupez un oignon, vous déclenchez une réaction chimique défensive sophistiquée. Cette "arme lacrymale" naturelle protège le bulbe contre les prédateurs dans la nature.</p><h3>Une cascade de réactions chimiques</h3><p>La coupe libère des enzymes qui transforment les composés soufrés en gaz irritant. Ce syn-Propanethial-S-oxide atteint nos yeux et déclenche automatiquement la production de larmes pour éliminer l\'irritant.</p><h3>Astuces pour éviter les pleurs</h3><p>Réfrigérez l\'oignon 30 minutes avant découpe, utilisez un couteau très aiguisé, ou coupez sous l\'eau courante. Ces techniques ralentissent ou diluent la réaction chimique responsable des larmes.</p>',
                'excerpt' => 'Pourquoi pleurer en coupant des oignons ? Découvrez le mécanisme de défense chimique et les astuces pour l\'éviter.',
                'meta_title' => 'Pourquoi les Oignons Font Pleurer : Science Expliquée | FarmShop',
                'meta_description' => 'Mécanisme scientifique des larmes causées par l\'oignon. Réaction chimique défensive et astuces pour éviter de pleurer.',
                'tags' => ['oignon', 'chimie', 'larmes', 'cuisine', 'science'],
                'reading_time' => 4
            ],
            [
                'category' => 'Le saviez-vous',
                'title' => 'Les abeilles voient en ultraviolet : un monde invisible',
                'content' => '<h2>La vision extraordinaire des abeilles</h2><p>Les abeilles perçoivent les ultraviolets, révélant des motifs floraux invisibles à l\'œil humain. Cette super-vision guide leur butinage avec une précision remarquable.</p><h3>Des fleurs marquées comme des pistes d\'atterrissage</h3><p>En lumière ultraviolette, les pétales révèlent des "guides nectarifères" : lignes, points et motifs qui dirigent l\'abeille vers le nectar. Ces signalisations, invisibles pour nous, facilitent la pollinisation.</p><h3>Communication par la danse</h3><p>De retour à la ruche, l\'abeille éclaireuse transmet par sa danse la direction, la distance et la qualité de sa découverte. Cette carte chorégraphiée guide ses sœurs vers les meilleures sources de nourriture.</p>',
                'excerpt' => 'Les abeilles voient en ultraviolet et découvrent des motifs floraux secrets. Plongez dans leur monde visuel extraordinaire.',
                'meta_title' => 'Vision Ultraviolet des Abeilles : Monde Invisible Révélé | FarmShop',
                'meta_description' => 'Les abeilles voient les ultraviolets et des motifs floraux cachés. Découvrez leur vision extraordinaire et la communication par la danse.',
                'tags' => ['abeilles', 'ultraviolet', 'vision', 'pollinisation', 'nature'],
                'reading_time' => 5
            ],
            [
                'category' => 'Le saviez-vous',
                'title' => 'La banane : une baie qui pousse sur une herbe géante',
                'content' => '<h2>Les surprises botaniques de la banane</h2><p>Contrairement aux apparences, la banane est botaniquement une baie, et le bananier n\'est pas un arbre mais la plus grande herbe du monde ! Ces révélations bousculent nos idées reçues sur ce fruit tropical.</p><h3>Une herbe de 15 mètres de haut</h3><p>Le "tronc" du bananier n\'est qu\'un assemblage de feuilles enroulées appelé pseudo-tronc. La véritable tige souterraine produit chaque année une nouvelle pousse qui donnera les régimes de bananes.</p><h3>Des bananes sans graines</h3><p>Les bananes commerciales sont issues de reproduction asexuée. Dans la nature, les bananes sauvages contiennent d\'énormes graines dures. Nos variétés cultivées ont perdu cette capacité de reproduction sexuée au profit du goût.</p>',
                'excerpt' => 'La banane cache bien son jeu : c\'est une baie qui pousse sur une herbe géante ! Découvrez les secrets botaniques de ce fruit familier.',
                'meta_title' => 'Banane : Baie sur Herbe Géante, Secrets Botaniques ! | FarmShop',
                'meta_description' => 'La banane est une baie et le bananier une herbe géante. Découvrez les surprises botaniques de ce fruit tropical familier.',
                'tags' => ['banane', 'botanique', 'fruit', 'tropical', 'surprenant'],
                'reading_time' => 4
            ],

            // Trucs et astuces (6 articles)
            [
                'category' => 'Trucs et astuces',
                'title' => '10 astuces de grand-mère pour un jardinage réussi',
                'content' => '<h2>Les secrets transmis de génération en génération</h2><p>Nos grands-mères avaient des techniques simples et efficaces pour jardiner sans produits chimiques. Redécouvrons ces astuces testées et approuvées !</p><h3>1. Le marc de café contre les limaces</h3><p>Répandez le marc de café autour de vos plants. Son acidité et sa texture rugueuse repoussent les limaces naturellement.</p><h3>2. La coquille d\'œuf pour le calcium</h3><p>Broyez vos coquilles d\'œufs et mélangez-les à la terre. Elles apportent du calcium et protègent contre la pourriture apicale des tomates.</p><h3>3. L\'ail contre les pucerons</h3><p>Plantez de l\'ail près de vos rosiers et légumes. Son odeur repousse naturellement les pucerons et autres insectes nuisibles.</p>',
                'excerpt' => 'Redécouvrez 10 astuces de jardinage naturelles transmises par nos grands-mères pour un potager sain et productif.',
                'meta_title' => '10 Astuces de Grand-Mère pour Jardiner Naturellement | FarmShop',
                'meta_description' => 'Astuces de jardinage naturel testées par nos grands-mères. Marc de café, coquilles d\'œuf, ail : solutions écologiques efficaces.',
                'tags' => ['astuces', 'jardinage naturel', 'grand-mère', 'écologique', 'techniques'],
                'reading_time' => 7
            ],
            [
                'category' => 'Trucs et astuces',
                'title' => 'Multiplier ses plantes gratuitement : bouturage facile',
                'content' => '<h2>L\'art du bouturage pour tous</h2><p>Pourquoi acheter des plantes quand on peut les multiplier gratuitement ? Le bouturage permet de créer de nouveaux plants identiques à la plante mère, simplement et économiquement.</p><h3>Bouturage dans l\'eau</h3><p>Géraniums, coleus, impatiens se bouturent facilement dans un verre d\'eau. Coupez une tige de 10cm sous un nœud, supprimez les feuilles du bas, et attendez l\'apparition des racines en 2-3 semaines.</p><h3>Bouturage en terre</h3><p>Lavande, romarin, chrysanthèmes préfèrent l\'enracinement direct en terre. Mélangez terreau et sable, maintenez humide sous cloche ou sac plastique pour créer une mini-serre.</p><h3>Hormones de bouturage naturelles</h3><p>Trempez vos boutures dans du miel ou de l\'eau de saule pour stimuler l\'enracinement. Ces alternatives naturelles remplacent efficacement les hormones chimiques.</p>',
                'excerpt' => 'Multipliez vos plantes gratuitement grâce au bouturage. Techniques simples pour géraniums, lavande, romarin et alternatives naturelles.',
                'meta_title' => 'Bouturage Facile : Multiplier ses Plantes Gratuitement | FarmShop',
                'meta_description' => 'Apprenez le bouturage pour multiplier vos plantes gratuitement. Techniques eau et terre, hormones naturelles, conseils pratiques.',
                'tags' => ['bouturage', 'multiplication', 'plantes', 'économique', 'jardinage'],
                'reading_time' => 6
            ],
            [
                'category' => 'Trucs et astuces',
                'title' => 'Économiser l\'eau au jardin : 8 techniques efficaces',
                'content' => '<h2>Jardiner malin face à la sécheresse</h2><p>Avec le changement climatique, économiser l\'eau devient crucial. Ces 8 techniques permettent de réduire de 50% la consommation d\'eau tout en gardant un jardin florissant.</p><h3>1. Paillage généralisé</h3><p>Une couche de 5-10cm de paillis (paille, tontes, BRF) réduit l\'évaporation de 75%. Le sol reste frais et humide même par forte chaleur, limitant les arrosages.</p><h3>2. Récupération d\'eau de pluie</h3><p>Installez des cuves sous les gouttières. 100m² de toiture récupèrent 60 000L par an ! Cette eau douce, sans chlore, est idéale pour les plantes acidophiles.</p><h3>3. Arrosage goutte-à-goutte</h3><p>Système le plus économe, il apporte l\'eau directement aux racines sans gaspillage. Facile à installer avec des kits prêts à poser pour 50€.</p>',
                'excerpt' => 'Réduisez de 50% votre consommation d\'eau au jardin. 8 techniques efficaces : paillage, récupération eau de pluie, goutte-à-goutte.',
                'meta_title' => 'Économiser l\'Eau au Jardin : 8 Techniques Efficaces | FarmShop',
                'meta_description' => 'Économisez 50% d\'eau au jardin avec ces 8 techniques : paillage, récupération eau de pluie, arrosage goutte-à-goutte.',
                'tags' => ['économie eau', 'paillage', 'récupération', 'goutte-à-goutte', 'sécheresse'],
                'reading_time' => 8
            ],
            [
                'category' => 'Trucs et astuces',
                'title' => 'Fabriquer son engrais liquide avec les orties',
                'content' => '<h2>L\'ortie, trésor du jardinier bio</h2><p>L\'ortie pousse partout et constitue l\'un des meilleurs engrais naturels. Riche en azote, potassium et oligo-éléments, elle stimule la croissance et renforce les défenses des plantes.</p><h3>Recette du purin d\'ortie</h3><p>1kg d\'orties fraîches dans 10L d\'eau de pluie. Laissez fermenter 15 jours en remuant quotidiennement. L\'odeur désagréable indique que la fermentation fonctionne bien !</p><h3>Utilisation et dilution</h3><p>Filtrez et diluez à 10% pour l\'arrosage (1L de purin + 9L d\'eau), à 5% pour la pulvérisation foliaire. N\'utilisez jamais pur sous peine de brûler les racines.</p><h3>Conservation et stockage</h3><p>Le purin se conserve 1 an dans des bidons fermés, à l\'abri de la lumière. Étiquetez avec la date de fabrication et la concentration pour éviter les erreurs.</p>',
                'excerpt' => 'Fabriquez votre engrais liquide à base d\'orties. Recette simple, utilisation, conservation : l\'ortie devient votre meilleur allié jardin.',
                'meta_title' => 'Engrais Liquide Orties : Fabriquer son Purin Maison | FarmShop',
                'meta_description' => 'Fabriquez votre purin d\'orties maison. Recette simple, dosage, conservation : engrais naturel gratuit et efficace.',
                'tags' => ['purin ortie', 'engrais naturel', 'bio', 'fabrication', 'jardinage'],
                'reading_time' => 6
            ],
            [
                'category' => 'Trucs et astuces',
                'title' => 'Compagnonnage : quelles plantes associer au potager ?',
                'content' => '<h2>L\'art d\'associer les légumes</h2><p>Certaines plantes s\'entraident mutuellement : protection contre les maladies, optimisation de l\'espace, amélioration du sol. Le compagnonnage révolutionne l\'approche du potager.</p><h3>Associations bénéfiques classiques</h3><p>Tomates + basilic : le basilic repousse les pucerons et améliore le goût. Carottes + poireaux : leurs odeurs respectives éloignent mouche de la carotte et ver du poireau.</p><h3>Les trois sœurs amérindiennes</h3><p>Maïs, haricots et courges forment un trio gagnant. Le maïs sert de tuteur aux haricots qui enrichissent le sol en azote, tandis que les courges couvrent le sol et conservent l\'humidité.</p><h3>Plantes répulsives</h3><p>Œillet d\'Inde contre les nématodes, capucines piège à pucerons, tanaisie répulsive aux fourmis. Ces fleurs utiles embellissent tout en protégeant.</p>',
                'excerpt' => 'Optimisez votre potager avec le compagnonnage. Associations bénéfiques, trois sœurs amérindiennes, plantes répulsives naturelles.',
                'meta_title' => 'Compagnonnage Potager : Associer les Légumes Efficacement | FarmShop',
                'meta_description' => 'Maîtrisez le compagnonnage au potager. Associations légumes, plantes répulsives, trois sœurs : optimisez vos cultures naturellement.',
                'tags' => ['compagnonnage', 'associations', 'potager', 'légumes', 'naturel'],
                'reading_time' => 7
            ],
            [
                'category' => 'Trucs et astuces',
                'title' => 'Préparer ses graines pour le semis : 5 techniques pros',
                'content' => '<h2>Optimiser la germination des graines</h2><p>Améliorer le taux de germination de vos graines avec ces techniques professionnelles. De simples préparatifs peuvent doubler vos chances de réussite !</p><h3>1. Trempage et stratification</h3><p>Graines dures (haricots, pois) : trempage 24h dans l\'eau tiède. Graines d\'arbres : stratification à froid 3 mois au réfrigérateur pour simuler l\'hiver.</p><h3>2. Scarification mécanique</h3><p>Grattez délicatement les graines très dures (glycines, cytises) avec du papier de verre fin pour percer leur enveloppe imperméable.</p><h3>3. Test de viabilité</h3><p>Placez 10 graines sur papier absorbant humide. Si moins de 7 germent en 10 jours, les graines sont périmées ou mal conservées.</p>',
                'excerpt' => 'Maximisez la germination avec ces 5 techniques pros : trempage, stratification, scarification. Doublez vos chances de réussite !',
                'meta_title' => 'Préparer ses Graines : 5 Techniques Pro de Germination | FarmShop',
                'meta_description' => 'Techniques professionnelles pour optimiser la germination. Trempage, stratification, scarification : doublez vos chances de réussite.',
                'tags' => ['graines', 'germination', 'semis', 'techniques', 'professionnel'],
                'reading_time' => 6
            ],

            // Potager et Legumes (10 articles)
            [
                'category' => 'Potager et Legumes',
                'title' => 'Créer un potager productif en carré : guide complet',
                'content' => '<h2>Le potager en carré : maximum de rendement, minimum d\'espace</h2><p>Le potager en carré révolutionne l\'art du jardinage en optimisant l\'espace et en facilitant l\'entretien. Cette méthode permet de produire 5 fois plus qu\'un potager traditionnel sur la même surface.</p><h3>Construction du carré potager</h3><p>Utilisez des planches de 20 cm de hauteur minimum. Divisez votre carré de 1,20m x 1,20m en 16 cases de 30cm x 30cm. Cette dimension permet d\'atteindre le centre sans marcher sur la terre.</p><h3>Préparation du substrat</h3><p>Mélangez 1/3 de compost, 1/3 de terre de jardin et 1/3 de sable ou vermiculite. Ce mélange assure un bon drainage et une nutrition optimale.</p><h3>Planification des cultures</h3><p>Alternez légumes-racines, légumes-feuilles et légumes-fruits. Respectez les associations bénéfiques : radis avec carottes, basilic avec tomates.</p>',
                'excerpt' => 'Maximisez votre production légumière avec la méthode du potager en carré. Guide pratique pour débuter et optimiser vos récoltes.',
                'meta_title' => 'Potager en Carré : Guide Complet pour Débutants | FarmShop',
                'meta_description' => 'Créez un potager en carré productif. Construction, substrat, planification : tout pour réussir votre potager urbain ou familial.',
                'tags' => ['potager en carré', 'jardinage urbain', 'productivité', 'espace', 'débutant'],
                'reading_time' => 10
            ],
            [
                'category' => 'Potager et Legumes',
                'title' => 'Tomates : variétés résistantes pour un été réussi',
                'content' => '<h2>Choisir des tomates adaptées à votre climat</h2><p>Face aux aléas climatiques et aux maladies, la sélection de variétés résistantes devient cruciale. Découvrez les tomates qui garantissent une récolte même dans des conditions difficiles.</p><h3>Variétés résistantes aux maladies</h3><p>La "Ferline" résiste au mildiou, la "Fantasio" tolère le virus de la mosaïque, et la "Philovita" supporte les variations de température. Ces hybrides F1 offrent une résistance exceptionnelle.</p><h3>Tomates cerises increvables</h3><p>Les variétés cerises comme "Sweet 100" ou "Surefire Red" produisent même par temps frais et résistent naturellement à la plupart des maladies cryptogamiques.</p><h3>Conseils de culture</h3><p>Plantez en mai après les Saints de Glace, paillez le sol, et arrosez régulièrement sans mouiller le feuillage. La rotation des cultures évite l\'épuisement du sol.</p>',
                'excerpt' => 'Sélection des meilleures variétés de tomates résistantes aux maladies et adaptées aux conditions climatiques changeantes.',
                'meta_title' => 'Tomates Résistantes : Variétés pour Récolte Garantie | FarmShop',
                'meta_description' => 'Variétés de tomates résistantes au mildiou, virus et intempéries. Guide pour choisir et cultiver des tomates robustes.',
                'tags' => ['tomates', 'variétés résistantes', 'maladies', 'climat', 'récolte'],
                'reading_time' => 8
            ],
            [
                'category' => 'Potager et Legumes',
                'title' => 'Rotation des cultures : planifier ses légumes pour 4 ans',
                'content' => '<h2>L\'importance de la rotation des cultures</h2><p>La rotation des cultures est une technique ancestrale qui permet de maintenir la fertilité du sol et de limiter les maladies. Elle consiste à ne pas cultiver la même famille de légumes au même endroit plusieurs années consécutives.</p><h3>Les 4 familles principales</h3><p>Pour organiser votre rotation, divisez vos légumes en 4 familles : les légumineuses (haricots, pois), les crucifères (choux, radis), les solanacées (tomates, pommes de terre) et les ombellifères (carottes, persil).</p><h3>Plan de rotation sur 4 ans</h3><p>Année 1 : Légumineuses qui enrichissent le sol en azote. Année 2 : Crucifères qui profitent de cet azote. Année 3 : Solanacées gourmandes. Année 4 : Ombellifères moins exigeantes.</p>',
                'excerpt' => 'Maîtrisez la rotation des cultures avec notre plan détaillé sur 4 ans pour optimiser votre potager et préserver votre sol.',
                'meta_title' => 'Rotation des Cultures : Plan 4 Ans pour Potager Productif | FarmShop',
                'meta_description' => 'Guide complet de la rotation des cultures sur 4 ans. Planification, familles de légumes, préservation du sol et optimisation des récoltes.',
                'tags' => ['rotation', 'cultures', 'planification', 'sol', 'fertilité'],
                'reading_time' => 10
            ],
            [
                'category' => 'Potager et Legumes',
                'title' => 'Cultiver des épinards toute l\'année : variétés et techniques',
                'content' => '<h2>Des épinards frais 12 mois sur 12</h2><p>L\'épinard se cultive en toutes saisons à condition de choisir les bonnes variétés et d\'adapter les techniques. Découvrez comment avoir des épinards frais toute l\'année.</p><h3>Variétés d\'été résistantes</h3><p>"Géant d\'hiver" et "Monstrueux de Viroflay" résistent à la montaison estivale. Semez en terrain frais, ombragé aux heures chaudes, et récoltez feuille par feuille.</p><h3>Épinards d\'hiver sous tunnel</h3><p>"Polka" et "Tarpy" supportent -10°C sous voile d\'hivernage. Ces variétés rustiques poussent lentement mais fournissent des feuilles tendres tout l\'hiver.</p><h3>Culture en bacs et jardinières</h3><p>L\'épinard "Baby Leaf" se cultive parfaitement en bacs de 20cm de profondeur. Semis échelonnés tous les 15 jours pour une récolte continue.</p>',
                'excerpt' => 'Cultivez des épinards frais toute l\'année. Variétés d\'été et d\'hiver, techniques sous tunnel, culture en bacs pour récolte continue.',
                'meta_title' => 'Épinards Toute l\'Année : Variétés et Techniques | FarmShop',
                'meta_description' => 'Épinards frais 12 mois sur 12 : variétés résistantes, culture sous tunnel, techniques en bacs pour production continue.',
                'tags' => ['épinards', 'toute année', 'variétés', 'saisons', 'culture continue'],
                'reading_time' => 7
            ],
            [
                'category' => 'Potager et Legumes',
                'title' => 'Radis : du semis à la récolte en 3 semaines',
                'content' => '<h2>Le radis, légume express du potager</h2><p>Aucun légume ne pousse aussi vite que le radis ! En 18 à 25 jours seulement, vous croquez dans vos propres radis croquants. Parfait pour initier les enfants au jardinage.</p><h3>Semis réussi en toute saison</h3><p>Semez clair (1cm entre graines), en lignes espacées de 15cm. En été, préférez la mi-ombre et arrosez quotidiennement. Les radis craignent la sécheresse qui les rend piquants et fibreux.</p><h3>Variétés colorées et savoureuses</h3><p>"18 jours" ultra-précoce, "Flamboyant" bicolore rouge et blanc, "Easter Egg" mélange multicolore, "Daikon" radis blanc japonais de 30cm ! Chaque variété apporte ses surprises.</p><h3>Éviter les échecs courants</h3><p>Radis qui montent en graines : semis trop dense ou temps trop chaud. Radis creux : manque d\'eau. Radis trop piquants : récolte tardive ou stress hydrique.</p>',
                'excerpt' => 'Récoltez vos radis en 3 semaines ! Semis, variétés colorées, astuces pour éviter les échecs : le légume parfait pour débuter.',
                'meta_title' => 'Radis Express : Semis à Récolte en 3 Semaines | FarmShop',
                'meta_description' => 'Cultivez des radis en 3 semaines. Semis réussi, variétés colorées, erreurs à éviter : guide complet du radis express.',
                'tags' => ['radis', 'express', 'semis', 'rapide', 'débutant'],
                'reading_time' => 5
            ],
            [
                'category' => 'Potager et Legumes',
                'title' => 'Courgettes prolifiques : techniques pour éviter l\'envahissement',
                'content' => '<h2>Maîtriser la production de courgettes</h2><p>Un pied de courgette peut produire 15 kg de légumes ! Cette générosité devient vite un problème. Découvrez comment réguler la production et valoriser les surplus.</p><h3>Taille et éclaircissage</h3><p>Supprimez les feuilles de la base dès qu\'elles touchent le sol. Éclaircissez les fruits : gardez 1 courgette sur 3 pour obtenir des légumes plus gros et savoureux. Coupez régulièrement pour stimuler la production.</p><h3>Variétés adaptées au petit potager</h3><p>"Ronde de Nice" compacte, "Gold Rush" jaune moins productive, "Patty Pan" décorative. Ces variétés conviennent mieux aux petits espaces que les courgettes géantes.</p><h3>Transformation et conservation</h3><p>Râpées et congelées, en ratatouille, pickles, ou pain aux courgettes. Les jeunes fleurs mâles se cuisinent en beignets, délicieux et originaux !</p>',
                'excerpt' => 'Maîtrisez la production prolifique des courgettes. Taille, variétés adaptées, transformation : évitez l\'envahissement !',
                'meta_title' => 'Courgettes Prolifiques : Maîtriser la Production | FarmShop',
                'meta_description' => 'Gérez la production prolifique des courgettes. Techniques de taille, variétés compactes, transformation et conservation des surplus.',
                'tags' => ['courgettes', 'production', 'taille', 'surplus', 'conservation'],
                'reading_time' => 6
            ],
            [
                'category' => 'Potager et Legumes',
                'title' => 'Haricots verts : semer et récolter tout l\'été',
                'content' => '<h2>Des haricots verts frais tout l\'été</h2><p>Échelonnez vos semis de haricots verts pour étaler la récolte de juin à octobre. Cette légumineuse enrichit le sol tout en nourrissant la famille.</p><h3>Semis échelonnés réussis</h3><p>Semez tous les 15 jours de mi-mai à mi-juillet. 5 graines par poquet, poquets espacés de 40cm. En région fraîche, utilisez un voile de forçage pour les premiers semis.</p><h3>Variétés naines et grimpantes</h3><p>Haricots nains "Fin de Bagnols" sans fils, "Purple Queen" violet décoratif. Haricots à rames "Fortex" grains fins, "Cobra" violet très productif sur 2m de hauteur.</p><h3>Récolte et conservation</h3><p>Récoltez jeunes quand la gousse craque sous le doigt. Cueillez tous les 2 jours pour stimuler la production. Blanchiment 3 minutes puis congélation pour l\'hiver.</p>',
                'excerpt' => 'Haricots verts frais tout l\'été avec des semis échelonnés. Variétés naines et grimpantes, récolte optimale et conservation.',
                'meta_title' => 'Haricots Verts Tout l\'Été : Semis Échelonnés | FarmShop',
                'meta_description' => 'Production continue de haricots verts par semis échelonnés. Variétés naines et grimpantes, techniques de récolte et conservation.',
                'tags' => ['haricots verts', 'semis échelonnés', 'été', 'récolte', 'conservation'],
                'reading_time' => 7
            ],
            [
                'category' => 'Potager et Legumes',
                'title' => 'Légumes perpétuels : investir une fois, récolter toujours',
                'content' => '<h2>Le potager paresseux avec les légumes perpétuels</h2><p>Certains légumes, une fois installés, produisent pendant des années sans nouvel achat de graines. L\'artichaut, l\'asperge, la rhubarbe et le poireau perpétuel transforment votre approche du potager.</p><h3>L\'artichaut, généreux et décoratif</h3><p>Un pied d\'artichaut produit 15 têtes par an pendant 4-5 ans. Plantez les œilletons en mars-avril, espacés de 1m. Protégez l\'hiver par un paillis épais dans les régions froides.</p><h3>Asperges : patience récompensée</h3><p>L\'aspergière produit 15-20 ans ! Plantation longue mais investissement rentable. Achetez des griffes de 2 ans, plantez en tranchées profondes, et patientez 3 ans avant la première récolte.</p><h3>Rhubarbe et poireau perpétuel</h3><p>La rhubarbe peut vivre 20 ans et se divise tous les 5 ans. Le poireau perpétuel se multiplie spontanément en touffes denses, fournissant des pousses tendres toute l\'année.</p>',
                'excerpt' => 'Légumes perpétuels pour potager paresseux : artichaut, asperge, rhubarbe. Investissez une fois, récoltez pendant des années !',
                'meta_title' => 'Légumes Perpétuels : Potager Paresseux Productif | FarmShop',
                'meta_description' => 'Légumes perpétuels pour récoltes durables : artichaut, asperge, rhubarbe, poireau perpétuel. Investissement unique, production longue.',
                'tags' => ['légumes perpétuels', 'artichaut', 'asperge', 'rhubarbe', 'durable'],
                'reading_time' => 8
            ],
            [
                'category' => 'Potager et Legumes',
                'title' => 'Choux : famille nombreuse aux multiples saveurs',
                'content' => '<h2>La grande famille des choux</h2><p>Chou-fleur, brocoli, chou rouge, chou de Bruxelles, chou-rave... Tous issus de la même espèce mais aux saveurs si différentes ! Découvrez cette famille nutritive et productive.</p><h3>Choux d\'été et d\'automne</h3><p>Brocolis et choux-fleurs d\'été se sèment en mai pour récolte estivale. Choux pommés d\'automne se plantent en juillet-août pour récolte hivernale. Échelonnez pour étaler la production.</p><h3>Choux originaux à découvrir</h3><p>Chou-rave au goût de navet tendre, chou romanesco aux spirales fractales, chou rouge décoratif, pak-choï asiatique rapide. Chaque variété apporte ses spécificités culinaires.</p><h3>Prévenir la hernie du chou</h3><p>Cette maladie du sol peut détruire toute la culture. Rotation de 5 ans minimum, amendement calcaire, drainage efficace. Privilégiez plants greffés en zone contaminée.</p>',
                'excerpt' => 'Explorez la diversité des choux : été, automne, variétés originales. Culture, prévention maladies, famille nutritive et productive.',
                'meta_title' => 'Choux Diversifiés : Famille Nombreuse du Potager | FarmShop',
                'meta_description' => 'Culture des choux : brocoli, chou-fleur, chou rouge, variétés originales. Plantation, prévention maladies, récolte étalée.',
                'tags' => ['choux', 'brocoli', 'chou-fleur', 'variétés', 'culture'],
                'reading_time' => 8
            ],
            [
                'category' => 'Potager et Legumes',
                'title' => 'Pommes de terre nouvelles : récolte précoce dès mai',
                'content' => '<h2>Pommes de terre primeur : le plaisir du précoce</h2><p>Dégustez vos premières pommes de terre en mai ! Les variétés précoces, plantées sous tunnel ou voile, offrent des tubercules fondants aux saveurs incomparables.</p><h3>Variétés précoces recommandées</h3><p>"Amandine" chair ferme idéale vapeur, "Charlotte" allongée parfaite en salade, "Noirmoutier" reine des primeurs. Ces variétés se récoltent 70 jours après plantation.</p><h3>Techniques de précocité</h3><p>Pré-germination en caissettes 6 semaines avant plantation. Plantation sous tunnel plastique dès mars. Buttage régulier et protection contre gelées tardives avec voile.</p><h3>Récolte et conservation</h3><p>Récoltez au fur et à mesure des besoins. Les pommes de terre nouvelles ne se conservent que 8 jours mais leurs saveurs délicates justifient cette fraîcheur.</p>',
                'excerpt' => 'Pommes de terre nouvelles dès mai ! Variétés précoces, techniques de précocité, récolte et dégustation des primeurs maison.',
                'meta_title' => 'Pommes de Terre Nouvelles : Récolte Précoce Mai | FarmShop',
                'meta_description' => 'Récoltez vos pommes de terre nouvelles dès mai. Variétés précoces, pré-germination, culture sous tunnel pour primeurs maison.',
                'tags' => ['pommes de terre', 'nouvelles', 'précoce', 'primeur', 'tunnel'],
                'reading_time' => 7
            ],

            // Fruits et Verger (6 articles)
            [
                'category' => 'Fruits et Verger',
                'title' => 'Planter un verger familial : quels arbres choisir ?',
                'content' => '<h2>Constituer un verger adapté à vos besoins</h2><p>Un verger familial bien pensé peut nourrir une famille de 4 personnes en fruits frais une grande partie de l\'année. Le secret ? Choisir les bonnes variétés et échelonner les récoltes.</p><h3>Les incontournables du verger</h3><p>Commencez par 2 pommiers de variétés différentes (une précoce comme "Gala", une tardive comme "Granny"), 1 poirier "Conférence", 1 prunier "Reine-Claude" et 1 cerisier "Bigarreau".</p><h3>Fruits à maturation étalée</h3><p>Plantez plusieurs variétés de chaque espèce : pommes de juillet à mars, poires d\'août à février, prunes de juillet à octobre. Cette diversité garantit des fruits frais toute l\'année.</p><h3>Espace et pollinisation</h3><p>Prévoyez 6m entre les arbres de plein vent, 4m pour les demi-tiges. Plantez toujours au moins 2 variétés de la même espèce pour assurer la pollinisation croisée.</p>',
                'excerpt' => 'Guide pratique pour créer un verger familial productif. Choix des variétés, espacement, pollinisation et calendrier de récolte.',
                'meta_title' => 'Verger Familial : Guide pour Planter les Bons Arbres | FarmShop',
                'meta_description' => 'Créez un verger familial productif. Sélection d\'arbres fruitiers, variétés complémentaires, espacement et techniques de plantation.',
                'tags' => ['verger', 'arbres fruitiers', 'plantation', 'famille', 'récolte'],
                'reading_time' => 9
            ],
            [
                'category' => 'Fruits et Verger',
                'title' => 'Taille des arbres fruitiers : quand et comment procéder',
                'content' => '<h2>Maîtriser la taille pour des récoltes abondantes</h2><p>La taille des arbres fruitiers influence directement la qualité et la quantité de fruits. Chaque espèce a ses exigences et ses périodes optimales d\'intervention.</p><h3>Taille d\'hiver des arbres à pépins</h3><p>Pommiers et poiriers se taillent de décembre à février, hors gel. Supprimez le bois mort, aérez le centre, raccourcissez les branches gourmandes. Objectif : laisser passer la lumière au cœur de l\'arbre.</p><h3>Taille d\'été des arbres à noyaux</h3><p>Cerisiers, pruniers, pêchers se taillent après récolte pour éviter les maladies. Supprimez les branches malades, éclaircissez les rameaux trop denses, maintenez la forme.</p><h3>Outils et cicatrisation</h3><p>Sécateur bien affûté, scie d\'élagage, mastic cicatrisant sur coupes importantes. Désinfectez les outils entre chaque arbre pour éviter la propagation des maladies.</p>',
                'excerpt' => 'Maîtrisez la taille des arbres fruitiers. Techniques spécifiques, périodes optimales, outils et cicatrisation pour récoltes abondantes.',
                'meta_title' => 'Taille Arbres Fruitiers : Techniques et Périodes | FarmShop',
                'meta_description' => 'Taille des arbres fruitiers : quand et comment. Techniques pommiers, poiriers, cerisiers, outils et cicatrisation pour productivité.',
                'tags' => ['taille', 'arbres fruitiers', 'élagage', 'techniques', 'récolte'],
                'reading_time' => 8
            ],
            [
                'category' => 'Fruits et Verger',
                'title' => 'Petits fruits rouges : cassis, groseilles et framboises',
                'content' => '<h2>Les trésors du jardin : petits fruits rouges</h2><p>Cassis, groseilles et framboises transforment un coin de jardin en pharmacie naturelle. Riches en vitamines et antioxydants, ils se cultivent facilement et produisent rapidement.</p><h3>Plantation et espacement</h3><p>Plantez de novembre à mars, hors gel. Espacez de 1,50m en haie, en exposition mi-ombragée. Sol frais et humifère indispensable, paillez généreusement pour maintenir la fraîcheur.</p><h3>Taille spécifique à chaque espèce</h3><p>Cassis : supprimez 1/3 du vieux bois chaque année. Groseilliers : éclaircie en gobelet. Framboises : coupez les cannes ayant fructifié, gardez 6-8 nouvelles cannes par pied.</p><h3>Récolte et transformation</h3><p>Cueillez à maturité complète le matin. Confitüres, coulis, sirops, fruits séchés : ces petits fruits se transforment en délices pour l\'hiver.</p>',
                'excerpt' => 'Cultivez cassis, groseilles et framboises. Plantation, taille spécifique, récolte et transformation de ces trésors nutritifs.',
                'meta_title' => 'Petits Fruits Rouges : Cassis, Groseilles, Framboises | FarmShop',
                'meta_description' => 'Culture des petits fruits rouges : cassis, groseilles, framboises. Plantation, taille, récolte et transformation de ces super-aliments.',
                'tags' => ['petits fruits', 'cassis', 'groseilles', 'framboises', 'antioxydants'],
                'reading_time' => 7
            ],
            [
                'category' => 'Fruits et Verger',
                'title' => 'Agrumes en pot : cultiver citronnier et oranger chez soi',
                'content' => '<h2>Agrumes du Midi dans votre salon</h2><p>Même au nord de la Loire, vous pouvez cultiver citronnier, oranger ou mandarinier ! La culture en pot permet de les rentrer l\'hiver tout en profitant de leurs fruits parfumés.</p><h3>Choix des variétés adaptées</h3><p>Citronnier "Meyer" compact et productif, oranger "Calamondin" décoratif, mandarinier "Satsuma" résistant au froid. Ces variétés s\'adaptent parfaitement à la culture en bac.</p><h3>Substrat et rempotage</h3><p>Mélange terre de jardin, terreau agrumes et sable pour le drainage. Rempotage tous les 2-3 ans dans un pot légèrement plus grand. Drainage impératif : billes d\'argile au fond.</p><h3>Hivernage et soins</h3><p>Rentrez dès 5°C dans un local lumineux et frais (8-12°C). Réduisez l\'arrosage, stoppez l\'engrais. Sortez progressivement après les gelées, acclimatation indispensable.</p>',
                'excerpt' => 'Cultivez citronnier et oranger en pot même au nord ! Variétés adaptées, culture en bac, hivernage et soins spécifiques.',
                'meta_title' => 'Agrumes en Pot : Citronnier Oranger à la Maison | FarmShop',
                'meta_description' => 'Culture d\'agrumes en pot : citronnier, oranger, mandarinier. Variétés adaptées, substrat, hivernage pour réussir partout.',
                'tags' => ['agrumes', 'pot', 'citronnier', 'oranger', 'hivernage'],
                'reading_time' => 8
            ],
            [
                'category' => 'Fruits et Verger',
                'title' => 'Kiwis : planter et faire fructifier l\'actinidia',
                'content' => '<h2>Le kiwi, fruit exotique qui pousse en France</h2><p>Plus rustique qu\'on ne le croit, le kiwi s\'acclimate parfaitement au climat français. Cette liane vigoureuse peut produire 50 kg de fruits par pied mâle et femelle !</p><h3>Plantation et sexualité</h3><p>Plantez 1 pied mâle pour 5-6 pieds femelles. "Hayward" femelle très productive, "Atlas" mâle pollinisateur universel. Distance 3-4m, palissage sur treille solide indispensable.</p><h3>Taille et conduite</h3><p>Formez une charpente en T la première année. Taillez les rameaux fructifères à 6-8 feuilles après les derniers fruits. Supprimez le bois de plus de 3 ans, gourmand et improductif.</p><h3>Récolte et maturation</h3><p>Récoltez fin octobre avant les gelées, fruits encore fermes. Maturation en cave avec pommes qui dégagent de l\'éthylène. Patience : première récolte vers 4-5 ans !</p>',
                'excerpt' => 'Cultivez des kiwis en France ! Plantation mâle-femelle, taille spécifique, récolte et maturation de ce fruit exotique rustique.',
                'meta_title' => 'Kiwis Maison : Cultiver l\'Actinidia en France | FarmShop',
                'meta_description' => 'Culture du kiwi en France : plantation mâle-femelle, taille, palissage et récolte. Fruit exotique rustique pour tous climats.',
                'tags' => ['kiwi', 'actinidia', 'fruit exotique', 'palissage', 'rustique'],
                'reading_time' => 7
            ],
            [
                'category' => 'Fruits et Verger',
                'title' => 'Greffe des arbres fruitiers : multiplier ses variétés préférées',
                'content' => '<h2>L\'art de la greffe pour des vergers uniques</h2><p>La greffe permet de multiplier fidèlement une variété, de rajeunir un vieil arbre ou de créer des arbres multi-variétés. Cette technique ancestrale ouvre des possibilités infinies.</p><h3>Greffe en fente au printemps</h3><p>Technique la plus simple pour débuter. Coupez le porte-greffe, fendez-le, insérez 2 greffons taillés en biseau. Ligaturez, mastiquez, ensachez. Réussite 80% avec un peu de pratique.</p><h3>Écussonnage estival</h3><p>Prélevez un œil sur pousse de l\'année, insérez-le sous l\'écorce du porte-greffe en T. Technique délicate mais très efficace pour les arbres à noyaux. Période : juillet-août.</p><h3>Matériel et réussite</h3><p>Greffoir bien affûté, raphia ou plastique de greffe, mastic cicatrisant. Compatibilité indispensable : pommier sur pommier, prunier sur prunier. Désinfection systématique des outils.</p>',
                'excerpt' => 'Maîtrisez la greffe des arbres fruitiers. Techniques de fente et écussonnage, matériel, compatibilité pour multiplier vos variétés.',
                'meta_title' => 'Greffe Arbres Fruitiers : Techniques de Multiplication | FarmShop',
                'meta_description' => 'Apprenez la greffe des arbres fruitiers. Greffe en fente, écussonnage, matériel et compatibilité pour créer vos vergers uniques.',
                'tags' => ['greffe', 'arbres fruitiers', 'multiplication', 'écussonnage', 'techniques'],
                'reading_time' => 9
            ],

            // Plantes Aromatiques (6 articles)
            [
                'category' => 'Plantes Aromatiques',
                'title' => 'Cultiver un jardin d\'aromates en bacs sur la terrasse',
                'content' => '<h2>Les aromates à portée de main</h2><p>Créer un jardin d\'aromates sur sa terrasse permet d\'avoir des herbes fraîches toute l\'année. Même 2m² suffisent pour cultiver une quinzaine d\'aromates différents.</p><h3>Choix des contenants</h3><p>Privilégiez des bacs de 40cm de profondeur minimum. Les jardinières en bois ou terre cuite gardent mieux l\'humidité que le plastique. Percez des trous de drainage tous les 20cm.</p><h3>Sélection d\'aromates faciles</h3><p>Basilic, persil, ciboulette, thym, romarin, sauge, origan : ces aromates s\'adaptent parfaitement à la culture en pot. Le persil supporte la mi-ombre, idéal pour les terrasses orientées nord.</p><h3>Entretien spécifique</h3><p>Arrosez régulièrement sans excès. Pincez les fleurs pour prolonger la production de feuilles. Rentrez les aromates méditerranéens (romarin, thym) en hiver dans les régions froides.</p>',
                'excerpt' => 'Transformez votre terrasse en jardin d\'aromates productif. Conseils pratiques pour cultiver herbes fraîches en bacs toute l\'année.',
                'meta_title' => 'Jardin d\'Aromates en Bacs : Guide Terrasse | FarmShop',
                'meta_description' => 'Créez un jardin d\'aromates sur votre terrasse. Choix des bacs, aromates faciles, entretien et récolte d\'herbes fraîches.',
                'tags' => ['aromates', 'terrasse', 'bacs', 'herbes', 'jardinage urbain'],
                'reading_time' => 6
            ],
            [
                'category' => 'Plantes Aromatiques',
                'title' => 'Basilic : 12 variétés surprenantes à découvrir',
                'content' => '<h2>Le basilic décliné en 12 saveurs</h2><p>Au-delà du basilic commun, découvrez un univers aromatique insoupçonné ! Basilic pourpre, citronné, cannelle ou thai : chaque variété apporte ses notes uniques en cuisine.</p><h3>Basilics colorés et décoratifs</h3><p>"Dark Opal" pourpre intense, "African Blue" violet panaché, "Cardinal" rouge flamboyant. Ces variétés ornementales parfument autant qu\'elles décorent massifs et jardinières.</p><h3>Basilics aux parfums exotiques</h3><p>"Citron" aux notes d\'agrumes, "Cannelle" épicé, "Thai" anisé indispensable à la cuisine asiatique. "Réglisse" surprenant pour desserts originaux.</p><h3>Culture et récolte</h3><p>Semis en godets dès mars au chaud, repiquage après les gelées. Pincez régulièrement les fleurs, récoltez feuille par feuille. Séchage, congélation ou huile aromatisée pour conserver les saveurs.</p>',
                'excerpt' => '12 variétés de basilic aux saveurs surprenantes : pourpre, citronné, cannelle, thai. Découvrez l\'univers aromatique du basilic !',
                'meta_title' => 'Basilic : 12 Variétés Surprenantes à Cultiver | FarmShop',
                'meta_description' => 'Découvrez 12 variétés de basilic aux parfums uniques : pourpre, citron, cannelle, thai. Culture, récolte et conservation.',
                'tags' => ['basilic', 'variétés', 'aromates', 'parfums', 'cuisine'],
                'reading_time' => 6
            ]
        ];
    }
}
