<?php

return [
    // ─── ROUTES (web.php fallback) ──────────────────────
    'page_not_found' => 'Page not found.',
    'page_not_found_login' => 'Page not found. Please log in.',

    // ─── AUTH ───────────────────────────────────────────
    'login_welcome' => 'Welcome',
    'login_invalid' => 'Invalid credentials. Check your email and password.',
    'register_success' => 'Your account has been created. You can now log in.',
    'logout_success' => 'Logged out successfully. Goodbye',

    // ─── MIDDLEWARE ─────────────────────────────────────
    'middleware_login_required' => 'Please log in.',
    'middleware_unauthorized' => 'You are not authorized to access this page.',
    'middleware_admin_no_delete' => 'Administrators cannot delete reservations.',
    'middleware_admin_no_cancel' => 'Administrators cannot cancel reservations.',
    'middleware_housekeeping_restricted' => 'Access restricted to housekeeping staff.',
    'middleware_housekeeping_readonly' => 'Housekeeping staff have read-only access.',
    'middleware_receptionist_unauthorized' => 'Action not authorized for reception staff.',
    'middleware_receptionist_no_cancel' => 'Reservation cancellation is restricted to administrators.',
    'middleware_receptionist_no_delete' => 'Reservation deletion is restricted to administrators.',
    'middleware_receptionist_no_delete_customer' => 'Customer deletion is restricted to administrators.',
    'middleware_receptionist_no_delete_room' => 'Room deletion is restricted to administrators.',
    'middleware_receptionist_no_cancel_payment' => 'Payment cancellation is restricted to administrators.',
    'middleware_receptionist_no_assign_housekeeping' => 'Housekeeping assignment is restricted to administrators.',
    'middleware_receptionist_no_start_cleaning' => 'Starting cleaning is restricted to housekeeping staff.',
    'middleware_receptionist_action_denied' => 'Action not authorized for reception staff.',
    'middleware_receptionist_needs_auth' => 'This action requires special authorization.',
    'middleware_plan_missing' => 'The ":label" module is not included in your :plan plan. Upgrade to activate it.',

    // ─── TRANSACTION ────────────────────────────────────
    'transaction_edit_closed' => 'Cannot edit a completed or cancelled reservation.',
    'transaction_updated' => 'Reservation #:id updated successfully.',
    'transaction_update_error' => 'Internal error while updating.',
    'transaction_deleted' => 'Reservation #:id (:name) permanently deleted.',
    'transaction_delete_error' => 'Error while deleting.',
    'transaction_status_updated' => 'Status updated: :status.',
    'transaction_status_error' => 'Internal error.',
    'transaction_cancelled' => 'Reservation cancelled successfully.',
    'transaction_no_show' => 'Reservation marked as No Show.',
    'transaction_restored' => 'Reservation restored successfully.',
    'transaction_extended' => 'Stay extended by :nights night(s). New checkout: :date.',
    'transaction_extend_error' => 'Error while extending stay.',
    'transaction_checkin_done' => 'Guest checked in. Room :room is now occupied.',
    'transaction_checkout_done' => 'Check-out completed successfully.',
    'transaction_checkout_cleaning' => 'Check-out recorded - Room to clean.',
    'transaction_checkout_cleaning_detail' => 'Guest departed. Room marked "TO CLEAN".',
    'transaction_late_checkout_created' => 'Late checkout recorded and payment created.',
    'transaction_late_checkout_paid' => 'Late checkout recorded and payment collected.',
    'transaction_early_checkout' => 'Early checkout recorded.',

    // ─── TRANSACTION ROOM RESERVATION ───────────────────
    'reservation_customer_updated' => 'Customer info updated: :name',
    'reservation_login_required' => 'You must be logged in to create a customer',
    'reservation_customer_created' => 'New customer created by :agent: :name',
    'reservation_system_error' => 'System error: No user found in database. Please contact the administrator.',
    'reservation_date_arrival_required' => 'Arrival date is required',
    'reservation_date_departure_required' => 'Departure date is required',
    'reservation_date_departure_after' => 'Departure date must be after arrival date',
    'reservation_capacity_exceeded' => 'Number of guests cannot exceed room capacity (:capacity)',
    'reservation_deposit_exceeded' => 'Deposit cannot exceed total price',
    'reservation_duplicate' => 'This reservation already exists. No duplicate created.',
    'reservation_room_unavailable' => 'This room is no longer available for the selected dates. Please choose other dates or another room.',
    'reservation_error' => 'Error while creating reservation.',
    'reservation_db_error' => 'Database error while creating reservation.',
    'reservation_column_missing' => 'Error: Column :column does not exist in the table.',
    'reservation_field_required' => 'Error: Field :field is required.',
    'reservation_customer_not_found' => 'No customer found with this email',

    // ─── CHECK-IN ───────────────────────────────────────
    'checkin_not_allowed' => 'This reservation cannot be checked in. Status: :status',
    'checkin_room_error' => 'Selected room does not allow check-in.',
    'checkin_room_unavailable' => 'Selected room is not available for this period',
    'checkin_price_change' => 'Price change detected. Old: :old CFA, New: :new CFA. Please confirm.',
    'checkin_error' => 'Error during check-in.',
    'checkin_direct_room_unavailable' => 'Room is not available. Please use normal check-in to select another room.',
    'checkin_direct_error' => 'Error during quick check-in.',
    'checkin_direct_error2' => 'Error during direct check-in.',

    // ─── PROFILE ────────────────────────────────────────
    'profile_invalid_phone' => 'Phone number is not valid (digits and + - ( ) spaces only).',
    'profile_email_taken' => 'This email address is already used by another account.',
    'profile_updated' => 'Information updated successfully.',
    'profile_wrong_password' => 'Current password is incorrect.',
    'profile_password_changed' => 'Password changed successfully.',
    'profile_avatar_error' => 'Photo upload failed. Try with a JPG/PNG image under 2 MB.',
    'profile_avatar_updated' => 'Profile photo updated.',
    'profile_updated_success' => 'Profile updated successfully!',

    'user_no_activity' => 'No activity',

    // ─── USER ───────────────────────────────────────────
    'user_super_reserved' => 'Reserved for Super Admin.',
    'user_created' => 'User :name created',
    'user_own_profile_only' => 'You can only view your own profile.',
    'user_delete_restricted' => 'Only Super Admins and Admins can delete users.',
    'user_cannot_delete_self' => 'You cannot delete your own account.',
    'user_has_active_reservations' => 'This customer has active reservations. Cannot delete.',
    'user_deleted' => 'User :name deleted successfully!',
    'user_delete_error' => 'Cannot delete :name.',
    'user_super_required' => 'Unauthorized: Super Admin privileges required.',
    'user_password_reset' => 'Password reset successfully.',
    'user_password_reset_error' => 'Error during password reset.',
    'user_cannot_disable_self' => 'You cannot disable your own account.',
    'user_status_changed' => 'User :label successfully.',
    'user_status_error' => 'Error while changing status.',

    // ─── PAYMENT ────────────────────────────────────────
    'payment_already_paid' => 'This transaction is already fully paid.',
    'payment_cancelled_no_show' => 'Cannot process payment on a cancelled or no-show transaction.',
    'payment_refund' => 'Early checkout refund',

    // ─── BILLING ────────────────────────────────────────
    'billing_suspended' => 'Your account has been suspended by the platform. Online payment is unavailable.',
    'billing_not_configured' => 'Online payment is not yet configured. Contact the platform.',
    'billing_error' => 'Unable to start payment right now. Please try again later.',
    'billing_session_not_found' => 'Payment session not found. If you were charged, contact us.',
    'billing_payment_failed' => 'Payment did not go through. No amount was charged.',
    'billing_confirmed' => 'Payment confirmed! Your subscription is active until :date.',

    // ─── HOTEL SETTINGS ─────────────────────────────────
    'hotel_settings_updated' => 'Your property information has been updated.',
    'hotel_settings_no_hotel' => 'No property associated with this account.',

    // ─── ONBOARDING ─────────────────────────────────────
    'onboarding_complete' => 'Your site is set up! Welcome to your dashboard.',

    // ─── FACILITY ───────────────────────────────────────
    'facility_created' => 'Equipment added.',
    'facility_updated' => 'Equipment updated.',
    'facility_deleted' => 'Equipment deleted.',

    // ─── TRANSACTION EXTRA ──────────────────────────────
    'extra_created' => 'Extra added successfully',
    'extra_created_detail' => 'Extra ":description" added to the invoice.',
    'extra_deleted' => 'Extra deleted',
    'extra_deleted_detail' => 'Extra removed from the invoice.',

    // ─── RESTAURANT CATEGORY ────────────────────────────
    'restaurant_category_created' => 'Category added successfully.',
    'restaurant_category_updated' => 'Category updated successfully.',
    'restaurant_category_deleted' => 'Category deleted successfully.',

    // ─── RESTAURANT ─────────────────────────────────────
    'restaurant_menu_created' => 'Menu added successfully!',
    'restaurant_menu_updated' => 'Menu updated successfully!',
    'restaurant_room_number_missing' => 'Room number is missing.',
    'restaurant_no_active_guest' => 'No active guest in room :room. Please verify the number.',

    // ─── IMAGE ──────────────────────────────────────────
    'image_created' => 'Image added!',

    // ─── ROOM ───────────────────────────────────────────
    'room_cannot_mark_dirty_occupied' => 'Cannot mark an occupied room as dirty. The guest is still present.',
    'room_cannot_mark_dirty_maintenance' => 'Cannot mark a maintenance room as dirty. Complete maintenance first.',
    'room_marked_dirty' => 'Room :number marked as dirty successfully.',

    // ─── CASHIER SESSION ────────────────────────────────
    'session_load_error' => 'Error loading sessions.',
    'session_already_active' => 'You already have an active session. Please close it before starting a new one.',
    'session_no_permission' => 'You don\'t have the required permissions to start a session.',
    'session_active_id' => 'You already have an active session. ID: #:id',
    'session_started' => 'Session started successfully! ID: #:id at :time',
    'session_no_access' => 'You don\'t have access to this session.',
    'session_unauthorized' => 'Unauthorized action.',
    'session_closed_error' => 'Closed sessions cannot be modified.',
    'session_updated' => 'Session updated successfully.',
    'session_not_active' => 'This session is not active.',
    'session_closed' => 'Session #:id closed.',
    'session_close_error' => 'Error closing session.',
    'session_no_active' => 'No active session. Please start a session.',
    'session_admin_only' => 'Access restricted to administrators.',
    'session_access_denied' => 'Access denied.',
    'session_must_be_closed' => 'The session must be closed to generate the report.',

    // ─── RECEPTIONIST SESSION ───────────────────────────
    'receptionist_session_unauthorized' => 'Access denied',

    // ─── REGISTER HOTEL ─────────────────────────────────
    'register_invalid_logo' => 'Logo must be a JPG, PNG, WEBP or SVG image.',
    'register_logo_too_heavy' => 'Logo is too large (4 MB max). Reduce its size and try again.',
    'register_logo_unreadable' => 'Logo could not be read. Try with a JPG or PNG image.',
    'register_existing_account' => 'Existing account detected. Plan updated to :plan.',
    'register_trial_started' => 'Welcome! Your :days-day free trial has started.',

    // ─── HOUSEKEEPING ───────────────────────────────────
    'housekeeping_load_error' => 'Error while loading.',
    'housekeeping_room_cleaned' => 'Room cleaned successfully.',
    'housekeeping_cleaning_started' => 'Cleaning started.',
    'housekeeping_inspection_requested' => 'Inspection requested.',
    'housekeeping_task_completed' => 'Task completed.',
    'housekeeping_assignment_saved' => 'Assignment saved.',
    'housekeeping_status_updated' => 'Status updated.',
    'housekeeping_report_generated' => 'Report generated.',
    'housekeeping_stats_loaded' => 'Statistics loaded.',
    'housekeeping_maintenance_requested' => 'Maintenance requested.',
    'housekeeping_room_available' => 'Room returned to service.',
    'housekeeping_error' => 'An error occurred.',
    'housekeeping_not_found' => 'Item not found.',
    'housekeeping_unauthorized' => 'Unauthorized action.',

    // ─── REPORT ─────────────────────────────────────────
    'report_today' => 'Today',
    'report_yesterday' => 'Yesterday',
    'report_this_week' => 'This week',
    'report_this_month' => 'This month',
    'report_this_quarter' => 'This quarter',
    'report_this_year' => 'This year',
];
