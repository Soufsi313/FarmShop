<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlogComment;
use App\Models\BlogCommentReport;
use App\Models\BlogPost;
use App\Models\User;
use Carbon\Carbon;

class BlogCommentSeeder extends Seeder
{
    /**
     * Créer 100 commentaires répartis sur les 100 articles de blog
     * - 75 commentaires normaux
     * - 25 commentaires signalés par des utilisateurs réels
     */
    public function run()
    {
        // Récupérer tous les articles et utilisateurs
        $blogPosts = BlogPost::all();
        $users = User::where('role', 'User')->get(); // Exclure l'admin des commentaires
        
        if ($blogPosts->count() < 100) {
            $this->command->error('Pas assez d\'articles de blog (besoin de 100)');
            return;
        }
        
        if ($users->count() < 25) {
            $this->command->error('Pas assez d\'utilisateurs pour les signalements (besoin de 25 minimum)');
            return;
        }

        $commentaires = $this->getCommentaires();
        $commentairesSignalables = $this->getCommentairesSignalables();
        
        $commentsCreated = 0;
        $reportsCreated = 0;

        // Créer 75 commentaires normaux
        for ($i = 0; $i < 75; $i++) {
            $post = $blogPosts[$i];
            $user = $users->random();
            $commentData = $commentaires[array_rand($commentaires)];
            
            BlogComment::create([
                'blog_post_id' => $post->id,
                'user_id' => $user->id,
                'guest_name' => null, // Utilisateur connecté
                'guest_email' => null, // Utilisateur connecté
                'content' => $commentData['content'],
                'status' => 'approved',
                'ip_address' => $this->generateRandomIP(),
                'user_agent' => $this->getRandomUserAgent(),
                'created_at' => Carbon::now()->subDays(rand(1, 90)),
                'updated_at' => Carbon::now()->subDays(rand(0, 30)),
            ]);
            
            $commentsCreated++;
        }

        // Créer 25 commentaires signalables avec leurs signalements
        for ($i = 75; $i < 100; $i++) {
            $post = $blogPosts[$i];
            $commentAuthor = $users->random();
            $commentData = $commentairesSignalables[array_rand($commentairesSignalables)];
            
            // Créer le commentaire signalable
            $comment = BlogComment::create([
                'blog_post_id' => $post->id,
                'user_id' => $commentAuthor->id,
                'guest_name' => null, // Utilisateur connecté
                'guest_email' => null, // Utilisateur connecté
                'content' => $commentData['content'],
                'status' => 'approved', // Approuvé initialement
                'ip_address' => $this->generateRandomIP(),
                'user_agent' => $this->getRandomUserAgent(),
                'created_at' => Carbon::now()->subDays(rand(1, 90)),
                'updated_at' => Carbon::now()->subDays(rand(0, 30)),
            ]);
            
            $commentsCreated++;
            
            // Créer le signalement par un autre utilisateur
            $reporter = $users->where('id', '!=', $commentAuthor->id)->random();
            
            BlogCommentReport::create([
                'blog_comment_id' => $comment->id,
                'reported_by' => $reporter->id,
                'reason' => $commentData['reason'],
                'description' => $commentData['description'],
                'status' => 'pending',
                'reporter_ip' => $this->generateRandomIP(),
                'reporter_user_agent' => $this->getRandomUserAgent(),
                'created_at' => $comment->created_at->addHours(rand(1, 48)),
                'updated_at' => $comment->created_at->addHours(rand(1, 48)),
            ]);
            
            $reportsCreated++;
        }

        $this->command->info("✅ {$commentsCreated} commentaires créés sur 100 articles");
        $this->command->info("⚠️ {$reportsCreated} signalements créés pour les commentaires problématiques");
        $this->command->info("📊 Répartition: 75 commentaires normaux + 25 commentaires signalés");
    }

