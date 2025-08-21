<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Newsletter;
use App\Models\User;
use Carbon\Carbon;

echo "=== Génération de 150 newsletters diversifiées ===\n\n";

// Vérifier l'admin
$adminUser = User::where('email', 's.mef2703@gmail.com')->first();
if (!$adminUser) {
    echo "❌ Utilisateur admin non trouvé!\n";
    exit(1);
}

// Templates de newsletters diversifiés
$newsletterTemplates = [
    // Newsletters de bienvenue
    [
        'category' => 'bienvenue',
        'subjects' => [
            'Bienvenue chez FarmShop ! Votre aventure agricole commence',
            'Merci de nous avoir rejoint ! Découvrez nos services',
            'Bienvenue dans la communauté FarmShop',
            'Votre compte FarmShop est prêt ! Explorez notre univers',
            'Félicitations ! Vous faites maintenant partie de FarmShop'
        ],
        'content_templates' => [
            'Nous sommes ravis de vous accueillir chez FarmShop ! Notre plateforme vous offre accès à plus de 200 produits agricoles et équipements en vente et location.',
            'Bienvenue ! Découvrez dès maintenant notre catalogue complet d\'outils agricoles, semences, et équipements professionnels.',
            'Votre inscription est confirmée ! Explorez nos catégories : tracteurs, outils de jardinage, semences bio et bien plus.',
        ]
    ],
    
    // Newsletters catalogue
    [
        'category' => 'catalogue',
        'subjects' => [
            'Nouveau catalogue automne 2025 - Plus de 50 nouveautés',
            'Découvrez nos derniers équipements agricoles',
            'Catalogue mis à jour - Nouvelles semences disponibles',
            'Équipements d\'automne : préparez votre exploitation',
            'Catalogue printemps : semences et outils de plantation'
        ],
        'content_templates' => [
            'Notre catalogue s\'enrichit avec de nouveaux équipements : tracteurs dernière génération, outils de précision, et semences certifiées.',
            'Découvrez notre sélection d\'équipements pour l\'automne : herses, charrues, épandeurs et matériel de récolte.',
            'Nouvelle gamme disponible : outils bio, semences résistantes, et équipements éco-responsables pour une agriculture durable.',
        ]
    ],
    
    // Newsletters vente
    [
        'category' => 'vente',
        'subjects' => [
            'Promotions exceptionnelles - Jusqu\'à 30% sur les tracteurs',
            'Vente flash : Outils de jardinage à prix réduits',
            'Liquidation stock - Semences à prix cassés',
            'Offre spéciale automne sur tout l\'outillage',
            'Dernière chance : Réductions sur les équipements'
        ],
        'content_templates' => [
            'Profitez de nos promotions exceptionnelles ! Réductions jusqu\'à 30% sur une sélection de tracteurs et équipements lourds.',
            'Vente flash limitée : tous nos outils de jardinage à prix réduits. Stocks limités, commandez rapidement !',
            'Liquidation de notre stock de semences 2024 : variétés premium à prix exceptionnels. Parfait pour vos cultures de printemps.',
        ]
    ],
    
    // Newsletters location
    [
        'category' => 'location',
        'subjects' => [
            'Location d\'équipements : flexibilité pour votre exploitation',
            'Nouveau service location - Tracteurs dès 45€/jour',
            'Location courte durée : équipements disponibles',
            'Service location étendu - Plus d\'équipements disponibles',
            'Location saisonnière : planifiez vos besoins'
        ],
        'content_templates' => [
            'Notre service de location vous offre accès à des équipements professionnels sans investissement lourd. Tracteurs, herses, épandeurs disponibles.',
            'Louez vos équipements selon vos besoins : location journalière, hebdomadaire ou saisonnière. Maintenance incluse.',
            'Service location étendu : plus de 50 équipements disponibles à la location avec service de livraison inclus.',
        ]
    ],
    
    // Newsletters blog
    [
        'category' => 'blog',
        'subjects' => [
            'Nouveau sur le blog : Techniques de culture biologique',
            'Article expert : Optimiser vos rendements cette saison',
            'Blog FarmShop : Conseils pour l\'entretien des tracteurs',
            'Découvrez nos articles : Agriculture de précision',
            'Nouveau guide : Rotation des cultures expliquée'
        ],
        'content_templates' => [
            'Découvrez notre dernier article sur les techniques de culture biologique : méthodes naturelles pour des rendements optimaux.',
            'Notre expert partage ses conseils pour optimiser vos rendements cette saison : planification, choix des semences, et timing.',
            'Guide complet sur l\'entretien préventif de vos tracteurs : prolongez leur durée de vie et évitez les pannes.',
        ]
    ],
    
    // Newsletters profil/compte
    [
        'category' => 'profil',
        'subjects' => [
            'Optimisez votre profil FarmShop pour plus d\'efficacité',
            'Nouvelles fonctionnalités dans votre espace client',
            'Gérez vos commandes depuis votre tableau de bord',
            'Votre espace client s\'améliore : découvrez les nouveautés',
            'Profil complété = Avantages exclusifs'
        ],
        'content_templates' => [
            'Optimisez votre expérience FarmShop en complétant votre profil : recommandations personnalisées et offres exclusives.',
            'Découvrez les nouvelles fonctionnalités de votre espace client : suivi commandes, historique locations, factures.',
            'Votre tableau de bord évolue : gestion simplifiée de vos commandes, locations et communications.',
        ]
    ],
    
    // Newsletters promotionnelles
    [
        'category' => 'promo',
        'subjects' => [
            'Code promo exclusif : FARM2025 pour 15% de réduction',
            'Offre limitée : Livraison gratuite cette semaine',
            'Black Friday agricole : Réductions exceptionnelles',
            'Soldes d\'été : Équipement à prix réduits',
            'Parrainage récompensé : Gagnez des bons d\'achat'
        ],
        'content_templates' => [
            'Utilisez le code FARM2025 pour bénéficier de 15% de réduction sur votre prochaine commande. Valable jusqu\'au 30 septembre.',
            'Cette semaine seulement : livraison gratuite sur toutes vos commandes. Profitez-en pour compléter votre équipement !',
            'Black Friday agricole : réductions jusqu\'à 40% sur une sélection d\'équipements et semences premium.',
        ]
    ],
    
    // Newsletters saisonnières
    [
        'category' => 'saison',
        'subjects' => [
            'Préparez votre exploitation pour l\'automne',
            'Printemps : Guide complet des semis et plantations',
            'Été 2025 : Irrigation et protection des cultures',
            'Hiver : Entretien et préparation du matériel',
            'Calendrier agricole : Planifiez votre année'
        ],
        'content_templates' => [
            'L\'automne approche : préparez vos sols, planifiez vos récoltes et entretenez votre matériel pour la saison.',
            'Guide printemps : calendrier des semis, choix des variétés, et préparation optimale de vos parcelles.',
            'Conseils été : systèmes d\'irrigation, protection contre les nuisibles, et gestion de la sécheresse.',
        ]
    ],
    
    // Newsletters techniques
    [
        'category' => 'technique',
        'subjects' => [
            'Innovation : Agriculture de précision et GPS',
            'Maintenance préventive : Prolongez la vie de vos machines',
            'Technique : Analyse de sol et fertilisation',
            'Expert : Choix des semences selon votre région',
            'Guide technique : Réglage optimal des équipements'
        ],
        'content_templates' => [
            'Découvrez les innovations en agriculture de précision : GPS, capteurs, et analyse de données pour optimiser vos rendements.',
            'Guide maintenance : check-list complète pour l\'entretien préventif de vos tracteurs et équipements.',
            'Analyse de sol approfondie : comprendre les besoins de vos parcelles pour une fertilisation ciblée.',
        ]
    ]
];

