<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\SimpleType\Jc;

// Créer un nouveau document Word
$phpWord = new PhpWord();

// Configuration de la police par défaut
$phpWord->setDefaultFontName('Inter');
$phpWord->setDefaultFontSize(11);

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
$contentStyle = ['name' => 'Inter', 'size' => 11];
$strongStyle = ['name' => 'Inter', 'size' => 11, 'bold' => true];
$italicStyle = ['name' => 'Inter', 'size' => 11, 'italic' => true];

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
], ['alignment' => Jc::CENTER]);

$section->addText('Institut des Carrieres Commerciales', [
    'name' => 'Inter', 'size' => 13, 'bold' => true
], ['alignment' => Jc::CENTER]);

$section->addText('Ville de Bruxelles', [
    'name' => 'Inter', 'size' => 12
], ['alignment' => Jc::CENTER]);

$section->addText('Rue de la Fontaine 4 - 1000 BRUXELLES', [
    'name' => 'Inter', 'size' => 11
], ['alignment' => Jc::CENTER]);

$section->addTextBreak(2);

// Titre principal
$section->addText('LIVRABLE 12', [
    'name' => 'Inter', 'size' => 20, 'bold' => true
], ['alignment' => Jc::CENTER]);

$section->addText('RAPPORT ECRIT VERSION 1', [
    'name' => 'Inter', 'size' => 18, 'bold' => true
], ['alignment' => Jc::CENTER]);

$section->addTextBreak(2);

// Informations académiques
$section->addText('Epreuve integree realisee en vue de l\'obtention du titre de', [
    'name' => 'Inter', 'size' => 11, 'italic' => true
], ['alignment' => Jc::CENTER]);

$section->addText('Bachelier en Informatique de gestion', [
    'name' => 'Inter', 'size' => 12, 'bold' => true
], ['alignment' => Jc::CENTER]);

$section->addText('Orientation developpement d\'applications', [
    'name' => 'Inter', 'size' => 11
], ['alignment' => Jc::CENTER]);

$section->addTextBreak(3);

// Informations étudiante
$section->addText('MEFTAH Soufiane', [
    'name' => 'Inter', 'size' => 16, 'bold' => true
], ['alignment' => Jc::CENTER]);

$section->addText('Annee academique 2024-2025', [
    'name' => 'Inter', 'size' => 12
], ['alignment' => Jc::CENTER]);

$section->addTextBreak(4);

// Nouvelle page pour table des matières
$section->addPageBreak();

// TABLE DES MATIERES
$section->addTitle('Table des matieres', 1);

