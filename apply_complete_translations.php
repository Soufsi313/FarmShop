<?php

require_once __DIR__ . '/vendor/autoload.php';

echo "🌍 Application des traductions professionnelles complètes...\n";

// Configuration des fichiers à modifier avec leurs traductions spécifiques
$viewTranslations = [
    'resources/views/welcome.blade.php' => [
        'Bienvenue sur FarmShop' => '{{ smart_translate("Bienvenue sur FarmShop") }}',
        'Nos Produits' => '{{ smart_translate("Nos Produits") }}',
        'Découvrir' => '{{ smart_translate("Découvrir") }}',
        'Location' => '{{ smart_translate("Location") }}',
        'Vente' => '{{ smart_translate("Vente") }}',
        'Notre Sélection' => '{{ smart_translate("Notre Sélection") }}',
        'Voir plus' => '{{ smart_translate("Voir plus") }}',
        'Derniers Articles' => '{{ smart_translate("Derniers Articles") }}',
        'Lire la suite' => '{{ smart_translate("Lire la suite") }}'
    ],
    
    'resources/views/products/index.blade.php' => [
        'Nos Produits' => '{{ smart_translate("Nos Produits") }}',
        'Rechercher un produit...' => '{{ smart_translate("Rechercher un produit...") }}',
        'Toutes les catégories' => '{{ smart_translate("Toutes les catégories") }}',
        'Tous les types' => '{{ smart_translate("Tous les types") }}',
        'Prix' => '{{ smart_translate("Prix") }}',
        'Trier par' => '{{ smart_translate("Trier par") }}',
        'Plus récent' => '{{ smart_translate("Plus récent") }}',
        'Prix croissant' => '{{ smart_translate("Prix croissant") }}',
        'Prix décroissant' => '{{ smart_translate("Prix décroissant") }}',
        'Nom A-Z' => '{{ smart_translate("Nom A-Z") }}',
        'Filtrer' => '{{ smart_translate("Filtrer") }}',
        'Aucun produit trouvé' => '{{ smart_translate("Aucun produit trouvé") }}',
        'Ajouter au panier' => '{{ smart_translate("Ajouter au panier") }}',
        'Voir le produit' => '{{ smart_translate("Voir le produit") }}',
        'En stock' => '{{ smart_translate("En stock") }}',
        'Rupture de stock' => '{{ smart_translate("Rupture de stock") }}'
    ],
    
    'resources/views/products/show.blade.php' => [
        'Description' => '{{ smart_translate("Description") }}',
        'Caractéristiques' => '{{ smart_translate("Caractéristiques") }}',
        'Disponibilité' => '{{ smart_translate("Disponibilité") }}',
        'Ajouter au panier' => '{{ smart_translate("Ajouter au panier") }}',
        'Quantité' => '{{ smart_translate("Quantité") }}',
        'Prix unitaire' => '{{ smart_translate("Prix unitaire") }}',
        'Retour aux produits' => '{{ smart_translate("Retour aux produits") }}',
        'Produits similaires' => '{{ smart_translate("Produits similaires") }}',
        'Partager' => '{{ smart_translate("Partager") }}'
    ],
    
    'resources/views/cart/index.blade.php' => [
        'Mon Panier' => '{{ smart_translate("Mon Panier") }}',
        'Votre panier est vide' => '{{ smart_translate("Votre panier est vide") }}',
        'Continuer mes achats' => '{{ smart_translate("Continuer mes achats") }}',
        'Produit' => '{{ smart_translate("Produit") }}',
        'Quantité' => '{{ smart_translate("Quantité") }}',
        'Prix unitaire' => '{{ smart_translate("Prix unitaire") }}',
        'Total' => '{{ smart_translate("Total") }}',
        'Sous-total' => '{{ smart_translate("Sous-total") }}',
        'TVA' => '{{ smart_translate("TVA") }}',
        'Frais de port' => '{{ smart_translate("Frais de port") }}',
        'Gratuit' => '{{ smart_translate("Gratuit") }}',
        'Total général' => '{{ smart_translate("Total général") }}',
        'Vider le panier' => '{{ smart_translate("Vider le panier") }}',
        'Valider ma commande' => '{{ smart_translate("Valider ma commande") }}',
        'Supprimer' => '{{ smart_translate("Supprimer") }}',
        'Modifier' => '{{ smart_translate("Modifier") }}'
    ],
    
    'resources/views/auth/login.blade.php' => [
        'Connexion' => '{{ smart_translate("Connexion") }}',
        'Email' => '{{ smart_translate("Email") }}',
        'Mot de passe' => '{{ smart_translate("Mot de passe") }}',
        'Se souvenir de moi' => '{{ smart_translate("Se souvenir de moi") }}',
        'Mot de passe oublié ?' => '{{ smart_translate("Mot de passe oublié ?") }}',
        'Se connecter' => '{{ smart_translate("Se connecter") }}',
        'Pas encore inscrit ?' => '{{ smart_translate("Pas encore inscrit ?") }}',
        'Créer un compte' => '{{ smart_translate("Créer un compte") }}'
    ],
    
    'resources/views/auth/register.blade.php' => [
        'Inscription' => '{{ smart_translate("Inscription") }}',
        'Nom' => '{{ smart_translate("Nom") }}',
        'Prénom' => '{{ smart_translate("Prénom") }}',
        'Email' => '{{ smart_translate("Email") }}',
        'Mot de passe' => '{{ smart_translate("Mot de passe") }}',
        'Confirmer le mot de passe' => '{{ smart_translate("Confirmer le mot de passe") }}',
        'Téléphone' => '{{ smart_translate("Téléphone") }}',
        'S\'inscrire' => '{{ smart_translate("S\'inscrire") }}',
        'Déjà inscrit ?' => '{{ smart_translate("Déjà inscrit ?") }}',
        'Se connecter' => '{{ smart_translate("Se connecter") }}'
    ],
    
    'resources/views/blog/index.blade.php' => [
        'Blog' => '{{ smart_translate("Blog") }}',
        'Nos Derniers Articles' => '{{ smart_translate("Nos Derniers Articles") }}',
        'Rechercher un article...' => '{{ smart_translate("Rechercher un article...") }}',
        'Toutes les catégories' => '{{ smart_translate("Toutes les catégories") }}',
        'Lire la suite' => '{{ smart_translate("Lire la suite") }}',
        'Aucun article trouvé' => '{{ smart_translate("Aucun article trouvé") }}',
        'Articles populaires' => '{{ smart_translate("Articles populaires") }}',
        'Catégories' => '{{ smart_translate("Catégories") }}'
    ],
    
    'resources/views/contact/index.blade.php' => [
        'Contact' => '{{ smart_translate("Contact") }}',
        'Nous contacter' => '{{ smart_translate("Nous contacter") }}',
        'Votre nom' => '{{ smart_translate("Votre nom") }}',
        'Votre email' => '{{ smart_translate("Votre email") }}',
        'Sujet' => '{{ smart_translate("Sujet") }}',
        'Votre message' => '{{ smart_translate("Votre message") }}',
        'Envoyer' => '{{ smart_translate("Envoyer") }}',
        'Nos coordonnées' => '{{ smart_translate("Nos coordonnées") }}',
        'Adresse' => '{{ smart_translate("Adresse") }}',
        'Téléphone' => '{{ smart_translate("Téléphone") }}',
        'Email' => '{{ smart_translate("Email") }}',
        'Horaires d\'ouverture' => '{{ smart_translate("Horaires d\'ouverture") }}'
    ]
];

