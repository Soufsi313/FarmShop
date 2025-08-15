<?php

/**
 * Générateur automatique de traductions EN/NL
 * Traite les résultats du scan et génère les fichiers de traduction
 */

require_once 'scan_translations.php';

class TranslationGenerator
{
    private $translations = [
        'fr' => [],
        'en' => [],
        'nl' => []
    ];

    private $commonTranslations = [
        // Navigation & Interface
        'home' => ['fr' => 'Accueil', 'en' => 'Home', 'nl' => 'Home'],
        'products' => ['fr' => 'Produits', 'en' => 'Products', 'nl' => 'Producten'],
        'rentals' => ['fr' => 'Locations', 'en' => 'Rentals', 'nl' => 'Verhuur'],
        'blog' => ['fr' => 'Blog', 'en' => 'Blog', 'nl' => 'Blog'],
        'contact' => ['fr' => 'Contact', 'en' => 'Contact', 'nl' => 'Contact'],
        'cart' => ['fr' => 'Panier', 'en' => 'Cart', 'nl' => 'Winkelwagen'],
        'profile' => ['fr' => 'Profil', 'en' => 'Profile', 'nl' => 'Profiel'],
        'wishlist' => ['fr' => 'Liste de souhaits', 'en' => 'Wishlist', 'nl' => 'Verlanglijst'],
        'orders' => ['fr' => 'Commandes', 'en' => 'Orders', 'nl' => 'Bestellingen'],
        
        // Actions & Buttons
        'add' => ['fr' => 'Ajouter', 'en' => 'Add', 'nl' => 'Toevoegen'],
        'edit' => ['fr' => 'Modifier', 'en' => 'Edit', 'nl' => 'Bewerken'],
        'delete' => ['fr' => 'Supprimer', 'en' => 'Delete', 'nl' => 'Verwijderen'],
        'save' => ['fr' => 'Enregistrer', 'en' => 'Save', 'nl' => 'Opslaan'],
        'cancel' => ['fr' => 'Annuler', 'en' => 'Cancel', 'nl' => 'Annuleren'],
        'confirm' => ['fr' => 'Confirmer', 'en' => 'Confirm', 'nl' => 'Bevestigen'],
        'submit' => ['fr' => 'Soumettre', 'en' => 'Submit', 'nl' => 'Versturen'],
        'send' => ['fr' => 'Envoyer', 'en' => 'Send', 'nl' => 'Verzenden'],
        'back' => ['fr' => 'Retour', 'en' => 'Back', 'nl' => 'Terug'],
        'next' => ['fr' => 'Suivant', 'en' => 'Next', 'nl' => 'Volgende'],
        'previous' => ['fr' => 'Précédent', 'en' => 'Previous', 'nl' => 'Vorige'],
        'continue' => ['fr' => 'Continuer', 'en' => 'Continue', 'nl' => 'Doorgaan'],
        'view' => ['fr' => 'Voir', 'en' => 'View', 'nl' => 'Bekijken'],
        'show' => ['fr' => 'Afficher', 'en' => 'Show', 'nl' => 'Tonen'],
        'hide' => ['fr' => 'Masquer', 'en' => 'Hide', 'nl' => 'Verbergen'],
        'close' => ['fr' => 'Fermer', 'en' => 'Close', 'nl' => 'Sluiten'],
        'open' => ['fr' => 'Ouvrir', 'en' => 'Open', 'nl' => 'Openen'],
        'search' => ['fr' => 'Rechercher', 'en' => 'Search', 'nl' => 'Zoeken'],
        'filter' => ['fr' => 'Filtrer', 'en' => 'Filter', 'nl' => 'Filteren'],
        'sort' => ['fr' => 'Trier', 'en' => 'Sort', 'nl' => 'Sorteren'],
        'select' => ['fr' => 'Sélectionner', 'en' => 'Select', 'nl' => 'Selecteren'],
        'choose' => ['fr' => 'Choisir', 'en' => 'Choose', 'nl' => 'Kiezen'],
        'download' => ['fr' => 'Télécharger', 'en' => 'Download', 'nl' => 'Downloaden'],
        'upload' => ['fr' => 'Téléverser', 'en' => 'Upload', 'nl' => 'Uploaden'],
        'print' => ['fr' => 'Imprimer', 'en' => 'Print', 'nl' => 'Afdrukken'],
        'export' => ['fr' => 'Exporter', 'en' => 'Export', 'nl' => 'Exporteren'],
        'import' => ['fr' => 'Importer', 'en' => 'Import', 'nl' => 'Importeren'],
        
        // Forms & Fields
        'name' => ['fr' => 'Nom', 'en' => 'Name', 'nl' => 'Naam'],
        'email' => ['fr' => 'Email', 'en' => 'Email', 'nl' => 'E-mail'],
        'password' => ['fr' => 'Mot de passe', 'en' => 'Password', 'nl' => 'Wachtwoord'],
        'phone' => ['fr' => 'Téléphone', 'en' => 'Phone', 'nl' => 'Telefoon'],
        'address' => ['fr' => 'Adresse', 'en' => 'Address', 'nl' => 'Adres'],
        'city' => ['fr' => 'Ville', 'en' => 'City', 'nl' => 'Stad'],
        'postal_code' => ['fr' => 'Code postal', 'en' => 'Postal Code', 'nl' => 'Postcode'],
        'country' => ['fr' => 'Pays', 'en' => 'Country', 'nl' => 'Land'],
        'date' => ['fr' => 'Date', 'en' => 'Date', 'nl' => 'Datum'],
        'time' => ['fr' => 'Heure', 'en' => 'Time', 'nl' => 'Tijd'],
        'message' => ['fr' => 'Message', 'en' => 'Message', 'nl' => 'Bericht'],
        'description' => ['fr' => 'Description', 'en' => 'Description', 'nl' => 'Beschrijving'],
        'title' => ['fr' => 'Titre', 'en' => 'Title', 'nl' => 'Titel'],
        'content' => ['fr' => 'Contenu', 'en' => 'Content', 'nl' => 'Inhoud'],
        'category' => ['fr' => 'Catégorie', 'en' => 'Category', 'nl' => 'Categorie'],
        'price' => ['fr' => 'Prix', 'en' => 'Price', 'nl' => 'Prijs'],
        'quantity' => ['fr' => 'Quantité', 'en' => 'Quantity', 'nl' => 'Aantal'],
        'total' => ['fr' => 'Total', 'en' => 'Total', 'nl' => 'Totaal'],
        'subtotal' => ['fr' => 'Sous-total', 'en' => 'Subtotal', 'nl' => 'Subtotaal'],
        'tax' => ['fr' => 'TVA', 'en' => 'Tax', 'nl' => 'BTW'],
        'shipping' => ['fr' => 'Livraison', 'en' => 'Shipping', 'nl' => 'Verzending'],
        'discount' => ['fr' => 'Remise', 'en' => 'Discount', 'nl' => 'Korting'],
        
        // Status & States
        'active' => ['fr' => 'Actif', 'en' => 'Active', 'nl' => 'Actief'],
        'inactive' => ['fr' => 'Inactif', 'en' => 'Inactive', 'nl' => 'Inactief'],
        'pending' => ['fr' => 'En attente', 'en' => 'Pending', 'nl' => 'In afwachting'],
        'confirmed' => ['fr' => 'Confirmé', 'en' => 'Confirmed', 'nl' => 'Bevestigd'],
        'cancelled' => ['fr' => 'Annulé', 'en' => 'Cancelled', 'nl' => 'Geannuleerd'],
        'completed' => ['fr' => 'Terminé', 'en' => 'Completed', 'nl' => 'Voltooid'],
        'processing' => ['fr' => 'En cours', 'en' => 'Processing', 'nl' => 'Wordt verwerkt'],
        'shipped' => ['fr' => 'Expédié', 'en' => 'Shipped', 'nl' => 'Verzonden'],
        'delivered' => ['fr' => 'Livré', 'en' => 'Delivered', 'nl' => 'Bezorgd'],
        'returned' => ['fr' => 'Retourné', 'en' => 'Returned', 'nl' => 'Geretourneerd'],
        'refunded' => ['fr' => 'Remboursé', 'en' => 'Refunded', 'nl' => 'Terugbetaald'],
        
        // Messages & Notifications
        'success' => ['fr' => 'Succès', 'en' => 'Success', 'nl' => 'Succes'],
        'error' => ['fr' => 'Erreur', 'en' => 'Error', 'nl' => 'Fout'],
        'warning' => ['fr' => 'Attention', 'en' => 'Warning', 'nl' => 'Waarschuwing'],
        'info' => ['fr' => 'Information', 'en' => 'Information', 'nl' => 'Informatie'],
        'loading' => ['fr' => 'Chargement...', 'en' => 'Loading...', 'nl' => 'Laden...'],
        'no_results' => ['fr' => 'Aucun résultat', 'en' => 'No results', 'nl' => 'Geen resultaten'],
        'empty' => ['fr' => 'Vide', 'en' => 'Empty', 'nl' => 'Leeg'],
        
        // E-commerce specific
        'add_to_cart' => ['fr' => 'Ajouter au panier', 'en' => 'Add to Cart', 'nl' => 'Toevoegen aan winkelwagen'],
        'checkout' => ['fr' => 'Commander', 'en' => 'Checkout', 'nl' => 'Afrekenen'],
        'payment' => ['fr' => 'Paiement', 'en' => 'Payment', 'nl' => 'Betaling'],
        'invoice' => ['fr' => 'Facture', 'en' => 'Invoice', 'nl' => 'Factuur'],
        'order' => ['fr' => 'Commande', 'en' => 'Order', 'nl' => 'Bestelling'],
        'stock' => ['fr' => 'Stock', 'en' => 'Stock', 'nl' => 'Voorraad'],
        'available' => ['fr' => 'Disponible', 'en' => 'Available', 'nl' => 'Beschikbaar'],
        'out_of_stock' => ['fr' => 'Rupture de stock', 'en' => 'Out of Stock', 'nl' => 'Niet op voorraad'],
        'in_stock' => ['fr' => 'En stock', 'en' => 'In Stock', 'nl' => 'Op voorraad'],
        
        // Time & Dates
        'today' => ['fr' => "Aujourd'hui", 'en' => 'Today', 'nl' => 'Vandaag'],
        'yesterday' => ['fr' => 'Hier', 'en' => 'Yesterday', 'nl' => 'Gisteren'],
        'tomorrow' => ['fr' => 'Demain', 'en' => 'Tomorrow', 'nl' => 'Morgen'],
        'week' => ['fr' => 'Semaine', 'en' => 'Week', 'nl' => 'Week'],
        'month' => ['fr' => 'Mois', 'en' => 'Month', 'nl' => 'Maand'],
        'year' => ['fr' => 'Année', 'en' => 'Year', 'nl' => 'Jaar'],
        
        // Common phrases
        'welcome' => ['fr' => 'Bienvenue', 'en' => 'Welcome', 'nl' => 'Welkom'],
        'thank_you' => ['fr' => 'Merci', 'en' => 'Thank you', 'nl' => 'Dank je wel'],
        'please_wait' => ['fr' => 'Veuillez patienter', 'en' => 'Please wait', 'nl' => 'Even wachten'],
        'are_you_sure' => ['fr' => 'Êtes-vous sûr ?', 'en' => 'Are you sure?', 'nl' => 'Weet je het zeker?'],
        'yes' => ['fr' => 'Oui', 'en' => 'Yes', 'nl' => 'Ja'],
        'no' => ['fr' => 'Non', 'en' => 'No', 'nl' => 'Nee'],
        'or' => ['fr' => 'ou', 'en' => 'or', 'nl' => 'of'],
        'and' => ['fr' => 'et', 'en' => 'and', 'nl' => 'en'],
        'all' => ['fr' => 'Tous', 'en' => 'All', 'nl' => 'Alle'],
        'none' => ['fr' => 'Aucun', 'en' => 'None', 'nl' => 'Geen'],
        'more' => ['fr' => 'Plus', 'en' => 'More', 'nl' => 'Meer'],
        'less' => ['fr' => 'Moins', 'en' => 'Less', 'nl' => 'Minder'],
        'required' => ['fr' => 'Requis', 'en' => 'Required', 'nl' => 'Verplicht'],
        'optional' => ['fr' => 'Optionnel', 'en' => 'Optional', 'nl' => 'Optioneel'],
        'help' => ['fr' => 'Aide', 'en' => 'Help', 'nl' => 'Help'],
        'about' => ['fr' => 'À propos', 'en' => 'About', 'nl' => 'Over'],
        'privacy' => ['fr' => 'Confidentialité', 'en' => 'Privacy', 'nl' => 'Privacy'],
        'terms' => ['fr' => 'Conditions', 'en' => 'Terms', 'nl' => 'Voorwaarden'],
        'login' => ['fr' => 'Se connecter', 'en' => 'Login', 'nl' => 'Inloggen'],
        'logout' => ['fr' => 'Se déconnecter', 'en' => 'Logout', 'nl' => 'Uitloggen'],
        'register' => ['fr' => 'S\'inscrire', 'en' => 'Register', 'nl' => 'Registreren'],
        'forgot_password' => ['fr' => 'Mot de passe oublié', 'en' => 'Forgot Password', 'nl' => 'Wachtwoord vergeten'],
        'remember_me' => ['fr' => 'Se souvenir de moi', 'en' => 'Remember Me', 'nl' => 'Onthouden'],
    ];

