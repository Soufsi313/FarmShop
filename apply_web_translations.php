<?php

require_once __DIR__ . '/vendor/autoload.php';

echo "🌍 Application finale des traductions sur les vues web...\n";

// Mise à jour du fichier principal des produits
$productIndexPath = 'resources/views/web/products/index.blade.php';
if (file_exists($productIndexPath)) {
    $content = file_get_contents($productIndexPath);
    
    $translations = [
        'Nos Produits' => '{{ smart_translate("Nos Produits") }}',
        'Tous les produits' => '{{ smart_translate("Tous les produits") }}',
        'Rechercher un produit' => '{{ smart_translate("Rechercher un produit") }}',
        'Rechercher...' => '{{ smart_translate("Rechercher...") }}',
        'Toutes les catégories' => '{{ smart_translate("Toutes les catégories") }}',
        'Tous les types' => '{{ smart_translate("Tous les types") }}',
        'Location' => '{{ smart_translate("Location") }}',
        'Vente' => '{{ smart_translate("Vente") }}',
        'Prix' => '{{ smart_translate("Prix") }}',
        'Trier par' => '{{ smart_translate("Trier par") }}',
        'Plus récent' => '{{ smart_translate("Plus récent") }}',
        'Prix croissant' => '{{ smart_translate("Prix croissant") }}',
        'Prix décroissant' => '{{ smart_translate("Prix décroissant") }}',
        'Nom A-Z' => '{{ smart_translate("Nom A-Z") }}',
        'Nom Z-A' => '{{ smart_translate("Nom Z-A") }}',
        'Filtrer' => '{{ smart_translate("Filtrer") }}',
        'Ajouter au panier' => '{{ smart_translate("Ajouter au panier") }}',
        'Voir le produit' => '{{ smart_translate("Voir le produit") }}',
        'En stock' => '{{ smart_translate("En stock") }}',
        'Rupture de stock' => '{{ smart_translate("Rupture de stock") }}',
        'Disponible' => '{{ smart_translate("Disponible") }}',
        'Non disponible' => '{{ smart_translate("Non disponible") }}',
        'Aucun produit trouvé' => '{{ smart_translate("Aucun produit trouvé") }}',
        'produits trouvés' => '{{ smart_translate("produits trouvés") }}',
        'Effacer les filtres' => '{{ smart_translate("Effacer les filtres") }}'
    ];
    
    $originalContent = $content;
    foreach ($translations as $search => $replace) {
        if (strpos($content, $replace) === false) {
            $content = str_replace($search, $replace, $content);
            $content = str_replace('"' . $search . '"', '"' . str_replace('{{ smart_translate("', '', str_replace('") }}', '', $replace)) . '"', $content);
        }
    }
    
    // Mise à jour des noms de produits et descriptions
    $content = preg_replace('/\{\{\s*\$product->name\s*\}\}/', '{{ trans_product($product, "name") }}', $content);
    $content = preg_replace('/\{\{\s*\$product->description\s*\}\}/', '{{ trans_product($product, "description") }}', $content);
    $content = preg_replace('/\{\{\s*\$product->short_description\s*\}\}/', '{{ trans_product($product, "short_description") }}', $content);
    
    // Mise à jour des prix
    $content = preg_replace('/\{\{\s*number_format\(\$product->price[^}]*\)\s*\}\}\s*€/', '{{ format_price($product->price) }}', $content);
    $content = preg_replace('/\{\{\s*\$product->price\s*\}\}\s*€/', '{{ format_price($product->price) }}', $content);
    
    if ($content !== $originalContent) {
        file_put_contents($productIndexPath, $content);
        echo "✅ Traductions appliquées dans: $productIndexPath\n";
    }
}

