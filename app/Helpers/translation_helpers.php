<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;

/**
 * Helper pour obtenir une traduction de produit
 */
if (!function_exists('trans_product')) {
    function trans_product($product, $field = 'name', $locale = null)
    {
        $locale = $locale ?: App::getLocale();
        
        // Si c'est le français (langue par défaut), retourner directement
        if ($locale === 'fr') {
            return $product->$field ?? '';
        }
        
        // Chercher la traduction
        $translation = DB::table('product_translations')
            ->where('product_id', $product->id)
            ->where('locale', $locale)
            ->first();
            
        if ($translation && isset($translation->$field)) {
            return $translation->$field;
        }
        
        // Fallback vers le français
        return $product->$field ?? '';
    }
}

/**
 * Helper pour obtenir une traduction de catégorie
 */
if (!function_exists('trans_category')) {
    function trans_category($category, $field = 'name', $locale = null)
    {
        $locale = $locale ?: App::getLocale();
        
        if ($locale === 'fr') {
            return $category->$field ?? '';
        }
        
        $translation = DB::table('category_translations')
            ->where('category_id', $category->id)
            ->where('locale', $locale)
            ->first();
            
        if ($translation && isset($translation->$field)) {
            return $translation->$field;
        }
        
        return $category->$field ?? '';
    }
}

/**
 * Helper pour les traductions d'interface
 */
if (!function_exists('trans_interface')) {
    function trans_interface($key, $group = 'interface', $locale = null, $default = null)
    {
        $locale = $locale ?: App::getLocale();
        
        $translation = DB::table('translations')
            ->where('group', $group)
            ->where('key', $key)
            ->where('locale', $locale)
            ->value('value');
            
        return $translation ?: ($default ?: $key);
    }
}

/**
 * Helper pour formater les prix selon la locale
 */
if (!function_exists('format_price')) {
    function format_price($amount, $locale = null)
    {
        $locale = $locale ?: App::getLocale();
        
        $formatters = [
            'fr' => ['symbol' => '€', 'position' => 'after', 'decimal' => ',', 'thousands' => ' '],
            'en' => ['symbol' => '€', 'position' => 'before', 'decimal' => '.', 'thousands' => ','],
            'nl' => ['symbol' => '€', 'position' => 'before', 'decimal' => ',', 'thousands' => '.']
        ];
        
        $format = $formatters[$locale] ?? $formatters['fr'];
        $formattedAmount = number_format($amount, 2, $format['decimal'], $format['thousands']);
        
        return $format['position'] === 'before' 
            ? $format['symbol'] . $formattedAmount
            : $formattedAmount . ' ' . $format['symbol'];
    }
}

/**
 * Helper intelligent pour détecter et traduire du contenu
 */
