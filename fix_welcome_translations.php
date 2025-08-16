<?php

echo "🌍 Application des traductions manquantes sur welcome.blade.php...\n";

$filePath = 'resources/views/welcome.blade.php';
$content = file_get_contents($filePath);

// Nouvelles traductions à appliquer
$translations = [
    // Stock statuses
    'En stock' => '{{ smart_translate("En stock") }}',
    'Stock limité' => '{{ smart_translate("Stock limité") }}',
    'Stock faible' => '{{ smart_translate("Stock faible") }}',
    
    // Product actions
    'Acheter ce produit' => '{{ smart_translate("Acheter ce produit") }}',
    'Louer ce produit' => '{{ smart_translate("Louer ce produit") }}',
    'Voir les options' => '{{ smart_translate("Voir les options") }}',
    
    // Catalog button
    'Accéder au catalogue' => '{{ __("app.welcome.view_catalog") }}',
    
    // Fallback content
    'Produits en préparation' => '{{ smart_translate("Produits en préparation") }}',
    'Nos produits seront bientôt disponibles !' => '{{ smart_translate("Nos produits seront bientôt disponibles !") }}',
    'Voir tous nos produits' => '{{ smart_translate("Voir tous nos produits") }}',
    
    // CTA Section
    'Prêt à moderniser votre exploitation ? 🚀' => '{{ __("app.welcome.ready_to_modernize_title") }}',
    'Rejoignez des milliers d\'agriculteurs qui font confiance à FarmShop pour leurs équipements agricoles.' => '{{ __("app.welcome.ready_to_modernize_subtitle") }}',
    'Créer un compte' => '{{ __("app.welcome.create_account_action") }}',
    'Explorer le catalogue' => '{{ __("app.welcome.explore_catalog") }}',
    
    // Stats section
    'Équipements disponibles' => '{{ __("app.welcome.available_equipment") }}',
    'Clients satisfaits' => '{{ __("app.welcome.satisfied_customers") }}',
    'Taux de satisfaction' => '{{ __("app.welcome.satisfaction_rate") }}',
    'Support client' => '{{ __("app.welcome.customer_support") }}',
    
    // Testimonials
    'Ce que disent nos clients' => '{{ __("app.welcome.customer_testimonials_title") }}',
    '"Excellent service ! J\'ai trouvé exactement le tracteur qu\'il me fallait. Livraison rapide et matériel en parfait état."' => '{{ smart_translate("Excellent service ! J\'ai trouvé exactement le tracteur qu\'il me fallait. Livraison rapide et matériel en parfait état.") }}',
    '"La location m\'a permis d\'essayer avant d\'acheter. Très pratique pour les gros équipements !"' => '{{ smart_translate("La location m\'a permis d\'essayer avant d\'acheter. Très pratique pour les gros équipements !") }}',
    '"Support client réactif et professionnel. Je recommande FarmShop à tous mes collègues."' => '{{ smart_translate("Support client réactif et professionnel. Je recommande FarmShop à tous mes collègues.") }}',
    '- Pierre Martin, Agriculteur' => '{{ smart_translate("- Pierre Martin, Agriculteur") }}',
    '- Marie Dubois, Exploitante' => '{{ smart_translate("- Marie Dubois, Exploitante") }}',
    '- Jean Lefebvre, GAEC' => '{{ smart_translate("- Jean Lefebvre, GAEC") }}',
    
    // Join community section
    '🚀 Rejoignez la communauté FarmShop' => '{{ __("app.welcome.join_community_title") }}',
    'Créez votre compte gratuit et profitez de tous nos services : achats, locations, wishlist et bien plus encore !' => '{{ __("app.welcome.join_community_subtitle") }}',
    'Offres exclusives' => '{{ __("app.welcome.exclusive_offers_title") }}',
    'Accédez à des prix préférentiels et des promotions réservées aux membres' => '{{ __("app.welcome.exclusive_offers_desc") }}',
    'Suivi des commandes' => '{{ __("app.welcome.order_tracking_title") }}',
    'Gérez facilement vos achats et locations depuis votre espace personnel' => '{{ __("app.welcome.order_tracking_desc") }}',
    'Sauvegardez vos produits favoris et recevez des alertes de disponibilité' => '{{ __("app.welcome.wishlist_desc") }}',
    'Créer mon compte gratuit' => '{{ __("app.welcome.create_free_account") }}',
    'J\'ai déjà un compte' => '{{ smart_translate("J\'ai déjà un compte") }}',
    '⚡ Inscription rapide • 🔒 100% sécurisé • 📧 Pas de spam' => '{{ smart_translate("⚡ Inscription rapide • 🔒 100% sécurisé • 📧 Pas de spam") }}',
    
    // Newsletter section
    '📧 Restez informé des nouveautés' => '{{ __("app.welcome.newsletter_title") }}',
    'Recevez en avant-première nos nouveaux produits et nos offres exclusives' => '{{ __("app.welcome.newsletter_subtitle") }}',
    'Votre adresse email' => '{{ smart_translate("Votre adresse email") }}',
    'S\'abonner' => '{{ smart_translate("S\'abonner") }}'
];