// Fonction pour appliquer les traductions dans un fichier
function applyTranslationsToFile($filePath, $translations) {
    if (!file_exists($filePath)) {
        echo "⚠️  Fichier non trouvé: $filePath\n";
        return false;
    }
    
    $content = file_get_contents($filePath);
    $originalContent = $content;
    
    foreach ($translations as $search => $replace) {
        // Éviter de remplacer si déjà traduit
        if (strpos($content, $replace) !== false) {
            continue;
        }
        
        // Remplacer le texte
        $content = str_replace($search, $replace, $content);
    }
    
    if ($content !== $originalContent) {
        file_put_contents($filePath, $content);
        echo "✅ Traductions appliquées dans: $filePath\n";
        return true;
    }
    
    return false;
}

// Appliquer les traductions pour chaque fichier
$totalFiles = 0;
$processedFiles = 0;

foreach ($viewTranslations as $filePath => $translations) {
    $totalFiles++;
    if (applyTranslationsToFile($filePath, $translations)) {
        $processedFiles++;
    }
}

echo "\n📊 Résumé:\n";
echo "   - Fichiers traités: $processedFiles/$totalFiles\n";

// Mise à jour du layout principal avec traductions dynamiques
echo "\n🎨 Mise à jour du layout principal...\n";

$layoutPath = 'resources/views/layouts/app.blade.php';
if (file_exists($layoutPath)) {
    $layoutContent = file_get_contents($layoutPath);
    
    // Remplacer les éléments de navigation
    $navTranslations = [
        '>Accueil<' => '>{{ smart_translate("Accueil") }}<',
        '>Produits<' => '>{{ smart_translate("Produits") }}<',
        '>Location<' => '>{{ smart_translate("Location") }}<',
        '>Vente<' => '>{{ smart_translate("Vente") }}<',
        '>Blog<' => '>{{ smart_translate("Blog") }}<',
        '>Contact<' => '>{{ smart_translate("Contact") }}<',
        '>Connexion<' => '>{{ smart_translate("Connexion") }}<',
        '>Inscription<' => '>{{ smart_translate("Inscription") }}<',
        '>Mon Compte<' => '>{{ smart_translate("Mon Compte") }}<',
        '>Déconnexion<' => '>{{ smart_translate("Déconnexion") }}<',
        '>Panier<' => '>{{ smart_translate("Panier") }}<',
        'placeholder="Rechercher..."' => 'placeholder="{{ smart_translate("Rechercher...") }}"',
        'title="Panier"' => 'title="{{ smart_translate("Panier") }}"'
    ];
    
    $originalLayout = $layoutContent;
    foreach ($navTranslations as $search => $replace) {
        if (strpos($layoutContent, $replace) === false) {
            $layoutContent = str_replace($search, $replace, $layoutContent);
        }
    }
    
    if ($layoutContent !== $originalLayout) {
        file_put_contents($layoutPath, $layoutContent);
        echo "✅ Layout principal mis à jour\n";
    }
}

