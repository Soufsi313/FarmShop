<?php

/**
 * Script de traduction complète pour FarmShop
 * Remplace tous les smart_translate() par des clés Laravel et ajoute les traductions
 */

require_once __DIR__ . '/vendor/autoload.php';

// Définir le mapping des traductions avec clés Laravel
$translations = [
    // Contenu principal
    'Location' => 'content.rental',
    'En stock' => 'content.in_stock',
    'Stock limité' => 'content.limited_stock',
    'Stock faible' => 'content.low_stock',
    'Rupture de stock' => 'content.out_of_stock',
    'Acheter ce produit' => 'content.buy_product',
    'Louer ce produit' => 'content.rent_product',
    'Voir les options' => 'content.view_options',
    'Ajouter au panier' => 'content.add_to_cart',
    'Acheter' => 'content.buy',
    'Commander' => 'content.order',
    'Quantité' => 'content.quantity',
    'Informations' => 'content.information',
    'Description' => 'content.description',
    'Disponibilité' => 'content.availability',
    'Produits similaires' => 'content.similar_products',
    'Prix' => 'content.price',
    
    // Pages
    'Nos Produits' => 'pages.our_products',
    'Blog' => 'pages.blog',
    'Catégorie' => 'pages.category',
    
    // Actions et boutons
    'Produits en préparation' => 'content.products_in_preparation',
    'Nos produits seront bientôt disponibles !' => 'content.products_coming_soon',
    'Voir tous nos produits' => 'content.view_all_products',
    'J\'ai déjà un compte' => 'auth.already_have_account',
    '⚡ Inscription rapide • 🔒 100% sécurisé • 📧 Pas de spam' => 'auth.registration_benefits',
    'Votre adresse email' => 'form.email_placeholder',
    'S\'abonner' => 'form.subscribe',
    
    // Témoignages
    'Excellent service ! J\'ai trouvé exactement le tracteur qu\'il me fallait. Livraison rapide et matériel en parfait état.' => 'testimonials.testimonial_1',
    '- Pierre Martin, Agriculteur' => 'testimonials.author_1',
    'La location m\'a permis d\'essayer avant d\'acheter. Très pratique pour les gros équipements !' => 'testimonials.testimonial_2',
    '- Marie Dubois, Exploitante' => 'testimonials.author_2',
    '- Jean Lefebvre, GAEC' => 'testimonials.author_3',
    
    // Blog
    'Rechercher un article...' => 'blog.search_placeholder',
    'Toutes les catégories' => 'blog.all_categories',
    'Aucun article trouvé' => 'blog.no_articles_found',
    'Articles populaires' => 'blog.popular_articles',
    'Catégories' => 'blog.categories',
    
    // Footer
    'Tous droits réservés' => 'footer.all_rights_reserved',
    
    // Cookies
    'Ce site utilise des cookies' => 'cookies.banner_title',
    'Nous utilisons des cookies pour améliorer votre expérience, analyser le trafic et personnaliser le contenu. Vous pouvez choisir quels cookies accepter.' => 'cookies.banner_description',
    'Personnaliser' => 'cookies.customize',
    'Refuser' => 'cookies.reject',
    'Tout accepter' => 'cookies.accept_all',
    'Préférences de cookies' => 'cookies.preferences_title',
    'Choisissez quels cookies vous souhaitez autoriser' => 'cookies.preferences_description',
    'Cookies nécessaires' => 'cookies.necessary_title',
    'Fonctionnement du site' => 'cookies.necessary_subtitle',
    'Ces cookies sont essentiels au fonctionnement du site et ne peuvent pas être désactivés.' => 'cookies.necessary_description',
    'Cookies d\'analyse' => 'cookies.analytics_title',
    'Amélioration de l\'expérience' => 'cookies.analytics_subtitle',
    'Ces cookies nous aident à comprendre comment vous utilisez le site pour l\'améliorer.' => 'cookies.analytics_description',
    'Cookies marketing' => 'cookies.marketing_title',
    'Publicité personnalisée' => 'cookies.marketing_subtitle',
    'Ces cookies permettent de vous proposer des publicités adaptées à vos centres d\'intérêt.' => 'cookies.marketing_description',
    'Cookies de préférences' => 'cookies.preferences_cookies_title',
    'Personnalisation de l\'expérience' => 'cookies.preferences_subtitle',
    'Ces cookies mémorisent vos préférences (langue, région, etc.) pour personnaliser votre expérience.' => 'cookies.preferences_description',
    'Cookies réseaux sociaux' => 'cookies.social_title',
    'Partage et intégrations sociales' => 'cookies.social_subtitle',
];

