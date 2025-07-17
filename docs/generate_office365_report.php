<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\SimpleType\Jc;

// Configuration pour Office 365 et encodage UTF-8
Settings::setOutputEscapingEnabled(true);
Settings::setCompatibility(true);

// Créer un nouveau document Word
$phpWord = new PhpWord();

// Configuration de la langue et police par défaut
$phpWord->getSettings()->setThemeFontLang(new \PhpOffice\PhpWord\Style\Language('fr-BE'));
$phpWord->setDefaultFontName('Inter');
$phpWord->setDefaultFontSize(11);

// Styles pour document académique
$phpWord->addTitleStyle(1, [
    'name' => 'Inter', 
    'size' => 18, 
    'bold' => true, 
    'color' => '000000'
]);

$phpWord->addTitleStyle(2, [
    'name' => 'Inter', 
    'size' => 16, 
    'bold' => true, 
    'color' => '000000'
]);

$phpWord->addTitleStyle(3, [
    'name' => 'Inter', 
    'size' => 14, 
    'bold' => true, 
    'color' => '000000'
]);

// Styles de contenu
$contentStyle = ['name' => 'Inter', 'size' => 11, 'lang' => 'fr-BE'];
$strongStyle = ['name' => 'Inter', 'size' => 11, 'bold' => true, 'lang' => 'fr-BE'];

// Section avec marges standards
$section = $phpWord->addSection([
    'marginTop' => 1440,
    'marginBottom' => 1440,
    'marginLeft' => 1440,
    'marginRight' => 1440
]);

// PAGE DE GARDE ACADEMIQUE
$section->addText('COMMUNAUTE FRANCAISE DE BELGIQUE', [
    'name' => 'Inter', 'size' => 14, 'bold' => true, 'lang' => 'fr-BE'
], ['alignment' => Jc::CENTER]);

$section->addText('Institut des Carrieres Commerciales', [
    'name' => 'Inter', 'size' => 13, 'bold' => true, 'lang' => 'fr-BE'
], ['alignment' => Jc::CENTER]);

$section->addText('Ville de Bruxelles', [
    'name' => 'Inter', 'size' => 12, 'lang' => 'fr-BE'
], ['alignment' => Jc::CENTER]);

$section->addText('Rue de la Fontaine 4 - 1000 BRUXELLES', [
    'name' => 'Inter', 'size' => 11, 'lang' => 'fr-BE'
], ['alignment' => Jc::CENTER]);

$section->addTextBreak(8);

$section->addText('LIVRABLE 12', [
    'name' => 'Inter', 'size' => 20, 'bold' => true, 'lang' => 'fr-BE'
], ['alignment' => Jc::CENTER]);

$section->addText('RAPPORT ECRIT VERSION 1', [
    'name' => 'Inter', 'size' => 18, 'bold' => true, 'lang' => 'fr-BE'
], ['alignment' => Jc::CENTER]);

$section->addTextBreak(3);

$section->addText('Epreuve integree realisee en vue de l\'obtention du titre de', [
    'name' => 'Inter', 'size' => 11, 'italic' => true, 'lang' => 'fr-BE'
], ['alignment' => Jc::CENTER]);

$section->addText('Bachelier en Informatique de gestion', [
    'name' => 'Inter', 'size' => 12, 'bold' => true, 'lang' => 'fr-BE'
], ['alignment' => Jc::CENTER]);

$section->addText('Orientation developpement d\'applications', [
    'name' => 'Inter', 'size' => 11, 'lang' => 'fr-BE'
], ['alignment' => Jc::CENTER]);

$section->addTextBreak(5);

$section->addText('MEFTAH Soufiane', [
    'name' => 'Inter', 'size' => 16, 'bold' => true, 'lang' => 'fr-BE'
], ['alignment' => Jc::CENTER]);

$section->addText('Annee academique 2024-2025', [
    'name' => 'Inter', 'size' => 12, 'lang' => 'fr-BE'
], ['alignment' => Jc::CENTER]);

// Nouvelle page
$section->addPageBreak();

// TABLE DES MATIERES
$section->addTitle('Table des matieres', 1);
$section->addTextBreak();