    public function generateTranslationFiles()
    {
        echo "🌍 Génération des fichiers de traduction EN/NL...\n";
        echo "============================================================\n";

        // Structure des sections
        $sections = [
            'general' => [],
            'nav' => [
                'home' => ['fr' => 'Accueil', 'en' => 'Home', 'nl' => 'Home'],
                'products' => ['fr' => 'Produits', 'en' => 'Products', 'nl' => 'Producten'],
                'rentals' => ['fr' => 'Locations', 'en' => 'Rentals', 'nl' => 'Verhuur'],
                'blog' => ['fr' => 'Blog', 'en' => 'Blog', 'nl' => 'Blog'],
                'contact' => ['fr' => 'Contact', 'en' => 'Contact', 'nl' => 'Contact'],
                'about' => ['fr' => 'À propos', 'en' => 'About', 'nl' => 'Over ons'],
                'newsletter' => ['fr' => 'Newsletter', 'en' => 'Newsletter', 'nl' => 'Nieuwsbrief'],
                'my_account' => ['fr' => 'Mon compte', 'en' => 'My Account', 'nl' => 'Mijn account'],
                'cart' => ['fr' => 'Panier', 'en' => 'Cart', 'nl' => 'Winkelwagen'],
                'wishlist' => ['fr' => 'Liste de souhaits', 'en' => 'Wishlist', 'nl' => 'Verlanglijst'],
                'orders' => ['fr' => 'Mes commandes', 'en' => 'My Orders', 'nl' => 'Mijn bestellingen'],
                'profile' => ['fr' => 'Profil', 'en' => 'Profile', 'nl' => 'Profiel'],
                'login' => ['fr' => 'Se connecter', 'en' => 'Login', 'nl' => 'Inloggen'],
                'logout' => ['fr' => 'Se déconnecter', 'en' => 'Logout', 'nl' => 'Uitloggen'],
                'register' => ['fr' => 'S\'inscrire', 'en' => 'Register', 'nl' => 'Registreren'],
            ],
            'welcome' => [
                'title' => ['fr' => 'Bienvenue chez FarmShop', 'en' => 'Welcome to FarmShop', 'nl' => 'Welkom bij FarmShop'],
                'subtitle' => ['fr' => 'Votre boutique agricole en ligne', 'en' => 'Your online agricultural store', 'nl' => 'Uw online landbouwwinkel'],
                'featured_products' => ['fr' => 'Produits vedettes', 'en' => 'Featured Products', 'nl' => 'Uitgelichte producten'],
                'latest_news' => ['fr' => 'Dernières actualités', 'en' => 'Latest News', 'nl' => 'Laatste nieuws'],
                'discover_more' => ['fr' => 'Découvrir plus', 'en' => 'Discover More', 'nl' => 'Ontdek meer'],
                'shop_now' => ['fr' => 'Acheter maintenant', 'en' => 'Shop Now', 'nl' => 'Nu winkelen'],
                'learn_more' => ['fr' => 'En savoir plus', 'en' => 'Learn More', 'nl' => 'Meer weten'],
            ],
            'buttons' => [
                'add' => ['fr' => 'Ajouter', 'en' => 'Add', 'nl' => 'Toevoegen'],
                'edit' => ['fr' => 'Modifier', 'en' => 'Edit', 'nl' => 'Bewerken'],
                'delete' => ['fr' => 'Supprimer', 'en' => 'Delete', 'nl' => 'Verwijderen'],
                'save' => ['fr' => 'Enregistrer', 'en' => 'Save', 'nl' => 'Opslaan'],
                'cancel' => ['fr' => 'Annuler', 'en' => 'Cancel', 'nl' => 'Annuleren'],
                'confirm' => ['fr' => 'Confirmer', 'en' => 'Confirm', 'nl' => 'Bevestigen'],
                'submit' => ['fr' => 'Soumettre', 'en' => 'Submit', 'nl' => 'Versturen'],
                'send' => ['fr' => 'Envoyer', 'en' => 'Send', 'nl' => 'Verzenden'],
                'back' => ['fr' => 'Retour', 'en' => 'Back', 'nl' => 'Terug'],
                'continue' => ['fr' => 'Continuer', 'en' => 'Continue', 'nl' => 'Doorgaan'],
                'view' => ['fr' => 'Voir', 'en' => 'View', 'nl' => 'Bekijken'],
                'download' => ['fr' => 'Télécharger', 'en' => 'Download', 'nl' => 'Downloaden'],
                'print' => ['fr' => 'Imprimer', 'en' => 'Print', 'nl' => 'Afdrukken'],
            ],
            'forms' => [
                'name' => ['fr' => 'Nom', 'en' => 'Name', 'nl' => 'Naam'],
                'first_name' => ['fr' => 'Prénom', 'en' => 'First Name', 'nl' => 'Voornaam'],
                'last_name' => ['fr' => 'Nom de famille', 'en' => 'Last Name', 'nl' => 'Achternaam'],
                'email' => ['fr' => 'Email', 'en' => 'Email', 'nl' => 'E-mail'],
                'password' => ['fr' => 'Mot de passe', 'en' => 'Password', 'nl' => 'Wachtwoord'],
                'confirm_password' => ['fr' => 'Confirmer le mot de passe', 'en' => 'Confirm Password', 'nl' => 'Bevestig wachtwoord'],
                'phone' => ['fr' => 'Téléphone', 'en' => 'Phone', 'nl' => 'Telefoon'],
                'address' => ['fr' => 'Adresse', 'en' => 'Address', 'nl' => 'Adres'],
                'city' => ['fr' => 'Ville', 'en' => 'City', 'nl' => 'Stad'],
                'postal_code' => ['fr' => 'Code postal', 'en' => 'Postal Code', 'nl' => 'Postcode'],
                'country' => ['fr' => 'Pays', 'en' => 'Country', 'nl' => 'Land'],
                'message' => ['fr' => 'Message', 'en' => 'Message', 'nl' => 'Bericht'],
                'subject' => ['fr' => 'Sujet', 'en' => 'Subject', 'nl' => 'Onderwerp'],
                'description' => ['fr' => 'Description', 'en' => 'Description', 'nl' => 'Beschrijving'],
                'required' => ['fr' => 'Obligatoire', 'en' => 'Required', 'nl' => 'Verplicht'],
                'optional' => ['fr' => 'Optionnel', 'en' => 'Optional', 'nl' => 'Optioneel'],
            ],
            'ecommerce' => [
                'price' => ['fr' => 'Prix', 'en' => 'Price', 'nl' => 'Prijs'],
                'quantity' => ['fr' => 'Quantité', 'en' => 'Quantity', 'nl' => 'Aantal'],
                'total' => ['fr' => 'Total', 'en' => 'Total', 'nl' => 'Totaal'],
                'subtotal' => ['fr' => 'Sous-total', 'en' => 'Subtotal', 'nl' => 'Subtotaal'],
                'tax' => ['fr' => 'TVA', 'en' => 'Tax', 'nl' => 'BTW'],
                'shipping' => ['fr' => 'Livraison', 'en' => 'Shipping', 'nl' => 'Verzending'],
                'discount' => ['fr' => 'Remise', 'en' => 'Discount', 'nl' => 'Korting'],
                'add_to_cart' => ['fr' => 'Ajouter au panier', 'en' => 'Add to Cart', 'nl' => 'Toevoegen aan winkelwagen'],
                'checkout' => ['fr' => 'Commander', 'en' => 'Checkout', 'nl' => 'Afrekenen'],
                'payment' => ['fr' => 'Paiement', 'en' => 'Payment', 'nl' => 'Betaling'],
                'order' => ['fr' => 'Commande', 'en' => 'Order', 'nl' => 'Bestelling'],
                'stock' => ['fr' => 'Stock', 'en' => 'Stock', 'nl' => 'Voorraad'],
                'available' => ['fr' => 'Disponible', 'en' => 'Available', 'nl' => 'Beschikbaar'],
                'out_of_stock' => ['fr' => 'Rupture de stock', 'en' => 'Out of Stock', 'nl' => 'Niet op voorraad'],
                'in_stock' => ['fr' => 'En stock', 'en' => 'In Stock', 'nl' => 'Op voorraad'],
                'category' => ['fr' => 'Catégorie', 'en' => 'Category', 'nl' => 'Categorie'],
                'categories' => ['fr' => 'Catégories', 'en' => 'Categories', 'nl' => 'Categorieën'],
                'product' => ['fr' => 'Produit', 'en' => 'Product', 'nl' => 'Product'],
                'products' => ['fr' => 'Produits', 'en' => 'Products', 'nl' => 'Producten'],
                'rental' => ['fr' => 'Location', 'en' => 'Rental', 'nl' => 'Verhuur'],
                'rentals' => ['fr' => 'Locations', 'en' => 'Rentals', 'nl' => 'Verhuur'],
            ],
            'messages' => [
                'success' => ['fr' => 'Succès', 'en' => 'Success', 'nl' => 'Succes'],
                'error' => ['fr' => 'Erreur', 'en' => 'Error', 'nl' => 'Fout'],
                'warning' => ['fr' => 'Attention', 'en' => 'Warning', 'nl' => 'Waarschuwing'],
                'info' => ['fr' => 'Information', 'en' => 'Information', 'nl' => 'Informatie'],
                'loading' => ['fr' => 'Chargement...', 'en' => 'Loading...', 'nl' => 'Laden...'],
                'no_results' => ['fr' => 'Aucun résultat trouvé', 'en' => 'No results found', 'nl' => 'Geen resultaten gevonden'],
                'empty' => ['fr' => 'Aucun élément', 'en' => 'No items', 'nl' => 'Geen items'],
                'thank_you' => ['fr' => 'Merci', 'en' => 'Thank you', 'nl' => 'Dank je wel'],
                'welcome' => ['fr' => 'Bienvenue', 'en' => 'Welcome', 'nl' => 'Welkom'],
                'please_wait' => ['fr' => 'Veuillez patienter', 'en' => 'Please wait', 'nl' => 'Even wachten'],
                'are_you_sure' => ['fr' => 'Êtes-vous sûr ?', 'en' => 'Are you sure?', 'nl' => 'Weet je het zeker?'],
                'operation_completed' => ['fr' => 'Opération terminée avec succès', 'en' => 'Operation completed successfully', 'nl' => 'Bewerking succesvol voltooid'],
                'operation_failed' => ['fr' => 'Échec de l\'opération', 'en' => 'Operation failed', 'nl' => 'Bewerking mislukt'],
            ],
            'footer' => [
                'about_us' => ['fr' => 'À propos de nous', 'en' => 'About Us', 'nl' => 'Over ons'],
                'contact_us' => ['fr' => 'Nous contacter', 'en' => 'Contact Us', 'nl' => 'Contact opnemen'],
                'privacy_policy' => ['fr' => 'Politique de confidentialité', 'en' => 'Privacy Policy', 'nl' => 'Privacybeleid'],
                'terms_of_service' => ['fr' => 'Conditions d\'utilisation', 'en' => 'Terms of Service', 'nl' => 'Servicevoorwaarden'],
                'follow_us' => ['fr' => 'Suivez-nous', 'en' => 'Follow Us', 'nl' => 'Volg ons'],
                'newsletter_signup' => ['fr' => 'S\'inscrire à la newsletter', 'en' => 'Newsletter Signup', 'nl' => 'Nieuwsbrief aanmelden'],
                'copyright' => ['fr' => 'Tous droits réservés', 'en' => 'All rights reserved', 'nl' => 'Alle rechten voorbehouden'],
            ],
        ];

        // Générer les fichiers pour chaque langue
        foreach (['en', 'nl'] as $locale) {
            $this->generateLanguageFile($locale, $sections);
        }

        echo "\n✅ Fichiers de traduction générés avec succès !\n";
        echo "📁 Fichiers créés:\n";
        echo "   - lang/en/app.php\n";
        echo "   - lang/nl/app.php\n";
        echo "\n🎯 Prochaines étapes:\n";
        echo "   1. Vérifier les traductions générées\n";
        echo "   2. Ajouter des traductions spécifiques si nécessaire\n";
        echo "   3. Tester le changement de langue sur le site\n";
    }

