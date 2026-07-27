<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Restaurant Navigation
    |--------------------------------------------------------------------------
    */
    'nav' => [
        'menus' => 'Menus',
        'orders' => 'Orders',
        'categories' => 'Categories',
        'sales' => 'Sales',
    ],

    /*
    |--------------------------------------------------------------------------
    | Index - Menu Listing
    |--------------------------------------------------------------------------
    */
    'index' => [
        'page_title' => 'Restaurant - Menus',
        'header' => 'Menu Management',
        'header_desc' => 'Configure and manage your restaurant menus',
        'new_menu' => 'New Menu',
        'all_categories' => 'All categories',
        'search_placeholder' => 'Search for a dish...',
        'cart' => 'Cart',
        'add' => 'Add',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'empty' => 'Menu empty',
        'empty_desc' => 'No menu items have been added yet.',
        'add_first' => 'Add the first menu item',
        'no_category' => 'No category',
        'availability' => 'Dish availability',
        'confirm_title' => 'Are you sure?',
        'confirm_text' => 'This menu will be deleted!',
        'confirm_yes' => 'Yes, delete!',
        'confirm_cancel' => 'Cancel',
        'delete_error' => 'Error during deletion',
        'monday' => 'M',
        'tuesday' => 'T',
        'wednesday' => 'W',
        'thursday' => 'T',
        'friday' => 'F',
        'saturday' => 'S',
        'sunday' => 'S',
    ],

    /*
    |--------------------------------------------------------------------------
    | Create - Add New Menu
    |--------------------------------------------------------------------------
    */
    'create' => [
        'page_title' => 'Restaurant - New Menu',
        'header' => 'Add a Menu Item',
        'back' => 'Back',
        'name' => 'Menu name',
        'category' => 'Category',
        'category_select' => 'Select a category',
        'price' => 'Price (FCFA)',
        'image' => 'Menu image',
        'image_hint' => 'Accepted formats: JPG, PNG, GIF. Max: 2MB',
        'description' => 'Description',
        'description_hint' => 'Describe the ingredients, preparation, etc.',
        'availability_days' => 'Availability days',
        'select_all' => 'All',
        'select_none' => 'None',
        'availability_hint' => 'Select the days when this menu is available. Unchecked days (like Friday by default here) will not be shown to customers for ordering on that day.',
        'available_now' => 'Available immediately',
        'preview' => 'Preview',
        'remove' => 'Remove',
        'save' => 'Save menu',
        'monday' => 'Mon',
        'tuesday' => 'Tue',
        'wednesday' => 'Wed',
        'thursday' => 'Thu',
        'friday' => 'Fri',
        'saturday' => 'Sat',
        'sunday' => 'Sun',
    ],

    /*
    |--------------------------------------------------------------------------
    | Edit - Update Menu
    |--------------------------------------------------------------------------
    */
    'edit' => [
        'page_title' => 'Restaurant - Edit Menu',
        'header' => 'Edit Menu Item',
        'back' => 'Back',
        'name' => 'Menu name',
        'category' => 'Category',
        'category_select' => 'Select a category',
        'price' => 'Price (FCFA)',
        'image' => 'Menu image',
        'image_hint' => 'Leave empty to keep the current image.',
        'description' => 'Description',
        'description_hint' => 'Describe the ingredients, preparation, etc.',
        'availability_days' => 'Availability days',
        'available_now' => 'Available immediately',
        'preview_original' => 'Original preview',
        'delete_image' => 'Delete current image',
        'cancel' => 'Cancel',
        'save' => 'Save changes',
        'monday' => 'Mon',
        'tuesday' => 'Tue',
        'wednesday' => 'Wed',
        'thursday' => 'Thu',
        'friday' => 'Fri',
        'saturday' => 'Sat',
        'sunday' => 'Sun',
    ],

    /*
    |--------------------------------------------------------------------------
    | Orders - Order Management
    |--------------------------------------------------------------------------
    */
    'orders' => [
        'page_title' => 'Restaurant - Orders',
        'header' => 'Order Management',
        'header_desc' => 'Track and process customer orders in real time',
        'new_order' => 'New Order',
        'qr_title' => 'Digital Restaurant Menu',
        'qr_desc' => 'This QR Code allows customers to scan and order directly via their mobile or a table tablet.',
        'download' => 'Download',
        'kpi_revenue' => 'TODAY\'S REVENUE',
        'kpi_orders' => 'ORDERS (TODAY)',
        'kpi_pending' => 'PENDING / PREP.',
        'kpi_ready' => 'READY',
        'kpi_delivered' => 'DELIVERED / PAID',
        'filter_all' => 'All statuses',
        'filter_pending' => 'Pending',
        'filter_validated' => 'Validated',
        'filter_preparing' => 'Preparing',
        'filter_ready' => 'Ready',
        'filter_delivered' => 'Delivered',
        'filter_paid' => 'Paid',
        'filter_cancelled' => 'Cancelled',
        'filter_btn' => 'Filter',
        'th_id' => 'ID',
        'th_client' => 'Customer',
        'th_room' => 'Room',
        'th_menus' => 'Menus',
        'th_total' => 'Total',
        'th_status' => 'Status',
        'th_date' => 'Date',
        'th_actions' => 'Actions',
        'no_orders' => 'No orders',
        'details_title' => 'Order details',
        'close' => 'Close',
        'print_ticket' => 'Print ticket',
        'view' => 'View',
        'validate' => 'Validate order',
        'start_prep' => 'Start preparation',
        'mark_ready' => 'Mark as ready',
        'mark_delivered' => 'Mark as delivered',
        'collect' => 'Collect payment',
        'print_invoice' => 'Print invoice',
        'room_charge' => 'Room charge',
        'error' => 'Unknown error',
    ],

    /*
    |--------------------------------------------------------------------------
    | Invoice
    |--------------------------------------------------------------------------
    */
    'invoice' => [
        'invoice' => 'INVOICE',
        'client' => 'Customer',
        'name' => 'Name',
        'room' => 'Room',
        'order' => 'Order',
        'reference' => 'Reference',
        'date' => 'Date',
        'payment' => 'Payment',
        'method_cash' => 'Cash',
        'method_card' => 'Credit Card',
        'method_mobile' => 'Mobile Money',
        'method_fedapay' => 'Fedapay',
        'method_transfer' => 'Transfer',
        'method_check' => 'Check',
        'method_room' => 'Room bill',
        'method_none' => 'Not specified',
        'designation' => 'Description',
        'qty' => 'Qty',
        'unit_price' => 'Unit price',
        'total' => 'Total',
        'total_due' => 'TOTAL DUE',
        'notes' => 'Notes:',
        'thank_you' => 'Thank you for your order',
        'printed_on' => 'Printed on',
        'close' => 'Close',
        'prompt_name' => 'Please enter the customer name for this invoice (Leave empty to skip):',
    ],

    /*
    |--------------------------------------------------------------------------
    | Sales - Sales Tracking
    |--------------------------------------------------------------------------
    */
    'sales' => [
        'page_title' => 'Restaurant - Sales Tracking',
        'header' => 'Sales Tracking',
        'header_desc' => 'Analyze restaurant performance and revenue',
        'print_report' => 'Print report',
        'kpi_total_revenue' => 'TOTAL REVENUE',
        'kpi_today' => 'TODAY\'S REVENUE',
        'kpi_month' => 'MONTHLY REVENUE',
        'kpi_orders' => 'TOTAL ORDERS',
        'chart_revenue' => 'Revenue · Last 7 days',
        'chart_category' => 'By category',
        'chart_top10' => 'Top 10 · Best-selling dishes',
        'th_dish' => 'Dish name',
        'th_category' => 'Category',
        'th_sales' => 'Sales',
        'th_revenue' => 'Revenue',
        'unknown_item' => 'Unknown item',
        'chart_monthly' => 'Monthly trend (12 months)',
        'monthly_ca' => 'Monthly revenue',
        'dataset_revenue' => 'Revenue (CFA)',
        'dataset_orders' => 'Orders',
    ],

    /*
    |--------------------------------------------------------------------------
    | Categories
    |--------------------------------------------------------------------------
    */
    'categories' => [
        'page_title' => 'Restaurant - Categories',
        'header' => 'Category Management',
        'header_desc' => 'Organize your menus by dish types',
        'new' => 'New Category',
        'th_name' => 'Name',
        'th_slug' => 'Slug',
        'th_actions' => 'Actions',
        'empty' => 'No categories defined.',
        'create_title' => 'New Category',
        'name_label' => 'Category name',
        'name_placeholder' => 'e.g.: Starters, Drinks...',
        'cancel' => 'Cancel',
        'save' => 'Save',
        'edit_title' => 'Edit Category',
        'update' => 'Update',
        'delete_confirm' => 'Confirmation',
        'delete_yes' => 'Yes, delete',
        'delete_cancel' => 'Cancel',
        'delete_message' => 'Do you really want to delete the category',
        'delete_warning' => 'Warning: This category contains',
        'delete_plat' => 'dish(es)',
    ],

    /*
    |--------------------------------------------------------------------------
    | Modal - New Order Modal
    |--------------------------------------------------------------------------
    */
    'modal' => [
        'title' => 'New Order',
        'subtitle' => 'Restaurant · Admin Interface',
        'step_dishes' => 'Dishes',
        'step_customer' => 'Customer',
        'step_confirm' => 'Confirmation',
        'cart_detail' => 'Order details',
        'cart_desc' => 'Here are the selected dishes. Adjust quantities or remove an item before continuing.',
        'cart_empty' => 'Your cart is empty.',
        'cart_empty_hint' => 'Add dishes from the restaurant page then come back here.',
        'clear_cart' => 'Clear cart',
        'total' => 'Total:',
        'step2_title' => 'Identification & Service Location',
        'step2_desc' => 'Specify where the customer will be served and identify them.',
        'location_label' => 'Service location',
        'location_room' => 'Room Service',
        'location_table' => 'Dine-in',
        'table_number' => 'Table Number',
        'customer_type' => 'Customer type',
        'customer_resident' => 'Resident Guest',
        'customer_outdoor' => 'External / New Customer',
        'search_customer' => 'Name, phone, room number...',
        'no_resident' => 'No active resident guests',
        'no_resident_hint' => 'All registered guests are currently checked out or have no active stay.',
        'change' => 'Change',
        'room' => 'Room',
        'status' => 'Status',
        'registered_guest' => 'Registered guest',
        'name_ref' => 'Name or Reference (Optional)',
        'step3_title' => 'Confirmation & Billing',
        'step3_desc' => 'Review the order, add notes and choose the billing method.',
        'room_billing' => 'Room billing active',
        'payment_label' => 'Choose payment method',
        'pay_cash' => 'Cash',
        'pay_card' => 'Card',
        'pay_mobile' => 'Mobile M.',
        'pay_transfer' => 'Transfer',
        'pay_fedapay' => 'Fedapay',
        'pay_check' => 'Check',
        'notes_chef' => 'Notes for the chef',
        'preview' => 'Preview & Details',
        'info_client' => 'Guest & Location Info',
        'chef_prefs' => 'Chef Preferences',
        'selected_dishes' => 'Selected dishes',
        'prev' => 'Previous',
        'next' => 'Next',
        'cancel' => 'Cancel',
        'submit' => 'Place order',
        'error_add_dish' => 'Please add at least one dish.',
        'error_table' => 'Please enter the table number.',
        'error_customer' => 'Please select a customer.',
        'clear_cart_title' => 'Clear the cart?',
        'clear_cart_text' => 'All selected dishes will be removed.',
        'clear_cart_yes' => 'Yes, clear',
        'clear_cart_cancel' => 'Cancel',
        'error_loading' => 'Loading error.',
        'cancel_order' => 'Cancel the order?',
        'cancel_yes' => 'Yes, cancel',
        'cancel_no' => 'No',
        'cancelled' => 'Cancelled!',
        'error_action' => 'Action impossible.',
        'error_title' => 'Error',
    ],

    /*
    |--------------------------------------------------------------------------
    | Details - Order Details
    |--------------------------------------------------------------------------
    */
    'details' => [
        'customer_info' => 'Customer information',
        'name' => 'Name:',
        'phone' => 'Phone:',
        'room' => 'Room:',
        'order_date' => 'Order date:',
        'order_summary' => 'Order summary',
        'number' => 'Number:',
        'status' => 'Status:',
        'total' => 'Total:',
        'payment' => 'Payment:',
        'method_cash' => 'Cash',
        'method_card' => 'Credit card',
        'method_room' => 'Room charge',
        'method_online' => 'Online',
        'items_title' => 'Item details',
        'th_menu' => 'Menu',
        'th_unit_price' => 'Unit price',
        'th_quantity' => 'Quantity',
        'th_total' => 'Total',
        'subtotal' => 'Subtotal:',
        'tax' => 'Tax',
        'discount' => 'Discount:',
        'notes_title' => 'Notes',
        'history_title' => 'History',
        'order_created' => 'Order created',
        'last_modified' => 'Last modified',
    ],

];