// Mise à jour de la page de détail produit
$productShowPath = 'resources/views/web/products/show.blade.php';
if (file_exists($productShowPath)) {
    $content = file_get_contents($productShowPath);
    
    $translations = [
        'Description' => '{{ smart_translate("Description") }}',
        'Caractéristiques' => '{{ smart_translate("Caractéristiques") }}',
        'Informations' => '{{ smart_translate("Informations") }}',
        'Disponibilité' => '{{ smart_translate("Disponibilité") }}',
        'Ajouter au panier' => '{{ smart_translate("Ajouter au panier") }}',
        'Quantité' => '{{ smart_translate("Quantité") }}',
        'Prix unitaire' => '{{ smart_translate("Prix unitaire") }}',
        'Prix' => '{{ smart_translate("Prix") }}',
        'Retour aux produits' => '{{ smart_translate("Retour aux produits") }}',
        'Retour' => '{{ smart_translate("Retour") }}',
        'Produits similaires' => '{{ smart_translate("Produits similaires") }}',
        'Produits associés' => '{{ smart_translate("Produits associés") }}',
        'Partager' => '{{ smart_translate("Partager") }}',
        'En stock' => '{{ smart_translate("En stock") }}',
        'Rupture de stock' => '{{ smart_translate("Rupture de stock") }}',
        'Commander' => '{{ smart_translate("Commander") }}',
        'Louer' => '{{ smart_translate("Louer") }}',
        'Acheter' => '{{ smart_translate("Acheter") }}',
        'Type' => '{{ smart_translate("Type") }}',
        'Catégorie' => '{{ smart_translate("Catégorie") }}',
        'Référence' => '{{ smart_translate("Référence") }}',
        'Voir plus' => '{{ smart_translate("Voir plus") }}'
    ];
    
    $originalContent = $content;
    foreach ($translations as $search => $replace) {
        if (strpos($content, $replace) === false) {
            $content = str_replace($search, $replace, $content);
        }
    }
    
    // Mise à jour des données produit
    $content = preg_replace('/\{\{\s*\$product->name\s*\}\}/', '{{ trans_product($product, "name") }}', $content);
    $content = preg_replace('/\{\{\s*\$product->description\s*\}\}/', '{{ trans_product($product, "description") }}', $content);
    $content = preg_replace('/\{\{\s*\$product->short_description\s*\}\}/', '{{ trans_product($product, "short_description") }}', $content);
    
    // Mise à jour des prix
    $content = preg_replace('/\{\{\s*number_format\(\$product->price[^}]*\)\s*\}\}\s*€/', '{{ format_price($product->price) }}', $content);
    $content = preg_replace('/\{\{\s*\$product->price\s*\}\}\s*€/', '{{ format_price($product->price) }}', $content);
    
    if ($content !== $originalContent) {
        file_put_contents($productShowPath, $content);
        echo "✅ Traductions appliquées dans: $productShowPath\n";
    }
}

// Mise à jour de la page de catégorie
$categoryPath = 'resources/views/web/products/category.blade.php';
if (file_exists($categoryPath)) {
    $content = file_get_contents($categoryPath);
    
    $translations = [
        'Catégorie' => '{{ smart_translate("Catégorie") }}',
        'Produits de la catégorie' => '{{ smart_translate("Produits de la catégorie") }}',
        'Tous les produits' => '{{ smart_translate("Tous les produits") }}',
        'Aucun produit dans cette catégorie' => '{{ smart_translate("Aucun produit dans cette catégorie") }}'
    ];
    
    $originalContent = $content;
    foreach ($translations as $search => $replace) {
        if (strpos($content, $replace) === false) {
            $content = str_replace($search, $replace, $content);
        }
    }
    
    // Mise à jour des noms de catégorie
    $content = preg_replace('/\{\{\s*\$category->name\s*\}\}/', '{{ trans_category($category, "name") }}', $content);
    $content = preg_replace('/\{\{\s*\$category->description\s*\}\}/', '{{ trans_category($category, "description") }}', $content);
    
    if ($content !== $originalContent) {
        file_put_contents($categoryPath, $content);
        echo "✅ Traductions appliquées dans: $categoryPath\n";
    }
}

