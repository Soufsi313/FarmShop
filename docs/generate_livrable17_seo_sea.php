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

$section->addText('LIVRABLE 17', [
    'name' => 'Inter', 'size' => 20, 'bold' => true, 'lang' => 'fr-BE'
], ['alignment' => Jc::CENTER]);

$section->addText('STRATEGIE DE REFERENCEMENT (SEO / SEA)', [
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
$section->addText('1. Analyse du marche et positionnement SEO', $contentStyle);
$section->addText('1.1 Etude de la concurrence agricole en ligne', $contentStyle);
$section->addText('1.2 Analyse des mots-cles strategiques', $contentStyle);
$section->addText('1.3 Positionnement FarmShop sur le marche', $contentStyle);
$section->addText('2. SEO - Optimisation pour les moteurs de recherche', $contentStyle);
$section->addText('2.1 SEO technique avec Laravel 11', $contentStyle);
$section->addText('2.2 Optimisation on-page', $contentStyle);
$section->addText('2.3 Strategie de contenu et blog', $contentStyle);
$section->addText('2.4 SEO local pour l\'agriculture', $contentStyle);
$section->addText('2.5 Schema markup et donnees structurees', $contentStyle);
$section->addText('3. SEA - Publicite payante et Google Ads', $contentStyle);
$section->addText('3.1 Strategie Google Ads pour e-commerce agricole', $contentStyle);
$section->addText('3.2 Campagnes Shopping pour produits agricoles', $contentStyle);
$section->addText('3.3 Remarketing et audiences personnalisees', $contentStyle);
$section->addText('3.4 Budget et ROI publicitaire', $contentStyle);
$section->addText('4. SMO - Optimisation pour les reseaux sociaux', $contentStyle);
$section->addText('4.1 Strategie multi-plateformes', $contentStyle);
$section->addText('4.2 Contenu viral et engagement agricole', $contentStyle);
$section->addText('4.3 Influenceurs et partenariats agricoles', $contentStyle);
$section->addText('5. Implementation technique Laravel', $contentStyle);
$section->addText('5.1 Packages SEO Laravel implementes', $contentStyle);
$section->addText('5.2 Sitemap XML automatique', $contentStyle);
$section->addText('5.3 Robots.txt et directives crawl', $contentStyle);
$section->addText('5.4 Performance et Core Web Vitals', $contentStyle);
$section->addText('6. Mesure et analytics', $contentStyle);
$section->addText('6.1 Google Analytics 4 configuration', $contentStyle);
$section->addText('6.2 Google Search Console monitoring', $contentStyle);
$section->addText('6.3 KPIs et tableaux de bord', $contentStyle);
$section->addText('7. Strategie a long terme', $contentStyle);
$section->addText('7.1 Roadmap SEO 12 mois', $contentStyle);
$section->addText('7.2 Evolution et adaptation', $contentStyle);
$section->addText('7.3 Budget et ressources', $contentStyle);
$section->addText('Conclusion', $contentStyle);
$section->addText('Bibliographie', $contentStyle);

// Nouvelle page
$section->addPageBreak();

// INTRODUCTION
$section->addTitle('Introduction', 1);
$section->addTextBreak();

$section->addText('Le referencement naturel et payant constitue un enjeu strategique majeur pour FarmShop, plateforme e-commerce specialisee dans la vente et location de produits agricoles. Dans un secteur ou la digitalisation s\'accelere, une visibilite optimale sur les moteurs de recherche determine directement le succes commercial', $contentStyle);
$section->addFootnote()->addText('GOOGLE. S.d. Google Search Central Documentation. Site web sur INTERNET. <developers.google.com/search>. Derniere consultation : le 17/07-2025.', ['name' => 'Inter', 'size' => 9, 'lang' => 'fr-BE']);
$section->addText('.', $contentStyle);

$section->addTextBreak();

$section->addText('Cette strategie SEM (Search Engine Marketing)', $contentStyle);
$section->addFootnote()->addText('MOZ. S.d. SEO Learning Center - What is SEM. Site web sur INTERNET. <moz.com/learn/seo>. Derniere consultation : le 17/07-2025.', ['name' => 'Inter', 'size' => 9, 'lang' => 'fr-BE']);
$section->addText(' adopte une approche holistique combinant SEO (referencement naturel), SEA (publicite payante) et SMO (optimisation reseaux sociaux) pour maximiser la presence digitale de FarmShop sur son marche cible.', $contentStyle);

$section->addTextBreak();

$section->addText('L\'objectif est d\'etablir FarmShop comme reference incontournable dans l\'e-commerce agricole belge et europeen, en capitalisant sur les specificites du secteur : saisonnalite, localisation, expertise technique et confiance client.', $contentStyle);

$section->addTextBreak(2);

// 1. ANALYSE MARCHE
$section->addTitle('1. Analyse du marche et positionnement SEO', 1);
$section->addTextBreak();

$section->addTitle('1.1 Etude de la concurrence agricole en ligne', 2);
$section->addTextBreak();

$section->addText('Concurrents directs identifies :', $strongStyle);
$section->addTextBreak();

// Tableau concurrence
$concurrenceTable = $section->addTable(['borderSize' => 6, 'borderColor' => '999999']);
$concurrenceTable->addRow();
$concurrenceTable->addCell(2500)->addText('Concurrent', $strongStyle);
$concurrenceTable->addCell(2000)->addText('DA/PA Score', $strongStyle);
$concurrenceTable->addCell(2500)->addText('Forces SEO', $strongStyle);
$concurrenceTable->addCell(2000)->addText('Faiblesses', $strongStyle);

$concurrenceTable->addRow();
$concurrenceTable->addCell(2500)->addText('AgriAffaires.com', $contentStyle);
$concurrenceTable->addCell(2000)->addText('DA: 65/100', $contentStyle);
$concurrenceTable->addCell(2500)->addText('Autorite domaine etablie', $contentStyle);
$concurrenceTable->addCell(2000)->addText('UX mobile faible', $contentStyle);

$concurrenceTable->addRow();
$concurrenceTable->addCell(2500)->addText('Terre-net.fr', $contentStyle);
$concurrenceTable->addCell(2000)->addText('DA: 58/100', $contentStyle);
$concurrenceTable->addCell(2500)->addText('Contenu editorial riche', $contentStyle);
$concurrenceTable->addCell(2000)->addText('E-commerce limite', $contentStyle);

$concurrenceTable->addRow();
$concurrenceTable->addCell(2500)->addText('Agrizone.be', $contentStyle);
$concurrenceTable->addCell(2000)->addText('DA: 42/100', $contentStyle);
$concurrenceTable->addCell(2500)->addText('Focus marche belge', $contentStyle);
$concurrenceTable->addCell(2000)->addText('SEO technique faible', $contentStyle);

$concurrenceTable->addRow();
$concurrenceTable->addCell(2500)->addText('FarmShop (cible)', $contentStyle);
$concurrenceTable->addCell(2000)->addText('DA: 0/100', $contentStyle);
$concurrenceTable->addCell(2500)->addText('Tech moderne, UX optimale', $contentStyle);
$concurrenceTable->addCell(2000)->addText('Nouveau domaine', $contentStyle);

$section->addTextBreak();

$section->addTitle('1.2 Analyse des mots-cles strategiques', 2);
$section->addTextBreak();

$section->addText('Recherche de mots-cles par categorie', $contentStyle);
$section->addFootnote()->addText('SEMRUSH. S.d. Keyword Magic Tool. Site web sur INTERNET. <semrush.com>. Derniere consultation : le 17/07-2025.', ['name' => 'Inter', 'size' => 9, 'lang' => 'fr-BE']);
$section->addText(' :', $strongStyle);
$section->addTextBreak();

// Tableau mots-clés
$keywordsTable = $section->addTable(['borderSize' => 6, 'borderColor' => '999999']);
$keywordsTable->addRow();
$keywordsTable->addCell(3000)->addText('Mot-cle principal', $strongStyle);
$keywordsTable->addCell(1500)->addText('Volume/mois', $strongStyle);
$keywordsTable->addCell(1500)->addText('Difficulte', $strongStyle);
$keywordsTable->addCell(3000)->addText('Intention recherche', $strongStyle);

$keywordsTable->addRow();
$keywordsTable->addCell(3000)->addText('materiel agricole occasion', $contentStyle);
$keywordsTable->addCell(1500)->addText('8 900', $contentStyle);
$keywordsTable->addCell(1500)->addText('65/100', $contentStyle);
$keywordsTable->addCell(3000)->addText('Commerciale - Achat', $contentStyle);

$keywordsTable->addRow();
$keywordsTable->addCell(3000)->addText('location tracteur belgique', $contentStyle);
$keywordsTable->addCell(1500)->addText('2 400', $contentStyle);
$keywordsTable->addCell(1500)->addText('45/100', $contentStyle);
$keywordsTable->addCell(3000)->addText('Commerciale - Location', $contentStyle);

$keywordsTable->addRow();
$keywordsTable->addCell(3000)->addText('outils jardinage professionnel', $contentStyle);
$keywordsTable->addCell(1500)->addText('5 600', $contentStyle);
$keywordsTable->addCell(1500)->addText('52/100', $contentStyle);
$keywordsTable->addCell(3000)->addText('Informationnelle/Commerciale', $contentStyle);

$keywordsTable->addRow();
$keywordsTable->addCell(3000)->addText('semences bio certifiees', $contentStyle);
$keywordsTable->addCell(1500)->addText('3 200', $contentStyle);
$keywordsTable->addCell(1500)->addText('38/100', $contentStyle);
$keywordsTable->addCell(3000)->addText('Commerciale - Niche', $contentStyle);

$keywordsTable->addRow();
$keywordsTable->addCell(3000)->addText('engrais naturel agriculture', $contentStyle);
$keywordsTable->addCell(1500)->addText('4 100', $contentStyle);
$keywordsTable->addCell(1500)->addText('42/100', $contentStyle);
$keywordsTable->addCell(3000)->addText('Informationnelle/Commerciale', $contentStyle);

$section->addTextBreak();

$section->addTitle('1.3 Positionnement FarmShop sur le marche', 2);
$section->addTextBreak();

$section->addText('Avantages concurrentiels SEO :', $strongStyle);
$section->addText('Architecture technique moderne Laravel 11', $contentStyle);
$section->addText('Performance superieure (Core Web Vitals optimises)', $contentStyle);
$section->addText('Experience utilisateur mobile-first', $contentStyle);
$section->addText('Systeme dual achat/location unique', $contentStyle);
$section->addText('Contenu editorial de qualite (blog agricole)', $contentStyle);
$section->addText('Integration parfaite e-commerce/SEO', $contentStyle);

$section->addTextBreak();

$section->addText('Strategie de rattrapage :', $strongStyle);
$section->addText('Focus mots-cles longue traine moins concurrentiels', $contentStyle);
$section->addText('Content marketing expertise agricole', $contentStyle);
$section->addText('SEO local pour marche belge/europeen', $contentStyle);
$section->addText('Link building partnerships secteur agricole', $contentStyle);

// Nouvelle page
$section->addPageBreak();

// 2. SEO
$section->addTitle('2. SEO - Optimisation pour les moteurs de recherche', 1);
$section->addTextBreak();

$section->addTitle('2.1 SEO technique avec Laravel 11', 2);
$section->addTextBreak();

$section->addText('Optimisations techniques implementees :', $strongStyle);
$section->addTextBreak();

$section->addText('Performance et vitesse :', $strongStyle);
$section->addText('Vite.js pour bundling optimise des assets', $contentStyle);
$section->addText('Lazy loading images avec Intersection Observer', $contentStyle);
$section->addText('Cache Redis pour requetes base de donnees', $contentStyle);
$section->addText('Compression Gzip/Brotli automatique', $contentStyle);
$section->addText('CDN Cloudflare pour distribution globale', $contentStyle);

$section->addTextBreak();

$section->addText('Architecture SEO-friendly :', $strongStyle);
$section->addText('URLs semantiques avec Laravel Route Model Binding', $contentStyle);
$section->addText('Structure HTML5 semantique avec schema.org', $contentStyle);
$section->addFootnote()->addText('SCHEMA.ORG. S.d. Schema.org Structured Data Documentation. Site web sur INTERNET. <schema.org>. Derniere consultation : le 17/07-2025.', ['name' => 'Inter', 'size' => 9, 'lang' => 'fr-BE']);
$section->addText('Meta tags dynamiques par page/produit', $contentStyle);
$section->addText('Breadcrumbs automatiques avec microdata', $contentStyle);
$section->addText('Pagination SEO avec rel="prev/next"', $contentStyle);

$section->addTextBreak();

$section->addTitle('2.2 Optimisation on-page', 2);
$section->addTextBreak();

$section->addText('Template de meta-donnees par type de page :', $strongStyle);
$section->addTextBreak();

// Tableau meta-données
$metaTable = $section->addTable(['borderSize' => 6, 'borderColor' => '999999']);
$metaTable->addRow();
$metaTable->addCell(2000)->addText('Type de page', $strongStyle);
$metaTable->addCell(3000)->addText('Title pattern', $strongStyle);
$metaTable->addCell(4000)->addText('Meta description pattern', $strongStyle);

$metaTable->addRow();
$metaTable->addCell(2000)->addText('Page produit', $contentStyle);
$metaTable->addCell(3000)->addText('[Nom produit] - [Categorie] | FarmShop', $contentStyle);
$metaTable->addCell(4000)->addText('[Description courte]. Prix: [Prix]. Livraison rapide. [CTA]', $contentStyle);

$metaTable->addRow();
$metaTable->addCell(2000)->addText('Categorie', $contentStyle);
$metaTable->addCell(3000)->addText('[Nom categorie] - [Nb produits] produits | FarmShop', $contentStyle);
$metaTable->addCell(4000)->addText('Decouvrez notre selection de [categorie]. [Nb] produits disponibles...', $contentStyle);

$metaTable->addRow();
$metaTable->addCell(2000)->addText('Article blog', $contentStyle);
$metaTable->addCell(3000)->addText('[Titre article] | Blog FarmShop', $contentStyle);
$metaTable->addCell(4000)->addText('[Resume article en 150 caracteres avec mot-cle principal]', $contentStyle);

$metaTable->addRow();
$metaTable->addCell(2000)->addText('Page location', $contentStyle);
$metaTable->addCell(3000)->addText('Location [Equipement] - [Ville] | FarmShop', $contentStyle);
$metaTable->addCell(4000)->addText('Louez [equipement] a [ville]. Tarifs competitifs, service professionnel...', $contentStyle);

$section->addTextBreak();

$section->addTitle('2.3 Strategie de contenu et blog', 2);
$section->addTextBreak();

$section->addText('Ligne editoriale blog agricole :', $strongStyle);
$section->addTextBreak();

$section->addText('Categories de contenu :', $strongStyle);
$section->addText('Guides techniques agriculture (how-to detailles)', $contentStyle);
$section->addText('Actualites secteur et innovations', $contentStyle);
$section->addText('Comparatifs produits et equipements', $contentStyle);
$section->addText('Temoignages clients et cas d\'usage', $contentStyle);
$section->addText('Conseils saisonniers et planning agricole', $contentStyle);

$section->addTextBreak();

$section->addText('Calendrier editorial type :', $strongStyle);
$section->addText('Lundi : Guide technique (2000+ mots)', $contentStyle);
$section->addText('Mercredi : Actualites secteur (800-1200 mots)', $contentStyle);
$section->addText('Vendredi : Comparatif produits (1500+ mots)', $contentStyle);
$section->addText('Publication supplementaire selon actualite', $contentStyle);

$section->addTextBreak();

$section->addTitle('2.4 SEO local pour l\'agriculture', 2);
$section->addTextBreak();

$section->addText('Optimisation geographique :', $strongStyle);
$section->addText('Google My Business profile complet et optimise', $contentStyle);
$section->addText('Pages de destination par region/province', $contentStyle);
$section->addText('Schema markup LocalBusiness implement', $contentStyle);
$section->addText('Citations locales annuaires agricoles', $contentStyle);
$section->addText('Avis clients geolocalises encourages', $contentStyle);

$section->addTextBreak();

$section->addTitle('2.5 Schema markup et donnees structurees', 2);
$section->addTextBreak();

$section->addText('Types de schema implementes :', $strongStyle);
$section->addText('Product schema pour chaque produit', $contentStyle);
$section->addText('Organization schema pour FarmShop', $contentStyle);
$section->addText('BreadcrumbList pour navigation', $contentStyle);
$section->addText('Review/AggregateRating pour avis', $contentStyle);
$section->addText('FAQPage pour pages questions frequentes', $contentStyle);
$section->addText('LocalBusiness pour presence locale', $contentStyle);

// Nouvelle page
$section->addPageBreak();

// 3. SEA
$section->addTitle('3. SEA - Publicite payante et Google Ads', 1);
$section->addTextBreak();

$section->addTitle('3.1 Strategie Google Ads pour e-commerce agricole', 2);
$section->addTextBreak();

$section->addText('Structure de campagnes', $contentStyle);
$section->addFootnote()->addText('GOOGLE. S.d. Google Ads Help Center. Site web sur INTERNET. <support.google.com/google-ads>. Derniere consultation : le 17/07-2025.', ['name' => 'Inter', 'size' => 9, 'lang' => 'fr-BE']);
$section->addText(' :', $strongStyle);
$section->addTextBreak();

// Tableau campagnes Google Ads
$adsTable = $section->addTable(['borderSize' => 6, 'borderColor' => '999999']);
$adsTable->addRow();
$adsTable->addCell(2000)->addText('Type campagne', $strongStyle);
$adsTable->addCell(2500)->addText('Objectif', $strongStyle);
$adsTable->addCell(2000)->addText('Budget/jour', $strongStyle);
$adsTable->addCell(2500)->addText('Mots-cles cibles', $strongStyle);

$adsTable->addRow();
$adsTable->addCell(2000)->addText('Search Brand', $contentStyle);
$adsTable->addCell(2500)->addText('Protection marque', $contentStyle);
$adsTable->addCell(2000)->addText('20€', $contentStyle);
$adsTable->addCell(2500)->addText('farmshop, farm shop', $contentStyle);

$adsTable->addRow();
$adsTable->addCell(2000)->addText('Search Achat', $contentStyle);
$adsTable->addCell(2500)->addText('Acquisitions ventes', $contentStyle);
$adsTable->addCell(2000)->addText('150€', $contentStyle);
$adsTable->addCell(2500)->addText('acheter, materiel agricole', $contentStyle);

$adsTable->addRow();
$adsTable->addCell(2000)->addText('Search Location', $contentStyle);
$adsTable->addCell(2500)->addText('Acquisitions locations', $contentStyle);
$adsTable->addCell(2000)->addText('100€', $contentStyle);
$adsTable->addCell(2500)->addText('louer, location tracteur', $contentStyle);

$adsTable->addRow();
$adsTable->addCell(2000)->addText('Shopping', $contentStyle);
$adsTable->addCell(2500)->addText('Visibilite produits', $contentStyle);
$adsTable->addCell(2000)->addText('200€', $contentStyle);
$adsTable->addCell(2500)->addText('Flux produits Google Merchant', $contentStyle);

$adsTable->addRow();
$adsTable->addCell(2000)->addText('Display Remarketing', $contentStyle);
$adsTable->addCell(2500)->addText('Reconversion visiteurs', $contentStyle);
$adsTable->addCell(2000)->addText('80€', $contentStyle);
$adsTable->addCell(2500)->addText('Audiences personnalisees', $contentStyle);

$section->addTextBreak();

$section->addTitle('3.2 Campagnes Shopping pour produits agricoles', 2);
$section->addTextBreak();

$section->addText('Configuration Google Merchant Center', $contentStyle);
$section->addFootnote()->addText('GOOGLE. S.d. Google Merchant Center Help. Site web sur INTERNET. <support.google.com/merchants>. Derniere consultation : le 17/07-2025.', ['name' => 'Inter', 'size' => 9, 'lang' => 'fr-BE']);
$section->addText(' :', $strongStyle);
$section->addText('Flux produits automatise via Laravel', $contentStyle);
$section->addText('Categories Google Shopping mappees', $contentStyle);
$section->addText('Images haute qualite optimisees', $contentStyle);
$section->addText('Prix competitifs mis a jour temps reel', $contentStyle);
$section->addText('Attributs specifiques agriculture (saison, usage)', $contentStyle);

$section->addTextBreak();

$section->addTitle('3.3 Remarketing et audiences personnalisees', 2);
$section->addTextBreak();

$section->addText('Segments d\'audience strategiques :', $strongStyle);
$section->addText('Visiteurs pages produits sans achat (30 jours)', $contentStyle);
$section->addText('Paniers abandonnes avec email (7 jours)', $contentStyle);
$section->addText('Clients ayant achete categories specifiques', $contentStyle);
$section->addText('Visiteurs blog articles techniques', $contentStyle);
$section->addText('Similaires aux meilleurs clients (lookalike)', $contentStyle);

$section->addTextBreak();

$section->addTitle('3.4 Budget et ROI publicitaire', 2);
$section->addTextBreak();

$section->addText('Repartition budget mensuel (1650€) :', $strongStyle);
$section->addText('Google Ads Search : 60% (990€)', $contentStyle);
$section->addText('Google Shopping : 25% (412€)', $contentStyle);
$section->addText('Display/Remarketing : 10% (165€)', $contentStyle);
$section->addText('Tests nouveaux canaux : 5% (83€)', $contentStyle);

$section->addTextBreak();

$section->addText('KPIs et objectifs ROI :', $strongStyle);
$section->addText('ROAS cible : 4:1 minimum (400% retour)', $contentStyle);
$section->addText('CPA acquisition : 25€ maximum', $contentStyle);
$section->addText('CTR moyen : 3%+ pour search, 0.8%+ display', $contentStyle);
$section->addText('Quality Score : 7+ pour 80% des mots-cles', $contentStyle);

// Nouvelle page
$section->addPageBreak();

// 4. SMO
$section->addTitle('4. SMO - Optimisation pour les reseaux sociaux', 1);
$section->addTextBreak();

$section->addTitle('4.1 Strategie multi-plateformes', 2);
$section->addTextBreak();

// Tableau réseaux sociaux
$socialTable = $section->addTable(['borderSize' => 6, 'borderColor' => '999999']);
$socialTable->addRow();
$socialTable->addCell(2000)->addText('Plateforme', $strongStyle);
$socialTable->addCell(2000)->addText('Objectif principal', $strongStyle);
$socialTable->addCell(2500)->addText('Type de contenu', $strongStyle);
$socialTable->addCell(2500)->addText('Frequence publication', $strongStyle);

$socialTable->addRow();
$socialTable->addCell(2000)->addText('Facebook', $contentStyle);
$socialTable->addCell(2000)->addText('Community building', $contentStyle);
$socialTable->addCell(2500)->addText('Conseils, actualites, videos', $contentStyle);
$socialTable->addCell(2500)->addText('1 post/jour', $contentStyle);

$socialTable->addRow();
$socialTable->addCell(2000)->addText('Instagram', $contentStyle);
$socialTable->addCell(2000)->addText('Branding visuel', $contentStyle);
$socialTable->addCell(2500)->addText('Photos produits, behind-scenes', $contentStyle);
$socialTable->addCell(2500)->addText('5-7 posts/semaine', $contentStyle);

$socialTable->addRow();
$socialTable->addCell(2000)->addText('YouTube', $contentStyle);
$socialTable->addCell(2000)->addText('Education technique', $contentStyle);
$socialTable->addCell(2500)->addText('Tutoriels, demonstrations', $contentStyle);
$socialTable->addCell(2500)->addText('2 videos/semaine', $contentStyle);

$socialTable->addRow();
$socialTable->addCell(2000)->addText('LinkedIn', $contentStyle);
$socialTable->addCell(2000)->addText('B2B networking', $contentStyle);
$socialTable->addCell(2500)->addText('Articles expertise, actualites', $contentStyle);
$socialTable->addCell(2500)->addText('3 posts/semaine', $contentStyle);

$socialTable->addRow();
$socialTable->addCell(2000)->addText('TikTok', $contentStyle);
$socialTable->addCell(2000)->addText('Jeune audience', $contentStyle);
$socialTable->addCell(2500)->addText('Videos courtes, tendances', $contentStyle);
$socialTable->addCell(2500)->addText('3-5 videos/semaine', $contentStyle);

$section->addTextBreak();

$section->addTitle('4.2 Contenu viral et engagement agricole', 2);
$section->addTextBreak();

$section->addText('Themes de contenu a fort potentiel :', $strongStyle);
$section->addText('Avant/apres transformations exploitations', $contentStyle);
$section->addText('Time-lapse travaux agricoles saisonniers', $contentStyle);
$section->addText('Fails/succes humouristiques agricoles', $contentStyle);
$section->addText('Innovations technologiques surprenantes', $contentStyle);
$section->addText('Defis et challenges communaute', $contentStyle);

$section->addTextBreak();

$section->addTitle('4.3 Influenceurs et partenariats agricoles', 2);
$section->addTextBreak();

$section->addText('Categories d\'influenceurs cibles :', $strongStyle);
$section->addText('Agriculteurs avec forte presence sociale (10K+ followers)', $contentStyle);
$section->addText('Youtubeurs specialises agriculture/jardinage', $contentStyle);
$section->addText('Experts techniques et formateurs', $contentStyle);
$section->addText('Journalistes secteur agricole', $contentStyle);

$section->addTextBreak();

$section->addText('Types de collaborations :', $strongStyle);
$section->addText('Tests produits authentiques avec avis', $contentStyle);
$section->addText('Sponsoring contenu educatif', $contentStyle);
$section->addText('Partenariats evenements agricoles', $contentStyle);
$section->addText('Co-creation contenu technique', $contentStyle);

// Nouvelle page
$section->addPageBreak();

// 5. IMPLEMENTATION TECHNIQUE
$section->addTitle('5. Implementation technique Laravel', 1);
$section->addTextBreak();

$section->addTitle('5.1 Packages SEO Laravel implementes', 2);
$section->addTextBreak();

$section->addText('Packages SEO installes', $contentStyle);
$section->addFootnote()->addText('SPATIE. S.d. Laravel SEO Tools Package. Site web sur INTERNET. <spatie.be/docs/laravel-sitemap>. Derniere consultation : le 17/07-2025.', ['name' => 'Inter', 'size' => 9, 'lang' => 'fr-BE']);
$section->addText(' :', $strongStyle);
$section->addText('spatie/laravel-sitemap : Generation sitemap XML automatique', $contentStyle);
$section->addText('artesaos/seotools : Meta tags dynamiques par page', $contentStyle);
$section->addText('spatie/schema-org : Schema markup structure', $contentStyle);
$section->addText('romanzipp/laravel-seo : SEO metadata management', $contentStyle);
$section->addText('Custom middleware : Redirections 301 et canonicals', $contentStyle);

$section->addTextBreak();

$section->addText('Configuration type meta tags :', $strongStyle);
$section->addText('Route::get(\'/produit/{slug}\', function($slug) {', $codeStyle);
$section->addText('  SEOMeta::setTitle($product->name . \' | FarmShop\');', $codeStyle);
$section->addText('  SEOMeta::setDescription($product->seo_description);', $codeStyle);
$section->addText('  SEOMeta::setCanonical(url()->current());', $codeStyle);
$section->addText('  OpenGraph::setTitle($product->name);', $codeStyle);
$section->addText('  OpenGraph::setImage($product->image_url);', $codeStyle);
$section->addText('});', $codeStyle);

$section->addTextBreak();

$section->addTitle('5.2 Sitemap XML automatique', 2);
$section->addTextBreak();

$section->addText('Generation sitemap dynamique :', $strongStyle);
$section->addText('Sitemap produits : mise a jour quotidienne', $contentStyle);
$section->addText('Sitemap categories : mise a jour lors modifications', $contentStyle);
$section->addText('Sitemap blog : mise a jour lors nouvelles publications', $contentStyle);
$section->addText('Sitemap pages statiques : manuel', $contentStyle);
$section->addText('Index sitemap : aggregation de tous les sitemaps', $contentStyle);

$section->addTextBreak();

$section->addTitle('5.3 Robots.txt et directives crawl', 2);
$section->addTextBreak();

$section->addText('Configuration robots.txt optimisee :', $strongStyle);
$section->addText('User-agent: *', $codeStyle);
$section->addText('Allow: /', $codeStyle);
$section->addText('Disallow: /admin/', $codeStyle);
$section->addText('Disallow: /api/', $codeStyle);
$section->addText('Disallow: /cart/', $codeStyle);
$section->addText('Disallow: /*?sort=', $codeStyle);
$section->addText('Disallow: /*?filter=', $codeStyle);
$section->addText('Sitemap: https://farmshop.be/sitemap.xml', $codeStyle);

$section->addTextBreak();

$section->addTitle('5.4 Performance et Core Web Vitals', 2);
$section->addTextBreak();

$section->addText('Optimisations performance implementees', $contentStyle);
$section->addFootnote()->addText('GOOGLE. S.d. Core Web Vitals Guide. Site web sur INTERNET. <web.dev/vitals>. Derniere consultation : le 17/07-2025.', ['name' => 'Inter', 'size' => 9, 'lang' => 'fr-BE']);
$section->addText(' :', $strongStyle);
$section->addText('Largest Contentful Paint (LCP) < 2.5s', $contentStyle);
$section->addText('- Images WebP avec fallback JPEG', $contentStyle);
$section->addText('- Critical CSS inline, autres CSS async', $contentStyle);
$section->addText('- Preload ressources critiques', $contentStyle);

$section->addTextBreak();

$section->addText('First Input Delay (FID) < 100ms', $contentStyle);
$section->addText('- JavaScript minifie et differencie', $contentStyle);
$section->addText('- Event listeners passifs', $contentStyle);
$section->addText('- Code splitting par route', $contentStyle);

$section->addTextBreak();

$section->addText('Cumulative Layout Shift (CLS) < 0.1', $contentStyle);
$section->addText('- Dimensions images specifiees', $contentStyle);
$section->addText('- Fonts preload avec font-display: swap', $contentStyle);
$section->addText('- Placeholders animations', $contentStyle);

// Nouvelle page
$section->addPageBreak();

// 6. MESURE ET ANALYTICS
$section->addTitle('6. Mesure et analytics', 1);
$section->addTextBreak();

$section->addTitle('6.1 Google Analytics 4 configuration', 2);
$section->addTextBreak();

$section->addText('Evenements personnalises e-commerce', $contentStyle);
$section->addFootnote()->addText('GOOGLE. S.d. Google Analytics 4 Documentation. Site web sur INTERNET. <developers.google.com/analytics/ga4>. Derniere consultation : le 17/07-2025.', ['name' => 'Inter', 'size' => 9, 'lang' => 'fr-BE']);
$section->addText(' :', $strongStyle);
$section->addText('view_item : consultation produit avec details', $contentStyle);
$section->addText('add_to_cart : ajout panier achat/location', $contentStyle);
$section->addText('begin_checkout : debut processus commande', $contentStyle);
$section->addText('purchase : finalisation achat', $contentStyle);
$section->addText('rental_inquiry : demande info location', $contentStyle);
$section->addText('blog_engagement : temps lecture articles', $contentStyle);

$section->addTextBreak();

$section->addText('Conversions et objectifs :', $strongStyle);
$section->addText('Objectif primaire : Achats (valeur monetaire)', $contentStyle);
$section->addText('Objectif secondaire : Demandes location', $contentStyle);
$section->addText('Micro-conversions : Newsletter, blog engagement', $contentStyle);

$section->addTextBreak();

$section->addTitle('6.2 Google Search Console monitoring', 2);
$section->addTextBreak();

$section->addText('KPIs Search Console surveilles', $contentStyle);
$section->addFootnote()->addText('GOOGLE. S.d. Google Search Console Help. Site web sur INTERNET. <support.google.com/webmasters>. Derniere consultation : le 17/07-2025.', ['name' => 'Inter', 'size' => 9, 'lang' => 'fr-BE']);
$section->addText(' :', $strongStyle);
$section->addText('Impressions totales et evolution', $contentStyle);
$section->addText('CTR moyen par type de page', $contentStyle);
$section->addText('Position moyenne mots-cles strategiques', $contentStyle);
$section->addText('Erreurs exploration et correction', $contentStyle);
$section->addText('Core Web Vitals scores reel', $contentStyle);

$section->addTextBreak();

$section->addTitle('6.3 KPIs et tableaux de bord', 2);
$section->addTextBreak();

// Tableau KPIs
$kpiTable = $section->addTable(['borderSize' => 6, 'borderColor' => '999999']);
$kpiTable->addRow();
$kpiTable->addCell(2500)->addText('KPI', $strongStyle);
$kpiTable->addCell(2000)->addText('Objectif', $strongStyle);
$kpiTable->addCell(2000)->addText('Frequence', $strongStyle);
$kpiTable->addCell(2500)->addText('Source donnees', $strongStyle);

$kpiTable->addRow();
$kpiTable->addCell(2500)->addText('Trafic organique', $contentStyle);
$kpiTable->addCell(2000)->addText('+20%/mois', $contentStyle);
$kpiTable->addCell(2000)->addText('Hebdomadaire', $contentStyle);
$kpiTable->addCell(2500)->addText('Google Analytics', $contentStyle);

$kpiTable->addRow();
$kpiTable->addCell(2500)->addText('Positions Top 10', $contentStyle);
$kpiTable->addCell(2000)->addText('50 mots-cles', $contentStyle);
$kpiTable->addCell(2000)->addText('Hebdomadaire', $contentStyle);
$kpiTable->addCell(2500)->addText('Search Console + SEMrush', $contentStyle);

$kpiTable->addRow();
$kpiTable->addCell(2500)->addText('ROAS Google Ads', $contentStyle);
$kpiTable->addCell(2000)->addText('4:1 minimum', $contentStyle);
$kpiTable->addCell(2000)->addText('Quotidien', $contentStyle);
$kpiTable->addCell(2500)->addText('Google Ads', $contentStyle);

$kpiTable->addRow();
$kpiTable->addCell(2500)->addText('Conversions organiques', $contentStyle);
$kpiTable->addCell(2000)->addText('5% taux conversion', $contentStyle);
$kpiTable->addCell(2000)->addText('Hebdomadaire', $contentStyle);
$kpiTable->addCell(2500)->addText('Google Analytics', $contentStyle);

$kpiTable->addRow();
$kpiTable->addCell(2500)->addText('Backlinks qualite', $contentStyle);
$kpiTable->addCell(2000)->addText('+5 DA40+/mois', $contentStyle);
$kpiTable->addCell(2000)->addText('Mensuel', $contentStyle);
$kpiTable->addCell(2500)->addText('Ahrefs/Majestic', $contentStyle);

// Nouvelle page
$section->addPageBreak();

// 7. STRATEGIE LONG TERME
$section->addTitle('7. Strategie a long terme', 1);
$section->addTextBreak();

$section->addTitle('7.1 Roadmap SEO 12 mois', 2);
$section->addTextBreak();

$section->addText('Phase 1 - Fondations (Mois 1-3) :', $strongStyle);
$section->addText('Setup technique complet (sitemap, robots.txt, schema)', $contentStyle);
$section->addText('Optimisation pages principales (home, categories)', $contentStyle);
$section->addText('Lancement blog avec 20 articles strategiques', $contentStyle);
$section->addText('Configuration analytics et tracking', $contentStyle);
$section->addText('Campagnes Google Ads initiales', $contentStyle);

$section->addTextBreak();

$section->addText('Phase 2 - Croissance (Mois 4-8) :', $strongStyle);
$section->addText('Expansion contenu blog (50+ articles)', $contentStyle);
$section->addText('Link building actif (10+ backlinks qualite/mois)', $contentStyle);
$section->addText('Optimisation pages produits (500+ fiches)', $contentStyle);
$section->addText('SEO local avance (pages regionales)', $contentStyle);
$section->addText('Tests A/B sur annonces payantes', $contentStyle);

$section->addTextBreak();

$section->addText('Phase 3 - Domination (Mois 9-12) :', $strongStyle);
$section->addText('Expansion internationale (marches europeens)', $contentStyle);
$section->addText('Content marketing avance (guides, ebooks)', $contentStyle);
$section->addText('Partenariats influenceurs et presse specialisee', $contentStyle);
$section->addText('Optimisation continue basee sur donnees', $contentStyle);
$section->addText('Diversification canaux acquisition', $contentStyle);

$section->addTextBreak();

$section->addTitle('7.2 Evolution et adaptation', 2);
$section->addTextBreak();

$section->addText('Veille et adaptation continue', $contentStyle);
$section->addFootnote()->addText('AHREFS. S.d. SEO Tools and Resources. Site web sur INTERNET. <ahrefs.com>. Derniere consultation : le 17/07-2025.', ['name' => 'Inter', 'size' => 9, 'lang' => 'fr-BE']);
$section->addText(' :', $strongStyle);
$section->addText('Suivi algorithmes Google (Core Updates)', $contentStyle);
$section->addText('Adaptation nouvelles fonctionnalites Search', $contentStyle);
$section->addText('Evolution comportements utilisateurs', $contentStyle);
$section->addText('Integration nouvelles plateformes sociales', $contentStyle);
$section->addText('Tests nouvelles technologies (IA, voice search)', $contentStyle);

$section->addTextBreak();

$section->addTitle('7.3 Budget et ressources', 2);
$section->addTextBreak();

$section->addText('Budget mensuel detaille :', $strongStyle);
$section->addText('Google Ads : 1650€/mois', $contentStyle);
$section->addText('Outils SEO (SEMrush, Ahrefs) : 200€/mois', $contentStyle);
$section->addText('Content marketing : 800€/mois', $contentStyle);
$section->addText('Link building : 500€/mois', $contentStyle);
$section->addText('Social media ads : 300€/mois', $contentStyle);
$section->addText('Formation et veille : 150€/mois', $contentStyle);
$section->addText('Total : 3600€/mois', $strongStyle);

$section->addTextBreak();

$section->addText('Ressources humaines necessaires :', $strongStyle);
$section->addText('SEO Manager : 1 ETP (temps plein)', $contentStyle);
$section->addText('Content Manager : 0.5 ETP', $contentStyle);
$section->addText('Social Media Manager : 0.5 ETP', $contentStyle);
$section->addText('Freelances specialises : ponctuel', $contentStyle);

// Nouvelle page
$section->addPageBreak();

// CONCLUSION
$section->addTitle('Conclusion', 1);
$section->addTextBreak();

$section->addText('Cette strategie de referencement SEM pour FarmShop etablit une approche complete et mesurable pour dominer le marche digital de l\'agriculture. L\'integration harmonieuse du SEO technique, du content marketing, de la publicite payante et de l\'optimisation sociale cree un ecosysteme de visibilite robuste et evolutif.', $contentStyle);

$section->addTextBreak();

$section->addText('L\'avantage concurrentiel de FarmShop repose sur son architecture technique moderne Laravel 11, permettant une implementation SEO native et performante. La specialisation secteur agricole, combinee a une approche data-driven, positionne favorablement la plateforme face a une concurrence encore peu mature techniquement.', $contentStyle);

$section->addTextBreak();

$section->addText('La roadmap 12 mois planifiee, avec un budget de 3600€ mensuel et des ressources humaines dediees, vise un objectif ambitieux mais realiste : etablir FarmShop comme leader du e-commerce agricole belge avec une expansion europeenne progressive.', $contentStyle);

$section->addTextBreak();

$section->addText('Le succes de cette strategie repose sur l\'execution rigoureuse, la mesure continue des performances et l\'adaptation agile aux evolutions du marche digital et du comportement des agriculteurs utilisateurs.', $contentStyle);

$section->addTextBreak(2);

// BIBLIOGRAPHIE
$section->addTitle('Bibliographie', 1);
$section->addTextBreak();

$section->addText('GOOGLE. S.d. Google Search Central Documentation. Site web sur INTERNET. <developers.google.com/search>. Derniere consultation : le 17/07-2025.', $contentStyle);
$section->addTextBreak();

$section->addText('GOOGLE. S.d. Google Ads Help Center. Site web sur INTERNET. <support.google.com/google-ads>. Derniere consultation : le 17/07-2025.', $contentStyle);
$section->addTextBreak();

$section->addText('MOZ. S.d. SEO Learning Center. Site web sur INTERNET. <moz.com/learn/seo>. Derniere consultation : le 17/07-2025.', $contentStyle);
$section->addTextBreak();

$section->addText('SEMRUSH. S.d. SEO Toolkit and Digital Marketing Platform. Site web sur INTERNET. <semrush.com>. Derniere consultation : le 17/07-2025.', $contentStyle);
$section->addTextBreak();

$section->addText('AHREFS. S.d. SEO Tools and Resources. Site web sur INTERNET. <ahrefs.com>. Derniere consultation : le 17/07-2025.', $contentStyle);
$section->addTextBreak();

$section->addText('SCHEMA.ORG. S.d. Schema.org Structured Data Documentation. Site web sur INTERNET. <schema.org>. Derniere consultation : le 17/07-2025.', $contentStyle);
$section->addTextBreak();

$section->addText('LARAVEL. S.d. Laravel SEO Best Practices. Site web sur INTERNET. <laravel.com/docs/11.x>. Derniere consultation : le 17/07-2025.', $contentStyle);
$section->addTextBreak();

$section->addText('SPATIE. S.d. Laravel SEO Tools Package. Site web sur INTERNET. <spatie.be/docs/laravel-sitemap>. Derniere consultation : le 17/07-2025.', $contentStyle);
$section->addTextBreak();

$section->addText('GOOGLE. S.d. Core Web Vitals Guide. Site web sur INTERNET. <web.dev/vitals>. Derniere consultation : le 17/07-2025.', $contentStyle);

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
$objWriter->save(__DIR__ . '/17_Strategie_Referencement_SEO_SEA_v2.docx');

echo "Livrable 17 - Strategie de referencement (SEO/SEA) cree avec succes !\n";
echo "Emplacement : " . __DIR__ . "/17_Strategie_Referencement_SEO_SEA_v2.docx\n";
echo "Document complet SEM avec notes de bas de page academiques !\n";

?>
