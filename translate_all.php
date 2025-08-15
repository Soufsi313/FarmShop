#!/usr/bin/env php
<?php

/**
 * Script de traduction automatique avancé
 * Traite massivement les pages de FarmShop
 */

require_once __DIR__ . '/vendor/autoload.php';

class AdvancedTranslationProcessor
{
    private $patterns = [
        // Patterns de contenu à traduire
        'buttons' => [
            'Ajouter au panier' => '__("app.ecommerce.add_to_cart")',
            'Voir les détails' => '__("app.buttons.view_details")',
            'En savoir plus' => '__("app.buttons.learn_more")',
            'Commander maintenant' => '__("app.ecommerce.order_now")',
            'Retour à la liste' => '__("app.buttons.back_to_list")',
            'Suivant' => '__("app.buttons.next")',
            'Précédent' => '__("app.buttons.previous")',
            'Rechercher' => '__("app.buttons.search")',
            'Filtrer' => '__("app.buttons.filter")',
            'Trier par' => '__("app.buttons.sort_by")',
            'Afficher tout' => '__("app.buttons.show_all")',
            'Masquer' => '__("app.buttons.hide")',
            'Afficher' => '__("app.buttons.show")',
            'Télécharger le PDF' => '__("app.buttons.download_pdf")',
            'Partager' => '__("app.buttons.share")',
            'Favoris' => '__("app.buttons.favorites")',
            'Noter ce produit' => '__("app.buttons.rate_product")',
            'Signaler un problème' => '__("app.buttons.report_issue")',
            'Aide' => '__("app.buttons.help")',
        ],
        
        'ecommerce' => [
            'Prix' => '__("app.ecommerce.price")',
            'À partir de' => '__("app.ecommerce.from")',
            'TVA incluse' => '__("app.ecommerce.tax_included")',
            'Livraison gratuite' => '__("app.ecommerce.free_shipping")',
            'Expédition sous' => '__("app.ecommerce.ships_in")',
            'Garantie' => '__("app.ecommerce.warranty")',
            'Retour gratuit' => '__("app.ecommerce.free_return")',
            'Stock limité' => '__("app.ecommerce.limited_stock")',
            'Derniers articles' => '__("app.ecommerce.last_items")',
            'Promotion' => '__("app.ecommerce.promotion")',
            'Soldes' => '__("app.ecommerce.sales")',
            'Nouveauté' => '__("app.ecommerce.new")',
            'Bestseller' => '__("app.ecommerce.bestseller")',
            'Recommandé' => '__("app.ecommerce.recommended")',
            'Note moyenne' => '__("app.ecommerce.average_rating")',
            'Avis clients' => '__("app.ecommerce.customer_reviews")',
            'Comparer' => '__("app.ecommerce.compare")',
            'Wishlist' => '__("app.ecommerce.wishlist")',
            'Panier' => '__("app.ecommerce.cart")',
            'Commande' => '__("app.ecommerce.order")',
            'Facturation' => '__("app.ecommerce.billing")',
            'Livraison' => '__("app.ecommerce.delivery")',
            'Paiement sécurisé' => '__("app.ecommerce.secure_payment")',
        ],
        
        'forms' => [
            'Nom complet' => '__("app.forms.full_name")',
            'Prénom' => '__("app.forms.first_name")',
            'Nom de famille' => '__("app.forms.last_name")',
            'Adresse e-mail' => '__("app.forms.email_address")',
            'Numéro de téléphone' => '__("app.forms.phone_number")',
            'Adresse de livraison' => '__("app.forms.delivery_address")',
            'Code postal' => '__("app.forms.postal_code")',
            'Ville' => '__("app.forms.city")',
            'Pays' => '__("app.forms.country")',
            'Date de naissance' => '__("app.forms.birth_date")',
            'Sexe' => '__("app.forms.gender")',
            'Profession' => '__("app.forms.profession")',
            'Entreprise' => '__("app.forms.company")',
            'Site web' => '__("app.forms.website")',
            'Message' => '__("app.forms.message")',
            'Commentaire' => '__("app.forms.comment")',
            'Sujet' => '__("app.forms.subject")',
            'Description' => '__("app.forms.description")',
            'Mot de passe' => '__("app.forms.password")',
            'Confirmer le mot de passe' => '__("app.forms.confirm_password")',
            'Mot de passe actuel' => '__("app.forms.current_password")',
            'Nouveau mot de passe' => '__("app.forms.new_password")',
            'Se souvenir de moi' => '__("app.forms.remember_me")',
            'J\'accepte les conditions' => '__("app.forms.accept_terms")',
            'Newsletter' => '__("app.forms.newsletter")',
            'Champ obligatoire' => '__("app.forms.required_field")',
            'Champ optionnel' => '__("app.forms.optional_field")',
        ],
        
        'status' => [
            'En cours' => '__("app.status.in_progress")',
            'Terminé' => '__("app.status.completed")',
            'En attente' => '__("app.status.pending")',
            'Confirmé' => '__("app.status.confirmed")',
            'Annulé' => '__("app.status.cancelled")',
            'Expédié' => '__("app.status.shipped")',
            'Livré' => '__("app.status.delivered")',
            'Retourné' => '__("app.status.returned")',
            'Remboursé' => '__("app.status.refunded")',
            'En préparation' => '__("app.status.preparing")',
            'Validé' => '__("app.status.validated")',
            'Rejeté' => '__("app.status.rejected")',
            'Actif' => '__("app.status.active")',
            'Inactif' => '__("app.status.inactive")',
            'Publié' => '__("app.status.published")',
            'Brouillon' => '__("app.status.draft")',
            'Archivé' => '__("app.status.archived")',
        ],

        'time' => [
            'Aujourd\'hui' => '__("app.time.today")',
            'Hier' => '__("app.time.yesterday")',
            'Demain' => '__("app.time.tomorrow")',
            'Cette semaine' => '__("app.time.this_week")',
            'La semaine dernière' => '__("app.time.last_week")',
            'La semaine prochaine' => '__("app.time.next_week")',
            'Ce mois-ci' => '__("app.time.this_month")',
            'Le mois dernier' => '__("app.time.last_month")',
            'Le mois prochain' => '__("app.time.next_month")',
            'Cette année' => '__("app.time.this_year")',
            'L\'année dernière' => '__("app.time.last_year")',
            'L\'année prochaine' => '__("app.time.next_year")',
            'Lundi' => '__("app.time.monday")',
            'Mardi' => '__("app.time.tuesday")',
            'Mercredi' => '__("app.time.wednesday")',
            'Jeudi' => '__("app.time.thursday")',
            'Vendredi' => '__("app.time.friday")',
            'Samedi' => '__("app.time.saturday")',
            'Dimanche' => '__("app.time.sunday")',
            'Janvier' => '__("app.time.january")',
            'Février' => '__("app.time.february")',
            'Mars' => '__("app.time.march")',
            'Avril' => '__("app.time.april")',
            'Mai' => '__("app.time.may")',
            'Juin' => '__("app.time.june")',
            'Juillet' => '__("app.time.july")',
            'Août' => '__("app.time.august")',
            'Septembre' => '__("app.time.september")',
            'Octobre' => '__("app.time.october")',
            'Novembre' => '__("app.time.november")',
            'Décembre' => '__("app.time.december")',
        ],

        'messages' => [
            'Opération réussie' => '__("app.messages.operation_successful")',
            'Erreur lors de l\'opération' => '__("app.messages.operation_error")',
            'Données sauvegardées' => '__("app.messages.data_saved")',
            'Élément supprimé' => '__("app.messages.item_deleted")',
            'Élément ajouté' => '__("app.messages.item_added")',
            'Modifications enregistrées' => '__("app.messages.changes_saved")',
            'Veuillez patienter' => '__("app.messages.please_wait")',
            'Chargement en cours' => '__("app.messages.loading_in_progress")',
            'Connexion réussie' => '__("app.messages.login_successful")',
            'Déconnexion réussie' => '__("app.messages.logout_successful")',
            'Mot de passe incorrect' => '__("app.messages.incorrect_password")',
            'Email non trouvé' => '__("app.messages.email_not_found")',
            'Compte créé avec succès' => '__("app.messages.account_created")',
            'Profil mis à jour' => '__("app.messages.profile_updated")',
            'Commande confirmée' => '__("app.messages.order_confirmed")',
            'Paiement réussi' => '__("app.messages.payment_successful")',
            'Paiement échoué' => '__("app.messages.payment_failed")',
            'Article ajouté au panier' => '__("app.messages.item_added_to_cart")',
            'Article retiré du panier' => '__("app.messages.item_removed_from_cart")',
            'Panier vide' => '__("app.messages.cart_empty")',
            'Stock insuffisant' => '__("app.messages.insufficient_stock")',
            'Produit indisponible' => '__("app.messages.product_unavailable")',
            'Livraison prévue le' => '__("app.messages.delivery_scheduled")',
            'Commande expédiée' => '__("app.messages.order_shipped")',
            'Commande livrée' => '__("app.messages.order_delivered")',
            'Retour accepté' => '__("app.messages.return_accepted")',
            'Remboursement effectué' => '__("app.messages.refund_processed")',
        ]
    ];

