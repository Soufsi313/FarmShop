<?php

return [
    // Page titles
    'page_title' => 'Newsletter Management - Admin Dashboard',
    'section_title' => 'Newsletter Management',
    
    // Header
    'title' => 'Newsletter Management',
    'description' => 'Email marketing campaign management interface',
    'total_newsletters' => 'Total Newsletters',
    
    // Statistics
    'statistics' => [
        'draft' => 'Drafts',
        'scheduled' => 'Scheduled', 
        'sent' => 'Sent',
        'subscribers' => 'Subscribers',
    ],
    
    // Filters section
    'filter_title' => 'Filter and search',
    'new_newsletter' => 'New Newsletter',
    'status_label' => 'Status',
    'all_statuses' => 'All statuses',
    'search_label' => 'Search',
    'search_placeholder' => 'Search by title, subject...',
    'filter_button' => 'Filter',
    'reset_button' => 'Reset',
    
    // Status labels
    'status' => [
        'draft' => 'Draft',
        'scheduled' => 'Scheduled',
        'sent' => 'Sent',
    ],
    
    // Newsletter list
    'results_count' => 'results',
    'sent_at' => 'Sent',
    
    // Action buttons
    'actions' => [
        'view' => 'View',
        'edit' => 'Edit',
        'locked' => 'Locked',
        'duplicate' => 'Duplicate',
        'send' => 'Send',
        'cancel' => 'Cancel',
        'resend' => 'Resend',
        'delete' => 'Delete',
    ],
    
    // Action tooltips
    'tooltips' => [
        'view_details' => 'View details',
        'edit_newsletter' => 'Edit newsletter',
        'locked_sent' => 'Newsletter already sent - editing not possible',
        'duplicate_newsletter' => 'Duplicate this newsletter',
        'send_now' => 'Send now',
        'cancel_schedule' => 'Cancel scheduling',
        'resend_newsletter' => 'Resend this newsletter to all subscribers',
        'delete_permanently' => 'Delete permanently',
    ],
    
    // Confirmation messages
    'confirmations' => [
        'send_now' => 'Are you sure you want to send this newsletter now?',
        'cancel_schedule' => 'Cancel the scheduling of this newsletter?',
        'resend' => 'Are you sure you want to resend this newsletter to all current subscribers?',
        'delete' => 'Are you sure you want to permanently delete this newsletter? This action is irreversible.',
    ],
    
    // Empty state
    'empty' => [
        'title' => 'No newsletter found',
        'no_results' => 'No newsletter matches your criteria.',
        'no_newsletters' => 'Create your first newsletter to get started.',
        'create_first' => 'Create my first newsletter',
    ],
    
    // Subscribers section
    'subscribers' => [
        'title' => 'Subscriber Management',
        'description' => 'Manage your newsletter subscribers: subscribe, unsubscribe, filter',
        'active_subscribers' => 'Active subscribers',
        'search_subscriber' => 'Search for a subscriber',
        'search_placeholder' => 'Name or email...',
        'subscription_status' => 'Subscription status',
        'all_users' => 'All users',
        'subscribed_only' => 'Subscribers only',
        'unsubscribed_only' => 'Non-subscribers only',
        'reset_filters' => 'Reset',
        
        // Bulk actions
        'selected_count' => 'user(s) selected',
        'bulk_subscribe' => 'Subscribe',
        'bulk_unsubscribe' => 'Unsubscribe',
        'bulk_delete' => 'Delete',
        
        // Table headers
        'table' => [
            'user' => 'User',
            'email' => 'Email',
            'status' => 'Status',
            'registration_date' => 'Registration date',
            'actions' => 'Actions',
        ],
        
        // Status labels
        'status_labels' => [
            'subscribed' => 'Subscribed',
            'unsubscribed' => 'Unsubscribed',
        ],
        
        // Actions
        'action_subscribe' => 'Subscribe',
        'action_unsubscribe' => 'Unsubscribe',
        'registered_on' => 'Registered on',
        
        // Empty state
        'no_users_found' => 'No users found',
        'no_matching_users' => 'No users match your search criteria.',
        'no_users_system' => 'No users registered in the system.',
    ],
    
    // JavaScript messages
    'js' => [
        'error' => 'Error',
        'error_occurred' => 'An error occurred',
        'select_user' => 'Please select at least one user',
        'confirm_subscribe' => 'Subscribe {count} user(s) to the newsletter?',
        'confirm_unsubscribe' => 'Unsubscribe {count} user(s) from the newsletter?',
        'confirm_delete_users' => 'Permanently delete {count} user(s)? This action is irreversible.',
    ],
];