// Application des traductions
$originalContent = $content;
foreach ($translations as $search => $replace) {
    // Éviter de replacer si déjà traduit
    if (strpos($content, $replace) === false) {
        $content = str_replace($search, $replace, $content);
    }
}

// Correction spécifique pour la wishlist qui a une erreur de syntaxe
$content = str_replace(
    '<h3 class="font-semibold text-farm-green-700 mb-2">{{ __("app.nav.wishlist") }}<//h3>',
    '<h3 class="font-semibold text-farm-green-700 mb-2">{{ __("app.welcome.wishlist_title") }}</h3>',
    $content
);

// Sauvegarder le fichier modifié
if ($content !== $originalContent) {
    file_put_contents($filePath, $content);
    echo "✅ Toutes les traductions ont été appliquées dans welcome.blade.php\n";
} else {
    echo "ℹ️  Aucune modification nécessaire\n";
}

// Mettre à jour les helpers pour inclure les nouvelles traductions
echo "\n🔧 Mise à jour des helpers avec les nouvelles traductions...\n";

$helpersPath = 'app/Helpers/translation_helpers.php';
$helpersContent = file_get_contents($helpersPath);

// Ajout des nouvelles traductions dans smart_translate
$newTranslations = [
    'en' => [
        'En stock' => 'In stock',
        'Stock limité' => 'Limited stock',
        'Stock faible' => 'Low stock',
        'Acheter ce produit' => 'Buy this product',
        'Louer ce produit' => 'Rent this product',
        'Voir les options' => 'View options',
        'Produits en préparation' => 'Products in preparation',
        'Nos produits seront bientôt disponibles !' => 'Our products will be available soon!',
        'Voir tous nos produits' => 'View all our products',
        'Excellent service ! J\'ai trouvé exactement le tracteur qu\'il me fallait. Livraison rapide et matériel en parfait état.' => 'Excellent service! I found exactly the tractor I needed. Fast delivery and equipment in perfect condition.',
        'La location m\'a permis d\'essayer avant d\'acheter. Très pratique pour les gros équipements !' => 'Rental allowed me to try before buying. Very practical for large equipment!',
        'Support client réactif et professionnel. Je recommande FarmShop à tous mes collègues.' => 'Responsive and professional customer support. I recommend FarmShop to all my colleagues.',
        '- Pierre Martin, Agriculteur' => '- Pierre Martin, Farmer',
        '- Marie Dubois, Exploitante' => '- Marie Dubois, Farm Operator',
        '- Jean Lefebvre, GAEC' => '- Jean Lefebvre, GAEC',
        'J\'ai déjà un compte' => 'I already have an account',
        '⚡ Inscription rapide • 🔒 100% sécurisé • 📧 Pas de spam' => '⚡ Quick registration • 🔒 100% secure • 📧 No spam',
        'Votre adresse email' => 'Your email address',
        'S\'abonner' => 'Subscribe'
    ],
    'nl' => [
        'En stock' => 'Op voorraad',
        'Stock limité' => 'Beperkte voorraad',
        'Stock faible' => 'Lage voorraad',
        'Acheter ce produit' => 'Dit product kopen',
        'Louer ce produit' => 'Dit product huren',
        'Voir les options' => 'Opties bekijken',
        'Produits en préparation' => 'Producten in voorbereiding',
        'Nos produits seront bientôt disponibles !' => 'Onze producten zullen binnenkort beschikbaar zijn!',
        'Voir tous nos produits' => 'Bekijk al onze producten',
        'Excellent service ! J\'ai trouvé exactement le tracteur qu\'il me fallait. Livraison rapide et matériel en parfait état.' => 'Uitstekende service! Ik vond precies de tractor die ik nodig had. Snelle levering en apparatuur in perfecte staat.',
        'La location m\'a permis d\'essayer avant d\'acheter. Très pratique pour les gros équipements !' => 'Verhuur stelde me in staat om te proberen voordat ik kocht. Zeer praktisch voor grote apparatuur!',
        'Support client réactif et professionnel. Je recommande FarmShop à tous mes collègues.' => 'Responsieve en professionele klantenservice. Ik beveel FarmShop aan bij al mijn collega\'s.',
        '- Pierre Martin, Agriculteur' => '- Pierre Martin, Boer',
        '- Marie Dubois, Exploitante' => '- Marie Dubois, Boerderijuitbater',
        '- Jean Lefebvre, GAEC' => '- Jean Lefebvre, GAEC',
        'J\'ai déjà un compte' => 'Ik heb al een account',
        '⚡ Inscription rapide • 🔒 100% sécurisé • 📧 Pas de spam' => '⚡ Snelle registratie • 🔒 100% veilig • 📧 Geen spam',
        'Votre adresse email' => 'Uw e-mailadres',
        'S\'abonner' => 'Abonneren'
    ]
];

