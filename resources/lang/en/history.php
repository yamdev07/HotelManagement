<?php

return [
    // Page title & navigation
    'page_title' => 'Reservation History',
    'dashboard' => 'Dashboard',
    'reservations' => 'Reservations',
    'reservation' => 'Reservation #:id',
    'history' => 'History',
    'page_heading' => 'Reservation History #:id',
    'page_description' => 'Complete log of changes and events',
    'back_to_details' => 'Back to details',

    // Summary
    'client' => 'Guest',
    'room' => 'Room',
    'room_number' => 'Room :number',
    'current_status' => 'Current Status',
    'created_on' => 'Created on',

    // Filters
    'filter_by_type' => 'Filter by type',
    'all' => 'All',
    'status' => 'Status',
    'payments' => 'Payments',
    'dates' => 'Dates',
    'notes' => 'Notes',
    'total_modifications' => 'Total Modifications',
    'users_involved' => 'Users Involved',

    // Timeline
    'event_timeline' => 'Event Timeline',
    'export' => 'Export',
    'system' => 'System',

    // Creation event
    'creation_badge' => 'Creation',
    'reservation_created' => 'Reservation Created',
    'reservation_created_text' => 'New reservation created with the following parameters:',
    'client_label' => 'Guest:',
    'room_label' => 'Room:',
    'arrival_label' => 'Arrival:',
    'departure_label' => 'Departure:',
    'initial_status' => 'Initial Status:',
    'nights' => 'Nights:',
    'total_price' => 'Total Price:',
    'initial_note' => 'Initial Note:',

    // Arrival event
    'arrival_badge' => 'Arrival',
    'guest_marked_arrived' => 'Guest Marked as Arrived',
    'guest_arrived_text' => 'The guest has arrived at the hotel and has been checked in.',
    'actual_arrival_time' => 'Actual Arrival Time:',

    // Departure event
    'departure_badge' => 'Departure',
    'guest_marked_departed' => 'Guest Marked as Departed',
    'guest_departed_text' => 'The guest has left the hotel. The stay is complete.',
    'actual_departure_time' => 'Actual Departure Time:',

    // Cancellation event
    'cancellation_badge' => 'Cancellation',
    'reservation_cancelled' => 'Reservation Cancelled',
    'cancellation_reason' => 'Cancellation Reason:',
    'cancellation_text' => 'The reservation has been cancelled. All associated payments have been refunded.',

    // Payment event
    'payment_badge' => 'Payment #:id',
    'payment_made' => 'Payment Made',
    'amount' => 'Amount:',
    'method' => 'Method:',
    'reference' => 'Reference:',
    'payment_status' => 'Status:',

    // No history
    'no_additional_history' => 'No additional changes have been recorded for this reservation. The complete history includes automatic events (creation, arrival, departure, payments).',

    // About section
    'about_history' => 'About this History',
    'about_history_text' => 'This history is generated automatically. All changes are recorded with timestamp and author.',
    'print' => 'Print',

    // JS alerts
    'print_title' => 'Print History?',
    'print_text' => 'The complete history will be printed in landscape format.',
    'print_confirm' => 'Print',
    'print_cancel' => 'Cancel',
];