// Compteurs de distribution
$statusDistribution = [
    'sent' => 100,     // 100 envoyées
    'scheduled' => 30, // 30 programmées 
    'draft' => 20      // 20 brouillons (en attente)
];

$generatedNewsletters = [];
$totalNewsletters = 0;

foreach ($statusDistribution as $status => $count) {
    echo "🔄 Génération de {$count} newsletters avec statut '{$status}'...\n";
    
    for ($i = 0; $i < $count; $i++) {
        // Sélectionner une catégorie aléatoire
        $template = $newsletterTemplates[array_rand($newsletterTemplates)];
        $subject = $template['subjects'][array_rand($template['subjects'])];
        $contentTemplate = $template['content_templates'][array_rand($template['content_templates'])];
        
        // Générer du contenu enrichi
        $content = generateRichContent($contentTemplate, $template['category']);
        
        // Générer des dates cohérentes
        $dates = generateDatesForNewsletter($status);
        
        // Statistiques réalistes selon le statut
        $stats = generateNewsletterStats($status);
        
        // Tags selon la catégorie
        $tags = generateTags($template['category']);
        
        $newsletterData = [
            'title' => generateTitle($subject),
            'subject' => $subject,
            'content' => $content,
            'excerpt' => generateExcerpt($contentTemplate),
            'featured_image' => generateFeaturedImage($template['category']),
            'status' => $status,
            'recipients_count' => $stats['recipients_count'],
            'sent_count' => $stats['sent_count'],
            'failed_count' => $stats['failed_count'],
            'opened_count' => $stats['opened_count'],
            'clicked_count' => $stats['clicked_count'],
            'unsubscribed_count' => $stats['unsubscribed_count'],
            'tags' => $tags,
            'metadata' => [
                'category' => $template['category'],
                'campaign_type' => getCampaignType($template['category']),
                'priority' => rand(1, 3)
            ],
            'is_template' => false,
            'created_by' => $adminUser->id,
            'updated_by' => $adminUser->id,
            'created_at' => $dates['created_at'],
            'updated_at' => $dates['updated_at']
        ];
        
        // Ajouter les dates spécifiques selon le statut
        if ($status === 'sent') {
            $newsletterData['sent_at'] = $dates['sent_at'];
        } elseif ($status === 'scheduled') {
            $newsletterData['scheduled_at'] = $dates['scheduled_at'];
        }
        
        try {
            $newsletter = Newsletter::create($newsletterData);
            $generatedNewsletters[] = $newsletter;
            $totalNewsletters++;
        } catch (Exception $e) {
            echo "❌ Erreur lors de la création de la newsletter : " . $e->getMessage() . "\n";
        }
    }
}

