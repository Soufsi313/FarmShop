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
$section->addText('LIVRABLE 10', [
    'name' => 'Inter', 'size' => 20, 'bold' => true
], ['alignment' => Jc::CENTER]);

$section->addText('RAPPORT DE CONCEPTION GRAPHIQUE', [
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

// Introduction
$section->addTitle('1. INTRODUCTION', 1);

$section->addText('FarmShop est une plateforme e-commerce specialisee dans le domaine agricole, proposant un service dual d\'achat et de location d\'equipements agricoles. Ce rapport presente les decisions de conception graphique prises pour creer une experience utilisateur optimale, alliant fonctionnalite et esthetique agricole moderne.', $contentStyle);

$section->addTextBreak();

$section->addText('L\'identite visuelle de FarmShop s\'articule autour de valeurs de confiance, de proximite avec la nature et de professionnalisme agricole, tout en offrant une interface moderne et intuitive.', $contentStyle);

$section->addTextBreak(2);

// Choix des couleurs
$section->addTitle('2. CHOIX DES COULEURS', 1);

$section->addTitle('2.1 Palette de couleurs principale', 2);

$section->addText('La palette de couleurs de FarmShop s\'inspire directement de l\'univers agricole et naturel :', $contentStyle);
$section->addTextBreak();

// Tableau des couleurs
$colorTable = $section->addTable(['borderSize' => 6, 'borderColor' => '999999', 'cellMargin' => 80]);
$colorTable->addRow();
$colorTable->addCell(3000)->addText('Couleur', $strongStyle);
$colorTable->addCell(2000)->addText('Code Hex', $strongStyle);
$colorTable->addCell(4000)->addText('Usage', $strongStyle);

$colorTable->addRow();
$colorTable->addCell(3000)->addText('Vert Agricole Principal', $contentStyle);
$colorTable->addCell(2000)->addText('#22c55e', $contentStyle);
$colorTable->addCell(4000)->addText('Boutons principaux, liens, elements d\'action', $contentStyle);

$colorTable->addRow();
$colorTable->addCell(3000)->addText('Vert Agricole Fonce', $contentStyle);
$colorTable->addCell(2000)->addText('#15803d', $contentStyle);
$colorTable->addCell(4000)->addText('Titres principaux, textes importants', $contentStyle);

$colorTable->addRow();
$colorTable->addCell(3000)->addText('Brun Agricole', $contentStyle);
$colorTable->addCell(2000)->addText('#a18072', $contentStyle);
$colorTable->addCell(4000)->addText('Elements secondaires, bordures', $contentStyle);

$colorTable->addRow();
$colorTable->addCell(3000)->addText('Vert Clair', $contentStyle);
$colorTable->addCell(2000)->addText('#f0fdf4', $contentStyle);
$colorTable->addCell(4000)->addText('Arriere-plans, zones de contenu', $contentStyle);

$section->addTextBreak();

$section->addTitle('2.2 Couleurs d\'etat et feedback', 2);

$section->addText('• Succes : #22c55e (vert agricole principal)', $contentStyle);
$section->addText('• Attention : #f59e0b (orange naturel)', $contentStyle);
$section->addText('• Erreur : #ef4444 (rouge discret)', $contentStyle);
$section->addText('• Information : #3b82f6 (bleu ciel)', $contentStyle);

$section->addTextBreak(2);

// Choix des polices
$section->addTitle('3. CHOIX DES POLICES', 1);

$section->addTitle('3.1 Police principale', 2);

$section->addText('Police choisie : Inter', $strongStyle);
$section->addTextBreak();

$section->addText('Justification du choix :', $strongStyle);
$section->addText('• Lisibilite exceptionnelle sur tous les supports', $contentStyle);
$section->addText('• Design moderne et professionnel', $contentStyle);
$section->addText('• Optimisee pour les interfaces numeriques', $contentStyle);
$section->addText('• Large gamme de graisses disponibles', $contentStyle);
$section->addText('• Compatible avec les standards d\'accessibilite', $contentStyle);

$section->addTextBreak();

$section->addTitle('3.2 Hierarchie typographique', 2);

$hierarchyTable = $section->addTable(['borderSize' => 6, 'borderColor' => '999999', 'cellMargin' => 80]);
$hierarchyTable->addRow();
$hierarchyTable->addCell(3000)->addText('Element', $strongStyle);
$hierarchyTable->addCell(2000)->addText('Taille', $strongStyle);
$hierarchyTable->addCell(2000)->addText('Graisse', $strongStyle);
$hierarchyTable->addCell(2000)->addText('Usage', $strongStyle);

$hierarchyTable->addRow();
$hierarchyTable->addCell(3000)->addText('Titre H1', $contentStyle);
$hierarchyTable->addCell(2000)->addText('48px', $contentStyle);
$hierarchyTable->addCell(2000)->addText('Bold', $contentStyle);
$hierarchyTable->addCell(2000)->addText('Titres de pages', $contentStyle);

$hierarchyTable->addRow();
$hierarchyTable->addCell(3000)->addText('Titre H2', $contentStyle);
$hierarchyTable->addCell(2000)->addText('36px', $contentStyle);
$hierarchyTable->addCell(2000)->addText('Bold', $contentStyle);
$hierarchyTable->addCell(2000)->addText('Sections principales', $contentStyle);

$hierarchyTable->addRow();
$hierarchyTable->addCell(3000)->addText('Titre H3', $contentStyle);
$hierarchyTable->addCell(2000)->addText('24px', $contentStyle);
$hierarchyTable->addCell(2000)->addText('Semibold', $contentStyle);
$hierarchyTable->addCell(2000)->addText('Sous-sections', $contentStyle);

$hierarchyTable->addRow();
$hierarchyTable->addCell(3000)->addText('Texte de base', $contentStyle);
$hierarchyTable->addCell(2000)->addText('16px', $contentStyle);
$hierarchyTable->addCell(2000)->addText('Regular', $contentStyle);
$hierarchyTable->addCell(2000)->addText('Contenu principal', $contentStyle);

$hierarchyTable->addRow();
$hierarchyTable->addCell(3000)->addText('Texte small', $contentStyle);
$hierarchyTable->addCell(2000)->addText('14px', $contentStyle);
$hierarchyTable->addCell(2000)->addText('Regular', $contentStyle);
$hierarchyTable->addCell(2000)->addText('Metadonnees, notes', $contentStyle);

$section->addTextBreak(2);

// Logo et identité visuelle
$section->addTitle('4. LOGO ET IDENTITE VISUELLE', 1);

$section->addTitle('4.1 Logo principal', 2);

$section->addText('Le logo FarmShop se compose de :', $contentStyle);
$section->addText('• Icone : Emoji tracteur (🚜) representant l\'univers agricole', $contentStyle);
$section->addText('• Typographie : "FarmShop" en Inter Bold', $contentStyle);
$section->addText('• Couleur : Vert agricole principal (#22c55e)', $contentStyle);

$section->addTextBreak();

$section->addTitle('4.2 Variantes du logo', 2);

$section->addText('• Version complete : Icone + texte (utilisation principale)', $contentStyle);
$section->addText('• Version icone seule : Pour les espaces reduits', $contentStyle);
$section->addText('• Version inversee : Sur fonds sombres', $contentStyle);
$section->addText('• Version monochrome : Pour impressions noir et blanc', $contentStyle);

$section->addTextBreak(2);

// Icônes et boutons
$section->addTitle('5. ICONES ET BOUTONS', 1);

$section->addTitle('5.1 Systeme d\'icones', 2);

$section->addText('Approche adoptee : Emojis Unicode pour l\'iconographie', $contentStyle);
$section->addTextBreak();

$section->addText('Avantages de ce choix :', $contentStyle);
$section->addText('• Universalite et reconnaissance immediate', $contentStyle);
$section->addText('• Coherence cross-platform', $contentStyle);
$section->addText('• Pas de dependance a des bibliotheques externes', $contentStyle);
$section->addText('• Accessibilite native', $contentStyle);

$section->addTextBreak();

$section->addTitle('5.2 Icones principales utilisees', 2);

$iconTable = $section->addTable(['borderSize' => 6, 'borderColor' => '999999', 'cellMargin' => 80]);
$iconTable->addRow();
$iconTable->addCell(2000)->addText('Icone', $strongStyle);
$iconTable->addCell(3000)->addText('Usage', $strongStyle);
$iconTable->addCell(4000)->addText('Contexte', $strongStyle);

$iconTable->addRow();
$iconTable->addCell(2000)->addText('🚜', $contentStyle);
$iconTable->addCell(3000)->addText('Produits agricoles', $contentStyle);
$iconTable->addCell(4000)->addText('Logo, categories, equipements', $contentStyle);

$iconTable->addRow();
$iconTable->addCell(2000)->addText('🛒', $contentStyle);
$iconTable->addCell(3000)->addText('Panier d\'achat', $contentStyle);
$iconTable->addCell(4000)->addText('E-commerce, commandes', $contentStyle);

$iconTable->addRow();
$iconTable->addCell(2000)->addText('📅', $contentStyle);
$iconTable->addCell(3000)->addText('Locations', $contentStyle);
$iconTable->addCell(4000)->addText('Systeme de reservation', $contentStyle);

$iconTable->addRow();
$iconTable->addCell(2000)->addText('👤', $contentStyle);
$iconTable->addCell(3000)->addText('Compte utilisateur', $contentStyle);
$iconTable->addCell(4000)->addText('Profil, authentification', $contentStyle);

$iconTable->addRow();
$iconTable->addCell(2000)->addText('🌾', $contentStyle);
$iconTable->addCell(3000)->addText('Agriculture generale', $contentStyle);
$iconTable->addCell(4000)->addText('Decoration, ambiance', $contentStyle);

$section->addTextBreak();

$section->addTitle('5.3 Conception des boutons', 2);

$section->addText('Principes de design des boutons :', $contentStyle);
$section->addText('• Boutons primaires : Fond vert agricole, texte blanc', $contentStyle);
$section->addText('• Boutons secondaires : Bordure verte, texte vert', $contentStyle);
$section->addText('• Boutons de danger : Fond rouge discret', $contentStyle);
$section->addText('• Etats hover : Assombrissement de 10% de la couleur', $contentStyle);
$section->addText('• Bordures arrondies : 8px pour la douceur', $contentStyle);
$section->addText('• Padding : 12px verticalement, 24px horizontalement', $contentStyle);

$section->addTextBreak(2);

// Structure du site
$section->addTitle('6. STRUCTURE DU SITE', 1);

$section->addTitle('6.1 Architecture de l\'information', 2);

$section->addText('Navigation principale :', $contentStyle);
$section->addText('• Accueil', $contentStyle);
$section->addText('• Produits (avec categories)', $contentStyle);
$section->addText('• Locations', $contentStyle);
$section->addText('• Mon compte', $contentStyle);
$section->addText('• Panier', $contentStyle);
$section->addText('• Contact', $contentStyle);

$section->addTextBreak();

$section->addTitle('6.2 Layout et grille', 2);

$section->addText('Systeme de grille : Bootstrap-inspired avec Tailwind CSS', $contentStyle);
$section->addText('• Breakpoints responsive standards', $contentStyle);
$section->addText('• Mobile-first approach', $contentStyle);
$section->addText('• Container max-width : 1200px', $contentStyle);
$section->addText('• Gutters : 24px sur desktop, 16px sur mobile', $contentStyle);

$section->addTextBreak(2);

// Trames de pages
$section->addTitle('7. TRAMES DE PAGES', 1);

$section->addTitle('7.1 Page d\'accueil', 2);

$section->addText('Structure de la page d\'accueil :', $contentStyle);
$section->addText('1. Header avec navigation et logo', $contentStyle);
$section->addText('2. Hero section avec message d\'accueil', $contentStyle);
$section->addText('3. Section "Pourquoi FarmShop"', $contentStyle);
$section->addText('4. Produits vedettes', $contentStyle);
$section->addText('5. Categories principales', $contentStyle);
$section->addText('6. Statistiques et temoignages', $contentStyle);
$section->addText('7. Newsletter et contact', $contentStyle);
$section->addText('8. Footer avec liens utiles', $contentStyle);

$section->addTextBreak();

$section->addTitle('7.2 Page produit', 2);

$section->addText('Elements de la page produit :', $contentStyle);
$section->addText('1. Breadcrumb de navigation', $contentStyle);
$section->addText('2. Galerie d\'images produit', $contentStyle);
$section->addText('3. Informations produit et prix', $contentStyle);
$section->addText('4. Options d\'achat/location', $contentStyle);
$section->addText('5. Description detaillee', $contentStyle);
$section->addText('6. Produits similaires', $contentStyle);
$section->addText('7. Avis clients', $contentStyle);

$section->addTextBreak();

$section->addTitle('7.3 Page de listing', 2);

$section->addText('Organisation des listings :', $contentStyle);
$section->addText('1. Header avec filtres', $contentStyle);
$section->addText('2. Sidebar avec facettes de recherche', $contentStyle);
$section->addText('3. Grille de produits responsive', $contentStyle);
$section->addText('4. Pagination', $contentStyle);
$section->addText('5. Options de tri', $contentStyle);

$section->addTextBreak(2);

// Maquettes fonctionnelles
$section->addTitle('8. MAQUETTES FONCTIONNELLES', 1);

$section->addTitle('8.1 Accueil - Version desktop', 2);

$section->addText('Disposition fonctionnelle de l\'accueil :', $contentStyle);
$section->addText('• Header fixe : Logo + Navigation + Compte/Panier', $contentStyle);
$section->addText('• Hero banner : 100vh avec CTA principaux', $contentStyle);
$section->addText('• Grid 3 colonnes : Services principaux', $contentStyle);
$section->addText('• Carousel : Produits vedettes', $contentStyle);
$section->addText('• Grid 4 colonnes : Categories', $contentStyle);
$section->addText('• Section stats : 3 colonnes avec chiffres cles', $contentStyle);

$section->addTextBreak();

$section->addTitle('8.2 Accueil - Version mobile', 2);

$section->addText('Adaptations mobile :', $contentStyle);
$section->addText('• Navigation hamburger', $contentStyle);
$section->addText('• Hero reduit : 60vh', $contentStyle);
$section->addText('• Grid 1 colonne : Empilement vertical', $contentStyle);
$section->addText('• Carousel touch-friendly', $contentStyle);
$section->addText('• Footer condense', $contentStyle);

$section->addTextBreak();

$section->addTitle('8.3 Page produit - Fonctionnalites', 2);

$section->addText('Elements fonctionnels :', $contentStyle);
$section->addText('• Zoom image au survol', $contentStyle);
$section->addText('• Selecteur quantite', $contentStyle);
$section->addText('• Calculateur de location', $contentStyle);
$section->addText('• Wishlist et partage', $contentStyle);
$section->addText('• Systeme d\'avis avec etoiles', $contentStyle);

$section->addTextBreak(2);

// Maquettes graphiques
$section->addTitle('9. MAQUETTES GRAPHIQUES', 1);

$section->addTitle('9.1 Charte graphique appliquee', 2);

$section->addText('Application concrete de l\'identite visuelle :', $contentStyle);
$section->addText('• Palette de couleurs respectee sur tous les elements', $contentStyle);
$section->addText('• Typographie Inter deployee systematiquement', $contentStyle);
$section->addText('• Iconographie emoji coherente', $contentStyle);
$section->addText('• Espacements harmonieux (multiples de 8px)', $contentStyle);
$section->addText('• Ombres subtiles pour la profondeur', $contentStyle);

$section->addTextBreak();

$section->addTitle('9.2 Design system components', 2);

$section->addText('Composants standardises :', $contentStyle);
$section->addText('• Cards produits avec hover effects', $contentStyle);
$section->addText('• Formulaires avec validation visuelle', $contentStyle);
$section->addText('• Modales et overlays', $contentStyle);
$section->addText('• Notifications toast', $contentStyle);
$section->addText('• Breadcrumbs et pagination', $contentStyle);

$section->addTextBreak();

$section->addTitle('9.3 Etats et interactions', 2);

$section->addText('Design des interactions :', $contentStyle);
$section->addText('• Transitions fluides (300ms ease)', $contentStyle);
$section->addText('• Loading states avec spinners', $contentStyle);
$section->addText('• Error states avec messages clairs', $contentStyle);
$section->addText('• Success feedback avec animations', $contentStyle);
$section->addText('• Disabled states avec opacite reduite', $contentStyle);

$section->addTextBreak(2);

// Expérience utilisateur
$section->addTitle('10. EXPERIENCE UTILISATEUR', 1);

$section->addTitle('10.1 Parcours utilisateur optimises', 2);

$section->addText('Parcours d\'achat :', $contentStyle);
$section->addText('1. Decouverte produit via accueil ou recherche', $contentStyle);
$section->addText('2. Consultation detaillee avec images et specs', $contentStyle);
$section->addText('3. Ajout au panier avec feedback visuel', $contentStyle);
$section->addText('4. Tunnel d\'achat simplifie (3 etapes max)', $contentStyle);
$section->addText('5. Confirmation avec tracking', $contentStyle);

$section->addTextBreak();

$section->addText('Parcours de location :', $contentStyle);
$section->addText('1. Selection dates via calendrier intuitif', $contentStyle);
$section->addText('2. Calcul automatique des couts', $contentStyle);
$section->addText('3. Gestion de la caution transparente', $contentStyle);
$section->addText('4. Suivi de location en temps reel', $contentStyle);

$section->addTextBreak();

$section->addTitle('10.2 Accessibilite et inclusivite', 2);

$section->addText('Mesures d\'accessibilite implementees :', $contentStyle);
$section->addText('• Contrastes WCAG AA respectes', $contentStyle);
$section->addText('• Navigation clavier complete', $contentStyle);
$section->addText('• Alt texts sur toutes les images', $contentStyle);
$section->addText('• Tailles de police ajustables', $contentStyle);
$section->addText('• Focus indicators visibles', $contentStyle);

$section->addTextBreak();

$section->addTitle('10.3 Performance et optimisation', 2);

$section->addText('Optimisations techniques :', $contentStyle);
$section->addText('• Images responsive avec lazy loading', $contentStyle);
$section->addText('• CSS et JS minifies', $contentStyle);
$section->addText('• Cache navigateur optimise', $contentStyle);
$section->addText('• Fonts preload pour Inter', $contentStyle);
$section->addText('• Critical CSS inline', $contentStyle);

$section->addTextBreak(2);

// Conclusion
$section->addTitle('11. CONCLUSION', 1);

$section->addText('La conception graphique de FarmShop repond aux enjeux specifiques d\'une marketplace agricole moderne. L\'identite visuelle developpe allie l\'authenticite du monde agricole a la modernite d\'une interface web contemporaine.', $contentStyle);

$section->addTextBreak();

$section->addText('Les choix de couleurs, de typographie et d\'iconographie creent un environnement visuel coherent et rassurant pour les utilisateurs. La structure modulaire et responsive garantit une experience optimale sur tous les appareils.', $contentStyle);

$section->addTextBreak();

$section->addText('Cette conception graphique pose les bases solides pour le deploiement d\'une plateforme e-commerce performante et attractive dans le secteur agricole.', $contentStyle);

$section->addTextBreak(4);

// Footer académique
$section->addText('Document genere le 15 juillet 2025', [
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
$objWriter->save(__DIR__ . '/10_Rapport_Conception_Graphique.docx');

echo "Rapport de conception graphique cree avec succes !\n";
echo "Emplacement : " . __DIR__ . "/10_Rapport_Conception_Graphique.docx\n";
echo "Document academique professionnel avec analyse complete du design FarmShop genere !\n";

?>