    public function extendTranslationFiles()
    {
        echo "🌍 Extension des fichiers de traduction...\n";
        
        $translations = [
            'buttons' => [
                'fr' => [
                    'view_details' => 'Voir les détails',
                    'learn_more' => 'En savoir plus',
                    'order_now' => 'Commander maintenant',
                    'back_to_list' => 'Retour à la liste',
                    'next' => 'Suivant',
                    'previous' => 'Précédent',
                    'search' => 'Rechercher',
                    'filter' => 'Filtrer',
                    'sort_by' => 'Trier par',
                    'show_all' => 'Afficher tout',
                    'hide' => 'Masquer',
                    'show' => 'Afficher',
                    'download_pdf' => 'Télécharger le PDF',
                    'share' => 'Partager',
                    'favorites' => 'Favoris',
                    'rate_product' => 'Noter ce produit',
                    'report_issue' => 'Signaler un problème',
                    'help' => 'Aide',
                ],
                'en' => [
                    'view_details' => 'View Details',
                    'learn_more' => 'Learn More',
                    'order_now' => 'Order Now',
                    'back_to_list' => 'Back to List',
                    'next' => 'Next',
                    'previous' => 'Previous',
                    'search' => 'Search',
                    'filter' => 'Filter',
                    'sort_by' => 'Sort By',
                    'show_all' => 'Show All',
                    'hide' => 'Hide',
                    'show' => 'Show',
                    'download_pdf' => 'Download PDF',
                    'share' => 'Share',
                    'favorites' => 'Favorites',
                    'rate_product' => 'Rate this Product',
                    'report_issue' => 'Report an Issue',
                    'help' => 'Help',
                ],
                'nl' => [
                    'view_details' => 'Details Bekijken',
                    'learn_more' => 'Meer Weten',
                    'order_now' => 'Nu Bestellen',
                    'back_to_list' => 'Terug naar Lijst',
                    'next' => 'Volgende',
                    'previous' => 'Vorige',
                    'search' => 'Zoeken',
                    'filter' => 'Filteren',
                    'sort_by' => 'Sorteren Op',
                    'show_all' => 'Alles Tonen',
                    'hide' => 'Verbergen',
                    'show' => 'Tonen',
                    'download_pdf' => 'PDF Downloaden',
                    'share' => 'Delen',
                    'favorites' => 'Favorieten',
                    'rate_product' => 'Product Beoordelen',
                    'report_issue' => 'Probleem Melden',
                    'help' => 'Help',
                ],
            ],
            
            'ecommerce' => [
                'fr' => [
                    'from' => 'À partir de',
                    'tax_included' => 'TVA incluse',
                    'free_shipping' => 'Livraison gratuite',
                    'ships_in' => 'Expédition sous',
                    'warranty' => 'Garantie',
                    'free_return' => 'Retour gratuit',
                    'limited_stock' => 'Stock limité',
                    'last_items' => 'Derniers articles',
                    'promotion' => 'Promotion',
                    'sales' => 'Soldes',
                    'new' => 'Nouveauté',
                    'bestseller' => 'Bestseller',
                    'recommended' => 'Recommandé',
                    'average_rating' => 'Note moyenne',
                    'customer_reviews' => 'Avis clients',
                    'compare' => 'Comparer',
                    'wishlist' => 'Wishlist',
                    'cart' => 'Panier',
                    'order' => 'Commande',
                    'billing' => 'Facturation',
                    'delivery' => 'Livraison',
                    'secure_payment' => 'Paiement sécurisé',
                ],
                'en' => [
                    'from' => 'From',
                    'tax_included' => 'Tax Included',
                    'free_shipping' => 'Free Shipping',
                    'ships_in' => 'Ships in',
                    'warranty' => 'Warranty',
                    'free_return' => 'Free Return',
                    'limited_stock' => 'Limited Stock',
                    'last_items' => 'Last Items',
                    'promotion' => 'Promotion',
                    'sales' => 'Sales',
                    'new' => 'New',
                    'bestseller' => 'Bestseller',
                    'recommended' => 'Recommended',
                    'average_rating' => 'Average Rating',
                    'customer_reviews' => 'Customer Reviews',
                    'compare' => 'Compare',
                    'wishlist' => 'Wishlist',
                    'cart' => 'Cart',
                    'order' => 'Order',
                    'billing' => 'Billing',
                    'delivery' => 'Delivery',
                    'secure_payment' => 'Secure Payment',
                ],
                'nl' => [
                    'from' => 'Vanaf',
                    'tax_included' => 'BTW Inbegrepen',
                    'free_shipping' => 'Gratis Verzending',
                    'ships_in' => 'Verzonden binnen',
                    'warranty' => 'Garantie',
                    'free_return' => 'Gratis Retour',
                    'limited_stock' => 'Beperkte Voorraad',
                    'last_items' => 'Laatste Artikelen',
                    'promotion' => 'Promotie',
                    'sales' => 'Uitverkoop',
                    'new' => 'Nieuw',
                    'bestseller' => 'Bestseller',
                    'recommended' => 'Aanbevolen',
                    'average_rating' => 'Gemiddelde Beoordeling',
                    'customer_reviews' => 'Klantbeoordelingen',
                    'compare' => 'Vergelijken',
                    'wishlist' => 'Verlanglijst',
                    'cart' => 'Winkelwagen',
                    'order' => 'Bestelling',
                    'billing' => 'Facturering',
                    'delivery' => 'Bezorging',
                    'secure_payment' => 'Veilige Betaling',
                ],
            ],
        ];

        foreach (['en', 'nl'] as $locale) {
            $this->updateTranslationFile($locale, $translations);
        }

        echo "✅ Fichiers de traduction étendus avec succès !\n";
    }

