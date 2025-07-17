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
$alertStyle = ['name' => 'Inter', 'size' => 11, 'bold' => true, 'color' => 'FF0000', 'lang' => 'fr-BE'];

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

$section->addText('LIVRABLE 16', [
    'name' => 'Inter', 'size' => 20, 'bold' => true, 'lang' => 'fr-BE'
], ['alignment' => Jc::CENTER]);

$section->addText('STRATEGIE DE SECURITE', [
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

$section->addText('Introduction', $contentStyle);
$section->addText('1. Security by Design - Principes fondamentaux', $contentStyle);
$section->addText('1.1 Approche Security by Design', $contentStyle);
$section->addText('1.2 Implementation dans FarmShop', $contentStyle);
$section->addText('1.3 Methodologie de securisation', $contentStyle);
$section->addText('2. Systemes d\'authentification et gestion des identifiants', $contentStyle);
$section->addText('2.1 Architecture d\'authentification Laravel Sanctum', $contentStyle);
$section->addText('2.2 Politique de mots de passe robustes', $contentStyle);
$section->addText('2.3 Authentification multi-facteurs (2FA)', $contentStyle);
$section->addText('2.4 Systeme de recuperation des identifiants', $contentStyle);
$section->addText('2.5 Gestion des sessions securisees', $contentStyle);
$section->addText('3. Techniques d\'autorisation et controle d\'acces', $contentStyle);
$section->addText('3.1 Modele RBAC avec Spatie Laravel-Permission', $contentStyle);
$section->addText('3.2 Gates et Policies Laravel', $contentStyle);
$section->addText('3.3 Controle d\'acces aux ressources', $contentStyle);
$section->addText('3.4 Segregation des privileges', $contentStyle);
$section->addText('4. Protection des donnees personnelles et conformite RGPD', $contentStyle);
$section->addText('4.1 Analyse d\'impact relative a la protection des donnees', $contentStyle);
$section->addText('4.2 Chiffrement des donnees sensibles', $contentStyle);
$section->addText('4.3 Anonymisation et pseudonymisation', $contentStyle);
$section->addText('4.4 Droit a l\'oubli et portabilite', $contentStyle);
$section->addText('5. Analyse OWASP Top 10 et contre-mesures', $contentStyle);
$section->addText('5.1 A01 Broken Access Control', $contentStyle);
$section->addText('5.2 A02 Cryptographic Failures', $contentStyle);
$section->addText('5.3 A03 Injection', $contentStyle);
$section->addText('5.4 A04 Insecure Design', $contentStyle);
$section->addText('5.5 A05 Security Misconfiguration', $contentStyle);
$section->addText('5.6 A06 Vulnerable Components', $contentStyle);
$section->addText('5.7 A07 Authentication Failures', $contentStyle);
$section->addText('5.8 A08 Software Integrity Failures', $contentStyle);
$section->addText('5.9 A09 Logging and Monitoring Failures', $contentStyle);
$section->addText('5.10 A10 Server-Side Request Forgery', $contentStyle);
$section->addText('6. Systeme de detection d\'intrusion (IDS)', $contentStyle);
$section->addText('6.1 Monitoring et alertes temps reel', $contentStyle);
$section->addText('6.2 Detection d\'anomalies comportementales', $contentStyle);
$section->addText('6.3 Rate limiting et protection DDoS', $contentStyle);
$section->addText('7. Plan de reprise d\'activite (DRP)', $contentStyle);
$section->addText('7.1 Strategie de sauvegarde', $contentStyle);
$section->addText('7.2 Procedures de restauration', $contentStyle);
$section->addText('7.3 Plan de continuite de service', $contentStyle);
$section->addText('8. Tests de securite et audit', $contentStyle);
$section->addText('8.1 Tests de penetration', $contentStyle);
$section->addText('8.2 Audit de securite continu', $contentStyle);
$section->addText('8.3 Veille technologique securite', $contentStyle);
$section->addText('Conclusion', $contentStyle);
$section->addText('Bibliographie', $contentStyle);

// Nouvelle page
$section->addPageBreak();

// INTRODUCTION
$section->addTitle('Introduction', 1);
$section->addTextBreak();

$section->addText('La securite d\'une application e-commerce comme FarmShop constitue un enjeu critique, particulierement dans un contexte ou les donnees personnelles et financieres des utilisateurs sont manipulees quotidiennement. Cette strategie de securite adopte une approche holistique basee sur les principes du Security by Design.', $contentStyle);

$section->addTextBreak();

$section->addText('FarmShop, en tant que plateforme hybride de vente et location de produits agricoles, presente des defis securitaires specifiques : gestion des paiements et cautions, protection des donnees clients, securisation des transactions financieres, et protection contre les fraudes liees aux locations d\'equipements couteux.', $contentStyle);

$section->addTextBreak();

$section->addText('Cette reflexion critique examine systematiquement chaque aspect de la securisation de l\'application, depuis l\'authentification jusqu\'au plan de reprise d\'activite, en s\'appuyant sur les meilleures pratiques industrielles et les recommandations OWASP.', $contentStyle);

$section->addTextBreak(2);

// 1. SECURITY BY DESIGN
$section->addTitle('1. Security by Design - Principes fondamentaux', 1);
$section->addTextBreak();

$section->addTitle('1.1 Approche Security by Design', 2);
$section->addTextBreak();

$section->addText('Principes directeurs implementes :', $strongStyle);
$section->addTextBreak();

$section->addText('Proactive vs Reactive :', $strongStyle);
$section->addText('La securite est integree des la conception plutot qu\'ajoutee apres coup. Chaque fonctionnalite FarmShop est analysee sous l\'angle securitaire avant implementation.', $contentStyle);
$section->addTextBreak();

$section->addText('Security by Default :', $strongStyle);
$section->addText('Les parametres par defaut privilegient toujours l\'option la plus securisee. Exemple : HTTPS obligatoire, cookies securises, headers de securite actives.', $contentStyle);
$section->addTextBreak();

$section->addText('Minimisation des privileges :', $strongStyle);
$section->addText('Chaque utilisateur et composant systeme ne dispose que des permissions strictement necessaires a ses fonctions.', $contentStyle);
$section->addTextBreak();

$section->addText('Defense en profondeur :', $strongStyle);
$section->addText('Multiples couches de securite : WAF, middleware Laravel, validation applicative, controles base de donnees.', $contentStyle);
$section->addTextBreak();

$section->addTitle('1.2 Implementation dans FarmShop', 2);
$section->addTextBreak();

$section->addText('Architecture securisee Laravel 11 :', $strongStyle);
$section->addText('Utilisation des middlewares de securite natifs Laravel', $contentStyle);
$section->addText('Configuration HTTPS obligatoire avec HSTS', $contentStyle);
$section->addText('Headers de securite automatiques (CSP, X-Frame-Options)', $contentStyle);
$section->addText('Validation robuste des entrees utilisateurs', $contentStyle);
$section->addText('Chiffrement transparent des donnees sensibles', $contentStyle);

$section->addTextBreak();

$section->addTitle('1.3 Methodologie de securisation', 2);
$section->addTextBreak();

$section->addText('Cycle de developpement securise :', $strongStyle);
$section->addText('1. Analyse des menaces par fonctionnalite', $contentStyle);
$section->addText('2. Design securise avec threat modeling', $contentStyle);
$section->addText('3. Implementation avec secure coding practices', $contentStyle);
$section->addText('4. Tests de securite automatises', $contentStyle);
$section->addText('5. Audit de securite pre-production', $contentStyle);
$section->addText('6. Monitoring continu post-deploiement', $contentStyle);

// Nouvelle page
$section->addPageBreak();

// 2. AUTHENTIFICATION
$section->addTitle('2. Systemes d\'authentification et gestion des identifiants', 1);
$section->addTextBreak();

$section->addTitle('2.1 Architecture d\'authentification Laravel Sanctum', 2);
$section->addTextBreak();

$section->addText('Solution technique retenue :', $strongStyle);
$section->addText('Laravel Sanctum pour authentification API et SPA', $contentStyle);
$section->addText('JWT tokens pour sessions longue duree', $contentStyle);
$section->addText('Personal Access Tokens pour API externe', $contentStyle);

$section->addTextBreak();

$section->addText('Configuration securisee :', $strongStyle);
$section->addText('Expiration automatique des tokens (24h par defaut)', $contentStyle);
$section->addText('Revocation immediate possible', $contentStyle);
$section->addText('Limitation du nombre de tokens actifs par utilisateur', $contentStyle);
$section->addText('Audit trail complet des connexions', $contentStyle);

$section->addTextBreak();

$section->addTitle('2.2 Politique de mots de passe robustes', 2);
$section->addTextBreak();

// Tableau politique mots de passe
$passwordTable = $section->addTable(['borderSize' => 6, 'borderColor' => '999999']);
$passwordTable->addRow();
$passwordTable->addCell(3000)->addText('Critere', $strongStyle);
$passwordTable->addCell(2000)->addText('Exigence', $strongStyle);
$passwordTable->addCell(4000)->addText('Implementation Laravel', $strongStyle);

$passwordTable->addRow();
$passwordTable->addCell(3000)->addText('Longueur minimale', $contentStyle);
$passwordTable->addCell(2000)->addText('12 caracteres', $contentStyle);
$passwordTable->addCell(4000)->addText('Rule::min(12) dans FormRequest', $contentStyle);

$passwordTable->addRow();
$passwordTable->addCell(3000)->addText('Complexite', $contentStyle);
$passwordTable->addCell(2000)->addText('Mixte requis', $contentStyle);
$passwordTable->addCell(4000)->addText('Password::min(12)->mixedCase()->numbers()->symbols()', $contentStyle);

$passwordTable->addRow();
$passwordTable->addCell(3000)->addText('Dictionnaire', $contentStyle);
$passwordTable->addCell(2000)->addText('Interdit', $contentStyle);
$passwordTable->addCell(4000)->addText('Password::uncompromised() HaveIBeenPwned API', $contentStyle);

$passwordTable->addRow();
$passwordTable->addCell(3000)->addText('Historique', $contentStyle);
$passwordTable->addCell(2000)->addText('5 derniers', $contentStyle);
$passwordTable->addCell(4000)->addText('Table password_history avec hachage', $contentStyle);

$passwordTable->addRow();
$passwordTable->addCell(3000)->addText('Expiration', $contentStyle);
$passwordTable->addCell(2000)->addText('90 jours max', $contentStyle);
$passwordTable->addCell(4000)->addText('Carbon diff dans middleware', $contentStyle);

$section->addTextBreak();

$section->addTitle('2.3 Authentification multi-facteurs (2FA)', 2);
$section->addTextBreak();

$section->addText('Implementation 2FA obligatoire :', $strongStyle);
$section->addText('TOTP (Time-based One-Time Password) via Google Authenticator', $contentStyle);
$section->addText('SMS backup pour utilisateurs sans smartphone', $contentStyle);
$section->addText('Codes de recuperation uniques (10 codes generes)', $contentStyle);

$section->addTextBreak();

$section->addText('Package utilise :', $strongStyle);
$section->addText('pragmarx/google2fa-laravel pour integration TOTP', $contentStyle);
$section->addText('Configuration : fenetre de 30 secondes, tolerance 1 fenetre', $contentStyle);

$section->addTextBreak();

$section->addTitle('2.4 Systeme de recuperation des identifiants', 2);
$section->addTextBreak();

$section->addText('Processus securise de recuperation :', $strongStyle);
$section->addText('1. Verification email + question de securite', $contentStyle);
$section->addText('2. Generation token cryptographiquement fort (64 caracteres)', $contentStyle);
$section->addText('3. Expiration token court (15 minutes)', $contentStyle);
$section->addText('4. Lien unique usage avec verification IP', $contentStyle);
$section->addText('5. Notification utilisateur sur toute tentative', $contentStyle);
$section->addText('6. Log audit complet avec geolocalisation', $contentStyle);

$section->addTextBreak();

$section->addTitle('2.5 Gestion des sessions securisees', 2);
$section->addTextBreak();

$section->addText('Configuration session Laravel :', $strongStyle);
$section->addText('Stockage Redis pour performance et securite', $contentStyle);
$section->addText('Cookies securises : HttpOnly, Secure, SameSite=Strict', $contentStyle);
$section->addText('Regeneration ID session a chaque login', $contentStyle);
$section->addText('Timeout inactivite : 30 minutes standard, 15 minutes admin', $contentStyle);
$section->addText('Detection sessions concurrentes avec limitation', $contentStyle);

// Nouvelle page
$section->addPageBreak();

// 3. AUTORISATION
$section->addTitle('3. Techniques d\'autorisation et controle d\'acces', 1);
$section->addTextBreak();

$section->addTitle('3.1 Modele RBAC avec Spatie Laravel-Permission', 2);
$section->addTextBreak();

$section->addText('Architecture des roles :', $strongStyle);
$section->addTextBreak();

// Tableau des rôles
$rolesTable = $section->addTable(['borderSize' => 6, 'borderColor' => '999999']);
$rolesTable->addRow();
$rolesTable->addCell(2000)->addText('Role', $strongStyle);
$rolesTable->addCell(3000)->addText('Permissions principales', $strongStyle);
$rolesTable->addCell(4000)->addText('Restrictions specifiques', $strongStyle);

$rolesTable->addRow();
$rolesTable->addCell(2000)->addText('Super Admin', $contentStyle);
$rolesTable->addCell(3000)->addText('Toutes permissions systeme', $contentStyle);
$rolesTable->addCell(4000)->addText('IP whitelist, 2FA obligatoire', $contentStyle);

$rolesTable->addRow();
$rolesTable->addCell(2000)->addText('Admin', $contentStyle);
$rolesTable->addCell(3000)->addText('Gestion produits, commandes, users', $contentStyle);
$rolesTable->addCell(4000)->addText('Pas de suppression donnees critiques', $contentStyle);

$rolesTable->addRow();
$rolesTable->addCell(2000)->addText('Moderateur', $contentStyle);
$rolesTable->addCell(3000)->addText('Moderation blog, support client', $contentStyle);
$rolesTable->addCell(4000)->addText('Read-only donnees financieres', $contentStyle);

$rolesTable->addRow();
$rolesTable->addCell(2000)->addText('Membre Premium', $contentStyle);
$rolesTable->addCell(3000)->addText('Achat, location, remises speciales', $contentStyle);
$rolesTable->addCell(4000)->addText('Limite montant transactions elevee', $contentStyle);

$rolesTable->addRow();
$rolesTable->addCell(2000)->addText('Membre Standard', $contentStyle);
$rolesTable->addCell(3000)->addText('Achat uniquement, blog read-only', $contentStyle);
$rolesTable->addCell(4000)->addText('Limite montant, pas de location', $contentStyle);

$rolesTable->addRow();
$rolesTable->addCell(2000)->addText('Visiteur', $contentStyle);
$rolesTable->addCell(3000)->addText('Consultation catalogue public', $contentStyle);
$rolesTable->addCell(4000)->addText('Pas d\'acces donnees sensibles', $contentStyle);

$section->addTextBreak();

$section->addTitle('3.2 Gates et Policies Laravel', 2);
$section->addTextBreak();

$section->addText('Implementation fine-grained permissions :', $strongStyle);
$section->addText('ProductPolicy : controle CRUD produits selon ownership', $contentStyle);
$section->addText('OrderPolicy : acces commandes limitees au proprietaire', $contentStyle);
$section->addText('RentalPolicy : verification ligibilite location', $contentStyle);
$section->addText('AdminPolicy : segregation administrative par modules', $contentStyle);

$section->addTextBreak();

$section->addTitle('3.3 Controle d\'acces aux ressources', 2);
$section->addTextBreak();

$section->addText('Principe du moindre privilege :', $strongStyle);
$section->addText('API endpoints proteges par middleware auth:sanctum', $contentStyle);
$section->addText('Rate limiting par role utilisateur', $contentStyle);
$section->addText('Validation ownership sur ressources privees', $contentStyle);
$section->addText('Logs acces detailles avec contexte utilisateur', $contentStyle);

$section->addTextBreak();

$section->addTitle('3.4 Segregation des privileges', 2);
$section->addTextBreak();

$section->addText('Separation des environnements :', $strongStyle);
$section->addText('Comptes dedies par environnement (dev/staging/prod)', $contentStyle);
$section->addText('Permissions base donnees minimales par service', $contentStyle);
$section->addText('Isolation network entre services critiques', $contentStyle);
$section->addText('Rotation automatique credentials non-humains', $contentStyle);

// Nouvelle page
$section->addPageBreak();

// 4. PROTECTION DONNEES PERSONNELLES
$section->addTitle('4. Protection des donnees personnelles et conformite RGPD', 1);
$section->addTextBreak();

$section->addTitle('4.1 Analyse d\'impact relative a la protection des donnees', 2);
$section->addTextBreak();

$section->addText('Donnees sensibles identifiees :', $strongStyle);
$section->addTextBreak();

// Tableau RGPD
$rgpdTable = $section->addTable(['borderSize' => 6, 'borderColor' => '999999']);
$rgpdTable->addRow();
$rgpdTable->addCell(2500)->addText('Type de donnee', $strongStyle);
$rgpdTable->addCell(2000)->addText('Sensibilite', $strongStyle);
$rgpdTable->addCell(2000)->addText('Retention', $strongStyle);
$rgpdTable->addCell(2500)->addText('Protection', $strongStyle);

$rgpdTable->addRow();
$rgpdTable->addCell(2500)->addText('Identite civile', $contentStyle);
$rgpdTable->addCell(2000)->addText('Elevee', $contentStyle);
$rgpdTable->addCell(2000)->addText('5 ans', $contentStyle);
$rgpdTable->addCell(2500)->addText('Chiffrement AES-256', $contentStyle);

$rgpdTable->addRow();
$rgpdTable->addCell(2500)->addText('Donnees financieres', $contentStyle);
$rgpdTable->addCell(2000)->addText('Critique', $contentStyle);
$rgpdTable->addCell(2000)->addText('7 ans', $contentStyle);
$rgpdTable->addCell(2500)->addText('PCI-DSS compliance', $contentStyle);

$rgpdTable->addRow();
$rgpdTable->addCell(2500)->addText('Adresses IP', $contentStyle);
$rgpdTable->addCell(2000)->addText('Moyenne', $contentStyle);
$rgpdTable->addCell(2000)->addText('1 an', $contentStyle);
$rgpdTable->addCell(2500)->addText('Hachage SHA-256', $contentStyle);

$rgpdTable->addRow();
$rgpdTable->addCell(2500)->addText('Comportement site', $contentStyle);
$rgpdTable->addCell(2000)->addText('Faible', $contentStyle);
$rgpdTable->addCell(2000)->addText('2 ans', $contentStyle);
$rgpdTable->addCell(2500)->addText('Anonymisation', $contentStyle);

$section->addTextBreak();

$section->addTitle('4.2 Chiffrement des donnees sensibles', 2);
$section->addTextBreak();

$section->addText('Strategie de chiffrement :', $strongStyle);
$section->addText('En transit : TLS 1.3 obligatoire, certificats EV', $contentStyle);
$section->addText('Au repos : Laravel Encrypt facade, cles rotatives', $contentStyle);
$section->addText('Base de donnees : chiffrement transparent MySQL', $contentStyle);
$section->addText('Sauvegardes : chiffrement asymetrique GPG', $contentStyle);

$section->addTextBreak();

$section->addTitle('4.3 Anonymisation et pseudonymisation', 2);
$section->addTextBreak();

$section->addText('Techniques implementees :', $strongStyle);
$section->addText('Hachage irreversible des identifiants pour analytics', $contentStyle);
$section->addText('Suppression automatique donnees apres expiration', $contentStyle);
$section->addText('Masquage donnees en environnements non-production', $contentStyle);

$section->addTextBreak();

$section->addTitle('4.4 Droit a l\'oubli et portabilite', 2);
$section->addTextBreak();

$section->addText('Fonctionnalites RGPD implementees :', $strongStyle);
$section->addText('Export donnees personnelles format JSON/PDF', $contentStyle);
$section->addText('Suppression compte avec cascade controle', $contentStyle);
$section->addText('Rectification donnees via interface utilisateur', $contentStyle);
$section->addText('Consentement granulaire cookies et tracking', $contentStyle);

// Nouvelle page
$section->addPageBreak();

// 5. OWASP TOP 10
$section->addTitle('5. Analyse OWASP Top 10 et contre-mesures', 1);
$section->addTextBreak();

$section->addTitle('5.1 A01 Broken Access Control', 2);
$section->addTextBreak();

$section->addText('Risques identifies :', $strongStyle);
$section->addText('Acces non autorise aux commandes d\'autres utilisateurs', $contentStyle);
$section->addText('Elevation de privileges via manipulation URL', $contentStyle);
$section->addText('Contournement controles cote client', $contentStyle);

$section->addTextBreak();

$section->addText('Contre-mesures implementees :', $strongStyle);
$section->addText('Validation ownership systematique via Policies Laravel', $contentStyle);
$section->addText('Middleware d\'autorisation sur toutes routes sensibles', $contentStyle);
$section->addText('Tests automatises controles d\'acces avec PHPUnit', $contentStyle);
$section->addText('Logs detailles tentatives d\'acces non autorises', $contentStyle);

$section->addTextBreak();

$section->addTitle('5.2 A02 Cryptographic Failures', 2);
$section->addTextBreak();

$section->addText('Vulnerabilites potentielles :', $strongStyle);
$section->addText('Transmission donnees sensibles en clair', $contentStyle);
$section->addText('Algorithmes cryptographiques faibles ou obsoletes', $contentStyle);
$section->addText('Gestion inadequate des cles de chiffrement', $contentStyle);

$section->addTextBreak();

$section->addText('Solutions mises en place :', $strongStyle);
$section->addText('HTTPS/TLS 1.3 obligatoire avec HSTS active', $contentStyle);
$section->addText('Chiffrement AES-256 pour donnees au repos', $contentStyle);
$section->addText('Hachage bcrypt (cost 12) pour mots de passe', $contentStyle);
$section->addText('Gestion centralisee cles via Laravel Vault', $contentStyle);

$section->addTextBreak();

$section->addTitle('5.3 A03 Injection', 2);
$section->addTextBreak();

$section->addText('Types d\'injection prevenus :', $strongStyle);
$section->addText('SQL Injection via Eloquent ORM et requetes preparees', $contentStyle);
$section->addText('XSS via echappement automatique Blade templates', $contentStyle);
$section->addText('Command Injection via validation stricte entrees', $contentStyle);
$section->addText('LDAP/NoSQL Injection via sanitisation parametres', $contentStyle);

$section->addTextBreak();

$section->addText('Mesures preventives :', $strongStyle);
$section->addText('Validation robuste avec Laravel Form Requests', $contentStyle);
$section->addText('Sanitisation entrees via htmlspecialchars', $contentStyle);
$section->addText('Whitelist caracteres autorises par champ', $contentStyle);
$section->addText('Content Security Policy restrictive', $contentStyle);

$section->addTextBreak();

$section->addTitle('5.4 A04 Insecure Design', 2);
$section->addTextBreak();

$section->addText('Principes design securise :', $strongStyle);
$section->addText('Threat modeling systematique par fonctionnalite', $contentStyle);
$section->addText('Architecture defense en profondeur', $contentStyle);
$section->addText('Fail-safe defaults dans toute l\'application', $contentStyle);
$section->addText('Segregation logique metier/securite', $contentStyle);

$section->addTextBreak();

$section->addTitle('5.5 A05 Security Misconfiguration', 2);
$section->addTextBreak();

$section->addText('Configurations securisees :', $strongStyle);
$section->addText('Headers securite automatiques (Helmet middleware)', $contentStyle);
$section->addText('Desactivation debug mode en production', $contentStyle);
$section->addText('Suppression endpoints non utilises', $contentStyle);
$section->addText('Configuration minimale services (principe moindre surface)', $contentStyle);

$section->addTextBreak();

$section->addTitle('5.6 A06 Vulnerable and Outdated Components', 2);
$section->addTextBreak();

$section->addText('Gestion vulnerabilites composants :', $strongStyle);
$section->addText('Audit automatise dependencies avec composer audit', $contentStyle);
$section->addText('Veille securite via GitHub Security Advisories', $contentStyle);
$section->addText('Mise a jour reguliere packages tiers', $contentStyle);
$section->addText('Tests regression apres chaque mise a jour', $contentStyle);

$section->addTextBreak();

$section->addTitle('5.7 A07 Identification and Authentication Failures', 2);
$section->addTextBreak();

$section->addText('Renforcement authentification :', $strongStyle);
$section->addText('2FA obligatoire comptes privileges', $contentStyle);
$section->addText('Politique mots de passe robuste appliquee', $contentStyle);
$section->addText('Rate limiting tentatives connexion', $contentStyle);
$section->addText('Detection comptes compromis (HaveIBeenPwned)', $contentStyle);

$section->addTextBreak();

$section->addTitle('5.8 A08 Software and Data Integrity Failures', 2);
$section->addTextBreak();

$section->addText('Protection integrite :', $strongStyle);
$section->addText('Verification checksums packages Composer', $contentStyle);
$section->addText('Signature cryptographique deployments', $contentStyle);
$section->addText('Pipeline CI/CD avec controles integrite', $contentStyle);
$section->addText('Immutable infrastructure principe', $contentStyle);

$section->addTextBreak();

$section->addTitle('5.9 A09 Security Logging and Monitoring Failures', 2);
$section->addTextBreak();

$section->addText('Strategie logging securite :', $strongStyle);
$section->addText('Logs centralises avec ELK Stack', $contentStyle);
$section->addText('Alertes temps reel événements critiques', $contentStyle);
$section->addText('Retention logs 1 an minimum', $contentStyle);
$section->addText('Correlation events multi-sources', $contentStyle);

$section->addTextBreak();

$section->addTitle('5.10 A10 Server-Side Request Forgery (SSRF)', 2);
$section->addTextBreak();

$section->addText('Prevention SSRF :', $strongStyle);
$section->addText('Whitelist URLs externes autorisees', $contentStyle);
$section->addText('Validation stricte parametres URL', $contentStyle);
$section->addText('Isolation network services internes', $contentStyle);
$section->addText('Monitoring requetes sortantes anormales', $contentStyle);

// Nouvelle page
$section->addPageBreak();

// 6. SYSTEME DETECTION INTRUSION
$section->addTitle('6. Systeme de detection d\'intrusion (IDS)', 1);
$section->addTextBreak();

$section->addTitle('6.1 Monitoring et alertes temps reel', 2);
$section->addTextBreak();

$section->addText('Architecture monitoring :', $strongStyle);
$section->addText('Laravel Telescope pour debug et monitoring dev', $contentStyle);
$section->addText('ELK Stack (Elasticsearch, Logstash, Kibana) production', $contentStyle);
$section->addText('Prometheus + Grafana metriques systeme', $contentStyle);
$section->addText('Sentry pour monitoring erreurs applicatives', $contentStyle);

$section->addTextBreak();

$section->addText('Alertes configurees :', $strongStyle);
$section->addText('Tentatives connexion echoues repetees (5+)', $contentStyle);
$section->addText('Acces ressources non autorisees', $contentStyle);
$section->addText('Pics trafic anormaux (>200% baseline)', $contentStyle);
$section->addText('Erreurs applicatives critiques', $contentStyle);
$section->addText('Tentatives injection detectees', $contentStyle);

$section->addTextBreak();

$section->addTitle('6.2 Detection d\'anomalies comportementales', 2);
$section->addTextBreak();

$section->addText('Algorithmes de detection :', $strongStyle);
$section->addText('Baseline comportemental par utilisateur', $contentStyle);
$section->addText('Machine Learning pour patterns anormaux', $contentStyle);
$section->addText('Geolocalisation connexions suspectes', $contentStyle);
$section->addText('Analyse temporelle activites', $contentStyle);

$section->addTextBreak();

$section->addTitle('6.3 Rate limiting et protection DDoS', 2);
$section->addTextBreak();

// Tableau rate limiting
$rateLimitTable = $section->addTable(['borderSize' => 6, 'borderColor' => '999999']);
$rateLimitTable->addRow();
$rateLimitTable->addCell(2500)->addText('Endpoint', $strongStyle);
$rateLimitTable->addCell(2000)->addText('Limite/minute', $strongStyle);
$rateLimitTable->addCell(2000)->addText('Fenetre', $strongStyle);
$rateLimitTable->addCell(2500)->addText('Action depassement', $strongStyle);

$rateLimitTable->addRow();
$rateLimitTable->addCell(2500)->addText('Login', $contentStyle);
$rateLimitTable->addCell(2000)->addText('5 tentatives', $contentStyle);
$rateLimitTable->addCell(2000)->addText('15 min', $contentStyle);
$rateLimitTable->addCell(2500)->addText('Blocage IP temporaire', $contentStyle);

$rateLimitTable->addRow();
$rateLimitTable->addCell(2500)->addText('API produits', $contentStyle);
$rateLimitTable->addCell(2000)->addText('100 req/min', $contentStyle);
$rateLimitTable->addCell(2000)->addText('1 min', $contentStyle);
$rateLimitTable->addCell(2500)->addText('HTTP 429 + retry-after', $contentStyle);

$rateLimitTable->addRow();
$rateLimitTable->addCell(2500)->addText('Contact form', $contentStyle);
$rateLimitTable->addCell(2000)->addText('3 soumissions', $contentStyle);
$rateLimitTable->addCell(2000)->addText('10 min', $contentStyle);
$rateLimitTable->addCell(2500)->addText('CAPTCHA obligatoire', $contentStyle);

$rateLimitTable->addRow();
$rateLimitTable->addCell(2500)->addText('Paiement', $contentStyle);
$rateLimitTable->addCell(2000)->addText('10 tentatives', $contentStyle);
$rateLimitTable->addCell(2000)->addText('1 heure', $contentStyle);
$rateLimitTable->addCell(2500)->addText('Blocage compte + alerte', $contentStyle);

// Nouvelle page
$section->addPageBreak();

// 7. PLAN DE REPRISE D'ACTIVITE
$section->addTitle('7. Plan de reprise d\'activite (DRP)', 1);
$section->addTextBreak();

$section->addTitle('7.1 Strategie de sauvegarde', 2);
$section->addTextBreak();

$section->addText('Politique de sauvegarde 3-2-1 :', $strongStyle);
$section->addText('3 copies des donnees critiques', $contentStyle);
$section->addText('2 supports de stockage differents', $contentStyle);
$section->addText('1 copie hors site (cloud)', $contentStyle);

$section->addTextBreak();

$section->addText('Frequences de sauvegarde :', $strongStyle);
$section->addText('Base de donnees : snapshot quotidien + binlog temps reel', $contentStyle);
$section->addText('Fichiers utilisateurs : sauvegarde incrementale 4h', $contentStyle);
$section->addText('Configuration systeme : sauvegarde hebdomadaire', $contentStyle);
$section->addText('Code source : Git avec mirrors multiples', $contentStyle);

$section->addTextBreak();

$section->addText('Solutions techniques :', $strongStyle);
$section->addText('Laravel Backup package pour automatisation', $contentStyle);
$section->addText('AWS S3 pour stockage long terme', $contentStyle);
$section->addText('MySQL replication master-slave', $contentStyle);
$section->addText('Chiffrement GPG toutes sauvegardes', $contentStyle);

$section->addTextBreak();

$section->addTitle('7.2 Procedures de restauration', 2);
$section->addTextBreak();

$section->addText('RTO (Recovery Time Objective) cibles :', $strongStyle);
$section->addText('Services critiques : 2 heures maximum', $contentStyle);
$section->addText('Base de donnees : 1 heure maximum', $contentStyle);
$section->addText('Fonctionnalites secondaires : 8 heures', $contentStyle);

$section->addTextBreak();

$section->addText('RPO (Recovery Point Objective) :', $strongStyle);
$section->addText('Donnees transactionnelles : 15 minutes maximum', $contentStyle);
$section->addText('Fichiers utilisateurs : 4 heures maximum', $contentStyle);
$section->addText('Configurations : 24 heures maximum', $contentStyle);

$section->addTextBreak();

$section->addTitle('7.3 Plan de continuite de service', 2);
$section->addTextBreak();

$section->addText('Architecture haute disponibilite :', $strongStyle);
$section->addText('Load balancer avec health checks automatiques', $contentStyle);
$section->addText('Auto-scaling horizontal selon charge', $contentStyle);
$section->addText('Failover automatique base de donnees', $contentStyle);
$section->addText('CDN global pour assets statiques', $contentStyle);

$section->addTextBreak();

$section->addText('Procedures d\'escalade :', $strongStyle);
$section->addText('Niveau 1 : Alertes automatiques equipe technique', $contentStyle);
$section->addText('Niveau 2 : Notification management apres 30min', $contentStyle);
$section->addText('Niveau 3 : Communication clients apres 1h', $contentStyle);
$section->addText('Niveau 4 : Activation plan de crise', $contentStyle);

// Nouvelle page
$section->addPageBreak();

// 8. TESTS DE SECURITE
$section->addTitle('8. Tests de securite et audit', 1);
$section->addTextBreak();

$section->addTitle('8.1 Tests de penetration', 2);
$section->addTextBreak();

$section->addText('Methodologie de pentest :', $strongStyle);
$section->addText('OWASP Testing Guide v4.2 comme reference', $contentStyle);
$section->addText('Tests automatises avec OWASP ZAP', $contentStyle);
$section->addText('Pentest manuel trimestriel par expert externe', $contentStyle);
$section->addText('Bug bounty programme pour crowdsourced security', $contentStyle);

$section->addTextBreak();

$section->addText('Scenarios de test prioritaires :', $strongStyle);
$section->addText('Authentification et autorisation', $contentStyle);
$section->addText('Injection SQL et XSS', $contentStyle);
$section->addText('Logique metier (workflow location/achat)', $contentStyle);
$section->addText('API REST endpoints securite', $contentStyle);
$section->addText('Configuration infrastructure', $contentStyle);

$section->addTextBreak();

$section->addTitle('8.2 Audit de securite continu', 2);
$section->addTextBreak();

$section->addText('Outils d\'audit integres :', $strongStyle);
$section->addText('PHPStan pour analyse statique code PHP', $contentStyle);
$section->addText('SonarQube pour qualite et securite code', $contentStyle);
$section->addText('Snyk pour vulnerabilites dependencies', $contentStyle);
$section->addText('Laravel Enlightn pour audit configuration', $contentStyle);

$section->addTextBreak();

$section->addTitle('8.3 Veille technologique securite', 2);
$section->addTextBreak();

$section->addText('Sources de veille :', $strongStyle);
$section->addText('CVE Database pour vulnerabilites nouvelles', $contentStyle);
$section->addText('OWASP community updates', $contentStyle);
$section->addText('Laravel Security Advisories', $contentStyle);
$section->addText('PHP Security Advisories Database', $contentStyle);

$section->addTextBreak();

$section->addText('Processus de mise a jour :', $strongStyle);
$section->addText('Evaluation risque dans les 48h', $contentStyle);
$section->addText('Patch critique applique sous 7 jours', $contentStyle);
$section->addText('Test regression systematique', $contentStyle);
$section->addText('Communication equipe et stakeholders', $contentStyle);

// Nouvelle page
$section->addPageBreak();

// CONCLUSION
$section->addTitle('Conclusion', 1);
$section->addTextBreak();

$section->addText('Cette strategie de securite pour FarmShop etablit un cadre robuste et complet, integrant les meilleures pratiques industrielles et les recommandations OWASP. L\'approche Security by Design garantit que la securite n\'est pas un ajout posterieur mais une composante fondamentale de l\'architecture.', $contentStyle);

$section->addTextBreak();

$section->addText('Les mesures implementees couvrent l\'ensemble du spectre securitaire : de l\'authentification multi-facteurs a la detection d\'intrusion, en passant par la protection RGPD et le plan de reprise d\'activite. Cette strategie defensive multicouche reduit considerablement la surface d\'attaque et assure une resilience maximale.', $contentStyle);

$section->addTextBreak();

$section->addText('La conformite OWASP Top 10 et l\'integration d\'outils d\'audit automatises permettent un maintien continu du niveau de securite. Le plan de reprise d\'activite assure la continuite de service meme en cas d\'incident majeur.', $contentStyle);

$section->addTextBreak();

$section->addText('Cette strategie evolue avec les menaces et sera regulierement mise a jour selon les nouvelles vulnerabilites identifiees et les evolutions technologiques de l\'ecosysteme Laravel.', $contentStyle);

$section->addTextBreak(2);

// BIBLIOGRAPHIE
$section->addTitle('Bibliographie', 1);
$section->addTextBreak();

$section->addText('OWASP FOUNDATION. 2021. OWASP Top 10 - 2021. Site web sur INTERNET. <owasp.org/Top10>. Derniere consultation : le 17/07-2025.', $contentStyle);
$section->addTextBreak();

$section->addText('LARAVEL. S.d. Laravel Security Documentation. Site web sur INTERNET. <laravel.com/docs/11.x/security>. Derniere consultation : le 17/07-2025.', $contentStyle);
$section->addTextBreak();

$section->addText('NIST. 2018. Framework for Improving Critical Infrastructure Cybersecurity. Site web sur INTERNET. <nist.gov/cyberframework>. Derniere consultation : le 17/07-2025.', $contentStyle);
$section->addTextBreak();

$section->addText('COMMISSION EUROPEENNE. 2018. Reglement General sur la Protection des Donnees (RGPD). Site web sur INTERNET. <gdpr.eu>. Derniere consultation : le 17/07-2025.', $contentStyle);
$section->addTextBreak();

$section->addText('SANS INSTITUTE. S.d. Security by Design Principles. Site web sur INTERNET. <sans.org/security-resources>. Derniere consultation : le 17/07-2025.', $contentStyle);
$section->addTextBreak();

$section->addText('SPATIE. S.d. Laravel Permission Documentation. Site web sur INTERNET. <spatie.be/docs/laravel-permission>. Derniere consultation : le 17/07-2025.', $contentStyle);
$section->addTextBreak();

$section->addText('HAVE I BEEN PWNED. S.d. Pwned Passwords API. Site web sur INTERNET. <haveibeenpwned.com/API>. Derniere consultation : le 17/07-2025.', $contentStyle);
$section->addTextBreak();

$section->addText('STRIPE. S.d. PCI Compliance Guide. Site web sur INTERNET. <stripe.com/guides/pci-compliance>. Derniere consultation : le 17/07-2025.', $contentStyle);
$section->addTextBreak();

$section->addText('CLOUDFLARE. S.d. Security Best Practices. Site web sur INTERNET. <developers.cloudflare.com/security>. Derniere consultation : le 17/07-2025.', $contentStyle);

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
$objWriter->save(__DIR__ . '/16_Strategie_Securite.docx');

echo "Livrable 16 - Strategie de securite cree avec succes !\n";
echo "Emplacement : " . __DIR__ . "/16_Strategie_Securite.docx\n";
echo "Document complet avec Security by Design, OWASP Top 10, RGPD et DRP !\n";

?>
