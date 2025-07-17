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

$phpWord->addTitleStyle(4, [
    'name' => 'Inter', 
    'size' => 12, 
    'bold' => true, 
    'color' => '000000'
]);

// Styles de contenu
$contentStyle = ['name' => 'Inter', 'size' => 11, 'lang' => 'fr-BE'];
$strongStyle = ['name' => 'Inter', 'size' => 11, 'bold' => true, 'lang' => 'fr-BE'];
$italicStyle = ['name' => 'Inter', 'size' => 11, 'italic' => true, 'lang' => 'fr-BE'];
$codeStyle = ['name' => 'Consolas', 'size' => 10, 'lang' => 'fr-BE'];
$legalStyle = ['name' => 'Inter', 'size' => 10, 'italic' => true, 'color' => '666666', 'lang' => 'fr-BE'];

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

$section->addText('LIVRABLE 18', [
    'name' => 'Inter', 'size' => 20, 'bold' => true, 'lang' => 'fr-BE'
], ['alignment' => Jc::CENTER]);

$section->addText('ASPECTS JURIDIQUES ET CADRE LEGAL', [
    'name' => 'Inter', 'size' => 16, 'bold' => true, 'lang' => 'fr-BE'
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

$section->addText('Introduction', $contentStyle);
$section->addText('1. Cadre juridique general du commerce electronique', $contentStyle);
$section->addText('1.1 Droit d\'Internet et responsabilites numeriques', $contentStyle);
$section->addText('1.2 Code de droit economique - Livre XII', $contentStyle);
$section->addText('1.3 Responsabilite des prestataires intermediaires', $contentStyle);
$section->addText('2. Specificites secteur agricole et alimentaire', $contentStyle);
$section->addText('2.1 AFSCA et controle qualite alimentaire', $contentStyle);
$section->addText('2.2 Reglementation produits phytopharmaceutiques', $contentStyle);
$section->addText('2.3 Tracabilite et etiquetage obligatoire', $contentStyle);
$section->addText('2.4 Autorisations specifiques vente/location', $contentStyle);
$section->addText('3. Protection des donnees personnelles (GDPR)', $contentStyle);
$section->addText('3.1 Les 7 principes cles GDPR', $contentStyle);
$section->addText('3.2 Implementation technique FarmShop', $contentStyle);
$section->addText('3.3 Droits des utilisateurs et procedures', $contentStyle);
$section->addText('3.4 Politique de cookies et consentement', $contentStyle);
$section->addText('4. Droit de la consommation e-commerce', $contentStyle);
$section->addText('4.1 Droit de retractation (14 jours minimum)', $contentStyle);
$section->addText('4.2 Information precontractuelle obligatoire', $contentStyle);
$section->addText('4.3 Conditions generales vente et location', $contentStyle);
$section->addText('4.4 Garanties legales et commerciales', $contentStyle);
$section->addText('5. Droit d\'auteur et propriete intellectuelle', $contentStyle);
$section->addText('5.1 Protection contenus et images FarmShop', $contentStyle);
$section->addText('5.2 Utilisation licites ressources tiers', $contentStyle);
$section->addText('5.3 Mentions legales et attributions', $contentStyle);
$section->addText('6. Obligations specifiques FarmShop', $contentStyle);
$section->addText('6.1 Mentions legales site web', $contentStyle);
$section->addText('6.2 CGV/CGU adaptees vente et location', $contentStyle);
$section->addText('6.3 Procedures conformite GDPR', $contentStyle);
$section->addText('6.4 Registre traitements donnees', $contentStyle);
$section->addText('7. Gestion des risques juridiques', $contentStyle);
$section->addText('7.1 Assurances professionnelles', $contentStyle);
$section->addText('7.2 Procedures contentieux client', $contentStyle);
$section->addText('7.3 Veille reglementaire continue', $contentStyle);
$section->addText('Conclusion', $contentStyle);

// Nouvelle page
$section->addPageBreak();

// INTRODUCTION
$section->addTitle('Introduction', 1);
$section->addTextBreak();

$section->addText('FarmShop, plateforme e-commerce specialisee dans la vente et location de produits agricoles, evolue dans un environnement juridique complexe necessitant une approche multidisciplinaire du droit. L\'intersection entre commerce electronique, secteur agricole et protection des donnees cree un cadre legal specifique exigeant une conformite rigoureuse.', $contentStyle);

$section->addTextBreak();

$section->addText('Ce livrable analyse les obligations juridiques applicables a FarmShop selon trois axes principaux : le droit du commerce electronique (Code de droit economique), la reglementation sectorielle agricole (AFSCA, phytopharmaceutiques) et la protection des donnees personnelles (GDPR/RGPD).', $contentStyle);

$section->addTextBreak();

$section->addText('L\'objectif est d\'identifier les risques juridiques, d\'etablir les procedures de conformite et de definir les mesures preventives pour assurer un fonctionnement legal et securise de la plateforme dans le respect des droits des consommateurs et utilisateurs.', $contentStyle);

$section->addTextBreak(2);

// 1. CADRE JURIDIQUE GENERAL
$section->addTitle('1. Cadre juridique general du commerce electronique', 1);
$section->addTextBreak();

$section->addTitle('1.1 Droit d\'Internet et responsabilites numeriques', 2);
$section->addTextBreak();

$section->addText('Fondements legaux applicables :', $strongStyle);
$section->addTextBreak();

$section->addText('Code de droit economique (CDE) - Livre XII "Services de la societe de l\'information"', $legalStyle);
$section->addText('Articles XII.1 a XII.23 definissant les obligations des prestateurs de services numeriques', $contentStyle);
$section->addTextBreak();

$section->addText('Loi du 11 mars 2003 sur certains aspects juridiques des services de la societe de l\'information', $legalStyle);
$section->addText('Transposition directive europeenne 2000/31/CE sur le commerce electronique', $contentStyle);
$section->addTextBreak();

$section->addText('Responsabilite de FarmShop en tant qu\'hebergeur de contenu :', $strongStyle);
$section->addText('Article 12 CDE : Exemption de responsabilite pour stockage automatique temporaire', $contentStyle);
$section->addText('Article 14 CDE : Obligation de retrait rapide sur notification autorite judiciaire', $contentStyle);
$section->addText('Procedure notice and takedown pour contenus illicites utilisateurs', $contentStyle);

$section->addTextBreak();

$section->addTitle('1.2 Code de droit economique - Livre XII', 2);
$section->addTextBreak();

$section->addText('Obligations information precontractuelle (Article XII.1) :', $strongStyle);
$section->addText('Identite complete entreprise (denomination, adresse, numero BCE)', $contentStyle);
$section->addText('Coordonnees contact (telephone, email, formulaire)', $contentStyle);
$section->addText('Numero TVA et autorisation commerciale', $contentStyle);
$section->addText('Conditions contractuelles accessibles et imprimables', $contentStyle);
$section->addText('Prix TTC, frais livraison et modalites paiement', $contentStyle);

$section->addTextBreak();

$section->addText('Procedure commande electronique (Article XII.2) :', $strongStyle);
$section->addText('Etapes techniques claires pour passer commande', $contentStyle);
$section->addText('Correction erreurs de saisie avant validation', $contentStyle);
$section->addText('Accusation reception commande par voie electronique', $contentStyle);
$section->addText('Conservation commande accessible pendant duree appropriee', $contentStyle);

$section->addTextBreak();

$section->addTitle('1.3 Responsabilite des prestataires intermediaires', 2);
$section->addTextBreak();

$section->addText('Statut juridique FarmShop :', $strongStyle);
$section->addText('Prestataire de services numeriques (hosting + e-commerce)', $contentStyle);
$section->addText('Responsabilite limitee pour contenus utilisateurs tiers', $contentStyle);
$section->addText('Responsabilite pleine pour contenus propres et produits vendus', $contentStyle);
$section->addText('Obligation de moyens pour securite technique plateforme', $contentStyle);

// Nouvelle page
$section->addPageBreak();

// 2. SPECIFICITES SECTEUR AGRICOLE
$section->addTitle('2. Specificites secteur agricole et alimentaire', 1);
$section->addTextBreak();

$section->addTitle('2.1 AFSCA et controle qualite alimentaire', 2);
$section->addTextBreak();

$section->addText('Agence Federale pour la Securite de la Chaine Alimentaire (AFSCA) :', $strongStyle);
$section->addTextBreak();

$section->addText('Reglementation applicable :', $strongStyle);
$section->addText('Arrete royal du 14 novembre 2003 relatif a l\'autosurveillance', $legalStyle);
$section->addText('Reglementation CE 178/2002 etablissant principes generaux legislation alimentaire', $legalStyle);
$section->addText('Arrete royal du 22 fevrier 2001 organisant controles AFSCA', $legalStyle);

$section->addTextBreak();

$section->addText('Obligations FarmShop pour produits alimentaires :', $strongStyle);
$section->addText('Declaration d\'activite AFSCA pour commercialisation produits alimentaires', $contentStyle);
$section->addText('Respect chaine de tracabilite (article 18 reglement CE 178/2002)', $contentStyle);
$section->addText('Controle provenance et certificats fournisseurs', $contentStyle);
$section->addText('Etiquetage conforme reglementation europeenne', $contentStyle);
$section->addText('Procedures retrait/rappel produits non-conformes', $contentStyle);

$section->addTextBreak();

$section->addText('Categories produits FarmShop concernes AFSCA :', $strongStyle);
$section->addText('Semences et plants (certifies bio/conventionnels)', $contentStyle);
$section->addText('Produits phytosanitaires et engrais', $contentStyle);
$section->addText('Alimentation animale (fourrage, complements)', $contentStyle);
$section->addText('Outils contact alimentaire (recipients, emballages)', $contentStyle);

$section->addTextBreak();

$section->addTitle('2.2 Reglementation produits phytopharmaceutiques', 2);
$section->addTextBreak();

$section->addText('Legislation specifique :', $strongStyle);
$section->addText('Arrete royal du 28 fevrier 1994 relatif a la conservation, commercialisation et utilisation des pesticides', $legalStyle);
$section->addText('Reglementation CE 1107/2009 concernant mise sur marche produits phytopharmaceutiques', $legalStyle);

$section->addTextBreak();

$section->addText('Obligations vente produits phytosanitaires :', $strongStyle);
$section->addText('Agrement vendeur professionnel obligatoire', $contentStyle);
$section->addText('Formation certifiee personnel commercial', $contentStyle);
$section->addText('Verification permis phytolicence acheteurs professionnels', $contentStyle);
$section->addText('Interdiction vente particuliers produits PPP professionnels', $contentStyle);
$section->addText('Registre ventes obligatoire et controles AFSCA', $contentStyle);

$section->addTextBreak();

$section->addTitle('2.3 Tracabilite et etiquetage obligatoire', 2);
$section->addTextBreak();

$section->addText('Obligations tracabilite (Art. 18 Reglement CE 178/2002) :', $strongStyle);
$section->addText('Identification fournisseurs directs (nom, adresse, produit)', $contentStyle);
$section->addText('Identification clients professionnels (registre ventes)', $contentStyle);
$section->addText('Conservation documents 5 ans minimum', $contentStyle);
$section->addText('Procedures de retrait/rappel en cas de probleme', $contentStyle);

$section->addTextBreak();

$section->addText('Implementation technique FarmShop :', $strongStyle);
$section->addText('Systeme Laravel trackage commandes et fournisseurs', $contentStyle);
$section->addText('Base donnees relationnelle produits/lots/origines', $contentStyle);
$section->addText('API integration certificats fournisseurs', $contentStyle);
$section->addText('Notifications automatiques rappels/retraits', $contentStyle);

$section->addTextBreak();

$section->addTitle('2.4 Autorisations specifiques vente/location', 2);
$section->addTextBreak();

$section->addText('Vente materiel agricole :', $strongStyle);
$section->addText('Pas d\'autorisation specifique pour materiel non-motorise', $contentStyle);
$section->addText('Declaration CE conformite pour machines neuves', $contentStyle);
$section->addText('Controle technique obligatoire materiel occasion motorise', $contentStyle);

$section->addTextBreak();

$section->addText('Location materiel agricole :', $strongStyle);
$section->addText('Assurance responsabilite civile professionnelle obligatoire', $contentStyle);
$section->addText('Maintenance preventive et certificats conformite', $contentStyle);
$section->addText('Formation utilisateur pour materiel complexe', $contentStyle);
$section->addText('Contrats location conformes Code civil (art. 1708 et suivants)', $contentStyle);

// Nouvelle page
$section->addPageBreak();

// 3. PROTECTION DONNEES GDPR
$section->addTitle('3. Protection des donnees personnelles (GDPR)', 1);
$section->addTextBreak();

$section->addTitle('3.1 Les 7 principes cles GDPR', 2);
$section->addTextBreak();

$section->addText('Reglement UE 2016/679 - Principes fondamentaux :', $strongStyle);
$section->addTextBreak();

// Tableau 7 principes GDPR
$gdprTable = $section->addTable(['borderSize' => 6, 'borderColor' => '999999']);
$gdprTable->addRow();
$gdprTable->addCell(2000)->addText('Principe GDPR', $strongStyle);
$gdprTable->addCell(3500)->addText('Definition legale', $strongStyle);
$gdprTable->addCell(3500)->addText('Application FarmShop', $strongStyle);

$gdprTable->addRow();
$gdprTable->addCell(2000)->addText('Licéité', $contentStyle);
$gdprTable->addCell(3500)->addText('Art. 6 - Base légale traitement', $contentStyle);
$gdprTable->addCell(3500)->addText('Consentement, exécution contrat, intérêt légitime', $contentStyle);

$gdprTable->addRow();
$gdprTable->addCell(2000)->addText('Loyauté', $contentStyle);
$gdprTable->addCell(3500)->addText('Art. 5.1.a - Transparence traitement', $contentStyle);
$gdprTable->addCell(3500)->addText('Information claire utilisation données', $contentStyle);

$gdprTable->addRow();
$gdprTable->addCell(2000)->addText('Transparence', $contentStyle);
$gdprTable->addCell(3500)->addText('Art. 12-14 - Information personne', $contentStyle);
$gdprTable->addCell(3500)->addText('Politique de confidentialité accessible', $contentStyle);

$gdprTable->addRow();
$gdprTable->addCell(2000)->addText('Finalité', $contentStyle);
$gdprTable->addCell(3500)->addText('Art. 5.1.b - Finalités déterminées', $contentStyle);
$gdprTable->addCell(3500)->addText('Gestion commandes, support, marketing', $contentStyle);

$gdprTable->addRow();
$gdprTable->addCell(2000)->addText('Proportionnalité', $contentStyle);
$gdprTable->addCell(3500)->addText('Art. 5.1.c - Minimisation données', $contentStyle);
$gdprTable->addCell(3500)->addText('Collecte strictement nécessaire', $contentStyle);

$gdprTable->addRow();
$gdprTable->addCell(2000)->addText('Exactitude', $contentStyle);
$gdprTable->addCell(3500)->addText('Art. 5.1.d - Données exactes/mises à jour', $contentStyle);
$gdprTable->addCell(3500)->addText('Procédures correction profil utilisateur', $contentStyle);

$gdprTable->addRow();
$gdprTable->addCell(2000)->addText('Conservation', $contentStyle);
$gdprTable->addCell(3500)->addText('Art. 5.1.e - Durée limitée', $contentStyle);
$gdprTable->addCell(3500)->addText('Suppression automatique après 3 ans inactivité', $contentStyle);

$gdprTable->addRow();
$gdprTable->addCell(2000)->addText('Sécurité', $contentStyle);
$gdprTable->addCell(3500)->addText('Art. 32 - Mesures techniques appropriées', $contentStyle);
$gdprTable->addCell(3500)->addText('Chiffrement, contrôles accès, sauvegardes', $contentStyle);

$section->addTextBreak();

$section->addTitle('3.2 Implementation technique FarmShop', 2);
$section->addTextBreak();

$section->addText('Mesures techniques implementees :', $strongStyle);
$section->addText('Laravel Sanctum authentication avec tokens securises', $contentStyle);
$section->addText('Chiffrement AES-256 donnees sensibles base MySQL', $contentStyle);
$section->addText('Logs audits acces donnees avec timestamps', $contentStyle);
$section->addText('Middleware controle autorisations RBAC', $contentStyle);
$section->addText('Procedures backup et recovery conformes art. 32 GDPR', $contentStyle);

$section->addTextBreak();

$section->addText('Consentement cookies et tracking :', $strongStyle);
$section->addText('Banner consentement conforme lignes directrices CNIL', $contentStyle);
$section->addText('Granularite consentement par categorie cookies', $contentStyle);
$section->addText('Retrait consentement facilite (un clic)', $contentStyle);
$section->addText('Pas de cookies non-essentiels avant consentement', $contentStyle);

$section->addTextBreak();

$section->addTitle('3.3 Droits des utilisateurs et procedures', 2);
$section->addTextBreak();

$section->addText('Droits implementes (Articles 15-22 GDPR) :', $strongStyle);
$section->addTextBreak();

// Tableau droits utilisateurs
$rightsTable = $section->addTable(['borderSize' => 6, 'borderColor' => '999999']);
$rightsTable->addRow();
$rightsTable->addCell(2500)->addText('Droit utilisateur', $strongStyle);
$rightsTable->addCell(2000)->addText('Article GDPR', $strongStyle);
$rightsTable->addCell(4500)->addText('Procedure FarmShop', $strongStyle);

$rightsTable->addRow();
$rightsTable->addCell(2500)->addText('Accès données', $contentStyle);
$rightsTable->addCell(2000)->addText('Art. 15', $contentStyle);
$rightsTable->addCell(4500)->addText('Export JSON via dashboard utilisateur + email dans 30 jours', $contentStyle);

$rightsTable->addRow();
$rightsTable->addCell(2500)->addText('Rectification', $contentStyle);
$rightsTable->addCell(2000)->addText('Art. 16', $contentStyle);
$rightsTable->addCell(4500)->addText('Modification profil direct + validation administrateur si nécessaire', $contentStyle);

$rightsTable->addRow();
$rightsTable->addCell(2500)->addText('Effacement', $contentStyle);
$rightsTable->addCell(2000)->addText('Art. 17', $contentStyle);
$rightsTable->addCell(4500)->addText('Suppression compte + anonymisation commandes historiques', $contentStyle);

$rightsTable->addRow();
$rightsTable->addCell(2500)->addText('Portabilité', $contentStyle);
$rightsTable->addCell(2000)->addText('Art. 20', $contentStyle);
$rightsTable->addCell(4500)->addText('Export structuré XML/JSON compatible import autres plateformes', $contentStyle);

$rightsTable->addRow();
$rightsTable->addCell(2500)->addText('Opposition', $contentStyle);
$rightsTable->addCell(2000)->addText('Art. 21', $contentStyle);
$rightsTable->addCell(4500)->addText('Opt-out marketing direct + suppression profils marketing', $contentStyle);

$section->addTextBreak();

$section->addTitle('3.4 Politique de cookies et consentement', 2);
$section->addTextBreak();

$section->addText('Categories cookies FarmShop :', $strongStyle);
$section->addText('Cookies essentiels : Session, authentification, panier (pas de consentement requis)', $contentStyle);
$section->addText('Cookies fonctionnels : Preferences utilisateur, langue (consentement simple)', $contentStyle);
$section->addText('Cookies analytiques : Google Analytics 4 (consentement granulaire)', $contentStyle);
$section->addText('Cookies marketing : Remarketing, publicites (consentement explicite)', $contentStyle);

$section->addTextBreak();

$section->addText('Implementation technique consentement :', $strongStyle);
$section->addText('Package Laravel Cookie Consent avec base donnees', $contentStyle);
$section->addText('JavaScript conditionnel selon consentement utilisateur', $contentStyle);
$section->addText('Integration Google Consent Mode v2', $contentStyle);
$section->addText('Duree conservation consentement : 13 mois', $contentStyle);

// Nouvelle page
$section->addPageBreak();

// 4. DROIT CONSOMMATION
$section->addTitle('4. Droit de la consommation e-commerce', 1);
$section->addTextBreak();

$section->addTitle('4.1 Droit de retractation (14 jours minimum)', 2);
$section->addTextBreak();

$section->addText('Fondement legal :', $strongStyle);
$section->addText('Code de droit economique - Livre VI, Titre 3 "Pratiques du marche et protection du consommateur"', $legalStyle);
$section->addText('Articles VI.47 a VI.60 relatifs aux contrats a distance', $legalStyle);
$section->addText('Directive 2011/83/UE relative aux droits des consommateurs', $legalStyle);

$section->addTextBreak();

$section->addText('Modalites droit retractation FarmShop :', $strongStyle);
$section->addText('Delai : 14 jours calendaires a compter reception produit', $contentStyle);
$section->addText('Information claire dans CGV et email confirmation', $contentStyle);
$section->addText('Formulaire retractation standardise disponible', $contentStyle);
$section->addText('Remboursement sous 14 jours apres retour produit', $contentStyle);
$section->addText('Frais retour a charge client sauf defaut produit', $contentStyle);

$section->addTextBreak();

$section->addText('Exceptions droit retractation (Art. VI.53 CDE) :', $strongStyle);
$section->addText('Produits perissables ou rapidement deteriorables', $contentStyle);
$section->addText('Produits confectionnes selon specifications consommateur', $contentStyle);
$section->addText('Produits scelles descelles pour raisons hygiene/sante', $contentStyle);
$section->addText('Semences et plants vivants (duree vie limitee)', $contentStyle);

$section->addTextBreak();

$section->addText('Specificites location materiel :', $strongStyle);
$section->addText('Pas de droit retractation pour prestations services commencees', $contentStyle);
$section->addText('Accord expres consommateur avant execution service', $contentStyle);
$section->addText('Renonciation expresse droit retractation pour location immediate', $contentStyle);

$section->addTextBreak();

$section->addTitle('4.2 Information precontractuelle obligatoire', 2);
$section->addTextBreak();

$section->addText('Obligations information selon Art. VI.45 CDE :', $strongStyle);
$section->addText('Caracteristiques essentielles produits/services', $contentStyle);
$section->addText('Prix total TTC, frais livraison et autres couts', $contentStyle);
$section->addText('Modalites paiement, livraison et execution', $contentStyle);
$section->addText('Duree engagement contractuel ou resiliation', $contentStyle);
$section->addText('Existence garanties legales et service apres-vente', $contentStyle);

$section->addTextBreak();

$section->addText('Implementation FarmShop :', $strongStyle);
$section->addText('Fiches produits detaillees avec specifications techniques', $contentStyle);
$section->addText('Calculateur frais livraison transparent avant commande', $contentStyle);
$section->addText('Conditions generales accessibles a chaque etape achat', $contentStyle);
$section->addText('Recapitulatif commande avec prix total avant validation', $contentStyle);

$section->addTextBreak();

$section->addTitle('4.3 Conditions generales vente et location', 2);
$section->addTextBreak();

$section->addText('Structure CGV FarmShop adaptee dual model :', $strongStyle);
$section->addTextBreak();

$section->addText('Section VENTE :', $strongStyle);
$section->addText('Formation du contrat et validation commande', $contentStyle);
$section->addText('Prix, modalites paiement (Stripe, virement)', $contentStyle);
$section->addText('Livraison, transfert risques et propriete', $contentStyle);
$section->addText('Garanties legales conformite et vices caches', $contentStyle);
$section->addText('Droit retractation et modalites retour', $contentStyle);

$section->addTextBreak();

$section->addText('Section LOCATION :', $strongStyle);
$section->addText('Duree location, renouvellement automatique', $contentStyle);
$section->addText('Tarification (jour, semaine, mois), caution securite', $contentStyle);
$section->addText('Etat materiel, responsabilite maintenance', $contentStyle);
$section->addText('Assurance, responsabilite dommages utilisateur', $contentStyle);
$section->addText('Conditions restitution, penalites retard', $contentStyle);

$section->addTextBreak();

$section->addTitle('4.4 Garanties legales et commerciales', 2);
$section->addTextBreak();

$section->addText('Garanties legales applicables (Articles 1649bis et suivants Code civil) :', $strongStyle);
$section->addText('Garantie de conformite : 2 ans produits neufs, 1 an occasion', $contentStyle);
$section->addText('Garantie vices caches : defauts anterieurs vente non apparents', $contentStyle);
$section->addText('Garantie eviction : protection contre revendication tiers', $contentStyle);

$section->addTextBreak();

$section->addText('Garanties commerciales FarmShop :', $strongStyle);
$section->addText('Extension garantie constructeur (tracteurs, machines)', $contentStyle);
$section->addText('Garantie satisfait ou rembourse 30 jours outils main', $contentStyle);
$section->addText('Service apres-vente et pieces detachees disponibles', $contentStyle);
$section->addText('Formation gratuite utilisation materiel complexe', $contentStyle);

// Nouvelle page
$section->addPageBreak();

// 5. DROIT AUTEUR
$section->addTitle('5. Droit d\'auteur et propriete intellectuelle', 1);
$section->addTextBreak();

$section->addTitle('5.1 Protection contenus et images FarmShop', 2);
$section->addTextBreak();

$section->addText('Fondements legaux :', $strongStyle);
$section->addText('Code de droit economique - Livre XI "Propriete intellectuelle"', $legalStyle);
$section->addText('Loi du 30 juin 1994 relative au droit d\'auteur et droits voisins', $legalStyle);
$section->addText('Convention de Berne pour protection oeuvres litteraires et artistiques', $legalStyle);

$section->addTextBreak();

$section->addText('Contenus originaux FarmShop proteges :', $strongStyle);
$section->addText('Descriptions produits et guides techniques rediges', $contentStyle);
$section->addText('Photographies produits prises par equipe interne', $contentStyle);
$section->addText('Design interface utilisateur et charte graphique', $contentStyle);
$section->addText('Code source Laravel applications specifiques', $contentStyle);
$section->addText('Videos demonstrations et tutoriels', $contentStyle);

$section->addTextBreak();

$section->addText('Mentions copyright implementees :', $strongStyle);
$section->addText('Footer site : "© 2024-2025 FarmShop. Tous droits reserves."', $contentStyle);
$section->addText('Metadonnees images avec attribution automatique', $contentStyle);
$section->addText('Watermark discret sur photos haute resolution', $contentStyle);
$section->addText('Licence utilisation contenus dans CGU', $contentStyle);

$section->addTextBreak();

$section->addTitle('5.2 Utilisation licites ressources tiers', 2);
$section->addTextBreak();

$section->addText('Sources images autorisees utilisees :', $strongStyle);
$section->addText('Unsplash : licence libre utilisation commerciale', $contentStyle);
$section->addText('Pixabay : licence Pixabay compatible usage commercial', $contentStyle);
$section->addText('Freepik : abonnement Premium autorisant usage commercial', $contentStyle);
$section->addText('Getty Images : licence etendue pour e-commerce', $contentStyle);

$section->addTextBreak();

$section->addText('Verification droits utilisation :', $strongStyle);
$section->addText('Base donnees interne sources et licences images', $contentStyle);
$section->addText('Attribution systematique selon conditions licence', $contentStyle);
$section->addText('Verification absence model release pour personnes identifiables', $contentStyle);
$section->addText('Documentation juridique conservation 10 ans', $contentStyle);

$section->addTextBreak();

$section->addTitle('5.3 Mentions legales et attributions', 2);
$section->addTextBreak();

$section->addText('Page mentions legales conforme :', $strongStyle);
$section->addText('Editeur : FarmShop SPRL, BCE 0XXX.XXX.XXX', $contentStyle);
$section->addText('Hebergeur : OVH SAS, 2 rue Kellermann 59100 Roubaix', $contentStyle);
$section->addText('Directeur publication : [Nom gerant]', $contentStyle);
$section->addText('Credits photos et illustrations avec liens sources', $contentStyle);
$section->addText('Politique propriete intellectuelle et signalement violations', $contentStyle);

// Nouvelle page
$section->addPageBreak();

// 6. OBLIGATIONS SPECIFIQUES FARMSHOP
$section->addTitle('6. Obligations specifiques FarmShop', 1);
$section->addTextBreak();

$section->addTitle('6.1 Mentions legales site web', 2);
$section->addTextBreak();

$section->addText('Informations obligatoires selon Art. XII.1 CDE :', $strongStyle);
$section->addTextBreak();

$section->addText('Identification entreprise :', $strongStyle);
$section->addText('Denomination sociale : FarmShop SPRL', $contentStyle);
$section->addText('Numero entreprise BCE : 0XXX.XXX.XXX', $contentStyle);
$section->addText('Adresse siege social : [Adresse complete]', $contentStyle);
$section->addText('Numero TVA : BE 0XXX.XXX.XXX', $contentStyle);
$section->addText('Forme juridique : Societe privee a responsabilite limitee', $contentStyle);

$section->addTextBreak();

$section->addText('Coordonnees contact :', $strongStyle);
$section->addText('Telephone : +32 X XX XX XX XX', $contentStyle);
$section->addText('Email : contact@farmshop.be', $contentStyle);
$section->addText('Formulaire contact securise avec accusation reception', $contentStyle);
$section->addText('Heures ouverture service client', $contentStyle);

$section->addTextBreak();

$section->addText('Autorisations et assurances :', $strongStyle);
$section->addText('Assurance responsabilite civile professionnelle', $contentStyle);
$section->addText('Numero police assurance et coordonnees assureur', $contentStyle);
$section->addText('Autorisations specifiques (AFSCA le cas echeant)', $contentStyle);

$section->addTextBreak();

$section->addTitle('6.2 CGV/CGU adaptees vente et location', 2);
$section->addTextBreak();

$section->addText('Structure contractuelle duale implementee :', $strongStyle);
$section->addTextBreak();

$section->addText('Conditions Generales Utilisation (CGU) :', $strongStyle);
$section->addText('Acces et utilisation plateforme FarmShop', $contentStyle);
$section->addText('Creation compte utilisateur et verification identite', $contentStyle);
$section->addText('Regles publication avis et commentaires', $contentStyle);
$section->addText('Interdictions usage (revente, concurrence)', $contentStyle);
$section->addText('Responsabilite utilisateur et sanctions', $contentStyle);

$section->addTextBreak();

$section->addText('Conditions Generales Vente (CGV) :', $strongStyle);
$section->addText('Formation contrat vente et validation commande', $contentStyle);
$section->addText('Prix, taxes, frais annexes et modalites paiement', $contentStyle);
$section->addText('Livraison, transfert propriete et risques', $contentStyle);
$section->addText('Garanties constructeur et service apres-vente', $contentStyle);
$section->addText('Droit retractation et procedure retour', $contentStyle);

$section->addTextBreak();

$section->addText('Conditions Generales Location (CGL) :', $strongStyle);
$section->addText('Formation contrat location et duree engagement', $contentStyle);
$section->addText('Tarification, caution et modalites paiement', $contentStyle);
$section->addText('Etat materiel, entretien et responsabilite utilisateur', $contentStyle);
$section->addText('Assurance obligatoire et franchise dommages', $contentStyle);
$section->addText('Conditions restitution et penalites', $contentStyle);

$section->addTextBreak();

$section->addTitle('6.3 Procedures conformite GDPR', 2);
$section->addTextBreak();

$section->addText('Organisation interne protection donnees :', $strongStyle);
$section->addText('Designation Data Protection Officer (DPO) interne', $contentStyle);
$section->addText('Formation equipe sur obligations GDPR', $contentStyle);
$section->addText('Procedures internes signalement violations donnees', $contentStyle);
$section->addText('Audit annuel conformite avec consultant externe', $contentStyle);

$section->addTextBreak();

$section->addText('Documentation conformite obligatoire :', $strongStyle);
$section->addText('Registre activites traitement (Art. 30 GDPR)', $contentStyle);
$section->addText('Analyse impact protection donnees (AIPD) si necessaire', $contentStyle);
$section->addText('Contrats sous-traitance avec clauses GDPR (AWS, Stripe)', $contentStyle);
$section->addText('Procedures notification violations dans 72h', $contentStyle);

$section->addTextBreak();

$section->addTitle('6.4 Registre traitements donnees', 2);
$section->addTextBreak();

$section->addText('Traitements declares registre FarmShop :', $strongStyle);
$section->addTextBreak();

// Tableau registre traitements
$registreTable = $section->addTable(['borderSize' => 6, 'borderColor' => '999999']);
$registreTable->addRow();
$registreTable->addCell(2000)->addText('Traitement', $strongStyle);
$registreTable->addCell(2000)->addText('Finalite', $strongStyle);
$registreTable->addCell(2000)->addText('Base legale', $strongStyle);
$registreTable->addCell(2000)->addText('Conservation', $strongStyle);
$registreTable->addCell(1000)->addText('Transfert', $strongStyle);

$registreTable->addRow();
$registreTable->addCell(2000)->addText('Gestion comptes', $contentStyle);
$registreTable->addCell(2000)->addText('Authentification', $contentStyle);
$registreTable->addCell(2000)->addText('Execution contrat', $contentStyle);
$registreTable->addCell(2000)->addText('3 ans inactivite', $contentStyle);
$registreTable->addCell(1000)->addText('Non', $contentStyle);

$registreTable->addRow();
$registreTable->addCell(2000)->addText('Gestion commandes', $contentStyle);
$registreTable->addCell(2000)->addText('Execution vente/location', $contentStyle);
$registreTable->addCell(2000)->addText('Execution contrat', $contentStyle);
$registreTable->addCell(2000)->addText('10 ans comptables', $contentStyle);
$registreTable->addCell(1000)->addText('Non', $contentStyle);

$registreTable->addRow();
$registreTable->addCell(2000)->addText('Paiements', $contentStyle);
$registreTable->addCell(2000)->addText('Transaction financiere', $contentStyle);
$registreTable->addCell(2000)->addText('Obligation legale', $contentStyle);
$registreTable->addCell(2000)->addText('10 ans', $contentStyle);
$registreTable->addCell(1000)->addText('Stripe', $contentStyle);

$registreTable->addRow();
$registreTable->addCell(2000)->addText('Marketing', $contentStyle);
$registreTable->addCell(2000)->addText('Communication commerciale', $contentStyle);
$registreTable->addCell(2000)->addText('Consentement', $contentStyle);
$registreTable->addCell(2000)->addText('Retrait consentement', $contentStyle);
$registreTable->addCell(1000)->addText('Non', $contentStyle);

$registreTable->addRow();
$registreTable->addCell(2000)->addText('Analytics', $contentStyle);
$registreTable->addCell(2000)->addText('Amelioration service', $contentStyle);
$registreTable->addCell(2000)->addText('Interet legitime', $contentStyle);
$registreTable->addCell(2000)->addText('26 mois', $contentStyle);
$registreTable->addCell(1000)->addText('Google', $contentStyle);

// Nouvelle page
$section->addPageBreak();

// 7. GESTION RISQUES
$section->addTitle('7. Gestion des risques juridiques', 1);
$section->addTextBreak();

$section->addTitle('7.1 Assurances professionnelles', 2);
$section->addTextBreak();

$section->addText('Couvertures assurance obligatoires :', $strongStyle);
$section->addTextBreak();

$section->addText('Responsabilite civile professionnelle :', $strongStyle);
$section->addText('Montant couverture : minimum 1.250.000€', $contentStyle);
$section->addText('Activites couvertes : vente, location, conseil materiel agricole', $contentStyle);
$section->addText('Exclusions : usage professionnel materiel par locataires', $contentStyle);
$section->addText('Franchise : 500€ par sinistre', $contentStyle);

$section->addTextBreak();

$section->addText('Assurance produits et rappels :', $strongStyle);
$section->addText('Couverture defauts produits vendus', $contentStyle);
$section->addText('Frais rappel et destruction produits non-conformes', $contentStyle);
$section->addText('Protection juridique procedures AFSCA', $contentStyle);

$section->addTextBreak();

$section->addText('Cyber-assurance :', $strongStyle);
$section->addText('Protection violations donnees personnelles', $contentStyle);
$section->addText('Frais notification autorites et personnes concernees', $contentStyle);
$section->addText('Couverture amendes GDPR (selon police)', $contentStyle);
$section->addText('Assistance juridique contentieux numerique', $contentStyle);

$section->addTextBreak();

$section->addTitle('7.2 Procedures contentieux client', 2);
$section->addTextBreak();

$section->addText('Resolution amiable differends :', $strongStyle);
$section->addText('Service client dedie avec procedure escalade', $contentStyle);
$section->addText('Mediateur consommation agrees (accord secteur)', $contentStyle);
$section->addText('Procedure mediation gratuite pour consommateurs', $contentStyle);
$section->addText('Respect delais reponse legaux (15 jours)', $contentStyle);

$section->addTextBreak();

$section->addText('Contentieux juridictionnel :', $strongStyle);
$section->addText('Competence tribunaux Brussels (siege social)', $contentStyle);
$section->addText('Exception : competence domicile consommateur (vente B2C)', $contentStyle);
$section->addText('Droit applicable : droit belge exclusivement', $contentStyle);
$section->addText('Procedures recouvrement creances avec avocat', $contentStyle);

$section->addTextBreak();

$section->addTitle('7.3 Veille reglementaire continue', 2);
$section->addTextBreak();

$section->addText('Sources veille juridique :', $strongStyle);
$section->addText('Moniteur belge pour nouvelles reglementations', $contentStyle);
$section->addText('Journal officiel UE pour directives europeennes', $contentStyle);
$section->addText('AFSCA circulaires et communications sectorielles', $contentStyle);
$section->addText('Autorite protection donnees (APD) - orientations GDPR', $contentStyle);
$section->addText('Federation secteur (FWA, Boerenbond) actualites juridiques', $contentStyle);

$section->addTextBreak();

$section->addText('Procedures mise a jour conformite :', $strongStyle);
$section->addText('Revue trimestrielle CGV/CGU avec juriste', $contentStyle);
$section->addText('Audit annuel conformite GDPR', $contentStyle);
$section->addText('Formation continue equipe evolution reglementaire', $contentStyle);
$section->addText('Tests procedures internes incidents donnees', $contentStyle);

// Nouvelle page
$section->addPageBreak();

// CONCLUSION
$section->addTitle('Conclusion', 1);
$section->addTextBreak();

$section->addText('L\'analyse juridique de FarmShop revele un environnement reglementaire complexe necessitant une approche proactive et multidisciplinaire. L\'intersection entre e-commerce, secteur agricole et protection des donnees cree des obligations specifiques exigeant une veille constante et des procedures rigoureuses.', $contentStyle);

$section->addTextBreak();

$section->addText('Les principaux defis juridiques identifies concernent la conformite GDPR avec implementation technique appropriee, le respect des obligations sectorielles AFSCA pour produits alimentaires, et l\'adaptation du droit de la consommation au modele dual vente/location de FarmShop.', $contentStyle);

$section->addTextBreak();

$section->addText('Les mesures preventives implementees (CGV adaptees, procedures GDPR, assurances adequates, veille reglementaire) etablissent un cadre de conformite solide. Neanmoins, l\'evolution rapide du droit numerique et des reglementations sectorielles impose une vigilance permanente et des adaptations regulieres.', $contentStyle);

$section->addTextBreak();

$section->addText('Le succes juridique de FarmShop repose sur l\'equilibre entre innovation technologique et respect scrupuleux des obligations legales, garantissant une relation de confiance durable avec clients et autorites de controle.', $contentStyle);

$section->addTextBreak(4);

// Footer academique
$section->addText('Document genere le 17 juillet 2025', [
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
$objWriter->save(__DIR__ . '/18_Aspects_Juridiques_Cadre_Legal.docx');

echo "Livrable 18 - Aspects juridiques et cadre legal cree avec succes !\n";
echo "Emplacement : " . __DIR__ . "/18_Aspects_Juridiques_Cadre_Legal.docx\n";
echo "Document complet avec GDPR, AFSCA, droit consommation et e-commerce !\n";

?>
