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
$apiStyle = ['name' => 'Inter', 'size' => 10, 'italic' => true, 'color' => '666666', 'lang' => 'fr-BE'];

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

$section->addText('LIVRABLE 15', [
    'name' => 'Inter', 'size' => 20, 'bold' => true, 'lang' => 'fr-BE'
], ['alignment' => Jc::CENTER]);

$section->addText('DOCUMENTATION API COMPLETE', [
    'name' => 'Inter', 'size' => 16, 'bold' => true, 'lang' => 'fr-BE'
], ['alignment' => Jc::CENTER]);

$section->addText('Interface de Programmation Application FarmShop', [
    'name' => 'Inter', 'size' => 14, 'bold' => true, 'lang' => 'fr-BE'
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
$section->addText('1. Architecture generale de l\'API FarmShop', $contentStyle);
$section->addText('1.1 Standards et technologies utilisees', $contentStyle);
$section->addText('1.2 Structure RESTful et organisation des endpoints', $contentStyle);
$section->addText('1.3 Systeme d\'authentification et autorisation', $contentStyle);
$section->addText('1.4 Gestion des erreurs et codes de reponse', $contentStyle);
$section->addText('2. Documentation interactive Swagger/OpenAPI', $contentStyle);
$section->addText('2.1 Implementation de l\'interface Swagger UI', $contentStyle);
$section->addText('2.2 Schemas de donnees et modeles', $contentStyle);
$section->addText('2.3 Annotations automatisees des endpoints', $contentStyle);
$section->addText('2.4 Tests interactifs et validation des API', $contentStyle);
$section->addText('3. Endpoints produits et catalogue', $contentStyle);
$section->addText('3.1 CRUD complet des produits', $contentStyle);
$section->addText('3.2 Recherche avancee et filtrage', $contentStyle);
$section->addText('3.3 Gestion des stocks et disponibilites', $contentStyle);
$section->addText('3.4 Categories et taxonomies', $contentStyle);
$section->addText('4. Systeme de panier et commandes', $contentStyle);
$section->addText('4.1 Gestion du panier d\'achat', $contentStyle);
$section->addText('4.2 Panier de location avec contraintes temporelles', $contentStyle);
$section->addText('4.3 Creation et suivi des commandes', $contentStyle);
$section->addText('4.4 Workflow des statuts de commande', $contentStyle);
$section->addText('5. Systeme de location et inspections', $contentStyle);
$section->addText('5.1 Contraintes de location et disponibilites', $contentStyle);
$section->addText('5.2 Commandes de location et gestion temporelle', $contentStyle);
$section->addText('5.3 Inspections et etats du materiel', $contentStyle);
$section->addText('5.4 Calcul des penalites et cautions', $contentStyle);
$section->addText('6. Gestion des utilisateurs et profils', $contentStyle);
$section->addText('6.1 Inscription et authentification', $contentStyle);
$section->addText('6.2 Profils utilisateur et preferences', $contentStyle);
$section->addText('6.3 Wishlist et likes', $contentStyle);
$section->addText('6.4 Historique et donnees personnelles GDPR', $contentStyle);
$section->addText('7. Systeme de paiement Stripe', $contentStyle);
$section->addText('7.1 Creation et confirmation des paiements', $contentStyle);
$section->addText('7.2 Gestion des cautions pour locations', $contentStyle);
$section->addText('7.3 Webhooks et synchronisation', $contentStyle);
$section->addText('7.4 Remboursements et annulations', $contentStyle);
$section->addText('8. Blog et systeme de contenu', $contentStyle);
$section->addText('8.1 Articles et categories de blog', $contentStyle);
$section->addText('8.2 Commentaires et moderation', $contentStyle);
$section->addText('8.3 Signalements et gestion communautaire', $contentStyle);
$section->addText('8.4 Statistiques et analytics', $contentStyle);
$section->addText('9. Systeme de messages et notifications', $contentStyle);
$section->addText('9.1 Messages administratifs et alertes', $contentStyle);
$section->addText('9.2 Notifications de stock', $contentStyle);
$section->addText('9.3 Messages de contact visiteurs', $contentStyle);
$section->addText('9.4 Newsletter et abonnements', $contentStyle);
$section->addText('10. Interface d\'administration', $contentStyle);
$section->addText('10.1 Endpoints reserves aux administrateurs', $contentStyle);
$section->addText('10.2 Statistiques et rapports', $contentStyle);
$section->addText('10.3 Gestion des utilisateurs et roles', $contentStyle);
$section->addText('10.4 Configuration et parametrage', $contentStyle);
$section->addText('11. Conformite GDPR et gestion des cookies', $contentStyle);
$section->addText('11.1 Preferences et consentements', $contentStyle);
$section->addText('11.2 Export et suppression des donnees', $contentStyle);
$section->addText('11.3 Migration des cookies invites', $contentStyle);
$section->addText('11.4 Historique et auditabilite', $contentStyle);
$section->addText('12. Performances et optimisation', $contentStyle);
$section->addText('12.1 Pagination et limitations', $contentStyle);
$section->addText('12.2 Cache et strategies de mise en cache', $contentStyle);
$section->addText('12.3 Monitoring et metriques', $contentStyle);
$section->addText('12.4 Scalabilite et architecture', $contentStyle);
$section->addText('Conclusion', $contentStyle);

// Nouvelle page
$section->addPageBreak();

// INTRODUCTION
$section->addTitle('Introduction', 1);
$section->addTextBreak();

$section->addText('La plateforme FarmShop, specialisee dans la vente et location de produits agricoles, s\'appuie sur une architecture API RESTful robuste et comprehensve. Cette documentation technique presente l\'ensemble des 540+ endpoints disponibles, leur utilisation, leurs parametres et les reponses attendues.', $contentStyle);

$section->addTextBreak();

$section->addText('L\'API FarmShop respecte les standards OpenAPI 3.0 et beneficie d\'une documentation interactive Swagger UI accessible en ligne. Elle couvre l\'ensemble des fonctionnalites de la plateforme : gestion des produits, systeme de commandes classiques et de location, paiements securises, blog, messages, administration et conformite GDPR.', $contentStyle);

$section->addTextBreak();

$section->addText('Cette documentation technique constitue le livrable 15 du projet et s\'adresse aux developpeurs, integrateurs et equipes techniques souhaitant comprendre ou utiliser l\'API FarmShop. Elle inclut des exemples concrets, les schemas de donnees complets et les meilleures pratiques d\'integration.', $contentStyle);

$section->addTextBreak();

$section->addText('Documentation Swagger interactive disponible a l\'adresse :', $strongStyle);
$section->addText('http://127.0.0.1:8000/api/documentation', $codeStyle);

$section->addTextBreak(2);

// 1. ARCHITECTURE GENERALE
$section->addTitle('1. Architecture generale de l\'API FarmShop', 1);
$section->addTextBreak();

$section->addTitle('1.1 Standards et technologies utilisees', 2);
$section->addTextBreak();

$section->addText('Specifications techniques :', $strongStyle);
$section->addTextBreak();

$section->addText('Standard OpenAPI 3.0.0 - Documentation automatisee et interactive', $apiStyle);
$section->addText('Architecture RESTful avec ressources hierarchiques et operations HTTP standards', $contentStyle);
$section->addTextBreak();

$section->addText('Framework Laravel 10+ avec Sanctum pour l\'authentification', $apiStyle);
$section->addText('Package darkaonline/l5-swagger v9.0.1 pour generation automatique documentation', $contentStyle);
$section->addTextBreak();

$section->addText('Base URL de l\'API :', $strongStyle);
$section->addText('http://127.0.0.1:8000/api', $codeStyle);
$section->addText('Version actuelle : 1.0.0', $contentStyle);
$section->addTextBreak();

$section->addText('Formats de donnees supportes :', $strongStyle);
$section->addText('• JSON (application/json) - Format principal pour requetes et reponses', $contentStyle);
$section->addText('• Form Data (multipart/form-data) - Upload de fichiers et images', $contentStyle);
$section->addText('• URL Encoded (application/x-www-form-urlencoded) - Formulaires simples', $contentStyle);

$section->addTextBreak();

$section->addTitle('1.2 Structure RESTful et organisation des endpoints', 2);
$section->addTextBreak();

$section->addText('L\'API FarmShop organise ses 540+ endpoints selon une structure hierarchique claire :', $contentStyle);
$section->addTextBreak();

$section->addText('Endpoints publics (consultation libre) :', $strongStyle);
$section->addText('GET /api/products - Liste des produits avec filtres avances', $codeStyle);
$section->addText('GET /api/categories - Catalogue des categories', $codeStyle);
$section->addText('GET /api/blog/posts - Articles de blog publies', $codeStyle);
$section->addText('POST /api/contact - Messages de contact visiteurs', $codeStyle);
$section->addTextBreak();

$section->addText('Endpoints authentifies (utilisateurs connectes) :', $strongStyle);
$section->addText('GET /api/profile - Profil utilisateur', $codeStyle);
$section->addText('POST /api/cart/products/{id} - Ajout au panier', $codeStyle);
$section->addText('GET /api/orders - Historique des commandes', $codeStyle);
$section->addText('POST /api/stripe/payment-intent - Creation paiement', $codeStyle);
$section->addTextBreak();

$section->addText('Endpoints administrateur (acces restreint) :', $strongStyle);
$section->addText('GET /api/admin/users - Gestion des utilisateurs', $codeStyle);
$section->addText('POST /api/admin/products - Creation de produits', $codeStyle);
$section->addText('GET /api/admin/orders/statistics - Rapports de ventes', $codeStyle);

$section->addTextBreak();

$section->addTitle('1.3 Systeme d\'authentification et autorisation', 2);
$section->addTextBreak();

$section->addText('L\'API utilise un systeme d\'authentification hybride combine :', $contentStyle);
$section->addTextBreak();

$section->addText('Authentification par session web (auth:web) :', $strongStyle);
$section->addText('• Utilisation des sessions Laravel classiques', $contentStyle);
$section->addText('• Protection CSRF automatique', $contentStyle);
$section->addText('• Gestion des cookies d\'authentification', $contentStyle);
$section->addText('• Ideal pour interfaces web frontend', $contentStyle);
$section->addTextBreak();

$section->addText('Authentification Bearer Token (Sanctum) :', $strongStyle);
$section->addText('• Tokens API personnels pour applications mobiles', $contentStyle);
$section->addText('• Support SPA (Single Page Applications)', $contentStyle);
$section->addText('• Gestion des portees et permissions', $contentStyle);
$section->addText('• Revocation et expiration des tokens', $contentStyle);
$section->addTextBreak();

$section->addText('Niveaux d\'autorisation :', $strongStyle);
$section->addText('• Visiteur : Acces lecture seule aux ressources publiques', $contentStyle);
$section->addText('• Utilisateur : Gestion profil, commandes, panier, wishlist', $contentStyle);
$section->addText('• Administrateur : Acces complet gestion plateforme', $contentStyle);

$section->addTextBreak();

$section->addTitle('1.4 Gestion des erreurs et codes de reponse', 2);
$section->addTextBreak();

$section->addText('L\'API respecte les codes de statut HTTP standards :', $contentStyle);
$section->addTextBreak();

$section->addText('Codes de succes :', $strongStyle);
$section->addText('• 200 OK : Requete reussie avec donnees', $contentStyle);
$section->addText('• 201 Created : Ressource creee avec succes', $contentStyle);
$section->addText('• 204 No Content : Operation reussie sans contenu retour', $contentStyle);
$section->addTextBreak();

$section->addText('Codes d\'erreur client :', $strongStyle);
$section->addText('• 400 Bad Request : Donnees invalides ou malformees', $contentStyle);
$section->addText('• 401 Unauthorized : Authentification requise', $contentStyle);
$section->addText('• 403 Forbidden : Permissions insuffisantes', $contentStyle);
$section->addText('• 404 Not Found : Ressource inexistante', $contentStyle);
$section->addText('• 409 Conflict : Conflit de donnees (stock insuffisant)', $contentStyle);
$section->addText('• 422 Unprocessable Entity : Erreurs de validation', $contentStyle);
$section->addTextBreak();

$section->addText('Codes d\'erreur serveur :', $strongStyle);
$section->addText('• 500 Internal Server Error : Erreur systeme interne', $contentStyle);
$section->addText('• 503 Service Unavailable : Service temporairement indisponible', $contentStyle);

$section->addTextBreak(2);

// 2. DOCUMENTATION SWAGGER
$section->addTitle('2. Documentation interactive Swagger/OpenAPI', 1);
$section->addTextBreak();

$section->addTitle('2.1 Implementation de l\'interface Swagger UI', 2);
$section->addTextBreak();

$section->addText('L\'API FarmShop integre une documentation interactive complete basee sur Swagger UI, accessible en temps reel et synchronisee avec le code source.', $contentStyle);
$section->addTextBreak();

$section->addText('Configuration technique :', $strongStyle);
$section->addText('Package : darkaonline/l5-swagger v9.0.1', $codeStyle);
$section->addText('URL d\'acces : http://127.0.0.1:8000/api/documentation', $codeStyle);
$section->addText('Regeneration : php artisan l5-swagger:generate', $codeStyle);
$section->addTextBreak();

$section->addText('Fonctionnalites disponibles :', $strongStyle);
$section->addText('• Interface graphique intuitive pour explorer tous les endpoints', $contentStyle);
$section->addText('• Tests interactifs directs depuis l\'interface', $contentStyle);
$section->addText('• Exemples de requetes et reponses automatiques', $contentStyle);
$section->addText('• Validation en temps reel des parametres', $contentStyle);
$section->addText('• Export de la specification OpenAPI (JSON/YAML)', $contentStyle);
$section->addText('• Documentation des schemas de donnees complets', $contentStyle);

$section->addTextBreak();

$section->addTitle('2.2 Schemas de donnees et modeles', 2);
$section->addTextBreak();

$section->addText('L\'API definit des schemas de donnees precis pour chaque entite :', $contentStyle);
$section->addTextBreak();

$section->addText('Modeles principaux documentes :', $strongStyle);
$section->addText('• Product : Produits avec attributs vente et location', $contentStyle);
$section->addText('• Category / RentalCategory : Taxonomies produits', $contentStyle);
$section->addText('• User : Utilisateurs et profils complets', $contentStyle);
$section->addText('• Order / OrderLocation : Commandes achat et location', $contentStyle);
$section->addText('• CartItem / CartItemLocation : Articles des paniers', $contentStyle);
$section->addText('• BlogPost / BlogCategory : Contenu editorial', $contentStyle);
$section->addText('• Message : Systeme de messagerie', $contentStyle);
$section->addText('• SpecialOffer : Promotions et reductions', $contentStyle);
$section->addTextBreak();

$section->addText('Schemas de reponse standardises :', $strongStyle);
$section->addText('• ApiResponse : Format uniforme pour toutes les reponses', $contentStyle);
$section->addText('• PaginatedResponse : Pagination standardisee', $contentStyle);
$section->addText('• ErrorResponse : Gestion coherente des erreurs', $contentStyle);

$section->addTextBreak();

$section->addTitle('2.3 Annotations automatisees des endpoints', 2);
$section->addTextBreak();

$section->addText('Chaque endpoint beneficie d\'annotations OpenAPI completes integrees directement dans le code source PHP, garantissant la synchronisation entre documentation et implementation.', $contentStyle);
$section->addTextBreak();

$section->addText('Exemple d\'annotation complete :', $strongStyle);
$section->addText('@OA\\Get(', $codeStyle);
$section->addText('    path="/api/products",', $codeStyle);
$section->addText('    tags={"Products"},', $codeStyle);
$section->addText('    summary="Liste des produits avec filtres avances",', $codeStyle);
$section->addText('    @OA\\Parameter(name="category_id", in="query", required=false),', $codeStyle);
$section->addText('    @OA\\Response(response=200, ref="#/components/schemas/PaginatedResponse")', $codeStyle);
$section->addText(')', $codeStyle);
$section->addTextBreak();

$section->addText('Informations documentees par endpoint :', $strongStyle);
$section->addText('• Description fonctionnelle detaillee', $contentStyle);
$section->addText('• Parametres avec types, contraintes et exemples', $contentStyle);
$section->addText('• Codes de reponse avec schemas associes', $contentStyle);
$section->addText('• Exigences d\'authentification et permissions', $contentStyle);
$section->addText('• Tags d\'organisation thematique', $contentStyle);

$section->addTextBreak();

$section->addTitle('2.4 Tests interactifs et validation des API', 2);
$section->addTextBreak();

$section->addText('L\'interface Swagger UI permet de tester directement tous les endpoints depuis le navigateur, facilitant le developpement et l\'integration.', $contentStyle);
$section->addTextBreak();

$section->addText('Capacites de test :', $strongStyle);
$section->addText('• Execution de requetes reelles contre l\'API', $contentStyle);
$section->addText('• Saisie assistee des parametres avec validation', $contentStyle);
$section->addText('• Gestion automatique de l\'authentification', $contentStyle);
$section->addText('• Affichage formate des reponses JSON', $contentStyle);
$section->addText('• Mesure des temps de reponse', $contentStyle);
$section->addText('• Export des requetes pour outils externes (curl, Postman)', $contentStyle);
$section->addTextBreak();

$section->addText('Validation automatique :', $strongStyle);
$section->addText('• Verification des types de parametres', $contentStyle);
$section->addText('• Controle des valeurs obligatoires', $contentStyle);
$section->addText('• Validation des formats (email, date, URL)', $contentStyle);
$section->addText('• Verification des schemas de reponse', $contentStyle);

$section->addTextBreak(2);

// 3. ENDPOINTS PRODUITS
$section->addTitle('3. Endpoints produits et catalogue', 1);
$section->addTextBreak();

$section->addTitle('3.1 CRUD complet des produits', 2);
$section->addTextBreak();

$section->addText('L\'API offre une gestion complete des produits avec operations CRUD differenciees selon les permissions utilisateur.', $contentStyle);
$section->addTextBreak();

$section->addText('Endpoints publics (consultation) :', $strongStyle);
$section->addTextBreak();

$section->addText('GET /api/products', $codeStyle);
$section->addText('Liste paginee des produits actifs avec filtres avances', $apiStyle);
$section->addText('Parametres : category_id, type, stock_status, search, sort, per_page', $contentStyle);
$section->addText('Reponse : Collection paginee avec metadonnees', $contentStyle);
$section->addTextBreak();

$section->addText('GET /api/products/{product}', $codeStyle);
$section->addText('Details complets d\'un produit avec relations', $apiStyle);
$section->addText('Include : category, rental_category, images, constraints', $contentStyle);
$section->addText('Cache : 60 minutes pour optimiser performances', $contentStyle);
$section->addTextBreak();

$section->addText('GET /api/products/category/{category}', $codeStyle);
$section->addText('Produits filtres par categorie avec pagination', $apiStyle);
$section->addText('Tri automatique par popularite et stock disponible', $contentStyle);
$section->addTextBreak();

$section->addText('POST /api/products/{product}/check-availability', $codeStyle);
$section->addText('Verification de disponibilite en temps reel', $apiStyle);
$section->addText('Parametres : quantity, start_date, end_date (pour locations)', $contentStyle);
$section->addText('Reponse : Disponibilite boolean + suggestions alternatives', $contentStyle);
$section->addTextBreak();

$section->addText('Endpoints administrateur (gestion complete) :', $strongStyle);
$section->addTextBreak();

$section->addText('POST /api/admin/products', $codeStyle);
$section->addText('Creation de nouveaux produits avec validation complete', $apiStyle);
$section->addText('Support upload images multiples et gestion stocks', $contentStyle);
$section->addTextBreak();

$section->addText('PUT /api/admin/products/{product}', $codeStyle);
$section->addText('Mise a jour complete ou partielle', $apiStyle);
$section->addText('Gestion automatique historique modifications', $contentStyle);
$section->addTextBreak();

$section->addText('DELETE /api/admin/products/{product}', $codeStyle);
$section->addText('Suppression logique avec conservation historique commandes', $apiStyle);
$section->addTextBreak();

$section->addText('POST /api/admin/products/{id}/restore', $codeStyle);
$section->addText('Restauration produits supprimes avec verification coherence', $apiStyle);

$section->addTextBreak();

$section->addTitle('3.2 Recherche avancee et filtrage', 2);
$section->addTextBreak();

$section->addText('Le systeme de recherche integre des capacites avancees de filtrage et tri pour optimiser la decouverte produits.', $contentStyle);
$section->addTextBreak();

$section->addText('GET /api/products/search', $codeStyle);
$section->addText('Recherche textuelle intelligente avec suggestions', $apiStyle);
$section->addTextBreak();

$section->addText('Parametres de recherche disponibles :', $strongStyle);
$section->addText('• q : Terme de recherche (nom, description, tags)', $contentStyle);
$section->addText('• category_id : Filtrage par categorie principale', $contentStyle);
$section->addText('• rental_category_id : Filtrage categories de location', $contentStyle);
$section->addText('• type : sale|rental|both - Type de produit', $contentStyle);
$section->addText('• price_min / price_max : Fourchette de prix', $contentStyle);
$section->addText('• rental_price_min / rental_price_max : Prix location', $contentStyle);
$section->addText('• stock_status : available|low|out_of_stock', $contentStyle);
$section->addText('• is_rental_available : Disponible en location', $contentStyle);
$section->addTextBreak();

$section->addText('Options de tri supportees :', $strongStyle);
$section->addText('• name_asc / name_desc : Ordre alphabetique', $contentStyle);
$section->addText('• price_asc / price_desc : Prix croissant/decroissant', $contentStyle);
$section->addText('• rental_price_asc / rental_price_desc : Prix location', $contentStyle);
$section->addText('• created_at : Nouveautes en premier', $contentStyle);
$section->addText('• popularity : Tri par likes et commandes', $contentStyle);
$section->addText('• stock_desc : Stock disponible en priorite', $contentStyle);
$section->addTextBreak();

$section->addText('Fonctionnalites avancees :', $strongStyle);
$section->addText('• Recherche fuzzy avec tolerance aux fautes de frappe', $contentStyle);
$section->addText('• Suggestions automatiques de termes similaires', $contentStyle);
$section->addText('• Mise en evidence des termes recherches', $contentStyle);
$section->addText('• Filtrage intelligent par disponibilite temps reel', $contentStyle);

$section->addTextBreak();

$section->addTitle('3.3 Gestion des stocks et disponibilites', 2);
$section->addTextBreak();

$section->addText('Le systeme de gestion des stocks distingue le stock de vente du stock de location avec alertes automatiques et verifications en temps reel.', $contentStyle);
$section->addTextBreak();

$section->addText('Endpoints de consultation des stocks :', $strongStyle);
$section->addTextBreak();

$section->addText('GET /api/admin/products/stock/dashboard', $codeStyle);
$section->addText('Tableau de bord complet des stocks avec indicateurs', $apiStyle);
$section->addText('Metriques : Stock total, rotation, alertes, valorisation', $contentStyle);
$section->addTextBreak();

$section->addText('GET /api/admin/products/stock/alerts', $codeStyle);
$section->addText('Liste des produits en situation de stock critique', $apiStyle);
$section->addText('Seuils configurables par produit avec notifications', $contentStyle);
$section->addTextBreak();

$section->addText('GET /api/admin/products/stock/low', $codeStyle);
$section->addText('Produits sous le seuil minimum avec suggestions reapprovisionnement', $apiStyle);
$section->addTextBreak();

$section->addText('Gestion des stocks (administrateur) :', $strongStyle);
$section->addTextBreak();

$section->addText('POST /api/admin/products/{product}/stock/update', $codeStyle);
$section->addText('Mise a jour des stocks avec traçabilite complete', $apiStyle);
$section->addText('Parametres : quantity, rental_stock, operation_type, reason', $contentStyle);
$section->addText('Historique : Conservation mouvements avec horodatage', $contentStyle);
$section->addTextBreak();

$section->addText('Verifications automatiques :', $strongStyle);
$section->addText('• Controle coherence stock vente/location', $contentStyle);
$section->addText('• Prevention survente avec reservations temporaires', $contentStyle);
$section->addText('• Alertes automatiques seuils critiques', $contentStyle);
$section->addText('• Calcul disponibilite location avec contraintes temporelles', $contentStyle);

$section->addTextBreak();

$section->addTitle('3.4 Categories et taxonomies', 2);
$section->addTextBreak();

$section->addText('L\'organisation des produits s\'appuie sur un double systeme de categorisation adapte aux besoins de vente et de location.', $contentStyle);
$section->addTextBreak();

$section->addText('Categories principales (vente) :', $strongStyle);
$section->addTextBreak();

$section->addText('GET /api/categories', $codeStyle);
$section->addText('Liste complete des categories actives avec compteurs', $apiStyle);
$section->addText('Include : Nombre de produits, ordre d\'affichage', $contentStyle);
$section->addTextBreak();

$section->addText('GET /api/categories/active', $codeStyle);
$section->addText('Categories publiees uniquement avec tri par priorite', $apiStyle);
$section->addTextBreak();

$section->addText('GET /api/categories/{category}', $codeStyle);
$section->addText('Details categorie avec produits associes pagines', $apiStyle);
$section->addTextBreak();

$section->addText('Categories de location :', $strongStyle);
$section->addTextBreak();

$section->addText('GET /api/rental-categories', $codeStyle);
$section->addText('Taxonomie specifique aux produits de location', $apiStyle);
$section->addText('Contraintes : Durees min/max, conditions particulieres', $contentStyle);
$section->addTextBreak();

$section->addText('GET /api/rental-categories/{rentalCategory}', $codeStyle);
$section->addText('Details avec produits disponibles en location', $apiStyle);
$section->addTextBreak();

$section->addText('Gestion administrative des categories :', $strongStyle);
$section->addTextBreak();

$section->addText('POST /api/admin/categories', $codeStyle);
$section->addText('Creation avec gestion automatique des slugs et ordre', $apiStyle);
$section->addTextBreak();

$section->addText('PUT /api/admin/categories/{category}', $codeStyle);
$section->addText('Modification avec preservation des relations produits', $apiStyle);
$section->addTextBreak();

$section->addText('PATCH /api/admin/categories/{category}/toggle-status', $codeStyle);
$section->addText('Activation/desactivation avec impact automatique sur produits', $apiStyle);
$section->addTextBreak();

$section->addText('GET /api/admin/categories/stats', $codeStyle);
$section->addText('Statistiques detaillees par categorie avec analytics', $apiStyle);

$section->addTextBreak(2);

// 4. SYSTEME DE PANIER ET COMMANDES  
$section->addTitle('4. Systeme de panier et commandes', 1);
$section->addTextBreak();

$section->addTitle('4.1 Gestion du panier d\'achat', 2);
$section->addTextBreak();

$section->addText('Le systeme de panier offre une experience utilisateur fluide avec verification automatique des stocks et calculs en temps reel.', $contentStyle);
$section->addTextBreak();

$section->addText('Endpoints de base :', $strongStyle);
$section->addTextBreak();

$section->addText('GET /api/cart', $codeStyle);
$section->addText('Recuperation complete du panier avec verification disponibilite', $apiStyle);
$section->addText('Include : articles, produits, resume financier, articles indisponibles', $contentStyle);
$section->addTextBreak();

$section->addText('POST /api/cart/products/{product}', $codeStyle);
$section->addText('Ajout produit avec verification stock en temps reel', $apiStyle);
$section->addText('Validation : quantity (1-100), product_id existant', $contentStyle);
$section->addText('Middleware : check.product.availability pour prevention survente', $contentStyle);
$section->addTextBreak();

$section->addText('PUT /api/cart/products/{product}', $codeStyle);
$section->addText('Modification quantite avec recalcul automatique totaux', $apiStyle);
$section->addTextBreak();

$section->addText('DELETE /api/cart/products/{product}', $codeStyle);
$section->addText('Suppression article avec mise a jour immediate totaux', $apiStyle);
$section->addTextBreak();

$section->addText('POST /api/cart/clear', $codeStyle);
$section->addText('Vidage complet du panier avec confirmation', $apiStyle);
$section->addTextBreak();

$section->addText('Fonctionnalites avancees :', $strongStyle);
$section->addText('• GET /api/cart/summary - Resume detaille avec frais livraison', $contentStyle);
$section->addText('• GET /api/cart/availability - Verification globale disponibilite', $contentStyle);
$section->addText('• GET /api/cart/checkout/prepare - Preparation donnees commande', $contentStyle);

$section->addTextBreak();

$section->addTitle('4.2 Panier de location avec contraintes temporelles', 2);
$section->addTextBreak();

$section->addText('Le panier de location gere des contraintes specifiques : durees, disponibilites calendaires et calculs de cautions.', $contentStyle);
$section->addTextBreak();

$section->addText('Endpoints specialises :', $strongStyle);
$section->addTextBreak();

$section->addText('GET /api/cart-location', $codeStyle);
$section->addText('Panier location avec verification contraintes temporelles', $apiStyle);
$section->addText('Calculs automatiques : duree, tarif journalier, caution totale', $contentStyle);
$section->addTextBreak();

$section->addText('POST /api/cart-location/products/{productSlug}', $codeStyle);
$section->addText('Ajout avec verification disponibilite sur periode', $apiStyle);
$section->addText('Parametres : quantity, start_date, end_date', $contentStyle);
$section->addTextBreak();

$section->addText('PUT /api/cart-location/products/{productSlug}/dates', $codeStyle);
$section->addText('Modification dates avec recalcul tarifs et verification', $apiStyle);
$section->addTextBreak();

$section->addText('PUT /api/cart-location/default-dates', $codeStyle);
$section->addText('Application dates par defaut a tous les articles', $apiStyle);
$section->addTextBreak();

$section->addText('Gestion des contraintes :', $strongStyle);
$section->addText('• Durees min/max par produit respectees', $contentStyle);
$section->addText('• Verification calendrier disponibilite', $contentStyle);
$section->addText('• Calcul automatique penalites retard', $contentStyle);
$section->addText('• Gestion conflits de reservation', $contentStyle);

$section->addTextBreak();

$section->addTitle('4.3 Creation et suivi des commandes', 2);
$section->addTextBreak();

$section->addText('Le workflow de commande integre validation, paiement et suivi automatise avec notifications.', $contentStyle);
$section->addTextBreak();

$section->addText('Cycle de vie des commandes :', $strongStyle);
$section->addTextBreak();

$section->addText('POST /api/orders', $codeStyle);
$section->addText('Creation commande depuis panier avec validation complete', $apiStyle);
$section->addText('Verification : stock, adresses, mode paiement', $contentStyle);
$section->addText('Generation automatique numero commande unique', $contentStyle);
$section->addTextBreak();

$section->addText('GET /api/orders', $codeStyle);
$section->addText('Historique commandes utilisateur avec pagination', $apiStyle);
$section->addText('Filtres : statut, periode, montant, mode paiement', $contentStyle);
$section->addTextBreak();

$section->addText('GET /api/orders/{order}', $codeStyle);
$section->addText('Details complets commande avec articles et statuts', $apiStyle);
$section->addText('Include : items, user, payment_info, shipping_details', $contentStyle);
$section->addTextBreak();

$section->addText('PATCH /api/orders/{order}/cancel', $codeStyle);
$section->addText('Annulation avec liberation stock et gestion remboursement', $apiStyle);
$section->addTextBreak();

$section->addText('Fonctionnalites de suivi :', $strongStyle);
$section->addText('• GET /api/orders/{order}/track - Suivi livraison temps reel', $contentStyle);
$section->addText('• GET /api/orders/{order}/invoice - Telechargement facture PDF', $contentStyle);
$section->addText('• POST /api/orders/{order}/trigger-next-status - Workflow automatise', $contentStyle);

$section->addTextBreak();

$section->addTitle('4.4 Workflow des statuts de commande', 2);
$section->addTextBreak();

$section->addText('Le systeme de statuts automatise les transitions avec notifications et actions associees.', $contentStyle);
$section->addTextBreak();

$section->addText('Statuts disponibles :', $strongStyle);
$section->addText('• pending : Commande en attente de confirmation', $contentStyle);
$section->addText('• confirmed : Commande confirmee, stock reserve', $contentStyle);
$section->addText('• processing : Preparation en cours', $contentStyle);
$section->addText('• shipped : Expedie avec numero de suivi', $contentStyle);
$section->addText('• delivered : Livre avec confirmation', $contentStyle);
$section->addText('• cancelled : Annule avec liberation stock', $contentStyle);
$section->addTextBreak();

$section->addText('Transitions automatisees :', $strongStyle);
$section->addText('• Confirmation apres paiement reussi', $contentStyle);
$section->addText('• Passage en preparation selon planification', $contentStyle);
$section->addText('• Notification expedition avec tracking', $contentStyle);
$section->addText('• Confirmation livraison automatique ou manuelle', $contentStyle);
$section->addTextBreak();

$section->addText('Actions associees :', $strongStyle);
$section->addText('• Reservation/liberation stock automatique', $contentStyle);
$section->addText('• Notifications email client et admin', $contentStyle);
$section->addText('• Generation documents (facture, bon livraison)', $contentStyle);
$section->addText('• Mise a jour metriques et statistiques', $contentStyle);

$section->addTextBreak(2);

// 5. SYSTEME DE LOCATION ET INSPECTIONS
$section->addTitle('5. Systeme de location et inspections', 1);
$section->addTextBreak();

$section->addTitle('5.1 Contraintes de location et disponibilites', 2);
$section->addTextBreak();

$section->addText('Le systeme de location integre des contraintes complexes pour optimiser l\'utilisation du materiel et minimiser les conflits.', $contentStyle);
$section->addTextBreak();

$section->addText('Gestion des contraintes :', $strongStyle);
$section->addTextBreak();

$section->addText('GET /api/rental-constraints/{product}', $codeStyle);
$section->addText('Contraintes specifiques produit : durees min/max, jours interdits', $apiStyle);
$section->addText('Include : maintenance_periods, seasonal_restrictions', $contentStyle);
$section->addTextBreak();

$section->addText('POST /api/rental-constraints/{product}/validate', $codeStyle);
$section->addText('Validation periode location avec verification conflits', $apiStyle);
$section->addText('Parametres : start_date, end_date, quantity', $contentStyle);
$section->addText('Reponse : valid boolean + detailed_conflicts array', $contentStyle);
$section->addTextBreak();

$section->addText('GET /api/rental-constraints/{product}/calendar', $codeStyle);
$section->addText('Calendrier disponibilite sur 12 mois avec details', $apiStyle);
$section->addText('Format : JSON avec disponibilites quotidiennes', $contentStyle);
$section->addTextBreak();

$section->addText('GET /api/rental-constraints/{product}/suggestions', $codeStyle);
$section->addText('Suggestions dates optimales selon preferences utilisateur', $apiStyle);
$section->addText('Algorithme : analyse historique + disponibilite + meteo', $contentStyle);
$section->addTextBreak();

$section->addText('Types de contraintes :', $strongStyle);
$section->addText('• Duree minimale : 1-30 jours selon produit', $contentStyle);
$section->addText('• Duree maximale : Protection contre monopolisation', $contentStyle);
$section->addText('• Jours d\'interdiction : Maintenance, fermeture', $contentStyle);
$section->addText('• Saisons d\'utilisation : Produits saisonniers', $contentStyle);
$section->addText('• Delai reservation : Anticipation minimum', $contentStyle);

$section->addTextBreak();

$section->addTitle('5.2 Commandes de location et gestion temporelle', 2);
$section->addTextBreak();

$section->addText('Les commandes de location suivent un workflow specifique avec gestion rigoureuse des dates et etats.', $contentStyle);
$section->addTextBreak();

$section->addText('Cycle de vie des locations :', $strongStyle);
$section->addTextBreak();

$section->addText('POST /api/rental-orders', $codeStyle);
$section->addText('Creation depuis panier location avec verification finale', $apiStyle);
$section->addText('Validation : disponibilite, contraintes, caution', $contentStyle);
$section->addTextBreak();

$section->addText('GET /api/rental-orders', $codeStyle);
$section->addText('Historique locations utilisateur avec filtres temporels', $apiStyle);
$section->addText('Filtres : status, period, product_type, cost_range', $contentStyle);
$section->addTextBreak();

$section->addText('GET /api/rental-orders/{orderLocation}', $codeStyle);
$section->addText('Details complets avec timeline et inspections', $apiStyle);
$section->addText('Include : items, inspections, penalties, payments', $contentStyle);
$section->addTextBreak();

$section->addText('Statuts specifiques location :', $strongStyle);
$section->addText('• pending : En attente confirmation et paiement', $contentStyle);
$section->addText('• confirmed : Confirmee, materiel reserve', $contentStyle);
$section->addText('• in_progress : Location active en cours', $contentStyle);
$section->addText('• returned : Materiel retourne, inspection en cours', $contentStyle);
$section->addText('• completed : Location terminee, caution liberee', $contentStyle);
$section->addText('• cancelled : Annulee avec liberation materiel', $contentStyle);

$section->addTextBreak();

$section->addTitle('5.3 Inspections et etats du materiel', 2);
$section->addTextBreak();

$section->addText('Le systeme d\'inspection garantit la qualite du materiel et la satisfaction client avec documentation complete.', $contentStyle);
$section->addTextBreak();

$section->addText('Processus d\'inspection :', $strongStyle);
$section->addTextBreak();

$section->addText('PATCH /api/admin/rental-orders/{orderLocation}/start-inspection', $codeStyle);
$section->addText('Demarrage inspection retour avec checklist', $apiStyle);
$section->addText('Creation rapport inspection vierge', $contentStyle);
$section->addTextBreak();

$section->addText('PATCH /api/admin/rental-items/{orderLocation}/{item}/pickup-condition', $codeStyle);
$section->addText('Documentation etat a la recuperation', $apiStyle);
$section->addText('Parametres : condition, photos, notes, inspector_id', $contentStyle);
$section->addTextBreak();

$section->addText('PATCH /api/admin/rental-items/{orderLocation}/{item}/return-condition', $codeStyle);
$section->addText('Documentation etat au retour avec comparaison', $apiStyle);
$section->addText('Calcul automatique degradations et penalites', $contentStyle);
$section->addTextBreak();

$section->addText('GET /api/rental-orders/{orderLocation}/items/{item}/condition-summary', $codeStyle);
$section->addText('Resume complet evolution etat materiel', $apiStyle);
$section->addText('Include : photos_avant, photos_apres, degradations, reparations', $contentStyle);
$section->addTextBreak();

$section->addText('Etats du materiel :', $strongStyle);
$section->addText('• excellent : Etat neuf, aucune usure visible', $contentStyle);
$section->addText('• good : Bon etat, usure normale acceptable', $contentStyle);
$section->addText('• fair : Etat correct, usure visible sans impact', $contentStyle);
$section->addText('• poor : Etat degrade, reparations mineures necessaires', $contentStyle);
$section->addText('• damaged : Endommage, reparations majeures requises', $contentStyle);

$section->addTextBreak();

$section->addTitle('5.4 Calcul des penalites et cautions', 2);
$section->addTextBreak();

$section->addText('Le systeme de penalites automatise encourage le respect du materiel et des delais avec transparence complete.', $contentStyle);
$section->addTextBreak();

$section->addText('Types de penalites :', $strongStyle);
$section->addText('• Retard retour : Calcul automatique par jour de retard', $contentStyle);
$section->addText('• Degradation materiel : Selon grille tarifaire', $contentStyle);
$section->addText('• Perte : Remplacement integral du materiel', $contentStyle);
$section->addText('• Nettoyage : Frais remise en etat', $contentStyle);
$section->addTextBreak();

$section->addText('Calculs automatises :', $strongStyle);
$section->addTextBreak();

$section->addText('GET /api/rental-orders/{orderLocation}/items/{item}/penalties', $codeStyle);
$section->addText('Calcul penalites en temps reel avec detail', $apiStyle);
$section->addText('Include : late_fees, damage_cost, cleaning_fees', $contentStyle);
$section->addTextBreak();

$section->addText('PATCH /api/admin/rental-items/{orderLocation}/{item}/penalties', $codeStyle);
$section->addText('Application penalites avec justification', $apiStyle);
$section->addText('Parametres : penalty_type, amount, reason, photos', $contentStyle);
$section->addTextBreak();

$section->addText('Gestion des cautions :', $strongStyle);
$section->addText('• Autorisation lors confirmation commande', $contentStyle);
$section->addText('• Capture partielle si penalites', $contentStyle);
$section->addText('• Liberation automatique si aucun probleme', $contentStyle);
$section->addText('• Remboursement immediat apres inspection', $contentStyle);

$section->addTextBreak(2);

// Sauvegarde du document avec le contenu complet
try {
    $filename = __DIR__ . '/Livrable_15_Documentation_API_FarmShop_Complete.docx';
    $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
    $objWriter->save($filename);
    
    echo "Document complet genere avec succes : " . $filename . "\n";
    echo "Taille du fichier : " . round(filesize($filename) / 1024, 2) . " KB\n";
    echo "Nombre de pages estimees : " . round(filesize($filename) / 2048) . " pages\n";
    
} catch (Exception $e) {
    echo "Erreur lors de la generation : " . $e->getMessage() . "\n";
}