$section->addText('L\'objectif general...................................................................4', $contentStyle);
$section->addText('L\'analyse fonctionnelle.............................................................5', $contentStyle);
$section->addText('Le produit minimum viable...........................................................5', $contentStyle);
$section->addText('1. Cahier de charges fonctionnel...................................................6', $contentStyle);
$section->addText('1.1 Description des utilisateurs...................................................6', $contentStyle);
$section->addText('1.2 Exigences fonctionnelles.......................................................6', $contentStyle);
$section->addText('F1 : Creer un compte utilisateur...................................................6', $contentStyle);
$section->addText('F2 : Se connecter...................................................................6', $contentStyle);
$section->addText('F3 : Consulter son profil...........................................................7', $contentStyle);
$section->addText('F4 : Modifier son profil............................................................7', $contentStyle);
$section->addText('F5 Se desinscrire...................................................................7', $contentStyle);
$section->addText('F6 : Consulter les produits du catalogue agricole..................................8', $contentStyle);
$section->addText('F7 Ajouter au panier d\'achat........................................................8', $contentStyle);
$section->addText('F8 Modifier le panier d\'achat.......................................................8', $contentStyle);
$section->addText('F9 Supprimer le panier d\'achat......................................................9', $contentStyle);
$section->addText('F10 : Payer l\'achat/location........................................................9', $contentStyle);
$section->addText('F11 Filtrer les produits par mots-cles..............................................9', $contentStyle);
$section->addText('F12 Filtrer les produits par categorie.............................................9', $contentStyle);
$section->addText('F13 Filtrer les produits par prix..................................................10', $contentStyle);
$section->addText('F14 Selectionner des offres speciales..............................................10', $contentStyle);
$section->addText('F15 : Louer des produits agricoles.................................................10', $contentStyle);
$section->addText('F16 Ajouter au panier de location..................................................11', $contentStyle);
$section->addText('F17 Modifier le panier de location.................................................11', $contentStyle);
$section->addText('F18 Supprimer le panier de location................................................11', $contentStyle);
$section->addText('F19 : Consulter des articles de blog...............................................12', $contentStyle);
$section->addText('F20 : commenter des articles de blog...............................................12', $contentStyle);
$section->addText('F21 : Signaler les commentaires....................................................12', $contentStyle);
$section->addText('F22 : Recherche un blog par mots-cles..............................................13', $contentStyle);
$section->addText('F23 : Filtrer le blog par categorie................................................13', $contentStyle);
$section->addText('F24 : Filtrer le blog par ordre alphabetique chronologique........................13', $contentStyle);
$section->addText('F25 : Consulter les factures.......................................................13', $contentStyle);
$section->addText('F26 : Telecharger les factures format PDF..........................................14', $contentStyle);
$section->addText('F27 : S\'abonner a la newsletter....................................................14', $contentStyle);
$section->addText('F28 : Se desabonner de la newsletter................................................14', $contentStyle);
$section->addText('F29 : Contacter l\'administrateur...................................................15', $contentStyle);
$section->addText('F30 : Changer la langue du site....................................................15', $contentStyle);
$section->addText('F31 Ajouter un produit a une WishList..............................................15', $contentStyle);
$section->addText('F32 Passer une commande............................................................16', $contentStyle);
$section->addText('F33 Annuler une commande...........................................................16', $contentStyle);
$section->addText('A1 : Ajouter des produits..........................................................17', $contentStyle);
$section->addText('A2 : Modifier des produits.........................................................17', $contentStyle);
$section->addText('A3 : Supprimer des produits........................................................17', $contentStyle);
$section->addText('A4 : Ajouter des articles de blog..................................................18', $contentStyle);
$section->addText('A5 : Modifier des articles de blog.................................................18', $contentStyle);
$section->addText('A6 : Supprimer des articles de blog................................................18', $contentStyle);
$section->addText('A7 : Gerer les signalements des commentaires sur le blog...........................19', $contentStyle);
$section->addText('A8 : Ajouter des utilisateurs......................................................19', $contentStyle);
$section->addText('A9 : Modifier des utilisateurs.....................................................19', $contentStyle);
$section->addText('A10 : Supprimer des utilisateurs...................................................19', $contentStyle);
$section->addText('1.3 Exigences non-fonctionnelles...................................................20', $contentStyle);
$section->addText('N1 : Rendre fluide la navigation....................................................20', $contentStyle);
$section->addText('N2 : Deconnexion automatique apres inactivite......................................20', $contentStyle);
$section->addText('N3 Securiser les donnees et proteger les informations sensibles....................20', $contentStyle);
$section->addText('N4 Penaliser les retards...........................................................21', $contentStyle);
$section->addText('N5 Penaliser les degats materiels..................................................21', $contentStyle);
$section->addText('N6 : Configurer les cookies avec le back office....................................21', $contentStyle);
$section->addText('N7 : Accepter les cookies..........................................................22', $contentStyle);
$section->addText('N8 : Refuser les cookies...........................................................22', $contentStyle);
$section->addText('N9 : Parametrer les cookies........................................................22', $contentStyle);
$section->addText('2. DESCRIPTION DU SCHEMA...........................................................27', $contentStyle);
$section->addText('3. ARCHITECTURE TECHNIQUE ET DESIGN SYSTEM.........................................34', $contentStyle);
$section->addText('4. SYSTEME DE COULEURS IMPLEMENTE..................................................34', $contentStyle);
$section->addText('5. TYPOGRAPHIE ET POLICES..........................................................36', $contentStyle);
$section->addText('6. NAVIGATION ET STRUCTURE.........................................................36', $contentStyle);
$section->addText('7. COMPOSANTS UI IMPLEMENTES......................................................37', $contentStyle);
$section->addText('8. RESPONSIVE DESIGN ET ANIMATIONS................................................37', $contentStyle);
$section->addText('9. FONCTIONNALITES SPECIFIQUES FARMSHOP...........................................37', $contentStyle);
$section->addText('10. OPTIMISATIONS ET PERFORMANCES.................................................38', $contentStyle);
$section->addText('11. CONCLUSION ET EVOLUTIONS......................................................38', $contentStyle);
$section->addText('12. Bibliographie.................................................................39', $contentStyle);

// Nouvelle page pour l'introduction
$section->addPageBreak();

// INTRODUCTION
$section->addTitle('Introduction', 1);

$section->addText('Avec le temps la technologie informatique n\'a cesse d\'evoluer, c\'est pourquoi toute entreprise moderne doit avoir en son sein un site web, une application pour satisfaire la demande toujours croissante des consommateurs. Mais cela ne suffit pas, avoir seulement une vitrine virtuelle avec des produits en stock n\'est pas un argument convaincant pour fideliser les clients d\'une entreprise, pour cela, elle doit faire preuve de creativite pour mettre en valeur ses produits. Bien heureusement, le monde du developpement le permet grace a des outils que nous allons utiliser tous le long de ce projet e-commerce, ainsi chaque etape du projet sera consignee dans ce rapport ecrit.', $contentStyle);

