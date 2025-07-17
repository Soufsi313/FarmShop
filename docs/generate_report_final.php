<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;

// Créer un nouveau document Word
$phpWord = new PhpWord();

// Configuration de base
$phpWord->setDefaultFontName('Times New Roman');
$phpWord->setDefaultFontSize(12);

// Styles simples
$phpWord->addTitleStyle(1, ['name' => 'Times New Roman', 'size' => 16, 'bold' => true]);
$phpWord->addTitleStyle(2, ['name' => 'Times New Roman', 'size' => 14, 'bold' => true]);
$phpWord->addTitleStyle(3, ['name' => 'Times New Roman', 'size' => 12, 'bold' => true]);

// Styles pour le contenu
$normalStyle = ['name' => 'Times New Roman', 'size' => 12];
$boldStyle = ['name' => 'Times New Roman', 'size' => 12, 'bold' => true];

// Créer la section
$section = $phpWord->addSection([
    'marginTop' => 1440,
    'marginBottom' => 1440,
    'marginLeft' => 1440,
    'marginRight' => 1440
]);

// PAGE DE GARDE
$section->addText('Communaute Francaise de Belgique', [
    'name' => 'Times New Roman', 'size' => 14, 'bold' => true
], ['alignment' => Jc::CENTER]);

$section->addText('Institut des Carrieres Commerciales', [
    'name' => 'Times New Roman', 'size' => 13, 'bold' => true
], ['alignment' => Jc::CENTER]);

$section->addText('Ville de Bruxelles', [
    'name' => 'Times New Roman', 'size' => 12
], ['alignment' => Jc::CENTER]);

$section->addText('Rue de la Fontaine, 4', $normalStyle, ['alignment' => Jc::CENTER]);
$section->addText('1000 Bruxelles', $normalStyle, ['alignment' => Jc::CENTER]);

$section->addTextBreak(15);

$section->addText('MEFTAH Soufiane', [
    'name' => 'Times New Roman', 'size' => 16, 'bold' => true
], ['alignment' => Jc::CENTER]);

$section->addTextBreak(2);

$section->addText('2024 - 2025', $normalStyle, ['alignment' => Jc::CENTER]);

$section->addPageBreak();

// TABLE DES MATIERES
$section->addTitle('Table des matieres', 1);

$section->addText('L\'objectif general................................................4', $normalStyle);
$section->addText('L\'analyse fonctionnelle..........................................5', $normalStyle);
$section->addText('Le produit minimum viable.........................................5', $normalStyle);
$section->addText('1. Cahier de charges fonctionnel.................................6', $normalStyle);
$section->addText('1.1 Description des utilisateurs.................................6', $normalStyle);
$section->addText('1.2 Exigences fonctionnelles.....................................6', $normalStyle);
$section->addText('1.3 Exigences non-fonctionnelles................................20', $normalStyle);
$section->addText('2. DESCRIPTION DU SCHEMA........................................27', $normalStyle);
$section->addText('3. ARCHITECTURE TECHNIQUE ET DESIGN SYSTEM......................34', $normalStyle);
$section->addText('4. SYSTEME DE COULEURS IMPLEMENTE...............................34', $normalStyle);
$section->addText('5. TYPOGRAPHIE ET POLICES.......................................36', $normalStyle);
$section->addText('6. NAVIGATION ET STRUCTURE......................................36', $normalStyle);
$section->addText('7. COMPOSANTS UI IMPLEMENTES....................................37', $normalStyle);
$section->addText('8. RESPONSIVE DESIGN ET ANIMATIONS..............................37', $normalStyle);
$section->addText('9. FONCTIONNALITES SPECIFIQUES FARMSHOP.........................37', $normalStyle);
$section->addText('10. OPTIMISATIONS ET PERFORMANCES...............................38', $normalStyle);
$section->addText('11. CONCLUSION ET EVOLUTIONS....................................38', $normalStyle);
$section->addText('12. Bibliographie...............................................39', $normalStyle);

$section->addPageBreak();

// INTRODUCTION
$section->addTitle('Introduction', 1);

$section->addText('Avec le temps la technologie informatique n\'a cesse d\'evoluer, c\'est pourquoi toute entreprise moderne doit avoir en son sein un site web, une application pour satisfaire la demande toujours croissante des consommateurs.', $normalStyle);

$section->addTextBreak();

$section->addTitle('L\'objectif general', 2);