if (!function_exists('smart_translate')) {
    function smart_translate($content, $locale = null)
    {
        $locale = $locale ?: App::getLocale();
        
        if ($locale === 'fr') {
            return $content;
        }
        
        // Mapping pour les termes courants
        $translations = [
            'en' => [
                'Accueil' => 'Home',
                'Produits' => 'Products',
                'Location' => 'Rental',
                'Vente' => 'Sale',
                'Panier' => 'Cart',
                'Connexion' => 'Login',
                'Inscription' => 'Register',
                'Mon Compte' => 'My Account',
                'Déconnexion' => 'Logout',
                'Contact' => 'Contact',
                'À propos' => 'About',
                'Blog' => 'Blog',
                'Rechercher' => 'Search',
                'Ajouter au panier' => 'Add to cart',
                'Voir le produit' => 'View product',
                'Prix' => 'Price',
                'Description' => 'Description',
                'Caractéristiques' => 'Features',
                'Disponibilité' => 'Availability',
                'En stock' => 'In stock',
                'Rupture de stock' => 'Out of stock',
                'Stock limité' => 'Limited stock',
                'Stock faible' => 'Low stock',
                'Commander' => 'Order',
                'Continuer mes achats' => 'Continue shopping',
                'Valider ma commande' => 'Validate my order',
                'Livraison' => 'Delivery',
                'Paiement' => 'Payment',
                'Total' => 'Total',
                'Sous-total' => 'Subtotal',
                'TVA' => 'VAT',
                'Frais de port' => 'Shipping costs',
                'Gratuit' => 'Free',
                'Nom' => 'Name',
                'Prénom' => 'First name',
                'Email' => 'Email',
                'Téléphone' => 'Phone',
                'Adresse' => 'Address',
                'Code postal' => 'Postal code',
                'Ville' => 'City',
                'Pays' => 'Country',
                'Enregistrer' => 'Save',
                'Annuler' => 'Cancel',
                'Modifier' => 'Edit',
                'Supprimer' => 'Delete',
                'Confirmer' => 'Confirm',
                'Retour' => 'Back',
                'Suivant' => 'Next',
                'Précédent' => 'Previous',
                'Fermer' => 'Close',
                'Ouvrir' => 'Open',
                'Télécharger' => 'Download',
                'Imprimer' => 'Print',
                'Partager' => 'Share',
                'Lire la suite' => 'Read more',
                'Acheter ce produit' => 'Buy this product',
                'Louer ce produit' => 'Rent this product',
                'Voir les options' => 'View options',
                'Produits en préparation' => 'Products in preparation',
                'Nos produits seront bientôt disponibles !' => 'Our products will be available soon!',
                'Voir tous nos produits' => 'View all our products',
                'Excellent service ! J\'ai trouvé exactement le tracteur qu\'il me fallait. Livraison rapide et matériel en parfait état.' => 'Excellent service! I found exactly the tractor I needed. Fast delivery and equipment in perfect condition.',
                'La location m\'a permis d\'essayer avant d\'acheter. Très pratique pour les gros équipements !' => 'Rental allowed me to try before buying. Very practical for large equipment!',
                'Support client réactif et professionnel. Je recommande FarmShop à tous mes collègues.' => 'Responsive and professional customer support. I recommend FarmShop to all my colleagues.',
                '- Pierre Martin, Agriculteur' => '- Pierre Martin, Farmer',
                '- Marie Dubois, Exploitante' => '- Marie Dubois, Farm Operator',
                '- Jean Lefebvre, GAEC' => '- Jean Lefebvre, GAEC',
                'J\'ai déjà un compte' => 'I already have an account',
                '⚡ Inscription rapide • 🔒 100% sécurisé • 📧 Pas de spam' => '⚡ Quick registration • 🔒 100% secure • 📧 No spam',
                'Votre adresse email' => 'Your email address',
                'S\'abonner' => 'Subscribe'
            ],
            'nl' => [
                'Accueil' => 'Home',
                'Produits' => 'Producten',
                'Location' => 'Verhuur',
                'Vente' => 'Verkoop',
                'Panier' => 'Winkelwagen',
                'Connexion' => 'Inloggen',
                'Inscription' => 'Registreren',
                'Mon Compte' => 'Mijn Account',
                'Déconnexion' => 'Uitloggen',
                'Contact' => 'Contact',
                'À propos' => 'Over ons',
                'Blog' => 'Blog',
                'Rechercher' => 'Zoeken',
                'Ajouter au panier' => 'Toevoegen aan winkelwagen',
                'Voir le produit' => 'Product bekijken',
                'Prix' => 'Prijs',
                'Description' => 'Beschrijving',
                'Caractéristiques' => 'Kenmerken',
                'Disponibilité' => 'Beschikbaarheid',
                'En stock' => 'Op voorraad',
                'Rupture de stock' => 'Uitverkocht',
                'Stock limité' => 'Beperkte voorraad',
                'Stock faible' => 'Lage voorraad',
                'Commander' => 'Bestellen',
                'Continuer mes achats' => 'Verder winkelen',
                'Valider ma commande' => 'Bestelling valideren',
                'Livraison' => 'Levering',
                'Paiement' => 'Betaling',
                'Total' => 'Totaal',
                'Sous-total' => 'Subtotaal',
                'TVA' => 'BTW',
                'Frais de port' => 'Verzendkosten',
                'Gratuit' => 'Gratis',
                'Nom' => 'Naam',
                'Prénom' => 'Voornaam',
                'Email' => 'Email',
                'Téléphone' => 'Telefoon',
                'Adresse' => 'Adres',
                'Code postal' => 'Postcode',
                'Ville' => 'Stad',
                'Pays' => 'Land',
                'Enregistrer' => 'Opslaan',
                'Annuler' => 'Annuleren',
                'Modifier' => 'Bewerken',
                'Supprimer' => 'Verwijderen',
                'Confirmer' => 'Bevestigen',
                'Retour' => 'Terug',
                'Suivant' => 'Volgende',
                'Précédent' => 'Vorige',
                'Fermer' => 'Sluiten',
                'Ouvrir' => 'Openen',
                'Télécharger' => 'Downloaden',
                'Imprimer' => 'Afdrukken',
                'Partager' => 'Delen',
                'Lire la suite' => 'Meer lezen',
                'Acheter ce produit' => 'Dit product kopen',
                'Louer ce produit' => 'Dit product huren',
                'Voir les options' => 'Opties bekijken',
                'Produits en préparation' => 'Producten in voorbereiding',
                'Nos produits seront bientôt disponibles !' => 'Onze producten zullen binnenkort beschikbaar zijn!',
                'Voir tous nos produits' => 'Bekijk al onze producten',
                'Excellent service ! J\'ai trouvé exactement le tracteur qu\'il me fallait. Livraison rapide et matériel en parfait état.' => 'Uitstekende service! Ik vond precies de tractor die ik nodig had. Snelle levering en apparatuur in perfecte staat.',
                'La location m\'a permis d\'essayer avant d\'acheter. Très pratique pour les gros équipements !' => 'Verhuur stelde me in staat om te proberen voordat ik kocht. Zeer praktisch voor grote apparatuur!',
                'Support client réactif et professionnel. Je recommande FarmShop à tous mes collègues.' => 'Responsieve en professionele klantenservice. Ik beveel FarmShop aan bij al mijn collega\'s.',
                '- Pierre Martin, Agriculteur' => '- Pierre Martin, Boer',
                '- Marie Dubois, Exploitante' => '- Marie Dubois, Boerderijuitbater',
                '- Jean Lefebvre, GAEC' => '- Jean Lefebvre, GAEC',
                'J\'ai déjà un compte' => 'Ik heb al een account',
                '⚡ Inscription rapide • 🔒 100% sécurisé • 📧 Pas de spam' => '⚡ Snelle registratie • 🔒 100% veilig • 📧 Geen spam',
                'Votre adresse email' => 'Uw e-mailadres',
                'S\'abonner' => 'Abonneren'
            ]
        ];
        
        $langTranslations = $translations[$locale] ?? [];
        
        return $langTranslations[$content] ?? $content;
    }
}

/**
 * Helper pour obtenir les traductions de blog
 */
if (!function_exists('trans_blog')) {
    function trans_blog($blogPost, $field = 'title', $locale = null)
    {
        $locale = $locale ?: App::getLocale();
        
        if ($locale === 'fr') {
            return $blogPost->$field ?? '';
        }
        
        $translation = DB::table('blog_post_translations')
            ->where('blog_post_id', $blogPost->id)
            ->where('locale', $locale)
            ->first();
            
        if ($translation && isset($translation->$field)) {
            return $translation->$field;
        }
        
        return $blogPost->$field ?? '';
    }
}
