<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\User;

class BlogPostBatch4Seeder extends Seeder
{
    /**
     * Seeder pour 4 catégories : Elevage Responsable, Produits du Terroir, Agriculture Durable, Compostage et Recyclage
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
        $startDate = Carbon::now()->subMonths(11); // Décaler par rapport aux batches précédents

        foreach ($articles as $index => $article) {
            $category = $categories->get($article['category']);
            
            if (!$category) {
                $this->command->warn("Catégorie '{$article['category']}' non trouvée pour l'article '{$article['title']}'");
                continue;
            }

            $publishedAt = $startDate->copy()->addDays($index * 5); // Étaler sur 11 mois
            
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
                    'batch' => 4,
                    'featured' => $index < 3,
                    'priority' => $index < 2 ? 'high' : 'normal'
                ],
                'tags' => $article['tags'],
                'views_count' => rand(40, 680),
                'likes_count' => rand(4, 55),
                'shares_count' => rand(1, 20),
                'comments_count' => rand(0, 18),
                'reading_time' => $article['reading_time'],
                'allow_comments' => true,
                'is_featured' => $index < 2,
                'is_sticky' => false,
                'author_id' => $admin->id,
                'last_edited_by' => $admin->id,
                'last_edited_at' => $publishedAt,
            ]);
            
            $publishedCount++;
        }

        $this->command->info("✅ Batch 4 : {$publishedCount} articles créés pour 4 catégories !");
    }

    private function getArticles()
    {
        return [
            // Elevage Responsable (5 articles)
            [
                'category' => 'Elevage Responsable',
                'title' => 'Bien-être animal : 10 principes fondamentaux',
                'content' => '<h2>Respecter nos compagnons de ferme</h2><p>Espace, alimentation, soins, respect : le bien-être animal combine éthique et performances. Ces 10 principes transforment l\'élevage en partenariat harmonieux entre homme et animal.</p><h3>Besoins physiologiques</h3><p><strong>Espace vital :</strong> 4m²/poule, 2m²/lapin, densité adaptée espèce. Entassement = stress, maladies, productivité chutée inéluctablement.</p><p><strong>Alimentation équilibrée :</strong> Rations complètes, eau propre permanent. Respecter régimes naturels, éviter carences, surplus destructeurs.</p><h3>Environnement adapté</h3><p><strong>Abris confortables :</strong> Température optimale, ventilation sans courant, litière sèche renouvelée. Protection intempéries, prédateurs, stress thermique.</p><p><strong>Lumière naturelle :</strong> Cycles jour/nuit respectés, accès extérieur encouragé. Vitamine D synthétisée, comportements naturels exprimés.</p><h3>Santé préventive</h3><p><strong>Observation quotidienne :</strong> Comportement, appétit, aspect général surveillés. Détection précoce, intervention rapide, souffrances évitées.</p><p><strong>Soins appropriés :</strong> Vétérinaire référent, protocoles préventifs, traitements raisonnés. Médecines douces privilégiées, antibiotiques derniers recours.</p><h3>Respect comportemental</h3><p><strong>Manipulations douces :</strong> Mouvements lents, voix calme, respect peurs. Confiance établie, stress minimisé, relations apaisées.</p>',
                'excerpt' => 'Bien-être animal : 10 principes pour élevage respectueux. Espace, alimentation, santé, comportement au cœur.',
                'meta_title' => 'Bien-être Animal : 10 Principes Fondamentaux | FarmShop',
                'meta_description' => 'Guide bien-être animal : 10 principes élevage responsable. Espace, santé, respect comportement pour ferme éthique.',
                'tags' => ['bien-être animal', 'élevage responsable', 'éthique', 'santé animale', 'respect'],
                'reading_time' => 8
            ],
            [
                'category' => 'Elevage Responsable',
                'title' => 'Pâturage tournant : optimiser les prairies',
                'content' => '<h2>Gérer l\'herbe intelligemment</h2><p>Rotation parcelles, repos végétation, charge animale adaptée : le pâturage tournant triple productivité prairies tout préservant sols et biodiversité naturelle.</p><h3>Principe de rotation</h3><p><strong>Division parcellaire :</strong> 6-12 paddocks selon surface, espèce, saison. Pâturage intensif court, repos long favorisent repousse optimale.</p><p><strong>Temps de séjour :</strong> 3-7 jours maximum/paddock, selon hauteur herbe. Éviter surpâturage destructeur, sous-pâturage gaspilleur également.</p><h3>Indicateurs terrain</h3><p><strong>Hauteur optimale :</strong> Entrée 15-20cm, sortie 5-7cm minimum. Préserver points végétatifs, assurer photosynthèse, maintenir système racinaire.</p><p><strong>Observation prairies :</strong> Couleur, densité, espèces indicatrices. Sol non piétiné, vers terre actifs, diversité floristique préservée.</p><h3>Bénéfices multiples</h3><p><strong>Productivité accrue :</strong> +50% production herbe, qualité nutritive améliorée. Légumineuses favorisées, autonomie protéique renforcée.</p><p><strong>Sol régénéré :</strong> Matière organique enrichie, structure améliorée, érosion limitée. Stockage carbone, fertilité restaurée naturellement.</p><h3>Mise en pratique</h3><p><strong>Clôtures mobiles :</strong> Fils électriques, piquets légers, déplacement rapide. Investissement modéré, flexibilité maximale, adaptation météo.</p>',
                'excerpt' => 'Pâturage tournant : tripler productivité prairies. Rotation, repos végétation, charge adaptée pour sols sains.',
                'meta_title' => 'Pâturage Tournant : Optimiser les Prairies | FarmShop',
                'meta_description' => 'Guide pâturage tournant : rotation, productivité, sols sains. Gestion intelligente prairies pour élevage durable.',
                'tags' => ['pâturage tournant', 'prairies', 'rotation', 'élevage durable', 'sols'],
                'reading_time' => 7
            ],
            [
                'category' => 'Elevage Responsable',
                'title' => 'Alimentation naturelle : plantes médicinales',
                'content' => '<h2>Soigner par les plantes</h2><p>Ortie, plantain, saule, ail : ces plantes communes renforcent immunité animale naturellement. Prévention douce, coûts réduits, résistances antibiotiques évitées intelligemment.</p><h3>Plantes immunostimulantes</h3><p><strong>Ortie séchée :</strong> 2% ration, fer biodisponible, vitamines concentrées. Poules pondeuses, vaches laitières, pelage brillant garanti.</p><p><strong>Échinacée :</strong> Cure préventive automne/hiver, immunité renforcée. Teinture-mère dans eau boisson, posologie vétérinaire respectée.</p><h3>Digestifs naturels</h3><p><strong>Plantain lancéolé :</strong> Anti-inflammatoire intestinal, diarrhées stoppées. Feuilles fraîches broutées, séchées en complément hivernal.</p><p><strong>Fenouil graines :</strong> Stimulant lactation, digestion améliorée. Juments gestantes, chèvres laitières, production optimisée naturellement.</p><h3>Vermifuges végétaux</h3><p><strong>Tanaisie :</strong> Antiparasitaire puissant, cure courte supervisée. Rotation avec autres plantes, résistances évitées, efficacité maintenue.</p><p><strong>Courge graines :</strong> Ténia, vers ronds éliminés. Broyage fin, incorporation aliment, traitement doux chevreaux/agneaux.</p><h3>Précautions usage</h3><p><strong>Identification certaine :</strong> Formation phytothérapie vétérinaire, plantes toxiques nombreuses. Dosage précis, suivi professionnel, automédicament évité.</p>',
                'excerpt' => 'Alimentation naturelle animaux : plantes médicinales préventives. Ortie, plantain, vermifuges végétaux pour immunité.',
                'meta_title' => 'Alimentation Naturelle : Plantes Médicinales | FarmShop',
                'meta_description' => 'Guide plantes médicinales animaux : ortie, plantain, vermifuges naturels. Soins préventifs et immunité renforcée.',
                'tags' => ['plantes médicinales', 'phytothérapie', 'alimentation naturelle', 'immunité', 'vermifuges'],
                'reading_time' => 8
            ],
            [
                'category' => 'Elevage Responsable',
                'title' => 'Races locales : préserver la biodiversité',
                'content' => '<h2>Patrimoine génétique vivant</h2><p>Marans, Gasconne, Ouessant, Bourbonnaise : ces races locales cumulent rusticité et qualités gustatives. Les préserver c\'est sauvegarder adaptation millénaire et diversité génétique.</p><h3>Avantages races locales</h3><p><strong>Adaptation climatique :</strong> Sélection naturelle séculaire, résistance maladies locales. Moins interventions vétérinaires, autonomie sanitaire renforcée remarquablement.</p><p><strong>Qualités gustatives :</strong> Viandes persillées, œufs colorés, laits typés. Circuits courts valorisés, consommateurs séduits, prix rémunérateurs obtenus.</p><h3>Races bovines patrimoniales</h3><p><strong>Aubrac :</strong> Montagne, rusticité extrême, vêlages faciles. Viande savoureuse, élevage extensif, entretien paysages difficiles.</p><p><strong>Bretonne Pie Noir :</strong> Lait riche, format moyen, docilité. Reconversion laitiers familiaux, fromages fermiers, qualité exceptionnelle.</p><h3>Volailles d\'exception</h3><p><strong>Bresse Gauloise :</strong> AOC prestigieuse, chair fine, croissance lente. Élevage parcours, céréales maïs, savoir-faire transmis.</p><p><strong>Marans :</strong> Œufs "chocolat", ponte régulière, tempérament calme. Marché œufs colorés, prix élevés, niche rentable.</p><h3>Conservation active</h3><p><strong>Reproducteurs :</strong> Choix lignées pures, éviter consanguinité. Registres généalogiques, échanges éleveurs, diversité maintenue activement.</p>',
                'excerpt' => 'Races locales : préserver biodiversité et rusticité. Adaptation climatique, qualités gustatives, patrimoine génétique.',
                'meta_title' => 'Races Locales : Préserver la Biodiversité | FarmShop',
                'meta_description' => 'Guide races locales : biodiversité, rusticité, adaptation. Préserver patrimoine génétique et qualités gustatives.',
                'tags' => ['races locales', 'biodiversité', 'patrimoine génétique', 'rusticité', 'adaptation'],
                'reading_time' => 7
            ],
            [
                'category' => 'Elevage Responsable',
                'title' => 'Réduction stress : environnement apaisant',
                'content' => '<h2>Créer un havre de paix</h2><p>Bruits forts, mouvements brusques, surpopulation : ces facteurs stressent animaux et chutent performances. Environnement calme = santé optimale et productivité naturelle.</p><h3>Sources de stress identifiées</h3><p><strong>Bruits soudains :</strong> Moteurs, cris, claquements dérangent. Routine établie, gestes doux, voix basse apaisent remarquablement les troupeaux.</p><p><strong>Surpopulation :</strong> Hiérarchie perturbée, agressions fréquentes, maladies propagées. Respect densités, groupes stables, dominance respectée.</p><h3>Aménagements anti-stress</h3><p><strong>Zones refuge :</strong> Abris multiples, cachettes naturelles, échappatoires possibles. Animaux faibles protégés, hiérarchie respectée, conflits évités.</p><p><strong>Éclairage progressif :</strong> Variateurs, simulation aube/crépuscule, rythmes respectés. Stress lumineux éliminé, bien-être amélioré considérablement.</p><h3>Manipulations apaisantes</h3><p><strong>Contention douce :</strong> Installations ergonomiques, surfaces antidérapantes, angles arrondis. Peur minimisée, sécurité maximisée, efficacité préservée.</p><p><strong>Approche progressive :</strong> Mouvements lents, contact visuel, récompenses positives. Conditionnement favorable, confiance établie, stress éliminé.</p><h3>Indicateurs de bien-être</h3><p><strong>Comportements naturels :</strong> Jeux, toilettage mutuel, repos paisible observés. Stress absent, épanouissement visible, performances optimales atteintes.</p>',
                'excerpt' => 'Réduction stress animaux : environnement calme, manipulations douces. Bien-être optimal pour performances naturelles.',
                'meta_title' => 'Réduction Stress : Environnement Apaisant | FarmShop',
                'meta_description' => 'Guide réduction stress animaux : environnement calme, manipulations douces. Bien-être et performances optimisées.',
                'tags' => ['réduction stress', 'bien-être', 'environnement calme', 'manipulations', 'performances'],
                'reading_time' => 7
            ],

            // Produits du Terroir (5 articles)
            [
                'category' => 'Produits du Terroir',
                'title' => 'Fromages fermiers : maîtriser l\'affinage',
                'content' => '<h2>L\'art de transformer le lait</h2><p>Température, humidité, retournements : l\'affinage transforme caillé fade en fromage complexe. Maîtriser ces paramètres révèle terroir et savoir-faire dans chaque bouchée.</p><h3>Cave d\'affinage idéale</h3><p><strong>Température :</strong> 10-14°C stable, variations minimales. Thermomètre min/max, isolation correcte, ventilation douce contrôlée.</p><p><strong>Hygrométrie :</strong> 80-95% selon type fromage. Bacs eau, pierre poreuse, humidificateurs maintiennent ambiance optimale.</p><h3>Techniques selon types</h3><p><strong>Pâtes molles :</strong> Camembert, brie, retournements quotidiens. Fleur blanche développée, cœur fondant, croûte non collante.</p><p><strong>Pâtes pressées :</strong> Tome, cantal, lavages saumure. Croûte orangée, pâte souple, goût développé progressivement.</p><h3>Suivi rigoureux</h3><p><strong>Retournements :</strong> Quotidiens première semaine, espacés ensuite. Forme maintenue, égouttage uniforme, moisissures contrôlées.</p><p><strong>Brossage :</strong> Soies naturelles, croûtes nettoyées, moisissures indésirables éliminées. Aspect commercial, conservation prolongée.</p><h3>Défauts à éviter</h3><p><strong>Amertume :</strong> Température excessive, humidité insuffisante. Surveillance quotidienne, corrections immédiates, qualité préservée.</p><p><strong>Moisissures noires :</strong> Ventilation défaillante, nettoyage insuffisant. Prophylaxie rigoureuse, cave assainie, production sauvegardée.</p>',
                'excerpt' => 'Fromages fermiers : maîtriser affinage cave. Température, humidité, techniques selon types pour révéler terroir.',
                'meta_title' => 'Fromages Fermiers : Maîtriser l\'Affinage | FarmShop',
                'meta_description' => 'Guide affinage fromages fermiers : cave, température, techniques. Révéler terroir et savoir-faire traditionnel.',
                'tags' => ['fromages fermiers', 'affinage', 'cave', 'techniques', 'terroir'],
                'reading_time' => 8
            ],
            [
                'category' => 'Produits du Terroir',
                'title' => 'Charcuterie maison : salaisons traditionnelles',
                'content' => '<h2>Conserver la viande ancestralement</h2><p>Sel, temps, patience : ces trois ingrédients transforment viande fraîche en charcuterie savoureuse. Techniques millénaires adaptées normes modernes pour produits authentiques.</p><h3>Salaison à sec</h3><p><strong>Jambon de campagne :</strong> Sel gris 3%, sucre 0,5%, temps. Frottage énergique, retournements réguliers, 1 jour/kg minimum.</p><p><strong>Coppa :</strong> Longe désossée, épices méditerranéennes, boyau naturel. Maturation 3-6 mois, perte poids 35%, texture fondante.</p><h3>Préparations boudin</h3><p><strong>Boudin noir :</strong> Sang frais, gras, oignons confits, épices. Cuisson douce 80°C, pas ébullition, boyaux naturels exclusivement.</p><p><strong>Boudin blanc :</strong> Viande blanche, lait, œufs, mie pain. Liaison parfaite, texture moelleuse, conservation limitée obligatoire.</p><h3>Séchage contrôlé</h3><p><strong>Saucisson sec :</strong> Hachage grossier, ferments lactiques, étuvage 24h. Flore développée, acidité contrôlée, séchage progressif.</p><p><strong>Conditions :</strong> 12-15°C, 75% humidité, ventilation légère. Cave naturelle, contrôles réguliers, qualité garantie.</p><h3>Sécurité sanitaire</h3><p><strong>Hygiène :</strong> Matériel stérilisé, viande fraîche, traçabilité complète. Température contrôlée, HACCP respecté, risques maîtrisés.</p>',
                'excerpt' => 'Charcuterie maison : salaisons traditionnelles sécurisées. Jambon, saucisson, techniques ancestrales adaptées normes.',
                'meta_title' => 'Charcuterie Maison : Salaisons Traditionnelles | FarmShop',
                'meta_description' => 'Guide charcuterie maison : salaisons, techniques traditionnelles, sécurité. Jambon, saucisson, conservation ancestrale.',
                'tags' => ['charcuterie maison', 'salaisons', 'jambon', 'saucisson', 'conservation'],
                'reading_time' => 9
            ],
            [
                'category' => 'Produits du Terroir',
                'title' => 'Huiles essentielles : distillation artisanale',
                'content' => '<h2>Capturer l\'essence des plantes</h2><p>Alambic, vapeur, décantation : la distillation révèle quintessence aromatique végétaux. Lavande, thym, menthe transformés en or liquide thérapeutique et olfactif.</p><h3>Principe distillation</h3><p><strong>Entraînement vapeur :</strong> Eau bouillante, vapeur traverse plantes, essences volatilisées. Condensation, séparation phases, huile pure récupérée.</p><p><strong>Rendements :</strong> Lavande 1%, thym 0,5%, rose 0,02%. Quantités importantes nécessaires, calculs économiques préalables indispensables.</p><h3>Matériel artisanal</h3><p><strong>Alambic cuivre :</strong> 25-100L selon production, chauffe gaz/électrique. Serpentin refroidissement, essencier séparateur, robinets qualité alimentaire.</p><p><strong>Préparation plantes :</strong> Récolte matin après rosée, séchage partiel optionnel. Hachage grossier, tassage évité, circulation vapeur optimisée.</p><h3>Processus détaillé</h3><p><strong>Chargement :</strong> Plantes fraîches ou sèches, eau pure, niveau optimal. Joints étanches, pression contrôlée, sécurité vérifiée.</p><p><strong>Distillation :</strong> Montée température progressive, première goutte = début. 2-4h selon plante, surveillance constante, qualité préservée.</p><h3>Conservation optimale</h3><p><strong>Flacons teintés :</strong> Verre brun, hermétiques, étiquetage précis. Réfrigération conseillée, durée 2-5 ans, qualité maintenue.</p>',
                'excerpt' => 'Distillation artisanale huiles essentielles : alambic, techniques, conservation. Lavande, thym, essence pure captée.',
                'meta_title' => 'Huiles Essentielles : Distillation Artisanale | FarmShop',
                'meta_description' => 'Guide distillation huiles essentielles : alambic, techniques, plantes. Capturer essence pure lavande, thym, menthe.',
                'tags' => ['huiles essentielles', 'distillation', 'alambic', 'lavande', 'artisanal'],
                'reading_time' => 8
            ],
            [
                'category' => 'Produits du Terroir',
                'title' => 'Conserves traditionnelles : stérilisation maîtrisée',
                'content' => '<h2>Préserver saveurs et sécurité</h2><p>Température, temps, acidité : la stérilisation maîtrisée garantit conserves saines 2-5 ans. Légumes, fruits, viandes transformés en réserves savoureuses et sécurisées.</p><h3>Principe stérilisation</h3><p><strong>Destruction pathogènes :</strong> 121°C détruit spores botuliques, sécurité absolue. Autoclave professionnel ou stérilisateur domestique adapté.</p><p><strong>Temps selon aliments :</strong> Légumes acides 15min, peu acides 90min. Viandes 120min minimum, sécurité priorité absolue.</p><h3>Préparation rigoureuse</h3><p><strong>Bocaux ébouillantés :</strong> Verre trempé, couvercles neufs, joints parfaits. Remplissage chaud, niveau optimal, bulles air éliminées.</p><p><strong>Légumes blanchis :</strong> Ébouillantage 2-5min, refroidissement brutal, enzymes inactivées. Couleur préservée, texture maintenue, vitamines protégées.</p><h3>Contrôles sécurité</h3><p><strong>pH mesuré :</strong> <4,6 pour conserves simples, acidification si nécessaire. Vinaigre, acide citrique, sécurité renforcée garantie.</p><p><strong>Vide d\'air :</strong> Capsules bombées rejetées, dates notées, rotation stocks. Consommation 2 ans maximum, qualité optimale préservée.</p><h3>Recettes éprouvées</h3><p><strong>Ratatouille :</strong> Légumes méditerranéens, huile olive, cuisson préalable. Stérilisation 40min/100°C, saveurs concentrées, praticité garantie.</p>',
                'excerpt' => 'Conserves traditionnelles sécurisées : stérilisation maîtrisée, légumes, fruits. Préserver saveurs 2-5 ans en sécurité.',
                'meta_title' => 'Conserves Traditionnelles : Stérilisation Maîtrisée | FarmShop',
                'meta_description' => 'Guide conserves maison : stérilisation, sécurité, techniques. Préserver légumes, fruits, viandes traditionnellement.',
                'tags' => ['conserves traditionnelles', 'stérilisation', 'bocaux', 'sécurité', 'préservation'],
                'reading_time' => 8
            ],
            [
                'category' => 'Produits du Terroir',
                'title' => 'Vinaigres aromatisés : macération et vieillissement',
                'content' => '<h2>Sublimer l\'acidité naturelle</h2><p>Herbes, fruits, épices : ces aromates transforment vinaigre simple en condiment gastronomique. Macération patiente révèle complexité gustative insoupçonnée et raffinement culinaire.</p><h3>Base vinaigre qualité</h3><p><strong>Vinaigre blanc :</strong> Neutre, 8° acidité, base universelle. Macérations délicates, aromates purs, polyvalence maximale garantie.</p><p><strong>Vinaigre vin :</strong> Rouge/blanc, caractère affirmé, terroir exprimé. Accord aromates, personnalité renforcée, sophistication assurée.</p><h3>Aromates sélectionnés</h3><p><strong>Estragon :</strong> Branche fraîche, macération 15 jours, filtrage. Vinaigrettes raffinées, sauces béarnaise, élégance française classique.</p><p><strong>Framboise :</strong> Fruits mûrs écrasés, macération 1 mois. Couleur rubis, acidité fruitée, salades sophistiquées sublimées.</p><h3>Techniques macération</h3><p><strong>Préparation :</strong> Aromates propres, récipients verre, hermétiques. Proportions 10%, agitation hebdomadaire, patience indispensable.</p><p><strong>Filtrage final :</strong> Étamine fine, clarification parfaite, résidus éliminés. Présentation commerciale, conservation prolongée, qualité maintenue.</p><h3>Vieillissement noble</h3><p><strong>Fûts chêne :</strong> Arômes boisés, concentration naturelle, évaporation contrôlée. Vinaigres balsamiques, prestiges gastronomiques, prix valorisés.</p>',
                'excerpt' => 'Vinaigres aromatisés artisanaux : macération herbes, fruits, épices. Transformer acidité simple en condiment gastronomique.',
                'meta_title' => 'Vinaigres Aromatisés : Macération et Vieillissement | FarmShop',
                'meta_description' => 'Guide vinaigres aromatisés : macération, aromates, techniques. Condiments gastronomiques artisanaux raffinés.',
                'tags' => ['vinaigres aromatisés', 'macération', 'aromates', 'gastronomie', 'condiments'],
                'reading_time' => 7
            ],

            // Agriculture Durable (5 articles)
            [
                'category' => 'Agriculture Durable',
                'title' => 'Permaculture : concevoir son système',
                'content' => '<h2>Imiter les écosystèmes naturels</h2><p>Observation, design, zones d\'activité : la permaculture optimise ressources en s\'inspirant nature. Productivité maximale, effort minimal, durabilité garantie par conception intelligente.</p><h3>Éthique permaculturelle</h3><p><strong>Prendre soin terre :</strong> Sol vivant, biodiversité préservée, ressources régénérées. Vision long terme, générations futures considérées, héritage transmis.</p><p><strong>Prendre soin humains :</strong> Santé, bien-être, relations sociales. Partage équitable, coopération privilégiée, harmonie communautaire recherchée.</p><h3>Principes de design</h3><p><strong>Observation prolongée :</strong> 1 an minimum, saisons, microclimats, circulation eau. Comprendre avant agir, solutions adaptées, erreurs évitées.</p><p><strong>Zonage fonctionnel :</strong> Zone 1 intensive, zone 5 sauvage. Énergie investie selon besoins, efficacité maximisée, gestion optimisée.</p><h3>Techniques intégrées</h3><p><strong>Associations bénéfiques :</strong> Trois sœurs (maïs-haricot-courge), guildes fruitières. Synergie végétaux, rendements multipliés, chimie éliminée.</p><p><strong>Recyclage nutriments :</strong> Compost, paillis, animaux intégrés. Déchets = ressources, cycles bouclés, autonomie renforcée.</p><h3>Mise en œuvre progressive</h3><p><strong>Démarrage modeste :</strong> Petit secteur maîtrisé, expérience acquise, expansion graduelle. Apprentissage continu, ajustements réguliers, succès consolidé.</p>',
                'excerpt' => 'Permaculture : concevoir système durable imitant nature. Observation, design, zones, techniques intégrées pour productivité optimale.',
                'meta_title' => 'Permaculture : Concevoir son Système | FarmShop',
                'meta_description' => 'Guide permaculture : design, observation, zonage. Concevoir système agricole durable imitant écosystèmes naturels.',
                'tags' => ['permaculture', 'design', 'zonage', 'écosystèmes', 'durabilité'],
                'reading_time' => 9
            ],
            [
                'category' => 'Agriculture Durable',
                'title' => 'Agroforesterie : arbres et cultures associées',
                'content' => '<h2>Marier arbres et agriculture</h2><p>Haies, alignements, bosquets : l\'agroforesterie multiplie fonctions parcelles. Protection vents, biodiversité, revenus diversifiés, carbone stocké durablement et intelligemment.</p><h3>Bénéfices multiples</h3><p><strong>Microclimat favorable :</strong> Brise-vent, ombrage partiel, humidité régulée. Cultures protégées, stress hydrique réduit, rendements stabilisés remarquablement.</p><p><strong>Biodiversité enrichie :</strong> Auxiliaires logés, corridors écologiques, pollinisateurs attirés. Équilibres naturels, traitements réduits, résilience accrue.</p><h3>Systèmes agroforestiers</h3><p><strong>Haies bocagères :</strong> Essences locales, multi-étages, fonctions variées. Bois énergie, fruits sauvages, refuge faune, patrimoine préservé.</p><p><strong>Alignements intraparcellaires :</strong> Rangées espacées 20-50m, mécanisation maintenue. Noyers, châtaigniers, revenus différés, valorisation long terme.</p><h3>Conception technique</h3><p><strong>Espacement calculé :</strong> Ombrage limité, concurrence racinaire minimisée. Modélisation solaire, zone influence, compromis optimal trouvé.</p><p><strong>Essences adaptées :</strong> Croissance rapide/lente, système racinaire, besoins hydriques. Compatibilité cultures, objectifs production, cohérence assurée.</p><h3>Gestion temporelle</h3><p><strong>Plantation progressive :</strong> Secteurs prioritaires, financement échelonné, expérience capitalisée. Erreurs corrigées, techniques affinées, succès généralisé.</p>',
                'excerpt' => 'Agroforesterie : associer arbres et cultures. Microclimat, biodiversité, revenus diversifiés, carbone stocké durablement.',
                'meta_title' => 'Agroforesterie : Arbres et Cultures Associées | FarmShop',
                'meta_description' => 'Guide agroforesterie : haies, alignements, association arbres-cultures. Biodiversité, microclimat, revenus diversifiés.',
                'tags' => ['agroforesterie', 'arbres', 'haies', 'biodiversité', 'microclimat'],
                'reading_time' => 8
            ],
            [
                'category' => 'Agriculture Durable',
                'title' => 'Gestion de l\'eau : récupération et stockage',
                'content' => '<h2>Chaque goutte compte</h2><p>Toitures, mares, fossés : multiplier points collecte optimise ressource hydrique. Autonomie renforcée, coûts réduits, résilience climatique accrue par gestion intelligente.</p><h3>Récupération eaux pluviales</h3><p><strong>Toitures :</strong> 600L/m²/an région tempérée, potentiel énorme. Gouttières dimensionnées, cuves 1000-5000L, filtration simple intégrée.</p><p><strong>Surfaces imperméables :</strong> Cours, serres, tunnels valorisés. Pentes dirigées, collecteurs enterrés, stockage centralisé optimisé.</p><h3>Stockage adapté</h3><p><strong>Cuves plastique :</strong> Économiques, modulaires, installation facile. Protection UV, vidange hivernale, maintenance réduite appréciée.</p><p><strong>Bassins géomembrane :</strong> Volumes importants, coûts maîtrisés. Bâches EPDM, protection mécanique, durée 20 ans garantie.</p><h3>Distribution raisonnée</h3><p><strong>Gravitaire :</strong> Surélévation naturelle/artificielle, pression suffisante. Économies énergie, fiabilité maximale, maintenance simplifiée.</p><p><strong>Aspersion :</strong> Pompes immergées, programmation automatique. Consommation optimisée, stress hydrique évité, productivité maintenue.</p><h3>Épuration naturelle</h3><p><strong>Lagunage :</strong> Bassins étagés, plantes épuratrices, filtration biologique. Eaux grises recyclées, autonomie complète, écologie respectée.</p>',
                'excerpt' => 'Gestion eau durable : récupération pluviale, stockage, distribution. Autonomie hydrique et résilience climatique.',
                'meta_title' => 'Gestion de l\'Eau : Récupération et Stockage | FarmShop',
                'meta_description' => 'Guide gestion eau : récupération pluviale, stockage, distribution. Autonomie hydrique pour agriculture durable.',
                'tags' => ['gestion eau', 'récupération pluviale', 'stockage', 'autonomie', 'résilience'],
                'reading_time' => 8
            ],
            [
                'category' => 'Agriculture Durable',
                'title' => 'Énergies renouvelables : autonomie de la ferme',
                'content' => '<h2>Indépendance énergétique agricole</h2><p>Solaire, éolien, biogaz : diversifier sources énergétiques libère exploitations. Coûts maîtrisés, revenus complémentaires, empreinte carbone réduite durablement et rentablement.</p><h3>Solaire photovoltaïque</h3><p><strong>Toitures optimisées :</strong> Orientation sud, inclinaison 30°, ombrages évités. Hangars, stabulations, serres équipées, espaces valorisés intelligemment.</p><p><strong>Dimensionnement :</strong> Autoconsommation prioritaire, revente surplus. 3kWc = 3000kWh/an, facture divisée, rentabilité 8-12 ans.</p><h3>Petit éolien agricole</h3><p><strong>Vents réguliers :</strong> Vitesse 15km/h minimum, régularité essentielle. Mât 12-15m, éoliennes 5-10kW, sites dégagés privilégiés.</p><p><strong>Acceptation locale :</strong> Nuisances sonores limitées, impact visuel réduit. Concertation riverains, autorisation préalable, intégration paysagère.</p><h3>Méthanisation à la ferme</h3><p><strong>Intrants disponibles :</strong> Fumiers, lisiers, déchets verts valorisés. Digesteur 20-100m³, biogaz produit, électricité générée, cycles bouclés.</p><p><strong>Digestat fertilisant :</strong> Azote stabilisé, odeurs réduites, épandage facilité. Valeur agronomique préservée, économies engrais, sol enrichi.</p><h3>Stockage et gestion</h3><p><strong>Batteries lithium :</strong> Autoconsommation différée, autonomie renforcée. Technologies évolutives, coûts baissent, rentabilité améliore.</p>',
                'excerpt' => 'Énergies renouvelables ferme : solaire, éolien, biogaz. Autonomie énergétique, coûts maîtrisés, revenus complémentaires.',
                'meta_title' => 'Énergies Renouvelables : Autonomie de la Ferme | FarmShop',
                'meta_description' => 'Guide énergies renouvelables agricoles : solaire, éolien, biogaz. Indépendance énergétique et revenus durables.',
                'tags' => ['énergies renouvelables', 'solaire', 'éolien', 'biogaz', 'autonomie'],
                'reading_time' => 9
            ],
            [
                'category' => 'Agriculture Durable',
                'title' => 'Sols vivants : restaurer la fertilité naturelle',
                'content' => '<h2>Nourrir la terre qui nous nourrit</h2><p>Vie microbienne, matière organique, structure stable : sols vivants produisent mieux avec moins d\'intrants. Régénération possible, techniques simples, résultats durables garantis.</p><h3>Diagnostic sol existant</h3><p><strong>Test bêche :</strong> Structure, compaction, vie visible. Vers terre nombreux = santé, mottes dures = dégradation, odeur fraîche = équilibre.</p><p><strong>Analyses biologiques :</strong> Biomasse microbienne, enzymes, respiration. Laboratoires spécialisés, indicateurs nouveaux, fertilité globale évaluée.</p><h3>Apports organiques</h3><p><strong>Compost mûr :</strong> 20-40T/ha, vie microbienne inoculée. Matière organique stable, humus formé, CEC améliorée durablement.</p><p><strong>BRF broyats :</strong> Rameaux feuillus, lignine dégradée, champignons favorisés. Mulchage épais, sol protégé, fertilité restaurée progressivement.</p><h3>Couverts végétaux</h3><p><strong>Légumineuses :</strong> Trèfle, luzerne, fixation azote gratuite. 150-300kg N/ha économisés, sol enrichi, cycles bouclés naturellement.</p><p><strong>Crucifères :</strong> Radis, moutarde, structuration profonde. Racines pivotantes, compaction cassée, drainage amélioré considérablement.</p><h3>Non-travail du sol</h3><p><strong>Semis direct :</strong> Structure préservée, vie respectée, érosion stoppée. Matériel adapté, technique maîtrisée, résultats progressifs.</p>',
                'excerpt' => 'Sols vivants : restaurer fertilité naturelle par vie microbienne, matière organique. Diagnostic, apports, couverts végétaux.',
                'meta_title' => 'Sols Vivants : Restaurer la Fertilité Naturelle | FarmShop',
                'meta_description' => 'Guide sols vivants : restauration fertilité, vie microbienne, couverts végétaux. Nourrir terre naturellement.',
                'tags' => ['sols vivants', 'fertilité', 'vie microbienne', 'couverts végétaux', 'restauration'],
                'reading_time' => 8
            ],

            // Compostage et Recyclage (5 articles)
            [
                'category' => 'Compostage et Recyclage',
                'title' => 'Compost thermophile : technique des andains',
                'content' => '<h2>Accélérer la décomposition</h2><p>Retournements fréquents, aération forcée, montée 60-70°C : compost thermophile transforme déchets en humus riche 3 mois. Pathogènes détruits, graines adventices éliminées.</p><h3>Principe thermophile</h3><p><strong>Montée température :</strong> Activité microbienne intense, chaleur dégagée, pathogènes détruits. Phase thermophile 2-3 semaines, maturation suivante.</p><p><strong>Équilibre C/N :</strong> Ratio 25-30/1 optimal, fermentation équilibrée. Carbone (paille) + azote (tontes), mélange homogène essentiel.</p><h3>Construction andains</h3><p><strong>Dimensions :</strong> Largeur 2m, hauteur 1,5m, longueur variable. Volume critique atteint, température maintenue, efficacité maximisée.</p><p><strong>Stratification :</strong> Couches alternées 20-30cm, matières mélangées. Brassage initial, homogénéité recherchée, fermentation uniforme.</p><h3>Gestion active</h3><p><strong>Retournements :</strong> J7, J14, J21, puis mensuels. Oxygénation forcée, température relancée, décomposition accélérée remarquablement.</p><p><strong>Contrôle humidité :</strong> 50-60% optimal, test poignée. Arrosage si sec, brassage si compact, équilibre maintenu.</p><h3>Maturation finale</h3><p><strong>Affinage :</strong> 2-3 mois supplémentaires, température ambiante. Stabilisation biologique, maturité atteinte, utilisation sécurisée possible.</p><p><strong>Test maturité :</strong> Odeur terre, couleur brune, structure grumeleuse. Graines radis germées, phytotoxicité absente, qualité confirmée.</p>',
                'excerpt' => 'Compost thermophile en andains : technique accélérée 3 mois. Retournements, aération, pathogènes détruits, humus riche.',
                'meta_title' => 'Compost Thermophile : Technique des Andains | FarmShop',
                'meta_description' => 'Guide compost thermophile : andains, retournements, 3 mois. Décomposition accélérée, pathogènes détruits.',
                'tags' => ['compost thermophile', 'andains', 'retournements', 'accéléré', 'pathogènes'],
                'reading_time' => 8
            ],
            [
                'category' => 'Compostage et Recyclage',
                'title' => 'Valoriser déchets verts : broyage et mulchage',
                'content' => '<h2>Transformer tailles en ressources</h2><p>Broyat, paillis, BRF : déchets taille deviennent amendements précieux. Broyeur adapté, calibrage optimal, utilisations multiples selon diamètres et essences.</p><h3>Matériel de broyage</h3><p><strong>Broyeur thermique :</strong> Branches jusqu\'8cm, débit 2-5m³/h. Moteur puissant, lames rotatives, entretien régulier nécessaire.</p><p><strong>Broyeur électrique :</strong> Branches 4cm, usage domestique. Silencieux, léger, parfait petits jardins, déchiquetage fin.</p><h3>Calibrage selon usages</h3><p><strong>Paillis fin :</strong> 0-2cm, massifs floraux, légumes délicats. Décomposition rapide, nutrition légère, esthétique soignée.</p><p><strong>Mulch grossier :</strong> 2-5cm, arbustes, allées, protection hivernale. Durabilité accrue, structure maintenue, passage possible.</p><h3>BRF spécialisé</h3><p><strong>Rameaux feuillus :</strong> Diamètre 2-7cm, feuilles intégrées. Chêne, charme, fruitiers privilégiés, résineux évités.</p><p><strong>Application :</strong> Automne/hiver, couche 5-10cm, incorporation légère. Champignons développés, sol structuré, fertilité régénérée.</p><h3>Stockage et maturation</h3><p><strong>Tas aérés :</strong> Brassage occasionnel, fermentation contrôlée. Échauffement évité, qualité préservée, utilisations étalées.</p>',
                'excerpt' => 'Valoriser déchets verts : broyage en paillis, BRF, mulch. Transformer tailles en amendements précieux selon calibres.',
                'meta_title' => 'Valoriser Déchets Verts : Broyage et Mulchage | FarmShop',
                'meta_description' => 'Guide valorisation déchets verts : broyage, paillis, BRF. Transformer branches en ressources jardin.',
                'tags' => ['déchets verts', 'broyage', 'paillis', 'BRF', 'mulchage'],
                'reading_time' => 7
            ],
            [
                'category' => 'Compostage et Recyclage',
                'title' => 'Lombricompostage : vers de terre producteurs',
                'content' => '<h2>Alliés invertébrés précieux</h2><p>Eisenia foetida transforme déchets organiques en lombricompost exceptionnel. Appartement, balcon, cave : cette technique compacte produit fertilisant concentré sans odeurs.</p><h3>Installation lombricomposteur</h3><p><strong>Bacs empilables :</strong> 3-4 plateaux, robinet récupération, couvercle hermétique. Progression ascendante, récolte continue, gestion facilitée.</p><p><strong>Vers spécialisés :</strong> Eisenia foetida/andrei, 500g démarrage. Reproduction rapide, adaptation parfaite, efficacité garantie.</p><h3>Alimentation équilibrée</h3><p><strong>Déchets acceptés :</strong> Épluchures, marc café, thé, papier/carton. Petits morceaux, variété importante, suralimentations évitées.</p><p><strong>Déchets refusés :</strong> Agrumes, oignons, viande, produits laitiers. Acidité excessive, odeurs, pathogènes potentiels éliminés.</p><h3>Conditions optimales</h3><p><strong>Température :</strong> 15-25°C constante, variations minimales. Intérieur protégé, cave tempérée, garage hors gel.</p><p><strong>Humidité :</strong> 80-85%, substrat humide non détrempé. Vaporisations légères, drainage prévu, équilibre maintenu.</p><h3>Récolte et usage</h3><p><strong>Lombricompost :</strong> 3-6 mois maturation, texture fine, odeur terre. Fertilisant concentré, rempotage, semis privilégiés.</p><p><strong>Lombrithé :</strong> Jus récupéré, dilution 1/10, arrosage liquide. Stimulant croissance, enracinement, résistance maladies.</p>',
                'excerpt' => 'Lombricompostage avec Eisenia : vers transforment déchets en fertilisant concentré. Technique compacte, sans odeurs.',
                'meta_title' => 'Lombricompostage : Vers de Terre Producteurs | FarmShop',
                'meta_description' => 'Guide lombricompostage : Eisenia, installation, alimentation. Vers transforment déchets en fertilisant concentré.',
                'tags' => ['lombricompostage', 'Eisenia', 'vers de terre', 'fertilisant', 'compost'],
                'reading_time' => 7
            ],
            [
                'category' => 'Compostage et Recyclage',
                'title' => 'Méthanisation domestique : biogaz autonome',
                'content' => '<h2>Énergie verte à domicile</h2><p>Déchets organiques produisent méthane combustible. Petit digesteur domestique fournit gaz cuisson, eau chaude tout recyclant déchets familiales et animales.</p><h3>Principe méthanisation</h3><p><strong>Fermentation anaérobie :</strong> Bactéries décomposent matière organique, méthane dégagé. Cuve étanche, température 35°C, pH neutre maintenu.</p><p><strong>Rendements :</strong> 1kg déchets = 50-200L biogaz. Pouvoir calorifique 6kWh/m³, équivalent 0,6L fioul, autonomie partielle.</p><h3>Installation domestique</h3><p><strong>Digesteur 2-5m³ :</strong> Cuve plastique/béton, étanchéité parfaite. Agitateur manuel, réchauffage solaire, investissement 2000-5000€.</p><p><strong>Gazomètre :</strong> Stockage biogaz, pression régulée, sécurité intégrée. Cloche flottante, volume 1-2m³, distribution contrôlée.</p><h3>Intrants optimaux</h3><p><strong>Fumiers/lisiers :</strong> Base fermentation, C/N équilibré, production régulière. Dilution 1/1, brassage quotidien, température maintenue.</p><p><strong>Déchets cuisine :</strong> Complément nutritif, production renforcée. Hachage fin, introduction progressive, surcharges évitées.</p><h3>Sécurité et entretien</h3><p><strong>Détection fuites :</strong> Méthane explosif, ventilation obligatoire. Détecteurs installés, consignes respectées, maintenance préventive.</p><p><strong>Digestat résiduel :</strong> Fertilisant liquide, azote assimilable, odeurs réduites. Épandage direct, compostage possible, valeur agronomique.</p>',
                'excerpt' => 'Méthanisation domestique : déchets organiques produisent biogaz. Petit digesteur autonome pour gaz cuisson, chauffage.',
                'meta_title' => 'Méthanisation Domestique : Biogaz Autonome | FarmShop',
                'meta_description' => 'Guide méthanisation domestique : digesteur, biogaz, autonomie énergétique. Déchets organiques en énergie verte.',
                'tags' => ['méthanisation', 'biogaz', 'digesteur', 'autonomie énergétique', 'déchets organiques'],
                'reading_time' => 8
            ],
            [
                'category' => 'Compostage et Recyclage',
                'title' => 'Recyclage matériaux : réemploi créatif',
                'content' => '<h2>Donner seconde vie aux déchets</h2><p>Palettes, bidons, pneus, contenants : imagination transforme déchets en outils jardinage. Économies réalisées, créativité exprimée, environnement préservé durablement.</p><h3>Palettes polyvalentes</h3><p><strong>Bacs compostage :</strong> Démontage partiel, reassemblage carré, fond grillagé. Aération naturelle, démontage facile, coût quasi nul.</p><p><strong>Jardinières verticales :</strong> Fixation murale, plantation directe, irrigation intégrée. Espaces restreints, esthétique rustique, productivité surprenante.</p><h3>Contenants réutilisés</h3><p><strong>Bidons plastique :</strong> Découpe créative, drainage perforé, étiquetage. Bacs semis, arrosoirs, stockage graines, polyvalence maximale.</p><p><strong>Pots yaourt :</strong> Semis individuels, repiquage facilité, coût minimal. Perçage drainage, étiquetage variétés, réutilisation multiple.</p><h3>Pneus détournés</h3><p><strong>Bacs plantation :</strong> Empilage stable, volume important, drainage naturel. Pommes terre, aromates, esthétique discutable mais efficace.</p><p><strong>Composteurs :</strong> Perforation latérale, aération excellente, retournement aisé. Installation rapide, maintenance simple, durabilité assurée.</p><h3>Sécurité réemploi</h3><p><strong>Nettoyage préalable :</strong> Décontamination complète, résidus chimiques éliminés. Usage alimentaire vérifié, risques sanitaires écartés impérativement.</p>',
                'excerpt' => 'Recyclage créatif matériaux : palettes, bidons, pneus transformés. Seconde vie déchets en outils jardinage économiques.',
                'meta_title' => 'Recyclage Matériaux : Réemploi Créatif | FarmShop',
                'meta_description' => 'Guide recyclage créatif : palettes, contenants, réemploi jardinage. Transformer déchets en outils utiles.',
                'tags' => ['recyclage créatif', 'palettes', 'réemploi', 'bidons', 'économies'],
                'reading_time' => 7
            ]
        ];
    }
}