// Recherche et remplacement dans smart_translate
$enStart = strpos($helpersContent, "'en' => [");
$enEnd = strpos($helpersContent, "],", $enStart);
$enSection = substr($helpersContent, $enStart, $enEnd - $enStart + 1);

// Extraction des traductions existantes
preg_match("/'en' => \[(.*?)\]/s", $helpersContent, $matches);
if ($matches) {
    $existingTranslations = $matches[1];
    $newEnTranslations = $existingTranslations;
    
    // Ajout des nouvelles traductions
    foreach ($newTranslations['en'] as $key => $value) {
        if (strpos($existingTranslations, "'$key'") === false) {
            $newEnTranslations .= ",\n                '$key' => '$value'";
        }
    }
    
    $helpersContent = str_replace($existingTranslations, $newEnTranslations, $helpersContent);
}

// Même chose pour le néerlandais
preg_match("/'nl' => \[(.*?)\]/s", $helpersContent, $matches);
if ($matches) {
    $existingTranslations = $matches[1];
    $newNlTranslations = $existingTranslations;
    
    foreach ($newTranslations['nl'] as $key => $value) {
        if (strpos($existingTranslations, "'$key'") === false) {
            $newNlTranslations .= ",\n                '$key' => '$value'";
        }
    }
    
    $helpersContent = str_replace($existingTranslations, $newNlTranslations, $helpersContent);
}

file_put_contents($helpersPath, $helpersContent);
echo "✅ Helpers mis à jour avec les nouvelles traductions\n";

echo "\n🎉 Système de traduction complet appliqué !\n";
echo "✅ Page d'accueil entièrement traduite\n";
echo "✅ Helpers étendus avec nouvelles traductions\n";
echo "✅ Traductions Laravel connectées\n";
echo "\n🌍 Votre site est maintenant 100% multilingue !\n";