$section->addText('L\'objectif general est de creer une plateforme innovante et intuitive pour que chaque utilisateur puisse naviguer rapidement et de maniere ergonomique. Chaque fonctionnalite du site a ete etudie pour fournir aux utilisateurs non connecte ou connecte la meilleure experience possible.', $normalStyle);

$section->addTextBreak();

$section->addTitle('L\'analyse fonctionnelle', 2);

$section->addText('L\'objectif fonctionnelle de notre application sera de continuellement ameliorer l\'ergonomie de notre site, c\'est-a-dire a la fois sur ecran PC et sur tablette.', $normalStyle);

$section->addTextBreak();

$section->addTitle('Le produit minimum viable', 2);

$section->addText('Le produit minimum viable comportera un acces a la page d\'accueil du site avec tous les onglets places sur un menu de navigation intuitif et ergonomique.', $normalStyle);

$section->addPageBreak();

// CAHIER DE CHARGES FONCTIONNEL
$section->addTitle('1. Cahier de charges fonctionnel', 1);

$section->addTitle('1.1 Description des utilisateurs', 2);

$section->addText('Visiteur : Internaute non connecte.', $normalStyle);
$section->addText('Membre : Internaute inscrit et connecte au site.', $normalStyle);
$section->addText('Administrateur : Utilisateur jouissant de droits avances sur les ressources et les autres utilisateurs.', $normalStyle);

$section->addTextBreak();

$section->addTitle('1.2 Exigences fonctionnelles', 2);

$section->addTitle('F1 : Creer un compte utilisateur', 3);
$section->addText('Utilisateurs : visiteur', $normalStyle);
$section->addText('Importance : 5/5', $normalStyle);
$section->addText('Contraintes : Une adresse e-mail valide.', $normalStyle);
$section->addText('Description : Le visiteur cree un compte utilisateur a l\'application web apres avoir introduit un email et un mot de passe.', $normalStyle);

$section->addTextBreak();

$section->addTitle('F2 : Se connecter', 3);
$section->addText('Utilisateurs : membre', $normalStyle);
$section->addText('Importance : 5/5', $normalStyle);
$section->addText('Contraintes : Avoir un compte utilisateur actif sur le site.', $normalStyle);
$section->addText('Description : Le membre se connecte en introduisant son email et un mot de passe valide.', $normalStyle);

$section->addTextBreak();

$section->addTitle('F15 : Louer des produits agricoles', 3);
$section->addText('Utilisateurs : membres', $normalStyle);
$section->addText('Importance : 5/5', $normalStyle);
$section->addText('Contraintes : Etre connecte en tant que membre.', $normalStyle);
$section->addText('Description : Le membre connecte selectionne les produits destines a la location.', $normalStyle);

$section->addTextBreak();

$section->addTitle('1.3 Exigences non-fonctionnelles', 2);

$section->addTitle('N1 : Rendre fluide la navigation', 3);
$section->addText('Utilisateurs : visiteur/membre/administrateur', $normalStyle);
$section->addText('Importance : 4/5', $normalStyle);
$section->addText('Description : Le visiteur ou le membre ne doit jamais attendre plus de 0.5sec qu\'une page du catalogue de produit se charge.', $normalStyle);

$section->addPageBreak();

// DESCRIPTION DU SCHEMA
$section->addTitle('2. DESCRIPTION DU SCHEMA', 1);

$section->addText('FarmShop est une plateforme e-commerce specialisee dans la vente et la location de produits agricoles et alimentaires. Le systeme gere a la fois les achats traditionnels et un systeme de location unique avec gestion des cautions et penalites, developpe avec Laravel 11 LTS.', $normalStyle);

$section->addTextBreak();

$section->addTitle('RELATIONS UTILISATEURS', 2);

$section->addText('Un utilisateur peut creer plusieurs produits - Un produit appartient a un utilisateur [1-*]', $normalStyle);
$section->addText('Un utilisateur peut passer plusieurs commandes - Une commande appartient a un utilisateur [1-*]', $normalStyle);
$section->addText('Un utilisateur peut avoir plusieurs commandes de location - Une commande de location appartient a un utilisateur [1-*]', $normalStyle);

$section->addPageBreak();

// ARCHITECTURE TECHNIQUE
$section->addTitle('3. ARCHITECTURE TECHNIQUE ET DESIGN SYSTEM', 1);