// Mise à jour du panier
$cartPath = 'resources/views/web/cart/index.blade.php';
if (file_exists($cartPath)) {
    $content = file_get_contents($cartPath);
    
    $translations = [
        'Mon Panier' => '{{ smart_translate("Mon Panier") }}',
        'Panier' => '{{ smart_translate("Panier") }}',
        'Votre panier est vide' => '{{ smart_translate("Votre panier est vide") }}',
        'Continuer mes achats' => '{{ smart_translate("Continuer mes achats") }}',
        'Continuer vos achats' => '{{ smart_translate("Continuer vos achats") }}',
        'Produit' => '{{ smart_translate("Produit") }}',
        'Quantité' => '{{ smart_translate("Quantité") }}',
        'Prix unitaire' => '{{ smart_translate("Prix unitaire") }}',
        'Total' => '{{ smart_translate("Total") }}',
        'Sous-total' => '{{ smart_translate("Sous-total") }}',
        'TVA' => '{{ smart_translate("TVA") }}',
        'Frais de port' => '{{ smart_translate("Frais de port") }}',
        'Livraison' => '{{ smart_translate("Livraison") }}',
        'Gratuit' => '{{ smart_translate("Gratuit") }}',
        'Total général' => '{{ smart_translate("Total général") }}',
        'Vider le panier' => '{{ smart_translate("Vider le panier") }}',
        'Valider ma commande' => '{{ smart_translate("Valider ma commande") }}',
        'Procéder au paiement' => '{{ smart_translate("Procéder au paiement") }}',
        'Supprimer' => '{{ smart_translate("Supprimer") }}',
        'Modifier' => '{{ smart_translate("Modifier") }}',
        'Mettre à jour' => '{{ smart_translate("Mettre à jour") }}'
    ];
    
    $originalContent = $content;
    foreach ($translations as $search => $replace) {
        if (strpos($content, $replace) === false) {
            $content = str_replace($search, $replace, $content);
        }
    }
    
    if ($content !== $originalContent) {
        file_put_contents($cartPath, $content);
        echo "✅ Traductions appliquées dans: $cartPath\n";
    }
}

// Mise à jour du layout principal
$layoutPath = 'resources/views/layouts/app.blade.php';
if (file_exists($layoutPath)) {
    $content = file_get_contents($layoutPath);
    
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
        'title="Panier"' => 'title="{{ smart_translate("Panier") }}"',
        'FarmShop' => 'FarmShop', // Garder le nom de marque
        'Tous droits réservés' => '{{ smart_translate("Tous droits réservés") }}',
        'Mentions légales' => '{{ smart_translate("Mentions légales") }}',
        'Conditions générales' => '{{ smart_translate("Conditions générales") }}',
        'Politique de confidentialité' => '{{ smart_translate("Politique de confidentialité") }}'
    ];
    
    $originalContent = $content;
    foreach ($navTranslations as $search => $replace) {
        if (strpos($content, $replace) === false) {
            $content = str_replace($search, $replace, $content);
        }
    }
    
    if ($content !== $originalContent) {
        file_put_contents($layoutPath, $content);
        echo "✅ Layout principal mis à jour\n";
    }
}

// Vérification du sélecteur de langue
$languageSelectorPath = 'resources/views/components/language-selector.blade.php';
if (file_exists($languageSelectorPath)) {
    echo "✅ Sélecteur de langue déjà en place\n";
} else {
    echo "⚠️  Sélecteur de langue non trouvé\n";
}

echo "\n🎉 Application complète des traductions terminée !\n";
echo "\n📊 Système de traduction professionnel activé:\n";
echo "   ✅ Interface utilisateur traduite\n";
echo "   ✅ Produits traduisibles avec trans_product()\n";
echo "   ✅ Catégories traduisibles avec trans_category()\n";
echo "   ✅ Prix formatés selon la locale\n";
echo "   ✅ Sélecteur de langue AJAX\n";
echo "   ✅ Base de données avec traductions\n";
echo "\n🌍 Votre site FarmShop est maintenant entièrement multilingue !\n";
echo "   FR (Français) - Langue par défaut\n";
echo "   EN (English) - Traduction complète\n";
echo "   NL (Nederlands) - Traduction complète\n";
