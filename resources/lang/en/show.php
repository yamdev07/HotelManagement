<?php

return [
    // Page title
    'page_title' => 'Booking Details #:id',

    // Breadcrumb
    'reservations' => 'Bookings',

    // Header
    'reservation_title' => 'Booking #:id',
    'check_in_out_badge' => 'Check-in 12h | Check-out 12h (grace period 14h)',

    // Status select options
    'status_reservation' => '📅 Reserved',
    'status_active' => '🏨 In Hotel',
    'status_completed' => '✅ Completed',
    'status_cancelled' => '❌ Cancelled',
    'status_no_show' => '👤 No Show',

    // Buttons
    'back' => 'Back',
    'arrival' => 'Check-in',
    'arrival_at_12h' => 'Check-in at 12h',
    'departure_largesse' => 'Check-out (grace)',
    'departure_late_checkout' => 'Check-out (late checkout)',
    'departure_pending_payment' => 'Check-out (payment pending)',
    'departure_blocked' => 'Check-out blocked',
    'departure_impossible' => 'Check-out impossible',
    'departure_at' => 'Check-out at :time',
    'departure_at_12h' => 'Check-out at 12h',
    'extend' => 'Extend',
    'extend_one_night' => 'Extend one night',
    'edit' => 'Edit',
    'payment' => 'Payment',
    'cancel' => 'Cancel',
    'view_profile' => 'View profile',
    'history' => 'History',
    'mark_as_paid' => 'Mark as paid',
    'add_payment' => 'Add payment',
    'create_payment' => 'Create payment',
    'invoice' => 'Invoice',
    'restore' => 'Restore',
    'receipt' => 'Receipt',
    'close' => 'Close',
    'processing' => 'Processing...',
    'confirm_late_checkout' => 'Confirm late checkout',
    'confirm_early_checkout' => 'Confirm early checkout',
    'confirm_cancellation' => 'Confirm cancellation',
    'early_checkout' => 'Early checkout',
    'late_checkout' => 'Late checkout',

    // Status alerts
    'reservation_heading' => '📅 RESERVED',
    'expected_arrival' => 'Expected arrival: :date at 12:00 PM',
    'active_heading' => '🏨 IN HOTEL',
    'expected_departure' => 'Expected departure:',
    'completed_heading' => '✅ STAY COMPLETED',
    'client_left_on' => 'Guest checked out on :date',
    'cancelled_heading' => '❌ CANCELLED',
    'cancelled_on' => 'Cancelled on :date',
    'cancel_reason' => 'Reason: :reason',
    'no_show_heading' => '👤 NO SHOW',
    'no_show_text' => 'Guest did not show up',

    // Late / Early checkout badges
    'late_checkout_label' => 'Late checkout: :time',
    'early_checkout_departure' => 'Early checkout - Early departure',
    'refunded' => 'Refunded: :amount FCFA',
    'early_checkout_abbrev' => 'Early departure',
    'in_late_checkout' => '(incl. late checkout)',
    'with_early_checkout' => '(early checkout)',

    // Tooltips
    'late_checkout_tooltip' => 'Payment pending - Click "Mark as paid" in the payment list',
    'late_fee_unpaid_tooltip' => 'Late checkout fee of :amount FCFA unpaid',
    'departure_after_20h_tooltip' => 'Departure after 8 PM - Extension required',
    'departure_at_time_tooltip' => 'Scheduled departure at :time',

    // Customer card
    'customer_info' => 'Guest Information',
    'email_not_set' => 'Email not provided',
    'phone' => 'Phone',
    'nic_id' => 'NIC/ID',
    'not_specified' => 'Not provided',

    // Stay card
    'stay_info' => 'Stay Information',
    'room' => 'Room',
    'type_not_specified' => 'Type not specified',
    'stay_duration' => 'Stay duration',
    'night' => 'night',
    'nights' => 'night(s)',
    'arrival_date' => 'Arrival',
    'departure_date' => 'Departure',
    'room_status' => 'Room status',
    'reservation_status' => 'Booking status',

    // Payments card
    'payments' => 'Payments',
    'settled' => 'Settled',
    'pending' => 'Pending',
    'no_debt' => 'No balance due',
    'initial_total' => 'Initial total',
    'late_supplement' => 'Late supplement',
    'refund' => 'Refund',
    'final_total' => 'Final total',
    'paid' => 'Paid',
    'remaining' => 'Remaining',
    'no_payment' => 'No payment',
    'no_payment_text' => 'No payment has been made for this booking.',

    // Restaurant consumption
    'restaurant_consumptions' => 'Restaurant Orders (Room Charge)',
    'id' => 'ID',
    'date' => 'Date',
    'status' => 'Status',
    'amount' => 'Amount',
    'delivered_on_bill' => 'Delivered (On bill)',
    'not_invoiced' => '(Not invoiced)',
    'total_added_to_bill' => 'Total added to bill:',

    // Payment history
    'payment_history' => 'Payment history',
    'payment_number' => 'Payment #:id',
    'refund_badge' => 'Refund',
    'note' => 'Note:',

    // Late checkout alerts
    'late_checkout_registered' => 'Late checkout registered',
    'late_checkout_pending_info' => 'Payment pending - Click "Mark as paid"',
    'no_payment_created' => 'No payment created',
    'early_checkout_registered' => 'Early checkout registered',
    'early_checkout_refund_text' => 'Early departure - Refund of :amount FCFA',

    // Quick actions card
    'quick_actions' => 'Quick actions',

    // Details card
    'details' => 'Details',
    'number_of_guests' => 'Number of guests',
    'person' => 'guest',
    'persons' => 'guest(s)',
    'price_per_night' => 'Price per night',
    'late_checkout_supplement' => 'Late checkout supplement',
    'early_checkout_refund_label' => 'Early checkout refund',
    'departure_time' => 'Departure time',
    'created_on' => 'Created on',
    'created_by' => 'Created by',
    'last_modified' => 'Last modified',
    'notes' => 'Notes',
    'early_checkout_reason' => 'Early checkout reason',
    'departure_notes' => 'Departure notes',

    // Statistics card
    'statistics' => 'Statistics',
    'nights_label' => 'Nights',
    'total' => 'Total',
    'remaining_to_pay' => 'Balance due',
    'remaining_with_late' => 'incl. :amount CFA late checkout',

    // Early checkout modal
    'early_checkout_title' => 'Early checkout - Early departure',
    'stay_summary' => 'Stay summary',
    'client_label' => 'Guest: :name',
    'room_label' => 'Room: :number',
    'planned_nights' => 'Planned nights',
    'actual_nights' => 'Actual nights',
    'unused_nights' => 'Unused nights',
    'total_paid' => 'Total paid',
    'potential_refund_calc' => 'Potential refund calculation:',
    'new_total' => 'New total',
    'already_paid' => 'Already paid',
    'max_refund' => 'Maximum refund',
    'refund_policy' => 'Refund policy',
    'full_refund' => 'Full refund (:amount FCFA)',
    'partial_refund' => 'Partial refund',
    'no_refund' => 'No refund',
    'refund_amount' => 'Refund amount (FCFA)',
    'refund_method' => 'Refund method',
    'cash' => 'Cash',
    'credit_card' => 'Credit card',
    'mobile_money' => 'Mobile Money',
    'bank_transfer' => 'Bank transfer',
    'early_departure_reason' => 'Early departure reason',
    'early_departure_placeholder' => 'E.g.: Emergency, Change of plans, Dissatisfaction...',

    // Late checkout modal
    'late_checkout_title' => 'Late checkout - Room :room',
    'normal_departure' => 'Normal departure:',
    'new_departure_time' => 'New departure time',
    'choose_time' => 'Choose a time',
    'payment_method' => 'Payment method',
    'amount_suggestions' => 'Amount suggestions',
    'free' => 'Free',
    'supplement_label' => 'Supplement (FCFA)',
    'free_label' => 'FREE',
    'night_price' => 'Night price:',
    'notes_optional' => 'Notes (optional)',
    'late_checkout_reason_placeholder' => 'Reason for late checkout...',
    'transfer' => 'Bank transfer',

    // Cancel modal
    'cancel_title' => 'Cancel booking',
    'cancel_confirm_text' => 'Are you sure you want to cancel this booking?',
    'cancel_reason_label' => 'Reason (optional)',
    'cancel_reason_placeholder' => 'Why cancel?',

    // Restore confirm
    'restore_confirm' => 'Restore this booking?',

    // JavaScript confirms
    'confirm_payment' => 'Confirm payment for this supplement?',
    'confirm_cancel_status' => '⚠️ Are you sure you want to cancel this booking?',
    'confirm_no_show_status' => '⚠️ Mark as "No Show"?',

    // Toast messages
    'payment_confirmed_title' => 'Payment confirmed!',
    'payment_confirmed_message' => 'The payment has been successfully marked as paid.',
    'error_title' => 'Error',
    'unknown_error' => 'Unknown error',
    'communication_error' => 'Communication error',
];