echo "\n✅ Génération terminée !\n\n";
echo "📊 Résumé :\n";
echo "- Total newsletters générées : {$totalNewsletters}\n";
echo "- Newsletters envoyées : " . Newsletter::where('status', 'sent')->count() . "\n";
echo "- Newsletters programmées : " . Newsletter::where('status', 'scheduled')->count() . "\n";
echo "- Brouillons : " . Newsletter::where('status', 'draft')->count() . "\n";

// Fonctions utilitaires

function generateDatesForNewsletter($status) {
    $now = Carbon::now();
    
    switch ($status) {
        case 'sent':
            $createdAt = $now->copy()->subDays(rand(1, 60));
            $sentAt = $createdAt->copy()->addDays(rand(0, 7));
            return [
                'created_at' => $createdAt,
                'updated_at' => $sentAt,
                'sent_at' => $sentAt
            ];
            
        case 'scheduled':
            $createdAt = $now->copy()->subDays(rand(1, 15));
            $scheduledAt = $now->copy()->addDays(rand(1, 30));
            return [
                'created_at' => $createdAt,
                'updated_at' => $createdAt->copy()->addHours(rand(1, 48)),
                'scheduled_at' => $scheduledAt
            ];
            
        case 'draft':
            $createdAt = $now->copy()->subDays(rand(1, 30));
            return [
                'created_at' => $createdAt,
                'updated_at' => $createdAt->copy()->addHours(rand(1, 72))
            ];
    }
}

