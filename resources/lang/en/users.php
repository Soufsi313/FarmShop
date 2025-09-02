<?php

return [
    'title' => 'User Management - Admin Dashboard',
    'page_title' => 'User Management',
    'manage_all_users' => 'Manage all users',
    'advanced_interface' => 'Advanced user account management interface with search and filters',
    'total_users' => 'Total Users',
    
    // Messages
    'success' => 'Success!',
    'error' => 'Error!',
    
    // Statistiques
    'stats' => [
        'users' => 'Users',
        'administrators' => 'Administrators',
        'deleted' => 'Deleted',
        'new_30_days' => 'New (30d)',
        'active_7_days' => 'Active (7d)',
    ],
    
    // Recherche et filtres
    'search' => [
        'title' => 'Advanced search and filters',
        'general_search' => 'General search',
        'placeholder' => 'Name, username, email...',
        'filter_by_role' => 'Filter by role',
        'all_roles' => 'All roles',
        'account_status' => 'Account status',
        'active_only' => '✅ Active accounts only',
        'deleted_only' => '🗑️ Deleted accounts only',
        'all_accounts' => '📋 All accounts',
        'sort_by' => 'Sort by',
        'order' => 'Order',
        'reset' => 'Reset',
        'apply_filters' => 'Apply filters',
    ],
    
    // Options de tri
    'sort_options' => [
        'created_at' => '📅 Registration date',
        'name' => '🔤 Name',
        'username' => '👤 Username',
        'email' => '📧 Email',
        'role' => '⚡ Role',
        'updated_at' => '🔄 Last modification',
        'deleted_at' => '🗑️ Deletion date',
    ],
    
    // Ordre de tri
    'order_options' => [
        'desc' => '⬇️ Descending',
        'asc' => '⬆️ Ascending',
    ],
    
    // Tableau
    'table' => [
        'user' => 'User',
        'email' => 'Email',
        'role' => 'Role',
        'status' => 'Status',
        'newsletter' => 'Newsletter',
        'registration' => 'Registration',
        'actions' => 'Actions',
    ],
    
    // Statuts
    'status' => [
        'deleted' => '🗑️ Deleted',
        'active' => '✅ Active',
        'subscribed' => '📧 Subscribed',
        'not_subscribed' => '❌ Not subscribed',
    ],
    
    // Actions
    'actions' => [
        'restore' => 'Restore',
        'restore_confirm' => 'Are you sure you want to restore this user?',
        'view_details' => 'View details',
        'edit_user' => 'Edit user',
        'delete_user' => 'Delete user',
        'delete_confirm' => 'Are you sure you want to delete this user? This action can be undone by restoring the account.',
    ],
    
    // Messages vides
    'empty' => [
        'title' => 'No users found',
        'filtered' => 'No users match your search criteria.',
        'no_users' => 'Users will appear here once they register.',
    ],
];
