<?php

/**
 * Applique automatiquement les traductions sur les pages principales
 * Convertit le texte français en appels de traduction __()
 */

class TranslationApplier
{
    private $translationFiles = [
        'fr' => 'lang/fr/app.php',
        'en' => 'lang/en/app.php', 
        'nl' => 'lang/nl/app.php'
    ];
    
    private $translations = [];
    
    private $commonReplacements = [
        // Navigation
        'Accueil' => '__("app.nav.home")',
        'Produits' => '__("app.nav.products")',
        'Locations' => '__("app.nav.rentals")',
        'Blog' => '__("app.nav.blog")',
        'Contact' => '__("app.nav.contact")',
        'Mon compte' => '__("app.nav.my_account")',
        'Panier' => '__("app.nav.cart")',
        'Liste de souhaits' => '__("app.nav.wishlist")',
        'Mes commandes' => '__("app.nav.orders")',
        'Profil' => '__("app.nav.profile")',
        'Se connecter' => '__("app.nav.login")',
        'Se déconnecter' => '__("app.nav.logout")',
        'S\'inscrire' => '__("app.nav.register")',
        
        // Boutons communs
        'Ajouter' => '__("app.buttons.add")',
        'Modifier' => '__("app.buttons.edit")',
        'Supprimer' => '__("app.buttons.delete")',
        'Enregistrer' => '__("app.buttons.save")',
        'Annuler' => '__("app.buttons.cancel")',
        'Confirmer' => '__("app.buttons.confirm")',
        'Soumettre' => '__("app.buttons.submit")',
        'Envoyer' => '__("app.buttons.send")',
        'Retour' => '__("app.buttons.back")',
        'Continuer' => '__("app.buttons.continue")',
        'Voir' => '__("app.buttons.view")',
        'Télécharger' => '__("app.buttons.download")',
        'Imprimer' => '__("app.buttons.print")',
        
        // Formulaires
        'Nom' => '__("app.forms.name")',
        'Prénom' => '__("app.forms.first_name")',
        'Nom de famille' => '__("app.forms.last_name")',
        'Email' => '__("app.forms.email")',
        'Mot de passe' => '__("app.forms.password")',
        'Téléphone' => '__("app.forms.phone")',
        'Adresse' => '__("app.forms.address")',
        'Ville' => '__("app.forms.city")',
        'Code postal' => '__("app.forms.postal_code")',
        'Message' => '__("app.forms.message")',
        'Description' => '__("app.forms.description")',
        'Obligatoire' => '__("app.forms.required")',
        'Optionnel' => '__("app.forms.optional")',
        
        // E-commerce
        'Prix' => '__("app.ecommerce.price")',
        'Quantité' => '__("app.ecommerce.quantity")',
        'Total' => '__("app.ecommerce.total")',
        'TVA' => '__("app.ecommerce.tax")',
        'Livraison' => '__("app.ecommerce.shipping")',
        'Remise' => '__("app.ecommerce.discount")',
        'Ajouter au panier' => '__("app.ecommerce.add_to_cart")',
        'Commander' => '__("app.ecommerce.checkout")',
        'Paiement' => '__("app.ecommerce.payment")',
        'Commande' => '__("app.ecommerce.order")',
        'Stock' => '__("app.ecommerce.stock")',
        'Disponible' => '__("app.ecommerce.available")',
        'Rupture de stock' => '__("app.ecommerce.out_of_stock")',
        'En stock' => '__("app.ecommerce.in_stock")',
        'Catégorie' => '__("app.ecommerce.category")',
        'Produit' => '__("app.ecommerce.product")',
        'Location' => '__("app.ecommerce.rental")',
        
        // Messages
        'Succès' => '__("app.messages.success")',
        'Erreur' => '__("app.messages.error")',
        'Attention' => '__("app.messages.warning")',
        'Information' => '__("app.messages.info")',
        'Chargement...' => '__("app.messages.loading")',
        'Aucun résultat trouvé' => '__("app.messages.no_results")',
        'Aucun élément' => '__("app.messages.empty")',
        'Merci' => '__("app.messages.thank_you")',
        'Bienvenue' => '__("app.messages.welcome")',
        'Êtes-vous sûr ?' => '__("app.messages.are_you_sure")',
        
        // Welcome
        'Bienvenue chez FarmShop' => '__("app.welcome.title")',
        'Votre boutique agricole en ligne' => '__("app.welcome.subtitle")',
        'Produits vedettes' => '__("app.welcome.featured_products")',
        'Dernières actualités' => '__("app.welcome.latest_news")',
        'Découvrir plus' => '__("app.welcome.discover_more")',
        'Acheter maintenant' => '__("app.welcome.shop_now")',
        'En savoir plus' => '__("app.welcome.learn_more")',
    ];

