<?php

return [
    // Page
    'page_title' => 'Blog',
    'title' => 'FarmShop Blog',
    'subtitle' => 'Ontdek ons deskundig advies, landbouwnieuws en praktische gidsen',
    
    // Search and filters
    'search_placeholder' => 'Zoek een artikel...',
    'all_categories' => 'Alle categorieën',
    'search_button' => 'Zoeken',
    'filter_button' => 'Filteren',
    
    // Categories
    'categories' => 'Categorieën',
    'popular_articles' => 'Populaire artikelen',
    
    // Results
    'no_articles' => 'Geen artikelen gevonden',
    'no_articles_message' => 'Geen artikelen komen overeen met uw zoekcriteria.',
    'articles_available_soon' => 'Blogartikelen zijn binnenkort beschikbaar.',
    
    // Article meta
    'published_on' => 'Gepubliceerd op',
    'by_author' => 'door',
    'read_more' => 'Lees meer',
    'comments' => 'reactie(s)',
    'views' => 'weergave(s)',
    'reading_time' => 'min lezen',
    
    // Sort options
    'sort' => [
        'recent' => 'Meest recent',
        'popular' => 'Meest populair', 
        'views' => 'Meest bekeken',
        'comments' => 'Meest becommentarieerd'
    ],
    
    // Tags
    'tags' => 'Tags',
    'related_articles' => 'Gerelateerde artikelen',
    
    // Admin interface
    'admin' => [
        'title' => 'Blog Artikelen Beheer',
        'page_title' => 'Blog Artikelen Beheer - FarmShop Admin',
        'subtitle' => 'Beheer en bekijk alle artikelen op je FarmShop blog',
        'blog_articles' => 'Blog Artikelen',
        
        // Actions
        'manage_categories' => 'Categorieën Beheren',
        'view_public_blog' => 'Openbare Blog Bekijken',
        'new_article' => 'Nieuw Artikel',
        
        // Statistics
        'total_articles' => 'Totaal Artikelen',
        'published' => 'Gepubliceerd',
        'drafts' => 'Concepten',
        'categories' => 'Categorieën',
        
        // Filters and search
        'search_filters_title' => 'Zoeken en Geavanceerde Filters',
        'search_articles' => 'Zoek artikelen',
        'search_placeholder_admin' => 'Titel, inhoud, samenvatting, tags...',
        'category' => 'Categorie',
        'all_categories_admin' => 'Alle categorieën',
        'articles_count' => ':count artikelen',
        'publication_status' => 'Publicatiestatus',
        'all_statuses' => 'Alle statussen',
        'published_status' => '✅ Gepubliceerd',
        'draft_status' => '📝 Concept',
        'scheduled_status' => '⏰ Gepland',
        'author' => 'Auteur',
        'all_authors' => 'Alle auteurs',
        'sort_by' => 'Sorteren op',
        'sort_creation_date' => 'Aanmaakdatum',
        'sort_update_date' => 'Laatste wijziging',
        'sort_publication_date' => 'Publicatiedatum',
        'sort_views_count' => 'Aantal weergaven',
        'sort_title' => 'Alfabetische titel',
        'order' => 'Volgorde',
        'descending' => '↓ Aflopend',
        'ascending' => '↑ Oplopend',
        'hide_advanced_filters' => 'Geavanceerde filters verbergen',
        'advanced_filters' => 'Geavanceerde filters',
        'reset' => 'Reset',
        'search_button' => 'Zoeken',
        'articles_list_title' => 'Artikelen (:count)',
        'sorted_by' => 'Gesorteerd op :sort_by (:direction)',
        
        // Table headers
        'table_article' => 'Artikel',
        'table_category' => 'Categorie',
        'table_author' => 'Auteur',
        'table_status' => 'Status',
        'table_date' => 'Datum',
        'table_views' => 'Weergaven',
        'table_actions' => 'Acties',
        
        // Article status badges
        'featured_badge' => '★ Uitgelicht',
        'no_category' => 'Geen categorie',
        'status_published' => 'Gepubliceerd',
        'status_draft' => 'Concept',
        'status_scheduled' => 'Gepland',
        
        // Action tooltips
        'view_article' => 'Artikel bekijken',
        'edit_article' => 'Artikel bewerken',
        'delete_article' => 'Artikel verwijderen',
        
        // Empty state messages
        'no_articles' => 'Geen artikelen',
        'no_articles_match_criteria' => 'Geen artikelen voldoen aan je zoekcriteria.',
        'create_first_article' => 'Begin met het maken van je eerste blog artikel.',
        
        // Info section
        'info_title' => '💡 Informatie over artikel beheer',
        'info_description_1' => 'Deze interface maakt <strong>bekijken en filteren</strong> van bestaande artikelen mogelijk.',
        'info_description_2' => 'Volledig beheer (aanmaken, bewerken, verwijderen) is beschikbaar via <strong>REST API</strong>. Bekijk de API documentatie voor meer details.',
        'api_documentation' => '📖 API Documentatie',
        'view_public_blog_info' => '🌐 Openbare blog bekijken',
        
        // Delete modal
        'confirm_deletion' => 'Verwijdering bevestigen',
        'delete_confirmation_question' => 'Weet je zeker dat je dit artikel wilt verwijderen?',
        'irreversible_action' => 'Deze actie is onomkeerbaar.',
        'cancel' => 'Annuleren',
        'delete' => 'Verwijderen',
        'deleting' => 'Verwijderen...',
    ],
    
    // Comments management
    'comments' => [
        'page_title' => 'Commentaarbeheer - FarmShop Admin',
        'section_title' => 'Commentaarbeheer',
        'title' => 'Commentaarbeheer',
        'description' => 'Beheer alle blogcommentaren en hun moderatie',
        
        // Header actions
        'filters_button' => 'Filters',
        'refresh_button' => 'Vernieuwen',
        
        // Statistics
        'total_comments' => 'Totaal Commentaren',
        'pending_comments' => 'In Afwachting',
        'approved_comments' => 'Goedgekeurd',
        'rejected_comments' => 'Afgewezen',
        'spam_comments' => 'Spam',
        'reported_comments' => 'Meldingen',
        
        // Search and filters
        'search_label' => 'Algemeen zoeken',
        'search_placeholder' => 'Zoeken op auteur, inhoud...',
        'status' => 'Moderatiestatus',
        'all_statuses' => 'Alle statussen',
        'status_pending' => 'In afwachting',
        'status_approved' => 'Goedgekeurd',
        'status_rejected' => 'Afgewezen',
        'status_spam' => 'Spam',
        'reports_label' => 'Meldingen',
        'all_reports' => 'Alle',
        'with_reports' => 'Met meldingen',
        'without_reports' => 'Zonder meldingen',
        
        // Table headers
        'comment' => 'Commentaar',
        'author' => 'Auteur',
        'article' => 'Artikel',
        'date' => 'Datum',
        'status' => 'Status',
        'actions' => 'Acties',
        'content' => 'Inhoud',
        
        // Advanced search section
        'advanced_search_title' => 'Geavanceerd zoeken en filters',
        'advanced_search_description' => 'Vind snel de reacties die u zoekt met onze filtertools',
        
        // Bulk actions
        'select_all' => 'Alles selecteren',
        'selected_count' => 'geselecteerd',
        'bulk_approve' => 'Goedkeuren',
        'bulk_reject' => 'Afwijzen',
        'bulk_delete' => 'Verwijderen',
        'quick_actions' => 'Snelle acties',
        
        // Comments list
        'comments_list' => 'Reactielijst',
        
        // Status labels
        'status_pending' => 'In afwachting',
        'status_approved' => 'Goedgekeurd',
        'status_rejected' => 'Afgewezen',
        'status_spam' => 'Spam',
        
        // Actions
        'approve' => 'Goedkeuren',
        'reject' => 'Afwijzen',
        'mark_spam' => 'Markeer spam',
        'edit' => 'Bewerken',
        'delete' => 'Verwijderen',
        'view_article' => 'Artikel bekijken',
        'reply' => 'Antwoorden',
        
        // Filters
        'filter_by_status' => 'Filter op status',
        'filter_by_article' => 'Filter op artikel',
        'search_label' => 'Zoeken',
        'search_placeholder' => 'Zoeken in commentaren...',
        'all_statuses' => 'Alle statussen',
        'all_articles' => 'Alle artikelen',
        'filter_button' => 'Filteren',
        'reset_filters' => 'Resetten',
        'reports_label' => 'Meldingen',
        'all_reports' => 'Alle',
        'with_reports' => 'Met meldingen',
        'without_reports' => 'Zonder meldingen',
        
        // Bulk actions
        'bulk_actions' => 'Bulk acties',
        'select_all' => 'Alles selecteren',
        'selected_count' => 'geselecteerd',
        'bulk_approve' => 'Geselecteerde goedkeuren',
        'bulk_reject' => 'Geselecteerde afwijzen',
        'bulk_spam' => 'Geselecteerde spam markeren',
        'bulk_delete' => 'Geselecteerde verwijderen',
        
        // Table
        'comments_list' => 'Commentarenlijst',
                'loading' => 'Reacties laden...',
        'no_comments' => 'Geen reacties',
        'no_comments_message' => 'Geen reacties komen overeen met uw zoekcriteria.',
        
        // JavaScript-berichten
        'js_error_loading' => 'Fout bij laden van reacties',
        'js_error_loading_detail' => 'Fout bij laden van details',
        'js_select_action' => 'Selecteer een actie',
        'js_action_success' => 'Actie succesvol uitgevoerd',
        'js_moderation_error' => 'Fout tijdens moderatie',
        'js_delete_success' => 'Reactie succesvol verwijderd',
        'js_delete_error' => 'Fout tijdens verwijdering',
        'js_select_comments' => 'Selecteer ten minste één reactie',
        
        // Messages
        'no_comments' => 'Geen reacties',
        'no_comments_message' => 'Geen reacties komen overeen met uw zoekcriteria.',
        
        // Table headers
        'table_author' => 'Auteur',
        'table_article' => 'Artikel',
        'table_status' => 'Status',
        'table_date' => 'Datum',
        'table_actions' => 'Acties',
        
        // Action buttons
        'action_approve' => 'Goedkeuren',
        'action_reject' => 'Afwijzen',
        'action_spam' => 'Markeren als spam',
        'action_delete' => 'Verwijderen',
        
        // Additional JavaScript messages
        'js_confirm_delete' => 'Weet je zeker dat je deze reactie wilt verwijderen?',
        'js_bulk_action_success' => 'Actie succesvol uitgevoerd',
        'js_bulk_action_error' => 'Fout tijdens bulk actie',
        'js_data_refreshed' => 'Gegevens ververst',
        'js_feature_development' => 'Functie in ontwikkeling',
        
        // Status labels for JavaScript
        'status_approved_label' => 'Goedgekeurd',
        'status_rejected_label' => 'Afgewezen',
        
        // Additional table headers
        'table_content' => 'Inhoud',
        'table_reports' => 'Meldingen',
        
        // Guest info
        'anonymous' => 'Anoniem',
        'not_available' => 'N.v.t.',
    ],
];
