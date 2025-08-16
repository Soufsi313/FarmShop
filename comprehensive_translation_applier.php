<?php

/**
 * Application complète des traductions sur tout le site
 * Remplace tous les textes français par des appels __()
 */

class ComprehensiveTranslationApplier
{
    private $processedFiles = [];
    private $totalReplacements = 0;
    
    // Dictionnaire complet de traductions FR -> clé
    private $translationMap = [
        // Navigation
        'Accueil' => 'app.nav.home',
        'Produits' => 'app.nav.products', 
        'Locations' => 'app.nav.rentals',
        'Blog' => 'app.nav.blog',
        'Contact' => 'app.nav.contact',
        'À propos' => 'app.nav.about',
        'Newsletter' => 'app.nav.newsletter',
        'Mon compte' => 'app.nav.my_account',
        'Panier' => 'app.nav.cart',
        'Liste de souhaits' => 'app.nav.wishlist',
        'Mes commandes' => 'app.nav.orders',
        'Profil' => 'app.nav.profile',
        'Se connecter' => 'app.nav.login',
        'Se déconnecter' => 'app.nav.logout',
        'S\'inscrire' => 'app.nav.register',
        'Déconnexion' => 'app.nav.logout',
        'Connexion' => 'app.nav.login',
        'Inscription' => 'app.nav.register',
        
        // Boutons génériques
        'Ajouter' => 'app.buttons.add',
        'Modifier' => 'app.buttons.edit',
        'Supprimer' => 'app.buttons.delete',
        'Enregistrer' => 'app.buttons.save',
        'Annuler' => 'app.buttons.cancel',
        'Confirmer' => 'app.buttons.confirm',
        'Soumettre' => 'app.buttons.submit',
        'Envoyer' => 'app.buttons.send',
        'Retour' => 'app.buttons.back',
        'Continuer' => 'app.buttons.continue',
        'Voir' => 'app.buttons.view',
        'Afficher' => 'app.buttons.show',
        'Masquer' => 'app.buttons.hide',
        'Fermer' => 'app.buttons.close',
        'Ouvrir' => 'app.buttons.open',
        'Rechercher' => 'app.buttons.search',
        'Filtrer' => 'app.buttons.filter',
        'Trier' => 'app.buttons.sort',
        'Sélectionner' => 'app.buttons.select',
        'Choisir' => 'app.buttons.choose',
        'Télécharger' => 'app.buttons.download',
        'Imprimer' => 'app.buttons.print',
        'Exporter' => 'app.buttons.export',
        'Importer' => 'app.buttons.import',
        'Valider' => 'app.buttons.validate',
        'Rejeter' => 'app.buttons.reject',
        'Approuver' => 'app.buttons.approve',
        
        // Formulaires
        'Nom' => 'app.forms.name',
        'Prénom' => 'app.forms.first_name',
        'Nom de famille' => 'app.forms.last_name',
        'Email' => 'app.forms.email',
        'E-mail' => 'app.forms.email',
        'Mot de passe' => 'app.forms.password',
        'Confirmer le mot de passe' => 'app.forms.confirm_password',
        'Téléphone' => 'app.forms.phone',
        'Adresse' => 'app.forms.address',
        'Ville' => 'app.forms.city',
        'Code postal' => 'app.forms.postal_code',
        'Pays' => 'app.forms.country',
        'Message' => 'app.forms.message',
        'Sujet' => 'app.forms.subject',
        'Description' => 'app.forms.description',
        'Titre' => 'app.forms.title',
        'Contenu' => 'app.forms.content',
        'Obligatoire' => 'app.forms.required',
        'Optionnel' => 'app.forms.optional',
        'Champ obligatoire' => 'app.forms.required_field',
        
        // E-commerce
        'Prix' => 'app.ecommerce.price',
        'Quantité' => 'app.ecommerce.quantity',
        'Total' => 'app.ecommerce.total',
        'Sous-total' => 'app.ecommerce.subtotal',
        'TVA' => 'app.ecommerce.tax',
        'Livraison' => 'app.ecommerce.shipping',
        'Remise' => 'app.ecommerce.discount',
        'Ajouter au panier' => 'app.ecommerce.add_to_cart',
        'Commander' => 'app.ecommerce.checkout',
        'Paiement' => 'app.ecommerce.payment',
        'Commande' => 'app.ecommerce.order',
        'Commandes' => 'app.ecommerce.orders',
        'Stock' => 'app.ecommerce.stock',
        'Disponible' => 'app.ecommerce.available',
        'Rupture de stock' => 'app.ecommerce.out_of_stock',
        'En stock' => 'app.ecommerce.in_stock',
        'Catégorie' => 'app.ecommerce.category',
        'Catégories' => 'app.ecommerce.categories',
        'Produit' => 'app.ecommerce.product',
        'Location' => 'app.ecommerce.rental',
        'Facture' => 'app.ecommerce.invoice',
        'Bon de commande' => 'app.ecommerce.order_form',
        
        // Messages et statuts
        'Succès' => 'app.messages.success',
        'Erreur' => 'app.messages.error',
        'Attention' => 'app.messages.warning',
        'Information' => 'app.messages.info',
        'Chargement...' => 'app.messages.loading',
        'Aucun résultat trouvé' => 'app.messages.no_results',
        'Aucun élément' => 'app.messages.empty',
        'Merci' => 'app.messages.thank_you',
        'Bienvenue' => 'app.messages.welcome',
        'Veuillez patienter' => 'app.messages.please_wait',
        'Êtes-vous sûr ?' => 'app.messages.are_you_sure',
        'Opération réussie' => 'app.messages.operation_success',
        'Opération échouée' => 'app.messages.operation_failed',
        
        // Statuts
        'En attente' => 'app.status.pending',
        'Confirmé' => 'app.status.confirmed',
        'En cours' => 'app.status.processing',
        'Expédié' => 'app.status.shipped',
        'Livré' => 'app.status.delivered',
        'Annulé' => 'app.status.cancelled',
        'Retourné' => 'app.status.returned',
        'Remboursé' => 'app.status.refunded',
        'Actif' => 'app.status.active',
        'Inactif' => 'app.status.inactive',
        
        // Dates et temps
        'Aujourd\'hui' => 'app.time.today',
        'Hier' => 'app.time.yesterday',
        'Demain' => 'app.time.tomorrow',
        'Semaine' => 'app.time.week',
        'Mois' => 'app.time.month',
        'Année' => 'app.time.year',
        'Date' => 'app.time.date',
        'Heure' => 'app.time.time',
        
        // Phrases communes
        'Oui' => 'app.common.yes',
        'Non' => 'app.common.no',
        'ou' => 'app.common.or',
        'et' => 'app.common.and',
        'Tous' => 'app.common.all',
        'Aucun' => 'app.common.none',
        'Plus' => 'app.common.more',
        'Moins' => 'app.common.less',
        'Aide' => 'app.common.help',
        'Nouveau' => 'app.common.new',
        'Ancien' => 'app.common.old',
        'Premier' => 'app.common.first',
        'Dernier' => 'app.common.last',
        'Suivant' => 'app.common.next',
        'Précédent' => 'app.common.previous',
        
        // Pages spécifiques
        'Tableau de bord' => 'app.pages.dashboard',
        'Profil utilisateur' => 'app.pages.user_profile',
        'Paramètres' => 'app.pages.settings',
        'Administration' => 'app.pages.admin',
        'Statistiques' => 'app.pages.statistics',
        'Rapports' => 'app.pages.reports',
        'Gestion' => 'app.pages.management',
        
        // Authentification
        'Connexion' => 'app.auth.login',
        'Déconnexion' => 'app.auth.logout',
        'Inscription' => 'app.auth.register',
        'Mot de passe oublié' => 'app.auth.forgot_password',
        'Se souvenir de moi' => 'app.auth.remember_me',
        'Créer un compte' => 'app.auth.create_account',
        'Déjà inscrit ?' => 'app.auth.already_registered',
        'Pas encore inscrit ?' => 'app.auth.not_registered_yet',
        
        // Légal
        'Mentions légales' => 'app.legal.legal_notices',
        'Politique de confidentialité' => 'app.legal.privacy_policy',
        'Conditions générales' => 'app.legal.terms_of_service',
        'Cookies' => 'app.legal.cookies',
        'RGPD' => 'app.legal.gdpr',
        
        // Welcome page spécifique
        'Bienvenue chez FarmShop' => 'app.welcome.hero_title',
        'Découvrir nos produits' => 'app.welcome.shop_now',
        'Louer du matériel' => 'app.welcome.rent_equipment',
        'Nouveau client ?' => 'app.welcome.new_customer',
        'Créer mon compte gratuit' => 'app.welcome.create_account',
        'Déjà membre ?' => 'app.welcome.already_member',
        'Me connecter' => 'app.welcome.login_button',
    ];
    