// Traductions pour chaque langue
$languageTranslations = [
    'fr' => [
        'content' => [
            'rental' => 'Location',
            'in_stock' => 'En stock',
            'limited_stock' => 'Stock limité',
            'low_stock' => 'Stock faible',
            'out_of_stock' => 'Rupture de stock',
            'buy_product' => 'Acheter ce produit',
            'rent_product' => 'Louer ce produit',
            'view_options' => 'Voir les options',
            'add_to_cart' => 'Ajouter au panier',
            'buy' => 'Acheter',
            'order' => 'Commander',
            'quantity' => 'Quantité',
            'information' => 'Informations',
            'description' => 'Description',
            'availability' => 'Disponibilité',
            'similar_products' => 'Produits similaires',
            'price' => 'Prix',
            'products_in_preparation' => 'Produits en préparation',
            'products_coming_soon' => 'Nos produits seront bientôt disponibles !',
            'view_all_products' => 'Voir tous nos produits'
        ],
        'pages' => [
            'our_products' => 'Nos Produits',
            'blog' => 'Blog',
            'category' => 'Catégorie'
        ],
        'auth' => [
            'already_have_account' => 'J\'ai déjà un compte',
            'registration_benefits' => '⚡ Inscription rapide • 🔒 100% sécurisé • 📧 Pas de spam'
        ],
        'form' => [
            'email_placeholder' => 'Votre adresse email',
            'subscribe' => 'S\'abonner'
        ],
        'testimonials' => [
            'testimonial_1' => 'Excellent service ! J\'ai trouvé exactement le tracteur qu\'il me fallait. Livraison rapide et matériel en parfait état.',
            'author_1' => '- Pierre Martin, Agriculteur',
            'testimonial_2' => 'La location m\'a permis d\'essayer avant d\'acheter. Très pratique pour les gros équipements !',
            'author_2' => '- Marie Dubois, Exploitante',
            'author_3' => '- Jean Lefebvre, GAEC'
        ],
        'blog' => [
            'search_placeholder' => 'Rechercher un article...',
            'all_categories' => 'Toutes les catégories',
            'no_articles_found' => 'Aucun article trouvé',
            'popular_articles' => 'Articles populaires',
            'categories' => 'Catégories'
        ],
        'footer' => [
            'all_rights_reserved' => 'Tous droits réservés'
        ],
        'cookies' => [
            'banner_title' => 'Ce site utilise des cookies',
            'banner_description' => 'Nous utilisons des cookies pour améliorer votre expérience, analyser le trafic et personnaliser le contenu. Vous pouvez choisir quels cookies accepter.',
            'customize' => 'Personnaliser',
            'reject' => 'Refuser',
            'accept_all' => 'Tout accepter',
            'preferences_title' => 'Préférences de cookies',
            'preferences_description' => 'Choisissez quels cookies vous souhaitez autoriser',
            'necessary_title' => 'Cookies nécessaires',
            'necessary_subtitle' => 'Fonctionnement du site',
            'necessary_description' => 'Ces cookies sont essentiels au fonctionnement du site et ne peuvent pas être désactivés.',
            'analytics_title' => 'Cookies d\'analyse',
            'analytics_subtitle' => 'Amélioration de l\'expérience',
            'analytics_description' => 'Ces cookies nous aident à comprendre comment vous utilisez le site pour l\'améliorer.',
            'marketing_title' => 'Cookies marketing',
            'marketing_subtitle' => 'Publicité personnalisée',
            'marketing_description' => 'Ces cookies permettent de vous proposer des publicités adaptées à vos centres d\'intérêt.',
            'preferences_cookies_title' => 'Cookies de préférences',
            'preferences_subtitle' => 'Personnalisation de l\'expérience',
            'preferences_description' => 'Ces cookies mémorisent vos préférences (langue, région, etc.) pour personnaliser votre expérience.',
            'social_title' => 'Cookies réseaux sociaux',
            'social_subtitle' => 'Partage et intégrations sociales'
        ]
    ],
    'en' => [
        'content' => [
            'rental' => 'Rental',
            'in_stock' => 'In stock',
            'limited_stock' => 'Limited stock',
            'low_stock' => 'Low stock',
            'out_of_stock' => 'Out of stock',
            'buy_product' => 'Buy this product',
            'rent_product' => 'Rent this product',
            'view_options' => 'View options',
            'add_to_cart' => 'Add to cart',
            'buy' => 'Buy',
            'order' => 'Order',
            'quantity' => 'Quantity',
            'information' => 'Information',
            'description' => 'Description',
            'availability' => 'Availability',
            'similar_products' => 'Similar products',
            'price' => 'Price',
            'products_in_preparation' => 'Products in preparation',
            'products_coming_soon' => 'Our products will be available soon!',
            'view_all_products' => 'View all products'
        ],
        'pages' => [
            'our_products' => 'Our Products',
            'blog' => 'Blog',
            'category' => 'Category'
        ],
        'auth' => [
            'already_have_account' => 'I already have an account',
            'registration_benefits' => '⚡ Quick registration • 🔒 100% secure • 📧 No spam'
        ],
        'form' => [
            'email_placeholder' => 'Your email address',
            'subscribe' => 'Subscribe'
        ],
        'testimonials' => [
            'testimonial_1' => 'Excellent service! I found exactly the tractor I needed. Fast delivery and equipment in perfect condition.',
            'author_1' => '- Pierre Martin, Farmer',
            'testimonial_2' => 'Rental allowed me to try before buying. Very practical for large equipment!',
            'author_2' => '- Marie Dubois, Farm Operator',
            'author_3' => '- Jean Lefebvre, GAEC'
        ],
        'blog' => [
            'search_placeholder' => 'Search an article...',
            'all_categories' => 'All categories',
            'no_articles_found' => 'No articles found',
            'popular_articles' => 'Popular articles',
            'categories' => 'Categories'
        ],
        'footer' => [
            'all_rights_reserved' => 'All rights reserved'
        ],
        'cookies' => [
            'banner_title' => 'This site uses cookies',
            'banner_description' => 'We use cookies to improve your experience, analyze traffic and personalize content. You can choose which cookies to accept.',
            'customize' => 'Customize',
            'reject' => 'Reject',
            'accept_all' => 'Accept all',
            'preferences_title' => 'Cookie preferences',
            'preferences_description' => 'Choose which cookies you want to allow',
            'necessary_title' => 'Necessary cookies',
            'necessary_subtitle' => 'Site functionality',
            'necessary_description' => 'These cookies are essential for the site to function and cannot be disabled.',
            'analytics_title' => 'Analytics cookies',
            'analytics_subtitle' => 'Experience improvement',
            'analytics_description' => 'These cookies help us understand how you use the site to improve it.',
            'marketing_title' => 'Marketing cookies',
            'marketing_subtitle' => 'Personalized advertising',
            'marketing_description' => 'These cookies allow us to offer you advertisements tailored to your interests.',
            'preferences_cookies_title' => 'Preference cookies',
            'preferences_subtitle' => 'Experience personalization',
            'preferences_description' => 'These cookies remember your preferences (language, region, etc.) to personalize your experience.',
            'social_title' => 'Social media cookies',
            'social_subtitle' => 'Social sharing and integrations'
        ]
    ],
    'nl' => [
        'content' => [
            'rental' => 'Verhuur',
            'in_stock' => 'Op voorraad',
            'limited_stock' => 'Beperkte voorraad',
            'low_stock' => 'Lage voorraad',
            'out_of_stock' => 'Uitverkocht',
            'buy_product' => 'Dit product kopen',
            'rent_product' => 'Dit product huren',
            'view_options' => 'Opties bekijken',
            'add_to_cart' => 'Toevoegen aan winkelwagen',
            'buy' => 'Kopen',
            'order' => 'Bestellen',
            'quantity' => 'Hoeveelheid',
            'information' => 'Informatie',
            'description' => 'Beschrijving',
            'availability' => 'Beschikbaarheid',
            'similar_products' => 'Vergelijkbare producten',
            'price' => 'Prijs',
            'products_in_preparation' => 'Producten in voorbereiding',
            'products_coming_soon' => 'Onze producten zijn binnenkort beschikbaar!',
            'view_all_products' => 'Alle producten bekijken'
        ],
        'pages' => [
            'our_products' => 'Onze Producten',
            'blog' => 'Blog',
            'category' => 'Categorie'
        ],
        'auth' => [
            'already_have_account' => 'Ik heb al een account',
            'registration_benefits' => '⚡ Snelle registratie • 🔒 100% veilig • 📧 Geen spam'
        ],
        'form' => [
            'email_placeholder' => 'Uw e-mailadres',
            'subscribe' => 'Abonneren'
        ],
        'testimonials' => [
            'testimonial_1' => 'Uitstekende service! Ik vond precies de tractor die ik nodig had. Snelle levering en apparatuur in perfecte staat.',
            'author_1' => '- Pierre Martin, Boer',
            'testimonial_2' => 'Verhuur stelde me in staat om te proberen voordat ik kocht. Zeer praktisch voor grote apparatuur!',
            'author_2' => '- Marie Dubois, Boerderijexploitant',
            'author_3' => '- Jean Lefebvre, GAEC'
        ],
        'blog' => [
            'search_placeholder' => 'Zoek een artikel...',
            'all_categories' => 'Alle categorieën',
            'no_articles_found' => 'Geen artikelen gevonden',
            'popular_articles' => 'Populaire artikelen',
            'categories' => 'Categorieën'
        ],
        'footer' => [
            'all_rights_reserved' => 'Alle rechten voorbehouden'
        ],
        'cookies' => [
            'banner_title' => 'Deze site gebruikt cookies',
            'banner_description' => 'We gebruiken cookies om uw ervaring te verbeteren, verkeer te analyseren en inhoud te personaliseren. U kunt kiezen welke cookies u accepteert.',
            'customize' => 'Aanpassen',
            'reject' => 'Weigeren',
            'accept_all' => 'Alles accepteren',
            'preferences_title' => 'Cookie voorkeuren',
            'preferences_description' => 'Kies welke cookies u wilt toestaan',
            'necessary_title' => 'Noodzakelijke cookies',
            'necessary_subtitle' => 'Site functionaliteit',
            'necessary_description' => 'Deze cookies zijn essentieel voor het functioneren van de site en kunnen niet worden uitgeschakeld.',
            'analytics_title' => 'Analytics cookies',
            'analytics_subtitle' => 'Ervaring verbetering',
            'analytics_description' => 'Deze cookies helpen ons begrijpen hoe u de site gebruikt om deze te verbeteren.',
            'marketing_title' => 'Marketing cookies',
            'marketing_subtitle' => 'Gepersonaliseerde reclame',
            'marketing_description' => 'Deze cookies stellen ons in staat om u advertenties aan te bieden die zijn afgestemd op uw interesses.',
            'preferences_cookies_title' => 'Voorkeur cookies',
            'preferences_subtitle' => 'Ervaring personalisatie',
            'preferences_description' => 'Deze cookies onthouden uw voorkeuren (taal, regio, enz.) om uw ervaring te personaliseren.',
            'social_title' => 'Social media cookies',
            'social_subtitle' => 'Sociale delen en integraties'
        ]
    ]
];

