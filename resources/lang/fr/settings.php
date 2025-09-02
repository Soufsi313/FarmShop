<?php

return [
    // Page titles
    'page_title' => 'Paramètres',
    'page_description' => 'Configurez les paramètres généraux de votre plateforme',
    'site_settings' => 'Paramètres du site',

    // General Information Section
    'general_information' => [
        'title' => 'Informations générales',
        'site_name' => 'Nom du site',
        'description' => 'Description',
        'contact_email' => 'Email de contact',
        'site_name_placeholder' => 'FarmShop',
        'description_placeholder' => 'Marketplace agricole de confiance pour acheter et louer du matériel agricole de qualité',
        'contact_email_placeholder' => 'contact@farmshop.be',
    ],

    // Payment Configuration Section
    'payment_configuration' => [
        'title' => 'Configuration Stripe',
        'public_key' => 'Clé publique Stripe',
        'secret_key' => 'Clé secrète Stripe',
        'public_key_placeholder' => 'pk_...',
        'secret_key_placeholder' => 'sk_...',
    ],

    // Action Buttons
    'actions' => [
        'cancel' => 'Annuler',
        'save' => 'Sauvegarder',
        'save_success' => 'Paramètres sauvegardés avec succès',
        'save_error' => 'Erreur lors de la sauvegarde des paramètres',
    ],

    // Form Labels and Help Text
    'labels' => [
        'required' => 'Requis',
        'optional' => 'Optionnel',
    ],

    // Additional Settings Sections (for future use)
    'email_settings' => [
        'title' => 'Configuration Email',
        'smtp_host' => 'Serveur SMTP',
        'smtp_port' => 'Port SMTP',
        'smtp_username' => 'Nom d\'utilisateur SMTP',
        'smtp_password' => 'Mot de passe SMTP',
        'from_address' => 'Adresse expéditeur',
        'from_name' => 'Nom expéditeur',
    ],

    'notification_settings' => [
        'title' => 'Paramètres de notification',
        'email_notifications' => 'Notifications par email',
        'sms_notifications' => 'Notifications SMS',
        'push_notifications' => 'Notifications push',
    ],

    'security_settings' => [
        'title' => 'Paramètres de sécurité',
        'two_factor_auth' => 'Authentification à deux facteurs',
        'password_policy' => 'Politique de mot de passe',
        'session_timeout' => 'Timeout de session',
    ],
];