    // Fichiers à traiter en priorité
    private $priorityFiles = [
        'resources/views/layouts/app.blade.php',
        'resources/views/welcome.blade.php',
        'resources/views/web/products/index.blade.php',
        'resources/views/web/products/show.blade.php',
        'resources/views/web/products/category.blade.php',
        'resources/views/web/rentals/index.blade.php',
        'resources/views/web/rentals/show.blade.php',
        'resources/views/contact.blade.php',
        'resources/views/blog/index.blade.php',
        'resources/views/blog/show.blade.php',
        'resources/views/auth/login.blade.php',
        'resources/views/auth/register.blade.php',
        'resources/views/cart/simple.blade.php',
        'resources/views/checkout/index.blade.php',
        'resources/views/orders/index.blade.php',
        'resources/views/orders/show.blade.php',
        'resources/views/users/profile.blade.php',
        'resources/views/wishlist/index.blade.php',
        'resources/views/my-rentals/index.blade.php',
        'resources/views/my-rentals/show.blade.php',
        'resources/views/payment/success.blade.php',
        'resources/views/payment/stripe-new.blade.php',
        'resources/views/legal/privacy.blade.php',
        'resources/views/legal/mentions.blade.php',
        'resources/views/legal/cgu.blade.php',
        'resources/views/legal/cgv.blade.php',
        'resources/views/newsletter/unsubscribe.blade.php',
    ];