    public function applyTranslationsToFile($filePath, $outputPath = null)
    {
        if (!file_exists($filePath)) {
            echo "❌ Fichier non trouvé: {$filePath}\n";
            return false;
        }

        $content = file_get_contents($filePath);
        $originalContent = $content;
        
        echo "🔄 Traitement de: {$filePath}\n";
        
        $replacements = 0;
        
        // Appliquer les remplacements communs
        foreach ($this->commonReplacements as $french => $translation) {
            // Remplacer dans les guillemets doubles
            $pattern = '/"' . preg_quote($french, '/') . '"/';
            if (preg_match($pattern, $content)) {
                $content = preg_replace($pattern, $translation, $content);
                $replacements++;
            }
            
            // Remplacer dans les guillemets simples
            $pattern = "/'" . preg_quote($french, '/') . "'/";
            if (preg_match($pattern, $content)) {
                $content = preg_replace($pattern, $translation, $content);
                $replacements++;
            }
            
            // Remplacer les textes entre balises HTML (plus prudent)
            $pattern = '/>' . preg_quote($french, '/') . '</';
            if (preg_match($pattern, $content)) {
                $content = preg_replace($pattern, '>{{ ' . $translation . ' }}</', $content);
                $replacements++;
            }
        }
        
        if ($replacements > 0) {
            $outputFile = $outputPath ?: $filePath;
            
            // Créer une sauvegarde si on modifie le fichier original
            if ($outputFile === $filePath) {
                $backupFile = $filePath . '.backup.' . date('Y-m-d-H-i-s');
                file_put_contents($backupFile, $originalContent);
                echo "   💾 Sauvegarde créée: {$backupFile}\n";
            }
            
            file_put_contents($outputFile, $content);
            echo "   ✅ {$replacements} remplacements effectués\n";
            
            if ($outputFile !== $filePath) {
                echo "   📁 Fichier traduit: {$outputFile}\n";
            }
        } else {
            echo "   ℹ️  Aucun remplacement nécessaire\n";
        }
        
        return $replacements > 0;
    }

    public function processMainPages()
    {
        echo "🌍 Application des traductions sur les pages principales...\n";
        echo "============================================================\n";

        $mainPages = [
            'resources/views/welcome.blade.php',
            'resources/views/layouts/app.blade.php',
            'resources/views/web/products/index.blade.php',
            'resources/views/web/products/show.blade.php',
            'resources/views/web/rentals/index.blade.php', 
            'resources/views/web/rentals/show.blade.php',
            'resources/views/contact.blade.php',
            'resources/views/auth/login.blade.php',
            'resources/views/auth/register.blade.php',
            'resources/views/cart/simple.blade.php',
            'resources/views/checkout/index.blade.php',
            'resources/views/blog/index.blade.php',
            'resources/views/blog/show.blade.php',
            'resources/views/orders/index.blade.php',
            'resources/views/orders/show.blade.php',
            'resources/views/users/profile.blade.php',
            'resources/views/wishlist/index.blade.php',
        ];

        $totalProcessed = 0;
        $totalReplacements = 0;

        foreach ($mainPages as $page) {
            if (file_exists($page)) {
                $result = $this->applyTranslationsToFile($page);
                if ($result) {
                    $totalProcessed++;
                    $totalReplacements++;
                }
            } else {
                echo "⚠️  Page non trouvée: {$page}\n";
            }
        }

        echo "\n📊 Résumé:\n";
        echo "   Pages traitées: {$totalProcessed}/" . count($mainPages) . "\n";
        echo "   Fichiers modifiés: {$totalReplacements}\n";
        echo "\n🎯 Prochaines étapes:\n";
        echo "   1. Redémarrer le serveur Laravel\n";
        echo "   2. Tester les pages traduites\n";
        echo "   3. Vérifier le changement de langue\n";
        echo "   4. Ajuster les traductions si nécessaire\n";
    }