function generateRichContent($baseContent, $category) {
    $additions = [
        'bienvenue' => "\n\nPour commencer :\n• Explorez notre catalogue\n• Configurez vos préférences\n• Contactez notre équipe support",
        'catalogue' => "\n\nNouvelles catégories :\n• Équipements électriques\n• Outils connectés\n• Solutions bio\n\nConsultez le catalogue complet sur notre site.",
        'vente' => "\n\nConditions de l'offre :\n• Valable jusqu'au stock épuisé\n• Livraison incluse\n• Garantie constructeur\n\nCommandez dès maintenant !",
        'location' => "\n\nAvantages location :\n• Maintenance incluse\n• Livraison/récupération\n• Tarifs dégressifs\n• Support technique",
        'blog' => "\n\nÀ lire également :\n• Archives du blog\n• Guides pratiques\n• Témoignages clients\n• Actualités secteur",
        'profil' => "\n\nFonctionnalités disponibles :\n• Suivi commandes temps réel\n• Historique détaillé\n• Recommandations personnalisées\n• Support prioritaire",
        'promo' => "\n\nConditions :\n• Offre limitée dans le temps\n• Non cumulable avec autres promotions\n• Voir conditions générales\n• Stock limité",
        'saison' => "\n\nConseils de saison :\n• Vérifiez la météo\n• Préparez vos équipements\n• Planifiez vos achats\n• Consultez notre calendrier",
        'technique' => "\n\nRessources techniques :\n• Fiches produits détaillées\n• Vidéos explicatives\n• Support technique\n• Formation utilisateurs"
    ];
    
    return $baseContent . ($additions[$category] ?? '');
}

function generateNewsletterStats($status) {
    switch ($status) {
        case 'sent':
            $recipients = rand(500, 2000);
            $sent = $recipients - rand(0, 10);
            $opened = rand(100, intval($sent * 0.4));
            $clicked = rand(10, intval($opened * 0.3));
            return [
                'recipients_count' => $recipients,
                'sent_count' => $sent,
                'failed_count' => $recipients - $sent,
                'opened_count' => $opened,
                'clicked_count' => $clicked,
                'unsubscribed_count' => rand(0, 5)
            ];
            
        case 'scheduled':
            $recipients = rand(500, 2000);
            return [
                'recipients_count' => $recipients,
                'sent_count' => 0,
                'failed_count' => 0,
                'opened_count' => 0,
                'clicked_count' => 0,
                'unsubscribed_count' => 0
            ];
            
        default: // draft
            return [
                'recipients_count' => 0,
                'sent_count' => 0,
                'failed_count' => 0,
                'opened_count' => 0,
                'clicked_count' => 0,
                'unsubscribed_count' => 0
            ];
    }
}

function generateTitle($subject) {
    return "Newsletter FarmShop - " . $subject;
}

function generateExcerpt($content) {
    return substr($content, 0, 150) . "...";
}

function generateFeaturedImage($category) {
    $images = [
        'bienvenue' => 'newsletters/welcome-farmshop.jpg',
        'catalogue' => 'newsletters/catalog-equipment.jpg',
        'vente' => 'newsletters/sales-promotion.jpg',
        'location' => 'newsletters/rental-services.jpg',
        'blog' => 'newsletters/blog-articles.jpg',
        'profil' => 'newsletters/user-profile.jpg',
        'promo' => 'newsletters/special-offers.jpg',
        'saison' => 'newsletters/seasonal-guide.jpg',
        'technique' => 'newsletters/technical-guide.jpg'
    ];
    
    return $images[$category] ?? 'newsletters/default.jpg';
}

function generateTags($category) {
    $baseTags = ['newsletter', 'farmshop'];
    
    $categoryTags = [
        'bienvenue' => ['bienvenue', 'nouveau-client', 'onboarding'],
        'catalogue' => ['catalogue', 'produits', 'nouveautés'],
        'vente' => ['vente', 'promotion', 'offre-spéciale'],
        'location' => ['location', 'service', 'équipement'],
        'blog' => ['blog', 'article', 'conseil'],
        'profil' => ['profil', 'compte', 'fonctionnalités'],
        'promo' => ['promotion', 'réduction', 'code-promo'],
        'saison' => ['saisonnier', 'calendrier', 'planning'],
        'technique' => ['technique', 'guide', 'expert']
    ];
    
    return array_merge($baseTags, $categoryTags[$category] ?? []);
}

function getCampaignType($category) {
    $types = [
        'bienvenue' => 'welcome',
        'catalogue' => 'product_announcement',
        'vente' => 'promotional',
        'location' => 'service_update',
        'blog' => 'content_marketing',
        'profil' => 'feature_announcement',
        'promo' => 'promotional',
        'saison' => 'seasonal',
        'technique' => 'educational'
    ];
    
    return $types[$category] ?? 'general';
}

echo "\n🎉 Les newsletters sont maintenant visibles sur http://127.0.0.1:8000/admin/newsletters\n";