    public function applyComprehensiveTranslations()
    {
        echo "🌍 Application complète des traductions sur tout le site\n";
        echo "=========================================================\n\n";

        // 1. D'abord, étendre les fichiers de traduction
        $this->extendTranslationFiles();
        
        echo "\n";
        
        // 2. Traiter les fichiers prioritaires
        $this->processPriorityFiles();
        
        echo "\n";
        
        // 3. Traiter tous les autres fichiers Blade
        $this->processAllBladeFiles();
        
        $this->showSummary();
    }
    
    private function extendTranslationFiles()
    {
        echo "📝 Extension des fichiers de traduction...\n";
        
        $additionalSections = [
            'status' => [
                'fr' => [
                    'pending' => 'En attente',
                    'confirmed' => 'Confirmé', 
                    'processing' => 'En cours',
                    'shipped' => 'Expédié',
                    'delivered' => 'Livré',
                    'cancelled' => 'Annulé',
                    'returned' => 'Retourné',
                    'refunded' => 'Remboursé',
                    'active' => 'Actif',
                    'inactive' => 'Inactif',
                ],
                'en' => [
                    'pending' => 'Pending',
                    'confirmed' => 'Confirmed',
                    'processing' => 'Processing', 
                    'shipped' => 'Shipped',
                    'delivered' => 'Delivered',
                    'cancelled' => 'Cancelled',
                    'returned' => 'Returned',
                    'refunded' => 'Refunded',
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                ],
                'nl' => [
                    'pending' => 'In behandeling',
                    'confirmed' => 'Bevestigd',
                    'processing' => 'Wordt verwerkt',
                    'shipped' => 'Verzonden', 
                    'delivered' => 'Bezorgd',
                    'cancelled' => 'Geannuleerd',
                    'returned' => 'Geretourneerd',
                    'refunded' => 'Terugbetaald',
                    'active' => 'Actief',
                    'inactive' => 'Inactief',
                ],
            ],
            'time' => [
                'fr' => [
                    'today' => 'Aujourd\'hui',
                    'yesterday' => 'Hier',
                    'tomorrow' => 'Demain', 
                    'week' => 'Semaine',
                    'month' => 'Mois',
                    'year' => 'Année',
                    'date' => 'Date',
                    'time' => 'Heure',
                ],
                'en' => [
                    'today' => 'Today',
                    'yesterday' => 'Yesterday',
                    'tomorrow' => 'Tomorrow',
                    'week' => 'Week',
                    'month' => 'Month', 
                    'year' => 'Year',
                    'date' => 'Date',
                    'time' => 'Time',
                ],
                'nl' => [
                    'today' => 'Vandaag',
                    'yesterday' => 'Gisteren',
                    'tomorrow' => 'Morgen',
                    'week' => 'Week',
                    'month' => 'Maand',
                    'year' => 'Jaar',
                    'date' => 'Datum', 
                    'time' => 'Tijd',
                ],
            ],
            'common' => [
                'fr' => [
                    'yes' => 'Oui',
                    'no' => 'Non',
                    'or' => 'ou',
                    'and' => 'et',
                    'all' => 'Tous',
                    'none' => 'Aucun',
                    'more' => 'Plus',
                    'less' => 'Moins',
                    'help' => 'Aide',
                    'new' => 'Nouveau',
                    'old' => 'Ancien',
                    'first' => 'Premier',
                    'last' => 'Dernier',
                    'next' => 'Suivant',
                    'previous' => 'Précédent',
                ],
                'en' => [
                    'yes' => 'Yes',
                    'no' => 'No',
                    'or' => 'or',
                    'and' => 'and',
                    'all' => 'All',
                    'none' => 'None',
                    'more' => 'More',
                    'less' => 'Less',
                    'help' => 'Help',
                    'new' => 'New',
                    'old' => 'Old', 
                    'first' => 'First',
                    'last' => 'Last',
                    'next' => 'Next',
                    'previous' => 'Previous',
                ],
                'nl' => [
                    'yes' => 'Ja',
                    'no' => 'Nee',
                    'or' => 'of',
                    'and' => 'en',
                    'all' => 'Alle',
                    'none' => 'Geen',
                    'more' => 'Meer',
                    'less' => 'Minder',
                    'help' => 'Help',
                    'new' => 'Nieuw',
                    'old' => 'Oud',
                    'first' => 'Eerste',
                    'last' => 'Laatste',
                    'next' => 'Volgende',
                    'previous' => 'Vorige',
                ],
            ],
            'auth' => [
                'fr' => [
                    'login' => 'Connexion',
                    'logout' => 'Déconnexion',
                    'register' => 'Inscription',
                    'forgot_password' => 'Mot de passe oublié',
                    'remember_me' => 'Se souvenir de moi',
                    'create_account' => 'Créer un compte',
                    'already_registered' => 'Déjà inscrit ?',
                    'not_registered_yet' => 'Pas encore inscrit ?',
                ],
                'en' => [
                    'login' => 'Login',
                    'logout' => 'Logout',
                    'register' => 'Register',
                    'forgot_password' => 'Forgot Password',
                    'remember_me' => 'Remember Me',
                    'create_account' => 'Create Account',
                    'already_registered' => 'Already registered?',
                    'not_registered_yet' => 'Not registered yet?',
                ],
                'nl' => [
                    'login' => 'Inloggen',
                    'logout' => 'Uitloggen',
                    'register' => 'Registreren',
                    'forgot_password' => 'Wachtwoord vergeten',
                    'remember_me' => 'Onthouden',
                    'create_account' => 'Account aanmaken',
                    'already_registered' => 'Al geregistreerd?',
                    'not_registered_yet' => 'Nog niet geregistreerd?',
                ],
            ],
            'pages' => [
                'fr' => [
                    'dashboard' => 'Tableau de bord',
                    'user_profile' => 'Profil utilisateur',
                    'settings' => 'Paramètres',
                    'admin' => 'Administration',
                    'statistics' => 'Statistiques',
                    'reports' => 'Rapports',
                    'management' => 'Gestion',
                ],
                'en' => [
                    'dashboard' => 'Dashboard',
                    'user_profile' => 'User Profile',
                    'settings' => 'Settings',
                    'admin' => 'Administration',
                    'statistics' => 'Statistics',
                    'reports' => 'Reports',
                    'management' => 'Management',
                ],
                'nl' => [
                    'dashboard' => 'Dashboard',
                    'user_profile' => 'Gebruikersprofiel',
                    'settings' => 'Instellingen',
                    'admin' => 'Beheer',
                    'statistics' => 'Statistieken',
                    'reports' => 'Rapporten',
                    'management' => 'Beheer',
                ],
            ],
            'legal' => [
                'fr' => [
                    'legal_notices' => 'Mentions légales',
                    'privacy_policy' => 'Politique de confidentialité',
                    'terms_of_service' => 'Conditions générales',
                    'cookies' => 'Cookies',
                    'gdpr' => 'RGPD',
                ],
                'en' => [
                    'legal_notices' => 'Legal Notices',
                    'privacy_policy' => 'Privacy Policy',
                    'terms_of_service' => 'Terms of Service',
                    'cookies' => 'Cookies',
                    'gdpr' => 'GDPR',
                ],
                'nl' => [
                    'legal_notices' => 'Juridische kennisgevingen',
                    'privacy_policy' => 'Privacybeleid',
                    'terms_of_service' => 'Algemene voorwaarden',
                    'cookies' => 'Cookies',
                    'gdpr' => 'AVG',
                ],
            ],
        ];

        foreach (['fr', 'en', 'nl'] as $locale) {
            $filePath = "lang/{$locale}/app.php";
            if (file_exists($filePath)) {
                $content = file_get_contents($filePath);
                
                foreach ($additionalSections as $section => $translations) {
                    if (isset($translations[$locale])) {
                        $sectionContent = "\n    // " . ucfirst($section) . "\n";
                        $sectionContent .= "    '{$section}' => [\n";
                        
                        foreach ($translations[$locale] as $key => $value) {
                            $sectionContent .= "        '{$key}' => '{$value}',\n";
                        }
                        
                        $sectionContent .= "    ],\n";
                        
                        if (!strpos($content, "'{$section}' =>")) {
                            $content = str_replace("];", $sectionContent . "];", $content);
                        }
                    }
                }
                
                file_put_contents($filePath, $content);
                echo "   ✅ {$locale}/app.php étendu\n";
            }
        }
    }
    
