<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;

// Créer un nouveau document Word
$phpWord = new PhpWord();

// Configuration de la police par défaut
$phpWord->setDefaultFontName('Arial');
$phpWord->setDefaultFontSize(11);

// Styles pour le document
$phpWord->addTitleStyle(1, ['name' => 'Arial', 'size' => 18, 'bold' => true]);
$phpWord->addTitleStyle(2, ['name' => 'Arial', 'size' => 16, 'bold' => true]);
$phpWord->addTitleStyle(3, ['name' => 'Arial', 'size' => 14, 'bold' => true]);

$contentStyle = ['name' => 'Arial', 'size' => 11];
$strongStyle = ['name' => 'Arial', 'size' => 11, 'bold' => true];

// Créer la section
$section = $phpWord->addSection([
    'marginTop' => 1440,
    'marginBottom' => 1440,
    'marginLeft' => 1440,
    'marginRight' => 1440
]);

// PAGE DE GARDE
$section->addText('Communaute Francaise de Belgique', [
    'name' => 'Arial', 'size' => 14, 'bold' => true
], ['alignment' => Jc::CENTER]);

$section->addText('Institut des Carrieres Commerciales', [
    'name' => 'Arial', 'size' => 13, 'bold' => true
], ['alignment' => Jc::CENTER]);

$section->addText('Ville de Bruxelles', [
    'name' => 'Arial', 'size' => 12
], ['alignment' => Jc::CENTER]);

$section->addText('Rue de la Fontaine, 4', [
    'name' => 'Arial', 'size' => 11
], ['alignment' => Jc::CENTER]);

$section->addText('1000 Bruxelles', [
    'name' => 'Arial', 'size' => 11
], ['alignment' => Jc::CENTER]);

$section->addTextBreak(10);

$section->addText('MEFTAH Soufiane', [
    'name' => 'Arial', 'size' => 16, 'bold' => true
], ['alignment' => Jc::CENTER]);

$section->addTextBreak(2);

$section->addText('2024 - 2025', [
    'name' => 'Arial', 'size' => 12
], ['alignment' => Jc::CENTER]);

// Nouvelle page
$section->addPageBreak();

// TABLE DES MATIERES
$section->addTitle('Table des matieres', 1);

$section->addText('L\'objectif general..........................................................................4', $contentStyle);
$section->addText('L\'analyse fonctionnelle.....................................................................5', $contentStyle);
$section->addText('Le produit minimum viable...................................................................5', $contentStyle);
$section->addText('1. Cahier de charges fonctionnel.......................................................6', $contentStyle);
$section->addText('1.1 Description des utilisateurs.......................................................6', $contentStyle);
$section->addText('1.2 Exigences fonctionnelles............................................................6', $contentStyle);
$section->addText('F1 : Creer un compte utilisateur.......................................................6', $contentStyle);
$section->addText('F2 : Se connecter.......................................................................6', $contentStyle);
$section->addText('1.3 Exigences non-fonctionnelles.......................................................20', $contentStyle);
$section->addText('2. DESCRIPTION DU SCHEMA...............................................................27', $contentStyle);
$section->addText('3. ARCHITECTURE TECHNIQUE ET DESIGN SYSTEM............................................34', $contentStyle);
$section->addText('4. SYSTEME DE COULEURS IMPLEMENTE.....................................................34', $contentStyle);
$section->addText('5. TYPOGRAPHIE ET POLICES..............................................................36', $contentStyle);
$section->addText('6. NAVIGATION ET STRUCTURE.............................................................36', $contentStyle);
$section->addText('7. COMPOSANTS UI IMPLEMENTES..........................................................37', $contentStyle);
$section->addText('8. RESPONSIVE DESIGN ET ANIMATIONS....................................................37', $contentStyle);
$section->addText('9. FONCTIONNALITES SPECIFIQUES FARMSHOP...............................................37', $contentStyle);
$section->addText('10. OPTIMISATIONS ET PERFORMANCES.....................................................38', $contentStyle);
$section->addText('11. CONCLUSION ET EVOLUTIONS..........................................................38', $contentStyle);
$section->addText('12. Bibliographie.....................................................................39', $contentStyle);

