<?php

return [
    // Page titles
    'page_title' => 'Settings',
    'page_description' => 'Configure the general settings of your platform',
    'site_settings' => 'Site Settings',

    // General Information Section
    'general_information' => [
        'title' => 'General Information',
        'site_name' => 'Site Name',
        'description' => 'Description',
        'contact_email' => 'Contact Email',
        'site_name_placeholder' => 'FarmShop',
        'description_placeholder' => 'Trusted agricultural marketplace for buying and renting quality farming equipment',
        'contact_email_placeholder' => 'contact@farmshop.be',
    ],

    // Payment Configuration Section
    'payment_configuration' => [
        'title' => 'Stripe Configuration',
        'public_key' => 'Stripe Public Key',
        'secret_key' => 'Stripe Secret Key',
        'public_key_placeholder' => 'pk_...',
        'secret_key_placeholder' => 'sk_...',
    ],

    // Action Buttons
    'actions' => [
        'cancel' => 'Cancel',
        'save' => 'Save',
        'save_success' => 'Settings saved successfully',
        'save_error' => 'Error saving settings',
    ],

    // Form Labels and Help Text
    'labels' => [
        'required' => 'Required',
        'optional' => 'Optional',
    ],

    // Additional Settings Sections (for future use)
    'email_settings' => [
        'title' => 'Email Configuration',
        'smtp_host' => 'SMTP Host',
        'smtp_port' => 'SMTP Port',
        'smtp_username' => 'SMTP Username',
        'smtp_password' => 'SMTP Password',
        'from_address' => 'From Address',
        'from_name' => 'From Name',
    ],

    'notification_settings' => [
        'title' => 'Notification Settings',
        'email_notifications' => 'Email Notifications',
        'sms_notifications' => 'SMS Notifications',
        'push_notifications' => 'Push Notifications',
    ],

    'security_settings' => [
        'title' => 'Security Settings',
        'two_factor_auth' => 'Two-Factor Authentication',
        'password_policy' => 'Password Policy',
        'session_timeout' => 'Session Timeout',
    ],
];