    private function processPriorityFiles()
    {
        echo "🎯 Traitement des fichiers prioritaires...\n";
        
        foreach ($this->priorityFiles as $file) {
            if (file_exists($file)) {
                $this->processFile($file);
            } else {
                echo "   ⚠️  Fichier non trouvé: {$file}\n";
            }
        }
    }
    
    private function processAllBladeFiles()
    {
        echo "📁 Traitement de tous les fichiers Blade...\n";
        
        $finder = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator('resources/views')
        );
        
        foreach ($finder as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $filePath = str_replace('\\', '/', $file->getPathname());
                
                // Exclure les vues admin et certains fichiers
                if (strpos($filePath, '/admin/') !== false || 
                    strpos($filePath, '/vendor/') !== false ||
                    in_array($filePath, $this->processedFiles)) {
                    continue;
                }
                
                $this->processFile($filePath);
            }
        }
    }
    
    private function processFile($filePath)
    {
        if (in_array($filePath, $this->processedFiles)) {
            return;
        }
        
        $content = file_get_contents($filePath);
        $originalContent = $content;
        $replacements = 0;
        
        // Appliquer les remplacements
        foreach ($this->translationMap as $french => $translationKey) {
            // Remplacer dans les guillemets doubles
            $pattern = '/"' . preg_quote($french, '/') . '"/u';
            if (preg_match($pattern, $content)) {
                $content = preg_replace($pattern, '{{ __("' . $translationKey . '") }}', $content);
                $replacements++;
            }
            
            // Remplacer dans les guillemets simples
            $pattern = "/'" . preg_quote($french, '/') . "'/u";
            if (preg_match($pattern, $content)) {
                $content = preg_replace($pattern, '{{ __("' . $translationKey . '") }}', $content);
                $replacements++;
            }
            
            // Remplacer les textes entre balises HTML simples
            $pattern = '/>' . preg_quote($french, '/') . '</u';
            if (preg_match($pattern, $content)) {
                $content = preg_replace($pattern, '>{{ __("' . $translationKey . '") }}<', $content);
                $replacements++;
            }
            
            // Remplacer dans les value et placeholder d'input
            $pattern = '/value="' . preg_quote($french, '/') . '"/u';
            if (preg_match($pattern, $content)) {
                $content = preg_replace($pattern, 'value="{{ __("' . $translationKey . '") }}"', $content);
                $replacements++;
            }
            
            $pattern = '/placeholder="' . preg_quote($french, '/') . '"/u';
            if (preg_match($pattern, $content)) {
                $content = preg_replace($pattern, 'placeholder="{{ __("' . $translationKey . '") }}"', $content);
                $replacements++;
            }
        }
        
        if ($replacements > 0) {
            // Créer une sauvegarde
            $backupFile = $filePath . '.backup.' . date('Y-m-d-H-i-s');
            file_put_contents($backupFile, $originalContent);
            
            // Sauvegarder le fichier modifié
            file_put_contents($filePath, $content);
            
            echo "   ✅ {$filePath}: {$replacements} remplacements\n";
            $this->totalReplacements += $replacements;
        } else {
            echo "   ℹ️  {$filePath}: aucun remplacement\n";
        }
        
        $this->processedFiles[] = $filePath;
    }
    
    private function showSummary()
    {
        echo "\n📊 Résumé de l'application des traductions:\n";
        echo "==========================================\n";
        echo "   Fichiers traités: " . count($this->processedFiles) . "\n";
        echo "   Total remplacements: {$this->totalReplacements}\n";
        echo "\n🎯 Actions suivantes:\n";
        echo "   1. Vérifiez que toutes les pages affichent bien les traductions\n";
        echo "   2. Testez le changement de langue sur différentes pages\n";
        echo "   3. Ajustez les traductions si nécessaire\n";
        echo "   4. Supprimez les fichiers de sauvegarde une fois satisfait\n";
        echo "\n✅ Application complète des traductions terminée !\n";
    }
}

// Exécution
if (basename(__FILE__) == basename($_SERVER['SCRIPT_NAME'])) {
    $applier = new ComprehensiveTranslationApplier();
    $applier->applyComprehensiveTranslations();
}