    private function updateTranslationFile($locale, $translations)
    {
        $filePath = "lang/{$locale}/app.php";
        
        if (!file_exists($filePath)) {
            echo "❌ Fichier {$filePath} non trouvé\n";
            return;
        }

        $content = file_get_contents($filePath);
        
        foreach ($translations as $section => $sectionTranslations) {
            if (isset($sectionTranslations[$locale])) {
                $newItems = '';
                foreach ($sectionTranslations[$locale] as $key => $value) {
                    $newItems .= "        '{$key}' => '{$value}',\n";
                }
                
                // Chercher la section existante et ajouter les nouvelles traductions
                $pattern = "/'{$section}' => \[\s*(.*?)\s*\],/s";
                if (preg_match($pattern, $content, $matches)) {
                    $existingContent = $matches[1];
                    $updatedContent = $existingContent . $newItems;
                    $replacement = "'{$section}' => [\n        " . trim($updatedContent) . "\n    ],";
                    $content = preg_replace($pattern, $replacement, $content);
                }
            }
        }
        
        file_put_contents($filePath, $content);
        echo "   ✅ {$locale}/app.php mis à jour\n";
    }

    public function processAllViewFiles()
    {
        echo "🔄 Traitement de tous les fichiers de vues...\n";
        
        $directories = [
            'resources/views/web',
            'resources/views/auth',
            'resources/views/cart',
            'resources/views/checkout',
            'resources/views/orders',
            'resources/views/blog',
            'resources/views/contact',
            'resources/views/users',
            'resources/views/wishlist',
            'resources/views/payment',
            'resources/views/legal',
            'resources/views/my-rentals',
            'resources/views/rental-orders',
        ];

        $totalFiles = 0;
        $totalReplacements = 0;

        foreach ($directories as $dir) {
            if (is_dir($dir)) {
                $files = $this->getBladeFiles($dir);
                foreach ($files as $file) {
                    $replacements = $this->processFile($file);
                    if ($replacements > 0) {
                        $totalFiles++;
                        $totalReplacements += $replacements;
                    }
                }
            }
        }

        echo "\n📊 Résumé final:\n";
        echo "   Fichiers traités: {$totalFiles}\n";
        echo "   Total remplacements: {$totalReplacements}\n";
        echo "\n🎉 Traitement terminé !\n";
    }

