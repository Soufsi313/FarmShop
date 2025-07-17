<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;

// Créer un nouveau document Word
$phpWord = new PhpWord();

// Configuration de la police par défaut
$phpWord->setDefaultFontName('Inter');
$phpWord->setDefaultFontSize(12);

// Styles personnalisés pour un document académique professionnel
$phpWord->addTitleStyle(1, [
    'name' => 'Inter', 
    'size' => 18, 
    'bold' => true, 
    'color' => '2c3e50'
]);
$phpWord->addTitleStyle(2, [
    'name' => 'Inter', 
    'size' => 16, 
    'bold' => true, 
    'color' => '34495e'
]);
$phpWord->addTitleStyle(3, [
    'name' => 'Inter', 
    'size' => 14, 
    'bold' => true, 
    'color' => '7f8c8d'
]);

// Styles pour le contenu
$relationStyle = ['name' => 'Inter', 'size' => 12];
$strongRelationStyle = ['name' => 'Inter', 'size' => 12, 'bold' => true];
$tableStyle = ['name' => 'Inter', 'size' => 11];

// Créer la première section avec marges appropriées
$section = $phpWord->addSection([
    'marginTop' => 1440,    // 2.5cm
    'marginBottom' => 1440, // 2.5cm
    'marginLeft' => 1440,   // 2.5cm
    'marginRight' => 1440   // 2.5cm
]);