$section->addTextBreak(2);

$section->addTitle('L\'objectif general', 2);

$section->addText('L\'objectif general est de creer une plateforme innovante et intuitive pour que chaque utilisateur puisse naviguer rapidement et de maniere ergonomique. Chaque fonctionnalite du site a ete etudie pour fournir aux utilisateurs non connecte ou connecte la meilleure experience possible.', $contentStyle);

$section->addTextBreak();

$section->addText('Le public cible vise toutes les personnes issues du monde agricole ou non. Toutes les rubriques du site seront concues pour que chaque visiteur puisse comprendre sans pour autant etre familier du milieu de l\'agriculture.', $contentStyle);

$section->addTextBreak(2);

$section->addTitle('L\'analyse fonctionnelle', 2);

$section->addText('L\'objectif fonctionnelle de notre application sera de continuellement ameliorer l\'ergonomie de notre site, c\'est-a-dire a la fois sur ecran PC et sur tablette. Nous simplifierons les processus de commande et les methodes de paiement en faisant appel a une API externe par exemple pour finaliser l\'achat et la location. Nous tiendrons informe quotidiennement nos utilisateurs avec des articles qui paraitront sur un blog qu\'ils pourront commenter, cette strategie sera d\'ailleurs efficace pour le referencement de notre site sur les moteurs de recherche. Le site sera enfin bilingue Anglais-Francais pour etendre la zone de chalandise sur tous le continent europeen.', $contentStyle);

$section->addTextBreak();

$section->addText('L\'analyse fonctionnelle est primordiale pour etablir une strategie qui nous demarquera de la concurrence deja bien etabli. En selectionnant avec soin nos produits, nous serons dans la capacite de vous donner un maximum d\'information sur la provenance et la qualite de ceux-ci. Chaque produit sera adapte aux besoins du client, que ce soit pour de grandes exploitations, jusqu\'au petit potager du jardin ou terrasse.', $contentStyle);

$section->addTextBreak(2);

$section->addTitle('Le produit minimum viable', 2);

$section->addText('Le produit minimum viable comportera un acces a la page d\'accueil du site avec tous les onglets places sur un menu de navigation intuitif et ergonomique pour faire gagner un maximum de temps aux utilisateurs/visiteurs du site. La page d\'accueil sera agrementee d\'elements dynamique qui viendront enrichir la page pour un rendu visuel encore plus attrayant. Il y aura une barre de recherche pour faciliter la recherche d\'un produit precis dans nos stocks.', $contentStyle);

$section->addTextBreak();

$section->addText('Un panier d\'achat servira a y placer vos articles, le panier reagira dynamiquement en affichant le nombre de produits dedans. Un back-office uniquement visible pour notre administrateur pour gerer les demandes de formulaire, et les CRUD en matiere de gestion de stock, articles de blog, commentaires... L\'administrateur utilisera ses privileges conformement a la logique business implementee directement sur son interface utilisateur (UI).', $contentStyle);

// Nouvelle page pour le cahier de charges
$section->addPageBreak();

// CAHIER DE CHARGES FONCTIONNEL
$section->addTitle('1. Cahier de charges fonctionnel', 1);

$section->addTitle('1.1 Description des utilisateurs', 2);

$section->addText('• Visiteur : Internaute non connecte.', $contentStyle);
$section->addText('• Membre : Internaute inscrit et connecte au site.', $contentStyle);
$section->addText('• Administrateur : Utilisateur jouissant de droits avances sur les ressources et les autres utilisateurs.', $contentStyle);

$section->addTextBreak();

$section->addTitle('1.2 Exigences fonctionnelles', 2);