    private function getBladeFiles($directory)
    {
        $files = [];
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
        
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php' && strpos($file->getFilename(), '.blade.') !== false) {
                $files[] = $file->getPathname();
            }
        }
        
        return $files;
    }

    private function processFile($filePath)
    {
        if (!file_exists($filePath)) {
            return 0;
        }

        $content = file_get_contents($filePath);
        $originalContent = $content;
        $replacements = 0;

        // Appliquer tous les patterns
        foreach ($this->patterns as $category => $patterns) {
            foreach ($patterns as $french => $translation) {
                // Remplacer dans les chaînes entre guillemets
                $pattern1 = '/"' . preg_quote($french, '/') . '"/u';
                $pattern2 = "/'" . preg_quote($french, '/') . "'/u";
                
                if (preg_match($pattern1, $content)) {
                    $content = preg_replace($pattern1, $translation, $content);
                    $replacements++;
                }
                
                if (preg_match($pattern2, $content)) {
                    $content = preg_replace($pattern2, $translation, $content);
                    $replacements++;
                }

                // Remplacer dans le contenu HTML (plus prudent)
                $pattern3 = '/>' . preg_quote($french, '/') . '</u';
                if (preg_match($pattern3, $content)) {
                    $content = preg_replace($pattern3, '>{{ ' . $translation . ' }}</', $content);
                    $replacements++;
                }
            }
        }

        if ($replacements > 0) {
            // Créer une sauvegarde
            $backupFile = $filePath . '.backup.' . date('Y-m-d-H-i-s');
            file_put_contents($backupFile, $originalContent);
            
            // Sauvegarder le fichier modifié
            file_put_contents($filePath, $content);
            
            echo "   ✅ {$filePath}: {$replacements} remplacements\n";
        }

        return $replacements;
    }
}

// Exécution
if (basename(__FILE__) == basename($_SERVER['SCRIPT_NAME'])) {
    $processor = new AdvancedTranslationProcessor();
    
    echo "🚀 Traitement avancé des traductions FarmShop\n";
    echo "===============================================\n\n";
    
    // Étendre les fichiers de traduction
    $processor->extendTranslationFiles();
    
    echo "\n";
    
    // Traiter tous les fichiers de vues
    $processor->processAllViewFiles();
    
    echo "\n🎯 Le système de traduction est maintenant opérationnel !\n";
    echo "🌍 Testez le changement de langue sur: http://127.0.0.1:8000\n";
}
