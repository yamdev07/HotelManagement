<?php

return [
    // Index page
    'page_title' => 'Room Types',
    'header_title' => 'Room <em>Types</em>',
    'header_sub' => ':count type(s) available',
    'new_type' => 'New type',
    'view_rooms' => 'View rooms',

    // Stat cards
    'stat_total' => 'Total types',
    'stat_total_footer' => 'Room types',
    'stat_active' => 'Active',
    'stat_active_footer' => 'Visible',
    'stat_inactive' => 'Inactive',
    'stat_inactive_footer' => 'Hidden',
    'stat_rooms' => 'Rooms',
    'stat_rooms_footer' => 'Total',

    // Table
    'card_title' => 'All room types',
    'col_number' => '#',
    'col_details' => 'Details',
    'col_rate' => 'Rate',
    'col_capacity' => 'Capacity',
    'col_status' => 'Status',
    'col_rooms' => 'Rooms',
    'col_actions' => 'Actions',

    // Labels
    'no_description' => 'No description',
    'popular' => 'Popular',
    'base_price_label' => 'base price',
    'not_defined' => 'Not defined',
    'person_s' => 'person(s)',
    'bed_type' => ':type bed',
    'active' => 'Active',
    'inactive' => 'Inactive',
    'room_s' => 'room(s)',

    // Tooltips
    'tooltip_edit' => 'Edit',
    'tooltip_view_rooms' => 'View rooms',
    'tooltip_delete' => 'Delete',

    // Footer
    'footer_showing' => 'Showing :count room type(s)',
    'footer_delete_disabled' => 'Deletion is disabled for types with assigned rooms',

    // Empty state
    'empty_title' => 'No room types',
    'empty_text_1' => 'Start by creating your first room type.',
    'empty_text_2' => 'Types help organize your rooms by category and rate.',
    'empty_add' => 'Create a type',

    // Help
    'help_title' => 'Need help managing room types?',
    'help_text' => 'Types define categories like "Standard", "Deluxe" or "Suite". Each type can have different rates, capacities and amenities.',
    'help_manage_rooms' => 'Manage rooms',

    // JS
    'js_delete_impossible' => 'Cannot delete ":name" because :count room(s) are assigned to it.\n\nPlease reassign or delete those rooms first.',
    'js_delete_confirm' => 'Are you sure you want to delete ":name"?\n\nThis action is irreversible.',

    // Create page
    'create_title' => 'New <em>type</em>',
    'create_subtitle' => 'Add a new room type',
    'breadcrumb_dashboard' => 'Dashboard',
    'breadcrumb_types' => 'Room types',
    'breadcrumb_new' => 'New type',
    'back' => 'Back',
    'card_info' => 'Type information',
    'label_name' => 'Type name *',
    'label_price' => 'Base price (FCFA)',
    'label_capacity' => 'Capacity',
    'label_description' => 'Description',
    'label_active' => 'Active (available for selection)',
    'placeholder_name' => 'e.g. Standard, Deluxe, Suite',
    'placeholder_price' => '50000',
    'placeholder_description' => 'Room type description...',
    'hint_price' => 'Recommended price per night',
    'hint_select' => '-- Select --',
    'submit_create' => 'Create type',
    'cancel' => 'Cancel',
    'saving' => 'Saving...',
    'js_error_create' => 'Error during creation',
    'js_error_network' => 'Network error. Please try again.',

    // Edit page
    'edit_title' => 'Edit <em>type</em>',
    'edit_subtitle' => ':name · Update information',
    'breadcrumb_edit' => 'Edit: :name',
    'submit_update' => 'Update',
    'js_error_edit' => 'Error during update',
];