    private function getCommentaires()
    {
        return [
            ['content' => 'Excellent article ! Ces conseils vont vraiment m\'aider pour mon potager. Merci pour ce partage d\'expérience.'],
            ['content' => 'J\'ai testé cette technique l\'année dernière et les résultats sont spectaculaires. Je recommande vivement !'],
            ['content' => 'Très intéressant, surtout la partie sur la rotation des cultures. J\'aimerais en savoir plus sur les associations bénéfiques.'],
            ['content' => 'Merci pour ces informations pratiques. En tant que débutant, cet article répond exactement à mes questions.'],
            ['content' => 'Formidable ! J\'applique déjà certaines de ces méthodes et je peux confirmer leur efficacité.'],
            ['content' => 'Article très complet et bien documenté. Les photos illustrent parfaitement les explications.'],
            ['content' => 'Enfin des conseils concrets ! J\'en avais assez des articles trop théoriques. Celui-ci est parfait.'],
            ['content' => 'Je vais essayer cette approche dès ce printemps. Avez-vous des recommandations pour les débutants ?'],
            ['content' => 'Excellent travail ! Cette méthode a révolutionné ma façon de cultiver. Résultats garantis.'],
            ['content' => 'Très bon article, j\'ajouterais juste qu\'il faut adapter selon le type de sol. Dans ma région argileuse, j\'ai dû modifier légèrement.'],
            ['content' => 'Merci pour ce partage ! Je cherchais exactement ces informations pour mon projet de ferme urbaine.'],
            ['content' => 'Technique intéressante ! J\'ai une question sur la période optimale d\'application. Cela fonctionne-t-il en automne ?'],
            ['content' => 'Super article ! J\'ai déjà commencé à mettre en pratique et je vois déjà une amélioration.'],
            ['content' => 'Très enrichissant ! Cette approche respectueuse de l\'environnement correspond parfaitement à mes valeurs.'],
            ['content' => 'Article fantastique ! Les explications sont claires et les exemples concrets très utiles.'],
            ['content' => 'J\'adore cette méthode naturelle ! Enfin une alternative aux produits chimiques qui fonctionne vraiment.'],
            ['content' => 'Merci pour ces conseils précieux ! Mon grand-père utilisait déjà certaines de ces techniques, c\'est du bon sens.'],
            ['content' => 'Très instructif ! J\'ai appris beaucoup de choses que je ne connaissais pas. Hâte de tester !'],
            ['content' => 'Excellente approche ! Cette technique s\'inscrit parfaitement dans une démarche de permaculture.'],
            ['content' => 'Article très utile ! J\'ai partagé avec mes amis jardiniers, ils vont adorer ces astuces.'],
            ['content' => 'Formidable explication ! Les étapes sont détaillées et faciles à suivre pour un novice comme moi.'],
            ['content' => 'Merci pour ce guide pratique ! J\'ai enfin trouvé la solution à mon problème de ravageurs.'],
            ['content' => 'Technique révolutionnaire ! Mes rendements ont doublé depuis que j\'applique cette méthode.'],
            ['content' => 'Article passionnant ! J\'aimerais voir plus de contenu sur l\'agriculture biologique.'],
            ['content' => 'Très bon conseil ! Cette approche économique et écologique est parfaite pour les petits budgets.'],
            ['content' => 'Génial ! Je vais tester cette méthode sur une parcelle pour comparer avec mes techniques actuelles.'],
            ['content' => 'Merci pour ce partage d\'expérience ! Vos conseils sont toujours pertinents et pratiques.'],
            ['content' => 'Article excellent ! La biodiversité de mon jardin s\'est nettement améliorée grâce à ces conseils.'],
            ['content' => 'Très interessant ! J\'ai une question: cette technique fonctionne-t-elle aussi en climat méditerranéen ?'],
            ['content' => 'Fantastique ! Cette méthode naturelle donne de bien meilleurs résultats que les engrais chimiques.'],
            ['content' => 'Super article ! Mon potager n\'a jamais été aussi productif depuis que j\'applique ces principes.'],
            ['content' => 'Merci pour ces explications détaillées ! Enfin je comprends pourquoi mes précédentes tentatives échouaient.'],
            ['content' => 'Excellent ! Cette approche respectueuse de l\'environnement donne des résultats impressionnants.'],
            ['content' => 'Très utile ! J\'ai recommandé cet article à tous les membres de mon association de jardiniers.'],
            ['content' => 'Article formidable ! Ces techniques ancestrales ont fait leurs preuves, merci de les rappeler.'],
            ['content' => 'Génial ! J\'ai déjà commandé le matériel nécessaire pour commencer dès demain.'],
            ['content' => 'Merci pour ce guide complet ! Mes voisins sont impressionnés par les résultats de mon potager.'],
            ['content' => 'Très bon article ! Cette méthode s\'adapte parfaitement à mon petit espace urbain.'],
            ['content' => 'Excellent conseil ! Mon compost n\'a jamais été aussi riche depuis que j\'applique cette technique.'],
            ['content' => 'Fantastique ! Cette approche holistique transforme complètement la vision du jardinage.'],
            ['content' => 'Super ! J\'ai testé sur mes tomates et le résultat est spectaculaire. Merci pour ce partage !'],
            ['content' => 'Très instructif ! Cette méthode me permettra d\'économiser sur les intrants tout en améliorant la qualité.'],
            ['content' => 'Article passionnant ! J\'aimerais voir plus de détails sur l\'adaptation selon les régions climatiques.'],
            ['content' => 'Merci pour ces conseils ! Mon sol s\'améliore de mois en mois grâce à cette approche naturelle.'],
            ['content' => 'Excellent ! Cette technique respectueuse de la biodiversité donne des légumes plus savoureux.'],
            ['content' => 'Très bon article ! J\'ai enfin trouvé une méthode qui fonctionne avec mon emploi du temps chargé.'],
            ['content' => 'Formidable ! Les abeilles sont revenues dans mon jardin depuis que j\'applique ces conseils.'],
            ['content' => 'Génial ! Cette approche économique me permet de nourrir ma famille avec un budget serré.'],
            ['content' => 'Merci pour ce guide ! Mes enfants adorent m\'aider au jardin maintenant que c\'est plus naturel.'],
            ['content' => 'Excellent article ! Cette méthode traditionnelle redonne du sens à l\'agriculture moderne.'],
        ];
    }

