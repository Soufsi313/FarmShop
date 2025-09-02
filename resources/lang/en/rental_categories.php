<?php

return [
    // Page titles and meta
    'title' => 'Rental Categories Management - FarmShop Admin',
    'page_title' => 'Rental Categories Management',
    
    // Header section
    'header' => [
        'title' => 'Rental Categories',
        'description' => 'Organize your rental product catalog',
        'add_category' => 'Add category',
    ],
    
    // Status badges
    'status' => [
        'active' => 'Active',
        'inactive' => 'Inactive',
    ],
    
    // Statistics and info
    'stats' => [
        'products_count' => 'rental product(s)',
        'products_singular' => 'rental product',
        'products_plural' => 'rental products',
        'no_products' => '0 rental products',
    ],
    
    // Actions
    'actions' => [
        'view' => 'View',
        'edit' => 'Edit', 
        'delete' => 'Delete',
        'create' => 'Create',
        'save' => 'Save',
        'cancel' => 'Cancel',
        'close' => 'Close',
    ],
    
    // Empty state
    'empty' => [
        'title' => 'No rental categories',
        'description' => 'Start by creating your first rental category.',
        'add_first' => 'Add category',
    ],
    
    // Delete modal
    'delete_modal' => [
        'title' => 'Delete rental category',
        'confirm_message' => 'Are you sure you want to delete the category ":name"? This action is irreversible.',
        'cannot_delete' => 'Cannot delete category ":name" because it contains :count rental product(s).',
        'delete_button' => 'Delete',
        'close_button' => 'Close',
    ],
    
    // Messages
    'messages' => [
        'created' => 'Rental category created successfully',
        'updated' => 'Rental category updated successfully',
        'deleted' => 'Rental category deleted successfully',
        'error' => 'An error occurred',
    ],

    // Empty state messages
    'empty_state' => [
        'title' => 'No rental categories',
        'description' => 'You haven\'t created any rental categories yet.',
        'action' => 'Create my first category',
    ],

    // Details page
    'show' => [
        'title' => 'Category Details',
        'page_title' => 'Rental Category Details',
        'edit_button' => 'Edit',
        'back_button' => 'Back to list',
        'general_info' => 'General information',
        'name_label' => 'Name',
        'translated_name_label' => 'Translated name',
        'slug_label' => 'Slug',
        'display_order_label' => 'Display order',
        'icon_label' => 'Icon',
        'no_icon' => 'No icon',
        'description_label' => 'Description',
        'translated_description_label' => 'Translated description',
        'seo_section' => 'SEO',
        'meta_title_label' => 'SEO Title (meta title)',
        'meta_description_label' => 'SEO Description (meta description)',
        'not_defined' => 'Not defined',
        'characters' => 'characters',
        'too_long' => '(⚠️ Too long)',
        'too_short' => '(⚠️ Too short)',
        'optimal' => '(✅ Optimal)',
        'sidebar_section' => 'Additional information',
        'status_label' => 'Status',
        'creation_date_label' => 'Creation date',
        'update_date_label' => 'Last modified',
        'products_count_label' => 'Number of products',
        'products_count_value' => ':count rental product(s)',
        'quick_actions' => 'Quick actions',
        'activate_button' => 'Activate',
        'deactivate_button' => 'Deactivate',
        'delete_button' => 'Delete',
        'view_products' => 'View products',
        'google_preview' => 'Google Preview',
        'statistics_section' => 'Statistics',
        'associated_products' => 'Associated products',
        'active_products' => 'Active products',
        'inactive_products' => 'Inactive products',
        'system_info' => 'System information',
        'created_on' => 'Created on',
        'modified_on' => 'Modified on',
        'deleted_on' => 'Deleted on',
    ],
];
