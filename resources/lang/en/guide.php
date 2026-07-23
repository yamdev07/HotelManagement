<?php

return [
    'page_title' => 'User Guide · :app',
    'nav_site' => 'Site',
    'nav_login' => 'Login',
    'nav_trial' => 'Free Trial',
    'search_placeholder' => 'Search the guide…',
    'toc_title' => 'Contents',
    'toc_empty' => 'No results for this search.',
    'support_link' => 'Support & help',
    'hero_chip' => 'User Guide',
    'hero_title' => 'Get started with :app in minutes',
    'hero_desc' => 'This guide walks you through everything, from your first login to daily property management.',

    // Sections
    'sec_start' => 'Getting Started',
    'sec_start_sub' => 'After signing up',
    'sec_start_desc' => 'Once your trial (or payment) is confirmed, you receive your credentials by email — check your spam folder. Log in at /login with the email and password you received.',
    'sec_process' => 'The Stay Lifecycle',
    'sec_process_sub' => 'The business process day to day',
    'sec_process_desc' => 'From reservation to guest checkout and room cleaning — here is the complete workflow managed by the application.',
    'sec_brand' => 'Customize Your Property',
    'sec_brand_sub' => 'Colors, logo & website',
    'sec_brand_desc' => 'On first login, a wizard helps you set your display name, logo and colors. You can return to it anytime from "My Property".',
    'sec_rooms' => 'Set Up Your Rooms',
    'sec_rooms_sub' => 'Types & rooms',
    'sec_rooms_desc' => 'First create your room types (Standard, Suite…), then add rooms with their number, capacity and price.',
    'sec_bookings' => 'Reservations & Check-in',
    'sec_bookings_sub' => 'The core of the business',
    'sec_bookings_desc' => 'Record a reservation, check in the guest on arrival and check out on departure, in just a few clicks.',
    'sec_cashier' => 'Cash Register',
    'sec_cashier_sub' => 'Payments',
    'sec_cashier_desc' => 'Open your register at shift start, collect payments, then close it at end of day for reconciliation.',
    'sec_housekeeping' => 'Housekeeping',
    'sec_housekeeping_sub' => 'Cleaning & statuses',
    'sec_housekeeping_desc' => 'Track room states (dirty, cleaning, clean) and assign tasks to your team. (Pro & Business plans)',
    'sec_restaurant' => 'Restaurant',
    'sec_restaurant_sub' => 'Orders & service',
    'sec_restaurant_desc' => 'Manage your menu, orders and room service from the Restaurant module. (Pro & Business plans)',
    'sec_site' => 'Your Website',
    'sec_site_sub' => 'Showcase & online bookings',
    'sec_site_desc' => 'Each property gets a branded mini-site to showcase rooms and accept online reservations.',
    'sec_reports' => 'Reports',
    'sec_reports_sub' => 'Analytics',
    'sec_reports_desc' => 'Track occupancy, revenue and property performance. (Pro & Business plans)',
    'sec_staff' => 'Your Staff',
    'sec_staff_sub' => 'Accounts & roles',
    'sec_staff_desc' => 'Create accounts for your receptionists, housekeeping team, etc., each with their own permissions.',
    'sec_billing' => 'Subscription & Billing',
    'sec_billing_sub' => 'Manage your plan',
    'sec_billing_desc' => 'From "My Subscription", view your due date, change plans and renew online (Mobile Money & card).',

    // Start section
    'start_intro' => 'After your free trial or payment is confirmed:',
    'start_step1' => '<b>Receive your credentials</b> by email (check your spam folder and mark the message as "not spam").',
    'start_step2' => '<b>Log in</b> on the login page with your email and password.',
    'start_step3' => '<b>Customize your site</b> via the welcome wizard (name, logo, colors).',
    'start_step4' => '<b>Add your rooms</b> and start recording reservations.',
    'start_tip' => 'Change your password after first login from <b>Profile → Change Password</b>.',

    // Process section
    'process_intro' => 'Here is how the application supports the complete stay lifecycle, from reservation to room cleaning.',
    'process_step1' => '<b>Reservation</b> · the guest books, either <b>online</b> from your website (showcase) or <b>at the reception</b>. You record the guest (guest profile) and choose the room and dates. The room moves to <b>"reserved"</b>.',
    'process_step2' => '<b>Arrival (check-in)</b> · on arrival, you validate check-in: the room becomes <b>"occupied"</b> and the stay is active. You can collect a deposit or the full amount.',
    'process_step3' => 'During the stay · you track present guests, add consumption (restaurant, room service…) and extras that are added to the bill.',
    'process_step4' => '<b>Departure (check-out)</b> · at departure, you close the stay: the final bill is calculated (room + extras), then <b>collected at the register</b>. The room switches to <b>"to clean"</b>.',
    'process_step5' => 'Housekeeping · the cleaning team sees rooms to clean, does the work, and the room returns to <b>"clean / available"</b>, ready for the next guest.',
    'process_step6' => '<b>Cash register & closing</b> · at shift end, you <b>close the register</b> to reconcile the day\'s collections.',
    'process_step7' => '<b>Analytics</b> · at any time, the dashboard and reports give you occupancy, revenue and property activity.',
    'process_tip' => 'Every action (check-in, payment, cleaning…) is <b>logged in the activity journal</b>, specific to your property.',

    // Rooms detail
    'rooms_intro' => 'Organization happens in two steps: first <b>room types</b>, then the <b>rooms</b> themselves.',
    'rooms_step1' => '<b>Create your room types</b> · Menu <b>Rooms → Types</b> → "New type". Give it a name (Standard, Suite, Deluxe…), a <b>capacity</b> (number of persons) and a <b>base price</b>. Repeat for each category.',
    'rooms_step2' => '<b>Add your rooms</b> · Menu <b>Rooms → New room</b>. Enter the <b>number</b>, choose the <b>type</b>, capacity and price. The room is created with "available" status.',
    'rooms_step3' => '<b>Track statuses</b> · each room displays its state (available, reserved, occupied, to clean, cleaning, maintenance). These statuses update <b>automatically</b> with reservations and housekeeping.',
    'rooms_tip' => '<b>The room number is unique per property</b>: two different hotels can each have a room "101" without conflict.',

    // Bookings detail
    'bookings_intro' => 'This is the core of the business: recording a stay, welcoming the guest, then seeing them off.',
    'bookings_step1' => '<b>New reservation</b> · click <b>New reservation</b>. Select or create the <b>guest</b>, choose the <b>room</b> and <b>dates</b> (arrival / departure). The room moves to "reserved".',
    'bookings_step2' => '<b>The guest</b> · search for an existing profile, or create one (name, phone, optional email). The same guest can return without creating a duplicate.',
    'bookings_step3' => '<b>Check-in (arrival)</b> · on arrival, open the reservation and click <b>Check-in</b>. The room becomes "occupied" and the stay is active. You can collect a deposit or the full amount.',
    'bookings_step4' => 'During the stay · add <b>extras</b> (services, consumptions) that are added to the guest\'s bill.',
    'bookings_step5' => '<b>Check-out (departure)</b> · at departure, click <b>Check-out</b>. The final bill (room + extras) is calculated, then collected at the register. The room switches to "to clean".',
    'bookings_tip' => 'A guest arrives <b>without a reservation</b>? Use <b>direct check-in</b>: the reservation and arrival happen in one step.',

    // Cashier detail
    'cashier_intro' => 'The register tracks all money collected during a shift, with opening and closing for reconciliation.',
    'cashier_step1' => '<b>Open the register</b> · at shift start, Menu <b>Register → Open register</b>. Enter the <b>float</b> (starting amount).',
    'cashier_step2' => '<b>Collect payments</b> · record each payment (room, extras) indicating the <b>method</b>: cash, Mobile Money, card… A payment can be <b>partial</b> (deposit) and completed later.',
    'cashier_step3' => '<b>Track in real time</b> · the register displays the <b>total collected</b> during the session as payments come in.',
    'cashier_step4' => '<b>Close the register</b> · at shift end, click <b>Close register</b>: count your drawer, the application calculates <b>reconciliation</b> and flags any discrepancy.',
    'cashier_tip' => 'Every collection is <b>logged in the activity journal</b> of your property, with the author and timestamp.',

    // Housekeeping detail
    'housekeeping_intro' => 'The module tracks each room\'s state and organizes the cleaning team\'s work. <i>(Pro & Business plans)</i>',
    'housekeeping_step1' => '<b>Find rooms to process</b> — Menu <b>Housekeeping</b>: after each departure, the room appears "to clean". You see at a glance what needs to be done.',
    'housekeeping_step2' => '<b>Start cleaning</b> — when an agent begins, mark the room <b>"cleaning"</b>. The team knows what\'s in progress.',
    'housekeeping_step3' => '<b>Mark "clean"</b> — once done, switch the room to <b>"clean / available"</b>: it becomes immediately reservable again.',
    'housekeeping_step4' => '<b>Report maintenance</b> — if a room needs repair, set it to <b>"maintenance"</b> to make it unavailable during repairs.',
    'housekeeping_tip' => 'The <b>dashboard</b> displays in real time the number of rooms to clean, to prioritize the day\'s arrivals.',

    // Restaurant detail
    'restaurant_intro' => 'Manage your menu, dine-in orders and room service. <i>(Pro & Business plans)</i>',
    'restaurant_step1' => '<b>Create your menu</b> — Menu <b>Restaurant → Menus</b>: add your dishes (name, price) and organize them by <b>categories</b> (starters, mains, drinks…).',
    'restaurant_step2' => '<b>Take an order</b> — create an order and assign it to a <b>table</b> or a <b>room</b> (room service).',
    'restaurant_step3' => '<b>Track service</b> — the order progresses through statuses (pending, preparing, served), visible to the team.',
    'restaurant_step4' => '<b>Bill it</b> — the amount is <b>added to the guest\'s bill</b> (room service, paid at checkout) or <b>collected directly at the register</b>.',
    'restaurant_tip' => 'A <b>room service</b> order is linked to the guest\'s current stay: everything is settled in one go at check-out.',

    // Site detail
    'site_intro' => 'Each property gets a <b>branded public mini-site</b> to showcase rooms and accept online reservations.',
    'site_step1' => '<b>Enable sections</b> — from <b>My Property</b>, enable the pages you want (rooms, restaurant, services, contact).',
    'site_step2' => '<b>Customize</b> — colors, logo, cover image, texts (about, services) and social media links.',
    'site_step3' => '<b>Share the link</b> — your showcase has a <b>public URL</b>; share it with guests (social media, business card, WhatsApp).',
    'site_step4' => '<b>Receive reservations</b> — visitors browse your rooms and book online; the reservation arrives directly in your dashboard.',
    'site_tip' => 'The <b>"View my site"</b> button opens the showcase as your guests see it, to verify the result.',

    // Reports detail
    'reports_intro' => 'Drive your property\'s performance with clear numbers. <i>(Pro & Business plans)</i>',
    'reports_step1' => '<b>Open Reports</b> — Menu <b>Reports</b>.',
    'reports_step2' => '<b>Choose the period</b> — day, week or month, depending on what you want to analyze.',
    'reports_step3' => '<b>Analyze</b> — <b>occupancy rate</b>, <b>revenue</b>, number of reservations and payment method breakdown.',
    'reports_step4' => '<b>Decide</b> — adjust your rates and organization based on observed trends.',
    'reports_tip' => 'The <b>dashboard</b> already gives a daily overview (occupancy, revenue, arrivals/departures) without opening reports.',

    // Support
    'support_title' => 'Support & Help',
    'support_desc' => 'A question? Stuck? Our team is here to help:',
    'support_whatsapp' => '<b>WhatsApp Support 7/7</b> · quick response to unblock you.',

    // CTA
    'cta_title' => 'Ready to manage your hotel with confidence?',
    'cta_desc' => 'Start your :days-day free trial, no card required.',
    'cta_button' => 'Create my property',
];