// Nouvelle page
$section->addPageBreak();

// INTRODUCTION
$section->addTitle('Introduction', 1);

$section->addText('Avec le temps la technologie informatique n\'a cesse d\'evoluer, c\'est pourquoi toute entreprise moderne doit avoir en son sein un site web, une application pour satisfaire la demande toujours croissante des consommateurs.', $contentStyle);

$section->addTextBreak();

$section->addText('Bien heureusement, le monde du developpement le permet grace a des outils que nous allons utiliser tous le long de ce projet e-commerce, ainsi chaque etape du projet sera consignee dans ce rapport ecrit.', $contentStyle);

$section->addTextBreak(2);

$section->addTitle('L\'objectif general', 2);

$section->addText('L\'objectif general est de creer une plateforme innovante et intuitive pour que chaque utilisateur puisse naviguer rapidement et de maniere ergonomique.', $contentStyle);

$section->addTextBreak();

$section->addText('Le public cible vise toutes les personnes issues du monde agricole ou non. Toutes les rubriques du site seront concues pour que chaque visiteur puisse comprendre sans pour autant etre familier du milieu de l\'agriculture.', $contentStyle);

$section->addTextBreak(2);

$section->addTitle('L\'analyse fonctionnelle', 2);

$section->addText('L\'objectif fonctionnelle de notre application sera de continuellement ameliorer l\'ergonomie de notre site. Nous simplifierons les processus de commande et les methodes de paiement.', $contentStyle);

$section->addTextBreak();

$section->addText('Le site sera bilingue Anglais-Francais pour etendre la zone de chalandise sur tout le continent europeen.', $contentStyle);

$section->addTextBreak(2);

$section->addTitle('Le produit minimum viable', 2);

$section->addText('Le produit minimum viable comportera un acces a la page d\'accueil du site avec tous les onglets places sur un menu de navigation intuitif et ergonomique.', $contentStyle);

$section->addTextBreak();

$section->addText('Un back-office uniquement visible pour notre administrateur pour gerer les demandes de formulaire, et les CRUD en matiere de gestion de stock, articles de blog, commentaires.', $contentStyle);

// Nouvelle page
$section->addPageBreak();

// CAHIER DE CHARGES
$section->addTitle('1. Cahier de charges fonctionnel', 1);

$section->addTitle('1.1 Description des utilisateurs', 2);

$section->addText('• Visiteur : Internaute non connecte.', $contentStyle);
$section->addText('• Membre : Internaute inscrit et connecte au site.', $contentStyle);
$section->addText('• Administrateur : Utilisateur jouissant de droits avances.', $contentStyle);

$section->addTextBreak();

$section->addTitle('1.2 Exigences fonctionnelles', 2);

