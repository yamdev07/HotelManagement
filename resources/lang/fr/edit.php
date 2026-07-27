<?php

return [
    // Page title
    'page_title' => 'Modifier Réservation',

    // Breadcrumb
    'dashboard' => 'Dashboard',
    'reservations' => 'Réservations',
    'breadcrumb_edit' => 'Modifier',

    // Header
    'edit_reservation_title' => 'Modifier la Réservation #:id',
    'client_label' => 'Client: :name',
    'back_button' => 'Retour',

    // Status alerts
    'cancelled_title' => 'Réservation annulée',
    'cancelled_text' => 'Cette réservation ne peut plus être modifiée.',
    'cancelled_at' => 'Annulée le :date',
    'no_show_title' => 'No Show',
    'no_show_text' => 'Le client ne s\'est pas présenté.',
    'completed_title' => 'Séjour terminé',
    'completed_text' => 'Le client est parti.',

    // Card header
    'edit_reservation_card' => 'Modifier la réservation',

    // Limited status warning
    'limited_status_warning' => 'Cette réservation est :status. Les modifications sont limitées.',

    // Client section
    'client_section' => 'Client',
    'client_name' => 'Nom',
    'client_phone' => 'Téléphone',
    'client_email' => 'Email',
    'not_specified' => 'Non renseigné',
    'client_actions' => 'Actions',
    'view_profile' => 'Voir profil',

    // Room section
    'room_section' => 'Chambre',
    'room_changeable' => 'Changeable',
    'select_room' => 'Sélectionner une chambre *',
    'choose_room' => '-- Choisir une chambre --',
    'current_room' => 'Chambre actuelle',
    'available_rooms' => 'Chambres disponibles',
    'occupied_rooms' => 'Chambres occupées (non disponibles)',
    'room_number_format' => 'Chambre :number - :type (:price CFA/nuit)',
    'room_occupied_format' => 'Chambre :number - :type (Occupée pour ces dates)',
    'room_availability_help' => 'Seules les chambres disponibles pour les dates sélectionnées sont affichées.',
    'room_number' => 'Numéro',
    'room_type' => 'Type',
    'room_price_per_night' => 'Prix/nuit',

    // Dates section
    'dates_section' => 'Dates du séjour',
    'fixed_times_badge' => 'Heures fixes 12h-12h',
    'nights_passed_info' => 'Nuits déjà passées :',
    'night' => 'nuit',
    'nights' => 'nights',
    'since' => 'depuis le',
    'check_in_date' => 'Date d\'arrivée *',
    'fixed_time_check_in' => 'Heure fixe : 12h00',
    'check_out_date' => 'Date de départ *',
    'fixed_time_check_out' => 'Heure fixe : 12h00 (largesse jusqu\'à 14h)',

    // Calculator section
    'nights_label' => 'Nuits',
    'total_price' => 'Prix total',
    'already_paid' => 'Déjà payé',
    'difference' => 'Différence',
    'check_availability' => 'Vérifier disponibilité',

    // Status section
    'status_section' => 'Statut',
    'change_status' => 'Modifier le statut',
    'status_reservation' => '📅 Réservation',
    'status_active' => '🏨 Dans l\'hôtel',
    'status_completed' => '✅ Terminé',
    'status_incomplete_payment' => '(paiement incomplet)',
    'status_cancelled' => '❌ Annulée',
    'status_no_show' => '👤 No Show',
    'incomplete_payment_help' => '⚠️ Paiement incomplet - Le client doit solder avant départ',
    'cancel_reason_label' => 'Raison de l\'annulation',
    'cancel_reason_placeholder' => 'Pourquoi annuler ?',
    'current_status' => 'Statut actuel :',
    'reason_label' => 'Raison :',

    // Notes section
    'notes_label' => 'Notes',
    'notes_placeholder' => 'Ajouter des notes...',

    // Action buttons
    'cancel_button' => 'Annuler',
    'save_button' => 'Enregistrer',

    // Summary sidebar
    'summary_title' => 'Résumé',
    'reservation_id' => 'ID Réservation',
    'summary_client' => 'Client',
    'summary_room' => 'Chambre',
    'arrival' => 'Arrivée',
    'departure' => 'Départ',
    'summary_nights' => 'Nuits',
    'nights_passed' => 'Nuits passées',
    'total' => 'Total',
    'paid' => 'Payé',
    'remaining' => 'Reste',
    'created_at' => 'Créée le',

    // Quick actions sidebar
    'quick_actions_title' => 'Actions rapides',
    'confirm_arrival' => 'Confirmer l\'arrivée ?',
    'mark_arrived' => 'Marquer arrivé',
    'departure_largesse' => 'Départ (largesse)',
    'departure_override' => 'Dérogation départ',
    'add_payment' => 'Ajouter paiement',
    'view_details' => 'Voir détails',

    // History sidebar
    'history_title' => 'Dernières modifications',
    'creation' => 'Création',
    'last_modification' => 'Dernière modif',
    'cancellation' => 'Annulation',
    'view_full_history' => 'Voir tout l\'historique',

    // Override modal
    'override_title' => 'Dérogation départ après 14h',
    'override_confirm' => 'Êtes-vous sûr de vouloir autoriser ce départ après 14h ?',
    'departure_planned' => 'Départ prévu :',
    'current_time' => 'Heure actuelle :',
    'override_reason_label' => 'Raison de la dérogation :',
    'override_reason_placeholder' => 'Pourquoi fermer les yeux ?',
    'authorize_departure' => 'Autoriser le départ',

    // JS strings - payment incomplete
    'js_incomplete_payment_title' => 'Paiement incomplet',
    'js_incomplete_payment_text' => 'Impossible de marquer comme terminé - Solde restant: :amount CFA',
    'js_understood' => 'Compris',

    // JS strings - date not reached
    'js_date_not_reached_title' => 'Date non atteinte',
    'js_arrival_planned_on' => 'L\'arrivée est prévue le :date',

    // JS strings - errors
    'js_error_title' => 'Erreur',
    'js_select_dates' => 'Veuillez sélectionner les dates',
    'js_select_room' => 'Veuillez sélectionner une chambre',
    'js_fill_all_dates' => 'Veuillez remplir toutes les dates',
    'js_departure_after_arrival' => 'Le départ doit être après l\'arrivée',
    'js_incomplete_payment_error' => 'Paiement incomplet - Impossible de marquer comme terminé',

    // JS strings - availability check
    'js_checking_title' => 'Vérification...',
    'js_room_available' => 'Chambre disponible',
    'js_room_not_available' => 'Chambre non disponible pour ces dates',

    // JS strings - cancel edit
    'js_cancel_edit_title' => 'Annuler les modifications ?',
    'js_cancel_edit_text' => 'Toutes les modifications non enregistrées seront perdues',
    'js_yes_cancel' => 'Oui, annuler',
    'js_no_stay' => 'Non, rester',

    // JS strings - save confirmation
    'js_save_title' => 'Enregistrer les modifications ?',
    'js_save_html_arrival' => 'Arrivée:',
    'js_save_html_departure' => 'Départ:',
    'js_save_html_nights' => 'Nuits:',
    'js_save_html_room' => 'Chambre:',
    'js_yes_save' => 'Oui, enregistrer',
    'js_saving' => 'Enregistrement...',

    // JS strings - price change
    'surcharge' => 'Majoration',
    'reduction' => 'Réduction',
    'price_per_night' => 'Prix/nuit',
    'impact_on_total' => 'Impact sur le total',
];