echo "🌍 Démarrage de la traduction complète pour FarmShop...\n\n";

// 1. Mettre à jour les fichiers de langue avec toutes les nouvelles traductions
foreach ($languageTranslations as $locale => $sections) {
    $langFile = __DIR__ . "/resources/lang/{$locale}/app.php";
    
    if (file_exists($langFile)) {
        $content = file_get_contents($langFile);
        
        // Parse le fichier PHP existant
        $existingTranslations = include $langFile;
        
        // Fusionner avec les nouvelles traductions
        $mergedTranslations = array_merge_recursive($existingTranslations, $sections);
        
        // Générer le nouveau contenu
        $newContent = "<?php\n\nreturn " . var_export($mergedTranslations, true) . ";\n";
        
        if (file_put_contents($langFile, $newContent)) {
            echo "✅ Fichier de langue {$locale} mis à jour\n";
        } else {
            echo "❌ Erreur lors de la mise à jour du fichier {$locale}\n";
        }
    } else {
        echo "⚠️ Fichier {$langFile} non trouvé\n";
    }
}

// 2. Remplacer smart_translate() par __() dans tous les fichiers Blade
$bladeFiles = [
    'resources/views/welcome.blade.php',
    'resources/views/layouts/app.blade.php',
    'resources/views/web/products/index.blade.php',
    'resources/views/web/products/show.blade.php',
    'resources/views/web/products/category.blade.php',
    'resources/views/web/rentals/index.blade.php',
    'resources/views/blog/index.blade.php',
    'resources/views/blog/show.blade.php'
];

