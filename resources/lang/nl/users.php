<?php

return [
    'title' => 'Gebruikersbeheer - Admin Dashboard',
    'page_title' => 'Gebruikersbeheer',
    'manage_all_users' => 'Beheer alle gebruikers',
    'advanced_interface' => 'Geavanceerde gebruikersaccountbeheerinterface met zoeken en filters',
    'total_users' => 'Totaal Gebruikers',
    
    // Messages
    'success' => 'Succes!',
    'error' => 'Fout!',
    
    // Statistiques
    'stats' => [
        'users' => 'Gebruikers',
        'administrators' => 'Beheerders',
        'deleted' => 'Verwijderd',
        'new_30_days' => 'Nieuw (30d)',
        'active_7_days' => 'Actief (7d)',
    ],
    
    // Recherche et filtres
    'search' => [
        'title' => 'Geavanceerd zoeken en filters',
        'general_search' => 'Algemeen zoeken',
        'placeholder' => 'Naam, gebruikersnaam, email...',
        'filter_by_role' => 'Filter op rol',
        'all_roles' => 'Alle rollen',
        'account_status' => 'Accountstatus',
        'active_only' => '✅ Alleen actieve accounts',
        'deleted_only' => '🗑️ Alleen verwijderde accounts',
        'all_accounts' => '📋 Alle accounts',
        'sort_by' => 'Sorteren op',
        'order' => 'Volgorde',
        'reset' => 'Resetten',
        'apply_filters' => 'Filters toepassen',
    ],
    
    // Options de tri
    'sort_options' => [
        'created_at' => '📅 Registratiedatum',
        'name' => '🔤 Naam',
        'username' => '👤 Gebruikersnaam',
        'email' => '📧 Email',
        'role' => '⚡ Rol',
        'updated_at' => '🔄 Laatste wijziging',
        'deleted_at' => '🗑️ Verwijderingsdatum',
    ],
    
    // Ordre de tri
    'order_options' => [
        'desc' => '⬇️ Aflopend',
        'asc' => '⬆️ Oplopend',
    ],
    
    // Tableau
    'table' => [
        'user' => 'Gebruiker',
        'email' => 'Email',
        'role' => 'Rol',
        'status' => 'Status',
        'newsletter' => 'Nieuwsbrief',
        'registration' => 'Registratie',
        'actions' => 'Acties',
    ],
    
    // Statuts
    'status' => [
        'deleted' => '🗑️ Verwijderd',
        'active' => '✅ Actief',
        'subscribed' => '📧 Geabonneerd',
        'not_subscribed' => '❌ Niet geabonneerd',
    ],
    
    // Actions
    'actions' => [
        'restore' => 'Herstellen',
        'restore_confirm' => 'Weet je zeker dat je deze gebruiker wilt herstellen?',
        'view_details' => 'Details bekijken',
        'edit_user' => 'Gebruiker bewerken',
        'delete_user' => 'Gebruiker verwijderen',
        'delete_confirm' => 'Weet je zeker dat je deze gebruiker wilt verwijderen? Deze actie kan ongedaan worden gemaakt door het account te herstellen.',
    ],
    
    // Messages vides
    'empty' => [
        'title' => 'Geen gebruikers gevonden',
        'filtered' => 'Geen gebruikers komen overeen met je zoekcriteria.',
        'no_users' => 'Gebruikers verschijnen hier zodra ze zich registreren.',
    ],
];