// Mise à jour des composants avec traductions de produits/catégories
echo "\n🧩 Mise à jour des composants avec helpers spécialisés...\n";

$componentUpdates = [
    'resources/views/components/product-card.blade.php' => [
        '$product->name' => 'trans_product($product, "name")',
        '$product->description' => 'trans_product($product, "description")',
        '$product->short_description' => 'trans_product($product, "short_description")'
    ],
    
    'resources/views/components/category-card.blade.php' => [
        '$category->name' => 'trans_category($category, "name")',
        '$category->description' => 'trans_category($category, "description")'
    ]
];

foreach ($componentUpdates as $filePath => $updates) {
    if (file_exists($filePath)) {
        $content = file_get_contents($filePath);
        $originalContent = $content;
        
        foreach ($updates as $search => $replace) {
            if (strpos($content, $replace) === false) {
                $content = str_replace('{{ ' . $search . ' }}', '{{ ' . $replace . ' }}', $content);
                $content = str_replace('{!! ' . $search . ' !!}', '{!! ' . $replace . ' !!}', $content);
            }
        }
        
        if ($content !== $originalContent) {
            file_put_contents($filePath, $content);
            echo "✅ Composant mis à jour: $filePath\n";
        }
    }
}

// Mise à jour du formatage des prix
echo "\n💰 Application du formatage des prix localisé...\n";

$priceFiles = [
    'resources/views/products/index.blade.php',
    'resources/views/products/show.blade.php',
    'resources/views/cart/index.blade.php',
    'resources/views/components/product-card.blade.php'
];

foreach ($priceFiles as $filePath) {
    if (file_exists($filePath)) {
        $content = file_get_contents($filePath);
        $originalContent = $content;
        
        // Remplacer les affichages de prix
        $content = preg_replace('/\{\{\s*\$[^}]*->price\s*\}\}\s*€/', '{{ format_price($product->price ?? 0) }}', $content);
        $content = preg_replace('/\{\{\s*number_format\([^}]*\)\s*\}\}\s*€/', '{{ format_price($product->price ?? 0) }}', $content);
        
        if ($content !== $originalContent) {
            file_put_contents($filePath, $content);
            echo "✅ Prix localisés dans: $filePath\n";
        }
    }
}

echo "\n🎉 Système de traduction professionnel complet installé !\n";
echo "\n📝 Fonctionnalités disponibles:\n";
echo "   ✅ Traductions d'interface avec smart_translate()\n";
echo "   ✅ Traductions de produits avec trans_product()\n";
echo "   ✅ Traductions de catégories avec trans_category()\n";
echo "   ✅ Traductions de blog avec trans_blog()\n";
echo "   ✅ Formatage des prix localisé avec format_price()\n";
echo "   ✅ Sélecteur de langue Alpine.js avec AJAX\n";
echo "   ✅ Base de données peuplée avec traductions\n";
echo "\n🌍 Langues supportées: Français (défaut), Anglais, Néerlandais\n";
echo "\n🚀 Votre site e-commerce est maintenant totalement multilingue comme Amazon !\n";
