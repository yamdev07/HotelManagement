<?php

return [
    // ── Common / Breadcrumbs ──
    'home' => 'Home',
    'cashier' => 'Cashier',
    'sessions' => 'Sessions',
    'status_active' => 'Active',
    'status_closed' => 'Closed',
    'in_progress' => 'In progress',
    'close' => 'Close',
    'cancel' => 'Cancel',
    'details' => 'View details',
    'back' => 'Back',
    'print' => 'Print',
    'reset' => 'Reset',
    'start' => 'Start',

    // ── Index: Header ──
    'title' => 'Cashier Sessions',
    'subtitle' => 'Manage and view all cashier sessions',
    'new_session' => 'New session',

    // ── Index: Stats cards ──
    'stat_total_sessions' => 'Total sessions',
    'stat_total_since' => 'All time',
    'stat_active_sessions' => 'Active sessions',
    'stat_active_en_cours' => 'In progress',
    'stat_revenue' => 'Revenue',
    'stat_revenue_total' => 'FCFA total',
    'stat_avg_session' => 'Avg/session',
    'stat_avg_per_session' => 'FCFA per session',

    // ── Index: Filters ──
    'filter_all_statuses' => 'All statuses',
    'filter_active_sessions' => 'Active sessions',
    'filter_closed_sessions' => 'Closed sessions',
    'filter_all_users' => 'All users',
    'filter_date_placeholder' => 'Date',
    'filter_apply' => 'Filter',
    'filter_reset' => 'Reset',

    // ── Index: Table headers ──
    'col_id' => 'ID',
    'col_receptionist' => 'Receptionist',
    'col_start' => 'Start',
    'col_end' => 'End',
    'col_duration' => 'Duration',
    'col_initial' => 'Initial',
    'col_final' => 'Final',
    'col_difference' => 'Difference',
    'col_status' => 'Status',
    'col_actions' => 'Actions',
    'unknown_user' => 'Unknown user',
    'tooltip_current_balance' => 'Current balance',
    'estimated' => '(estimated)',
    'btn_view_details' => 'View details',
    'btn_report' => 'Detailed report',

    // ── Index: Empty state ──
    'empty_title' => 'No sessions',
    'empty_message' => 'No cashier sessions were found.',
    'btn_start_session' => 'Start a session',

    // ── Show: Header ──
    'show_title' => 'Session Details',
    'session_label' => 'Session',

    // ── Show: Stats cards ──
    'period' => 'Period',
    'initial_balance' => 'Initial balance',
    'collections' => 'Collections',
    'payments_count' => ':count payment(s)',
    'refunds' => 'Refunds',
    'refunds_count' => ':count tx',
    'net' => 'Net',
    'final_balance' => 'Final balance',
    'gap' => 'Gap',

    // ── Show: Payment table ──
    'payment_history' => 'Payment history',
    'transactions_count' => ':count transaction(s)',
    'col_reference' => 'Reference',
    'col_date' => 'Date',
    'col_client' => 'Client',
    'col_amount' => 'Amount',
    'col_method' => 'Method',
    'col_status' => 'Status',
    'status_completed' => '✓ Completed',
    'status_pending' => '⏱ Pending',
    'btn_view_payment' => 'View details',
    'empty_payments_title' => 'No payments',
    'empty_payments_message' => 'No payments were recorded during this session.',

    // ── Show: Close modal ──
    'close_session_title' => 'Close session',
    'close_session_irreversible' => 'This action is irreversible.',
    'summary_initial_balance' => 'Initial balance',
    'summary_collections' => 'Collections',
    'summary_refunds' => 'Refunds',
    'summary_theoretical_balance' => 'Theoretical balance',
    'form_real_balance' => 'Actual final balance',
    'form_real_balance_hint' => 'Actual amount in cash drawer',
    'form_notes' => 'Notes',
    'form_notes_placeholder' => 'Observations, anomalies...',
    'btn_close_session' => 'Close',

    // ── Create: Header ──
    'create_title' => 'New Session',
    'create_breadcrumb' => 'New session',

    // ── Create: Alerts ──
    'alert_fix_errors' => 'Please fix the following errors:',

    // ── Create: Rules ──
    'rules_title' => 'Important rules',
    'rule_one_session' => 'Only one active session per user at a time',
    'rule_records_payments' => 'This session will record all your payments',
    'rule_close_anytime' => 'You can close it at any time with a detailed report',
    'rule_starting_balance' => 'The starting balance is automatically set to 0 FCFA',

    // ── Create: Form ──
    'form_start_new_session' => 'Start a new session',
    'form_start_subtitle' => 'Open your shift to start recording payments',
    'form_session_info' => 'Session information',
    'form_receptionist' => 'Receptionist:',
    'form_date' => 'Date:',
    'form_time' => 'Time:',
    'form_starting_balance' => 'Starting balance:',
    'form_session_number' => 'Session #:',
    'form_auto_generated' => 'Auto-generated',
    'form_notes_optional' => 'Notes (optional)',
    'form_notes_placeholder' => 'Additional information (specific observations...)',
    'form_notes_max' => 'Maximum 500 characters',
    'btn_cancel' => 'Cancel',
    'btn_start_session' => 'Start',
    'security_note' => 'All actions will be recorded with your name',
    'confirm_start' => 'Start a new session?',
    'starting' => 'Starting...',

    // ── Report: Header ──
    'report_title' => 'Session Report',
    'session_active_badge' => 'Active session',

    // ── Report: Info grid ──
    'info_receptionist' => 'RECEPTIONIST',
    'info_date' => 'DATE',
    'info_schedule' => 'SCHEDULE',
    'info_initial_balance' => 'INITIAL BALANCE',

    // ── Report: KPI cards ──
    'kpi_collected' => 'Collected',
    'kpi_refunded' => 'Refunded',
    'kpi_net' => 'Net',
    'kpi_gap' => 'Gap: :amount',
    'kpi_avg' => 'Average',
    'kpi_payments_count' => ':count payments',
    'kpi_per_tx' => 'FCFA/tx',

    // ── Report: Section headers ──
    'section_breakdown' => 'Breakdown',
    'section_transactions' => 'Transactions',
    'section_tx_count' => ':count tx',

    // ── Report: Table headers ──
    'col_ref_short' => 'Ref.',
    'col_method_short' => 'Method',

    // ── Report: Table footer ──
    'total' => 'Total',
    'total_collected' => 'Collected',
    'total_refunded' => 'Refunded',
    'total_net' => 'Net',

    // ── Report: Summary ──
    'summary_initial' => 'Initial balance',
    'summary_total_collected' => 'Total collected',
    'summary_total_refunded' => 'Total refunded',
    'summary_final' => 'Final balance',

    // ── Report: Signatures ──
    'signature_receptionist' => 'Receptionist',
    'signature_superior' => 'Supervisor',
    'signature_stamp' => 'Stamp',
];
