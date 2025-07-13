<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer l'admin (destinataire de tous les messages)
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $this->command->error('Aucun administrateur trouvé. Créez d\'abord un admin.');
            return;
        }

        // Récupérer les utilisateurs existants (sans les admins) pour les messages d'utilisateurs inscrits
        $users = User::where('role', '!=', 'admin')->get();
        
        if ($users->count() < 20) {
            $this->command->error('Il faut au moins 20 utilisateurs non-admin pour créer les messages');
            return;
        }

        // 50 messages de visiteurs (non-inscrits)
        $visitorMessages = $this->getVisitorMessagesData();
        
        foreach ($visitorMessages as $messageData) {
            Message::create([
                'user_id' => $admin->id, // Destinataire : admin
                'sender_id' => null, // Expéditeur : visiteur non-inscrit
                'subject' => $messageData['subject'],
                'content' => $messageData['content'],
                'type' => 'contact',
                'status' => $messageData['status'],
                'priority' => $messageData['priority'],
                'is_important' => $messageData['is_important'],
                'metadata' => [
                    'sender_name' => $messageData['sender_name'],
                    'sender_email' => $messageData['sender_email'],
                    'sender_phone' => $messageData['sender_phone'] ?? null,
                    'contact_reason' => $messageData['contact_reason'],
                    'migrated_from_contacts' => false,
                    'admin_responded' => $messageData['admin_responded'] ?? false,
                ],
                'read_at' => $messageData['read_at'],
                'created_at' => $messageData['created_at'],
                'updated_at' => $messageData['created_at'],
            ]);
        }

        // 50 messages d'utilisateurs inscrits
        $userMessages = $this->getUserMessagesData();
        
        foreach ($userMessages as $messageData) {
            $randomUser = $users->random();
            
            Message::create([
                'user_id' => $admin->id, // Destinataire : admin
                'sender_id' => $randomUser->id, // Expéditeur : utilisateur inscrit
                'subject' => $messageData['subject'],
                'content' => $messageData['content'],
                'type' => 'contact',
                'status' => $messageData['status'],
                'priority' => $messageData['priority'],
                'is_important' => $messageData['is_important'],
                'metadata' => [
                    'contact_reason' => $messageData['contact_reason'],
                    'admin_responded' => $messageData['admin_responded'] ?? false,
                ],
                'read_at' => $messageData['read_at'],
                'created_at' => $messageData['created_at'],
                'updated_at' => $messageData['created_at'],
            ]);
        }

        $this->command->info('100 messages créés avec succès (50 visiteurs + 50 utilisateurs)');
    }

    /**
     * Données pour les messages de visiteurs
     */
    private function getVisitorMessagesData(): array
    {
        $visitorNames = [
            ['name' => 'Marc Lefevre', 'email' => 'marc.lefevre@gmail.com', 'phone' => '06.12.34.56.78'],
            ['name' => 'Julie Moreau', 'email' => 'julie.moreau@outlook.fr', 'phone' => '07.23.45.67.89'],
            ['name' => 'David Martinez', 'email' => 'david.martinez@yahoo.fr', 'phone' => null],
            ['name' => 'Sophie Nguyen', 'email' => 'sophie.nguyen@hotmail.com', 'phone' => '06.34.56.78.90'],
            ['name' => 'Paul Dubois', 'email' => 'paul.dubois@free.fr', 'phone' => '07.45.67.89.01'],
            ['name' => 'Claire Bernard', 'email' => 'claire.bernard@orange.fr', 'phone' => null],
            ['name' => 'Antoine Rousseau', 'email' => 'antoine.rousseau@sfr.fr', 'phone' => '06.56.78.90.12'],
            ['name' => 'Lisa Chen', 'email' => 'lisa.chen@protonmail.com', 'phone' => '07.67.89.01.23'],
            ['name' => 'Kevin Lambert', 'email' => 'kevin.lambert@wanadoo.fr', 'phone' => null],
            ['name' => 'Emilie Garnier', 'email' => 'emilie.garnier@laposte.net', 'phone' => '06.78.90.12.34'],
        ];

        $subjects = [
            'Question sur vos produits bio',
            'Demande de devis pour matériel agricole',
            'Problème avec ma commande',
            'Disponibilité des semences de tomates',
            'Renseignements sur la livraison',
            'Prix des engrais naturels',
            'Formation en agriculture biologique',
            'Partenariat commercial',
            'Réclamation produit défectueux',
            'Conseil pour potager urbain',
            'Catalogue 2025',
            'Méthodes de paiement acceptées',
            'Délais de livraison en zone rurale',
            'Produits pour permaculture',
            'Assurance qualité bio',
            'Programme de fidélité',
            'Retour produit non conforme',
            'Conseils plantation automne',
            'Stock graines anciennes',
            'Service après-vente',
        ];

        $contents = [
            "Bonjour,\n\nJe suis intéressé par vos produits biologiques, notamment les graines potagères. Pourriez-vous m'envoyer votre catalogue complet ?\n\nCordialement.",
            "Bonjour,\n\nJe souhaiterais obtenir un devis pour l'achat de matériel agricole : bêches, serfouettes et arrosoirs. Je suis un particulier avec un potager de 200m².\n\nMerci d'avance.",
            "Bonsoir,\n\nJ'ai passé commande il y a une semaine (réf: #12345) mais je n'ai toujours pas reçu mes produits. Pouvez-vous me donner des nouvelles ?\n\nBien à vous.",
            "Bonjour,\n\nAvez-vous encore en stock des graines de tomates anciennes ? Je cherche particulièrement la variété 'Cœur de Bœuf'.\n\nMerci pour votre réponse.",
            "Bonjour,\n\nQuels sont vos délais de livraison pour la région Provence-Alpes-Côte d'Azur ? Livrez-vous jusqu'en zone rurale ?\n\nCordialement.",
            "Bonjour,\n\nPourriez-vous m'indiquer les prix de vos engrais naturels, notamment le compost et le fumier de cheval ?\n\nMerci beaucoup.",
            "Bonjour,\n\nProposez-vous des formations ou des ateliers sur l'agriculture biologique ? Je débute dans ce domaine.\n\nBonne journée.",
            "Bonjour,\n\nJe représente une coopérative agricole et nous aimerions étudier un partenariat commercial. Pouvons-nous organiser un rendez-vous ?\n\nCordialement.",
            "Bonjour,\n\nJ'ai reçu ma commande mais l'un des outils (bêche) présente un défaut de fabrication. Comment procéder pour un échange ?\n\nMerci.",
            "Bonjour,\n\nJe vis en appartement et souhaite créer un potager sur mon balcon. Quels conseils pourriez-vous me donner ?\n\nBien à vous.",
            "Bonjour,\n\nVotre nouveau catalogue 2025 est-il disponible ? J'aimerais consulter vos nouveautés.\n\nCordialement.",
            "Bonjour,\n\nQuels sont les moyens de paiement que vous acceptez ? Prenez-vous les chèques et virements ?\n\nMerci.",
            "Bonjour,\n\nJ'habite dans un petit village isolé. Pouvez-vous livrer jusqu'ici ? Y a-t-il des frais supplémentaires ?\n\nBonne journée.",
            "Bonjour,\n\nJe m'intéresse à la permaculture. Avez-vous des produits spécialement adaptés à cette pratique ?\n\nCordialement.",
            "Bonjour,\n\nComment puis-je être sûr de la qualité biologique de vos produits ? Avez-vous des certifications ?\n\nMerci.",
        ];

        $reasons = ['question', 'support', 'commande', 'autre'];
        $statuses = ['unread', 'read', 'archived'];
        $priorities = ['normal', 'high', 'urgent', 'low'];

        $messages = [];
        
        for ($i = 0; $i < 50; $i++) {
            $visitor = $visitorNames[$i % count($visitorNames)];
            $createdAt = Carbon::now()->subDays(rand(1, 60))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
            $status = $statuses[array_rand($statuses)];
            
            $messages[] = [
                'sender_name' => $visitor['name'],
                'sender_email' => $visitor['email'],
                'sender_phone' => $visitor['phone'],
                'subject' => $subjects[$i % count($subjects)],
                'content' => $contents[$i % count($contents)],
                'contact_reason' => $reasons[array_rand($reasons)],
                'status' => $status,
                'priority' => $priorities[array_rand($priorities)],
                'is_important' => rand(0, 100) < 15, // 15% de chance d'être important
                'read_at' => in_array($status, ['read', 'archived']) ? $createdAt->copy()->addHours(rand(1, 48)) : null,
                'admin_responded' => $status === 'archived', // Supposons que archivé = répondu
                'created_at' => $createdAt,
            ];
        }

        return $messages;
    }

    /**
     * Données pour les messages d'utilisateurs inscrits
     */
    private function getUserMessagesData(): array
    {
        $subjects = [
            'Mise à jour de mon profil',
            'Problème de connexion',
            'Changement d\'adresse de livraison',
            'Suivi de ma commande',
            'Remboursement demandé',
            'Amélioration suggestion',
            'Bug sur le site web',
            'Modification commande en cours',
            'Programme de parrainage',
            'Facture introuvable',
            'Réduction fidélité',
            'Produit indisponible',
            'Livraison retardée',
            'Qualité produit',
            'Nouveau mot de passe',
            'Compte suspendu',
            'Newsletter désinscription',
            'Service client',
            'Garantie produit',
            'Échange article',
        ];

        $contents = [
            "Bonjour,\n\nJe souhaiterais mettre à jour les informations de mon profil, notamment mon adresse et mon numéro de téléphone.\n\nPouvez-vous m'indiquer la procédure ?\n\nMerci.",
            "Bonjour,\n\nJe n'arrive plus à me connecter à mon compte depuis ce matin. Mon mot de passe semble ne plus fonctionner.\n\nPouvez-vous m'aider ?\n\nCordialement.",
            "Bonjour,\n\nJe viens de déménager et j'aimerais changer mon adresse de livraison pour mes prochaines commandes.\n\nComment procéder ?\n\nMerci beaucoup.",
            "Bonjour,\n\nJ'ai passé commande il y a 3 jours mais je n'ai aucune information sur l'expédition. Pouvez-vous me donner le statut ?\n\nBien à vous.",
            "Bonjour,\n\nJe souhaiterais être remboursé pour ma dernière commande qui ne correspond pas à mes attentes.\n\nQuelle est la procédure ?\n\nCordialement.",
            "Bonjour,\n\nJ'aimerais suggérer une amélioration pour votre site : ajouter un système de notation des produits.\n\nQu'en pensez-vous ?\n\nBonne journée.",
            "Bonjour,\n\nJe rencontre un bug sur votre site : le panier ne se met pas à jour quand je modifie les quantités.\n\nPouvez-vous corriger cela ?\n\nMerci.",
            "Bonjour,\n\nJ'aimerais modifier ma commande en cours (ajouter un article). Est-ce encore possible ?\n\nMerci pour votre réponse rapide.",
            "Bonjour,\n\nComment fonctionne votre programme de parrainage ? Quels sont les avantages pour le parrain et le filleul ?\n\nCordialement.",
            "Bonjour,\n\nJe ne trouve plus ma facture de commande du mois dernier. Pouvez-vous me la renvoyer par email ?\n\nMerci d'avance.",
            "Bonjour,\n\nEn tant que client fidèle, puis-je bénéficier d'une réduction sur ma prochaine commande ?\n\nBien à vous.",
            "Bonjour,\n\nLe produit que je voulais commander est marqué comme indisponible. Quand sera-t-il de nouveau en stock ?\n\nMerci.",
            "Bonjour,\n\nMa livraison prévue hier n'est toujours pas arrivée. Y a-t-il un problème avec le transporteur ?\n\nCordialement.",
            "Bonjour,\n\nJe suis très satisfait de la qualité de vos produits ! Continuez ainsi.\n\nJuste un petit retour positif.",
            "Bonjour,\n\nJ'ai oublié mon mot de passe et la fonction de récupération ne fonctionne pas. Pouvez-vous m'aider ?\n\nMerci.",
        ];

        $reasons = ['question', 'support', 'commande', 'autre'];
        $statuses = ['unread', 'read', 'archived'];
        $priorities = ['normal', 'high', 'urgent', 'low'];

        $messages = [];
        
        for ($i = 0; $i < 50; $i++) {
            $createdAt = Carbon::now()->subDays(rand(1, 45))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
            $status = $statuses[array_rand($statuses)];
            
            $messages[] = [
                'subject' => $subjects[$i % count($subjects)],
                'content' => $contents[$i % count($contents)],
                'contact_reason' => $reasons[array_rand($reasons)],
                'status' => $status,
                'priority' => $priorities[array_rand($priorities)],
                'is_important' => rand(0, 100) < 10, // 10% de chance d'être important
                'read_at' => in_array($status, ['read', 'archived']) ? $createdAt->copy()->addHours(rand(1, 72)) : null,
                'admin_responded' => $status === 'archived', // Supposons que archivé = répondu
                'created_at' => $createdAt,
            ];
        }

        return $messages;
    }
}