$section->addText('L\'objectif general', $contentStyle);
$section->addText('L\'analyse fonctionnelle', $contentStyle);
$section->addText('Le produit minimum viable', $contentStyle);
$section->addText('1. Cahier de charges fonctionnel', $contentStyle);
$section->addText('1.1 Description des utilisateurs', $contentStyle);
$section->addText('1.2 Exigences fonctionnelles', $contentStyle);
$section->addText('F1 : Creer un compte utilisateur', $contentStyle);
$section->addText('F2 : Se connecter', $contentStyle);
$section->addText('F3 : Consulter son profil', $contentStyle);
$section->addText('F4 : Modifier son profil', $contentStyle);
$section->addText('F5 : Se desinscrire', $contentStyle);
$section->addText('F6 : Consulter les produits du catalogue agricole', $contentStyle);
$section->addText('F7 : Ajouter au panier d\'achat', $contentStyle);
$section->addText('F8 : Modifier le panier d\'achat', $contentStyle);
$section->addText('F9 : Supprimer le panier d\'achat', $contentStyle);
$section->addText('F10 : Payer l\'achat/location', $contentStyle);
$section->addText('F11 : Filtrer les produits par mots-cles', $contentStyle);
$section->addText('F12 : Filtrer les produits par categorie', $contentStyle);
$section->addText('F13 : Filtrer les produits par prix', $contentStyle);
$section->addText('F14 : Selectionner des offres speciales', $contentStyle);
$section->addText('F15 : Louer des produits agricoles', $contentStyle);
$section->addText('F16 : Ajouter au panier de location', $contentStyle);
$section->addText('F17 : Modifier le panier de location', $contentStyle);
$section->addText('F18 : Supprimer le panier de location', $contentStyle);
$section->addText('F19 : Consulter des articles de blog', $contentStyle);
$section->addText('F20 : Commenter des articles de blog', $contentStyle);
$section->addText('F21 : Signaler les commentaires', $contentStyle);
$section->addText('F22 : Recherche un blog par mots-cles', $contentStyle);
$section->addText('F23 : Filtrer le blog par categorie', $contentStyle);
$section->addText('F24 : Filtrer le blog par ordre alphabetique chronologique', $contentStyle);
$section->addText('F25 : Consulter les factures', $contentStyle);
$section->addText('F26 : Telecharger les factures format PDF', $contentStyle);
$section->addText('F27 : S\'abonner a la newsletter', $contentStyle);
$section->addText('F28 : Se desabonner de la newsletter', $contentStyle);
$section->addText('F29 : Contacter l\'administrateur', $contentStyle);
$section->addText('F30 : Changer la langue du site', $contentStyle);
$section->addText('F31 : Ajouter un produit a une WishList', $contentStyle);
$section->addText('F32 : Passer une commande', $contentStyle);
$section->addText('F33 : Annuler une commande', $contentStyle);
$section->addText('A1 : Ajouter des produits', $contentStyle);
$section->addText('A2 : Modifier des produits', $contentStyle);
$section->addText('A3 : Supprimer des produits', $contentStyle);
$section->addText('A4 : Ajouter des articles de blog', $contentStyle);
$section->addText('A5 : Modifier des articles de blog', $contentStyle);
$section->addText('A6 : Supprimer des articles de blog', $contentStyle);
$section->addText('A7 : Gerer les signalements des commentaires sur le blog', $contentStyle);
$section->addText('A8 : Ajouter des utilisateurs', $contentStyle);
$section->addText('A9 : Modifier des utilisateurs', $contentStyle);
$section->addText('A10 : Supprimer des utilisateurs', $contentStyle);
$section->addText('1.3 Exigences non-fonctionnelles', $contentStyle);
$section->addText('N1 : Rendre fluide la navigation', $contentStyle);
$section->addText('N2 : Deconnexion automatique apres inactivite', $contentStyle);
$section->addText('N3 : Securiser les donnees et proteger les informations sensibles', $contentStyle);
$section->addText('N4 : Penaliser les retards', $contentStyle);
$section->addText('N5 : Penaliser les degats materiels', $contentStyle);
$section->addText('N6 : Configurer les cookies avec le back office', $contentStyle);
$section->addText('N7 : Accepter les cookies', $contentStyle);
$section->addText('N8 : Refuser les cookies', $contentStyle);
$section->addText('N9 : Parametrer les cookies', $contentStyle);
$section->addText('2. DESCRIPTION DU SCHEMA', $contentStyle);
$section->addText('3. ARCHITECTURE TECHNIQUE ET DESIGN SYSTEM', $contentStyle);
$section->addText('4. SYSTEME DE COULEURS IMPLEMENTE', $contentStyle);
$section->addText('5. TYPOGRAPHIE ET POLICES', $contentStyle);
$section->addText('6. NAVIGATION ET STRUCTURE', $contentStyle);
$section->addText('7. COMPOSANTS UI IMPLEMENTES', $contentStyle);
$section->addText('8. RESPONSIVE DESIGN ET ANIMATIONS', $contentStyle);
$section->addText('9. FONCTIONNALITES SPECIFIQUES FARMSHOP', $contentStyle);
$section->addText('10. OPTIMISATIONS ET PERFORMANCES', $contentStyle);
$section->addText('11. CONCLUSION ET EVOLUTIONS', $contentStyle);
$section->addText('12. Bibliographie', $contentStyle);

// Nouvelle page
$section->addPageBreak();

// INTRODUCTION
$section->addTitle('Introduction', 1);
$section->addTextBreak();

$section->addText('Avec le temps la technologie informatique n\'a cesse d\'evoluer, c\'est pourquoi toute entreprise moderne doit avoir en son sein un site web, une application pour satisfaire la demande toujours croissante des consommateurs. Mais cela ne suffit pas, avoir seulement une vitrine virtuelle avec des produits en stock n\'est pas un argument convaincant pour fideliser les clients d\'une entreprise, pour cela, elle doit faire preuve de creativite pour mettre en valeur ses produits.', $contentStyle);

$section->addTextBreak();

$section->addText('Bien heureusement, le monde du developpement le permet grace a des outils que nous allons utiliser tous le long de ce projet e-commerce, ainsi chaque etape du projet sera consignee dans ce rapport ecrit.', $contentStyle);

$section->addTextBreak(2);

$section->addTitle('L\'objectif general', 2);
$section->addTextBreak();

$section->addText('L\'objectif general est de creer une plateforme innovante et intuitive pour que chaque utilisateur puisse naviguer rapidement et de maniere ergonomique. Chaque fonctionnalite du site a ete etudie pour fournir aux utilisateurs non connecte ou connecte la meilleure experience possible.', $contentStyle);

$section->addTextBreak();

$section->addText('Le public cible vise toutes les personnes issues du monde agricole ou non. Toutes les rubriques du site seront concues pour que chaque visiteur puisse comprendre sans pour autant etre familier du milieu de l\'agriculture.', $contentStyle);

$section->addTextBreak(2);

$section->addTitle('L\'analyse fonctionnelle', 2);
$section->addTextBreak();

$section->addText('L\'objectif fonctionnelle de notre application sera de continuellement ameliorer l\'ergonomie de notre site, c\'est-a-dire a la fois sur ecran PC et sur tablette. Nous simplifierons les processus de commande et les methodes de paiement en faisant appel a une API externe par exemple pour finaliser l\'achat et la location.', $contentStyle);

$section->addTextBreak();

