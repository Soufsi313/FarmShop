<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\User;

class BlogPostSeederPart4 extends Seeder
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
            // Agriculture Durable (5 articles)
            [
                'category' => 'Agriculture Durable',
                'title' => 'Agroécologie : principes et mise en pratique',
                'content' => '<h2>Révolution douce des campagnes</h2><p>Biodiversité fonctionnelle, cycles fermés, autonomie énergétique : l\'agroécologie réconcilie productivité et respect environnemental. Transition progressive vers système résilient.</p><h3>Fondements agroécologiques</h3><p><strong>Diversité cultivée :</strong> Rotations longues, associations végétales, haies bocagères. Imiter écosystèmes naturels, stabilité biologique renforcée.</p><p><strong>Sol vivant :</strong> Couverts permanents, apports organiques, limitation travail. Biologie tellurique activée, fertilité auto-entretenue.</p><h3>Pratiques concrètes</h3><p><strong>Associations bénéfiques :</strong> Légumineuses-graminées, plantes compagnes, guildes fruitières. Synergies nutritionnelles, protection mutuelle parasites.</p><p><strong>Régulation naturelle :</strong> Auxiliaires indigènes, prédation équilibrée, résistance variétale. Réduire intrants 70% possible progressive.</p><h3>Transition accompagnée</h3><p><strong>Phases adaptation :</strong> Conversion progressive 3-5 ans, formation continue nécessaire. Accompagnement technique, économique indispensable.</p><p><strong>Résultats mesurés :</strong> Coûts production -40%, biodiversité +60%, stockage carbone doublé. Rentabilité long terme supérieure.</p><h3>Défis et opportunités</h3><p>Période adaptation délicate, marchés valorisation. Soutien public, consommation responsable encouragent transition nécessaire.</p>',
                'excerpt' => 'Agroécologie : principes biodiversité, sol vivant, autonomie. Transition vers agriculture productive et respectueuse.',
                'meta_title' => 'Agroécologie : Principes et Mise en Pratique | FarmShop',
                'meta_description' => 'Guide agroécologie : principes, pratiques concrètes, transition. Agriculture durable productive et écologique.',
                'tags' => ['agroécologie', 'agriculture durable', 'biodiversité', 'sol vivant', 'transition'],
                'reading_time' => 11
            ],
            [
                'category' => 'Agriculture Durable',
                'title' => 'Permaculture urbaine : nourrir la ville',
                'content' => '<h2>Révolution verte citadine</h2><p>Toits végétalisés, jardins verticaux, micro-fermes urbaines : la permaculture investit espaces urbains. Produire local, réduire empreinte, créer liens sociaux nouveaux.</p><h3>Espaces disponibles</h3><p><strong>Toitures exploitables :</strong> Terrasses, toits plats, structures renforcées. Substrats légers, drainage étudié, exposition optimisée.</p><p><strong>Façades productives :</strong> Murs végétaux, treillis grimpants, systèmes hydroponiques. Isolation thermique, esthétique, production combinées.</p><h3>Techniques adaptées</h3><p><strong>Culture verticale :</strong> Tours légumes, pyramides aromatiques, étagements optimisés. Rendement/m² multiplié, accessibilité préservée.</p><p><strong>Hydroponie simplifiée :</strong> Substrats inertes, solutions nutritives, cycles courts. Croissance accélérée, économie eau 90%.</p><h3>Productions spécialisées</h3><p><strong>Micro-pousses :</strong> Germination contrôlée, récolte 7-14 jours. Valeur nutritionnelle concentrée, marchés haut gamme.</p><p><strong>Champignons urbains :</strong> Caves, parkings, espaces sombres. Pleurotes, shiitakés sur substrats recyclés urbains.</p><h3>Impact social</h3><p>Jardins partagés, éducation environnementale, insertion professionnelle. Lien social recréé, alimentation relocalisée progressivement.</p>',
                'excerpt' => 'Permaculture urbaine : toits, façades, micro-fermes. Produire en ville, réduire empreinte, créer liens sociaux.',
                'meta_title' => 'Permaculture Urbaine : Nourrir la Ville | FarmShop',
                'meta_description' => 'Guide permaculture urbaine : techniques, espaces, productions. Agriculture en ville durable.',
                'tags' => ['permaculture urbaine', 'agriculture urbaine', 'toits végétalisés', 'jardins verticaux', 'ville'],
                'reading_time' => 9
            ],
            [
                'category' => 'Agriculture Durable',
                'title' => 'Énergies renouvelables : autonomie de la ferme',
                'content' => '<h2>Ferme énergie positive</h2><p>Solaire photovoltaïque, éolien domestique, méthanisation, géothermie : la ferme devient productrice énergie. Autonomie énergétique possible, revenu complémentaire généré.</p><h3>Solaire agricole</h3><p><strong>Panneaux toitures :</strong> Hangars, stabulations, serres photovoltaïques. Double usage espace, amortissement 8-12 ans selon région.</p><p><strong>Centrale sol :</strong> Terres marginales, agrivoltaïsme naissant. Élevage sous panneaux, cultures d\'ombre possibles.</p><h3>Éolien adapté</h3><p><strong>Petit éolien :</strong> 1-10kW, sites ventés, autoconsommation. Technologie mature, maintenance réduite modèles récents.</p><p><strong>Éolien participatif :</strong> Regroupement exploitants, investissement collectif. Rentabilité améliorée, acceptation locale facilitée.</p><h3>Méthanisation fermière</h3><p><strong>Substrats disponibles :</strong> Fumiers, résidus cultures, déchets organiques. Valorisation totale, digestat fertilisant produit.</p><p><strong>Dimensionnement :</strong> 100-500kW électrique, cogénération chaleur. Revenu 60-80k€/an selon substrats disponibles.</p><h3>Géothermie basse température</h3><p>Chauffage serres, séchage grains, eau chaude. Investissement élevé, rentabilité long terme. Pompes chaleur évolutives.</p>',
                'excerpt' => 'Énergies renouvelables ferme : solaire, éolien, méthanisation, géothermie. Autonomie et revenus complémentaires.',
                'meta_title' => 'Énergies Renouvelables : Autonomie de la Ferme | FarmShop',
                'meta_description' => 'Guide énergies renouvelables agricoles : solaire, éolien, méthanisation. Autonomie énergétique ferme.',
                'tags' => ['énergies renouvelables', 'solaire agricole', 'méthanisation', 'autonomie énergétique', 'ferme'],
                'reading_time' => 10
            ],
            [
                'category' => 'Agriculture Durable',
                'title' => 'Circuits courts : vente directe rentable',
                'content' => '<h2>Rapprocher producteur et consommateur</h2><p>AMAP, marchés locaux, magasins producteurs, vente ferme : les circuits courts valorisent production tout en créant lien social. Stratégies rentabilité, organisation commerciale.</p><h3>Formats de vente directe</h3><p><strong>AMAP :</strong> Contrats saisonniers, production diversifiée, fidélisation clientèle. Sécurité revenu, planification cultures facilitée.</p><p><strong>Marchés locaux :</strong> Contact direct, adaptabilité offre, trésorerie rapide. Animation commerciale, présentation soignée indispensables.</p><h3>Magasins collectifs</h3><p><strong>Magasins producteurs :</strong> Investissement collectif, permanences partagées, gamme élargie. Charges réduites, professionnalisation progressive.</p><p><strong>Drives fermiers :</strong> Commandes internet, points retrait, optimisation tournées. Modernité, praticité clientèle urbaine.</p><h3>Organisation commerciale</h3><p><strong>Logistique :</strong> Stockage, conditionnement, transport optimisé. Coûts masqués nombreux, rentabilité précise calculée.</p><p><strong>Communication :</strong> Réseaux sociaux, newsletters, événements ferme. Storytelling authentique, transparence production valorisée.</p><h3>Rentabilité optimisée</h3><p>Marge brute +200-400%, temps commercial compensé. Diversification produits, transformation artisanale. Fidélisation priorité absolue.</p>',
                'excerpt' => 'Circuits courts rentables : AMAP, marchés, magasins producteurs. Vente directe valorisée, lien social créé.',
                'meta_title' => 'Circuits Courts : Vente Directe Rentable | FarmShop',
                'meta_description' => 'Guide circuits courts : AMAP, marchés locaux, vente directe. Rentabilité et lien producteur-consommateur.',
                'tags' => ['circuits courts', 'vente directe', 'AMAP', 'marchés locaux', 'rentabilité'],
                'reading_time' => 9
            ],
            [
                'category' => 'Agriculture Durable',
                'title' => 'Certification bio : démarches et avantages',
                'content' => '<h2>Label qualité reconnu</h2><p>Cahier charges strict, contrôles annuels, plus-value commerciale : la certification bio sécurise débouchés tout en structurant pratiques. Étapes conversion, bénéfices mesurés.</p><h3>Cahier des charges</h3><p><strong>Interdictions :</strong> Pesticides synthèse, OGM, engrais chimiques. Liste substances autorisées restrictive, traçabilité rigoureuse obligatoire.</p><p><strong>Pratiques imposées :</strong> Rotations diversifiées, bien-être animal renforcé, biodiversité préservée. Contrôles inopinés possibles.</p><h3>Période de conversion</h3><p><strong>Durée :</strong> 2-3 ans selon cultures, contrôles dès première année. Production convertie valorisable partiellement.</p><p><strong>Accompagnement :</strong> Chambres agriculture, organismes certificateurs, groupes producteurs. Formation continue recommandée.</p><h3>Certification et contrôles</h3><p><strong>Organismes agréés :</strong> Ecocert, Certipaq, Bureau Veritas. Choix libre, coûts variables 800-2000€/an.</p><p><strong>Audit annuel :</strong> Inspection culture, élevage, stockage, comptabilité. Non-conformités sanctionnées, certification suspendue possible.</p><h3>Avantages économiques</h3><p><strong>Plus-value :</strong> +30-60% prix conventionnel selon filières. Débouchés sécurisés, contrats pluriannuels fréquents.</p><p><strong>Aides publiques :</strong> Conversion, maintien, investissements. Soutien Europe, État, régions cumulables conditions.</p>',
                'excerpt' => 'Certification bio : cahier charges, conversion, contrôles. Label qualité, plus-value commerciale, débouchés sécurisés.',
                'meta_title' => 'Certification Bio : Démarches et Avantages | FarmShop',
                'meta_description' => 'Guide certification agriculture biologique : démarches, conversion, avantages. Label qualité reconnu.',
                'tags' => ['certification bio', 'agriculture biologique', 'conversion bio', 'label qualité', 'contrôles'],
                'reading_time' => 8
            ],

            // Compostage et Recyclage (3 articles)
            [
                'category' => 'Compostage et Recyclage',
                'title' => 'Lombricompostage : vers de terre au travail',
                'content' => '<h2>Usine de recyclage miniature</h2><p>Eisenia foetida transforme déchets cuisine en lombricompost exceptionnel. Technique urbaine, production continue, engrais liquide bonus. Guide complet installation domestique.</p><h3>Installation lombricomposteur</h3><p><strong>Bacs étagés :</strong> Plastique alimentaire, grillage drainage, robinet récupération. Commercial ou fabrication maison économique.</p><p><strong>Litière démarrage :</strong> Carton humide, terre jardin, première nourriture. Population 500g vers pour famille 4 personnes.</p><h3>Alimentation équilibrée</h3><p><strong>Déchets acceptés :</strong> Épluchures légumes, fruits, marc café, coquilles œufs broyées. Éviter agrumes, ail, oignons en excès.</p><p><strong>Rythme nourrissage :</strong> Petites quantités quotidiennes, enfouissement léger. Vers consomment leur poids/jour conditions optimales.</p><h3>Conditions optimales</h3><p><strong>Humidité :</strong> 75-85%, test poignée égouttée. Arrosage fin si nécessaire, drainage evacuation excès.</p><p><strong>Température :</strong> 15-25°C idéal, intérieur possible. Aération suffisante, éviter putréfaction anaérobie.</p><h3>Récolte et usage</h3><p><strong>Lombricompost :</strong> Récupération 6 mois, texture fine, odeur terre forêt. Rempotage, semis, top-dressing plantes.</p><p><strong>Lombrithé :</strong> Engrais liquide concentré, dilution 1/10. Arrosage fertilisant exceptionnel, croissance stimulée.</p>',
                'excerpt' => 'Lombricompostage domestique : vers Eisenia transforment déchets en compost. Installation, alimentation, récolte.',
                'meta_title' => 'Lombricompostage : Vers de Terre au Travail | FarmShop',
                'meta_description' => 'Guide lombricompostage : installation, vers Eisenia, production compost. Recyclage déchets domestiques.',
                'tags' => ['lombricompostage', 'vers de terre', 'eisenia', 'recyclage', 'compost'],
                'reading_time' => 8
            ],
            [
                'category' => 'Compostage et Recyclage',
                'title' => 'Bokashi : fermentation japonaise',
                'content' => '<h2>Révolution microbiologique</h2><p>Microorganismes efficaces accélèrent décomposition, éliminent odeurs, préservent nutriments. Technique japonaise urbaine, seau hermétique, résultats rapides 15 jours.</p><h3>Principe du bokashi</h3><p><strong>Fermentation lactique :</strong> Microorganismes anaérobies, acidification protective, conservation nutriments. Transformation sans putréfaction possible.</p><p><strong>Son activé :</strong> Support microorganismes, absorption humidité, accélérateur process. Achat ou fabrication maison levures pain.</p><h3>Matériel nécessaire</h3><p><strong>Seau hermétique :</strong> Couvercle étanche, robinet récupération jus. Contenance 15-20L famille moyenne.</p><p><strong>Outil tassement :</strong> Compression anaérobie, élimination air. Presse-purée adapté, contact alimentaire.</p><h3>Processus étape par étape</h3><p><strong>Remplissage :</strong> Déchets couches 3cm, son activé saupoudrage, tassement énergique. Tous déchets acceptés, viandes comprises.</p><p><strong>Fermentation :</strong> 15 jours hermétique, jus évacuation régulière. Odeur aigre-douce normale, moisissures blanches acceptables.</p><h3>Valorisation produits</h3><p><strong>Pré-compost :</strong> Enfouissement jardin, maturation 15 jours terre. Acidité neutralisée, assimilation racinaire facilitée.</p><p><strong>Jus bokashi :</strong> Engrais liquide dilué 1/200, débouchoir canalisations pur. Double usage économique écologique.</p>',
                'excerpt' => 'Bokashi japonais : fermentation microorganismes, déchets transformés 15 jours. Technique urbaine, résultats rapides.',
                'meta_title' => 'Bokashi : Fermentation Japonaise | FarmShop',
                'meta_description' => 'Guide bokashi : fermentation déchets, microorganismes efficaces. Technique japonaise rapide et urbaine.',
                'tags' => ['bokashi', 'fermentation', 'microorganismes', 'japonais', 'déchets'],
                'reading_time' => 7
            ],
            [
                'category' => 'Compostage et Recyclage',
                'title' => 'Compost thermophile : technique accélérée',
                'content' => '<h2>Haute température, décomposition rapide</h2><p>Méthode Berkeley atteint 60-70°C, compost mûr 18 jours. Retournements fréquents, aération forcée, hygiénisation garantie. Technique intensive, résultats spectaculaires.</p><h3>Principe thermophile</h3><p><strong>Montée température :</strong> Fermentation aérobie intensive, dégagement chaleur. Élimination pathogènes, graines adventices détruites.</p><p><strong>Équilibre C/N :</strong> Ratio 30/1 optimal, matières carbonées/azotées. Calcul précis, thermomètre contrôle indispensable.</p><h3>Construction tas</h3><p><strong>Volume minimum :</strong> 1m³ masse critique, isolation thermique. Base drainage, aération passive insuffisante.</p><p><strong>Stratification :</strong> Couches alternées 20cm, brassage homogène. Humidification régulière, consistance éponge essorée.</p><h3>Conduite thermophile</h3><p><strong>Phase 1 :</strong> Montée température 3 jours, pic 65-70°C. Retournement jour 4, réoxygénation complète.</p><p><strong>Phase 2 :</strong> Retournements J7, J10, J14, température décroissante. Surveillance humidité, arrosage si nécessaire.</p><h3>Maturation finale</h3><p><strong>Refroidissement :</strong> Température ambiante jour 18, recolonisation. Test germination cresson, maturité vérifiée.</p><p><strong>Qualité exceptionnelle :</strong> Compost hygiénisé, structure parfaite, richesse nutritive. Usage immédiat possible, stockage longue durée.</p>',
                'excerpt' => 'Compost thermophile Berkeley : 60-70°C, compost mûr 18 jours. Technique accélérée, hygiénisation garantie.',
                'meta_title' => 'Compost Thermophile : Technique Accélérée | FarmShop',
                'meta_description' => 'Guide compost thermophile : méthode Berkeley, haute température. Compostage accéléré 18 jours.',
                'tags' => ['compost thermophile', 'berkeley', 'compostage accéléré', 'haute température', 'hygiénisation'],
                'reading_time' => 8
            ],

            // Biodiversité (3 articles)
            [
                'category' => 'Biodiversité',
                'title' => 'Haies bocagères : corridors de biodiversité',
                'content' => '<h2>Autoroutes du vivant</h2><p>Essences locales, strates végétales, continuité écologique : les haies bocagères structurent paysage et biodiversité. Plantation, entretien, bénéfices écosystémiques mesurés.</p><h3>Fonctions écologiques</h3><p><strong>Corridor biologique :</strong> Circulation faune, pollinisation, dispersion graines. Connexion habitats fragmentés, survie espèces facilitée.</p><p><strong>Régulation climatique :</strong> Brise-vent, ombrage, évapotranspiration. Microclimat tempéré, stress hydrique atténué cultures.</p><h3>Composition optimale</h3><p><strong>Essences locales :</strong> Aubépine, noisetier, prunellier, troène. Adaptation pédoclimatique, résistance parasites indigènes.</p><p><strong>Strates végétales :</strong> Arbres haut-jet, arbustes, herbacées. Diversité niches écologiques, richesse spécifique maximisée.</p><h3>Plantation technique</h3><p><strong>Préparation sol :</strong> Labour bande 2m, amendement organique. Plantation automne, racines nues économiques.</p><p><strong>Densité :</strong> 1 plant/mètre linéaire, quinconce évite concurrence. Paillage protecteur, arrosage première année.</p><h3>Entretien durable</h3><p><strong>Taille rotation :</strong> Sections 50m, cycle 7-10 ans. Préservation nidification, repousse naturelle favorisée.</p><p><strong>Enrichissement :</strong> Plantations complémentaires, invasives contrôlées. Évolution spontanée respectée, intervention minimale.</p>',
                'excerpt' => 'Haies bocagères : corridors biodiversité, essences locales, strates végétales. Plantation, entretien, bénéfices écologiques.',
                'meta_title' => 'Haies Bocagères : Corridors de Biodiversité | FarmShop',
                'meta_description' => 'Guide haies bocagères : plantation, entretien, biodiversité. Corridors écologiques et bénéfices environnementaux.',
                'tags' => ['haies bocagères', 'biodiversité', 'corridors biologiques', 'essences locales', 'écosystème'],
                'reading_time' => 9
            ],
            [
                'category' => 'Biodiversité',
                'title' => 'Mares et points d\'eau : oasis de vie',
                'content' => '<h2>Réveil de la vie aquatique</h2><p>Amphibiens, libellules, oiseaux d\'eau : une mare transforme biodiversité locale. Conception naturelle, équilibre biologique, entretien minimal pour maximum impact écologique.</p><h3>Conception écologique</h3><p><strong>Profils variés :</strong> Zones 20cm-1,5m, berges douces, îlot central. Diversité habitats, colonisation spontanée facilitée.</p><p><strong>Étanchéité naturelle :</strong> Argile compactée, bentonite écologique. Éviter bâches plastique, intégration paysagère réussie.</p><h3>Équilibre biologique</h3><p><strong>Plantes aquatiques :</strong> Immergées oxygénantes, flottantes, émergées berges. Épuration naturelle, refuge faune diversifiée.</p><p><strong>Colonisation :</b> Patience 2-3 ans, équilibre spontané. Éviter poissons rouges, prédation excessive têtards.</p><h3>Faune attendue</h3><p><strong>Amphibiens :</strong> Grenouilles, crapauds, tritons colonisation rapide. Reproduction printanière, spectacle nature garanti.</p><p><strong>Insectes aquatiques :</strong> Libellules, dytiques, notonectes. Prédateurs naturels moustiques, régulation biologique.</p><h3>Entretien minimal</h3><p><strong>Gestion extensive :</strong> Faucardage partiel automne, préservation vases. Éviter vidange complète, écosystème fragile.</p><p><strong>Surveillance qualité :</strong> Éviter eutrophisation, plantes invasives. Intervention légère, équilibre priorité absolue.</p>',
                'excerpt' => 'Mares et points d\'eau : conception écologique, équilibre biologique. Oasis biodiversité, amphibiens, libellules.',
                'meta_title' => 'Mares et Points d\'Eau : Oasis de Vie | FarmShop',
                'meta_description' => 'Guide création mares : conception, équilibre biologique, faune aquatique. Biodiversité et écosystème aquatique.',
                'tags' => ['mares', 'points eau', 'biodiversité aquatique', 'amphibiens', 'écosystème'],
                'reading_time' => 8
            ],
            [
                'category' => 'Biodiversité',
                'title' => 'Hôtels à insectes : abris sur mesure',
                'content' => '<h2>Résidences 5 étoiles pour auxiliaires</h2><p>Bambous creux, bûches percées, briques creuses : chaque matériau attire espèces spécifiques. Construction, emplacement, entretien pour efficacité maximale.</p><h3>Architecture adaptée</h3><p><strong>Matériaux naturels :</strong> Bambous diamètres variés, bois tendres percés, tiges creuses. Adaptation morphologie insectes, diamètres 5-12mm.</p><p><strong>Compartiments spécialisés :</strong> Abeilles solitaires, coccinelles, chrysopes, perces-oreilles. Chaque espèce besoins spécifiques habitat.</p><h3>Construction pratique</h3><p><strong>Structure porteuse :</strong> Bois résistant intempéries, toit protecteur, orientation sud-est. Stabilité indispensable, oscillations fatales pontes.</p><p><strong>Remplissage :</strong> Matériaux secs, compaction légère, profondeur 15-20cm. Éviter traitements chimiques, bois autoclave déconseillé.</p><h3>Emplacement stratégique</h3><p><strong>Proximité ressources :</strong> Fleurs mellifères 50m, pucerons contrôler, eau disponible. Circuit court auxiliaires, efficacité renforcée.</p><p><strong>Protection prédateurs :</strong> Grillage fin anti-oiseaux, socle surélévation. Éviter exposition vents dominants.</p><h3>Entretien annuel</h3><p><strong>Nettoyage partiel :</strong> Renouvellement 1/3 matériaux automne, préservation cocons. Observation discrète, colonisation progressive.</p><p><strong>Amélioration continue :</strong> Matériaux attractifs, modules complémentaires. Adaptation locale, espèces observées.</p>',
                'excerpt' => 'Hôtels à insectes : construction, matériaux, emplacement. Abris auxiliaires, biodiversité fonctionnelle renforcée.',
                'meta_title' => 'Hôtels à Insectes : Abris sur Mesure | FarmShop',
                'meta_description' => 'Guide hôtels à insectes : construction, matériaux, emplacement. Abris auxiliaires pour biodiversité jardin.',
                'tags' => ['hôtels insectes', 'auxiliaires', 'biodiversité', 'construction', 'abris'],
                'reading_time' => 7
            ],

            // Agenda du Fermier (3 articles)
            [
                'category' => 'Agenda du Fermier',
                'title' => 'Calendrier lunaire : influence sur les cultures',
                'content' => '<h2>Jardiner avec la lune</h2><p>Lune montante/descendante, croissante/décroissante : ces cycles influencent sève, germination, conservation. Observations empiriques, applications pratiques jardinage respectueux.</p><h3>Cycles lunaires</h3><p><strong>Lune montante :</strong> Sève monte, greffage favorisé, récolte fruits aériens. Période favorable semis, transplantations jeunes plants.</p><p><strong>Lune descendante :</strong> Sève descend, enracinement stimulé, travail sol. Plantation, taille, récolte légumes-racines optimisée.</p><h3>Applications pratiques</h3><p><strong>Semis :</strong> Lune croissante graines rapides, décroissante graines lentes. Radis 3 jours croissante, carottes décroissante préférée.</p><p><strong>Plantations :</strong> Lune descendante, sol plus réceptif. Reprise racinaire facilitée, stress transplantation atténué.</p><h3>Récoltes optimisées</h3><p><strong>Fruits conserves :</strong> Lune descendante, concentration sucs. Pommes, poires stockage hivernal qualité supérieure.</p><p><strong>Légumes-feuilles :</strong> Lune montante, saveurs concentrées. Salades, épinards fraîcheur prolongée.</p><h3>Précautions d\'usage</h3><p>Observations empiriques, preuves scientifiques limitées. Complément pratiques conventionnelles, non substitution totale. Expérimentation personnelle encouraged.</p>',
                'excerpt' => 'Calendrier lunaire jardinage : cycles montante/descendante, applications semis, plantations, récoltes. Jardiner avec lune.',
                'meta_title' => 'Calendrier Lunaire : Influence sur les Cultures | FarmShop',
                'meta_description' => 'Guide calendrier lunaire : cycles, applications jardinage. Influence lune sur semis, plantations, récoltes.',
                'tags' => ['calendrier lunaire', 'jardinage lune', 'cycles lunaires', 'semis', 'plantations'],
                'reading_time' => 7
            ],
            [
                'category' => 'Agenda du Fermier',
                'title' => 'Planning saisonnier : organiser son potager',
                'content' => '<h2>Orchestrer les saisons</h2><p>Semis échelonnés, rotations planifiées, récoltes étalées : l\'organisation temporelle optimise production et travail. Planning annuel, ajustements climatiques régionaux.</p><h3>Planification annuelle</h3><p><strong>Hiver :</strong> Commandes graines, plans cultures, préparation matériel. Réflexion stratégique, formations techniques.</p><p><strong>Printemps :</strong> Semis intensifs, plantations, travaux préparatoires. Période critique, organisation minutieuse nécessaire.</p><h3>Échelonnement récoltes</h3><p><strong>Succession radis :</strong> Semis tous 15 jours mars-septembre. Production continue, évite surplus ponctuels.</p><p><strong>Étalement laitues :</strong> Variétés saisonnières, résistance montaison. Salade quotidienne avril-novembre possible.</p><h3>Rotations optimisées</h3><p><strong>Planning 4 ans :</strong> Légumineuses, feuilles, fruits, racines. Fertilité préservée, maladies limitées naturellement.</p><p><strong>Engrais verts :</strong> Intersaisons valorisées, sol protégé. Moutarde automne, phacélie printemps classiques.</p><h3>Adaptations régionales</h3><p><strong>Nord :</strong> Saison courte, semis tardifs, variétés précoces. Protection hivernale, cultures sous abri.</p><p><strong>Midi :</strong> Été difficile, cultures d\'arrière-saison. Ombrage, irrigation, variétés résistantes.</p>',
                'excerpt' => 'Planning saisonnier potager : échelonnement, rotations, adaptations régionales. Organisation temporelle optimisée.',
                'meta_title' => 'Planning Saisonnier : Organiser son Potager | FarmShop',
                'meta_description' => 'Guide planning potager : échelonnement semis, rotations, adaptations. Organisation saisonnière optimisée.',
                'tags' => ['planning saisonnier', 'organisation potager', 'échelonnement', 'rotations', 'saisons'],
                'reading_time' => 8
            ],
            [
                'category' => 'Agenda du Fermier',
                'title' => 'Météo agricole : anticiper et s\'adapter',
                'content' => '<h2>Danse avec les éléments</h2><p>Prévisions étendues, indicateurs locaux, mesures protection : anticiper météo optimise rendements et préserve cultures. Stations locales, observations terrain, stratégies adaptatives.</p><h3>Outils prévision</h3><p><strong>Stations météo :</strong> Météo France, réseaux locaux, applications spécialisées. Données précises 7 jours, tendances saisonnières.</p><p><strong>Observations terrain :</strong> Comportement animal, indices végétaux, dictons populaires. Sagesse paysanne, complémentarité technologie.</p><h3>Protections préventives</h3><p><strong>Gel tardif :</strong> Voiles protection, arrosage aspersion, fumigènes. Surveillance températures nocturnes critiques.</p><p><strong>Grêle :</strong> Filets anti-grêle, assurances récoltes, abris mobiles. Prévention investissement rentable.</p><h3>Adaptation culturale</h3><p><strong>Variétés résistantes :</strong> Sécheresse, excès eau, températures extrêmes. Sélection adaptée changement climatique.</p><p><strong>Calendrier flexible :</strong> Semis échelonnés, reports possibles, plans B préparés. Réactivité face aléas météorologiques.</p><h3>Gestion eau</h3><p><strong>Récupération :</strong> Citernes, bassins, toitures valorisées. Stockage périodes excédentaires, autonomie renforcée.</p><p><strong>Économie :</strong> Paillage, goutte-à-goutte, horaires optimaux. Efficience irrigation, préservation ressource.</p>',
                'excerpt' => 'Météo agricole : prévisions, protections, adaptations. Anticiper éléments, optimiser rendements, préserver cultures.',
                'meta_title' => 'Météo Agricole : Anticiper et s\'Adapter | FarmShop',
                'meta_description' => 'Guide météo agricole : prévisions, protections, adaptations climatiques. Stratégies face aux aléas météo.',
                'tags' => ['météo agricole', 'prévisions', 'adaptations climatiques', 'protections', 'gestion eau'],
                'reading_time' => 8
            ],

            // Matériel et Outils (2 articles)
            [
                'category' => 'Matériel et Outils',
                'title' => 'Outils manuels : choisir l\'essentiel',
                'content' => '<h2>Prolongements de nos mains</h2><p>Bêche qualité, serfouette polyvalente, sécateur affûté : bien choisir ses outils transforme jardinage. Critères qualité, entretien, budget optimisé pour efficacité durable.</p><h3>Outils de base indispensables</h3><p><strong>Bêche :</strong> Lame forgée, manche bois dur, emmanchement solide. Investissement 50-80€, durée vie 20 ans entretien.</p><p><strong>Serfouette :</strong> Panne/langue, manche 40cm, poids équilibré. Bingage, buttage, sarclage tool polyvalent.</p><h3>Qualité vs prix</h3><p><strong>Acier qualité :</strong> Carbone ou inox, trempe adaptée, résistance usure. Affûtage facile, performance constante.</p><p><strong>Manches ergonomiques :</strong> Bois dur (frêne), longueur adaptée morphologie. Prises confortables, effort réduit.</p><h3>Entretien prolongeant vie</h3><p><strong>Nettoyage :</strong> Terre éliminée, huilage léger anti-rouille. Rangement sec, protection intempéries.</p><p><strong>Affûtage :</strong> Lima douce, angle respecté, fil entretenu. Efficacité préservée, effort diminué.</p><h3>Spécialisation progressive</h3><p><strong>Outils spécifiques :</strong> Transplantoir, couteau désherboir, fourche-bêche. Acquisition selon besoins réels, pas gadgets.</p><p><strong>Budget raisonné :</strong> Qualité essentiel, spécialisés progressivement. 200-300€ panoplie complète amateur.</p>',
                'excerpt' => 'Outils manuels jardinage : bêche, serfouette, sécateur. Choisir qualité, entretien, budget optimisé.',
                'meta_title' => 'Outils Manuels : Choisir l\'Essentiel | FarmShop',
                'meta_description' => 'Guide outils manuels jardinage : critères qualité, entretien, budget. Choisir l\'essentiel pour efficacité.',
                'tags' => ['outils manuels', 'jardinage', 'bêche', 'serfouette', 'qualité'],
                'reading_time' => 7
            ],
            [
                'category' => 'Matériel et Outils',
                'title' => 'Motoculteur vs grelinette : que choisir ?',
                'content' => '<h2>Révolution mécanique ou respect du sol ?</h2><p>Motoculteur puissant vs grelinette préservatrice : deux philosophies jardinage. Avantages, inconvénients, choix selon surface, philosophie, budget disponible.</p><h3>Motoculteur mécanique</h3><p><strong>Avantages :</strong> Rapidité, surfaces importantes, labour profond. Effort physique minimal, productivité maximale.</p><p><strong>Inconvénients :</strong> Investissement élevé, entretien motorisation, compactage sol possible. Bruit, pollution, dépendance énergétique.</p><h3>Grelinette écologique</h3><p><strong>Avantages :</strong> Respect structure sol, pas retournement, exercice physique. Investissement unique 100-150€, durabilité exceptionnelle.</p><p><strong>Inconvénients :</strong> Effort physique, lenteur relative, surfaces limitées. Apprentissage gestuelle, conditions sols.</p><h3>Critères de choix</h3><p><strong>Surface :</strong> <500m² grelinette suffisante, >1000m² motoculteur justifié. Zone intermédiaire selon philosophie personnelle.</p><p><strong>Type sol :</strong> Argileux compact favorise motoculteur, sableux grelinette idéale. Adaptabilité selon conditions.</p><h3>Solutions mixtes</h3><p><strong>Location ponctuelle :</strong> Motoculteur gros travaux, grelinette entretien. Flexibilité, investissement réduit.</p><p><strong>Évolution progressive :</strong> Débuter grelinette, motoculteur si extension. Expérience avant investissement.</p>',
                'excerpt' => 'Motoculteur vs grelinette : comparaison avantages, critères choix. Mécanique puissant ou respect du sol ?',
                'meta_title' => 'Motoculteur vs Grelinette : Que Choisir ? | FarmShop',
                'meta_description' => 'Comparaison motoculteur grelinette : avantages, inconvénients, choix selon surface. Guide équipement jardin.',
                'tags' => ['motoculteur', 'grelinette', 'travail sol', 'équipement jardin', 'choix matériel'],
                'reading_time' => 8
            ],

            // Actualités Agricoles (2 articles)
            [
                'category' => 'Actualités Agricoles',
                'title' => 'Agriculture 4.0 : révolution numérique',
                'content' => '<h2>Technologie au service de la terre</h2><p>GPS, capteurs IoT, intelligence artificielle, drones : la révolution numérique transforme agriculture. Précision, optimisation, durabilité renforcée par innovation technologique.</p><h3>Technologies émergentes</h3><p><strong>Agriculture précision :</strong> GPS centimétrique, modulation intrants, cartographie rendements. Optimisation ressources, réduction impacts environnementaux.</p><p><strong>Capteurs connectés :</strong> Humidité sol, météo locale, stades cultures. Données temps réel, décisions éclairées.</p><h3>Applications concrètes</h3><p><strong>Drones surveillance :</strong> Stress hydrique, maladies, comptages. Vision aérienne, détection précoce anomalies.</p><p><strong>Robots autonomes :</strong> Désherbage mécanique, récolte fruits, traitement ciblé. Pénibilité réduite, précision accrue.</p><h3>Intelligence artificielle</h3><p><strong>Prédiction maladies :</strong> Modèles prédictifs, algorithmes apprentissage. Traitements préventifs ciblés, résistances évitées.</p><p><strong>Optimisation irrigation :</strong> IA analyse données multiples, recommandations personnalisées. Économie eau 30-50% possible.</p><h3>Défis adaptation</h3><p><strong>Coût technologies :</strong> Investissements importants, amortissement long terme. Mutualisation équipements, services partagés.</p><p><strong>Formation nécessaire :</strong> Compétences numériques, interprétation données. Accompagnement changement indispensable.</p>',
                'excerpt' => 'Agriculture 4.0 : GPS, IoT, IA, drones transforment agriculture. Précision, optimisation, révolution numérique.',
                'meta_title' => 'Agriculture 4.0 : Révolution Numérique | FarmShop',
                'meta_description' => 'Agriculture 4.0 : technologies GPS, IoT, IA, drones. Révolution numérique agriculture de précision.',
                'tags' => ['agriculture 4.0', 'technologie agricole', 'GPS', 'IoT', 'intelligence artificielle'],
                'reading_time' => 9
            ],
            [
                'category' => 'Actualités Agricoles',
                'title' => 'Changement climatique : adaptation nécessaire',
                'content' => '<h2>S\'adapter pour survivre</h2><p>Températures extrêmes, pluviométrie erratique, nouveaux parasites : l\'agriculture doit s\'adapter rapidement. Variétés résistantes, pratiques innovantes, solidarité territoriale.</p><h3>Impacts observés</h3><p><strong>Stress thermique :</strong> Canicules fréquentes, nuits tropicales, échaudage céréales. Rendements affectés, qualité dégradée.</p><p><strong>Régime hydrique :</strong> Sécheresses prolongées, pluies intenses concentrées. Gestion eau repensée, stockage nécessaire.</p><h3>Stratégies adaptation</h3><p><strong>Variétés tolérantes :</strong> Sécheresse, chaleur, nouveaux pathogènes. Recherche intensive, diffusion accélérée nécessaire.</p><p><strong>Pratiques culturales :</strong> Couverts permanents, agroforesterie, irrigation économe. Résilience renforcée, risques atténués.</p><h3>Innovation variétale</h3><p><strong>Sélection assistée :</strong> Marqueurs moléculaires, amélioration ciblée. Accélération programmes, traits complexes accessibles.</p><p><strong>Diversité génétique :</strong> Variétés anciennes, espèces négligées, conservation ressources. Réservoir adaptatif préservé.</p><h3>Accompagnement transition</h3><p><strong>Politiques publiques :</strong> Aides adaptation, recherche soutenue, formation renforcée. Anticipation nécessaire, réactivité insuffisante.</p><p><strong>Solidarité territoriale :</strong> Partage expériences, mutualisation risques, innovation collective. Résilience communautaire construite.</p>',
                'excerpt' => 'Changement climatique agriculture : impacts, adaptation, variétés résistantes. Stratégies survie face défis climatiques.',
                'meta_title' => 'Changement Climatique : Adaptation Nécessaire | FarmShop',
                'meta_description' => 'Changement climatique agriculture : impacts, stratégies adaptation, variétés résistantes. Défis climatiques.',
                'tags' => ['changement climatique', 'adaptation agriculture', 'variétés résistantes', 'stress climatique', 'résilience'],
                'reading_time' => 10
            ]
        ];
    }
}