    private function getCommentairesSignalables()
    {
        return [
            [
                'content' => 'N\'importe quoi ! Ces méthodes anciennes sont dépassées. Achetez plutôt mes produits miracle sur mon site www.engrais-chimiques-puissants.com ! Résultats garantis en 24h !',
                'reason' => 'spam',
                'description' => 'Promotion de site commercial et dénigrement du contenu'
            ],
            [
                'content' => 'URGENT !!! GAGNEZ 5000€ PAR MOIS SANS EFFORT !!! Cliquez sur ce lien maintenant : bit.ly/argent-facile-2024 !!! Offre limitée !!!',
                'reason' => 'spam',
                'description' => 'Spam publicitaire avec liens suspects'
            ],
            [
                'content' => 'Les agriculteurs bio sont des idiots qui ne comprennent rien à la science moderne. Vos méthodes primitives vont nous faire crever de faim !',
                'reason' => 'inappropriate_content',
                'description' => 'Insultes envers les agriculteurs biologiques'
            ],
            [
                'content' => 'Fake news ! Le réchauffement climatique n\'existe pas, arrêtez de nous bourrer le crâne avec votre propagande écolo !',
                'reason' => 'false_information',
                'description' => 'Désinformation sur le changement climatique'
            ],
            [
                'content' => 'Moi je vends des tracteurs d\'occasion, excellent état, prix imbattables ! Contactez-moi au 06.XX.XX.XX.XX pour plus d\'infos. Livraison gratuite !',
                'reason' => 'spam',
                'description' => 'Publicité commerciale non sollicitée'
            ],
            [
                'content' => 'Vous êtes tous des moutons ! Les légumes bio coûtent 3 fois plus cher pour la même chose. Réveillez-vous bande d\'abrutis !',
                'reason' => 'inappropriate_content',
                'description' => 'Langage injurieux et agressif'
            ],
            [
                'content' => 'ATTENTION DANGER !!! Ces techniques détruisent l\'ADN des plantes ! Big Pharma veut empoisonner nos enfants ! Partagez massivement !',
                'reason' => 'false_information',
                'description' => 'Théories conspirationnistes sans fondement scientifique'
            ],
            [
                'content' => 'Je propose mes services de consultation agricole à domicile. Tarifs préférentiels pour les nouveaux clients. Appelez-moi vite !',
                'reason' => 'spam',
                'description' => 'Sollicitation commerciale inappropriée'
            ],
            [
                'content' => 'Vos articles sont nuls ! Mon blog agriculture-pro-2024.fr est 1000 fois mieux ! Arrêtez de copier mes idées !',
                'reason' => 'inappropriate_content',
                'description' => 'Dénigrement et auto-promotion agressive'
            ],
            [
                'content' => 'Les pesticides naturels sont plus dangereux que les chimiques ! Vous allez tuer vos voisins avec vos pseudo-sciences !',
                'reason' => 'false_information',
                'description' => 'Affirmations erronées sur les produits naturels'
            ],
            [
                'content' => 'PROMO EXCEPTIONNELLE !!! Graines magiques qui poussent en 2 jours !!! Stock limité !!! Commandez sur miracle-seeds.net !!!',
                'reason' => 'spam',
                'description' => 'Publicité pour produits miracles non fondés'
            ],
            [
                'content' => 'Les écolos sont des terroristes qui veulent détruire l\'agriculture française ! Retournez planter vos radis !',
                'reason' => 'hate_speech',
                'description' => 'Propos haineux envers les écologistes'
            ],
            [
                'content' => 'Article complètement faux ! Je suis ingénieur agronome et je peux vous assurer que tout est inventé !',
                'reason' => 'false_information',
                'description' => 'Remise en cause non fondée de l\'expertise'
            ],
            [
                'content' => 'Je vends des poules pondeuses de race pure, excellente production ! Prix négociable, contactez-moi par MP !',
                'reason' => 'spam',
                'description' => 'Vente d\'animaux en commentaire inapproprié'
            ],
            [
                'content' => 'Vous voulez empoisonner les gens avec vos méthodes de sauvages ! L\'industrie chimique nous protège !',
                'reason' => 'inappropriate_content',
                'description' => 'Accusations infondées et langage hostile'
            ],
            [
                'content' => 'URGENT : Mes plants de tomates géants sont en vente ! 100% naturels, 5kg par tomate garantie ! Commande rapide !',
                'reason' => 'spam',
                'description' => 'Publicité mensongère sur des produits impossibles'
            ],
            [
                'content' => 'Les bio-bobos parisiens qui n\'ont jamais mis les pieds dans un champ viennent donner des leçons ! Ridicule !',
                'reason' => 'inappropriate_content',
                'description' => 'Stéréotypes et mépris social'
            ],
            [
                'content' => 'Foutaises ! Les OGM sont la solution d\'avenir, pas vos techniques moyenâgeuses qui nous ramènent à la famine !',
                'reason' => 'false_information',
                'description' => 'Dénigrement systématique sans argumentation'
            ],
            [
                'content' => 'Formation agriculture intensive weekend prochain ! Doublez vos rendements ! Inscriptions limitées ! www.formation-agri.com',
                'reason' => 'spam',
                'description' => 'Promotion de formation commerciale'
            ],
            [
                'content' => 'Votre site est pourri ! Mes méthodes sont brevetées et vous les volez ! Je vais porter plainte !',
                'reason' => 'harassment',
                'description' => 'Menaces juridiques et accusations de vol'
            ],
            [
                'content' => 'ALERTE ROUGE !!! Ces méthodes provoquent des cancers !!! L\'État nous ment !!! Réveillez-vous !!!',
                'reason' => 'false_information',
                'description' => 'Panique sanitaire non fondée et complotisme'
            ],
            [
                'content' => 'Liquidation totale matériel agricole ! Tracteurs, moissonneuses, prix cassés ! Tél: 0892.XX.XX.XX (0,35€/min)',
                'reason' => 'spam',
                'description' => 'Publicité commerciale avec numéro surtaxé'
            ],
            [
                'content' => 'Les citadins qui font du jardinage du dimanche feraient mieux de retourner à leurs écrans ! Pathétique !',
                'reason' => 'inappropriate_content',
                'description' => 'Mépris et exclusion des jardiniers amateurs'
            ],
            [
                'content' => 'Propaganda écolo-gauchiste ! Les vrais agriculteurs utilisent de la vraie chimie, pas ces tisanes de grand-mère !',
                'reason' => 'hate_speech',
                'description' => 'Politisation agressive et dénigrement'
            ],
            [
                'content' => 'DERNIERE CHANCE !!! Engrais révolutionnaire interdit en France !!! Commande discrète sur dark-fertilizer.onion !!!',
                'reason' => 'spam',
                'description' => 'Promotion de produits potentiellement illégaux'
            ]
        ];
    }

    private function generateRandomIP()
    {
        return rand(1, 255) . '.' . rand(1, 255) . '.' . rand(1, 255) . '.' . rand(1, 255);
    }

    private function getRandomUserAgent()
    {
        $userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.1.1 Safari/605.1.15',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:89.0) Gecko/20100101 Firefox/89.0',
            'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Mozilla/5.0 (iPad; CPU OS 14_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1'
        ];
        
        return $userAgents[array_rand($userAgents)];
    }
}
