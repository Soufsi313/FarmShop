<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\User;

class BlogPostBatch3Seeder extends Seeder
{
    /**
     * Seeder pour 4 catégories : Materiel et Outils, Agenda du Fermier, Conservation et Transformation, Recettes de Saison
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
        $startDate = Carbon::now()->subMonths(8); // Décaler par rapport aux batches précédents

        foreach ($articles as $index => $article) {
            $category = $categories->get($article['category']);
            
            if (!$category) {
                $this->command->warn("Catégorie '{$article['category']}' non trouvée pour l'article '{$article['title']}'");
                continue;
            }

            $publishedAt = $startDate->copy()->addDays($index * 4); // Étaler sur 8 mois
            
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
                    'batch' => 3,
                    'featured' => $index < 4,
                    'priority' => $index < 2 ? 'high' : 'normal'
                ],
                'tags' => $article['tags'],
                'views_count' => rand(45, 750),
                'likes_count' => rand(5, 65),
                'shares_count' => rand(1, 25),
                'comments_count' => rand(0, 20),
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

        $this->command->info("✅ Batch 3 : {$publishedCount} articles créés pour 4 catégories !");
    }

    private function getArticles()
    {
        return [
            // Materiel et Outils (5 articles)
            [
                'category' => 'Materiel et Outils',
                'title' => 'Outils de jardinage : guide d\'achat malin',
                'content' => '<h2>Investir judicieusement dans ses outils</h2><p>Qualité plutôt que quantité : 10 outils bien choisis suffisent pour 90% des travaux. Privilégiez durabilité et ergonomie pour jardiner efficacement sans se ruiner.</p><h3>Les indispensables (budget 150€)</h3><p><strong>Bêche :</strong> Acier trempé, manche bois 28cm, emmanchement solide. Marques Opinel, Leborgne garantissent 20 ans utilisation.</p><p><strong>Sécateur :</strong> Bypass pour bois vert, enclume pour bois sec. Felco modèle 2 : référence professionnelle, pièces détachées disponibles.</p><h3>Confort et efficacité</h3><p><strong>Bêche fourche :</strong> Sol lourd, moins d\'effort qu\'une bêche classique. Dents recourbées pénètrent facilement, ameublissement parfait.</p><p><strong>Binette :</strong> Lame affûtée 14cm, manche 130cm évite mal de dos. Sarclage précis, buttage, création sillons semis.</p><h3>Entretien des outils</h3><p><strong>Nettoyage :</strong> Terre enlevée après usage, huilage parties métalliques. Affûtage annuel lime, rangement sec aéré.</p><p><strong>Réparations :</strong> Manches cassés remplaçables, vis ressserrées régulièrement. Outils bien entretenus = investissement durable.</p>',
                'excerpt' => 'Guide d\'achat outils de jardinage : 10 indispensables, budget 150€, qualité vs quantité. Investir malin.',
                'meta_title' => 'Outils de Jardinage : Guide d\'Achat Malin | FarmShop',
                'meta_description' => 'Guide achat outils jardinage : 10 indispensables, budget optimal, qualité. Investir intelligemment pour jardiner efficacement.',
                'tags' => ['outils jardinage', 'guide achat', 'équipement', 'budget', 'qualité'],
                'reading_time' => 7
            ],
            [
                'category' => 'Materiel et Outils',
                'title' => 'Serre de jardin : choisir selon ses besoins',
                'content' => '<h2>Étendre sa saison de culture</h2><p>Tunnel plastique, serre polycarbonate ou verre : chaque structure répond à des besoins spécifiques. Analysez usage prévu et budget pour choisir votre prolongement de saison.</p><h3>Serres économiques (100-300€)</h3><p><strong>Tunnel plastique :</strong> 2x3m, bâches 200 microns, structure acier galvanisé. Parfait débutants, cultures précoces, hivernage plantes.</p><p><strong>Mini-serre châssis :</strong> 1x1m, semis protégés, repiquages. Transportable, montage 30 minutes, excellent rapport qualité-prix.</p><h3>Serres intermédiaires (500-1500€)</h3><p><strong>Polycarbonate alvéolaire :</strong> Isolation thermique excellente, résistance grêle. 6mm épaisseur minimum, garantie 10 ans anti-UV.</p><p><strong>Structure aluminium :</strong> Légère, inoxydable, assemblage simple. Fondations béton recommandées, ancrage indispensable.</p><h3>Aménagement intérieur</h3><p><strong>Tablettes :</strong> Étagement culture, optimisation espace. Hauteurs réglables, matériaux imputrescibles conseillés.</p><p><strong>Ventilation :</strong> Ouvertures automatiques, régulation température. Éviter condensation, champignons, coup de chaleur estival.</p>',
                'excerpt' => 'Choisir sa serre de jardin : tunnel, polycarbonate, verre. Selon budget et besoins, étendre saison culture.',
                'meta_title' => 'Serre de Jardin : Choisir Selon ses Besoins | FarmShop',
                'meta_description' => 'Guide choix serre jardin : tunnel, polycarbonate, verre. Budget, usage, aménagement pour étendre saison.',
                'tags' => ['serre jardin', 'tunnel plastique', 'polycarbonate', 'cultures protégées', 'saison'],
                'reading_time' => 8
            ],
            [
                'category' => 'Materiel et Outils',
                'title' => 'Arrosage automatique : économiser l\'eau',
                'content' => '<h2>Irriguer juste et économe</h2><p>Goutte-à-goutte, aspersion, programmateurs : l\'arrosage automatique divise consommation par 2 tout en améliorant résultats. Investissement rapidement rentabilisé.</p><h3>Systèmes goutte-à-goutte</h3><p><strong>Principe :</strong> Eau délivrée lentement aux racines, 0% évaporation. Tuyaux micro-perforés ou goutteurs auto-régulants 2L/h.</p><p><strong>Installation :</strong> Collecteur principal, dérivations secondaires, goutteurs positionnés plantes. Pression régulée 1,5 bars maximum.</p><h3>Arrosage par aspersion</h3><p><strong>Asperseurs rotatifs :</strong> Portée 5-15m, idéal pelouses, grandes surfaces. Programmation zones selon exposition, type végétaux.</p><p><strong>Tuyères escamotables :</strong> Discrètes, arrosage uniforme, parfaites massifs. Installation enterrée, maintenance réduite.</p><h3>Programmation intelligente</h3><p><strong>Programmateurs :</strong> Multi-voies, cycles personnalisés, sonde humidité. Arrosage nocturne évite évaporation, stress hydrique.</p><p><strong>Récupération eau :</strong> Cuves 1000L, pompes immergées, filtration simple. Autonomie 15 jours, écologie + économies.</p>',
                'excerpt' => 'Arrosage automatique économe : goutte-à-goutte, aspersion, programmation. Diviser consommation eau par 2.',
                'meta_title' => 'Arrosage Automatique : Économiser l\'Eau | FarmShop',
                'meta_description' => 'Guide arrosage automatique : goutte-à-goutte, programmateurs, économies eau. Irrigation efficace et écologique.',
                'tags' => ['arrosage automatique', 'goutte à goutte', 'économie eau', 'programmateur', 'irrigation'],
                'reading_time' => 8
            ],
            [
                'category' => 'Materiel et Outils',
                'title' => 'Tondeuse : bien choisir selon son terrain',
                'content' => '<h2>Adapter l\'outil à sa pelouse</h2><p>Surface, relief, obstacles, fréquence tonte : ces critères déterminent type optimal. Manuelle, électrique, thermique ou robot : chaque solution a ses avantages.</p><h3>Tondeuses manuelles (0-500m²)</h3><p><strong>Hélicoïdale :</strong> Coupe nette, silencieuse, écologique. Parfaite gazons fins, tonte régulière obligatoire, entretien minimal.</p><p><strong>Avantages :</strong> Prix 80-200€, exercice physique, précision bordures. Largeur 30-40cm, hauteur réglable, bac ramassage optionnel.</p><h3>Solutions électriques (500-1500m²)</h3><p><strong>Filaire :</strong> Puissance constante, légèreté, prix abordable 150-400€. Longueur câble limitante, rallonges multipliables dangereuses.</p><p><strong>Batterie :</strong> Liberté mouvement, démarrage instantané. Autonomie 45-90min, batteries supplémentaires coûteuses mais pratiques.</p><h3>Tondeuses thermiques (1500m²+)</h3><p><strong>Autotractées :</strong> Terrain en pente, surfaces importantes. Moteur 4 temps fiable, largeur 46-53cm, bac 60-80L.</p><p><strong>Entretien :</strong> Vidange annuelle, bougie, filtre air. Hivernage carburant vidangé, remisage sec aéré indispensable.</p>',
                'excerpt' => 'Choisir sa tondeuse selon terrain : manuelle, électrique, thermique, robot. Surface, relief, fréquence tonte.',
                'meta_title' => 'Tondeuse : Bien Choisir Selon son Terrain | FarmShop',
                'meta_description' => 'Guide choix tondeuse : manuelle, électrique, thermique. Adapter selon surface, relief et usage.',
                'tags' => ['tondeuse', 'pelouse', 'terrain', 'électrique', 'thermique'],
                'reading_time' => 7
            ],
            [
                'category' => 'Materiel et Outils',
                'title' => 'Composteur : 5 modèles selon l\'espace',
                'content' => '<h2>Composter partout, même en ville</h2><p>Balcon, petit jardin ou grand terrain : solutions adaptées existent pour recycler déchets organiques. Du lombricomposteur au silo 800L, trouvez votre modèle.</p><h3>Compostage urbain (balcon, terrasse)</h3><p><strong>Lombricomposteur :</strong> 40x60cm, 3 plateaux, vers Eisenia. Compost en 3-4 mois, pas d\'odeur si bien géré.</p><p><strong>Bokashi :</strong> Fermentation anaérobie, seau étanche 15L. Micro-organismes efficaces, tous déchets acceptés, même cuits.</p><h3>Jardins moyens (200-500m²)</h3><p><strong>Composteur plastique :</strong> 300-600L, montage facile, trappe récupération. Couvercle anti-rongeurs, aération latérale optimisée.</p><p><strong>Bac bois :</strong> Pin traité autoclave, 400-800L. Esthétique naturelle, démontage facile, durée vie 10 ans.</p><h3>Grands espaces (500m²+)</h3><p><strong>Compostage en tas :</strong> Gratuit, volumes importants, brassage facile. Emplacement mi-ombre, sur terre nue obligatoire.</p><p><strong>Silo grillagé :</strong> 1m³, aération parfaite, évolution visible. Grillage galvanisé maille 25mm, poteaux béton scellés.</p>',
                'excerpt' => 'Choisir son composteur selon l\'espace : lombricomposteur, bac bois, silo. Du balcon au grand jardin.',
                'meta_title' => 'Composteur : 5 Modèles Selon l\'Espace | FarmShop',
                'meta_description' => 'Guide composteurs : lombricomposteur balcon, bacs jardins, silos grands espaces. Composter partout.',
                'tags' => ['composteur', 'compostage', 'lombricomposteur', 'déchets verts', 'recyclage'],
                'reading_time' => 6
            ],

            // Agenda du Fermier (5 articles)
            [
                'category' => 'Agenda du Fermier',
                'title' => 'Calendrier des semis : légumes mois par mois',
                'content' => '<h2>Semer au bon moment</h2><p>Chaque légume a sa période optimale : trop tôt = échec, trop tard = rendement médiocre. Ce calendrier garantit semis réussis et récoltes étalées toute l\'année.</p><h3>Printemps précoce (février-avril)</h3><p><strong>Février :</strong> Radis sous tunnel, fèves pleine terre zones douces. Semis intérieur : tomates, aubergines, poivrons à 20°C constant.</p><p><strong>Mars :</strong> Épinards, laitues, petits pois direct. Repiquage sous abri plants sensibles, protection voile anti-insectes.</p><p><strong>Avril :</strong> Haricots verts après gelées, courgettes sous cloche. Carotte hâtive, betterave rouge, radis échelonnés.</p><h3>Cœur de saison (mai-juillet)</h3><p><strong>Mai :</strong> Plantation tomates, basilic, concombres après Saints de Glace. Haricots grimpants, maïs doux, tournesols.</p><p><strong>Juin :</strong> Chicorées d\'automne, choux d\'hiver, poireaux. Derniers semis courgettes, succession haricots verts.</p><p><strong>Juillet :</strong> Navets, radis d\'hiver, épinards automne. Engrais verts (moutarde, phacélie) parcelles libérées.</p><h3>Préparation hiver (août-octobre)</h3><p><strong>Août :</strong> Mâche, roquette, chicorée pain de sucre. Plantations choux, poireaux pour récoltes hivernales.</p>',
                'excerpt' => 'Calendrier semis légumes mois par mois : printemps, été, automne. Semer au bon moment pour réussir.',
                'meta_title' => 'Calendrier des Semis : Légumes Mois par Mois | FarmShop',
                'meta_description' => 'Calendrier semis légumes : périodes optimales, semis échelonnés. Guide mois par mois pour récoltes réussies.',
                'tags' => ['calendrier semis', 'légumes', 'saisonnalité', 'périodes semis', 'potager'],
                'reading_time' => 9
            ],
            [
                'category' => 'Agenda du Fermier',
                'title' => 'Légumes d\'hiver : cultiver sous la neige',
                'content' => '<h2>Le potager ne s\'arrête jamais</h2><p>Poireaux, choux, mâche, épinards : ces légumes bravent le froid et fournissent vitamines fraîches en plein hiver. Protection adaptée et variétés rustiques sont les clés.</p><h3>Champions du froid</h3><p><strong>Poireau :</strong> Résiste -15°C, récolte décembre-mars. Variétés "Bleu de Solaise", "Monstrueux de Carentan" extra-rustiques.</p><p><strong>Chou de Bruxelles :</strong> Améliore au gel, récolte progressive. Cueillette bas vers haut, pommes plus tendres après gelée.</p><h3>Protections efficaces</h3><p><strong>Paillage épais :</strong> 15cm paille/feuilles, protection racines. Évite gel/dégel destructeur, facilite arrachage terre gelée.</p><p><strong>Voile hivernal :</strong> P30 double épaisseur, +4°C gagné. Aération régulière évite condensation, moisissures nuisibles.</p><h3>Récoltes d\'urgence</h3><p><strong>Mâche :</strong> Rosettes croquantes même gelées. Récolte matin avant dégel, conservation réfrigérateur 1 semaine.</p><p><strong>Épinards géants :</strong> Feuilles plus épaisses qu\'été. Cuisson vapeur préserve vitamines, fer, acide folique concentrés.</p>',
                'excerpt' => 'Légumes d\'hiver résistants au froid : poireaux, choux, mâche. Cultiver et récolter sous la neige.',
                'meta_title' => 'Légumes d\'Hiver : Cultiver Sous la Neige | FarmShop',
                'meta_description' => 'Guide légumes d\'hiver : variétés rustiques, protections, récoltes. Potager productif même sous la neige.',
                'tags' => ['légumes hiver', 'froid', 'protection', 'rustiques', 'poireaux'],
                'reading_time' => 7
            ],
            [
                'category' => 'Agenda du Fermier',
                'title' => 'Fruits de saison : calendrier de récolte',
                'content' => '<h2>Cueillir au moment parfait</h2><p>Chaque fruit a son pic de maturité : fraises en juin, pêches en août, pommes en octobre. Respecter ces rythmes naturels garantit saveurs optimales et conservation prolongée.</p><h3>Fruits rouges précoces</h3><p><strong>Fraises :</strong> Mai-juillet selon variétés. Test maturité : rouge uniforme, détachement facile. Cueillette matin, fraîcheur préservée.</p><p><strong>Groseilles :</strong> Juillet, grappes translucides à point. Congélation possible grappes entières, gelées savoureuses garanties.</p><h3>Fruits d\'été généreux</h3><p><strong>Pêches/Abricots :</strong> Juillet-août, parfum intense signe maturité. Légère pression doigt, chair cède sans s\'écraser.</p><p><strong>Prunes :</strong> Août-septembre, pruine blanchâtre préservée. Variétés échelonnées allongent période, transformation possible.</p><h3>Récoltes automnales</h3><p><strong>Pommes :</strong> Septembre-novembre selon variétés. Test chute naturelle, pépins bruns à maturité. Conservation cave 6 mois possible.</p><p><strong>Poires :</strong> Récolte avant maturité complète, murissement post-récolte. Chair ferme cueillette, tendresse après quelques jours.</p><h3>Conservation optimale</h3><p>Réfrigération immédiate fruits tendres, cave fraîche fruits garde. Éviter mélange espèces (éthylène accélère vieillissement).</p>',
                'excerpt' => 'Calendrier récolte fruits de saison : fraises juin, pêches août, pommes octobre. Cueillir au moment parfait.',
                'meta_title' => 'Fruits de Saison : Calendrier de Récolte | FarmShop',
                'meta_description' => 'Calendrier récolte fruits : périodes optimales, signes maturité, conservation. Cueillir au bon moment.',
                'tags' => ['fruits saison', 'récolte', 'maturité', 'conservation', 'calendrier'],
                'reading_time' => 8
            ],
            [
                'category' => 'Agenda du Fermier',
                'title' => 'Préparations hivernales : protéger le jardin',
                'content' => '<h2>Anticiper les rigueurs hivernales</h2><p>Novembre-décembre : période cruciale protection plantes sensibles. Paillage, voilage, rentrée pots transforment jardin fragile en oasis résistante au gel.</p><h3>Protection au sol</h3><p><strong>Paillage généralisé :</strong> Couche 15-20cm, matériaux isolants. Feuilles mortes broyées, paille, écorces selon disponibilité régionale.</p><p><strong>Buttage :</strong> Rosiers, artichauts, figuiers zones limites. Terre rapportée 30cm, protection cœur/greffe, débuttage mars.</p><h3>Voilages et abris</h3><p><strong>Voile hivernage :</strong> P30 double pour -5°C, P50 pour -8°C. Fixation solide, aération base évite condensation destructrice.</p><p><strong>Abris temporaires :</strong> Cagettes retournées, cartons, film bulle. Protection courte durée, surveillance météo obligatoire.</p><h3>Plantes en pots</h3><p><strong>Rentrée sélective :</strong> Agrumes, lauriers-roses, plantes méditerranéennes. Local hors gel, arrosage très réduit, surveillance parasites.</p><p><strong>Protection sur place :</strong> Film bulle pots, surélévation drainage. Éviter contact sol gelé, regroupement économise protection.</p>',
                'excerpt' => 'Préparations hivernales jardin : paillage, voilage, protection plantes. Anticiper gel et rigueurs.',
                'meta_title' => 'Préparations Hivernales : Protéger le Jardin | FarmShop',
                'meta_description' => 'Guide préparations hivernales : protection gel, paillage, voilage. Préparer jardin avant l\'hiver.',
                'tags' => ['préparations hiver', 'protection gel', 'paillage', 'voilage', 'hivernage'],
                'reading_time' => 7
            ],
            [
                'category' => 'Agenda du Fermier',
                'title' => 'Jardiner selon la lune : mythe ou réalité ?',
                'content' => '<h2>Décrypter l\'influence lunaire</h2><p>Lune montante/descendante, croissante/décroissante : ces cycles influencent-ils vraiment végétaux ? Entre tradition ancestrale et études scientifiques, faisons le point objectivement.</p><h3>Principes du calendrier lunaire</h3><p><strong>Lune montante :</strong> Sève monte, période favorable semis/greffes. Observation empirique : germination accélérée, reprise racinaire améliorée.</p><p><strong>Lune descendante :</strong> Sève descend, travail sol/plantations. Enracinement favorisé, cicatrisation taille plus rapide constatée.</p><h3>Applications pratiques</h3><p><strong>Semis :</strong> Lune croissante/montante traditionnellement recommandée. Tests comparatifs : différences marginales, autres facteurs prépondérants.</p><p><strong>Taille :</strong> Lune descendante limite montée sève. Cicatrisation observée effectivement plus rapide, saignement réduit.</p><h3>Approche scientifique</h3><p><strong>Études contrôlées :</strong> Résultats contradictoires, effets non reproductibles systématiquement. Variables climatiques masquent influences lunaires supposées.</p><p><strong>Gravitation :</strong> Force lunaire 300 000 fois plus faible que terrestre. Impact direct sur végétaux hautement improbable physiquement.</p><h3>Conclusion nuancée</h3><p>Calendrier lunaire structure planning, observation fine favorise. Même si influence discutable, méthode encourage régularité bénéfique.</p>',
                'excerpt' => 'Jardiner selon la lune : influence réelle ou mythe ? Analyse scientifique des cycles lunaires au jardin.',
                'meta_title' => 'Jardiner Selon la Lune : Mythe ou Réalité ? | FarmShop',
                'meta_description' => 'Jardinage lunaire : analyse cycles, influence végétaux, études scientifiques. Mythe ou réalité ?',
                'tags' => ['jardinage lunaire', 'cycles lune', 'influence', 'scientifique', 'tradition'],
                'reading_time' => 8
            ],

            // Conservation et Transformation (5 articles)
            [
                'category' => 'Conservation et Transformation',
                'title' => 'Conserver légumes racines : cave et sable',
                'content' => '<h2>Stocker sa récolte automnale</h2><p>Carottes, betteraves, navets, radis noirs : ces légumes se conservent 6-8 mois en cave fraîche. Technique du sable humide reproduit conditions souterraines idéales.</p><h3>Préparation avant stockage</h3><p><strong>Récolte optimale :</strong> Avant gelées sévères, temps sec 2 jours. Arrachage délicat, terre éliminée sans lavage, fanes coupées 2cm.</p><p><strong>Tri sélectif :</strong> Calibres uniformes, pas de blessures/maladies. Consommation immédiate légumes abîmés, stockage parfaits uniquement.</p><h3>Technique du sable</h3><p><strong>Préparation :</strong> Sable rivière lavé, humidité équivalente sponge essorée. Caisses bois/plastique, drainage trous fonds.</p><p><strong>Stratification :</strong> Couche sable 5cm, rangée légumes sans contact, sable recouvrement. Alternance jusqu\'au remplissage complet.</p><h3>Conditions optimales</h3><p><strong>Cave idéale :</strong> Température 2-4°C, humidité 85-90%, obscurité totale. Ventilation légère, pas de chauffage/gel.</p><p><strong>Surveillance :</strong> Contrôle mensuel, élimination pourris. Humidification sable si dessèchement, aération si moisi.</p><h3>Variétés recommandées</h3><p><strong>Carottes :</strong> "Colmar", "Touchon" conservation excellente. <strong>Betteraves :</strong> "Crapaudine", "Chioggia" goût préservé.</p>',
                'excerpt' => 'Conserver légumes racines 6-8 mois : technique sable humide, cave fraîche. Carottes, betteraves parfaitement stockées.',
                'meta_title' => 'Conserver Légumes Racines : Cave et Sable | FarmShop',
                'meta_description' => 'Guide conservation légumes racines : technique sable, cave fraîche, stockage 6-8 mois. Carottes, betteraves.',
                'tags' => ['conservation légumes', 'légumes racines', 'stockage', 'cave', 'sable'],
                'reading_time' => 8
            ],
            [
                'category' => 'Conservation et Transformation',
                'title' => 'Congélation légumes : techniques et astuces',
                'content' => '<h2>Préserver saveurs et vitamines</h2><p>Blanchiment, portions adaptées, étiquetage précis : la congélation maîtrisée conserve légumes 10-12 mois. Techniques spécifiques selon chaque famille végétale.</p><h3>Règles générales</h3><p><strong>Fraîcheur maximale :</strong> Congélation dans 2h après récolte/achat. Qualité initiale détermine résultat final, pas rattrapage possible.</p><p><strong>Blanchiment obligatoire :</strong> Ébouillantage 2-5min selon légume, refroidissement eau glacée. Stoppe enzymes, préserve couleur/texture/vitamines.</p><h3>Techniques spécialisées</h3><p><strong>Haricots verts :</strong> Équeutage, blanchiment 3min, séchage parfait. Sachets portions 500g, pas de sur-emballage nécessaire.</p><p><strong>Épinards :</strong> Blanchiment 1min, essorage poussé, boules compactes. Décongélation express possible, usage soupes/gratins.</p><h3>Légumes sans blanchiment</h3><p><strong>Courgettes :</strong> Râpées crues, égouttage sel 30min. Portions sachets, usage galettes/gratins exclusivement.</p><p><strong>Champignons :</strong> Émincés crus, citron anti-oxydant. Congélation plateau puis sachets, usage cuissons directes.</p><h3>Organisation congélateur</h3><p><strong>Étiquetage :</strong> Date, contenu, poids visible. Rotation stocks, premiers entrés = premiers sortis impératif.</p>',
                'excerpt' => 'Congélation légumes réussie : blanchiment, portions, techniques. Préserver saveurs et vitamines 10-12 mois.',
                'meta_title' => 'Congélation Légumes : Techniques et Astuces | FarmShop',
                'meta_description' => 'Guide congélation légumes : blanchiment, portions, conservation 10-12 mois. Techniques préservation saveurs.',
                'tags' => ['congélation légumes', 'blanchiment', 'conservation', 'techniques', 'congélateur'],
                'reading_time' => 7
            ],
            [
                'category' => 'Conservation et Transformation',
                'title' => 'Séchage et déshydratation : méthodes naturelles',
                'content' => '<h2>Concentrer saveurs et nutriments</h2><p>Solaire, four, déshydrateur : le séchage multiplie par 10 la durée conservation. Aromates, légumes, fruits transformés en réserves concentrées savoureuses.</p><h3>Séchage solaire gratuit</h3><p><strong>Aromates :</strong> Bouquets suspendus, lieu sec/ventilé/ombragé. Basilic, persil, thym sèchent 8-15 jours selon hygrométrie.</p><p><strong>Séchoir artisanal :</strong> Cadre bois, grillage fin, orientation sud. Protection pluie indispensable, brassage air favorable.</p><h3>Techniques four domestique</h3><p><strong>Température basse :</strong> 60°C maximum, porte entrouverte évacuation humidité. Surveillance régulière évite sur-cuisson destructrice.</p><p><strong>Tomates :</strong> Demi-tomates salées, 6-8h séchage. Conservation huile olive, saveur italienne authentique garantie.</p><h3>Déshydrateur électrique</h3><p><strong>Avantages :</strong> Température contrôlée, ventilation forcée, plateaux empilables. Séchage uniforme, économique long terme.</p><p><strong>Courgettes :</strong> Lamelles 5mm, sel léger 30min. Chips croustillantes, apéritifs sains, conservation bocaux hermétiques.</p><h3>Conservation optimale</h3><p><strong>Séchage complet :</strong> Cassant/friable = réussi, souple = insuffisant. Bocaux verre, sachets absorbeurs humidité conseillés.</p>',
                'excerpt' => 'Séchage légumes et aromates : solaire, four, déshydrateur. Concentrer saveurs, conservation naturelle longue durée.',
                'meta_title' => 'Séchage et Déshydratation : Méthodes Naturelles | FarmShop',
                'meta_description' => 'Guide séchage légumes : techniques solaire, four, déshydrateur. Conservation naturelle et concentration saveurs.',
                'tags' => ['séchage légumes', 'déshydratation', 'conservation', 'aromates', 'séchage solaire'],
                'reading_time' => 8
            ],
            [
                'category' => 'Conservation et Transformation',
                'title' => 'Lactofermentation : légumes probiotiques',
                'content' => '<h2>Transformer en super-aliments</h2><p>Choucroute, kimchi, cornichons : la lactofermentation transforme légumes frais en probiotiques bénéfiques. Technique ancestrale simple, résultats nutritionnels exceptionnels.</p><h3>Principe de fermentation</h3><p><strong>Bactéries lactiques :</strong> Transformation sucres en acide lactique, pH abaissé. Milieu hostile pathogènes, conservation naturelle 6-12 mois.</p><p><strong>Conditions :</strong> Anaérobie stricte, sel 2-3%, température 18-22°C. Fermentation active 3-7 jours, maturation 3-4 semaines.</p><h3>Choucroute maison</h3><p><strong>Ingrédients :</strong> Chou blanc, sel marin 2%, bocaux ébouillantés. Râpage fin, malaxage sel, tassement hermétique.</p><p><strong>Technique :</strong> Couches tassées, saumure recouvrement, poids maintien immersion. Fermentation 3 semaines, contrôle goût évolution.</p><h3>Légumes variés</h3><p><strong>Carottes râpées :</strong> Sel 3%, épices (cumin, coriandre). Fermentation rapide 5 jours, croquant préservé.</p><p><strong>Radis roses :</strong> Lamelles fines, gingembre frais, fermentation 2 semaines. Probiotiques + vitamines concentrées.</p><h3>Réussite garantie</h3><p><strong>Hygiène :</strong> Ustensiles ébouillantés, mains propres, légumes frais. Sel pur (pas iodé), eau non chlorée.</p>',
                'excerpt' => 'Lactofermentation légumes : choucroute, kimchi, probiotiques. Transformer en super-aliments nutritionnels.',
                'meta_title' => 'Lactofermentation : Légumes Probiotiques | FarmShop',
                'meta_description' => 'Guide lactofermentation : choucroute, légumes fermentés, probiotiques. Transformation nutritionnelle ancestrale.',
                'tags' => ['lactofermentation', 'choucroute', 'probiotiques', 'fermentation', 'conservation'],
                'reading_time' => 8
            ],
            [
                'category' => 'Conservation et Transformation',
                'title' => 'Stockage graines : maintenir viabilité',
                'content' => '<h2>Préserver semences pour l\'avenir</h2><p>Humidité contrôlée, température stable, contenants hermétiques : stockage optimal maintient pouvoir germinatif 5-10 ans. Autonomie semencière et biodiversité préservées.</p><h3>Récolte et préparation</h3><p><strong>Maturité complète :</strong> Graines bien formées, sèches naturellement. Tomates très mûres, courges stockées 1 mois, haricots gousses brunes.</p><p><strong>Séchage parfait :</strong> Étalement 15 jours, brassage quotidien. Test cassure nette, pas de pliage, stockage seulement si parfait.</p><h3>Conditionnement optimal</h3><p><strong>Contenants :</strong> Bocaux verre, sachets kraft, étiquetage précis. Date récolte, variété, pourcentage germination si testé.</p><p><strong>Silice gel :</strong> Sachets absorbeurs 5-10% poids graines. Humidité maintenue <5%, viabilité maximale préservée.</p><h3>Conditions stockage</h3><p><strong>Température :</strong> Fraîche stable 5-10°C, éviter variations. Réfrigérateur possible, congélateur pour très long terme.</p><p><strong>Obscurité :</strong> Lumière dégrade rapidement graines. Placards, caves, éviter greniers surchauffés été.</p><h3>Tests viabilité</h3><p><strong>Germination :</strong> 10 graines, papier humide, 7-14 jours observation. Taux >70% = excellent, 50-70% = correct, <50% = renouveler.</p>',
                'excerpt' => 'Stockage graines optimal : humidité contrôlée, température stable. Maintenir viabilité 5-10 ans, autonomie semencière.',
                'meta_title' => 'Stockage Graines : Maintenir Viabilité | FarmShop',
                'meta_description' => 'Guide stockage graines : conditions optimales, viabilité 5-10 ans. Préserver semences et autonomie.',
                'tags' => ['stockage graines', 'semences', 'viabilité', 'conservation', 'autonomie'],
                'reading_time' => 7
            ],

            // Recettes de Saison (5 articles)
            [
                'category' => 'Recettes de Saison',
                'title' => 'Cuisiner fanes et épluchures : anti-gaspi créatif',
                'content' => '<h2>Transformer déchets en délices</h2><p>Fanes carottes, épluchures pommes de terre, cosses petits pois : ces "déchets" regorgent vitamines et saveurs. Pestos, chips, soupes créatives réduisent gaspillage 30%.</p><h3>Fanes aromatiques</h3><p><strong>Pesto de fanes :</strong> Carottes/radis/betteraves, pignons, parmesan, huile olive. Mixer grossièrement, assaisonnement généreux, conservation réfrigérateur 1 semaine.</p><p><strong>Soupe verdure :</strong> Pommes de terre, fanes variées, bouillon légumes. Cuisson 20min, mixage, crème fraîche finition, persil ciselé.</p><h3>Épluchures transformées</h3><p><strong>Chips pommes terre :</strong> Épluchures lavées, séchées, four 180°C 15min. Huile légère, sel fin, apéritif original/économique.</p><p><strong>Bouillon zéro déchet :</strong> Parures légumes, herbes fanées, eau frémissante 1h. Filtrage, congélation portions, base soupes/risottos.</p><h3>Cosses et tiges</h3><p><strong>Velouté cosses :</strong> Petits pois, oignon, bouillon, cuisson 15min. Mixage fin, filtrage optionnel, goût délicat surprenant.</p><p><strong>Salade tiges :</strong> Brocolis pelées, blanchiment 3min. Vinaigrette moutarde, graines tournesol, texture croquante originale.</p><h3>Conseils sécurité</h3><p><strong>Légumes bio :</strong> Privilégier pour épluchures, pesticides concentrés surface. Lavage soigneux, éviter légumes anciens/abîmés.</p>',
                'excerpt' => 'Cuisiner fanes et épluchures : pestos, chips, soupes anti-gaspi. Transformer déchets en délices créatifs.',
                'meta_title' => 'Cuisiner Fanes et Épluchures : Anti-Gaspi Créatif | FarmShop',
                'meta_description' => 'Recettes anti-gaspi : fanes, épluchures, cosses. Transformer déchets légumes en plats délicieux.',
                'tags' => ['anti-gaspi', 'fanes', 'épluchures', 'recettes', 'zéro déchet'],
                'reading_time' => 7
            ],
            [
                'category' => 'Recettes de Saison',
                'title' => 'Légumes lactofermentés : idées recettes',
                'content' => '<h2>Probiotiques savoureux au quotidien</h2><p>Choucroute, kimchi, pickles : ces légumes fermentés enrichissent plats et intestins. Associations créatives, techniques cuisson adaptées révèlent leurs potentiels.</p><h3>Accompagnements classiques</h3><p><strong>Choucroute garnie :</strong> Porc fumé, saucisses, baies genièvre, vin blanc sec. Cuisson douce 2h, acidité équilibrée, générosité traditionnelle.</p><p><strong>Salade kimchi :</strong> Chou fermenté coréen, concombre, sésame grillé. Sauce soja, huile sésame, piment doux, fraîcheur relevée.</p><h3>Intégration plats chauds</h3><p><strong>Soupe miso-légumes :</strong> Bouillon dashi, radis fermentés, tofu soyeux. Cuisson minimale préserve probiotiques, saveur umami développée.</p><p><strong>Gratin dauphinois :</strong> Pommes terre, choucroute égouttée, crème, gruyère. Acidité équilibre richesse, digestion facilitée, originalité garantie.</p><h3>Condiments et sauces</h3><p><strong>Vinaigrette probiotique :</strong> Jus fermentation, huile noix, moutarde grain. Émulsification délicate, vitamines préservées, intestins choyés.</p><p><strong>Tartare légumes :</strong> Betteraves/carottes fermentées hachées, câpres, échalotes. Fraîcheur acidulée, couleurs vives, nutrition dense.</p><h3>Dosage et équilibre</h3><p><strong>Quantités :</strong> 50-100g/personne suffisent, goût concentré. Introduction progressive, adaptation flore intestinale, bienfaits progressifs.</p>',
                'excerpt' => 'Recettes légumes lactofermentés : choucroute, kimchi, accompagnements. Probiotiques savoureux au quotidien.',
                'meta_title' => 'Légumes Lactofermentés : Idées Recettes | FarmShop',
                'meta_description' => 'Recettes légumes fermentés : choucroute, kimchi, accompagnements probiotiques. Cuisine santé savoureuse.',
                'tags' => ['légumes fermentés', 'choucroute', 'kimchi', 'probiotiques', 'recettes'],
                'reading_time' => 8
            ],
            [
                'category' => 'Recettes de Saison',
                'title' => 'Confitures originales : légumes sucrés',
                'content' => '<h2>Sucré-salé végétal audacieux</h2><p>Courgettes-citron, tomates vertes, betteraves-orange : ces confitures végétales surprennent et régalent. Techniques adaptées révèlent saveurs insoupçonnées, textures originales.</p><h3>Courgettes métamorphosées</h3><p><strong>Confiture courgette-citron :</strong> 1kg courgettes râpées, 600g sucre, 2 citrons bio. Dégorgeage sel 2h, cuisson douce, gélifiant naturel pectine.</p><p><strong>Technique :</strong> Évaporation lente, cuisson 45min, test assiette froide. Texture confiture classique, goût citronné dominant.</p><h3>Tomates vertes recyclées</h3><p><strong>Confiture tomates-pommes :</strong> Tomates vertes fin saison, pommes, gingembre frais. Proportions égales, acidité pommes équilibre, épices réchauffent.</p><p><strong>Cuisson :</strong> Dés 1cm, sucre 24h macération. Cuisson vive début, réduction douce, consistance parfaite.</p><h3>Betteraves colorées</h3><p><strong>Confiture betterave-orange :</strong> Betteraves cuites râpées, oranges entières, sucre gélifiant. Couleur rubis spectaculaire, goût terreux-sucré unique.</p><h3>Associations créatives</h3><p><strong>Carottes-gingembre :</strong> Carottes nouvelles, gingembre confit, cardamome. Exotisme garanti, vitamines préservées, originalité absolue.</p><h3>Conservation et dégustation</h3><p><strong>Stérilisation :</strong> Bocaux ébouillantés, retournement refroidissement. Conservation 1 an, texture maintenue, saveurs développées.</p>',
                'excerpt' => 'Confitures légumes originales : courgettes-citron, tomates vertes, betteraves. Sucré-salé végétal audacieux.',
                'meta_title' => 'Confitures Originales : Légumes Sucrés | FarmShop',
                'meta_description' => 'Recettes confitures légumes : courgettes, tomates vertes, betteraves. Sucré-salé original et savoureux.',
                'tags' => ['confitures légumes', 'courgettes', 'tomates vertes', 'sucré-salé', 'originalité'],
                'reading_time' => 7
            ],
            [
                'category' => 'Recettes de Saison',
                'title' => 'Légumes anciens : redécouvrir les saveurs',
                'content' => '<h2>Patrimoine culinaire retrouvé</h2><p>Panais, rutabaga, topinambour, crosne : ces légumes oubliés reviennent en force. Techniques cuisson adaptées, associations modernes révèlent potentiel gastronomique insoupçonné.</p><h3>Racines rustiques</h3><p><strong>Panais rôtis :</strong> Bâtonnets épais, huile olive, thym, four 200°C 30min. Caramélisation extérieure, cœur fondant, douceur naturelle révélée.</p><p><strong>Purée rutabaga :</strong> Cuisson vapeur 25min, beurre salé, muscade râpée. Texture veloutée, goût subtil navet-chou, accompagnement gibier parfait.</p><h3>Tubercules surprenants</h3><p><strong>Topinambours sautés :</strong> Lamelles fines, ail, persil, cuisson vive 8min. Croquant maintenu, goût artichaut-noisette, digestion facilitée épluchage.</p><p><strong>Crosnes à l\'orientale :</strong> Sautage wok, gingembre, sauce soja, graines sésame. Forme originale préservée, goût délicat artichaut-salsifis.</p><h3>Préparations modernes</h3><p><strong>Chips légumes :</strong> Mandoline fine, déshydrateur/four, assaisonnements variés. Apéritifs colorés, textures craquantes, alternative saine.</p><p><strong>Velouté mélangé :</strong> Association 3-4 légumes anciens, bouillon poule, crème végétale. Complexité gustative, présentation soignée, découverte garantie.</p><h3>Conseils pratiques</h3><p><strong>Préparation :</strong> Brossage suffit souvent, épluchage fin économe. Cuissons courtes préservent textures, assaisonnements légers subliment.</p>',
                'excerpt' => 'Légumes anciens redécouverts : panais, rutabaga, topinambour, crosne. Patrimoine culinaire aux saveurs retrouvées.',
                'meta_title' => 'Légumes Anciens : Redécouvrir les Saveurs | FarmShop',
                'meta_description' => 'Recettes légumes anciens : panais, rutabaga, topinambour. Redécouvrir patrimoine culinaire et saveurs oubliées.',
                'tags' => ['légumes anciens', 'panais', 'rutabaga', 'topinambour', 'patrimoine'],
                'reading_time' => 8
            ],
            [
                'category' => 'Recettes de Saison',
                'title' => 'Herbes sauvages comestibles : cuisine nature',
                'content' => '<h2>La nature dans l\'assiette</h2><p>Pissenlit, plantain, ortie, pourpier : ces "mauvaises herbes" sont trésors nutritionnels. Identification sûre, cueillette responsable, préparations savoureuses révèlent richesses insoupçonnées.</p><h3>Identification et cueillette</h3><p><strong>Pissenlit :</strong> Feuilles dentées, fleur jaune caractéristique, racine pivotante. Jeunes feuilles moins amères, cueillette avant floraison optimale.</p><p><strong>Plantain :</strong> Feuilles nervurées parallèles, épis discrets, partout présent. Antiseptique naturel, goût champignon, très digestible.</p><h3>Préparations de base</h3><p><strong>Salade pissenlit :</strong> Feuilles jeunes, lardons chauds, vinaigre, œuf poché. Amertume équilibrée gras, tradition campagnarde revisitée moderne.</p><p><strong>Soupe ortie :</strong> Orties gantées, pommes terre, oignon, bouillon. Ébouillantage élimine piquant, goût épinard concentré, fer exceptionnel.</p><h3>Techniques culinaires</h3><p><strong>Beignets fleurs :</strong> Sureau, acacia, pâte légère, friture dorée. Parfums floraux délicats, présentation spectaculaire, desserts originaux.</p><p><strong>Pestos sauvages :</strong> Ail des ours, pignons, parmesan, huile olive. Saveur ail puissante, conservation excellente, polyvalence remarquable.</p><h3>Précautions essentielles</h3><p><strong>Identification certaine :</strong> Guides spécialisés, sorties accompagnées, doutes = abstention. Confusions dangereuses possibles, prudence vitale.</p><p><strong>Zones propres :</strong> Éviter bords routes, pollutions, traitements chimiques. Nature sauvage préservée, qualité sanitaire garantie.</p>',
                'excerpt' => 'Herbes sauvages comestibles : pissenlit, ortie, plantain. Cuisine nature, identification sûre, préparations savoureuses.',
                'meta_title' => 'Herbes Sauvages Comestibles : Cuisine Nature | FarmShop',
                'meta_description' => 'Guide herbes sauvages : pissenlit, ortie, plantain. Identification, cueillette, recettes nature.',
                'tags' => ['herbes sauvages', 'pissenlit', 'ortie', 'cueillette', 'cuisine nature'],
                'reading_time' => 9
            ]
        ];
    }
}
