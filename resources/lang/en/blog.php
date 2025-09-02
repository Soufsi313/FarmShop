<?php

return [
    // Page
    'page_title' => 'Blog',
    'title' => 'FarmShop Blog',
    'subtitle' => 'Discover our expert advice, agricultural news and practical guides',
    
    // Search and filters
    'search_placeholder' => 'Search an article...',
    'all_categories' => 'All categories',
    'search_button' => 'Search',
    'filter_button' => 'Filter',
    
    // Categories
    'categories' => 'Categories',
    'popular_articles' => 'Popular articles',
    
    // Results
    'no_articles' => 'No articles found',
    'no_articles_message' => 'No articles match your search criteria.',
    'articles_available_soon' => 'Blog articles will be available soon.',
    
    // Article meta
    'published_on' => 'Published on',
    'by_author' => 'by',
    'read_more' => 'Read more',
    'comments' => 'comment(s)',
    'views' => 'view(s)',
    'reading_time' => 'min read',
    
    // Sort options
    'sort' => [
        'recent' => 'Most recent',
        'popular' => 'Most popular', 
        'views' => 'Most viewed',
        'comments' => 'Most commented'
    ],
    
    // Tags
    'tags' => 'Tags',
    'related_articles' => 'Related articles',
    
    // Admin interface
    'admin' => [
        'title' => 'Blog Articles Management',
        'page_title' => 'Blog Articles Management - FarmShop Admin',
        'subtitle' => 'Manage and view all articles on your FarmShop blog',
        'blog_articles' => 'Blog Articles',
        
        // Actions
        'manage_categories' => 'Manage Categories',
        'view_public_blog' => 'View Public Blog',
        'new_article' => 'New Article',
        
        // Statistics
        'total_articles' => 'Total Articles',
        'published' => 'Published',
        'drafts' => 'Drafts',
        'categories' => 'Categories',
        
        // Filters and search
        'search_filters_title' => 'Search and Advanced Filters',
        'search_articles' => 'Search articles',
        'search_placeholder_admin' => 'Title, content, excerpt, tags...',
        'category' => 'Category',
        'all_categories_admin' => 'All categories',
        'articles_count' => ':count articles',
        'publication_status' => 'Publication status',
        'all_statuses' => 'All statuses',
        'published_status' => '✅ Published',
        'draft_status' => '📝 Draft',
        'scheduled_status' => '⏰ Scheduled',
        'author' => 'Author',
        'all_authors' => 'All authors',
        'sort_by' => 'Sort by',
        'sort_creation_date' => 'Creation date',
        'sort_update_date' => 'Last modification',
        'sort_publication_date' => 'Publication date',
        'sort_views_count' => 'Views count',
        'sort_title' => 'Alphabetical title',
        'order' => 'Order',
        'descending' => '↓ Descending',
        'ascending' => '↑ Ascending',
        'hide_advanced_filters' => 'Hide advanced filters',
        'advanced_filters' => 'Advanced filters',
        'reset' => 'Reset',
        'search_button' => 'Search',
        'articles_list_title' => 'Articles (:count)',
        'sorted_by' => 'Sorted by :sort_by (:direction)',
        
        // Table headers
        'table_article' => 'Article',
        'table_category' => 'Category',
        'table_author' => 'Author',
        'table_status' => 'Status',
        'table_date' => 'Date',
        'table_views' => 'Views',
        'table_actions' => 'Actions',
        
        // Article status badges
        'featured_badge' => '★ Featured',
        'no_category' => 'No category',
        'status_published' => 'Published',
        'status_draft' => 'Draft',
        'status_scheduled' => 'Scheduled',
        
        // Action tooltips
        'view_article' => 'View article',
        'edit_article' => 'Edit article',
        'delete_article' => 'Delete article',
        
        // Empty state messages
        'no_articles' => 'No articles',
        'no_articles_match_criteria' => 'No articles match your search criteria.',
        'create_first_article' => 'Start by creating your first blog article.',
        
        // Info section
        'info_title' => '💡 Information about article management',
        'info_description_1' => 'This interface allows <strong>viewing and filtering</strong> existing articles.',
        'info_description_2' => 'Complete management (creation, editing, deletion) is available via <strong>REST API</strong>. Check the API documentation for more details.',
        'api_documentation' => '📖 API Documentation',
        'view_public_blog_info' => '🌐 View public blog',
        
        // Delete modal
        'confirm_deletion' => 'Confirm deletion',
        'delete_confirmation_question' => 'Are you sure you want to delete this article?',
        'irreversible_action' => 'This action is irreversible.',
        'cancel' => 'Cancel',
        'delete' => 'Delete',
        'deleting' => 'Deleting...',
    ],
    
    // Comments management
    'comments' => [
        'page_title' => 'Comments Management - FarmShop Admin',
        'section_title' => 'Comments Management',
        'title' => 'Comments Management',
        'description' => 'Manage all blog comments and their moderation',
        
        // Header actions
        'filters_button' => 'Filters',
        'refresh_button' => 'Refresh',
        
        // Statistics
        'total_comments' => 'Total Comments',
        'pending_comments' => 'Pending',
        'approved_comments' => 'Approved',
        'rejected_comments' => 'Rejected',
        'spam_comments' => 'Spam',
        'reported_comments' => 'Reports',
        
        // Search and filters
        'search_label' => 'General Search',
        'search_placeholder' => 'Search by author, content...',
        'status' => 'Moderation Status',
        'all_statuses' => 'All statuses',
        'status_pending' => 'Pending',
        'status_approved' => 'Approved',
        'status_rejected' => 'Rejected',
        'status_spam' => 'Spam',
        'reports_label' => 'Reports',
        'all_reports' => 'All',
        'with_reports' => 'With reports',
        'without_reports' => 'Without reports',
        
        // Table headers
        'comment' => 'Comment',
        'author' => 'Author',
        'article' => 'Article',
        'date' => 'Date',
        'status' => 'Status',
        'actions' => 'Actions',
        'content' => 'Content',
        
        // Advanced search section
        'advanced_search_title' => 'Advanced Search and Filters',
        'advanced_search_description' => 'Quickly find the comments you are looking for with our filtering tools',
        
        // Bulk actions
        'select_all' => 'Select All',
        'selected_count' => 'selected',
        'bulk_approve' => 'Approve',
        'bulk_reject' => 'Reject',
        'bulk_delete' => 'Delete',
        'quick_actions' => 'Quick Actions',
        
        // Comments list
        'comments_list' => 'Comments List',
        
        // Status labels
        'status_pending' => 'Pending',
        'status_approved' => 'Approved',
        'status_rejected' => 'Rejected',
        'status_spam' => 'Spam',
        
        // Actions
        'approve' => 'Approve',
        'reject' => 'Reject',
        'mark_spam' => 'Mark spam',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'view_article' => 'View article',
        'reply' => 'Reply',
        
        // Filters
        'filter_by_status' => 'Filter by status',
        'filter_by_article' => 'Filter by article',
        'search_label' => 'Search',
        'search_placeholder' => 'Search in comments...',
        'all_statuses' => 'All statuses',
        'all_articles' => 'All articles',
        'filter_button' => 'Filter',
        'reset_filters' => 'Reset',
        'reports_label' => 'Reports',
        'all_reports' => 'All',
        'with_reports' => 'With reports',
        'without_reports' => 'Without reports',
        
        // Bulk actions
        'bulk_actions' => 'Bulk actions',
        'select_all' => 'Select all',
        'selected_count' => 'selected',
        'bulk_approve' => 'Approve selected',
        'bulk_reject' => 'Reject selected',
        'bulk_spam' => 'Mark spam selected',
        'bulk_delete' => 'Delete selected',
        
        // Table
        'comments_list' => 'Comments List',
                'loading' => 'Loading comments...',
        'no_comments' => 'No comments',
        'no_comments_message' => 'No comments match your search criteria.',
        
        // JavaScript messages
        'js_error_loading' => 'Error loading comments',
        'js_error_loading_detail' => 'Error loading details',
        'js_select_action' => 'Please select an action',
        'js_action_success' => 'Action completed successfully',
        'js_moderation_error' => 'Error during moderation',
        'js_delete_success' => 'Comment deleted successfully',
        'js_delete_error' => 'Error during deletion',
        'js_select_comments' => 'Please select at least one comment',
        
        // Messages
        'no_comments' => 'No comments',
        'no_comments_message' => 'No comments match your search criteria.',
        
        // Table headers
        'table_author' => 'Author',
        'table_article' => 'Article',
        'table_status' => 'Status',
        'table_date' => 'Date',
        'table_actions' => 'Actions',
        
        // Action buttons
        'action_approve' => 'Approve',
        'action_reject' => 'Reject',
        'action_spam' => 'Mark as spam',
        'action_delete' => 'Delete',
        
        // Additional JavaScript messages
        'js_confirm_delete' => 'Are you sure you want to delete this comment?',
        'js_bulk_action_success' => 'Action completed successfully',
        'js_bulk_action_error' => 'Error during bulk action',
        'js_data_refreshed' => 'Data refreshed',
        'js_feature_development' => 'Feature in development',
        
        // Status labels for JavaScript
        'status_approved_label' => 'Approved',
        'status_rejected_label' => 'Rejected',
        
        // Additional table headers
        'table_content' => 'Content',
        'table_reports' => 'Reports',
        
        // Guest info
        'anonymous' => 'Anonymous',
        'not_available' => 'N/A',
    ],
];