$section->addTitle('F1 : Creer un compte utilisateur', 3);
$section->addText('Utilisateurs : visiteur', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes : Une adresse e-mail valide.', $contentStyle);
$section->addText('Description : Le visiteur cree un compte utilisateur a l\'application web.', $contentStyle);
$section->addTextBreak();

$section->addTitle('F2 : Se connecter', 3);
$section->addText('Utilisateurs : membre', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes : Avoir un compte utilisateur actif.', $contentStyle);
$section->addText('Description : Le membre se connecte avec email et mot de passe.', $contentStyle);
$section->addTextBreak();

$section->addTitle('F15 : Louer des produits agricoles', 3);
$section->addText('Utilisateurs : membres', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes : Etre connecte en tant que membre.', $contentStyle);
$section->addText('Description : Le membre selectionne les produits destines a la location.', $contentStyle);
$section->addTextBreak();

$section->addTitle('1.3 Exigences non-fonctionnelles', 2);

$section->addTitle('N1 : Rendre fluide la navigation', 3);
$section->addText('Utilisateurs : visiteur/membre/administrateur', $contentStyle);
$section->addText('Importance : 4/5', $contentStyle);
$section->addText('Description : Navigation rapide, moins de 0.5sec de chargement.', $contentStyle);
$section->addTextBreak();

$section->addTitle('N3 : Securiser les donnees', 3);
$section->addText('Utilisateurs : tous', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Description : Protocole HTTPS, hachage mot de passe, respect RGPD.', $contentStyle);
$section->addTextBreak();

// Nouvelle page
$section->addPageBreak();

// DESCRIPTION DU SCHEMA
$section->addTitle('2. DESCRIPTION DU SCHEMA', 1);

$section->addText('FarmShop est une plateforme e-commerce specialisee dans la vente et la location de produits agricoles, developpe avec Laravel 11 LTS.', $contentStyle);

$section->addTextBreak();

$section->addTitle('RELATIONS UTILISATEURS', 2);
$section->addText('Un utilisateur peut creer plusieurs produits - Un produit appartient a un utilisateur [1-*]', $contentStyle);
$section->addText('Un utilisateur peut passer plusieurs commandes - Une commande appartient a un utilisateur [1-*]', $contentStyle);
$section->addText('Un utilisateur peut avoir plusieurs paniers de location [1-*]', $contentStyle);

$section->addTextBreak();

$section->addTitle('TABLES PRINCIPALES', 2);
$section->addText('• users (table principale des utilisateurs)', $contentStyle);
$section->addText('• products (produits)', $contentStyle);
$section->addText('• categories (categories de produits)', $contentStyle);
$section->addText('• carts (paniers d\'achat)', $contentStyle);
$section->addText('• cart_locations (paniers de location)', $contentStyle);
$section->addText('• orders (commandes)', $contentStyle);

// Nouvelle page
$section->addPageBreak();

// ARCHITECTURE TECHNIQUE
$section->addTitle('3. ARCHITECTURE TECHNIQUE ET DESIGN SYSTEM', 1);

$section->addText('FarmShop est developpe avec Laravel 11 LTS et utilise Tailwind CSS comme systeme de design frontend principal.', $contentStyle);

$section->addTextBreak();

$section->addTitle('Nouveautes Laravel 11 implementees', 2);
$section->addText('• Nouveau systeme de routing simplifie', $contentStyle);
$section->addText('• Eloquent ORM ameliore', $contentStyle);
$section->addText('• Support natif de PHP 8.3', $contentStyle);
$section->addText('• Systeme de cache Redis integre', $contentStyle);

$section->addTextBreak();

// SYSTEME DE COULEURS
$section->addTitle('4. SYSTEME DE COULEURS IMPLEMENTE', 1);

$section->addText('La palette de couleurs est definie via Tailwind CSS dans tailwind.config.js.', $contentStyle);

$section->addTextBreak();

// Tableau simple des couleurs
$colorTable = $section->addTable(['borderSize' => 6, 'borderColor' => '999999']);
$colorTable->addRow();
$colorTable->addCell(3000)->addText('Couleur', $strongStyle);
$colorTable->addCell(2000)->addText('Code', $strongStyle);
$colorTable->addCell(4000)->addText('Usage', $strongStyle);

$colorTable->addRow();
$colorTable->addCell(3000)->addText('farm-green-500', $contentStyle);
$colorTable->addCell(2000)->addText('#22c55e', $contentStyle);
$colorTable->addCell(4000)->addText('Couleur principale', $contentStyle);

$colorTable->addRow();
$colorTable->addCell(3000)->addText('farm-brown-500', $contentStyle);
$colorTable->addCell(2000)->addText('#a18072', $contentStyle);
$colorTable->addCell(4000)->addText('Couleur secondaire', $contentStyle);

$section->addTextBreak();

// TYPOGRAPHIE
$section->addTitle('5. TYPOGRAPHIE ET POLICES', 1);

$section->addText('FarmShop utilise la police Inter configuree dans Tailwind CSS pour une lisibilite optimale.', $contentStyle);

$section->addTextBreak();

// NAVIGATION
$section->addTitle('6. NAVIGATION ET STRUCTURE', 1);

$section->addText('La navigation utilise Tailwind CSS avec des composants Alpine.js pour l\'interactivite.', $contentStyle);

$section->addTextBreak();

// COMPOSANTS UI
$section->addTitle('7. COMPOSANTS UI IMPLEMENTES', 1);

$section->addText('Les composants utilisent les classes Tailwind CSS avec Alpine.js pour les interactions.', $contentStyle);

$section->addTextBreak();

// RESPONSIVE DESIGN
$section->addTitle('8. RESPONSIVE DESIGN ET ANIMATIONS', 1);

$section->addText('Le design responsive utilise le systeme de breakpoints Tailwind CSS avec des animations optimisees.', $contentStyle);

$section->addTextBreak();

// FONCTIONNALITES SPECIFIQUES
$section->addTitle('9. FONCTIONNALITES SPECIFIQUES FARMSHOP', 1);

$section->addText('L\'interface reflete la double vocation de FarmShop avec des composants visuels distincts pour l\'achat et la location.', $contentStyle);

$section->addTextBreak();

// OPTIMISATIONS
$section->addTitle('10. OPTIMISATIONS ET PERFORMANCES', 1);

$section->addText('Les optimisations tirent parti des nouveautes Laravel 11 pour ameliorer les performances.', $contentStyle);

$section->addTextBreak();

$section->addText('• Vite.js : Bundling optimise', $contentStyle);
$section->addText('• Eager Loading : Relations Eloquent optimisees', $contentStyle);
$section->addText('• Redis Cache : Mise en cache des donnees', $contentStyle);

$section->addTextBreak();

// CONCLUSION
$section->addTitle('11. CONCLUSION ET EVOLUTIONS', 1);

$section->addText('L\'implementation de FarmShop respecte les standards modernes avec Laravel 11 LTS et Tailwind CSS.', $contentStyle);

$section->addTextBreak();

$section->addText('Points forts :', $contentStyle);
$section->addText('• Architecture moderne avec Laravel 11', $contentStyle);
$section->addText('• Design system coherent avec Tailwind CSS', $contentStyle);
$section->addText('• Performance optimisee avec Vite.js', $contentStyle);

$section->addTextBreak();

// BIBLIOGRAPHIE
$section->addTitle('12. Bibliographie', 1);

$section->addText('LARAVEL. S.d. Laravel 11 Documentation. Site web sur INTERNET. <laravel.com/docs/11.x>. Derniere consultation : le 15/07-2025.', $contentStyle);
$section->addTextBreak();

$section->addText('TAILWIND CSS. S.d. Tailwind CSS. Site web sur INTERNET. <tailwindcss.com>. Derniere consultation : le 15/07-2025.', $contentStyle);
$section->addTextBreak();

$section->addText('ALPINE.JS. S.d. Alpine.js. Site web sur INTERNET. <alpinejs.dev>. Derniere consultation : le 15/07-2025.', $contentStyle);
$section->addTextBreak();

$section->addText('VITE.JS. S.d. Vite. Site web sur INTERNET. <vitejs.dev>. Derniere consultation : le 15/07-2025.', $contentStyle);

$section->addTextBreak(3);

// Footer
$section->addText('Document genere le 16 juillet 2025', [
    'name' => 'Arial', 'size' => 10
], ['alignment' => Jc::CENTER]);

$section->addText('MEFTAH Soufiane - Bachelier en Informatique de gestion', [
    'name' => 'Arial', 'size' => 10
], ['alignment' => Jc::CENTER]);

$section->addText('Institut des Carrieres Commerciales - Bruxelles', [
    'name' => 'Arial', 'size' => 10
], ['alignment' => Jc::CENTER]);

// Sauvegarder le document
$objWriter = IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save(__DIR__ . '/12_Rapport_Ecrit_Simple.docx');

echo "Rapport ecrit cree avec succes !\n";
echo "Fichier : 12_Rapport_Ecrit_Simple.docx\n";

?>
