<?php

/**
 * Script de traduction rapide pour les pages restantes
 */

$pagesTranslations = [
    // Pages principales
    'Contact' => 'pages.contact',
    'Blog' => 'pages.blog',
    'Location' => 'pages.rentals',
    'Connexion' => 'auth.login',
    'Inscription' => 'auth.register',
    
    // Formulaires
    'Nom' => 'form.name',
    'Prénom' => 'form.first_name',
    'Email' => 'form.email',
    'Téléphone' => 'form.phone',
    'Message' => 'form.message',
    'Envoyer' => 'form.send',
    'Mot de passe' => 'form.password',
    'Confirmer le mot de passe' => 'form.confirm_password',
    'Se souvenir de moi' => 'form.remember_me',
    'Se connecter' => 'form.login',
    'S\'inscrire' => 'form.register',
    'Mot de passe oublié ?' => 'form.forgot_password',
    
    // Messages
    'Merci pour votre message' => 'messages.thank_you',
    'Nous vous répondrons rapidement' => 'messages.reply_soon',
    'Une erreur s\'est produite' => 'messages.error_occurred',
    
    // Location/Rentals
    'Nos équipements en location' => 'rentals.title',
    'Trouvez l\'équipement parfait pour vos besoins' => 'rentals.subtitle',
    'Durée de location' => 'rentals.duration',
    'Date de début' => 'rentals.start_date',
    'Date de fin' => 'rentals.end_date',
    'Réserver' => 'rentals.book',
    'Caution' => 'rentals.deposit',
    'Prix par jour' => 'rentals.price_per_day',
    
    // Blog
    'Nos derniers articles' => 'blog.latest_articles',
    'Partager' => 'blog.share',
    'Lire plus' => 'blog.read_more',
    'Publié le' => 'blog.published_on',
    'Par' => 'blog.by',
    'Commentaires' => 'blog.comments',
    'Laissez un commentaire' => 'blog.leave_comment',
];

