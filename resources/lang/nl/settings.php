<?php

return [
    // Page titles
    'page_title' => 'Instellingen',
    'page_description' => 'Configureer de algemene instellingen van uw platform',
    'site_settings' => 'Site Instellingen',

    // General Information Section
    'general_information' => [
        'title' => 'Algemene Informatie',
        'site_name' => 'Site Naam',
        'description' => 'Beschrijving',
        'contact_email' => 'Contact Email',
        'site_name_placeholder' => 'FarmShop',
        'description_placeholder' => 'Vertrouwde agrarische marktplaats voor het kopen en huren van kwaliteitslandbouwuitrusting',
        'contact_email_placeholder' => 'contact@farmshop.be',
    ],

    // Payment Configuration Section
    'payment_configuration' => [
        'title' => 'Stripe Configuratie',
        'public_key' => 'Stripe Publieke Sleutel',
        'secret_key' => 'Stripe Geheime Sleutel',
        'public_key_placeholder' => 'pk_...',
        'secret_key_placeholder' => 'sk_...',
    ],

    // Action Buttons
    'actions' => [
        'cancel' => 'Annuleren',
        'save' => 'Opslaan',
        'save_success' => 'Instellingen succesvol opgeslagen',
        'save_error' => 'Fout bij het opslaan van instellingen',
    ],

    // Form Labels and Help Text
    'labels' => [
        'required' => 'Verplicht',
        'optional' => 'Optioneel',
    ],

    // Additional Settings Sections (for future use)
    'email_settings' => [
        'title' => 'Email Configuratie',
        'smtp_host' => 'SMTP Host',
        'smtp_port' => 'SMTP Poort',
        'smtp_username' => 'SMTP Gebruikersnaam',
        'smtp_password' => 'SMTP Wachtwoord',
        'from_address' => 'Afzender Adres',
        'from_name' => 'Afzender Naam',
    ],

    'notification_settings' => [
        'title' => 'Notificatie Instellingen',
        'email_notifications' => 'Email Notificaties',
        'sms_notifications' => 'SMS Notificaties',
        'push_notifications' => 'Push Notificaties',
    ],

    'security_settings' => [
        'title' => 'Beveiligingsinstellingen',
        'two_factor_auth' => 'Tweefactorauthenticatie',
        'password_policy' => 'Wachtwoordbeleid',
        'session_timeout' => 'Sessie Timeout',
    ],
];
