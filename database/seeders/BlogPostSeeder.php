<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BlogPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer les catégories et l'admin
        $categories = BlogCategory::all();
        $admin = User::where('role', 'Admin')->first();
        
        if (!$admin) {
            $this->command->error('Aucun utilisateur admin trouvé. Veuillez créer un admin d\'abord.');
            return;
        }

        if ($categories->isEmpty()) {
            $this->command->error('Aucune catégorie trouvée. Veuillez exécuter BlogCategorySeeder d\'abord.');
            return;
        }

        $articles = [
            // Le saviez-vous
            [
                'title' => 'Le saviez-vous ? Les tomates communiquent entre elles',
                'excerpt' => 'Découvrez comment les plants de tomates utilisent des signaux chimiques pour avertir leurs voisins des dangers.',
                'content' => $this->getArticleContent('tomates_communication'),
                'category_name' => 'Le saviez-vous',
                'tags' => ['tomates', 'communication', 'plantes', 'sciences'],
                'is_featured' => true,
                'meta_title' => 'Les tomates communiquent : découverte scientifique étonnante',
                'meta_description' => 'Les plants de tomates utilisent des signaux chimiques pour communiquer. Une découverte fascinante sur l\'intelligence végétale.',
            ],
            [
                'title' => 'Pourquoi les abeilles dansent-elles ?',
                'excerpt' => 'La danse des abeilles n\'est pas un spectacle, mais un système de communication sophistiqué pour indiquer la localisation des fleurs.',
                'content' => $this->getArticleContent('danse_abeilles'),
                'category_name' => 'Le saviez-vous',
                'tags' => ['abeilles', 'communication', 'pollinisation', 'nature'],
                'is_featured' => false,
            ],

            // Trucs et astuces
            [
                'title' => '5 astuces pour économiser l\'eau au potager',
                'excerpt' => 'Réduisez votre consommation d\'eau tout en gardant un potager florissant grâce à ces techniques simples et efficaces.',
                'content' => $this->getArticleContent('economie_eau'),
                'category_name' => 'Trucs et astuces',
                'tags' => ['économie d\'eau', 'potager', 'écologie', 'jardinage'],
                'is_featured' => true,
            ],
            [
                'title' => 'Comment faire son purin d\'ortie maison',
                'excerpt' => 'Préparez votre propre engrais naturel et répulsif écologique avec des orties fraîches. Recette détaillée et conseils d\'utilisation.',
                'content' => $this->getArticleContent('purin_ortie'),
                'category_name' => 'Trucs et astuces',
                'tags' => ['purin d\'ortie', 'engrais naturel', 'bio', 'ortie'],
                'is_featured' => false,
            ],

            // Potager et Légumes
            [
                'title' => 'Calendrier de plantation des légumes d\'automne',
                'excerpt' => 'Que planter en automne pour des récoltes hivernales ? Découvrez les légumes parfaits pour cette saison.',
                'content' => $this->getArticleContent('plantation_automne'),
                'category_name' => 'Potager et Legumes',
                'tags' => ['automne', 'plantation', 'légumes', 'calendrier'],
                'is_featured' => false,
            ],
            [
                'title' => 'Cultiver des radis : du semis à la récolte',
                'excerpt' => 'Les radis sont parfaits pour les débutants. Apprenez tout sur leur culture rapide et leurs variétés savoureuses.',
                'content' => $this->getArticleContent('culture_radis'),
                'category_name' => 'Potager et Legumes',
                'tags' => ['radis', 'culture', 'légumes-racines', 'débutant'],
                'is_featured' => false,
            ],

            // Fruits et Verger
            [
                'title' => 'Tailler ses pommiers : guide complet',
                'excerpt' => 'Apprenez les techniques de taille pour obtenir de belles récoltes de pommes. Quand, comment et pourquoi tailler ?',
                'content' => $this->getArticleContent('taille_pommiers'),
                'category_name' => 'Fruits et Verger',
                'tags' => ['pommiers', 'taille', 'arboriculture', 'verger'],
                'is_featured' => true,
            ],
            [
                'title' => 'Créer un petit verger familial',
                'excerpt' => 'Conseils pour planifier et créer votre verger familial, même sur une petite surface. Choix des variétés et plantation.',
                'content' => $this->getArticleContent('verger_familial'),
                'category_name' => 'Fruits et Verger',
                'tags' => ['verger', 'famille', 'plantation', 'fruits'],
                'is_featured' => false,
            ],

            // Plantes Aromatiques
            [
                'title' => 'Basilic : culture et variétés méconnues',
                'excerpt' => 'Au-delà du basilic commun, découvrez les variétés surprenantes et leurs utilisations culinaires et thérapeutiques.',
                'content' => $this->getArticleContent('basilic_varietes'),
                'category_name' => 'Plantes Aromatiques',
                'tags' => ['basilic', 'aromatiques', 'variétés', 'cuisine'],
                'is_featured' => false,
            ],
            [
                'title' => 'Créer une spirale d\'aromatiques',
                'excerpt' => 'Optimisez l\'espace et créez un microclimat parfait pour vos herbes avec une spirale d\'aromatiques. Plans et conseils.',
                'content' => $this->getArticleContent('spirale_aromatiques'),
                'category_name' => 'Plantes Aromatiques',
                'tags' => ['spirale', 'aromatiques', 'permaculture', 'design'],
                'is_featured' => false,
            ],

            // Jardinage Bio
            [
                'title' => 'Associations de plantes bénéfiques au potager',
                'excerpt' => 'Découvrez quelles plantes associer pour améliorer la croissance, repousser les nuisibles et optimiser l\'espace.',
                'content' => $this->getArticleContent('associations_plantes'),
                'category_name' => 'Jardinage Bio',
                'tags' => ['associations', 'compagnonnage', 'bio', 'permaculture'],
                'is_featured' => true,
            ],
            [
                'title' => 'Lutter naturellement contre les pucerons',
                'excerpt' => 'Solutions écologiques et préventives pour éliminer les pucerons sans pesticides chimiques.',
                'content' => $this->getArticleContent('lutte_pucerons'),
                'category_name' => 'Jardinage Bio',
                'tags' => ['pucerons', 'bio', 'lutte naturelle', 'insectes'],
                'is_featured' => false,
            ],

            // Animaux de Basse-cour
            [
                'title' => 'Élever des poules pondeuses : guide du débutant',
                'excerpt' => 'Tout ce qu\'il faut savoir pour commencer un petit élevage de poules : races, logement, alimentation et soins.',
                'content' => $this->getArticleContent('elevage_poules'),
                'category_name' => 'Animaux de Basse-cour',
                'tags' => ['poules', 'élevage', 'œufs', 'basse-cour'],
                'is_featured' => true,
            ],
            [
                'title' => 'Construire un poulailler adapté',
                'excerpt' => 'Plans et conseils pour construire un poulailler sécurisé et confortable pour vos poules.',
                'content' => $this->getArticleContent('construction_poulailler'),
                'category_name' => 'Animaux de Basse-cour',
                'tags' => ['poulailler', 'construction', 'DIY', 'plans'],
                'is_featured' => false,
            ],

            // Apiculture
            [
                'title' => 'Débuter en apiculture : équipement et première ruche',
                'excerpt' => 'Guide complet pour les apprentis apiculteurs : matériel nécessaire, choix de l\'emplacement et premiers pas.',
                'content' => $this->getArticleContent('debuter_apiculture'),
                'category_name' => 'Apiculture',
                'tags' => ['apiculture', 'débutant', 'ruche', 'abeilles'],
                'is_featured' => false,
            ],
            [
                'title' => 'Calendrier de l\'apiculteur',
                'excerpt' => 'Les interventions saisonnières indispensables pour maintenir des ruches en bonne santé toute l\'année.',
                'content' => $this->getArticleContent('calendrier_apiculteur'),
                'category_name' => 'Apiculture',
                'tags' => ['calendrier', 'apiculture', 'saisonnier', 'entretien'],
                'is_featured' => false,
            ],

            // Recettes de Saison
            [
                'title' => 'Conserves de tomates : 3 recettes incontournables',
                'excerpt' => 'Profitez de l\'abondance estivale pour préparer vos conserves de tomates. Recettes traditionnelles et conseils de conservation.',
                'content' => $this->getArticleContent('conserves_tomates'),
                'category_name' => 'Recettes de Saison',
                'tags' => ['conserves', 'tomates', 'été', 'conservation'],
                'is_featured' => false,
            ],
            [
                'title' => 'Soupes d\'automne avec les légumes du jardin',
                'excerpt' => 'Réchauffez-vous avec ces délicieuses soupes préparées avec les légumes de saison de votre potager.',
                'content' => $this->getArticleContent('soupes_automne'),
                'category_name' => 'Recettes de Saison',
                'tags' => ['soupes', 'automne', 'légumes', 'cuisine'],
                'is_featured' => false,
            ],

            // Agriculture Durable
            [
                'title' => 'Principes de la permaculture au potager',
                'excerpt' => 'Découvrez comment appliquer les principes de la permaculture pour créer un écosystème durable et productif.',
                'content' => $this->getArticleContent('permaculture_potager'),
                'category_name' => 'Agriculture Durable',
                'tags' => ['permaculture', 'durable', 'écosystème', 'principes'],
                'is_featured' => true,
            ],
            [
                'title' => 'Rotation des cultures : planifier pour 4 ans',
                'excerpt' => 'Organisez la rotation de vos cultures pour maintenir la fertilité du sol et prévenir les maladies.',
                'content' => $this->getArticleContent('rotation_cultures'),
                'category_name' => 'Agriculture Durable',
                'tags' => ['rotation', 'cultures', 'planification', 'sol'],
                'is_featured' => false,
            ],

            // Compostage et Recyclage
            [
                'title' => 'Réussir son compost en 6 étapes',
                'excerpt' => 'Transformez vos déchets organiques en or noir pour votre jardin. Méthode simple et efficace pour un compost parfait.',
                'content' => $this->getArticleContent('reussir_compost'),
                'category_name' => 'Compostage et Recyclage',
                'tags' => ['compost', 'recyclage', 'déchets organiques', 'engrais'],
                'is_featured' => true,
            ],
            [
                'title' => 'Lombricompostage en appartement',
                'excerpt' => 'Même en ville, vous pouvez composter ! Découvrez le lombricompostage, parfait pour les espaces réduits.',
                'content' => $this->getArticleContent('lombricompostage'),
                'category_name' => 'Compostage et Recyclage',
                'tags' => ['lombricompostage', 'appartement', 'vers', 'urbain'],
                'is_featured' => false,
            ],
        ];

        $this->command->info('Création des articles de blog...');

        foreach ($articles as $index => $articleData) {
            $category = $categories->where('name', $articleData['category_name'])->first();
            
            if (!$category) {
                $this->command->warn("Catégorie '{$articleData['category_name']}' non trouvée pour l'article '{$articleData['title']}'");
                continue;
            }

            // Dates de publication variées
            $publishedDays = rand(1, 180); // Articles des 6 derniers mois
            $publishedAt = Carbon::now()->subDays($publishedDays);

            $article = BlogPost::create([
                'title' => $articleData['title'],
                'slug' => Str::slug($articleData['title']),
                'excerpt' => $articleData['excerpt'],
                'content' => $articleData['content'],
                'blog_category_id' => $category->id,
                'author_id' => $admin->id,
                'status' => 'published',
                'published_at' => $publishedAt,
                'meta_title' => $articleData['meta_title'] ?? $articleData['title'],
                'meta_description' => $articleData['meta_description'] ?? $articleData['excerpt'],
                'tags' => $articleData['tags'],
                'views_count' => rand(50, 2000),
                'likes_count' => rand(5, 150),
                'shares_count' => rand(0, 50),
                'comments_count' => rand(0, 25),
                'reading_time' => round(str_word_count($articleData['content']) / 200, 1), // ~200 mots/minute
                'allow_comments' => true,
                'is_featured' => $articleData['is_featured'] ?? false,
                'is_sticky' => false,
                'created_at' => $publishedAt,
                'updated_at' => $publishedAt,
            ]);

            $this->command->info("Article créé : {$article->title}");
        }

        // Mettre à jour les compteurs de posts dans les catégories
        foreach ($categories as $category) {
            $category->update([
                'posts_count' => $category->blogPosts()->where('status', 'published')->count()
            ]);
        }

        $this->command->info('Articles de blog créés avec succès !');
        $this->command->info('Total articles créés : ' . count($articles));
    }

    /**
     * Générer le contenu des articles
     */
    private function getArticleContent($type): string
    {
        $contents = [
            'tomates_communication' => "
# Les tomates : des communicatrices hors pair

Les plants de tomates sont bien plus intelligents qu'on ne le pense ! Des recherches récentes ont révélé que ces plantes populaires de nos potagers possèdent un système de communication chimique sophistiqué.

## Comment les tomates communiquent-elles ?

Lorsqu'un plant de tomate est attaqué par des insectes nuisibles, il libère des composés chimiques volatils dans l'air. Ces molécules agissent comme des signaux d'alarme pour avertir les plants voisins du danger imminent.

### Le processus en détail

1. **Détection de l'attaque** : Le plant détecte la salive des insectes
2. **Production de signaux** : Il synthétise des composés chimiques spécifiques
3. **Diffusion aérienne** : Ces molécules se dispersent dans l'air
4. **Réception** : Les plants voisins captent ces signaux
5. **Préparation défensive** : Ils renforcent leurs défenses naturelles

## Implications pour le jardinier

Cette découverte explique pourquoi la biodiversité au potager est si importante. Un écosystème riche permet aux plantes de mieux communiquer et se défendre collectivement.

### Conseils pratiques

- Évitez l'utilisation excessive de pesticides qui perturbent cette communication
- Favorisez la plantation en groupes plutôt qu'en rangs isolés
- Intégrez des plantes compagnes qui amplifient ces signaux naturels

*Cette capacité de communication témoigne de l'intelligence remarquable du monde végétal et nous invite à repenser notre approche du jardinage.*
            ",

            'danse_abeilles' => "
# La danse des abeilles : un GPS naturel

La danse des abeilles est l'un des systèmes de communication les plus sophistiqués du règne animal. Découverte par Karl von Frisch, prix Nobel de médecine en 1973, cette danse permet aux abeilles de transmettre avec précision la localisation des sources de nectar.

## Les types de danses

### La danse en rond
- **Distance** : Sources à moins de 50 mètres
- **Message** : \"Il y a du nectar proche de la ruche\"
- **Durée** : Courte et répétitive

### La danse en huit (waggle dance)
- **Distance** : Sources à plus de 50 mètres
- **Information transmise** :
  - Direction par rapport au soleil
  - Distance précise
  - Qualité de la source

## Décryptage de la danse

L'angle de la danse par rapport à la verticale indique la direction :
- **Vers le haut** : Direction du soleil
- **Vers le bas** : Direction opposée au soleil
- **Angle** : Direction précise par rapport au soleil

La durée de la phase droite indique la distance :
- **1 seconde** ≈ 1000 mètres
- Plus la danse est longue, plus la source est éloignée

## L'importance pour l'apiculture

Comprendre cette danse aide les apiculteurs à :
- Identifier les zones de butinage favorites
- Détecter les périodes de disette
- Optimiser l'emplacement des ruches

*Observer la danse des abeilles, c'est assister à l'un des spectacles les plus fascinants de la nature !*
            ",

            'economie_eau' => "
# 5 astuces pour économiser l'eau au potager

Face aux enjeux climatiques et à la raréfaction de l'eau, optimiser l'arrosage devient essentiel. Voici 5 techniques éprouvées pour réduire considérablement votre consommation d'eau.

## 1. Le paillage : votre meilleur allié

Le paillage réduit l'évaporation de 70% et maintient l'humidité du sol.

### Matériaux recommandés :
- **Paille** : Idéale pour les légumes
- **Feuilles mortes** : Gratuit et nutritif
- **BRF** (Bois Raméal Fragmenté) : Améliore la structure du sol
- **Tonte de gazon** : À utiliser séchée

**Application** : Épaisseur de 5-10 cm autour des plants

## 2. L'arrosage goutte à goutte

Cette technique apporte l'eau directement aux racines, évitant tout gaspillage.

### Systèmes DIY simples :
- **Bouteilles percées** : Solution économique
- **Ollas** : Jarres enterrées traditionnelles
- **Tuyaux microporeux** : Efficaces pour les rangs

**Économie** : Jusqu'à 50% d'eau en moins

## 3. Récupération d'eau de pluie

Un toit de 100m² peut collecter 60 000 litres/an !

### Installation basique :
1. Gouttières dirigées vers une cuve
2. Filtre simple (grillage fin)
3. Robinet de distribution
4. Couvercle anti-moustiques

## 4. Choix de variétés résistantes

Privilégiez les légumes peu gourmands en eau :

**Très résistants** :
- Pourpier, roquette, épinards
- Betteraves, radis
- Haricots verts

**Moyennement résistants** :
- Tomates cerises
- Courges (une fois établies)
- Herbes aromatiques

## 5. Timing optimal d'arrosage

**Meilleurs moments** :
- **Tôt le matin** (6h-8h) : Évaporation minimale
- **Fin de soirée** (19h-21h) : Les plantes absorbent mieux

**À éviter** :
- Milieu de journée (évaporation maximale)
- Plein soleil (risque de brûlure des feuilles)

## Bonus : La technique du bassinage

Créez de petites cuvettes autour des plants pour retenir l'eau d'arrosage et de pluie. Particulièrement efficace pour les jeunes plantations.

*Avec ces techniques, vous pouvez réduire votre consommation d'eau de 60% tout en gardant un potager florissant !*
            ",

            'purin_ortie' => "
# Purin d'ortie : l'engrais miracle du jardinier bio

Le purin d'ortie est un fertilisant naturel polyvalent, facile à préparer et économique. Découvrez tous ses secrets pour booster vos cultures naturellement.

## Pourquoi l'ortie ?

L'ortie (Urtica dioica) est exceptionnellement riche en :
- **Azote** : Croissance et verdure
- **Potassium** : Résistance aux maladies
- **Fer** : Photosynthèse
- **Silice** : Renforcement des tissus

## Recette du purin d'ortie

### Ingrédients
- 1 kg d'orties fraîches (avant floraison)
- 10 litres d'eau de pluie (de préférence)
- 1 récipient non métallique

### Préparation

1. **Récolte** : Cueillez les orties avec des gants, de préférence le matin
2. **Hachage** : Coupez grossièrement pour accélérer la fermentation
3. **Macération** : Placez dans l'eau, couvrez avec un linge
4. **Fermentation** : Remuez quotidiennement pendant 10-15 jours
5. **Filtrage** : Quand l'odeur devient acceptable, filtrez

### Signes de réussite
- Couleur brun-vert foncé
- Odeur forte mais pas putride
- Absence de mousse en surface

## Utilisations et dosages

### Comme engrais
- **Dilution** : 1 volume de purin pour 10 volumes d'eau
- **Fréquence** : Tous les 15 jours pendant la croissance
- **Application** : Au pied des plants, jamais sur les feuilles

### Comme répulsif (non dilué)
- **Contre les pucerons** : Pulvérisation directe
- **Limaces** : Arrosage autour des plants sensibles
- **Fourmis** : Sur les passages

## Plantes bénéficiaires

**Très réceptives** :
- Tomates, courgettes, choux
- Rosiers, géraniums
- Pelouse

**À éviter** :
- Légumineuses (haricots, pois)
- Plantes de terre acide (myrtilles, azalées)

## Conservation

- **Frais** : 1 mois au frais
- **Concentré** : Réduire de moitié par ébullition (conserve 1 an)
- **Congelé** : En bacs à glaçons pour petites doses

## Conseils d'utilisation

### Précautions
- Ne jamais utiliser pur comme engrais
- Éviter par temps très chaud
- Porter des gants lors de la manipulation

### Optimisation
- Ajouter quelques feuilles de consoude (riche en potasse)
- Mélanger avec du purin de prêle pour l'effet antifongique

*Le purin d'ortie est un incontournable du jardinage biologique. Une fois maîtrisé, il deviendra votre allié le plus fidèle !*
            ",

            'plantation_automne' => "
# Calendrier de plantation des légumes d'automne

L'automne n'est pas synonyme de fin de saison au potager ! C'est le moment idéal pour planter de nombreux légumes qui vous régaleront tout l'hiver.

## Septembre : les dernières plantations d'été

### À planter
- **Radis** : Variétés d'hiver (Noir rond, Rose de Chine)
- **Épinards** : Pour récoltes d'octobre à décembre
- **Mâche** : Résiste jusqu'à -15°C
- **Oignons blancs** : Plantation des bulbilles

### À semer
- **Roquette** : Croissance rapide (30 jours)
- **Cressons** : Alénois et de jardin
- **Pourpier d'hiver** : Alternative aux épinards

## Octobre : préparation de l'hiver

### Légumes racines
- **Carottes** : Variétés courtes (Marché de Paris)
- **Navets** : Excellents en soupe
- **Panais** : Résistants au gel

### Légumes feuilles
- **Choux** : Choux d'hiver, choux de Bruxelles
- **Poireaux** : Plantation des derniers plants
- **Persil** : Frisé ou plat, résiste au froid

## Novembre : les rustiques

### Plantations sous abri
- **Salade d'hiver** : Batavia, chicorée
- **Cresson** : En bac ou jardinière
- **Ciboulette** : Division des touffes

### En pleine terre
- **Ail** : Plantation des caïeux
- **Échalotes** : Variétés d'hiver
- **Fèves** : Semis pour récolte précoce

## Techniques pour prolonger les récoltes

### Protection hivernale

#### Voiles d'hivernage
- **Température** : Gagne 2-4°C
- **Installation** : Directement sur les plants
- **Matériaux** : Polypropylène non-tissé

#### Tunnels plastiques
- **Avantages** : Protection pluie et vent
- **Ventilation** : Ouvrir par temps doux
- **Hauteur** : 40-60 cm pour les légumes feuilles

#### Paillis protecteur
- **Épaisseur** : 10-15 cm
- **Matériaux** : Feuilles mortes, paille
- **Fonction** : Isole les racines du gel

### Chassez-croisés malins

**Successions d'été** :
- Après tomates → Mâche et épinards
- Après haricots → Radis d'hiver
- Après courgettes → Choux

## Légumes perpétuels à installer

Ces légumes une fois plantés produisent plusieurs années :

### Vivaces productives
- **Artichaut** : Production 4-5 ans
- **Asperge** : Investissement long terme (15 ans)
- **Rhubarbe** : Facile et productive

### Aromatiques persistantes
- **Thym, romarin** : Résistent au gel
- **Sauge, origan** : Protection légère suffisante
- **Ciboulette** : Repos hivernal, repousse au printemps

## Planification pour le printemps

Profitez de l'automne pour préparer la saison suivante :

### Amendements
- **Compost** : Épandage avant l'hiver
- **Fumier** : Incorporation en surface
- **Engrais verts** : Semis de phacélie, moutarde

### Organisation
- **Rotation** : Planifiez les emplacements 2024
- **Commandes** : Graines et plants pour le printemps
- **Outils** : Nettoyage et entretien hivernal

*L'automne au potager, c'est la promesse de légumes frais tout l'hiver et une meilleure organisation pour l'année suivante !*
            ",

            // Ajoutez d'autres contenus selon vos besoins...
            'culture_radis' => "
# Cultiver des radis : du semis à la récolte

Les radis sont les champions de la rapidité au potager ! Parfaits pour les débutants, ils offrent une satisfaction immédiate avec des récoltes en 3 à 4 semaines seulement.

## Pourquoi choisir les radis ?

### Avantages
- **Croissance ultra-rapide** : 18 jours pour les plus précoces
- **Culture facile** : Peu d'entretien requis
- **Peu d'espace** : Parfait pour les petits jardins
- **Multiple variétés** : Formes et couleurs variées
- **Récoltes échelonnées** : Semis possible de mars à septembre

## Variétés incontournables

### Radis de tous les mois
- **18 jours** : Le plus rapide (rouge et blanc)
- **Cerise** : Petit, rond, rouge vif
- **Flamboyant** : Rouge à pointe blanche

### Radis d'été
- **Sezanne** : Résiste bien à la chaleur
- **Saxa** : Forme ronde parfaite
- **French Breakfast** : Allongé, doux

### Radis d'hiver
- **Noir rond** : Se conserve tout l'hiver
- **Rose de Chine** : Chair blanche, peau rose
- **Daikon** : Radis japonais géant

## Semis et plantation

### Préparation du sol
- **Exposition** : Soleil ou mi-ombre
- **Sol** : Léger, bien drainé, meuble
- **pH** : 6 à 7 (neutre)
- **Préparation** : Bêchage superficiel (15 cm)

### Technique de semis
1. **Sillons** : Profondeur 1-2 cm, espacés de 15 cm
2. **Semences** : 1 graine tous les 2-3 cm
3. **Recouvrement** : Terre fine, tasser légèrement
4. **Arrosage** : Pluie fine pour ne pas déplacer les graines

### Calendrier de semis
- **Mars-avril** : Sous abri ou voile
- **Mai-septembre** : Pleine terre
- **Octobre** : Derniers semis de variétés d'hiver

## Entretien quotidien

### Arrosage
- **Régularité** : Clé de la réussite
- **Fréquence** : Tous les 2-3 jours en été
- **Technique** : Arrosoir à pomme fine
- **Éviter** : L'eau stagnante (pourriture)

### Éclaircissage
Quand les plantules ont 2 vraies feuilles :
- **Distance finale** : 3-4 cm entre radis
- **Technique** : Arracher délicatement les plus faibles
- **Récupération** : Les jeunes pousses se mangent en salade

### Binage et sarclage
- **Fréquence** : Après chaque arrosage
- **Profondeur** : Superficiel (2-3 cm)
- **Objectif** : Aérer le sol, éliminer les mauvaises herbes

## Récolte et conservation

### Signes de maturité
- **Taille** : Selon la variété (1-3 cm pour les ronds)
- **Fermeté** : Radis ferme sous le doigt
- **Feuillage** : Bien développé, vert
- **Timing** : Ne pas attendre (deviennent piquants)

### Technique de récolte
- **Moment** : Tôt le matin (plus croquants)
- **Méthode** : Tirer délicatement par les feuilles
- **Nettoyage** : Brosser sous l'eau froide

### Conservation
- **Frais** : 1 semaine au réfrigérateur (feuilles coupées)
- **En terre** : Possible en hiver avec paillis
- **Radis d'hiver** : 2-3 mois en cave dans du sable

## Problèmes courants et solutions

### Radis qui montent en graine
**Causes** : Stress hydrique, chaleur excessive
**Solutions** : Arrosage régulier, semis échelonnés, ombrage en été

### Radis piquants ou fibreux
**Causes** : Manque d'eau, récolte tardive
**Solutions** : Arrosage constant, récolte à temps

### Radis creux
**Causes** : Excès d'azote, croissance trop rapide
**Solutions** : Éviter les fumures fraîches, arrosage modéré

### Altises (petites puces)
**Symptômes** : Feuilles criblées de petits trous
**Solutions** : Voile anti-insectes, arrosage des feuilles

## Associations bénéfiques

### Plantes compagnes
- **Carottes** : Complémentarité parfaite
- **Laitues** : Utilisation optimale de l'espace
- **Épinards** : Même exigences d'arrosage

### À éviter
- **Crucifères** : Choux, navets (même famille)
- **Haricots** : Compétition pour l'eau

*Les radis sont la porte d'entrée idéale vers le jardinage. Leur succès rapide motive et enseigne les bases de la culture potagère !*
            ",

            // Continuez à ajouter d'autres contenus...
        ];

        return $contents[$type] ?? "Contenu de l'article à développer pour : " . $type;
    }
}