// Ajouter les traductions dans les fichiers de langue
$languages = [
    'fr' => [
        'pages' => [
            'contact' => 'Contact',
            'blog' => 'Blog',
            'rentals' => 'Location'
        ],
        'auth' => [
            'login' => 'Connexion',
            'register' => 'Inscription'
        ],
        'form' => [
            'name' => 'Nom',
            'first_name' => 'Prénom',
            'email' => 'Email',
            'phone' => 'Téléphone',
            'message' => 'Message',
            'send' => 'Envoyer',
            'password' => 'Mot de passe',
            'confirm_password' => 'Confirmer le mot de passe',
            'remember_me' => 'Se souvenir de moi',
            'login' => 'Se connecter',
            'register' => 'S\'inscrire',
            'forgot_password' => 'Mot de passe oublié ?'
        ],
        'messages' => [
            'thank_you' => 'Merci pour votre message',
            'reply_soon' => 'Nous vous répondrons rapidement',
            'error_occurred' => 'Une erreur s\'est produite'
        ],
        'rentals' => [
            'title' => 'Nos équipements en location',
            'subtitle' => 'Trouvez l\'équipement parfait pour vos besoins',
            'duration' => 'Durée de location',
            'start_date' => 'Date de début',
            'end_date' => 'Date de fin',
            'book' => 'Réserver',
            'deposit' => 'Caution',
            'price_per_day' => 'Prix par jour'
        ],
        'blog' => [
            'latest_articles' => 'Nos derniers articles',
            'share' => 'Partager',
            'read_more' => 'Lire plus',
            'published_on' => 'Publié le',
            'by' => 'Par',
            'comments' => 'Commentaires',
            'leave_comment' => 'Laissez un commentaire'
        ]
    ],
    'en' => [
        'pages' => [
            'contact' => 'Contact',
            'blog' => 'Blog',
            'rentals' => 'Rentals'
        ],
        'auth' => [
            'login' => 'Login',
            'register' => 'Register'
        ],
        'form' => [
            'name' => 'Name',
            'first_name' => 'First Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'message' => 'Message',
            'send' => 'Send',
            'password' => 'Password',
            'confirm_password' => 'Confirm Password',
            'remember_me' => 'Remember me',
            'login' => 'Login',
            'register' => 'Register',
            'forgot_password' => 'Forgot password?'
        ],
        'messages' => [
            'thank_you' => 'Thank you for your message',
            'reply_soon' => 'We will reply to you soon',
            'error_occurred' => 'An error occurred'
        ],
        'rentals' => [
            'title' => 'Our rental equipment',
            'subtitle' => 'Find the perfect equipment for your needs',
            'duration' => 'Rental duration',
            'start_date' => 'Start date',
            'end_date' => 'End date',
            'book' => 'Book',
            'deposit' => 'Deposit',
            'price_per_day' => 'Price per day'
        ],
        'blog' => [
            'latest_articles' => 'Our latest articles',
            'share' => 'Share',
            'read_more' => 'Read more',
            'published_on' => 'Published on',
            'by' => 'By',
            'comments' => 'Comments',
            'leave_comment' => 'Leave a comment'
        ]
    ],
    'nl' => [
        'pages' => [
            'contact' => 'Contact',
            'blog' => 'Blog',
            'rentals' => 'Verhuur'
        ],
        'auth' => [
            'login' => 'Inloggen',
            'register' => 'Registreren'
        ],
        'form' => [
            'name' => 'Naam',
            'first_name' => 'Voornaam',
            'email' => 'Email',
            'phone' => 'Telefoon',
            'message' => 'Bericht',
            'send' => 'Verzenden',
            'password' => 'Wachtwoord',
            'confirm_password' => 'Bevestig wachtwoord',
            'remember_me' => 'Onthoud mij',
            'login' => 'Inloggen',
            'register' => 'Registreren',
            'forgot_password' => 'Wachtwoord vergeten?'
        ],
        'messages' => [
            'thank_you' => 'Bedankt voor uw bericht',
            'reply_soon' => 'We zullen u spoedig antwoorden',
            'error_occurred' => 'Er is een fout opgetreden'
        ],
        'rentals' => [
            'title' => 'Onze verhuurapparatuur',
            'subtitle' => 'Vind de perfecte apparatuur voor uw behoeften',
            'duration' => 'Verhuurduur',
            'start_date' => 'Startdatum',
            'end_date' => 'Einddatum',
            'book' => 'Boeken',
            'deposit' => 'Borg',
            'price_per_day' => 'Prijs per dag'
        ],
        'blog' => [
            'latest_articles' => 'Onze laatste artikelen',
            'share' => 'Delen',
            'read_more' => 'Lees meer',
            'published_on' => 'Gepubliceerd op',
            'by' => 'Door',
            'comments' => 'Reacties',
            'leave_comment' => 'Laat een reactie achter'
        ]
    ]
];

echo "🚀 Mise à jour des traductions pour toutes les pages...\n\n";

foreach ($languages as $locale => $sections) {
    $langFile = __DIR__ . "/resources/lang/{$locale}/app.php";
    
    if (file_exists($langFile)) {
        // Lire le fichier existant
        $existingTranslations = include $langFile;
        
        // Fusionner avec les nouvelles traductions
        $mergedTranslations = array_merge_recursive($existingTranslations, $sections);
        
        // Sauvegarder
        $newContent = "<?php\n\nreturn " . var_export($mergedTranslations, true) . ";\n";
        
        if (file_put_contents($langFile, $newContent)) {
            echo "✅ Fichier {$locale} mis à jour avec les nouvelles traductions\n";
        }
    }
}

echo "\n🎉 Toutes les traductions ont été ajoutées !\n";
echo "📄 Pages concernées : Contact, Blog, Location, Login, Register\n";
echo "🌍 3 langues mises à jour : FR, EN, NL\n";
