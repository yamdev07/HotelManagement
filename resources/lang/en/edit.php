<?php

return [
    // Page title
    'page_title' => 'Edit Reservation',

    // Breadcrumb
    'dashboard' => 'Dashboard',
    'reservations' => 'Reservations',
    'breadcrumb_edit' => 'Edit',

    // Header
    'edit_reservation_title' => 'Edit Reservation #:id',
    'client_label' => 'Client: :name',
    'back_button' => 'Back',

    // Status alerts
    'cancelled_title' => 'Cancelled Reservation',
    'cancelled_text' => 'This reservation can no longer be modified.',
    'cancelled_at' => 'Cancelled on :date',
    'no_show_title' => 'No Show',
    'no_show_text' => 'The client did not show up.',
    'completed_title' => 'Stay completed',
    'completed_text' => 'The client has left.',

    // Card header
    'edit_reservation_card' => 'Edit reservation',

    // Limited status warning
    'limited_status_warning' => 'This reservation is :status. Modifications are limited.',

    // Client section
    'client_section' => 'Client',
    'client_name' => 'Name',
    'client_phone' => 'Phone',
    'client_email' => 'Email',
    'not_specified' => 'Not specified',
    'client_actions' => 'Actions',
    'view_profile' => 'View profile',

    // Room section
    'room_section' => 'Room',
    'room_changeable' => 'Changeable',
    'select_room' => 'Select a room *',
    'choose_room' => '-- Choose a room --',
    'current_room' => 'Current room',
    'available_rooms' => 'Available rooms',
    'occupied_rooms' => 'Occupied rooms (unavailable)',
    'room_number_format' => 'Room :number - :type (:price CFA/night)',
    'room_occupied_format' => 'Room :number - :type (Occupied for these dates)',
    'room_availability_help' => 'Only rooms available for the selected dates are displayed.',
    'room_number' => 'Number',
    'room_type' => 'Type',
    'room_price_per_night' => 'Price/night',

    // Dates section
    'dates_section' => 'Stay dates',
    'fixed_times_badge' => 'Fixed hours 12pm-12pm',
    'nights_passed_info' => 'Nights already passed:',
    'night' => 'night',
    'nights' => 'nights',
    'since' => 'since',
    'check_in_date' => 'Check-in date *',
    'fixed_time_check_in' => 'Fixed time: 12:00 PM',
    'check_out_date' => 'Check-out date *',
    'fixed_time_check_out' => 'Fixed time: 12:00 PM (grace until 2:00 PM)',

    // Calculator section
    'nights_label' => 'Nights',
    'total_price' => 'Total price',
    'already_paid' => 'Already paid',
    'difference' => 'Difference',
    'check_availability' => 'Check availability',

    // Status section
    'status_section' => 'Status',
    'change_status' => 'Change status',
    'status_reservation' => '📅 Reservation',
    'status_active' => '🏨 In the hotel',
    'status_completed' => '✅ Completed',
    'status_incomplete_payment' => '(incomplete payment)',
    'status_cancelled' => '❌ Cancelled',
    'status_no_show' => '👤 No Show',
    'incomplete_payment_help' => '⚠️ Incomplete payment - Client must settle before departure',
    'cancel_reason_label' => 'Cancellation reason',
    'cancel_reason_placeholder' => 'Why cancel?',
    'current_status' => 'Current status:',
    'reason_label' => 'Reason:',

    // Notes section
    'notes_label' => 'Notes',
    'notes_placeholder' => 'Add notes...',

    // Action buttons
    'cancel_button' => 'Cancel',
    'save_button' => 'Save',

    // Summary sidebar
    'summary_title' => 'Summary',
    'reservation_id' => 'Reservation ID',
    'summary_client' => 'Client',
    'summary_room' => 'Room',
    'arrival' => 'Arrival',
    'departure' => 'Departure',
    'summary_nights' => 'Nights',
    'nights_passed' => 'Nights passed',
    'total' => 'Total',
    'paid' => 'Paid',
    'remaining' => 'Remaining',
    'created_at' => 'Created on',

    // Quick actions sidebar
    'quick_actions_title' => 'Quick actions',
    'confirm_arrival' => 'Confirm arrival?',
    'mark_arrived' => 'Mark arrived',
    'departure_largesse' => 'Departure (grace)',
    'departure_override' => 'Departure override',
    'add_payment' => 'Add payment',
    'view_details' => 'View details',

    // History sidebar
    'history_title' => 'Recent changes',
    'creation' => 'Creation',
    'last_modification' => 'Last modified',
    'cancellation' => 'Cancellation',
    'view_full_history' => 'View full history',

    // Override modal
    'override_title' => 'Departure override after 2:00 PM',
    'override_confirm' => 'Are you sure you want to authorize this departure after 2:00 PM?',
    'departure_planned' => 'Planned departure:',
    'current_time' => 'Current time:',
    'override_reason_label' => 'Override reason:',
    'override_reason_placeholder' => 'Why turn a blind eye?',
    'authorize_departure' => 'Authorize departure',

    // JS strings - payment incomplete
    'js_incomplete_payment_title' => 'Incomplete payment',
    'js_incomplete_payment_text' => 'Cannot mark as completed - Remaining balance: :amount CFA',
    'js_understood' => 'Got it',

    // JS strings - date not reached
    'js_date_not_reached_title' => 'Date not reached',
    'js_arrival_planned_on' => 'Arrival is scheduled for :date',

    // JS strings - errors
    'js_error_title' => 'Error',
    'js_select_dates' => 'Please select the dates',
    'js_select_room' => 'Please select a room',
    'js_fill_all_dates' => 'Please fill in all dates',
    'js_departure_after_arrival' => 'Departure must be after arrival',
    'js_incomplete_payment_error' => 'Incomplete payment - Cannot mark as completed',

    // JS strings - availability check
    'js_checking_title' => 'Checking...',
    'js_room_available' => 'Room available',
    'js_room_not_available' => 'Room not available for these dates',

    // JS strings - cancel edit
    'js_cancel_edit_title' => 'Cancel changes?',
    'js_cancel_edit_text' => 'All unsaved changes will be lost',
    'js_yes_cancel' => 'Yes, cancel',
    'js_no_stay' => 'No, stay',

    // JS strings - save confirmation
    'js_save_title' => 'Save changes?',
    'js_save_html_arrival' => 'Arrival:',
    'js_save_html_departure' => 'Departure:',
    'js_save_html_nights' => 'Nights:',
    'js_save_html_room' => 'Room:',
    'js_yes_save' => 'Yes, save',
    'js_saving' => 'Saving...',

    // JS strings - price change
    'surcharge' => 'Surcharge',
    'reduction' => 'Reduction',
    'price_per_night' => 'Price/night',
    'impact_on_total' => 'Impact on total',
];
