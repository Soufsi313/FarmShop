<?php

return [
    // Page titles
    'page_title' => 'Nieuwsbrief Beheer - Admin Dashboard',
    'section_title' => 'Nieuwsbrief Beheer',
    
    // Header
    'title' => 'Nieuwsbrief Beheer',
    'description' => 'Email marketing campagne beheerinterface',
    'total_newsletters' => 'Totaal Nieuwsbrieven',
    
    // Statistics
    'statistics' => [
        'draft' => 'Concepten',
        'scheduled' => 'Gepland', 
        'sent' => 'Verzonden',
        'subscribers' => 'Abonnees',
    ],
    
    // Filters section
    'filter_title' => 'Filteren en zoeken',
    'new_newsletter' => 'Nieuwe Nieuwsbrief',
    'status_label' => 'Status',
    'all_statuses' => 'Alle statussen',
    'search_label' => 'Zoeken',
    'search_placeholder' => 'Zoeken op titel, onderwerp...',
    'filter_button' => 'Filteren',
    'reset_button' => 'Reset',
    
    // Status labels
    'status' => [
        'draft' => 'Concept',
        'scheduled' => 'Gepland',
        'sent' => 'Verzonden',
    ],
    
    // Newsletter list
    'results_count' => 'resultaten',
    'sent_at' => 'Verzonden',
    
    // Action buttons
    'actions' => [
        'view' => 'Bekijken',
        'edit' => 'Bewerken',
        'locked' => 'Vergrendeld',
        'duplicate' => 'Dupliceren',
        'send' => 'Verzenden',
        'cancel' => 'Annuleren',
        'resend' => 'Opnieuw verzenden',
        'delete' => 'Verwijderen',
    ],
    
    // Action tooltips
    'tooltips' => [
        'view_details' => 'Details bekijken',
        'edit_newsletter' => 'Nieuwsbrief bewerken',
        'locked_sent' => 'Nieuwsbrief al verzonden - bewerken niet mogelijk',
        'duplicate_newsletter' => 'Deze nieuwsbrief dupliceren',
        'send_now' => 'Nu verzenden',
        'cancel_schedule' => 'Planning annuleren',
        'resend_newsletter' => 'Deze nieuwsbrief opnieuw verzenden naar alle abonnees',
        'delete_permanently' => 'Permanent verwijderen',
    ],
    
    // Confirmation messages
    'confirmations' => [
        'send_now' => 'Weet je zeker dat je deze nieuwsbrief nu wilt verzenden?',
        'cancel_schedule' => 'De planning van deze nieuwsbrief annuleren?',
        'resend' => 'Weet je zeker dat je deze nieuwsbrief opnieuw wilt verzenden naar alle huidige abonnees?',
        'delete' => 'Weet je zeker dat je deze nieuwsbrief permanent wilt verwijderen? Deze actie is onomkeerbaar.',
    ],
    
    // Empty state
    'empty' => [
        'title' => 'Geen nieuwsbrief gevonden',
        'no_results' => 'Geen nieuwsbrief komt overeen met uw criteria.',
        'no_newsletters' => 'Maak uw eerste nieuwsbrief om te beginnen.',
        'create_first' => 'Mijn eerste nieuwsbrief maken',
    ],
    
    // Subscribers section
    'subscribers' => [
        'title' => 'Abonnee Beheer',
        'description' => 'Beheer uw nieuwsbrief abonnees: abonneren, uitschrijven, filteren',
        'active_subscribers' => 'Actieve abonnees',
        'search_subscriber' => 'Zoeken naar een abonnee',
        'search_placeholder' => 'Naam of email...',
        'subscription_status' => 'Abonnement status',
        'all_users' => 'Alle gebruikers',
        'subscribed_only' => 'Alleen abonnees',
        'unsubscribed_only' => 'Alleen niet-abonnees',
        'reset_filters' => 'Reset',
        
        // Bulk actions
        'selected_count' => 'gebruiker(s) geselecteerd',
        'bulk_subscribe' => 'Abonneren',
        'bulk_unsubscribe' => 'Uitschrijven',
        'bulk_delete' => 'Verwijderen',
        
        // Table headers
        'table' => [
            'user' => 'Gebruiker',
            'email' => 'Email',
            'status' => 'Status',
            'registration_date' => 'Registratiedatum',
            'actions' => 'Acties',
        ],
        
        // Status labels
        'status_labels' => [
            'subscribed' => 'Geabonneerd',
            'unsubscribed' => 'Niet geabonneerd',
        ],
        
        // Actions
        'action_subscribe' => 'Abonneren',
        'action_unsubscribe' => 'Uitschrijven',
        'registered_on' => 'Geregistreerd op',
        
        // Empty state
        'no_users_found' => 'Geen gebruikers gevonden',
        'no_matching_users' => 'Geen gebruikers komen overeen met uw zoekcriteria.',
        'no_users_system' => 'Geen gebruikers geregistreerd in het systeem.',
    ],
    
    // JavaScript messages
    'js' => [
        'error' => 'Fout',
        'error_occurred' => 'Er is een fout opgetreden',
        'select_user' => 'Selecteer ten minste één gebruiker',
        'confirm_subscribe' => '{count} gebruiker(s) abonneren op de nieuwsbrief?',
        'confirm_unsubscribe' => '{count} gebruiker(s) uitschrijven van de nieuwsbrief?',
        'confirm_delete_users' => '{count} gebruiker(s) permanent verwijderen? Deze actie is onomkeerbaar.',
    ],
];