// En-tête académique professionnel
$section->addText('COMMUNAUTE FRANCAISE DE BELGIQUE', [
    'name' => 'Inter', 'size' => 14, 'bold' => true
], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

$section->addText('Institut des Carrieres Commerciales', [
    'name' => 'Inter', 'size' => 13, 'bold' => true
], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

$section->addText('Ville de Bruxelles', [
    'name' => 'Inter', 'size' => 12
], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

$section->addText('Rue de la Fontaine 4 - 1000 BRUXELLES', [
    'name' => 'Inter', 'size' => 11
], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

$section->addTextBreak(2);

// Titre principal
$section->addText('LIVRABLE 08', [
    'name' => 'Inter', 'size' => 20, 'bold' => true
], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

$section->addText('SCHEMA DE BASE DE DONNEES', [
    'name' => 'Inter', 'size' => 18, 'bold' => true
], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

$section->addTextBreak(2);

// Informations académiques
$section->addText('Epreuve integree realisee en vue de l\'obtention du titre de', [
    'name' => 'Inter', 'size' => 11, 'italic' => true
], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

$section->addText('Bachelier en Informatique de gestion', [
    'name' => 'Inter', 'size' => 12, 'bold' => true
], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

$section->addText('Orientation developpement d\'applications', [
    'name' => 'Inter', 'size' => 11
], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

$section->addTextBreak(3);

// Informations étudiante
$section->addText('MEFTAH Soufiane', [
    'name' => 'Inter', 'size' => 16, 'bold' => true
], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

$section->addText('Annee academique 2024-2025', [
    'name' => 'Inter', 'size' => 12
], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

$section->addTextBreak(4);

// Introduction
$section->addTitle('1. INTRODUCTION', 1);

$section->addText('FarmShop est une plateforme e-commerce innovante specialisee dans la vente et la location d\'equipements agricoles. Le systeme gere un double flux metier : les achats traditionnels et un systeme de location unique avec gestion sophistiquee des cautions, penalites et contraintes temporelles.', $relationStyle);

$section->addTextBreak();

$section->addText('Ce document presente l\'architecture de la base de donnees supportant l\'ensemble des fonctionnalites de la plateforme, incluant la gestion des utilisateurs, des produits, des commandes et du systeme de location.', $relationStyle);

$section->addTextBreak(2);

// Relations Utilisateurs
$section->addTitle('2. RELATIONS UTILISATEURS', 1);

$section->addTitle('2.1 Relations Principales', 2);

$relations_principales = [
    'Un utilisateur peut creer plusieurs produits - Un produit appartient a un utilisateur [1-*]',
    'Un utilisateur peut passer plusieurs commandes - Une commande appartient a un utilisateur [1-*]',
    'Un utilisateur peut avoir plusieurs commandes de location - Une commande de location appartient a un utilisateur [1-*]',
    'Un utilisateur peut avoir plusieurs locations actives - Une location appartient a un utilisateur [1-*]',
    'Un utilisateur possede un panier d\'achat - Un panier appartient a un utilisateur [1-1]',
    'Un utilisateur peut avoir plusieurs paniers de location - Un panier de location appartient a un utilisateur [1-*]'
];

foreach ($relations_principales as $relation) {
    $section->addText('• ' . $relation, $relationStyle);
}

$section->addTextBreak();

$section->addTitle('2.2 Relations Interactions', 2);

$relations_interactions = [
    'Un utilisateur peut envoyer plusieurs contacts - Un contact appartient a un utilisateur [1-*]',
    'Un utilisateur peut aimer plusieurs produits - Un produit peut etre aime par plusieurs utilisateurs [*-*]',
    'Un utilisateur peut avoir plusieurs produits en wishlist - Un produit peut etre dans plusieurs wishlists [*-*]'
];

foreach ($relations_interactions as $relation) {
    $section->addText('• ' . $relation, $relationStyle);
}

$section->addTextBreak(2);

// Relations Produits & Catégories
$section->addTitle('3. RELATIONS PRODUITS ET CATEGORIES', 1);

$section->addTitle('3.1 Structure des Produits', 2);

$relations_produits = [
    'Une categorie contient plusieurs produits - Un produit appartient a une categorie [1-*]',
    'Un produit peut avoir plusieurs images - Une image appartient a un produit [1-*]',
    'Un produit peut avoir plusieurs offres speciales - Une offre speciale appartient a un produit [1-*]'
];

foreach ($relations_produits as $relation) {
    $section->addText('• ' . $relation, $relationStyle);
}

$section->addTextBreak();

$section->addTitle('3.2 Interactions Utilisateurs-Produits', 2);

$relations_interactions_produits = [
    'Un produit peut etre aime par plusieurs utilisateurs - Un utilisateur peut aimer plusieurs produits [*-*]',
    'Un produit peut etre dans plusieurs wishlists - Un utilisateur peut avoir plusieurs produits en wishlist [*-*]',
    'Un produit peut etre ajoute dans plusieurs paniers - Un article de panier reference un produit [1-*]',
    'Un produit peut etre loue dans plusieurs paniers de location - Un article de location reference un produit [1-*]'
];

foreach ($relations_interactions_produits as $relation) {
    $section->addText('• ' . $relation, $relationStyle);
}

$section->addTextBreak(2);

// Relations Panier & Articles
$section->addTitle('4. RELATIONS PANIER ET ARTICLES', 1);

$section->addTitle('4.1 Panier d\'Achat Classique', 2);

$relations_panier = [
    'Un panier contient plusieurs articles - Un article de panier appartient a un panier [1-*]',
    'Un utilisateur peut avoir plusieurs articles dans son panier - Un article de panier appartient a un utilisateur [1-*]',
    'Un produit peut etre dans plusieurs paniers - Un article de panier reference un produit [1-*]'
];

foreach ($relations_panier as $relation) {
    $section->addText('• ' . $relation, $relationStyle);
}

$section->addTextBreak();

$section->addTitle('4.2 Panier de Location', 2);

$relations_panier_location = [
    'Un panier de location contient plusieurs articles de location - Un article de location appartient a un panier de location [1-*]',
    'Un produit peut etre dans plusieurs paniers de location - Un article de location reference un produit [1-*]'
];

foreach ($relations_panier_location as $relation) {
    $section->addText('• ' . $relation, $relationStyle);
}

$section->addTextBreak(2);

// Tables principales
$section->addTitle('5. TABLES PRINCIPALES IDENTIFIEES', 1);

$tablesData = [
    ['5.1 Tables Utilisateurs et Authentification', ['users', 'roles', 'permissions', 'model_has_roles', 'model_has_permissions', 'role_has_permissions']],
    ['5.2 Tables Produits et Catalogue', ['categories', 'products', 'product_images', 'special_offers']],
    ['5.3 Tables Panier et Navigation', ['carts', 'cart_items', 'cart_locations', 'cart_item_locations']],
    ['5.4 Tables Commandes', ['orders', 'order_items', 'order_returns', 'order_locations', 'order_item_locations']],
    ['5.5 Tables Locations', ['rentals', 'rental_items', 'rental_penalties']],
    ['5.6 Tables Communication', ['contacts', 'admin_messages', 'admin_message_replies']],
    ['5.7 Tables Contenu', ['blogs', 'blog_comments', 'blog_comment_reports', 'newsletters', 'newsletter_subscriptions']],
    ['5.8 Tables Interactions', ['product_likes', 'wishlists']],
    ['5.9 Tables Confidentialite', ['cookies', 'cookie_consents']]
];

foreach ($tablesData as [$title, $tables]) {
    $section->addTitle($title, 2);
    
    foreach ($tables as $table) {
        $section->addText('• ' . $table, $tableStyle);
    }
    
    $section->addTextBreak();
}

$section->addTextBreak(2);

// Notes techniques
$section->addTitle('6. NOTES TECHNIQUES', 1);

$section->addTitle('6.1 Conventions de Nommage', 2);

$conventions = [
    'Cles primaires : id (BIGINT UNSIGNED)',
    'Cles etrangeres : {table}_id (BIGINT UNSIGNED)',
    'Timestamps : created_at, updated_at',
    'Soft deletes : deleted_at (sur certaines tables sensibles)'
];

foreach ($conventions as $convention) {
    $section->addText('• ' . $convention, $tableStyle);
}

$section->addTextBreak();

$section->addTitle('6.2 Particularites Metier', 2);

$particularites = [
    'Systeme dual achat/location avec des flux separes',
    'Gestion des cautions pour les locations',
    'Systeme de penalites automatise (10 euros/jour de retard)',
    'Conversion automatique panier vers commande vers location',
    'Gestion des retours pour les achats',
    'Systeme de notifications integrees'
];

foreach ($particularites as $particularite) {
    $section->addText('• ' . $particularite, $tableStyle);
}

$section->addTextBreak(4);

// Footer académique
$section->addText('Document genere le 15 juillet 2025', [
    'name' => 'Inter', 'size' => 10
], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

$section->addText('MEFTAH Soufiane - Bachelier en Informatique de gestion', [
    'name' => 'Inter', 'size' => 10
], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

$section->addText('Institut des Carrieres Commerciales - Bruxelles', [
    'name' => 'Inter', 'size' => 10
], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

// Sauvegarder le document
$objWriter = IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save(__DIR__ . '/08_Schema_Base_Donnees_Pro.docx');

echo "Document Word cree avec succes : 08_Schema_Base_Donnees_Pro.docx\n";
echo "Emplacement : " . __DIR__ . "/08_Schema_Base_Donnees_Pro.docx\n";
echo "Document academique professionnel avec police Inter genere !\n";

?>