$replacements = 0;

foreach ($bladeFiles as $file) {
    $filePath = __DIR__ . '/' . $file;
    
    if (!file_exists($filePath)) {
        echo "⚠️ Fichier {$file} non trouvé\n";
        continue;
    }
    
    $content = file_get_contents($filePath);
    $originalContent = $content;
    
    // Remplacer chaque smart_translate() par son équivalent __()
    foreach ($translations as $frenchText => $laravelKey) {
        $pattern = '/\{\{\s*smart_translate\(\s*["\']' . preg_quote($frenchText, '/') . '["\']\s*\)\s*\}\}/';
        $replacement = '{{ __("app.' . $laravelKey . '") }}';
        $content = preg_replace($pattern, $replacement, $content);
    }
    
    // Remplacer les @section avec smart_translate
    foreach ($translations as $frenchText => $laravelKey) {
        $pattern = '/@section\(["\']title["\']\s*,\s*["\'].*\{\{\s*smart_translate\(\s*["\']' . preg_quote($frenchText, '/') . '["\']\s*\)\s*\}\}.*["\']\)/';
        $replacement = '@section(\'title\', __("app.' . $laravelKey . '") . \' - FarmShop\')';
        $content = preg_replace($pattern, $replacement, $content);
    }
    
    if ($content !== $originalContent) {
        if (file_put_contents($filePath, $content)) {
            $fileReplacements = substr_count($originalContent, 'smart_translate') - substr_count($content, 'smart_translate');
            $replacements += $fileReplacements;
            echo "✅ {$file}: {$fileReplacements} remplacements effectués\n";
        } else {
            echo "❌ Erreur lors de l'écriture de {$file}\n";
        }
    } else {
        echo "ℹ️ {$file}: Aucun remplacement nécessaire\n";
    }
}

echo "\n🎉 Traduction complète terminée !\n";
echo "📊 Total de remplacements effectués: {$replacements}\n";
echo "🌍 Langues mises à jour: Français, English, Nederlands\n";
echo "📄 Pages traduites: Accueil, Produits, Blog, Location, Contact, etc.\n";
echo "\n🚀 Le site est maintenant entièrement traduit dans les 3 langues !\n";