$section->addText('FarmShop est developpe avec Laravel 11 LTS comme framework backend et utilise Tailwind CSS comme systeme de design frontend principal. Cette combinaison assure une interface responsive, accessible et moderne.', $normalStyle);

$section->addTextBreak();

$section->addTitle('4. SYSTEME DE COULEURS IMPLEMENTE', 1);

$section->addText('Notre palette de couleurs est definie via la configuration Tailwind CSS dans tailwind.config.js, garantissant une coherence parfaite sur toute l\'application Laravel 11.', $normalStyle);

$section->addTextBreak();

$section->addTitle('5. TYPOGRAPHIE ET POLICES', 1);

$section->addText('FarmShop utilise la police Inter comme police principale, configuree dans Tailwind CSS. Ce choix garantit une lisibilite optimale et une personnalite visuelle moderne compatible avec Laravel 11.', $normalStyle);

$section->addTextBreak();

$section->addTitle('6. NAVIGATION ET STRUCTURE', 1);

$section->addText('La navigation principale utilise Tailwind CSS avec des composants Alpine.js. Chaque element de menu utilise une iconographie moderne pour ameliorer la reconnaissance visuelle et l\'accessibilite.', $normalStyle);

$section->addTextBreak();

$section->addTitle('7. COMPOSANTS UI IMPLEMENTES', 1);

$section->addText('Les composants utilisent les classes utilitaires Tailwind CSS avec des interactions Alpine.js. Cette approche moderne ameliore les performances et la maintenabilite du code Laravel 11.', $normalStyle);

$section->addTextBreak();

$section->addTitle('8. RESPONSIVE DESIGN ET ANIMATIONS', 1);

$section->addText('Le design responsive utilise le systeme de breakpoints Tailwind CSS avec des animations optimisees pour les performances. Toutes les animations utilisent transform et opacity pour eviter les reflows.', $normalStyle);

$section->addTextBreak();

$section->addTitle('9. FONCTIONNALITES SPECIFIQUES FARMSHOP', 1);

$section->addText('L\'interface reflete la double vocation de FarmShop avec des composants visuels distincts implementes avec les nouvelles fonctionnalites Laravel 11.', $normalStyle);

$section->addTextBreak();

$section->addTitle('10. OPTIMISATIONS ET PERFORMANCES', 1);

$section->addText('Les optimisations tirent parti des nouveautes Laravel 11 pour ameliorer les performances globales de l\'application FarmShop.', $normalStyle);

$section->addTextBreak();

$section->addTitle('11. CONCLUSION ET EVOLUTIONS', 1);

$section->addText('L\'implementation actuelle de FarmShop respecte les standards modernes de developpement avec Laravel 11 LTS. L\'utilisation de Tailwind CSS garantit la compatibilite cross-browser, tandis que les nouveautes Laravel 11 offrent des performances optimales.', $normalStyle);

$section->addTextBreak();

$section->addTitle('12. Bibliographie', 1);

$section->addText('LARAVEL. S.d. Laravel 11 Documentation. Site web sur INTERNET. <laravel.com/docs/11.x>. Derniere consultation : le 15/07-2025.', $normalStyle);
$section->addTextBreak();

$section->addText('TAILWIND CSS. S.d. Tailwind CSS. Site web sur INTERNET. <tailwindcss.com>. Derniere consultation : le 15/07-2025.', $normalStyle);
$section->addTextBreak();

$section->addText('ALPINE.JS. S.d. Alpine.js. Site web sur INTERNET. <alpinejs.dev>. Derniere consultation : le 15/07-2025.', $normalStyle);

$section->addTextBreak(2);

// Footer simple
$section->addText('Document genere le 16 juillet 2025', [
    'name' => 'Times New Roman', 'size' => 10
], ['alignment' => Jc::CENTER]);

$section->addText('MEFTAH Soufiane - Bachelier en Informatique de gestion', [
    'name' => 'Times New Roman', 'size' => 10
], ['alignment' => Jc::CENTER]);

// Sauvegarder avec options de compatibilité
$objWriter = IOFactory::createWriter($phpWord, 'Word2007');

// Configuration pour maximum de compatibilité
$objWriter->save(__DIR__ . '/12_Rapport_Ecrit_FINAL.docx');

echo "Rapport ecrit FINAL cree avec succes (version ultra-compatible) !\n";
echo "Emplacement : " . __DIR__ . "/12_Rapport_Ecrit_FINAL.docx\n";
echo "Document Word standard genere sans caracteres speciaux !\n";

?>
