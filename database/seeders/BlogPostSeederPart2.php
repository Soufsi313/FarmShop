<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\User;

class BlogPostSeederPart2 extends Seeder
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
            // Jardinage Bio (6 articles)
            [
                'category' => 'Jardinage Bio',
                'title' => 'Purins et décoctions : 15 recettes naturelles',
                'content' => '<h2>L\'arsenal naturel du jardinier bio</h2><p>Ortie, prêle, consoude, pissenlit... La nature regorge d\'alliés précieux pour fortifier vos cultures sans chimie. Ces préparations ancestrales nourrissent et protègent efficacement.</p><h3>Purins fertilisants</h3><p><strong>Purin d\'ortie :</strong> 1kg d\'orties fraîches dans 10L d\'eau, fermentation 15 jours. Dilution 1/10 pour fertiliser, 1/20 pour traiter pucerons.</p><p><strong>Purin de consoude :</strong> Riche en potasse, idéal tomates et fruits. Même principe, dilution 1/5.</p><h3>Décoctions protectrices</h3><p><strong>Décoction de prêle :</strong> 100g séché dans 1L, bouillir 30min, diluer 1/5. Anti-fongique puissant contre mildiou, oïdium.</p><p><strong>Infusion d\'ail :</strong> 100g gousses hachées, eau bouillante, infuser 12h. Répulsif insectes et escargots.</p>',
                'excerpt' => '15 recettes de purins et décoctions naturelles pour fertiliser et protéger votre jardin bio. Ortie, consoude, prêle...',
                'meta_title' => 'Purins et Décoctions Bio : 15 Recettes Naturelles | FarmShop',
                'meta_description' => '15 recettes de purins et décoctions bio pour votre jardin : ortie, consoude, prêle. Fertilisation et protection naturelles.',
                'tags' => ['jardinage bio', 'purins', 'décoctions', 'naturel', 'fertilisant'],
                'reading_time' => 8
            ],
            [
                'category' => 'Jardinage Bio',
                'title' => 'Paillis : 10 matériaux pour chaque usage',
                'content' => '<h2>Le paillis, allié incontournable du jardin</h2><p>Économie d\'eau, limitation des adventices, protection du sol : le paillage transforme votre jardinage. Chaque matériau apporte ses spécificités selon vos cultures.</p><h3>Paillis organiques décoratifs</h3><p><strong>Copeaux de bois :</strong> Durables, esthétiques, parfaits massifs et allées. Évitez près légumes (faim d\'azote).</p><p><strong>Paille :</strong> Classique potager, excellente isolation. Ideal fraisiers, tomates, courgettes.</p><h3>Paillis nutritifs</h3><p><strong>Tonte de gazon :</strong> Gratuite, se décompose rapidement. Sécher avant usage, couche fine.</p><p><strong>Feuilles mortes :</strong> Broyées, excellent humus. Parfait vivaces et arbustes.</p><h3>Paillis minéraux</h3><p><strong>Ardoise pilée :</strong> Décorative, durable, régule température. Idéale plantes méditerranéennes.</p>',
                'excerpt' => '10 types de paillis pour chaque usage au jardin : copeaux, paille, tonte, feuilles. Guide complet du paillage.',
                'meta_title' => 'Paillis : 10 Matériaux pour Chaque Usage au Jardin | FarmShop',
                'meta_description' => 'Guide complet des paillis : 10 matériaux organiques et minéraux pour protéger et nourrir votre jardin naturellement.',
                'tags' => ['paillis', 'paillage', 'jardinage bio', 'protection sol', 'économie eau'],
                'reading_time' => 7
            ],
            [
                'category' => 'Jardinage Bio',
                'title' => 'Rotation des cultures : planification sur 4 ans',
                'content' => '<h2>La rotation, clé de la fertilité naturelle</h2><p>Légumes-feuilles, légumes-fruits, légumes-racines, légumineuses : cette rotation de 4 ans préserve la fertilité du sol et limite naturellement parasites et maladies.</p><h3>Année 1 : Légumineuses</h3><p>Haricots, pois, fèves enrichissent le sol en azote. Préparer avec compost, ameublir profondément. Ces "engrais verts" nourrissent les cultures suivantes.</p><h3>Année 2 : Légumes-feuilles</h3><p>Choux, épinards, laitues profitent de l\'azote fixé. Apports modérés en compost mûr. Binages fréquents, paillage léger.</p><h3>Année 3 : Légumes-fruits</h3><p>Tomates, courgettes, aubergines consomment beaucoup. Amendements organiques copieux, arrosages suivis, tuteurage solide.</p><h3>Année 4 : Légumes-racines</h3><p>Carottes, radis, betteraves nettoient le sol. Éviter fumure fraîche (racines fourchues). Bêchage profond, sol bien drainé.</p>',
                'excerpt' => 'Planification de rotation des cultures sur 4 ans : légumineuses, feuilles, fruits, racines. Fertilité naturelle du sol.',
                'meta_title' => 'Rotation des Cultures : Plan sur 4 Ans pour Votre Potager | FarmShop',
                'meta_description' => 'Guide de rotation des cultures sur 4 ans : légumineuses, légumes-feuilles, fruits, racines. Fertilité naturelle garantie.',
                'tags' => ['rotation cultures', 'planification', 'potager bio', 'fertilité sol', 'légumineuses'],
                'reading_time' => 9
            ],
            [
                'category' => 'Jardinage Bio',
                'title' => 'Auxiliaires du jardin : les attirer et les garder',
                'content' => '<h2>Créer un écosystème protecteur</h2><p>Coccinelles, chrysopes, hérissons, oiseaux : ces alliés naturels régulent parasites et nuisibles plus efficacement que les pesticides. Créons-leur un habitat accueillant !</p><h3>Insectes auxiliaires</h3><p><strong>Coccinelles :</strong> Plantez aneth, fenouil, achillée. Hôtel à insectes avec bambous creux. Une coccinelle dévore 150 pucerons/jour !</p><p><strong>Chrysopes :</strong> Attirées par cosmos, tournesols. Larves voraces de pucerons et cochenilles.</p><h3>Prédateurs plus gros</h3><p><strong>Hérisson :</strong> Tas de branches, passage sous clôture. Dévore limaces, escargots, insectes nuisibles.</p><p><strong>Oiseaux :</strong> Nichoirs, points d\'eau, haies diversifiées. Mésanges consomment 500 chenilles/jour en période nidification.</p><h3>Aménagements favorables</h3><p>Haies mellifères, prairie fleurie, mare, pierriers : diversité des habitats = équilibre naturel garanti.</p>',
                'excerpt' => 'Comment attirer auxiliaires au jardin : coccinelles, chrysopes, hérissons, oiseaux. Régulation naturelle des parasites.',
                'meta_title' => 'Auxiliaires du Jardin : Attirer vos Alliés Naturels | FarmShop',
                'meta_description' => 'Guide pour attirer auxiliaires au jardin : coccinelles, chrysopes, hérissons, oiseaux. Protection naturelle contre parasites.',
                'tags' => ['auxiliaires', 'coccinelles', 'biodiversité', 'protection naturelle', 'écosystème'],
                'reading_time' => 8
            ],
            [
                'category' => 'Jardinage Bio',
                'title' => 'Engrais verts : 8 espèces pour chaque saison',
                'content' => '<h2>Nourrir le sol naturellement</h2><p>Moutarde, phacélie, seigle, trèfle : les engrais verts enrichissent, ameublissent et protègent le sol. Semés entre deux cultures, ils transforment votre terre.</p><h3>Engrais verts d\'automne</h3><p><strong>Seigle :</strong> Rustique, améliore structure argileuse. Semis septembre-octobre, fauchage floraison.</p><p><strong>Vesce :</strong> Légumineuse fixatrice d\'azote. Associer à seigle pour équilibre C/N parfait.</p><h3>Engrais verts de printemps</h3><p><strong>Moutarde :</strong> Croissance rapide, nettoyage sol. Enfouir avant grenaison. Éviter avant crucifères.</p><p><strong>Phacélie :</strong> Universelle, mellifère, améliore tous sols. Fleurs magnifiques attirent pollinisateurs.</p><h3>Engrais verts d\'été</h3><p><strong>Sarrasin :</strong> Supporte sécheresse, mobilise phosphore. Floraison décorative, graines comestibles.</p><p><strong>Tournesol :</strong> Décompacte, remonte éléments profonds. Tiges creuses = mulch hivernal.</p>',
                'excerpt' => '8 engrais verts pour chaque saison : moutarde, phacélie, seigle, trèfle. Enrichissement naturel du sol.',
                'meta_title' => 'Engrais Verts : 8 Espèces pour Chaque Saison | FarmShop',
                'meta_description' => 'Guide des engrais verts par saison : moutarde, phacélie, seigle, trèfle. Enrichissement et amélioration naturelle du sol.',
                'tags' => ['engrais verts', 'moutarde', 'phacélie', 'amélioration sol', 'fertilité'],
                'reading_time' => 7
            ],
            [
                'category' => 'Jardinage Bio',
                'title' => 'Compostage : réussir en 6 étapes simples',
                'content' => '<h2>Transformer déchets en or noir</h2><p>Épluchures, tontes, feuilles mortes deviennent compost riche en 6 mois. Cette transformation naturelle nourrit votre jardin tout en réduisant vos déchets de 30%.</p><h3>Étape 1 : Choisir l\'emplacement</h3><p>Mi-ombre, à l\'abri du vent, sur terre nue. Accès facile avec brouette. Bac de 1m³ minimum ou simple tas pour grands jardins.</p><h3>Étape 2 : Équilibrer matières</h3><p><strong>Matières azotées :</strong> Épluchures, tontes fraîches, marc de café (1/3)</p><p><strong>Matières carbonées :</strong> Feuilles sèches, branches broyées, carton (2/3)</p><h3>Étape 3 : Stratifier et mélanger</h3><p>Alterner couches de 20cm, mélanger régulièrement. Aérer avec fourche tous les 15 jours première fois.</p><h3>Étape 4 : Surveiller humidité</h3><p>Consistance éponge essorée. Arroser si sec, ajouter matières sèches si trop humide.</p><h3>Étapes 5-6 : Patience et tamisage</h3><p>Maturation 6-12 mois. Compost prêt = brun, grumeleux, odeur de sous-bois. Tamiser avant usage.</p>',
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
                'content' => '<h2>Choisir ses poules pour optimiser la ponte</h2><p>Entre 250 et 320 œufs par an : certaines races cumulent production et rusticité. Découvrez les championnes de la ponte pour un poulailler familial productif.</p><h3>Les records de ponte</h3><p><strong>ISA Brown :</strong> Hybride commercial, 320 œufs/an, ponte précoce 18 semaines. Docile, adaptable, œufs roux calibre moyen.</p><p><strong>Leghorn blanche :</strong> Race pure, 280 œufs blancs/an. Nerveuse mais excellente pondeuse, résiste chaleur.</p><h3>Rusticité et production</h3><p><strong>Sussex herminée :</strong> 250 œufs crème/an, très rustique. Chair excellente, couvaison naturelle possible.</p><p><strong>Rhode Island Red :</strong> 270 œufs roux/an, résiste froid. Tempérament calme, idéale débutants.</p><h3>Races d\'exception</h3><p><strong>Marans :</strong> 200 œufs "chocolat" extra-roux/an. Moins productive mais œufs recherchés.</p><p><strong>Harco :</strong> Hybride récent, 290 œufs/an, plumage noir à camail doré. Robuste et productive.</p>',
                'excerpt' => '10 races de poules pondeuses les plus productives : ISA Brown, Leghorn, Sussex. Guide des championnes de la ponte.',
                'meta_title' => 'Poules Pondeuses : 10 Races les Plus Productives | FarmShop',
                'meta_description' => 'Top 10 races poules pondeuses : ISA Brown, Leghorn, Sussex, Rhode Island. Production, rusticité et caractéristiques.',
                'tags' => ['poules pondeuses', 'races poules', 'ponte', 'œufs', 'productivité'],
                'reading_time' => 7
            ],
            [
                'category' => 'Animaux de Basse-cour',
                'title' => 'Construire un poulailler : plans et matériaux',
                'content' => '<h2>Concevoir l\'habitat idéal</h2><p>1m² au sol + 10m² parcours par poule : respecter ces proportions garantit bien-être et productivité. Plans détaillés pour autoconstruction économique.</p><h3>Dimensions et conception</h3><p><strong>Poulailler 6 poules :</strong> 2x3m au sol, hauteur 1,8m. Prédire extension future, prévoir isolation région froide.</p><p><strong>Perchoirs :</strong> 25cm/poule, section 4x4cm, hauteur 50cm. Démontables pour nettoyage.</p><h3>Matériaux recommandés</h3><p><strong>Ossature :</strong> Bois traité classe 3, visserie inox. Éviter aggloméré (humidité).</p><p><strong>Couverture :</strong> Bac acier isolé ou ardoises. Pente 30% minimum évacuation eaux.</p><h3>Aménagements essentiels</h3><p><strong>Pondoirs :</strong> 30x30x30cm, 1 pour 4 poules. Litière paille, ramasse-œufs extérieur.</p><p><strong>Parcours :</strong> Grillage maille 25mm enterré 30cm. Filet anti-rapaces hauteur 2m.</p><h3>Ventilation et éclairage</h3><p>Ouvertures hautes courants d\'air, fenêtre sud orientation. Trappe automatique sécurisée.</p>',
                'excerpt' => 'Plans détaillés pour construire poulailler : dimensions, matériaux, aménagements. Guide complet autoconstruction.',
                'meta_title' => 'Construire un Poulailler : Plans et Matériaux | FarmShop',
                'meta_description' => 'Guide construction poulailler : plans détaillés, matériaux, dimensions. Habitat idéal pour poules pondeuses.',
                'tags' => ['construction poulailler', 'plans poulailler', 'matériaux', 'aménagement', 'basse-cour'],
                'reading_time' => 9
            ],
            [
                'category' => 'Animaux de Basse-cour',
                'title' => 'Alimentation poules : ration équilibrée maison',
                'content' => '<h2>Nourrir naturellement ses poules</h2><p>120g/jour/poule d\'un mélange équilibré : céréales (70%), protéines (20%), minéraux (10%). Recette économique et nutritive pour poules en pleine forme.</p><h3>Base céréalière (70%)</h3><p><strong>Blé :</strong> 40% du mélange, énergie principale. Tremper 12h améliore digestibilité.</p><p><strong>Maïs concassé :</strong> 30%, apport énergétique hivernal. Éviter excès (graisse).</p><h3>Apports protéiques (20%)</h3><p><strong>Tourteau de soja :</strong> 15%, protéines végétales équilibrées. Alternative : tourteau de tournesol.</p><p><strong>Vers de terre :</strong> 5% frais ou séchés, protéines animales naturelles.</p><h3>Compléments minéraux (10%)</h3><p><strong>Coquilles d\'huîtres :</strong> Calcium pour coquilles solides, libre service.</p><p><strong>Gravier :</strong> Aide digestion, stockage gavage séparé.</p><h3>Verdure et compléments</h3><p>Herbe fraîche, déchets légumes, orties séchées. Éviter pommes de terre crues, avocat, chocolat (toxiques).</p>',
                'excerpt' => 'Ration équilibrée maison pour poules : céréales, protéines, minéraux. Recette économique et nutritive.',
                'meta_title' => 'Alimentation Poules : Ration Équilibrée Maison | FarmShop',
                'meta_description' => 'Guide alimentation poules : ration équilibrée, céréales, protéines, minéraux. Nutrition naturelle et économique.',
                'tags' => ['alimentation poules', 'ration', 'céréales', 'protéines', 'nutrition'],
                'reading_time' => 6
            ],
            [
                'category' => 'Animaux de Basse-cour',
                'title' => 'Canards de Barbarie : élevage et avantages',
                'content' => '<h2>L\'alternative rustique aux poules</h2><p>Moins bruyants que les canards classiques, les Barbarie excellent en chair et pondent 120 œufs/an. Leur robustesse et facilité d\'élevage séduisent de plus en plus d\'éleveurs familiaux.</p><h3>Caractéristiques remarquables</h3><p><strong>Silence appréciable :</strong> Chuintements discrets vs cancanements. Idéal voisinage proche.</p><p><strong>Chair savoureuse :</strong> Moins grasse que canard classique, goût délicat. Mâles 4-5kg, femelles 2,5kg.</p><h3>Installation et logement</h3><p><strong>Abri simple :</strong> Cabane 2m² pour 6 canards, litière paille épaisse. Résistent froid mais craignent humidité.</p><p><strong>Parcours :</strong> 20m² minimum/sujet, accès point d\'eau apprécié mais non indispensable.</p><h3>Reproduction naturelle</h3><p><strong>Ponte :</strong> Mars à octobre, 120 œufs/an pesant 70g. Excellentes couveuses 35 jours incubation.</p><p><strong>Élevage canetons :</strong> Autonomes dès éclosion, croissance rapide. Abattage optimal 10-12 semaines.</p><h3>Avantages économiques</h3><p>Consommation moindre que poules, valorisation déchets verts excellente. Rentabilité supérieure.</p>',
                'excerpt' => 'Élevage canards de Barbarie : silencieux, rustiques, chair savoureuse. Alternative intéressante aux poules.',
                'meta_title' => 'Canards de Barbarie : Élevage et Avantages | FarmShop',
                'meta_description' => 'Guide élevage canards Barbarie : caractéristiques, installation, reproduction. Alternative rustique aux poules.',
                'tags' => ['canards barbarie', 'élevage canards', 'basse-cour', 'chair', 'silencieux'],
                'reading_time' => 8
            ],
            [
                'category' => 'Animaux de Basse-cour',
                'title' => 'Prévenir maladies : santé de la basse-cour',
                'content' => '<h2>Prévention = meilleure médecine</h2><p>Observation quotidienne, hygiène rigoureuse, prophylaxie adaptée : ces gestes simples préservent la santé de vos volailles mieux que tous les traitements curatifs.</p><h3>Signes d\'alerte à surveiller</h3><p><strong>Comportement :</strong> Isolement, abattement, baisse ponte subite. Poule qui reste perchée en journée = urgence.</p><p><strong>Physique :</strong> Plumes ébouriffées, écoulements nez/yeux, diarrhée colorée. Palpation jabot vide le matin.</p><h3>Hygiène préventive</h3><p><strong>Nettoyage :</strong> Litière changée chaque semaine, désinfection mensuelle poulailler. Eau propre quotidiennement.</p><p><strong>Vide sanitaire :</strong> 15 jours entre lots, nettoyage intégral avec chaux vive.</p><h3>Prophylaxie naturelle</h3><p><strong>Vinaigre de cidre :</strong> 1 cuillère/litre d\'eau, 3 jours/mois. Acidifie tube digestif, limite pathogènes.</p><p><strong>Ail frais :</strong> Vermifuge naturel, stimule immunité. 1 gousse hachée/10 poules dans pâtée.</p><h3>Quarantaine obligatoire</h3><p>Tout nouvel arrivant isolé 15 jours minimum avant intégration. Évite contamination cheptel.</p>',
                'excerpt' => 'Prévenir maladies basse-cour : observation, hygiène, prophylaxie naturelle. Santé volailles par la prévention.',
                'meta_title' => 'Prévenir Maladies : Santé de la Basse-cour | FarmShop',
                'meta_description' => 'Guide prévention maladies volailles : observation, hygiène, prophylaxie. Santé basse-cour par prévention.',
                'tags' => ['santé volailles', 'prévention maladies', 'hygiène', 'prophylaxie', 'observation'],
                'reading_time' => 7
            ],

            // Apiculture (4 articles)
            [
                'category' => 'Apiculture',
                'title' => 'Débuter en apiculture : matériel indispensable',
                'content' => '<h2>S\'équiper pour ses premières ruches</h2><p>Ruche, enfumoir, lève-cadres, combinaison : l\'investissement initial représente 300-500€ par ruche. Guide d\'achat pour débuter sereinement dans l\'aventure apicole.</p><h3>La ruche et ses éléments</h3><p><strong>Corps de ruche :</strong> Dadant 10 cadres recommandée débutants. Bois non traité, assemblage vissé. Prévoir plateau, toit, grille reine.</p><p><strong>Cadres :</strong> Filés inox, cire gaufrée bio. Jeu complet : 10 corps + 9 hausse minimum par ruche.</p><h3>Équipement de protection</h3><p><strong>Combinaison :</strong> Tissu épais, voile intégré, fermetures étanches. Gants cuir souple débutants.</p><p><strong>Chaussures :</strong> Montantes, lisses (éviter velcro). Surchaussures plastique économiques.</p><h3>Outils de travail</h3><p><strong>Enfumoir :</strong> Grand modèle, combustible naturel (carton, aiguilles pin). Allumage facile, fumée froide.</p><p><strong>Lève-cadres :</strong> Modèle coudé polyvalent, acier inox. Brosse soies naturelles inspection.</p><h3>Matériel extraction</h3><p>Maturateur 50kg, désoperculateur manuel suffisent production familiale. Extracteur tangentiel 3 cadres.</p>',
                'excerpt' => 'Matériel indispensable débuter apiculture : ruche, combinaison, enfumoir, lève-cadres. Guide équipement 300-500€.',
                'meta_title' => 'Débuter Apiculture : Matériel Indispensable | FarmShop',
                'meta_description' => 'Guide matériel apiculture débutant : ruche, protection, outils. Équipement indispensable pour débuter.',
                'tags' => ['apiculture débutant', 'matériel apiculture', 'ruche', 'équipement', 'protection'],
                'reading_time' => 8
            ],
            [
                'category' => 'Apiculture',
                'title' => 'Calendrier apicole : interventions mois par mois',
                'content' => '<h2>Rythmer ses visites selon les saisons</h2><p>L\'abeille suit les saisons : hibernation, développement printanier, miellées, préparation hivernage. Adapter ses interventions au cycle naturel optimise productions et survie colonies.</p><h3>Hiver (décembre-février)</h3><p><strong>Décembre :</strong> Visite exterieures uniquement. Vérifier étanchéité, dégager entrée neige. Pas d\'ouverture ruche.</p><p><strong>Janvier :</strong> Commande matériel saison. Préparation cadres, entretien outils. Surveillance prédateurs (pic-vert).</p><p><strong>Février :</strong> Première visite rapide jour doux (+15°C). Évaluation provisions, mortalité éventuelle.</p><h3>Printemps (mars-mai)</h3><p><strong>Mars :</strong> Nettoyage plateau, changement cadres noircis. Stimulation sirop léger si nécessaire.</p><p><strong>Avril :</strong> Pose hausses, extension couvain. Prévention essaimage, marquage reines.</p><p><strong>Mai :</strong> Surveillance essaimage, captures, divisions. Première récolte miel acacia.</p><h3>Été-Automne (juin-novembre)</h3><p><strong>Juin-juillet :</strong> Récoltes principales, extraction. Protection canicule, abreuvement.</p><p><strong>Août-septembre :</strong> Traitement varroa, nourrissement hivernal. Réduction entrées.</p><p><strong>Octobre-novembre :</strong> Préparation hivernage, isolation ruches régions froides.</p>',
                'excerpt' => 'Calendrier apicole mois par mois : interventions hivernales, printanières, estivales. Rythmer visites selon saisons.',
                'meta_title' => 'Calendrier Apicole : Interventions Mois par Mois | FarmShop',
                'meta_description' => 'Calendrier apicole complet : interventions par saison, visites, récoltes. Guide mois par mois.',
                'tags' => ['calendrier apicole', 'interventions ruche', 'saisons', 'visites', 'planning'],
                'reading_time' => 9
            ],
            [
                'category' => 'Apiculture',
                'title' => 'Varroa destructor : traitement et prévention',
                'content' => '<h2>Lutter contre le fléau des ruches</h2><p>Varroa destructor décime colonies mondiales depuis 30 ans. Traitement intégré biologique et surveillance régulière permettent cohabitation contrôlée avec ce parasite.</p><h3>Cycle et dégâts du varroa</h3><p><strong>Reproduction :</strong> Femelle pond dans cellule operculée, progéniture se nourrit hémolymphe nymphe. Affaiblissement, virus transmis.</p><p><strong>Symptômes :</strong> Abeilles déformées, ailes atrophiées, couvain troué. Chute naturelle +50 varroas/jour = urgence.</p><h3>Méthodes biologiques</h3><p><strong>Acide formique :</strong> 65% concentration, diffuseurs 3 semaines. Efficace +90%, période hors miellée.</p><p><strong>Thymol :</strong> Thymovar, Apiguard selon température. Traitement doux, goût miel préservé.</p><h3>Techniques mécaniques</h3><p><strong>Cadre piège :</strong> Cire mâle sacrifié, retrait couvain operculé. Réduction 30% population parasite.</p><p><strong>Interruption ponte :</strong> Encagement reine 25 jours, élimination varroas reproducteurs.</p><h3>Surveillance annuelle</h3><p>Comptage naturel mensuel, test sucre glace. Traitement si >3% infestation printemps, >10% automne.</p>',
                'excerpt' => 'Varroa destructor : traitements biologiques, surveillance, prévention. Lutter contre le parasite des abeilles.',
                'meta_title' => 'Varroa Destructor : Traitement et Prévention | FarmShop',
                'meta_description' => 'Guide traitement varroa : méthodes biologiques, surveillance, prévention. Lutter contre le parasite des ruches.',
                'tags' => ['varroa', 'traitement varroa', 'parasite abeilles', 'apiculture bio', 'surveillance'],
                'reading_time' => 8
            ],
            [
                'category' => 'Apiculture',
                'title' => 'Plantes mellifères : jardin des abeilles',
                'content' => '<h2>Créer un buffet permanent</h2><p>Succession florale mars-octobre garantit ressources constantes. Planter mellifères compense raréfaction flore sauvage et soutient colonies en disette.</p><h3>Floraison précoce (mars-mai)</h3><p><strong>Arbres :</strong> Saule marsault, merisier, érable champêtre. Pollen abondant relance pontes printanières.</p><p><strong>Arbustes :</strong> Groseillier, cassissier, aubépine. Floraisons généreuses, facilement cultivables.</p><h3>Miellées principales (juin-juillet)</h3><p><strong>Tilleul :</strong> Miellée courte mais intense. Miel clair délicat, 15-20kg/ruche en bonne année.</p><p><strong>Châtaignier :</strong> Miel ambré corsé, récolte juillet. Floraison longue, sécurise production.</p><h3>Soutien estival (août-septembre)</h3><p><strong>Tournesol :</strong> Pollen protéiné excellent. Implanter variétés échelonnées allonger période.</p><p><strong>Sarrasin :</strong> Mellifère exceptionnel, miel typé. Culture possible 60 jours.</p><h3>Vivaces compagnes</h3><p><strong>Phacélie :</strong> Semis printemps, floraison 6 semaines après. Engrais vert mellifère idéal.</p><p><strong>Lavande :</strong> Floraison longue, miel recherché. Résiste sécheresse, vivace productive.</p>',
                'excerpt' => 'Plantes mellifères pour jardin abeilles : succession florale mars-octobre. Soutenir colonies par diversité florale.',
                'meta_title' => 'Plantes Mellifères : Jardin des Abeilles | FarmShop',
                'meta_description' => 'Guide plantes mellifères : succession florale, arbres, vivaces. Créer jardin pour soutenir colonies abeilles.',
                'tags' => ['plantes mellifères', 'jardin abeilles', 'floraison', 'nectar', 'pollen'],
                'reading_time' => 7
            ],

            // Élevage Responsable (4 articles)
            [
                'category' => 'Élevage Responsable',
                'title' => 'Bien-être animal : 5 libertés fondamentales',
                'content' => '<h2>Éthique et responsabilité en élevage</h2><p>Absence de faim, inconfort, douleur, peur, possibilité d\'expression comportements naturels : ces 5 libertés guident l\'élevage respectueux du bien-être animal.</p><h3>1. Liberté physiologique</h3><p><strong>Absence faim/soif :</strong> Accès permanent eau propre, ration adaptée besoins. Surveillance état corporel, adaptation saisonnière.</p><p><strong>Nutrition équilibrée :</strong> Qualité fourrage, complémentation ciblée. Éviter carences et excès perturbant métabolisme.</p><h3>2. Liberté environnementale</h3><p><strong>Confort thermique :</strong> Abris adaptés climat, ventilation naturelle. Zones ombragées été, protection vent/pluie hiver.</p><p><strong>Espace suffisant :</strong> Densité raisonnable évitant stress social. Aires repos, alimentation, abreuvement distinctes.</p><h3>3. Liberté sanitaire</h3><p><strong>Prévention :</strong> Prophylaxie adaptée, observation quotidienne. Environnement sain limitant pathogènes.</p><p><strong>Soins :</strong> Intervention vétérinaire rapide, analgésie systématique actes douloureux.</p><h3>4-5. Libertés comportementales</h3><p><strong>Réduction stress :</strong> Manipulation douce, routines rassurantes. Éviter bruits, mouvements brusques.</p><p><strong>Expression naturelle :</strong> Fouissement porcs, perchage volailles, socialisation respectée.</p>',
                'excerpt' => '5 libertés fondamentales bien-être animal : absence faim, inconfort, douleur, peur, expression comportements naturels.',
                'meta_title' => 'Bien-être Animal : 5 Libertés Fondamentales | FarmShop',
                'meta_description' => 'Guide bien-être animal : 5 libertés fondamentales pour élevage responsable et éthique.',
                'tags' => ['bien-être animal', '5 libertés', 'éthique', 'élevage responsable', 'confort'],
                'reading_time' => 8
            ],
            [
                'category' => 'Élevage Responsable',
                'title' => 'Pâturage tournant : optimiser prairies',
                'content' => '<h2>Gérer durablement ses herbages</h2><p>Rotation 21-28 jours préserve prairie et optimise nutrition. Cette méthode ancestrale augmente production fourragère de 30% tout en améliorant biodiversité.</p><h3>Principe du pâturage tournant</h3><p><strong>Repos végétal :</strong> 21 jours minimum repousse optimale graminées. Période variable selon saison, météo, espèces.</p><p><strong>Exploitation idéale :</strong> Entrée 15-20cm hauteur, sortie 5cm minimum. Préserve points végétatifs, évite surpâturage.</p><h3>Découpage parcelles</h3><p><strong>Calcul surface :</strong> 0,5 ha/UGB pâturage permanent, 0,3 ha système intensif. Diviser en 6-10 parcelles selon rotation.</p><p><strong>Aménagement :</strong> Clôtures mobiles, points d\'eau multiples. Chemin accès évitant piétinement.</p><h3>Conduite saisonnière</h3><p><strong>Printemps :</strong> Entrée tardive sol portant, croissance explosive. Rotation accélérée 14-18 jours.</p><p><strong>Été :</strong> Période critique, économiser pousse. Complémentation si nécessaire, irrigation localisée.</p><p><strong>Automne :</strong> Derniers passages, préparation hivernage. Éviter tassement sol humide.</p><h3>Bénéfices mesurés</h3><p>+30% production, amélioration flore, séquestration carbone. Économie intrants, autonomie fourragère renforcée.</p>',
                'excerpt' => 'Pâturage tournant : rotation 21-28 jours optimise prairies. +30% production, biodiversité préservée.',
                'meta_title' => 'Pâturage Tournant : Optimiser vos Prairies | FarmShop',
                'meta_description' => 'Guide pâturage tournant : rotation, découpage parcelles, conduite saisonnière. Optimisation prairie durable.',
                'tags' => ['pâturage tournant', 'prairie', 'rotation', 'fourrage', 'gestion durable'],
                'reading_time' => 9
            ],
            [
                'category' => 'Élevage Responsable',
                'title' => 'Chèvres laitières : installation et conduite',
                'content' => '<h2>Autonomie laitière familiale</h2><p>2-3 chèvres suffisent besoins familiaux : 4-6 litres/jour 10 mois/an. Installation simple, conduite accessible, transformation artisanale valorise production.</p><h3>Choix des races</h3><p><strong>Alpine :</strong> 800-1000L/lactation, rustique, bonne adaptatrice. Lait équilibré transformation fromagère.</p><p><strong>Saanen :</strong> Production record 1200L, lait moins gras. Sensible chaleur, préférer régions tempérées.</p><p><strong>Poitevine :</strong> Race locale rustique, 600L production. Excellente mère, bonne longévité.</p><h3>Logement et parcours</h3><p><strong>Chèvrerie :</strong> 2m²/chèvre, aire couchage paillée, râtelier foin. Ventilation haute, éviter courants d\'air.</p><p><strong>Parcours :</strong> 200m²/chèvre minimum, clôture 1,2m hauteur. Abri météo, point d\'eau permanent.</p><h3>Alimentation équilibrée</h3><p><strong>Base fourragère :</strong> Foin qualité ad libitum, 2kg matière sèche/jour. Pâturage 8h quotidiennes saison.</p><p><strong>Concentrés :</strong> 200-400g/litre produit selon fourrage. Orge, avoine, tourteau soja équilibrent ration.</p><h3>Traite et hygiène</h3><p>2 traites/jour espacement régulier. Nettoyage mamelle, local propre, filtration lait. Conservation +4°C maximum 48h.</p>',
                'excerpt' => 'Élevage chèvres laitières : 2-3 chèvres pour autonomie familiale. Installation, conduite, traite hygiénique.',
                'meta_title' => 'Chèvres Laitières : Installation et Conduite | FarmShop',
                'meta_description' => 'Guide élevage chèvres laitières : races, logement, alimentation, traite. Autonomie laitière familiale.',
                'tags' => ['chèvres laitières', 'élevage chèvres', 'traite', 'autonomie', 'lait'],
                'reading_time' => 8
            ],
            [
                'category' => 'Élevage Responsable',
                'title' => 'Moutons : gestion du troupeau familial',
                'content' => '<h2>Entretien naturel des espaces</h2><p>10-15 brebis entretiennent 2-3 hectares tout en produisant agneaux, laine, fumier. Élevage extensif valorise terrains difficiles et crée liens privilégiés.</p><h3>Races adaptées petit élevage</h3><p><strong>Ouessant :</strong> Race naine 15-20kg, tondeuse écologique parfaite. Rustique, familière, peu productive mais attachante.</p><p><strong>Thones et Marthod :</strong> Montagnarde 60kg, prolificité correcte. Adaptée terrains pentus, laine recherchée.</p><p><strong>Caussenarde :</strong> 70kg, excellente mère, terrain sec. Résiste parasites, agnelage facile.</p><h3>Conduite du troupeau</h3><p><strong>Reproduction :</strong> Lutte automne, agnelage fin hiver. 1 bélier pour 30 brebis, rotation évite consanguinité.</p><p><strong>Agnelage :</strong> Surveillance rapprochée, aide si nécessaire. Désinfection cordon, adoption forcée orphelins.</p><h3>Alimentation extensive</h3><p><strong>Pâturage :</strong> Valorise friches, sous-bois, coteaux. Débroussaillage naturel, entretien paysager gratuit.</p><p><strong>Complémentation :</strong> Foin hiver, concentrés fin gestation/début lactation. Minéraux spécifiques ovins obligatoires.</p><h3>Prévention sanitaire</h3><p>Vaccination clostridioses, vermifugation raisonnée. Parage onglons, surveillance boiteries. Quarantaine nouveaux sujets.</p>',
                'excerpt' => 'Gestion troupeau moutons familial : 10-15 brebis entretiennent 2-3 hectares. Races, conduite, pâturage extensif.',
                'meta_title' => 'Moutons : Gestion du Troupeau Familial | FarmShop',
                'meta_description' => 'Guide élevage moutons familial : races, conduite, pâturage. Entretien naturel espaces verts.',
                'tags' => ['élevage moutons', 'troupeau familial', 'pâturage', 'races ovines', 'entretien naturel'],
                'reading_time' => 9
            ]
        ];
    }
}