$section->addText('Nous tiendrons informe quotidiennement nos utilisateurs avec des articles qui paraitront sur un blog qu\'ils pourront commenter, cette strategie sera d\'ailleurs efficace pour le referencement de notre site sur les moteurs de recherche. Le site sera enfin bilingue Anglais-Francais pour etendre la zone de chalandise sur tous le continent europeen.', $contentStyle);

$section->addTextBreak();

$section->addText('L\'analyse fonctionnelle est primordiale pour etablir une strategie qui nous demarquera de la concurrence deja bien etabli. En selectionnant avec soin nos produits, nous serons dans la capacite de vous donner un maximum d\'information sur la provenance et la qualite de ceux-ci.', $contentStyle);

$section->addTextBreak(2);

$section->addTitle('Le produit minimum viable', 2);
$section->addTextBreak();

$section->addText('Le produit minimum viable comportera un acces a la page d\'accueil du site avec tous les onglets places sur un menu de navigation intuitif et ergonomique pour faire gagner un maximum de temps aux utilisateurs/visiteurs du site.', $contentStyle);

$section->addTextBreak();

$section->addText('La page d\'accueil sera agrementee d\'elements dynamique qui viendront enrichir la page pour un rendu visuel encore plus attrayant. Il y aura une barre de recherche pour faciliter la recherche d\'un produit precis dans nos stocks.', $contentStyle);

$section->addTextBreak();

$section->addText('Un panier d\'achat servira a y placer vos articles, le panier reagira dynamiquement en affichant le nombre de produits dedans. Un back-office uniquement visible pour notre administrateur pour gerer les demandes de formulaire, et les CRUD en matiere de gestion de stock, articles de blog, commentaires.', $contentStyle);

// Nouvelle page
$section->addPageBreak();

// CAHIER DE CHARGES
$section->addTitle('1. Cahier de charges fonctionnel', 1);
$section->addTextBreak();

$section->addTitle('1.1 Description des utilisateurs', 2);
$section->addTextBreak();

$section->addText('Visiteur : Internaute non connecte.', $contentStyle);
$section->addText('Membre : Internaute inscrit et connecte au site.', $contentStyle);
$section->addText('Administrateur : Utilisateur jouissant de droits avances sur les ressources et les autres utilisateurs.', $contentStyle);

$section->addTextBreak(2);

$section->addTitle('1.2 Exigences fonctionnelles', 2);
$section->addTextBreak();

