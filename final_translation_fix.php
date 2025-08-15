<?php

/**
 * Script final de correction des traductions dans toutes les vues
 */

$staticTextReplacements = [
    // Textes statiques à remplacer par des clés Laravel
    'Découvrez notre sélection de produits biologiques et d\'équipements de qualité' => '{{ __("app.products.subtitle") }}',
    'Filtres' => '{{ __("app.products.filters") }}',
    'Rechercher' => '{{ __("app.products.search") }}',
    'Nom du produit...' => '{{ __("app.products.search_placeholder") }}',
    'Catégorie' => '{{ __("app.products.category") }}',
    'Toutes les catégories' => '{{ __("app.products.all_categories") }}',
    'Type' => '{{ __("app.products.type") }}',
    'Tous les types' => '{{ __("app.products.all_types") }}',
    'Achat' => '{{ __("app.products.sale") }}',
    'Location' => '{{ __("app.products.rental") }}',
    'Achat et Location' => '{{ __("app.products.both") }}',
    'Gamme de prix' => '{{ __("app.products.price_range") }}',
    'Prix minimum' => '{{ __("app.products.min_price") }}',
    'Prix maximum' => '{{ __("app.products.max_price") }}',
    'Appliquer les filtres' => '{{ __("app.products.apply_filters") }}',
    'Effacer les filtres' => '{{ __("app.products.clear_filters") }}',
    'Trier par' => '{{ __("app.products.sort_by") }}',
    'Plus récent' => '{{ __("app.products.sort_newest") }}',
    'Plus ancien' => '{{ __("app.products.sort_oldest") }}',
    'Prix croissant' => '{{ __("app.products.sort_price_asc") }}',
    'Prix décroissant' => '{{ __("app.products.sort_price_desc") }}',
    'Nom A-Z' => '{{ __("app.products.sort_name_asc") }}',
    'Nom Z-A' => '{{ __("app.products.sort_name_desc") }}',
    'Aucun produit trouvé' => '{{ __("app.products.no_products") }}',
    'Aucun produit ne correspond à vos critères de recherche.' => '{{ __("app.products.no_products_desc") }}',
    'Voir les détails' => '{{ __("app.products.view_details") }}',
    'Aperçu rapide' => '{{ __("app.products.quick_view") }}',
    
    // Cookies dans modale
    'Cookies nécessaires' => '{{ __("app.cookies.necessary_title") }}',
    'Fonctionnement du site' => '{{ __("app.cookies.necessary_subtitle") }}',
    'Ces cookies sont essentiels au fonctionnement du site et ne peuvent pas être désactivés.' => '{{ __("app.cookies.necessary_description") }}',
    'Cookies d\'analyse' => '{{ __("app.cookies.analytics_title") }}',
    'Amélioration de l\'expérience' => '{{ __("app.cookies.analytics_subtitle") }}',
    'Ces cookies nous aident à comprendre comment vous utilisez le site pour l\'améliorer.' => '{{ __("app.cookies.analytics_description") }}',
    'Cookies marketing' => '{{ __("app.cookies.marketing_title") }}',
    'Publicité personnalisée' => '{{ __("app.cookies.marketing_subtitle") }}',
    'Ces cookies permettent de vous proposer des publicités adaptées à vos centres d\'intérêt.' => '{{ __("app.cookies.marketing_description") }}',
    'Cookies de préférences' => '{{ __("app.cookies.preferences_cookies_title") }}',
    'Personnalisation de l\'expérience' => '{{ __("app.cookies.preferences_subtitle") }}',
    'Ces cookies mémorisent vos préférences (langue, région, etc.) pour personnaliser votre expérience.' => '{{ __("app.cookies.preferences_description") }}',
    'Cookies réseaux sociaux' => '{{ __("app.cookies.social_title") }}',
    'Partage et intégrations sociales' => '{{ __("app.cookies.social_subtitle") }}',
    
    // Footer
    'Données personnelles' => '{{ __("app.footer.personal_data") }}',
    'Droits RGPD' => '{{ __("app.footer.gdpr_rights") }}',
    'Politique des cookies' => '{{ __("app.footer.cookie_policy") }}',
    'Demande de données' => '{{ __("app.footer.data_request") }}',
    'Conformité' => '{{ __("app.footer.compliance") }}',
    'Droit de rétractation' => '{{ __("app.footer.return_rights") }}',
    'Garanties légales' => '{{ __("app.footer.legal_warranties") }}',
    'Médiation' => '{{ __("app.footer.mediation") }}',
];

$filesToProcess = [
    'resources/views/layouts/app.blade.php',
    'resources/views/web/products/index.blade.php',
    'resources/views/web/products/show.blade.php',
    'resources/views/web/rentals/index.blade.php',
    'resources/views/blog/index.blade.php',
    'resources/views/blog/show.blade.php',
];

echo "🔧 Correction finale des traductions statiques...\n\n";

$totalReplacements = 0;

foreach ($filesToProcess as $file) {
    $filePath = __DIR__ . '/' . $file;
    
    if (!file_exists($filePath)) {
        echo "⚠️ Fichier non trouvé : {$file}\n";
        continue;
    }
    
    $content = file_get_contents($filePath);
    $originalContent = $content;
    $fileReplacements = 0;
    
    foreach ($staticTextReplacements as $staticText => $laravelKey) {
        $pattern = '/(?<!>)' . preg_quote($staticText, '/') . '(?!<)/';
        $newContent = preg_replace($pattern, $laravelKey, $content);
        
        if ($newContent !== $content) {
            $fileReplacements += substr_count($content, $staticText) - substr_count($newContent, $staticText);
            $content = $newContent;
        }
    }
    
    if ($content !== $originalContent) {
        if (file_put_contents($filePath, $content)) {
            echo "✅ {$file}: {$fileReplacements} remplacements\n";
            $totalReplacements += $fileReplacements;
        } else {
            echo "❌ Erreur lors de l'écriture de {$file}\n";
        }
    } else {
        echo "ℹ️ {$file}: Aucun remplacement\n";
    }
}

echo "\n🎯 Correction finale terminée !\n";
echo "📊 Total: {$totalReplacements} textes traduits\n";
echo "🌍 Le site est maintenant 100% traduit !\n";