    private function generateLanguageFile($locale, $sections)
    {
        echo "📝 Génération du fichier {$locale}/app.php...\n";

        $filePath = "lang/{$locale}/app.php";
        
        $content = "<?php\n\n";
        $content .= "return [\n\n";

        foreach ($sections as $sectionName => $translations) {
            $content .= "    // {$this->getSectionTitle($sectionName)}\n";
            $content .= "    '{$sectionName}' => [\n";
            
            foreach ($translations as $key => $trans) {
                if (isset($trans[$locale])) {
                    $value = $this->escapePhpString($trans[$locale]);
                    $content .= "        '{$key}' => '{$value}',\n";
                }
            }
            
            $content .= "    ],\n\n";
        }

        $content .= "];\n";

        if (!is_dir("lang/{$locale}")) {
            mkdir("lang/{$locale}", 0755, true);
        }

        file_put_contents($filePath, $content);
        echo "   ✅ {$filePath} créé\n";
    }

    private function getSectionTitle($section)
    {
        $titles = [
            'general' => 'Général',
            'nav' => 'Navigation',
            'welcome' => 'Page d\'accueil',
            'buttons' => 'Boutons',
            'forms' => 'Formulaires',
            'ecommerce' => 'E-commerce',
            'messages' => 'Messages',
            'footer' => 'Pied de page',
        ];

        return $titles[$section] ?? ucfirst($section);
    }

    private function escapePhpString($string)
    {
        return str_replace("'", "\'", $string);
    }
}

// Exécution si appelé directement
if (basename(__FILE__) == basename($_SERVER['SCRIPT_NAME'])) {
    $generator = new TranslationGenerator();
    $generator->generateTranslationFiles();
}