// F1
$section->addTitle('F1 : Creer un compte utilisateur', 3);
$section->addText('Utilisateurs : visiteur', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('Une adresse e-mail valide.', $contentStyle);
$section->addTextBreak();
$section->addText('Description :', $strongStyle);
$section->addText('Le visiteur creer un compte utilisateur a l\'application web apres avoir introduit un email et un mot de passe.', $contentStyle);
$section->addTextBreak();

// F2
$section->addTitle('F2 : Se connecter', 3);
$section->addText('Utilisateurs : membre', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('Avoir un compte utilisateur actif sur le site.', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('Le membre se connecte en introduisant son email et un mot de passe valide.', $contentStyle);
$section->addTextBreak();

// F3
$section->addTitle('F3 : Consulter son profil', 3);
$section->addText('Utilisateurs : membres', $contentStyle);
$section->addText('Importance : 2/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('L\'utilisateur doit etre connecte pour pouvoir consulter son profil', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('Le membre accede a sa page de profil dans laquelle il pourra consulter les donnees personnelles suivantes : nom complet ou pseudonyme, photo de profil.', $contentStyle);
$section->addTextBreak();

// F4
$section->addTitle('F4 : Modifier son profil', 3);
$section->addText('Utilisateurs : membres', $contentStyle);
$section->addText('Importance : 2/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('L\'utilisateur doit etre connecte pour pouvoir modifier son profil', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('L\'utilisateur membre modifie son mot de passe et sa photo de profil via l\'espace client de son profil.', $contentStyle);
$section->addTextBreak();

// F5
$section->addTitle('F5 : Se desinscrire', 3);
$section->addText('Utilisateurs : membre/administrateur', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('L\'utilisateur doit avoir un compte actif pour se desinscrire', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('L\'utilisateur peut se desinscrire a tout moment sur le site. L\'utilisateur peut telecharger ses informations de navigations (Historique de commandes, de locations, factures).', $contentStyle);
$section->addTextBreak();

// F6
$section->addTitle('F6 : Consulter les produits du catalogue agricole', 3);
$section->addText('Utilisateurs : membre/visiteur', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('Aucunes', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('Le catalogue des produits agricole destine a la vente et location est consultable par les visiteurs et les membres connectes.', $contentStyle);
$section->addTextBreak();

// F7
$section->addTitle('F7 : Ajouter au panier d\'achat', 3);
$section->addText('Utilisateurs : membres/administrateur', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('Etre connecte en tant que membre.', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('Chaque produit selectionne est envoye dans un panier.', $contentStyle);
$section->addTextBreak();

// F8
$section->addTitle('F8 : Modifier le panier d\'achat', 3);
$section->addText('Utilisateurs : membres', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('Etre connecte en tant que membre.', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('Chaque produit selectionne dans le panier peut etre modifier (ajout/diminution de quantite et de produit.', $contentStyle);
$section->addTextBreak();

// F9
$section->addTitle('F9 : Supprimer le panier d\'achat', 3);
$section->addText('Utilisateurs : membres', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('Etre connecte en tant que membre.', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('Chaque produit selectionne dans le panier peut etre supprime (suppression de quantite et de produit).', $contentStyle);
$section->addTextBreak();

// F10
$section->addTitle('F10 : Payer l\'achat/location', 3);
$section->addText('Utilisateurs : membres', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('Etre connecte en tant que membre.', $contentStyle);
$section->addText('Posseder une carte bancaire valide.', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('Paiement securise au moyen d\'une API (exemple : stripe).', $contentStyle);
$section->addTextBreak();

// F11
$section->addTitle('F11 : Filtrer les produits par mots-cles', 3);
$section->addText('Utilisateurs : membres', $contentStyle);
$section->addText('Importance : 2/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('Aucunes', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('Chaque produit peut etre filtre par mots-cles dans une barre de recherche.', $contentStyle);
$section->addTextBreak();

// F12
$section->addTitle('F12 : Filtrer les produits par categorie', 3);
$section->addText('Utilisateurs : membres', $contentStyle);
$section->addText('Importance : 2/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('Aucunes', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('Chaque produit peut etre filtre par categorie dans une barre de recherche.', $contentStyle);
$section->addTextBreak();

// F13
$section->addTitle('F13 : Filtrer les produits par prix', 3);
$section->addText('Utilisateurs : membres', $contentStyle);
$section->addText('Importance : 2/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('Aucunes', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('Chaque produit peut etre filtre par prix croissant et decroissant dans une barre de recherche.', $contentStyle);
$section->addTextBreak();

// F14
$section->addTitle('F14 : Selectionner des offres speciales', 3);
$section->addText('Utilisateurs : membre', $contentStyle);
$section->addText('Importance : 3/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('L\'utilisateur doit etre connecte', $contentStyle);
$section->addText('Le produit doit etre eligible au statut offre speciale.', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('L\'utilisateur peut beneficier d\'une offre speciale sur un produit si il le selectionne dans la liste des produits.', $contentStyle);
$section->addTextBreak();

// F15
$section->addTitle('F15 : Louer des produits agricoles', 3);
$section->addText('Utilisateurs : membres', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('Etre connecte en tant que membre.', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('Le membre connecte selectionne les produits destines a la location qui seront envoye vers le panier de location. Les produits loues auront une date de debut et une date de fin de location.', $contentStyle);
$section->addTextBreak();

// F16
$section->addTitle('F16 : Ajouter au panier de location', 3);
$section->addText('Utilisateurs : membres', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('Etre connecte en tant que membre.', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('Le membre connecte selectionne les produits destines a la location qui seront envoye vers le panier de location. Les produits loues auront une date de debut et une date de fin de location.', $contentStyle);
$section->addTextBreak();

// F17
$section->addTitle('F17 : Modifier le panier de location', 3);
$section->addText('Utilisateurs : membres', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('Etre connecte en tant que membre.', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('Chaque produit selectionne dans le panier peut etre modifier (ajout/diminution de quantite et de produit.', $contentStyle);
$section->addTextBreak();

// F18
$section->addTitle('F18 : Supprimer le panier de location', 3);
$section->addText('Utilisateurs : membres', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('Etre connecte en tant que membre.', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('Chaque produit selectionne dans le panier peut etre supprime (suppression de quantite et de produit).', $contentStyle);
$section->addTextBreak();

// F19
$section->addTitle('F19 : Consulter des articles de blog', 3);
$section->addText('Utilisateurs : visiteur/membre/administrateur', $contentStyle);
$section->addText('Importance : 2/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('Aucunes', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('L\'utilisateur membre et non membre consulte un article de blog publie sur le site. L\'article peut etre partage sur les reseaux sociaux (Whatsapp, instagram Twitter).', $contentStyle);
$section->addTextBreak();

// F20
$section->addTitle('F20 : Commenter des articles de blog', 3);
$section->addText('Utilisateurs : visiteur/membre/Administrateur', $contentStyle);
$section->addText('Importance : 2/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('Etre membre.', $contentStyle);
$section->addText('Etre connecte en tant que membre.', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('L\'utilisateur connecte peut laisser un commentaire sur un article de blog. L\'article peut etre partage sur les reseaux sociaux (Whatsapp, instagram Twitter).', $contentStyle);
$section->addTextBreak();

// F21
$section->addTitle('F21 : Signaler les commentaires', 3);
$section->addText('Utilisateurs : visiteur/membre/Administrateur', $contentStyle);
$section->addText('Importance : 2/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('Etre membre.', $contentStyle);
$section->addText('Etre connecte en tant que membre.', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('L\'utilisateur connecte peut signaler les commentaires au moyen d\'un bouton signalement.', $contentStyle);
$section->addTextBreak();

// F22
$section->addTitle('F22 : Recherche un blog par mots-cles', 3);
$section->addText('Utilisateurs : visiteur/membre/Administrateur', $contentStyle);
$section->addText('Importance : 2/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('Aucunes contraintes.', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('Le visiteur ou l\'utilisateur peut faire une recherche par mots-cles sur la page qui liste les blogs.', $contentStyle);
$section->addTextBreak();

// F23
$section->addTitle('F23 : Filtrer le blog par categorie', 3);
$section->addText('Utilisateurs : visiteur/membre/Administrateur', $contentStyle);
$section->addText('Importance : 2/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('Aucunes contraintes.', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('Le visiteur ou l\'utilisateur peut filtrer par categorie sur la page qui liste les blogs.', $contentStyle);
$section->addTextBreak();

// F24
$section->addTitle('F24 : Filtrer le blog par ordre alphabetique chronologique', 3);
$section->addText('Utilisateurs : visiteur/membre/Administrateur', $contentStyle);
$section->addText('Importance : 2/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('Aucunes contraintes.', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('Le visiteur ou l\'utilisateur peut filtrer par ordre alphabetique ou chronologique sur la page qui liste les blogs.', $contentStyle);
$section->addTextBreak();

// F25
$section->addTitle('F25 : Consulter les factures', 3);
$section->addText('Utilisateurs : membre/administrateur', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('L\'utilisateur doit etre connecte sur son espace profil.', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('L\'utilisateur connecte a acces a ses factures et documents a tout moment.', $contentStyle);
$section->addTextBreak();

// F26
$section->addTitle('F26 : Telecharger les factures format PDF', 3);
$section->addText('Utilisateurs : membre', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('L\'utilisateur doit etre connecte sur son espace utilisateur.', $contentStyle);
$section->addText('L\'utilisateur doit avoir au moins effectue un achat ou une location sur le site.', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('L\'utilisateur connecte telecharges les factures via un bouton.', $contentStyle);
$section->addTextBreak();

// F27
$section->addTitle('F27 : S\'abonner a la newsletter', 3);
$section->addText('Utilisateurs : membre', $contentStyle);
$section->addText('Importance : 2/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('L\'utilisateur doit etre connecte.', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('L\'utilisateur s\'abonne a la newsletter du site et recoit des annonces et des nouvelles dans sa boite a reception.', $contentStyle);
$section->addTextBreak();

// F28
$section->addTitle('F28 : Se desabonner de la newsletter', 3);
$section->addText('Utilisateurs : membre', $contentStyle);
$section->addText('Importance : 2/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('L\'utilisateur doit etre connecte.', $contentStyle);
$section->addText('L\'utilisateur doit etre abonne a la newsletter', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('L\'utilisateur se desabonne de la newsletter du site et ne recoit plus les annonces et des nouvelles dans sa boite a reception.', $contentStyle);
$section->addTextBreak();

// F29
$section->addTitle('F29 : Contacter l\'administrateur', 3);
$section->addText('Utilisateurs : visiteur/membre', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('Selectionner la categorie du formulaire de contact.', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('L\'utilisateur connecte ou non connecte contacte l\'administrateur via un formulaire en selectionnant la categorie adequate (produits, blog, autres).', $contentStyle);
$section->addTextBreak();

// F30
$section->addTitle('F30 : Changer la langue du site', 3);
$section->addText('Utilisateurs : visiteur/membre/administrateur', $contentStyle);
$section->addText('Importance : 2/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('Aucunes.', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('L\'utilisateur connecte ou non connecte peut changer la langue du site a tout moment. Les langues disponibles sont : Francais ou Anglais.', $contentStyle);
$section->addTextBreak();

// F31
$section->addTitle('F31 : Ajouter un produit a une WishList', 3);
$section->addText('Utilisateurs : membre', $contentStyle);
$section->addText('Importance : 2/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('L\'utilisateur doit etre connecte pour sauvegarder un produit dans une WhisList', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('L\'utilisateur connecte selectionne un produit qu\'il aimerait acheter plus tard et le place dans une liste de souhait.', $contentStyle);
$section->addTextBreak();

// F32
$section->addTitle('F32 : Passer une commande', 3);
$section->addText('Utilisateurs : membre', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('L\'utilisateur doit etre connecte', $contentStyle);
$section->addText('Les produits doivent etre place dans un panier d\'achat ou de location', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('L\'utilisateur, apres avoir mis ses produits dans le panier finalise sa commande.', $contentStyle);
$section->addTextBreak();

// F33
$section->addTitle('F33 : Annuler une commande', 3);
$section->addText('Utilisateurs : membre', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('L\'utilisateur doit etre connecte', $contentStyle);
$section->addText('Les produits doivent etre place dans un panier d\'achat ou de location', $contentStyle);
$section->addText('La commande ne doit pas avoir le statut expedie', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('Si la commande n\'est pas encore expediee, l\'utilisateur peut annuler sa commande.', $contentStyle);
$section->addTextBreak();

// A1
$section->addTitle('A1 : Ajouter des produits', 3);
$section->addText('Utilisateurs : administrateur', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('Etre connecte au back-office de gestion.', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('L\'administrateur uniquement, ajoute un nouveau produit d\'achat ou de location au moyen du panel admin.', $contentStyle);
$section->addTextBreak();

// A2
$section->addTitle('A2 : Modifier des produits', 3);
$section->addText('Utilisateurs : administrateur', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('Etre connecte au back-office de gestion.', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('L\'administrateur uniquement, modifie un produit au moyen du panel admin.', $contentStyle);
$section->addTextBreak();

// A3
$section->addTitle('A3 : Supprimer des produits', 3);
$section->addText('Utilisateurs : administrateur', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('Etre connecte au back-office de gestion.', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('L\'administrateur uniquement, supprime un produit au moyen du panel admin.', $contentStyle);
$section->addTextBreak();

// A4
$section->addTitle('A4 : Ajouter des articles de blog', 3);
$section->addText('Utilisateurs : administrateur', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('Etre connecte au back-office de gestion.', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('L\'administrateur uniquement, ajoute un nouvel article de blog au moyen du panel admin.', $contentStyle);
$section->addTextBreak();

// A5
$section->addTitle('A5 : Modifier des articles de blog', 3);
$section->addText('Utilisateurs : administrateur', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('Etre connecte au back-office de gestion.', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('L\'administrateur uniquement, modifie un article de blog au moyen du panel admin.', $contentStyle);
$section->addTextBreak();

// A6
$section->addTitle('A6 : Supprimer des articles de blog', 3);
$section->addText('Utilisateurs : administrateur', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('Etre connecte au back-office de gestion.', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('L\'administrateur uniquement, supprime un article de blog au moyen du panel admin.', $contentStyle);
$section->addTextBreak();

// A7
$section->addTitle('A7 : Gerer les signalements des commentaires sur le blog', 3);
$section->addText('Utilisateurs : administrateur', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('Etre connecte au back-office de gestion.', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('L\'administrateur uniquement, gere les signalements des commentaires sur le blog. Les commentaires signales traites seront supprimes.', $contentStyle);
$section->addTextBreak();

// A8
$section->addTitle('A8 : Ajouter des utilisateurs', 3);
$section->addText('Utilisateurs : administrateur', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('Etre connecte au back-office de gestion.', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('L\'administrateur uniquement, ajoute un nouvel utilisateur au moyen du panel admin.', $contentStyle);
$section->addTextBreak();

// A9
$section->addTitle('A9 : Modifier des utilisateurs', 3);
$section->addText('Utilisateurs : administrateur', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('Etre connecte au back-office de gestion.', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('L\'administrateur uniquement, modifie le role d\'un utilisateur (Admin ou User).', $contentStyle);
$section->addTextBreak();

// A10
$section->addTitle('A10 : Supprimer des utilisateurs', 3);
$section->addText('Utilisateurs : administrateur', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('Etre connecte au back-office de gestion.', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('L\'administrateur uniquement, supprime un utilisateur (soft delete) au moyen du panel admin. L\'admin peut reactiver son compte a tout moment.', $contentStyle);
$section->addTextBreak();

$section->addTitle('1.3 Exigences non-fonctionnelles', 2);
$section->addTextBreak();

// N1
$section->addTitle('N1 : Rendre fluide la navigation', 3);
$section->addText('Utilisateurs : visiteur/membre/administrateur', $contentStyle);
$section->addText('Importance : 4/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('Description :', $strongStyle);
$section->addText('Le visiteur ou le membre ne doit jamais attendre plus de 0.5sec qu\'une page du catalogue de produit se charge.', $contentStyle);
$section->addTextBreak();

// N2
$section->addTitle('N2 : Deconnexion automatique apres inactivite', 3);
$section->addText('Utilisateurs : visiteur/membre/administrateur', $contentStyle);
$section->addText('Importance : 4/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('Description :', $strongStyle);
$section->addText('Si le membre reste inactif pendant un laps de temps, sa session prend fin et il est deconnecte.', $contentStyle);
$section->addTextBreak();

// N3
$section->addTitle('N3 : Securiser les donnees et proteger les informations sensibles', 3);
$section->addText('Utilisateurs : visiteur/membre/administrateur', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('Utiliser le protocole HTTPS', $contentStyle);
$section->addText('Utiliser le hachage de mot de passe', $contentStyle);
$section->addText('Respect des normes RPGD', $contentStyle);
$section->addText('Protection contre les attaques XSS', $contentStyle);
$section->addText('Protection contre les attaques CSRF', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('Assurer la securite des donnees sensibles (identifiants, mots de passe, informations personnelles, factures) pour tous les utilisateurs.', $contentStyle);
$section->addTextBreak();

// N4
$section->addTitle('N4 : Penaliser les retards', 3);
$section->addText('Utilisateurs : membre', $contentStyle);
$section->addText('Importance : 2/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('L\'utilisateur doit avoir louer un produit pour une duree determinee.', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('Les produits loues par l\'utilisateur connecte sont de type outillage et motorise. Si l\'utilisateur ne remet pas dans les delais le produit de location, une penalite lui sera attribue via une amende dans sa boite de reception.', $contentStyle);
$section->addTextBreak();

// N5
$section->addTitle('N5 : Penaliser les degats materiels', 3);
$section->addText('Utilisateurs : membre', $contentStyle);
$section->addText('Importance : 2/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('L\'utilisateur doit avoir louer un produit pour une duree determinee.', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('Les produits loues par l\'utilisateur connecte sont de type outillage et motorise. Si l\'utilisateur remet le produit de location en mauvaise etat, une amende proportionnelle au degat sera envoyee dans sa boite a reception.', $contentStyle);
$section->addTextBreak();

// N6
$section->addTitle('N6 : Configurer les cookies avec le back office', 3);
$section->addText('Utilisateurs : administrateur', $contentStyle);
$section->addText('Importance : 4/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('Connexion en tant qu\'administrateur pour operer la configuration.', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('L\'administrateur s\'authentifie et configure les parametres de cookies en back office.', $contentStyle);
$section->addTextBreak();

// N7
$section->addTitle('N7 : Accepter les cookies', 3);
$section->addText('Utilisateurs : visiteur/membre', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('Enregistrement des actions de l\'utilisateur pour une duree. L\'affichage de la banderole doit etre affiche des la visite du site.', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('Le visiteur accepte l\'entirete des cookies du site, cela inclus les cookies essentiels, non essentiels et publicitaires.', $contentStyle);
$section->addTextBreak();

// N8
$section->addTitle('N8 : Refuser les cookies', 3);
$section->addText('Utilisateurs : visiteur/membre', $contentStyle);
$section->addText('Importance : 5/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('Les cookies du site ne doivent pas etre sauvegardes dans le navigateur de l\'utilisateur.', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('L\'utilisateur refuse les cookies a l\'acception des cookies essentiels au fonctionnement du site.', $contentStyle);
$section->addTextBreak();

// N9
$section->addTitle('N9 : Parametrer les cookies', 3);
$section->addText('Utilisateurs : visiteur/membre/administrateur', $contentStyle);
$section->addText('Importance : 4/5', $contentStyle);
$section->addText('Contraintes :', $strongStyle);
$section->addText('Un lien de modification des cookies doit apparaitre dans la banderole sur tous les pages.', $contentStyle);
$section->addText('Description :', $strongStyle);
$section->addText('L\'utilisateur choisi ses preferences de cookies sur toutes les pages qu\'il consulte.', $contentStyle);
$section->addTextBreak();

// Nouvelle page
$section->addPageBreak();

// DESCRIPTION DU SCHEMA
$section->addTitle('2. DESCRIPTION DU SCHEMA', 1);
$section->addTextBreak();

$section->addText('FarmShop est une plateforme e-commerce specialisee dans la vente et la location de produits agricoles et alimentaires. Le systeme gere a la fois les achats traditionnels et un systeme de location unique avec gestion des cautions et penalites, developpe avec Laravel 11 LTS.', $contentStyle);

$section->addTextBreak();

$section->addTitle('RELATIONS UTILISATEURS', 2);
$section->addTextBreak();

$section->addTitle('Relations principales', 3);
$section->addText('Un utilisateur peut creer plusieurs produits - Un produit appartient a un utilisateur [1-*]', $contentStyle);
$section->addText('Un utilisateur peut passer plusieurs commandes - Une commande appartient a un utilisateur [1-*]', $contentStyle);
$section->addText('Un utilisateur peut avoir plusieurs commandes de location - Une commande de location appartient a un utilisateur [1-*]', $contentStyle);
$section->addText('Un utilisateur possede un panier d\'achat - Un panier appartient a un utilisateur [1-1]', $contentStyle);

$section->addTextBreak();

$section->addTitle('TABLES PRINCIPALES IDENTIFIEES', 2);
$section->addTextBreak();

$section->addTitle('Tables utilisateurs et authentification', 3);
$section->addText('users (table principale des utilisateurs)', $contentStyle);
$section->addText('roles (roles du systeme)', $contentStyle);
$section->addText('permissions (permissions du systeme)', $contentStyle);
$section->addText('model_has_roles (table pivot utilisateurs-roles)', $contentStyle);

$section->addTextBreak();

$section->addTitle('Tables produits et catalogue', 3);
$section->addText('categories (categories de produits)', $contentStyle);
$section->addText('products (produits)', $contentStyle);
$section->addText('product_images (images des produits)', $contentStyle);
$section->addText('special_offers (offres speciales)', $contentStyle);

// Nouvelle page
$section->addPageBreak();

// ARCHITECTURE TECHNIQUE
$section->addTitle('3. ARCHITECTURE TECHNIQUE ET DESIGN SYSTEM', 1);
$section->addTextBreak();

$section->addTitle('Framework et technologies utilisees', 2);
$section->addText('FarmShop est developpe avec Laravel 11 LTS comme framework backend et utilise Tailwind CSS comme systeme de design frontend principal. Cette combinaison assure une interface responsive, accessible et moderne. Le projet integre egalement Alpine.js pour l\'interactivite JavaScript.', $contentStyle);

$section->addTextBreak();

$section->addTitle('Nouveautes Laravel 11 LTS implementees', 2);
$section->addText('Nouveau systeme de routing simplifie', $contentStyle);
$section->addText('Middleware optimise pour les performances', $contentStyle);
$section->addText('Eloquent ORM ameliore avec de meilleures relations', $contentStyle);
$section->addText('Systeme de cache Redis integre', $contentStyle);
$section->addText('Support natif de PHP 8.3', $contentStyle);
$section->addText('Nouveaux helpers pour la validation', $contentStyle);

$section->addTextBreak();

// SYSTEME DE COULEURS
$section->addTitle('4. SYSTEME DE COULEURS IMPLEMENTE', 1);
$section->addTextBreak();

$section->addText('Notre palette de couleurs est definie via la configuration Tailwind CSS dans tailwind.config.js, garantissant une coherence parfaite sur toute l\'application Laravel 11.', $contentStyle);

$section->addTextBreak();

// Tableau simple des couleurs
$colorTable = $section->addTable(['borderSize' => 6, 'borderColor' => '999999']);
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

$section->addTextBreak();

// TYPOGRAPHIE
$section->addTitle('5. TYPOGRAPHIE ET POLICES', 1);
$section->addTextBreak();

$section->addTitle('Systeme typographique Inter optimise', 2);
$section->addText('FarmShop utilise la police Inter comme police principale, configuree dans Tailwind CSS. Ce choix garantit une lisibilite optimale et une personnalite visuelle moderne compatible avec Laravel 11.', $contentStyle);

$section->addTextBreak();

$section->addText('Justification du choix typographique :', $strongStyle);
$section->addText('Inter : Police systeme moderne, optimisee pour les interfaces numeriques', $contentStyle);
$section->addText('Configuration Tailwind : Integration native dans la configuration', $contentStyle);
$section->addText('Performance : Chargement optimise avec preload', $contentStyle);
$section->addText('Accessibilite : Respecte les standards WCAG 2.1', $contentStyle);

$section->addTextBreak();

// NAVIGATION
$section->addTitle('6. NAVIGATION ET STRUCTURE', 1);
$section->addTextBreak();

$section->addTitle('Navigation Tailwind moderne', 2);
$section->addText('La navigation principale utilise Tailwind CSS avec des composants Alpine.js. Chaque element de menu utilise une iconographie moderne pour ameliorer la reconnaissance visuelle et l\'accessibilite.', $contentStyle);

$section->addTextBreak();

$section->addText('Structure de navigation implementee :', $strongStyle);
$section->addText('Logo FarmShop : Identite visuelle en farm-green-500', $contentStyle);
$section->addText('Accueil : Interface intuitive avec Alpine.js', $contentStyle);
$section->addText('Produits : Grid responsive avec Tailwind', $contentStyle);
$section->addText('Panier d\'achat : Compteur dynamique', $contentStyle);
$section->addText('Blog : Systeme de commentaires integre', $contentStyle);

$section->addTextBreak();

// COMPOSANTS UI
$section->addTitle('7. COMPOSANTS UI IMPLEMENTES', 1);
$section->addTextBreak();

$section->addTitle('Systeme de composants Tailwind + Alpine.js', 2);
$section->addText('Les composants utilisent les classes utilitaires Tailwind CSS avec des interactions Alpine.js. Cette approche moderne ameliore les performances et la maintenabilite du code Laravel 11.', $contentStyle);

$section->addTextBreak();

$section->addText('Composants principaux implementes :', $strongStyle);
$section->addText('Cards produits : hover:scale-105 transform transition', $contentStyle);
$section->addText('Boutons CTA : bg-farm-green-500 hover:bg-farm-green-700', $contentStyle);
$section->addText('Formulaires : Validation temps reel avec Alpine.js', $contentStyle);
$section->addText('Modales : Overlay avec backdrop-blur-sm', $contentStyle);

$section->addTextBreak();

// RESPONSIVE DESIGN
$section->addTitle('8. RESPONSIVE DESIGN ET ANIMATIONS', 1);
$section->addTextBreak();

$section->addTitle('Approche mobile-first avec Tailwind', 2);
$section->addText('Le design responsive utilise le systeme de breakpoints Tailwind CSS avec des animations optimisees pour les performances. Toutes les animations utilisent transform et opacity pour eviter les reflows.', $contentStyle);

$section->addTextBreak();

$section->addText('Animations implementees avec Tailwind :', $strongStyle);
$section->addText('hover:scale-105 : Zoom cards au survol', $contentStyle);
$section->addText('transition-all duration-300 : Transitions fluides', $contentStyle);
$section->addText('animate-pulse : Loading states', $contentStyle);
$section->addText('hover:shadow-lg : Elevations dynamiques', $contentStyle);

$section->addTextBreak();

// FONCTIONNALITES SPECIFIQUES
$section->addTitle('9. FONCTIONNALITES SPECIFIQUES FARMSHOP', 1);
$section->addTextBreak();

$section->addTitle('Dualite Achat/Location avec Laravel 11', 2);
$section->addText('L\'interface reflete la double vocation de FarmShop avec des composants visuels distincts implementes avec les nouvelles fonctionnalites Laravel 11. Les paniers d\'achat (farm-green) et de location (blue-500) utilisent des controleurs separes pour optimiser les performances.', $contentStyle);

$section->addTextBreak();

$section->addTitle('Systeme de roles avec Spatie Laravel-Permission', 2);
$section->addText('Le Panel Admin utilise le package Spatie Laravel-Permission optimise pour Laravel 11, avec des gates et policies pour controler l\'acces aux fonctionnalites administratives.', $contentStyle);

$section->addTextBreak();

// OPTIMISATIONS
$section->addTitle('10. OPTIMISATIONS ET PERFORMANCES', 1);
$section->addTextBreak();

$section->addTitle('Optimisations Laravel 11 LTS', 2);
$section->addText('Les optimisations tirent parti des nouveautes Laravel 11 pour ameliorer les performances globales de l\'application FarmShop.', $contentStyle);

$section->addTextBreak();

$section->addText('Optimisations implementees :', $strongStyle);
$section->addText('Vite.js : Bundling optimise pour production', $contentStyle);
$section->addText('Eager Loading : Relations Eloquent optimisees', $contentStyle);
$section->addText('Route Caching : Cache des routes Laravel 11', $contentStyle);
$section->addText('Redis Cache : Mise en cache des donnees frequentes', $contentStyle);
$section->addText('Tailwind CSS Purge : Suppression des classes inutilisees', $contentStyle);

$section->addTextBreak();

// CONCLUSION
$section->addTitle('11. CONCLUSION ET EVOLUTIONS', 1);
$section->addTextBreak();

$section->addTitle('Bilan de l\'implementation Laravel 11', 2);
$section->addText('L\'implementation actuelle de FarmShop respecte les standards modernes de developpement avec Laravel 11 LTS. L\'utilisation de Tailwind CSS garantit la compatibilite cross-browser, tandis que les nouveautes Laravel 11 offrent des performances optimales.', $contentStyle);

$section->addTextBreak();

$section->addText('Points forts de l\'implementation Laravel 11 :', $strongStyle);
$section->addText('Architecture moderne avec Laravel 11 LTS', $contentStyle);
$section->addText('Design system coherent base sur Tailwind CSS', $contentStyle);
$section->addText('Performance optimisee avec Vite.js et caching', $contentStyle);
$section->addText('Interface adaptee aux specificites metier (achat/location)', $contentStyle);
$section->addText('Securite renforcee avec les middlewares Laravel 11', $contentStyle);

$section->addTextBreak();

$section->addText('Evolutions futures possibles :', $strongStyle);
$section->addText('Le systeme actuel permet facilement l\'ajout de nouvelles fonctionnalites grace a la flexibilite de Laravel 11. L\'integration future d\'API externes (paiement, logistique) sera simplifiee par la nouvelle architecture de services.', $contentStyle);

$section->addTextBreak();

// BIBLIOGRAPHIE
$section->addTitle('12. Bibliographie', 1);
$section->addTextBreak();

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

// Footer academique
$section->addText('Document genere le 16 juillet 2025', [
    'name' => 'Inter', 'size' => 10, 'lang' => 'fr-BE'
], ['alignment' => Jc::CENTER]);

$section->addText('MEFTAH Soufiane - Bachelier en Informatique de gestion', [
    'name' => 'Inter', 'size' => 10, 'lang' => 'fr-BE'
], ['alignment' => Jc::CENTER]);

$section->addText('Institut des Carrieres Commerciales - Bruxelles', [
    'name' => 'Inter', 'size' => 10, 'lang' => 'fr-BE'
], ['alignment' => Jc::CENTER]);

// Sauvegarder avec encodage UTF-8 pour Office 365
$objWriter = IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save(__DIR__ . '/12_Rapport_Ecrit_Complet.docx');

echo "Rapport ecrit Office 365 compatible cree avec succes !\n";
echo "Emplacement : " . __DIR__ . "/12_Rapport_Ecrit_Complet.docx\n";
echo "Document avec encodage UTF-8, langue fr-BE, police Inter, compatible Office 365 !\n";
echo "Cahier de charges complet avec toutes les exigences F1-F33, A1-A10, N1-N9 !\n";

?>
