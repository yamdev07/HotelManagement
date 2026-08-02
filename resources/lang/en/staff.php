<?php

return [
    // Page title
    'page_title' => 'Staff',

    // Header
    'header_title' => 'Staff <em>Management</em>',
    'header_subtitle' => '<i class="fas fa-shield-halved"></i> Your team accounts · limited to your establishment',

    // Buttons
    'btn_new_member' => 'New Member',
    'btn_create_account' => 'Create Account',

    // Stats
    'stat_total_members' => 'Total Members',
    'stat_your_team' => 'Your Team',

    // Role labels
    'role_receptionist' => 'Receptionist',
    'role_cashier' => 'Cashier',
    'role_housekeeping' => 'Housekeeping',
    'role_servant' => 'Waiter',
    'role_cuisinier' => 'Cook',

    // Form
    'form_add_member' => 'Add a member',
    'form_hint' => 'Each member receives their own <strong>credentials</strong> and only accesses what their role allows. You never share your password.',
    'form_full_name' => 'Full Name *',
    'form_email' => 'Email * <span class="text-muted">(login identifier)</span>',
    'form_phone' => 'Phone',
    'form_role' => 'Role *',
    'form_role_placeholder' => '- Choose -',
    'form_password' => 'Password *',
    'form_password_placeholder' => '8+ chars, letters + numbers',

    // Table
    'table_member' => 'Member',
    'table_role' => 'Role',
    'table_actions' => 'Actions',
    'table_registered' => ':count registered',

    // Action bar
    'action_team' => 'Team',
    'action_search_placeholder' => 'Search for a member…',

    // List
    'list_title' => 'My Team',

    // Actions
    'action_reset_password' => 'Reset Password',
    'action_delete' => 'Delete',
    'action_reset' => 'Reset',

    // Password reset
    'password_new_placeholder' => 'New password (8+, letters + numbers)',

    // Confirmations
    'confirm_delete' => 'Delete the account of :name?',

    // Empty state
    'empty_title' => 'No members yet.',
    'empty_desc' => 'Click <strong>"New Member"</strong> to add your first recruit.',

    // Alerts
    'alert_success_created' => 'Staff member ":name" created. They can log in with their email and the password you set.',
    'alert_success_reset' => 'Password for :name has been reset.',
    'alert_success_deleted' => 'Member ":name" has been deleted.',
    'alert_error_no_hotel' => 'No establishment associated with your account.',
    'alert_error_unauthorized' => 'Unauthorized action.',

    // Validation attributes
    'validation_name' => 'name',
    'validation_email' => 'email',
    'validation_role' => 'role',
    'validation_password' => 'password',
];