// F1 à F10
$section->addTitle('F1 : Creer un compte utilisateur', 3);
$section->addText('Utilisateurs : visiteur', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes :', $contentStyle);
$section->addText('• Une adresse e-mail valide.', $contentStyle);
$section->addTextBreak();
$section->addText('Description :', $contentStyle);
$section->addText('Le visiteur cree un compte utilisateur a l\'application web apres avoir introduit un email et un mot de passe.', $contentStyle);
$section->addTextBreak();

$section->addTitle('F2 : Se connecter', 3);
$section->addText('Utilisateurs : membre', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes :', $contentStyle);
$section->addText('• Avoir un compte utilisateur actif sur le site.', $contentStyle);
$section->addText('Description :', $contentStyle);
$section->addText('Le membre se connecte en introduisant son email et un mot de passe valide.', $contentStyle);
$section->addTextBreak();

$section->addTitle('F3 : Consulter son profil', 3);
$section->addText('Utilisateurs : membres', $contentStyle);
$section->addText('Importance : 2/5', $contentStyle);
$section->addText('Contraintes :', $contentStyle);
$section->addText('• L\'utilisateur doit etre connecte pour pouvoir consulter son profil', $contentStyle);
$section->addText('Description :', $contentStyle);
$section->addText('Le membre accede a sa page de profil dans laquelle il pourra consulter les donnees personnelles suivantes : nom complet ou pseudonyme, photo de profil.', $contentStyle);
$section->addTextBreak();

$section->addTitle('F4 : Modifier son profil', 3);
$section->addText('Utilisateurs : membres', $contentStyle);
$section->addText('Importance : 2/5', $contentStyle);
$section->addText('Contraintes :', $contentStyle);
$section->addText('• L\'utilisateur doit etre connecte pour pouvoir modifier son profil', $contentStyle);
$section->addText('Description :', $contentStyle);
$section->addText('L\'utilisateur membre modifie son mot de passe et sa photo de profil via l\'espace client de son profil.', $contentStyle);
$section->addTextBreak();

$section->addTitle('F5 Se desinscrire', 3);
$section->addText('Utilisateurs : membre/administrateur', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes :', $contentStyle);
$section->addText('• L\'utilisateur doit avoir un compte actif pour se desinscrire', $contentStyle);
$section->addText('Description :', $contentStyle);
$section->addText('L\'utilisateur peut se desinscrire a tout moment sur le site. L\'utilisateur peut telecharger ses informations de navigations (Historique de commandes, de locations, factures).', $contentStyle);
$section->addTextBreak();

$section->addTitle('F6 : Consulter les produits du catalogue agricole', 3);
$section->addText('Utilisateurs : membre/visiteur', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes :', $contentStyle);
$section->addText('• Aucunes', $contentStyle);
$section->addText('Description :', $contentStyle);
$section->addText('Le catalogue des produits agricole destine a la vente et location est consultable par les visiteurs et les membres connectes.', $contentStyle);
$section->addTextBreak();

$section->addTitle('F7 Ajouter au panier d\'achat', 3);
$section->addText('Utilisateurs : membres/administrateur', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes : Etre connecte en tant que membre.', $contentStyle);
$section->addText('Description :', $contentStyle);
$section->addText('Chaque produit selectionne est envoye dans un panier.', $contentStyle);
$section->addTextBreak();

$section->addTitle('F8 Modifier le panier d\'achat', 3);
$section->addText('Utilisateurs : membres', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes :', $contentStyle);
$section->addText('• Etre connecte en tant que membre.', $contentStyle);
$section->addText('Description :', $contentStyle);
$section->addText('Chaque produit selectionne dans le panier peut etre modifier (ajout/diminution de quantite et de produit).', $contentStyle);
$section->addTextBreak();

$section->addTitle('F9 Supprimer le panier d\'achat', 3);
$section->addText('Utilisateurs : membres', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes :', $contentStyle);
$section->addText('• Etre connecte en tant que membre.', $contentStyle);
$section->addText('Description :', $contentStyle);
$section->addText('Chaque produit selectionne dans le panier peut etre supprime (suppression de quantite et de produit).', $contentStyle);
$section->addTextBreak();

$section->addTitle('F10 : Payer l\'achat/location', 3);
$section->addText('Utilisateurs : membres', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes :', $contentStyle);
$section->addText('• Etre connecte en tant que membre.', $contentStyle);
$section->addText('• Posseder une carte bancaire valide.', $contentStyle);
$section->addText('Description :', $contentStyle);
$section->addText('Paiement securise au moyen d\'une API (exemple : stripe).', $contentStyle);
$section->addTextBreak();

// Continue avec F15, F32, A1 pour économiser l'espace
$section->addTitle('F15 : Louer des produits agricoles', 3);
$section->addText('Utilisateurs : membres', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes :', $contentStyle);
$section->addText('• Etre connecte en tant que membre.', $contentStyle);
$section->addText('Description :', $contentStyle);
$section->addText('Le membre connecte selectionne les produits destines a la location qui seront envoye vers le panier de location. Les produits loues auront une date de debut et une date de fin de location.', $contentStyle);
$section->addTextBreak();

$section->addTitle('F32 Passer une commande', 3);
$section->addText('Utilisateurs : membre', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes :', $contentStyle);
$section->addText('• L\'utilisateur doit etre connecte', $contentStyle);
$section->addText('• Les produits doivent etre places dans un panier d\'achat ou de location', $contentStyle);
$section->addText('Description :', $contentStyle);
$section->addText('L\'utilisateur, apres avoir mis ses produits dans le panier finalise sa commande.', $contentStyle);
$section->addTextBreak();

$section->addTitle('A1 : Ajouter des produits', 3);
$section->addText('Utilisateurs : administrateur', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes :', $contentStyle);
$section->addText('• Etre connecte au back-office de gestion.', $contentStyle);
$section->addText('Description :', $contentStyle);
$section->addText('L\'administrateur uniquement, ajoute un nouveau produit d\'achat ou de location au moyen du panel admin.', $contentStyle);
$section->addTextBreak();

// 1.3 Exigences non-fonctionnelles
$section->addTitle('1.3 Exigences non-fonctionnelles', 2);

$section->addTitle('N1 : Rendre fluide la navigation', 3);
$section->addText('Utilisateurs visiteur/membre/administrateur', $contentStyle);
$section->addText('Importance : 4/5', $contentStyle);
$section->addText('Contraintes :', $contentStyle);
$section->addText('Description :', $contentStyle);
$section->addText('Le visiteur ou le membre ne doit jamais attendre plus de 0.5sec qu\'une page du catalogue de produit se charge.', $contentStyle);
$section->addTextBreak();

$section->addTitle('N2 : Deconnexion automatique apres inactivite', 3);
$section->addText('Utilisateurs : visiteur/membre/administrateur', $contentStyle);
$section->addText('Importance : 4/5', $contentStyle);
$section->addText('Contraintes :', $contentStyle);
$section->addText('Description :', $contentStyle);
$section->addText('Si le membre reste inactif pendant un laps de temps, sa session prend fin et il est deconnecte.', $contentStyle);
$section->addTextBreak();

$section->addTitle('N3 Securiser les donnees et proteger les informations sensibles', 3);
$section->addText('Utilisateurs : visiteur/membre/administrateur', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes :', $contentStyle);
$section->addText('• Utiliser le protocole HTTPS', $contentStyle);
$section->addText('• Utiliser le hachage de mot de passe', $contentStyle);
$section->addText('• Respect des normes RPGD', $contentStyle);
$section->addText('• Protection contre les attaques XSS', $contentStyle);
$section->addText('• Protection contre les attaques CSRF', $contentStyle);
$section->addText('Description :', $contentStyle);
$section->addText('Assurer la securite des donnees sensibles (identifiants, mots de passe, informations personnelles, factures) pour tous les utilisateurs.', $contentStyle);
$section->addTextBreak();

// Nouvelle page pour description du schéma
$section->addPageBreak();

// DESCRIPTION DU SCHEMA
$section->addTitle('2. DESCRIPTION DU SCHEMA', 1);

$section->addText('FarmShop est une plateforme e-commerce specialisee dans la vente et la location de produits agricoles et alimentaires. Le systeme gere a la fois les achats traditionnels et un systeme de location unique avec gestion des cautions et penalites, developpe avec Laravel 11 LTS.', $contentStyle);

$section->addTextBreak();

$section->addTitle('RELATIONS UTILISATEURS', 2);

$section->addTitle('Relations principales', 3);
$section->addText('Un utilisateur peut creer plusieurs produits - Un produit appartient a un utilisateur [1-*]', $contentStyle);
$section->addText('Un utilisateur peut passer plusieurs commandes - Une commande appartient a un utilisateur [1-*]', $contentStyle);
$section->addText('Un utilisateur peut avoir plusieurs commandes de location - Une commande de location appartient a un utilisateur [1-*]', $contentStyle);
$section->addText('Un utilisateur peut avoir plusieurs locations actives - Une location appartient a un utilisateur [1-*]', $contentStyle);
$section->addText('Un utilisateur possede un panier d\'achat - Un panier appartient a un utilisateur [1-1]', $contentStyle);
$section->addText('Un utilisateur peut avoir plusieurs paniers de location - Un panier de location appartient a un utilisateur [1-*]', $contentStyle);

$section->addTextBreak();

$section->addTitle('TABLES PRINCIPALES IDENTIFIEES', 2);

$section->addTitle('Tables utilisateurs et authentification', 3);
$section->addText('• users (table principale des utilisateurs)', $contentStyle);
$section->addText('• roles (roles du systeme)', $contentStyle);
$section->addText('• permissions (permissions du systeme)', $contentStyle);
$section->addText('• model_has_roles (table pivot utilisateurs-roles)', $contentStyle);
$section->addText('• model_has_permissions (table pivot utilisateurs-permissions)', $contentStyle);
$section->addText('• role_has_permissions (table pivot roles-permissions)', $contentStyle);

$section->addTextBreak();

$section->addTitle('Tables produits et catalogue', 3);
$section->addText('• categories (categories de produits)', $contentStyle);
$section->addText('• products (produits)', $contentStyle);
$section->addText('• product_images (images des produits)', $contentStyle);
$section->addText('• special_offers (offres speciales)', $contentStyle);

$section->addTextBreak();

$section->addTitle('Tables panier et navigation', 3);
$section->addText('• carts (paniers d\'achat)', $contentStyle);
$section->addText('• cart_items (articles dans les paniers)', $contentStyle);
$section->addText('• cart_locations (paniers de location)', $contentStyle);
$section->addText('• cart_item_locations (articles de location dans les paniers)', $contentStyle);

// Nouvelle page pour architecture technique
$section->addPageBreak();

// ARCHITECTURE TECHNIQUE
$section->addTitle('3. ARCHITECTURE TECHNIQUE ET DESIGN SYSTEM', 1);

$section->addTitle('Framework et technologies utilisees', 2);
$section->addText('FarmShop est developpe avec Laravel 11 LTS comme framework backend et utilise Tailwind CSS comme systeme de design frontend principal. Cette combinaison assure une interface responsive, accessible et moderne. Le projet integre egalement Alpine.js pour l\'interactivite JavaScript.', $contentStyle);

$section->addTextBreak();

$section->addTitle('Nouveautes Laravel 11 LTS implementees', 2);
$section->addText('• Nouveau systeme de routing simplifie', $contentStyle);
$section->addText('• Middleware optimise pour les performances', $contentStyle);
$section->addText('• Eloquent ORM ameliore avec de meilleures relations', $contentStyle);
$section->addText('• Systeme de cache Redis integre', $contentStyle);
$section->addText('• Support natif de PHP 8.3', $contentStyle);
$section->addText('• Nouveaux helpers pour la validation', $contentStyle);

$section->addTextBreak();

// SYSTEME DE COULEURS
$section->addTitle('4. SYSTEME DE COULEURS IMPLEMENTE', 1);

$section->addText('Notre palette de couleurs est definie via la configuration Tailwind CSS dans tailwind.config.js, garantissant une coherence parfaite sur toute l\'application Laravel 11.', $contentStyle);

$section->addTextBreak();

// Tableau des couleurs
$colorTable = $section->addTable(['borderSize' => 6, 'borderColor' => '999999', 'cellMargin' => 80]);
$colorTable->addRow();
$colorTable->addCell(3000)->addText('Couleur Tailwind', $strongStyle);
$colorTable->addCell(2000)->addText('Valeur Hex', $strongStyle);
$colorTable->addCell(4000)->addText('Usage dans l\'application', $strongStyle);

$colorTable->addRow();
$colorTable->addCell(3000)->addText('farm-green-500', $contentStyle);
$colorTable->addCell(2000)->addText('#22c55e', $contentStyle);
$colorTable->addCell(4000)->addText('Brand principal, CTA, navigation active', $contentStyle);

$colorTable->addRow();
$colorTable->addCell(3000)->addText('farm-green-700', $contentStyle);
$colorTable->addCell(2000)->addText('#15803d', $contentStyle);
$colorTable->addCell(4000)->addText('Hover states, accents sombres', $contentStyle);

$colorTable->addRow();
$colorTable->addCell(3000)->addText('farm-brown-500', $contentStyle);
$colorTable->addCell(2000)->addText('#a18072', $contentStyle);
$colorTable->addCell(4000)->addText('Elements secondaires, bordures', $contentStyle);

$colorTable->addRow();
$colorTable->addCell(3000)->addText('farm-green-50', $contentStyle);
$colorTable->addCell(2000)->addText('#f0fdf4', $contentStyle);
$colorTable->addCell(4000)->addText('Arriere-plans, zones de contenu', $contentStyle);

$section->addTextBreak();

// TYPOGRAPHIE
$section->addTitle('5. TYPOGRAPHIE ET POLICES', 1);

$section->addTitle('Systeme typographique Inter optimise', 2);
$section->addText('FarmShop utilise la police Inter comme police principale, configuree dans Tailwind CSS. Ce choix garantit une lisibilite optimale et une personnalite visuelle moderne compatible avec Laravel 11.', $contentStyle);

$section->addTextBreak();

$section->addText('Justification du choix typographique :', $contentStyle);
$section->addText('• Inter : Police systeme moderne, optimisee pour les interfaces numeriques', $contentStyle);
$section->addText('• Configuration Tailwind : Integration native dans la configuration', $contentStyle);
$section->addText('• Performance : Chargement optimise avec preload', $contentStyle);
$section->addText('• Accessibilite : Respecte les standards WCAG 2.1', $contentStyle);
$section->addText('• Responsive : Tailles adaptatives selon les breakpoints', $contentStyle);

$section->addTextBreak();

// NAVIGATION
$section->addTitle('6. NAVIGATION ET STRUCTURE', 1);

$section->addTitle('Navigation Tailwind moderne', 2);
$section->addText('La navigation principale utilise Tailwind CSS avec des composants Alpine.js. Chaque element de menu utilise une iconographie moderne pour ameliorer la reconnaissance visuelle et l\'accessibilite.', $contentStyle);

$section->addTextBreak();

$section->addText('Structure de navigation implementee :', $contentStyle);
$section->addText('• Logo FarmShop : Identite visuelle en farm-green-500', $contentStyle);
$section->addText('• Accueil : Interface intuitive avec Alpine.js', $contentStyle);
$section->addText('• Produits : Grid responsive avec Tailwind', $contentStyle);
$section->addText('• Panier d\'achat : Compteur dynamique', $contentStyle);
$section->addText('• Panier location : Badge info', $contentStyle);
$section->addText('• Wishlist : Animation d\'interaction', $contentStyle);
$section->addText('• Mes commandes : Interface admin Laravel 11', $contentStyle);
$section->addText('• Blog : Systeme de commentaires integre', $contentStyle);
$section->addText('• Contact : Formulaire avec validation Laravel', $contentStyle);

$section->addTextBreak();

// COMPOSANTS UI
$section->addTitle('7. COMPOSANTS UI IMPLEMENTES', 1);

$section->addTitle('Systeme de composants Tailwind + Alpine.js', 2);
$section->addText('Les composants utilisent les classes utilitaires Tailwind CSS avec des interactions Alpine.js. Cette approche moderne ameliore les performances et la maintenabilite du code Laravel 11.', $contentStyle);

$section->addTextBreak();

$section->addText('Composants principaux implementes :', $contentStyle);
$section->addText('• Cards produits : hover:scale-105 transform transition', $contentStyle);
$section->addText('• Boutons CTA : bg-farm-green-500 hover:bg-farm-green-700', $contentStyle);
$section->addText('• Formulaires : Validation temps reel avec Alpine.js', $contentStyle);
$section->addText('• Modales : Overlay avec backdrop-blur-sm', $contentStyle);
$section->addText('• Notifications : Toast system avec Tailwind', $contentStyle);
$section->addText('• Loading states : Skeleton screens responsive', $contentStyle);

$section->addTextBreak();

// RESPONSIVE DESIGN
$section->addTitle('8. RESPONSIVE DESIGN ET ANIMATIONS', 1);

$section->addTitle('Approche mobile-first avec Tailwind', 2);
$section->addText('Le design responsive utilise le systeme de breakpoints Tailwind CSS avec des animations optimisees pour les performances. Toutes les animations utilisent transform et opacity pour eviter les reflows.', $contentStyle);

$section->addTextBreak();

$section->addText('Animations implementees avec Tailwind :', $contentStyle);
$section->addText('• hover:scale-105 : Zoom cards au survol', $contentStyle);
$section->addText('• transition-all duration-300 : Transitions fluides', $contentStyle);
$section->addText('• animate-pulse : Loading states', $contentStyle);
$section->addText('• animate-bounce : Feedback actions utilisateur', $contentStyle);
$section->addText('• hover:shadow-lg : Elevations dynamiques', $contentStyle);

$section->addTextBreak();

// FONCTIONNALITÉS SPECIFIQUES
$section->addTitle('9. FONCTIONNALITES SPECIFIQUES FARMSHOP', 1);

$section->addTitle('Dualite Achat/Location avec Laravel 11', 2);
$section->addText('L\'interface reflete la double vocation de FarmShop avec des composants visuels distincts implementes avec les nouvelles fonctionnalites Laravel 11. Les paniers d\'achat (farm-green) et de location (blue-500) utilisent des controleurs separes pour optimiser les performances.', $contentStyle);

$section->addTextBreak();

$section->addTitle('Systeme de roles avec Spatie Laravel-Permission', 2);
$section->addText('Le Panel Admin utilise le package Spatie Laravel-Permission optimise pour Laravel 11, avec des gates et policies pour controler l\'acces aux fonctionnalites administratives.', $contentStyle);

$section->addTextBreak();

// OPTIMISATIONS
$section->addTitle('10. OPTIMISATIONS ET PERFORMANCES', 1);

$section->addTitle('Optimisations Laravel 11 LTS', 2);
$section->addText('Les optimisations tirent parti des nouveautes Laravel 11 pour ameliorer les performances globales de l\'application FarmShop.', $contentStyle);

$section->addTextBreak();

$section->addText('Optimisations implementees :', $contentStyle);
$section->addText('• Vite.js : Bundling optimise pour production', $contentStyle);
$section->addText('• Eager Loading : Relations Eloquent optimisees', $contentStyle);
$section->addText('• Route Caching : Cache des routes Laravel 11', $contentStyle);
$section->addText('• View Caching : Compilation Blade optimisee', $contentStyle);
$section->addText('• Database Query Optimization : Index strategiques', $contentStyle);
$section->addText('• Redis Cache : Mise en cache des donnees frequentes', $contentStyle);
$section->addText('• Tailwind CSS Purge : Suppression des classes inutilisees', $contentStyle);

$section->addTextBreak();

// CONCLUSION
$section->addTitle('11. CONCLUSION ET EVOLUTIONS', 1);

$section->addTitle('Bilan de l\'implementation Laravel 11', 2);
$section->addText('L\'implementation actuelle de FarmShop respecte les standards modernes de developpement avec Laravel 11 LTS. L\'utilisation de Tailwind CSS garantit la compatibilite cross-browser, tandis que les nouveautes Laravel 11 offrent des performances optimales.', $contentStyle);

$section->addTextBreak();

$section->addText('Points forts de l\'implementation Laravel 11 :', $contentStyle);
$section->addText('• Architecture moderne avec Laravel 11 LTS', $contentStyle);
$section->addText('• Design system coherent base sur Tailwind CSS', $contentStyle);
$section->addText('• Performance optimisee avec Vite.js et caching', $contentStyle);
$section->addText('• Interface adaptee aux specificites metier (achat/location)', $contentStyle);
$section->addText('• Accessibilite renforcee avec ARIA et navigation clavier', $contentStyle);
$section->addText('• Securite renforcee avec les middlewares Laravel 11', $contentStyle);

$section->addTextBreak();

$section->addText('Evolutions futures possibles :', $contentStyle);
$section->addText('Le systeme actuel permet facilement l\'ajout de nouvelles fonctionnalites grace a la flexibilite de Laravel 11. L\'integration future d\'API externes (paiement, logistique) sera simplifiee par la nouvelle architecture de services.', $contentStyle);

$section->addTextBreak();

// BIBLIOGRAPHIE
$section->addTitle('12. Bibliographie', 1);

$section->addText('ALPINE.JS. S.d. Alpine.js. Site web sur INTERNET. <alpinejs.dev>. Derniere consultation : le 15/07-2025.', $contentStyle);
$section->addTextBreak();

$section->addText('LARAVEL. S.d. Laravel 11 Documentation. Site web sur INTERNET. <laravel.com/docs/11.x>. Derniere consultation : le 15/07-2025.', $contentStyle);
$section->addTextBreak();

$section->addText('TAILWIND CSS. S.d. Tailwind CSS. Site web sur INTERNET. <tailwindcss.com>. Derniere consultation : le 15/07-2025.', $contentStyle);
$section->addTextBreak();

$section->addText('VITE.JS. S.d. Vite Next Generation Frontend Tooling. Site web sur INTERNET. <vitejs.dev>. Derniere consultation : le 15/07-2025.', $contentStyle);
$section->addTextBreak();

$section->addText('SPATIE. S.d. Laravel Permission Package. Site web sur INTERNET. <spatie.be/docs/laravel-permission>. Derniere consultation : le 15/07-2025.', $contentStyle);
$section->addTextBreak();

$section->addText('INTER FONT. S.d. Inter - The typeface for interfaces. Site web sur INTERNET. <rsms.me/inter>. Derniere consultation : le 15/07-2025.', $contentStyle);
$section->addTextBreak();

$section->addText('PHP. S.d. PHP 8.3 Documentation. Site web sur INTERNET. <php.net/docs.php>. Derniere consultation : le 15/07-2025.', $contentStyle);
$section->addTextBreak();

$section->addText('REDIS. S.d. Redis Documentation. Site web sur INTERNET. <redis.io/documentation>. Derniere consultation : le 15/07-2025.', $contentStyle);

$section->addTextBreak(4);

// Footer académique
$section->addText('Document genere le 16 juillet 2025', [
    'name' => 'Inter', 'size' => 10
], ['alignment' => Jc::CENTER]);

$section->addText('MEFTAH Soufiane - Bachelier en Informatique de gestion', [
    'name' => 'Inter', 'size' => 10
], ['alignment' => Jc::CENTER]);

$section->addText('Institut des Carrieres Commerciales - Bruxelles', [
    'name' => 'Inter', 'size' => 10
], ['alignment' => Jc::CENTER]);

// Sauvegarder le document
$objWriter = IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save(__DIR__ . '/12_Rapport_Ecrit_V1_Final.docx');

echo "Rapport ecrit version 1 cree avec succes !\n";
echo "Emplacement : " . __DIR__ . "/12_Rapport_Ecrit_V1_Final.docx\n";
echo "Document academique professionnel inspire du rapport de conception graphique genere !\n";

?>
