<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\User;

class BlogPostBatch2Seeder extends Seeder
{
    /**
     * Seeder pour 4 catégories : Plantes Aromatiques, Jardinage Bio, Animaux de Basse-cour, Apiculture
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
        $startDate = Carbon::now()->subMonths(5); // Décaler par rapport au batch 1

        foreach ($articles as $index => $article) {
            $category = $categories->get($article['category']);
            
            if (!$category) {
                $this->command->warn("Catégorie '{$article['category']}' non trouvée pour l'article '{$article['title']}'");
                continue;
            }

            $publishedAt = $startDate->copy()->addDays($index * 3); // Étaler sur 5 mois
            
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
                    'batch' => 2,
                    'featured' => $index < 5,
                    'priority' => $index < 3 ? 'high' : 'normal'
                ],
                'tags' => $article['tags'],
                'views_count' => rand(60, 900),
                'likes_count' => rand(8, 70),
                'shares_count' => rand(2, 30),
                'comments_count' => rand(1, 25),
                'reading_time' => $article['reading_time'],
                'allow_comments' => true,
                'is_featured' => $index < 3,
                'is_sticky' => false, // Pas d'épinglé dans ce batch
                'author_id' => $admin->id,
                'last_edited_by' => $admin->id,
                'last_edited_at' => $publishedAt,
            ]);
            
            $publishedCount++;
        }

        $this->command->info("✅ Batch 2 : {$publishedCount} articles créés pour 4 catégories !");
    }

    private function getArticles()
    {
        return [
            // Plantes Aromatiques (5 articles)
            [
                'category' => 'Plantes Aromatiques',
                'title' => 'Basilic : 12 variétés surprenantes à découvrir',
                'content' => '<h2>Le basilic décliné en 12 saveurs</h2><p>Au-delà du basilic commun, découvrez un univers aromatique insoupçonné ! Basilic pourpre, citronné, cannelle ou thai : chaque variété apporte ses notes uniques en cuisine.</p><h3>Basilics colorés et décoratifs</h3><p><strong>"Dark Opal"</strong> pourpre intense, <strong>"African Blue"</strong> violet panaché, <strong>"Cardinal"</strong> rouge flamboyant. Ces variétés ornementales parfument autant qu\'elles décorent massifs et jardinières.</p><h3>Basilics aux parfums exotiques</h3><p><strong>"Citron"</strong> aux notes d\'agrumes, <strong>"Cannelle"</strong> épicé, <strong>"Thai"</strong> anisé indispensable à la cuisine asiatique. <strong>"Réglisse"</strong> surprenant pour desserts originaux.</p><h3>Culture et récolte</h3><p>Semis en godets dès mars au chaud, repiquage après les gelées. Pincez régulièrement les fleurs, récoltez feuille par feuille. Séchage, congélation ou huile aromatisée pour conserver les saveurs.</p>',
                'excerpt' => '12 variétés de basilic aux saveurs surprenantes : pourpre, citronné, cannelle, thai. Découvrez l\'univers aromatique du basilic !',
                'meta_title' => 'Basilic : 12 Variétés Surprenantes à Cultiver | FarmShop',
                'meta_description' => 'Découvrez 12 variétés de basilic aux parfums uniques : pourpre, citron, cannelle, thai. Culture, récolte et conservation.',
                'tags' => ['basilic', 'variétés', 'aromates', 'parfums', 'cuisine'],
                'reading_time' => 6
            ],
            [
                'category' => 'Plantes Aromatiques',
                'title' => 'Thym : culture et multiplication faciles',
                'content' => '<h2>Le thym, aromate méditerranéen incontournable</h2><p>Résistant à la sécheresse, parfumé, mellifère : le thym cumule les qualités. Apprenez à le cultiver et le multiplier pour avoir toujours cette herbe précieuse à portée de main.</p><h3>Variétés recommandées</h3><p><strong>Thym commun</strong> : Rustique, parfum intense, parfait cuisine. <strong>Thym citron</strong> : Notes d\'agrumes, idéal infusions et poissons. <strong>Thym serpolet</strong> : Couvre-sol parfumé, fleurs mellifères.</p><h3>Culture simplifiée</h3><p>Sol drainant obligatoire, exposition plein soleil. Résiste à -15°C, ne supporte pas l\'humidité stagnante. Plantation au printemps, espacement 30cm.</p><h3>Multiplication par division</h3><p>Division des touffes au printemps tous les 3-4 ans. Séparation délicate avec racines, repiquage immédiat. Bouturage possible en juin-juillet.</p><h3>Récolte et conservation</h3><p>Cueillette avant floraison pour maximum d\'arômes. Séchage en bouquets suspendus, conservation en bocaux hermétiques. Congélation possible en glaçons.</p>',
                'excerpt' => 'Culture et multiplication du thym : variétés, sol drainant, division des touffes. L\'aromate méditerranéen facile.',
                'meta_title' => 'Thym : Culture et Multiplication Faciles | FarmShop',
                'meta_description' => 'Guide culture thym : variétés, plantation, multiplication. Aromate méditerranéen résistant et parfumé.',
                'tags' => ['thym', 'aromates', 'méditerranéen', 'multiplication', 'culture'],
                'reading_time' => 5
            ],
            [
                'category' => 'Plantes Aromatiques',
                'title' => 'Menthe : maîtriser son expansion',
                'content' => '<h2>La menthe envahissante mais délicieuse</h2><p>Rafraîchissante, digestive, polyvalente : la menthe est irremplaçable en cuisine et phytothérapie. Mais attention à son caractère envahissant ! Découvrez comment la cultiver sans qu\'elle colonise tout le jardin.</p><h3>Confinement obligatoire</h3><p>Culture en pot conseillée ou barrière anti-rhizomes. Enterrez un pot sans fond ou des planches sur 40cm de profondeur. Les stolons ne passent pas !</p><h3>Variétés gourmandes</h3><p><strong>Menthe verte</strong> : Classique, parfum équilibré. <strong>Menthe chocolat</strong> : Notes cacaotées surprenantes. <strong>Menthe marocaine</strong> : Feuilles fines, thé à la menthe authentique.</p><h3>Culture et entretien</h3><p>Mi-ombre appréciée, sol frais maintenu. Arrosage régulier, paillage conseillé. Division annuelle pour rajeunir les touffes.</p><h3>Conservation optimale</h3><p>Congélation en glaçons, séchage rapide au four 60°C. Sirop de menthe maison, huile aromatisée pour prolonger les plaisirs.</p>',
                'excerpt' => 'Maîtriser la culture de menthe : confinement, variétés gourmandes, entretien. Éviter l\'invasion tout en profitant des saveurs.',
                'meta_title' => 'Menthe : Maîtriser son Expansion au Jardin | FarmShop',
                'meta_description' => 'Guide culture menthe : confinement, variétés, entretien. Cultiver sans invasion, profiter des saveurs rafraîchissantes.',
                'tags' => ['menthe', 'confinement', 'variétés', 'envahissante', 'aromates'],
                'reading_time' => 5
            ],
            [
                'category' => 'Plantes Aromatiques',
                'title' => 'Romarin : taille et hivernage réussis',
                'content' => '<h2>Le romarin, sentinelle méditerranéenne</h2><p>Persistant, résistant, aromatique : le romarin structure le jardin toute l\'année. Maîtrisez sa taille et son hivernage pour un arbuste toujours beau et productif.</p><h3>Taille annuelle indispensable</h3><p><strong>Quand :</strong> Après floraison, mai-juin. Évitez l\'automne qui fragilise avant l\'hiver.</p><p><strong>Comment :</strong> Raccourcissez de 1/3, respectez le bois vert. Ne taillez jamais dans le vieux bois qui ne reperce pas.</p><h3>Variétés selon climat</h3><p><strong>Romarin officinal</strong> : Résiste -10°C, port dressé classique. <strong>Romarin rampant</strong> : Couvre-sol, plus fragile. <strong>Romarin à fleurs blanches</strong> : Décoratif, rusticité moyenne.</p><h3>Protection hivernale</h3><p>Paillage du pied, voile d\'hivernage en zone limite. Culture en pot possible, rentrage hors gel. Évitez l\'humidité stagnante fatale.</p><h3>Multiplication simple</h3><p>Bouturage en septembre, réussite 80%. Tiges de 15cm, suppression feuilles basses, hormone de bouturage optionnelle.</p>',
                'excerpt' => 'Romarin : taille après floraison, protection hivernale, multiplication. Maîtriser la sentinelle méditerranéenne.',
                'meta_title' => 'Romarin : Taille et Hivernage Réussis | FarmShop',
                'meta_description' => 'Guide romarin : taille, hivernage, variétés selon climat. Arbuste méditerranéen persistant et aromatique.',
                'tags' => ['romarin', 'taille', 'hivernage', 'méditerranéen', 'persistant'],
                'reading_time' => 6
            ],
            [
                'category' => 'Plantes Aromatiques',
                'title' => 'Ciboulette : semis et division pour débutants',
                'content' => '<h2>La ciboulette, aromate facile et généreux</h2><p>Vivace rustique, croissance rapide, saveur délicate : la ciboulette est l\'aromate parfait pour débuter. Semis, division, récolte : tout est simple avec cette cousine de l\'oignon.</p><h3>Semis ultra-facile</h3><p>Semis direct mars-avril, graines en surface juste recouvertes. Germination 10-15 jours, éclaircissage à 15cm. Première récolte 2 mois après semis.</p><h3>Division tous les 3 ans</h3><p><strong>Période :</strong> Printemps ou automne, hors gel. Déterrer la touffe, séparer en éclats avec racines.</p><p><strong>Technique :</strong> Couteau propre, 5-6 brins minimum par éclat. Replantation immédiate, arrosage suivi.</p><h3>Récolte continue</h3><p>Coupez à 2cm du sol, repousse en 15 jours. Évitez de laisser fleurir si vous voulez privilégier les feuilles. Congélation possible ciselée.</p><h3>Variétés intéressantes</h3><p><strong>Ciboulette commune</strong> : Rustique, productive. <strong>Ciboulette à grosses touffes</strong> : Plus vigoureuse. <strong>Ciboulette chinoise</strong> : Feuilles plates, goût d\'ail léger.</p>',
                'excerpt' => 'Ciboulette facile : semis direct, division tous les 3 ans, récolte continue. L\'aromate parfait pour débuter.',
                'meta_title' => 'Ciboulette : Semis et Division pour Débutants | FarmShop',
                'meta_description' => 'Guide ciboulette débutant : semis facile, division, récolte. Aromate vivace rustique et généreux.',
                'tags' => ['ciboulette', 'semis', 'division', 'débutants', 'vivace'],
                'reading_time' => 4
            ],

            // Jardinage Bio (5 articles)
            [
                'category' => 'Jardinage Bio',
                'title' => 'Purins et décoctions : 15 recettes naturelles',
                'content' => '<h2>L\'arsenal naturel du jardinier bio</h2><p>Ortie, prêle, consoude, pissenlit... La nature regorge d\'alliés précieux pour fortifier vos cultures sans chimie. Ces préparations ancestrales nourrissent et protègent efficacement.</p><h3>Purins fertilisants</h3><p><strong>Purin d\'ortie :</strong> 1kg d\'orties fraîches dans 10L d\'eau, fermentation 15 jours. Dilution 1/10 pour fertiliser, 1/20 pour traiter pucerons.</p><p><strong>Purin de consoude :</strong> Riche en potasse, idéal tomates et fruits. Même principe, dilution 1/5 maximum.</p><h3>Décoctions protectrices</h3><p><strong>Décoction de prêle :</strong> 100g séché dans 1L, bouillir 30min, diluer 1/5. Anti-fongique puissant contre mildiou, oïdium.</p><p><strong>Infusion d\'ail :</strong> 100g gousses hachées, eau bouillante, infuser 12h. Répulsif insectes et escargots.</p><h3>Macérations spéciales</h3><p><strong>Macération de rhubarbe :</strong> Anti-pucerons redoutable. <strong>Purin de pissenlit :</strong> Stimulant général, améliore résistance.</p>',
                'excerpt' => '15 recettes de purins et décoctions naturelles pour fertiliser et protéger votre jardin bio. Ortie, consoude, prêle...',
                'meta_title' => 'Purins et Décoctions Bio : 15 Recettes Naturelles | FarmShop',
                'meta_description' => '15 recettes de purins et décoctions bio pour votre jardin : ortie, consoude, prêle. Fertilisation et protection naturelles.',
                'tags' => ['jardinage bio', 'purins', 'décoctions', 'naturel', 'fertilisant'],
                'reading_time' => 8
            ],
            [
                'category' => 'Jardinage Bio',
                'title' => 'Rotation des cultures : planification sur 4 ans',
                'content' => '<h2>La rotation, clé de la fertilité naturelle</h2><p>Légumes-feuilles, légumes-fruits, légumes-racines, légumineuses : cette rotation de 4 ans préserve la fertilité du sol et limite naturellement parasites et maladies.</p><h3>Année 1 : Légumineuses</h3><p>Haricots, pois, fèves enrichissent le sol en azote. Préparer avec compost, ameublir profondément. Ces "engrais verts" nourrissent les cultures suivantes.</p><h3>Année 2 : Légumes-feuilles</h3><p>Choux, épinards, laitues profitent de l\'azote fixé. Apports modérés en compost mûr. Binages fréquents, paillage léger conseillé.</p><h3>Année 3 : Légumes-fruits</h3><p>Tomates, courgettes, aubergines consomment beaucoup. Amendements organiques copieux, arrosages suivis, tuteurage solide nécessaire.</p><h3>Année 4 : Légumes-racines</h3><p>Carottes, radis, betteraves nettoient le sol. Éviter fumure fraîche (racines fourchues). Bêchage profond, sol bien drainé.</p>',
                'excerpt' => 'Planification de rotation des cultures sur 4 ans : légumineuses, feuilles, fruits, racines. Fertilité naturelle du sol.',
                'meta_title' => 'Rotation des Cultures : Plan sur 4 Ans pour Votre Potager | FarmShop',
                'meta_description' => 'Guide de rotation des cultures sur 4 ans : légumineuses, légumes-feuilles, fruits, racines. Fertilité naturelle garantie.',
                'tags' => ['rotation cultures', 'planification', 'potager bio', 'fertilité sol', 'légumineuses'],
                'reading_time' => 9
            ],
            [
                'category' => 'Jardinage Bio',
                'title' => 'Paillis : 10 matériaux pour chaque usage',
                'content' => '<h2>Le paillis, allié incontournable du jardin</h2><p>Économie d\'eau, limitation des adventices, protection du sol : le paillage transforme votre jardinage. Chaque matériau apporte ses spécificités selon vos cultures.</p><h3>Paillis organiques décoratifs</h3><p><strong>Copeaux de bois :</strong> Durables, esthétiques, parfaits massifs et allées. Évitez près légumes (faim d\'azote).</p><p><strong>Paille :</strong> Classique potager, excellente isolation. Idéal fraisiers, tomates, courgettes.</p><h3>Paillis nutritifs</h3><p><strong>Tonte de gazon :</strong> Gratuite, se décompose rapidement. Sécher avant usage, couche fine pour éviter fermentation.</p><p><strong>Feuilles mortes :</strong> Broyées, excellent humus. Parfait vivaces et arbustes, protection hivernale.</p><h3>Paillis minéraux</h3><p><strong>Ardoise pilée :</strong> Décorative, durable, régule température. Idéale plantes méditerranéennes, drainage parfait.</p>',
                'excerpt' => '10 types de paillis pour chaque usage au jardin : copeaux, paille, tonte, feuilles. Guide complet du paillage.',
                'meta_title' => 'Paillis : 10 Matériaux pour Chaque Usage au Jardin | FarmShop',
                'meta_description' => 'Guide complet des paillis : 10 matériaux organiques et minéraux pour protéger et nourrir votre jardin naturellement.',
                'tags' => ['paillis', 'paillage', 'jardinage bio', 'protection sol', 'économie eau'],
                'reading_time' => 7
            ],
            [
                'category' => 'Jardinage Bio',
                'title' => 'Auxiliaires du jardin : les attirer et les garder',
                'content' => '<h2>Créer un écosystème protecteur</h2><p>Coccinelles, chrysopes, hérissons, oiseaux : ces alliés naturels régulent parasites et nuisibles plus efficacement que les pesticides. Créons-leur un habitat accueillant !</p><h3>Insectes auxiliaires</h3><p><strong>Coccinelles :</strong> Plantez aneth, fenouil, achillée. Hôtel à insectes avec bambous creux. Une coccinelle dévore 150 pucerons/jour !</p><p><strong>Chrysopes :</strong> Attirées par cosmos, tournesols. Larves voraces de pucerons et cochenilles, surnommées "lions des pucerons".</p><h3>Prédateurs plus gros</h3><p><strong>Hérisson :</strong> Tas de branches, passage sous clôture. Dévore limaces, escargots, insectes nuisibles chaque nuit.</p><p><strong>Oiseaux :</strong> Nichoirs, points d\'eau, haies diversifiées. Mésanges consomment 500 chenilles/jour en période nidification.</p><h3>Aménagements favorables</h3><p>Haies mellifères, prairie fleurie, mare, pierriers : diversité des habitats = équilibre naturel garanti.</p>',
                'excerpt' => 'Comment attirer auxiliaires au jardin : coccinelles, chrysopes, hérissons, oiseaux. Régulation naturelle des parasites.',
                'meta_title' => 'Auxiliaires du Jardin : Attirer vos Alliés Naturels | FarmShop',
                'meta_description' => 'Guide pour attirer auxiliaires au jardin : coccinelles, chrysopes, hérissons, oiseaux. Protection naturelle contre parasites.',
                'tags' => ['auxiliaires', 'coccinelles', 'biodiversité', 'protection naturelle', 'écosystème'],
                'reading_time' => 8
            ],
            [
                'category' => 'Jardinage Bio',
                'title' => 'Compostage : réussir en 6 étapes simples',
                'content' => '<h2>Transformer déchets en or noir</h2><p>Épluchures, tontes, feuilles mortes deviennent compost riche en 6 mois. Cette transformation naturelle nourrit votre jardin tout en réduisant vos déchets de 30%.</p><h3>Étape 1 : Choisir l\'emplacement</h3><p>Mi-ombre, à l\'abri du vent, sur terre nue. Accès facile avec brouette. Bac de 1m³ minimum ou simple tas pour grands jardins.</p><h3>Étape 2 : Équilibrer matières</h3><p><strong>Matières azotées :</strong> Épluchures, tontes fraîches, marc de café (1/3)</p><p><strong>Matières carbonées :</strong> Feuilles sèches, branches broyées, carton (2/3)</p><h3>Étapes 3-4 : Stratifier et mélanger</h3><p>Alterner couches de 20cm, mélanger régulièrement. Aérer avec fourche tous les 15 jours première fois.</p><h3>Étapes 5-6 : Surveiller et récolter</h3><p>Humidité éponge essorée, maturation 6-12 mois. Compost prêt = brun, grumeleux, odeur de sous-bois.</p>',
                'excerpt' => 'Réussir son compost en 6 étapes : emplacement, équilibre matières, brassage. Guide complet du compostage.',
                'meta_title' => 'Compostage : Réussir en 6 Étapes Simples | FarmShop',
                'meta_description' => 'Guide complet du compostage : 6 étapes pour transformer déchets en compost. Emplacement, équilibre, maturation.',
                'tags' => ['compostage', 'compost', 'recyclage', 'déchets verts', 'matières organiques'],
                'reading_time' => 8
            ],

            // Animaux de Basse-cour (5 articles)
            [
                'category' => 'Animaux de Basse-cour',
                'title' => 'Poules pondeuses : 10 races les plus productives',
                'content' => '<h2>Choisir ses poules pour optimiser la ponte</h2><p>Entre 250 et 320 œufs par an : certaines races cumulent production et rusticité. Découvrez les championnes de la ponte pour un poulailler familial productif.</p><h3>Les records de ponte</h3><p><strong>ISA Brown :</strong> Hybride commercial, 320 œufs/an, ponte précoce 18 semaines. Docile, adaptable, œufs roux calibre moyen parfaits.</p><p><strong>Leghorn blanche :</strong> Race pure, 280 œufs blancs/an. Nerveuse mais excellente pondeuse, résiste chaleur méditerranéenne.</p><h3>Rusticité et production</h3><p><strong>Sussex herminée :</strong> 250 œufs crème/an, très rustique. Chair excellente, couvaison naturelle possible pour reproduction.</p><p><strong>Rhode Island Red :</strong> 270 œufs roux/an, résiste froid. Tempérament calme, idéale débutants en basse-cour.</p><h3>Races d\'exception</h3><p><strong>Marans :</strong> 200 œufs "chocolat" extra-roux/an. Moins productive mais œufs recherchés gastronomie.</p><p><strong>Harco :</strong> Hybride récent, 290 œufs/an, plumage noir à camail doré. Robuste et productive, parfait compromis.</p>',
                'excerpt' => '10 races de poules pondeuses les plus productives : ISA Brown, Leghorn, Sussex. Guide des championnes de la ponte.',
                'meta_title' => 'Poules Pondeuses : 10 Races les Plus Productives | FarmShop',
                'meta_description' => 'Top 10 races poules pondeuses : ISA Brown, Leghorn, Sussex, Rhode Island. Production, rusticité et caractéristiques.',
                'tags' => ['poules pondeuses', 'races poules', 'ponte', 'œufs', 'productivité'],
                'reading_time' => 7
            ],
            [
                'category' => 'Animaux de Basse-cour',
                'title' => 'Construire un poulailler : plans et matériaux',
                'content' => '<h2>Concevoir l\'habitat idéal</h2><p>1m² au sol + 10m² parcours par poule : respecter ces proportions garantit bien-être et productivité. Plans détaillés pour autoconstruction économique et durable.</p><h3>Dimensions et conception</h3><p><strong>Poulailler 6 poules :</strong> 2x3m au sol, hauteur 1,8m. Prédire extension future, prévoir isolation région froide importante.</p><p><strong>Perchoirs :</strong> 25cm/poule, section 4x4cm, hauteur 50cm. Démontables pour nettoyage, éviter perchoirs ronds.</p><h3>Matériaux recommandés</h3><p><strong>Ossature :</strong> Bois traité classe 3, visserie inox. Éviter aggloméré (humidité), privilégier assemblages vissés.</p><p><strong>Couverture :</strong> Bac acier isolé ou ardoises. Pente 30% minimum évacuation eaux, débords protecteurs.</p><h3>Aménagements essentiels</h3><p><strong>Pondoirs :</strong> 30x30x30cm, 1 pour 4 poules. Litière paille, ramasse-œufs extérieur pratique.</p><p><strong>Parcours :</strong> Grillage maille 25mm enterré 30cm. Filet anti-rapaces hauteur 2m, portail accès facile.</p>',
                'excerpt' => 'Plans détaillés pour construire poulailler : dimensions, matériaux, aménagements. Guide complet autoconstruction.',
                'meta_title' => 'Construire un Poulailler : Plans et Matériaux | FarmShop',
                'meta_description' => 'Guide construction poulailler : plans détaillés, matériaux, dimensions. Habitat idéal pour poules pondeuses.',
                'tags' => ['construction poulailler', 'plans poulailler', 'matériaux', 'aménagement', 'basse-cour'],
                'reading_time' => 9
            ],
            [
                'category' => 'Animaux de Basse-cour',
                'title' => 'Alimentation poules : ration équilibrée maison',
                'content' => '<h2>Nourrir naturellement ses poules</h2><p>120g/jour/poule d\'un mélange équilibré : céréales (70%), protéines (20%), minéraux (10%). Recette économique et nutritive pour poules en pleine forme et ponte optimale.</p><h3>Base céréalière (70%)</h3><p><strong>Blé :</strong> 40% du mélange, énergie principale. Tremper 12h améliore digestibilité, évite gaspillage granulés.</p><p><strong>Maïs concassé :</strong> 30%, apport énergétique hivernal. Éviter excès (graisse abdominale néfaste ponte).</p><h3>Apports protéiques (20%)</h3><p><strong>Tourteau de soja :</strong> 15%, protéines végétales équilibrées. Alternative : tourteau de tournesol moins cher.</p><p><strong>Vers de terre :</strong> 5% frais ou séchés, protéines animales naturelles. Élevage possible composteur.</p><h3>Compléments minéraux (10%)</h3><p><strong>Coquilles d\'huîtres :</strong> Calcium pour coquilles solides, libre service mangeoire séparée.</p><p><strong>Gravier :</strong> Aide digestion, stockage gavage séparé. Renouveler régulièrement, granulométrie adaptée.</p>',
                'excerpt' => 'Ration équilibrée maison pour poules : céréales, protéines, minéraux. Recette économique et nutritive.',
                'meta_title' => 'Alimentation Poules : Ration Équilibrée Maison | FarmShop',
                'meta_description' => 'Guide alimentation poules : ration équilibrée, céréales, protéines, minéraux. Nutrition naturelle et économique.',
                'tags' => ['alimentation poules', 'ration', 'céréales', 'protéines', 'nutrition'],
                'reading_time' => 6
            ],
            [
                'category' => 'Animaux de Basse-cour',
                'title' => 'Canards de Barbarie : élevage et avantages',
                'content' => '<h2>L\'alternative rustique aux poules</h2><p>Moins bruyants que les canards classiques, les Barbarie excellent en chair et pondent 120 œufs/an. Leur robustesse et facilité d\'élevage séduisent de plus en plus d\'éleveurs familiaux.</p><h3>Caractéristiques remarquables</h3><p><strong>Silence appréciable :</strong> Chuintements discrets vs cancanements. Idéal voisinage proche, réglementation urbaine respectée.</p><p><strong>Chair savoureuse :</strong> Moins grasse que canard classique, goût délicat. Mâles 4-5kg, femelles 2,5kg, rendement excellent.</p><h3>Installation et logement</h3><p><strong>Abri simple :</strong> Cabane 2m² pour 6 canards, litière paille épaisse. Résistent froid mais craignent humidité stagnante.</p><p><strong>Parcours :</strong> 20m² minimum/sujet, accès point d\'eau apprécié mais non indispensable contrairement idées reçues.</p><h3>Reproduction naturelle</h3><p><strong>Ponte :</strong> Mars à octobre, 120 œufs/an pesant 70g. Excellentes couveuses 35 jours incubation, instinct maternel développé.</p><p><strong>Élevage canetons :</strong> Autonomes dès éclosion, croissance rapide. Abattage optimal 10-12 semaines, chair tendre.</p>',
                'excerpt' => 'Élevage canards de Barbarie : silencieux, rustiques, chair savoureuse. Alternative intéressante aux poules.',
                'meta_title' => 'Canards de Barbarie : Élevage et Avantages | FarmShop',
                'meta_description' => 'Guide élevage canards Barbarie : caractéristiques, installation, reproduction. Alternative rustique aux poules.',
                'tags' => ['canards barbarie', 'élevage canards', 'basse-cour', 'chair', 'silencieux'],
                'reading_time' => 8
            ],
            [
                'category' => 'Animaux de Basse-cour',
                'title' => 'Prévenir maladies : santé de la basse-cour',
                'content' => '<h2>Prévention = meilleure médecine</h2><p>Observation quotidienne, hygiène rigoureuse, prophylaxie adaptée : ces gestes simples préservent la santé de vos volailles mieux que tous les traitements curatifs coûteux.</p><h3>Signes d\'alerte à surveiller</h3><p><strong>Comportement :</strong> Isolement, abattement, baisse ponte subite. Poule qui reste perchée en journée = urgence vétérinaire.</p><p><strong>Physique :</strong> Plumes ébouriffées, écoulements nez/yeux, diarrhée colorée. Palpation jabot vide le matin inquiétante.</p><h3>Hygiène préventive</h3><p><strong>Nettoyage :</strong> Litière changée chaque semaine, désinfection mensuelle poulailler. Eau propre quotidiennement, mangeoires nettoyées.</p><p><strong>Vide sanitaire :</strong> 15 jours entre lots, nettoyage intégral avec chaux vive. Désinfection matériel, renouvellement litière complète.</p><h3>Prophylaxie naturelle</h3><p><strong>Vinaigre de cidre :</strong> 1 cuillère/litre d\'eau, 3 jours/mois. Acidifie tube digestif, limite pathogènes, renforce immunité.</p><p><strong>Ail frais :</strong> Vermifuge naturel, stimule immunité. 1 gousse hachée/10 poules dans pâtée, cure mensuelle.</p>',
                'excerpt' => 'Prévenir maladies basse-cour : observation, hygiène, prophylaxie naturelle. Santé volailles par la prévention.',
                'meta_title' => 'Prévenir Maladies : Santé de la Basse-cour | FarmShop',
                'meta_description' => 'Guide prévention maladies volailles : observation, hygiène, prophylaxie. Santé basse-cour par prévention.',
                'tags' => ['santé volailles', 'prévention maladies', 'hygiène', 'prophylaxie', 'observation'],
                'reading_time' => 7
            ],

            // Apiculture (5 articles)
            [
                'category' => 'Apiculture',
                'title' => 'Débuter en apiculture : matériel indispensable',
                'content' => '<h2>S\'équiper pour ses premières ruches</h2><p>Ruche, enfumoir, lève-cadres, combinaison : l\'investissement initial représente 300-500€ par ruche. Guide d\'achat pour débuter sereinement dans l\'aventure apicole passionnante.</p><h3>La ruche et ses éléments</h3><p><strong>Corps de ruche :</strong> Dadant 10 cadres recommandée débutants. Bois non traité, assemblage vissé solide. Prévoir plateau, toit, grille reine séparatrice.</p><p><strong>Cadres :</strong> Filés inox, cire gaufrée bio indispensable. Jeu complet : 10 corps + 9 hausse minimum par ruche active.</p><h3>Équipement de protection</h3><p><strong>Combinaison :</strong> Tissu épais, voile intégré, fermetures étanches. Gants cuir souple débutants, pas gants plastique.</p><p><strong>Chaussures :</strong> Montantes, lisses (éviter velcro attractif). Surchaussures plastique économiques, nettoyage facile.</p><h3>Outils de travail</h3><p><strong>Enfumoir :</strong> Grand modèle, combustible naturel (carton, aiguilles pin). Allumage facile, fumée froide apaisante.</p><p><strong>Lève-cadres :</strong> Modèle coudé polyvalent, acier inox. Brosse soies naturelles inspection délicate.</p>',
                'excerpt' => 'Matériel indispensable débuter apiculture : ruche, combinaison, enfumoir, lève-cadres. Guide équipement 300-500€.',
                'meta_title' => 'Débuter Apiculture : Matériel Indispensable | FarmShop',
                'meta_description' => 'Guide matériel apiculture débutant : ruche, protection, outils. Équipement indispensable pour débuter.',
                'tags' => ['apiculture débutant', 'matériel apiculture', 'ruche', 'équipement', 'protection'],
                'reading_time' => 8
            ],
            [
                'category' => 'Apiculture',
                'title' => 'Calendrier apicole : interventions mois par mois',
                'content' => '<h2>Rythmer ses visites selon les saisons</h2><p>L\'abeille suit les saisons : hibernation, développement printanier, miellées, préparation hivernage. Adapter ses interventions au cycle naturel optimise productions et survie colonies.</p><h3>Hiver (décembre-février)</h3><p><strong>Décembre :</strong> Visite extérieures uniquement. Vérifier étanchéité, dégager entrée neige. Pas d\'ouverture ruche, cluster fragile.</p><p><strong>Janvier :</strong> Commande matériel saison. Préparation cadres, entretien outils. Surveillance prédateurs (pic-vert destructeur).</p><p><strong>Février :</strong> Première visite rapide jour doux (+15°C). Évaluation provisions, mortalité éventuelle, pesée arrière.</p><h3>Printemps (mars-mai)</h3><p><strong>Mars :</strong> Nettoyage plateau, changement cadres noircis. Stimulation sirop léger si provisions insuffisantes.</p><p><strong>Avril :</strong> Pose hausses, extension couvain. Prévention essaimage, marquage reines, divisions préventives.</p><p><strong>Mai :</strong> Surveillance essaimage, captures, divisions curatives. Première récolte miel acacia précoce.</p><h3>Été-Automne (juin-novembre)</h3><p><strong>Juin-juillet :</strong> Récoltes principales, extraction soigneuse. Protection canicule, abreuvement permanent, ombrage ruches.</p>',
                'excerpt' => 'Calendrier apicole mois par mois : interventions hivernales, printanières, estivales. Rythmer visites selon saisons.',
                'meta_title' => 'Calendrier Apicole : Interventions Mois par Mois | FarmShop',
                'meta_description' => 'Calendrier apicole complet : interventions par saison, visites, récoltes. Guide mois par mois.',
                'tags' => ['calendrier apicole', 'interventions ruche', 'saisons', 'visites', 'planning'],
                'reading_time' => 9
            ],
            [
                'category' => 'Apiculture',
                'title' => 'Varroa destructor : traitement et prévention',
                'content' => '<h2>Lutter contre le fléau des ruches</h2><p>Varroa destructor décime colonies mondiales depuis 30 ans. Traitement intégré biologique et surveillance régulière permettent cohabitation contrôlée avec ce parasite redoutable.</p><h3>Cycle et dégâts du varroa</h3><p><strong>Reproduction :</strong> Femelle pond dans cellule operculée, progéniture se nourrit hémolymphe nymphe. Affaiblissement, virus transmis (DWV).</p><p><strong>Symptômes :</strong> Abeilles déformées, ailes atrophiées, couvain troué. Chute naturelle +50 varroas/jour = urgence traitement.</p><h3>Méthodes biologiques</h3><p><strong>Acide formique :</strong> 65% concentration, diffuseurs 3 semaines. Efficace +90%, période hors miellée obligatoire.</p><p><strong>Thymol :</strong> Thymovar, Apiguard selon température. Traitement doux, goût miel préservé, colonies acceptantes.</p><h3>Techniques mécaniques</h3><p><strong>Cadre piège :</strong> Cire mâle sacrifié, retrait couvain operculé. Réduction 30% population parasite, méthode complémentaire.</p><p><strong>Interruption ponte :</strong> Encagement reine 25 jours, élimination varroas reproducteurs. Technique drastique, colonies fortes uniquement.</p>',
                'excerpt' => 'Varroa destructor : traitements biologiques, surveillance, prévention. Lutter contre le parasite des abeilles.',
                'meta_title' => 'Varroa Destructor : Traitement et Prévention | FarmShop',
                'meta_description' => 'Guide traitement varroa : méthodes biologiques, surveillance, prévention. Lutter contre le parasite des ruches.',
                'tags' => ['varroa', 'traitement varroa', 'parasite abeilles', 'apiculture bio', 'surveillance'],
                'reading_time' => 8
            ],
            [
                'category' => 'Apiculture',
                'title' => 'Plantes mellifères : jardin des abeilles',
                'content' => '<h2>Créer un buffet permanent</h2><p>Succession florale mars-octobre garantit ressources constantes. Planter mellifères compense raréfaction flore sauvage et soutient colonies en disette, investissement rentable.</p><h3>Floraison précoce (mars-mai)</h3><p><strong>Arbres :</strong> Saule marsault, merisier, érable champêtre. Pollen abondant relance pontes printanières, ressource critique sortie hivernage.</p><p><strong>Arbustes :</strong> Groseillier, cassissier, aubépine. Floraisons généreuses, facilement cultivables, mellifères reconnus.</p><h3>Miellées principales (juin-juillet)</h3><p><strong>Tilleul :</strong> Miellée courte mais intense. Miel clair délicat, 15-20kg/ruche en bonne année climatique.</p><p><strong>Châtaignier :</strong> Miel ambré corsé, récolte juillet. Floraison longue, sécurise production, tanins caractéristiques.</p><h3>Soutien estival (août-septembre)</h3><p><strong>Tournesol :</strong> Pollen protéiné excellent. Implanter variétés échelonnées allonger période, cultures possibles.</p><p><strong>Sarrasin :</strong> Mellifère exceptionnel, miel typé. Culture possible 60 jours, engrais vert mellifère double usage.</p>',
                'excerpt' => 'Plantes mellifères pour jardin abeilles : succession florale mars-octobre. Soutenir colonies par diversité florale.',
                'meta_title' => 'Plantes Mellifères : Jardin des Abeilles | FarmShop',
                'meta_description' => 'Guide plantes mellifères : succession florale, arbres, vivaces. Créer jardin pour soutenir colonies abeilles.',
                'tags' => ['plantes mellifères', 'jardin abeilles', 'floraison', 'nectar', 'pollen'],
                'reading_time' => 7
            ],
            [
                'category' => 'Apiculture',
                'title' => 'Récolte du miel : extraction et conservation',
                'content' => '<h2>De la ruche au pot</h2><p>Désoperculation, extraction, maturation : chaque étape influence qualité finale. Maîtrisez ces gestes techniques pour un miel parfait, conservant toutes ses propriétés naturelles.</p><h3>Moment optimal récolte</h3><p><strong>Maturité :</strong> Miel operculé 80% minimum, humidité <20%. Test secouage cadre, pas d\'écoulement = prêt extraction.</p><p><strong>Météo :</strong> Journée ensoleillée, abeilles au travail. Éviter veille d\'orage, nervosité colonies.</p><h3>Extraction soigneuse</h3><p><strong>Désoperculation :</strong> Couteau chaud, lames parfaitement propres. Tranches fines, cire récupérée séparément, pas de miel gaspillé.</p><p><strong>Centrifugation :</strong> Vitesse progressive, cadres équilibrés. Retournement mi-parcours, extraction complète sans casse.</p><h3>Maturation et filtration</h3><p><strong>Décantation :</strong> 48h minimum, écumes éliminées. Bullles air remontent, impuretés décantent naturellement.</p><p><strong>Filtration :</strong> Tamis grossier uniquement, préserver pollens. Pas filtration fine destructrice, miel vivant respecté.</p><h3>Conservation optimale</h3><p>Pots verre stérilisés, étiquetage réglementaire. Stockage sec, température stable, cristallisation naturelle normale.</p>',
                'excerpt' => 'Récolte miel : extraction, maturation, conservation. De la ruche au pot, préserver qualité naturelle.',
                'meta_title' => 'Récolte du Miel : Extraction et Conservation | FarmShop',
                'meta_description' => 'Guide récolte miel : extraction, maturation, conservation. Techniques pour miel de qualité optimale.',
                'tags' => ['récolte miel', 'extraction', 'conservation', 'maturation', 'qualité'],
                'reading_time' => 8
            ]
        ];
    }
}