    public function addSpecificTranslations()
    {
        echo "📝 Ajout de traductions spécifiques...\n";
        
        // Ajouter plus de traductions spécifiques au contexte FarmShop
        $additionalTranslations = [
            // Pages spécifiques
            'pages' => [
                'fr' => [
                    'home_title' => 'Accueil - FarmShop',
                    'products_title' => 'Nos Produits',
                    'rentals_title' => 'Locations d\'Équipements',
                    'contact_title' => 'Contactez-nous',
                    'about_title' => 'À propos de FarmShop',
                    'cart_title' => 'Votre Panier',
                    'checkout_title' => 'Finaliser la commande',
                    'profile_title' => 'Mon Profil',
                    'orders_title' => 'Mes Commandes',
                    'wishlist_title' => 'Ma Liste de Souhaits',
                ],
                'en' => [
                    'home_title' => 'Home - FarmShop',
                    'products_title' => 'Our Products',
                    'rentals_title' => 'Equipment Rentals',
                    'contact_title' => 'Contact Us',
                    'about_title' => 'About FarmShop',
                    'cart_title' => 'Your Cart',
                    'checkout_title' => 'Checkout',
                    'profile_title' => 'My Profile',
                    'orders_title' => 'My Orders',
                    'wishlist_title' => 'My Wishlist',
                ],
                'nl' => [
                    'home_title' => 'Home - FarmShop',
                    'products_title' => 'Onze Producten',
                    'rentals_title' => 'Materiaalverhuur',
                    'contact_title' => 'Contact',
                    'about_title' => 'Over FarmShop',
                    'cart_title' => 'Uw Winkelwagen',
                    'checkout_title' => 'Afrekenen',
                    'profile_title' => 'Mijn Profiel',
                    'orders_title' => 'Mijn Bestellingen',
                    'wishlist_title' => 'Mijn Verlanglijst',
                ],
            ],
            
            // Status
            'status' => [
                'fr' => [
                    'pending' => 'En attente',
                    'confirmed' => 'Confirmé',
                    'processing' => 'En cours de traitement',
                    'shipped' => 'Expédié',
                    'delivered' => 'Livré',
                    'cancelled' => 'Annulé',
                    'returned' => 'Retourné',
                    'refunded' => 'Remboursé',
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
                ],
            ],
        ];

        // Mettre à jour les fichiers de traduction
        foreach (['en', 'nl'] as $locale) {
            $filePath = "lang/{$locale}/app.php";
            if (file_exists($filePath)) {
                $content = file_get_contents($filePath);
                
                // Ajouter les nouvelles sections
                foreach ($additionalTranslations as $section => $translations) {
                    if (isset($translations[$locale])) {
                        $sectionContent = "\n    // " . ucfirst($section) . "\n";
                        $sectionContent .= "    '{$section}' => [\n";
                        
                        foreach ($translations[$locale] as $key => $value) {
                            $sectionContent .= "        '{$key}' => '{$value}',\n";
                        }
                        
                        $sectionContent .= "    ],\n";
                        
                        // Insérer avant la fermeture du fichier
                        $content = str_replace("];", $sectionContent . "];", $content);
                    }
                }
                
                file_put_contents($filePath, $content);
                echo "   ✅ Fichier {$locale}/app.php mis à jour\n";
            }
        }
    }
}

// Exécution si appelé directement
if (basename(__FILE__) == basename($_SERVER['SCRIPT_NAME'])) {
    $applier = new TranslationApplier();
    
    echo "🚀 Démarrage de l'application des traductions...\n\n";
    
    // Ajouter les traductions spécifiques
    $applier->addSpecificTranslations();
    
    echo "\n";
    
    // Traiter les pages principales  
    $applier->processMainPages();
    
    echo "\n✅ Application des traductions terminée !\n";
}
