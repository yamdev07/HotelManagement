<?php

return [
    // ── Index page ──
    'page_title' => 'Room Management',
    'header_title' => 'Room <em>Management</em>',
    'total_rooms_suffix' => 'rooms total',
    'showing_range' => 'Showing :first-:last',
    'new_room' => 'New room',

    // Stat cards
    'stat_total' => 'Total rooms',
    'stat_total_footer' => 'Total capacity',
    'stat_available' => 'Available',
    'stat_available_footer' => 'Ready for check-in',
    'stat_occupied' => 'Occupied',
    'stat_occupied_footer' => 'In progress',
    'stat_dirty' => 'To clean',
    'stat_dirty_footer' => 'Check-in blocked',
    'stat_maintenance' => 'Maintenance',
    'stat_maintenance_footer' => 'Out of service',

    // Action bar
    'all_rooms' => 'All rooms',
    'search_placeholder' => 'Search by number, name or type...',

    // Card
    'list_title' => 'Room list',
    'entries_badge' => ':count entries',

    // Table headers
    'col_number' => 'Room #',
    'col_name' => 'Name',
    'col_type' => 'Type',
    'col_capacity' => 'Capacity',
    'col_price' => 'Price (FCFA)',
    'col_status' => 'Status',
    'col_actions' => 'Actions',

    // Capacity
    'person_s' => 'person(s)',
    'custom_price' => 'Custom price',
    'base_price' => 'Base: :price FCFA',
    'standard' => 'Standard',

    // Action tooltips
    'tooltip_view' => 'View details',
    'tooltip_edit' => 'Edit',
    'tooltip_mark_dirty' => 'Mark as dirty',
    'tooltip_mark_clean' => 'Mark as clean',
    'tooltip_delete' => 'Delete',
    'tooltip_already_dirty' => 'Already dirty',
    'tooltip_room_occupied' => 'Room occupied',
    'tooltip_action_unavailable' => 'Action unavailable',
    'tooltip_no_clean_needed' => 'No cleaning needed',
    'tooltip_delete_occupied' => 'Cannot delete an occupied room',

    // Confirm messages
    'confirm_mark_dirty' => 'Mark room :number as dirty?',
    'confirm_mark_clean' => 'Mark room :number as clean?',
    'confirm_delete' => 'Delete room :number? This action is irreversible.',

    // Empty state
    'empty_title' => 'No rooms found',
    'empty_text' => "You haven't added any rooms yet.",
    'empty_add' => 'Add a room',

    // Pagination
    'pagination_info' => 'Showing :first to :last of :total entries',

    // JS search empty
    'search_no_result' => 'No results',
    'search_no_match' => 'No rooms match your search',

    // ── Create page ──
    'create_page_title' => 'New Room',
    'breadcrumb_dashboard' => 'Dashboard',
    'breadcrumb_rooms' => 'Rooms',
    'breadcrumb_new' => 'New room',
    'create_title' => 'New <em>room</em>',
    'create_subtitle' => 'Add a new room to the hotel',
    'back' => 'Back',
    'error_heading' => 'Please correct the following errors:',

    // Form
    'card_info' => 'Room information',
    'label_number' => 'Room number *',
    'label_name' => 'Room name',
    'optional' => '(Optional)',
    'label_type' => 'Room type *',
    'label_room_status' => 'Room status *',
    'label_capacity' => 'Capacity *',
    'label_price' => 'Price per night *',
    'label_view' => 'View description',

    // Placeholders
    'placeholder_number' => 'e.g. 101, 201, 301',
    'placeholder_name' => 'e.g. Presidential Suite, Ocean View',
    'placeholder_description' => 'e.g. Spacious suite with panoramic view...',
    'placeholder_capacity' => 'e.g. 2, 4, 6',
    'placeholder_price' => 'e.g. 50000',
    'placeholder_view' => 'e.g. Ocean view, Mountain view, City view',

    // Hints
    'hint_unique_id' => 'Unique room identifier · must differ from existing numbers',
    'hint_name' => 'Descriptive name for the room',
    'hint_select_type' => '-- Select a type --',
    'hint_select_status' => '-- Select a status --',
    'hint_status_initial' => 'Initial room status (can be changed later)',
    'hint_capacity' => 'Number of persons (1-10)',
    'hint_view_optional' => 'Optional - describes the view from the room',

    // Live number check
    'number_taken' => 'This number is <strong>already taken</strong>, choose another one.',
    'number_available' => 'Number available.',

    // Existing numbers
    'existing_numbers_title' => 'Already used numbers (:count)',
    'existing_numbers_empty' => 'No rooms yet · this is your first one.',

    // Buttons
    'cancel' => 'Cancel',
    'reset' => 'Reset',
    'create_submit' => 'Create room',
    'saving' => 'Saving...',

    // ── Edit page ──
    'edit_page_title' => 'Edit Room',
    'breadcrumb_room_number' => 'Room :number',
    'breadcrumb_edit' => 'Edit',
    'edit_title' => 'Edit <em>room</em>',
    'edit_subtitle' => 'Room :number · :name',
    'view_button' => 'View',
    'hint_unique_id_edit' => 'Unique room identifier',
    'hint_status_auto' => 'Auto-managed status',
    'hint_status_auto_desc' => 'This status is automatically updated based on reservations and stays.',
    'maintenance_toggle_on' => 'Start maintenance',
    'maintenance_toggle_off' => 'End maintenance',
    'label_status_current' => 'Current status',
    'auto_managed' => '(Auto-managed)',
    'meta_created' => 'Created: :date',
    'meta_updated' => 'Last updated: :date',
    'submit_update' => 'Update',

    // SweetAlert edit page
    'swal_maintenance_start_title' => 'Start maintenance?',
    'swal_maintenance_end_title' => 'End maintenance?',
    'swal_maintenance_start_html' => 'This action will temporarily mark the room as unavailable.',
    'swal_maintenance_end_html' => 'This action will mark the room as available again.',
    'swal_maintenance_reason_label' => 'Maintenance reason:',
    'swal_maintenance_reason_placeholder' => 'Cleaning, repairs, renovation...',
    'swal_maintenance_reason_required' => 'Please enter a maintenance reason',
    'swal_confirm_end' => 'Yes, end',
    'swal_confirm_start' => 'Yes, start maintenance',
    'swal_processing' => 'Processing...',
    'swal_please_wait' => 'Please wait',
    'swal_success_title' => 'Success!',
    'swal_error_title' => 'Error',
    'swal_operation_failed' => 'Operation failed',
    'swal_network_error' => 'Network error. Please try again.',

    // ── Show page ──
    'show_page_title' => 'Room Details',
    'show_title' => 'Room <em>Details</em>',
    'show_subtitle' => 'Room :number · :name',
    'edit_action' => 'Edit',

    // Guest section
    'guest_current' => 'Current guest',
    'guest_no_specified' => 'Not specified',
    'guest_room_available' => 'Room available',
    'guest_no_current' => 'No current guest',

    // Info section
    'info_title' => 'Information',
    'info_type' => 'Type',
    'info_status' => 'Status',
    'info_capacity' => 'Capacity',
    'info_persons' => 'persons',
    'info_price' => 'Price',
    'info_price_per_night' => 'FCFA / night',
    'info_view' => 'View',
    'info_add_image' => 'Add image',

    // Images section
    'images_title' => 'Images',
    'images_empty_title' => 'No images',
    'images_empty_text' => 'This room has no images yet',

    // Modal
    'modal_upload_title' => 'Add image to gallery',
    'modal_label_image' => 'Select an image',
    'modal_formats' => 'JPG, PNG or WEBP · 4 MB max.',
    'modal_upload_btn' => 'Add photo',
    'image_err_required' => 'Please select an image.',
    'image_err_type' => 'Unsupported format. Use JPG, PNG or WEBP.',
    'image_err_size' => 'The image is too large (4 MB max).',
    'upl_drop_title' => 'Drag a photo here',
    'upl_drop_or' => 'or',
    'upl_drop_browse' => 'browse your files',
    'upl_remove' => 'Remove',
    'modal_close' => 'Close',
    'modal_save' => 'Save',
    'modal_image_title_label' => 'Image title (optional)',

    // Confirm
    'confirm_delete_image' => 'Delete this image?',

    // Toast
    'toast_success' => 'Success',
    'toast_error' => 'Error',
    'toast_upload_failed' => 'Upload failed',

    // Shared
    'unknown' => 'Unknown',
    'image_alt' => 'Room image',
    'label_guest' => 'Guest:',
    'label_arrival' => 'Arrival:',
    'label_since' => 'Since:',
    'label_reason' => 'Reason:',
];
