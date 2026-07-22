<?php

return [
    // Page title
    'page_title' => 'Gestion des Réservations',

    // Header
    'header_title_1' => 'Gestion des',
    'header_title_2' => 'Réservations',
    'header_sub' => 'Gérez les arrivées, séjours et départs',
    'new_reservation' => 'Nouvelle réservation',
    'history' => 'Historique',
    'full_permissions' => 'Permissions complètes',

    // Legend
    'legend_reservation' => 'Réservation',
    'legend_in_hotel' => 'Dans l\'hôtel',
    'legend_completed' => 'Terminé (payé)',
    'legend_cancelled' => 'Annulée',
    'legend_no_show' => 'No Show',
    'legend_late' => 'Late checkout',

    // Search
    'search_title' => 'Rechercher une réservation',
    'search_placeholder' => 'ID, nom client ou numéro de chambre...',
    'search_button' => 'Rechercher',
    'clear' => 'Effacer',

    // Receptionist note
    'recep_note_title' => 'Réceptionniste · Permissions Complètes',
    'recep_note_desc' => 'Création, modification, paiements, check-in/out, annulation ✓ (sauf suppression)',

    // Active reservations
    'active_reservations' => 'Réservations en cours',
    'arrivals_stays' => 'Arrivées & séjours en cours',
    'col_id' => '#',
    'col_client' => 'Client',
    'col_room' => 'Chambre',
    'col_arrival' => 'Arrivée',
    'col_departure' => 'Départ',
    'col_nights' => 'Nuits',
    'col_total' => 'Total',
    'col_paid' => 'Payé',
    'col_remaining' => 'Reste',
    'col_status' => 'Statut',
    'col_actions' => 'Actions',
    'nights_count' => ':count nuit(s)',
    'ready' => 'Prêt',
    'departure_possible' => 'Départ possible',
    'grace' => 'largesse',
    'overdue' => 'Dépassé',
    'settled' => 'Soldé',
    'pay_now' => 'Régler',

    // Status labels
    'status_reservation' => 'Réservation',
    'status_in_hotel' => 'Dans hôtel',
    'status_completed' => 'Terminé',
    'status_cancelled' => 'Annulée',
    'status_no_show' => 'No Show',
    'status_late' => 'Late checkout',
    'status_unpaid' => '(impayé)',

    // Action tooltips
    'tooltip_payment' => 'Paiement',
    'tooltip_mark_arrived' => 'Marquer arrivé',
    'tooltip_edit' => 'Modifier',
    'tooltip_view' => 'Voir détails',
    'tooltip_view_late' => 'Voir détails late checkout',
    'tooltip_stay_bill' => 'Compte séjour',

    // Empty state
    'no_active_reservations' => 'Aucune réservation active',
    'start_create' => 'Commencez par créer une nouvelle réservation',

    // Old reservations
    'old_reservations' => 'Anciennes réservations',
    'old_reservations_sub' => 'Terminées ou expirées',

    // Departure tooltips
    'departure_largesse' => 'Départ (largesse jusqu\'à 14h)',
    'departure_late' => 'Départ (late checkout)',
    'late_fee_pending' => 'Supplément late checkout de :amount FCFA en attente',
    'departure_after_14h' => 'Départ après 14h - Prolonger',
    'arrival_scheduled' => 'Arrivée prévue le :date',
    'checkin_available_12h' => 'Check-in possible à partir de 12h. Encore :hours heure(s).',
    'departure_scheduled' => 'Départ prévu le :date',
    'checkout_available_12h' => 'Check-out possible à partir de 12h. Encore :hours heure(s).',

    // Create Identity page
    'create_client' => 'Création client',
    'create_client_title_1' => 'Création',
    'create_client_title_2' => 'client',
    'step_1_4' => 'Étape 1/4 · Informations personnelles',
    'cancel' => 'Annuler',
    'step_identity' => 'Identité',
    'step_dates' => 'Dates',
    'step_room' => 'Chambre',
    'step_confirmation' => 'Confirmation',
    'customer_info' => 'Informations client',
    'important_email' => 'Le même email peut être utilisé pour plusieurs réservations. Si le client existe déjà, ses informations seront mises à jour.',
    'existing_customer_found' => 'Client existant trouvé',
    'existing_reservations' => ':count réservation(s) existante(s)',
    'fix_errors' => 'Veuillez corriger les erreurs :',
    'email_label' => 'Adresse email',
    'email_hint' => 'Saisissez l\'email du client. Le système vérifiera s\'il existe déjà.',
    'full_name' => 'Nom complet',
    'birthdate' => 'Date de naissance',
    'gender' => 'Genre',
    'select' => '-- Sélectionner --',
    'male' => 'Masculin',
    'female' => 'Féminin',
    'other' => 'Autre',
    'phone' => 'Téléphone',
    'profession' => 'Profession',
    'address' => 'Adresse',
    'address_placeholder' => 'Rue, Ville, Pays',
    'photo' => 'Photo de profil',
    'clear_photo' => 'Effacer',
    'formats_accepted' => 'Formats acceptés : JPEG, PNG, GIF (max 2MB)',
    'save_continue' => 'et continuer',
    'save_update' => 'et mettre à jour',
    'step_1_identity' => 'Étape 1/4 - Identité',
    'next_step' => 'Prochaine étape : Dates et chambre',

    // Modal new reservation
    'has_account_question' => 'Le client a-t-il déjà un compte ?',
    'new_account' => 'Nouveau compte',
    'existing_customer' => 'Client existant',

    // JS messages
    'checking' => 'Vérification...',
    'invalid_email' => 'Veuillez saisir une adresse email valide.',
    'enter_name' => 'Veuillez saisir le nom du client.',
    'confirm_existing' => 'Ce client existe déjà. Voulez-vous mettre à jour ses informations et créer une nouvelle réservation ?',

    // Status extra
    'status_non_paid' => '(non payé)',

    // Restore
    'confirm_restore' => 'Restaurer cette réservation ?',

    // Cancel reservation
    'confirm_cancel_reservation' => 'Annuler cette réservation ?',
    'no_show_message' => 'Le client ne s\'est pas présenté',

    // Checkout time messages
    'too_early' => '⏳ Trop tôt',
    'checkout_from_12h' => 'Check-out possible à partir de 12h',
    'after_8pm' => '⚠️ Après 20h',
    'departure_impossible_after_20h' => 'Départ impossible après 20h. Veuillez prolonger le séjour.',

    // Departure confirmation
    'room_marked_clean' => 'La chambre sera marquée comme à nettoyer.',
    'late_checkout_room_clean' => 'Late checkout - La chambre sera marquée comme à nettoyer.',
    'grace_2h_room_clean' => 'Largesse de 2h accordée. La chambre sera marquée comme à nettoyer.',
    'confirm_departure' => 'Confirmer le départ ?',
    'yes_departure' => 'Oui, départ',

    // Create Identity extras
    'profession_placeholder' => 'Développeur, Médecin, Étudiant...',
    'preview' => 'Aperçu',

    // JS generic
    'yes' => 'Oui',
    'no' => 'Non',
    'cancel_reason_placeholder' => 'Raison (optionnelle)',
    'mark_no_show' => 'Marquer comme "No Show" ?',
    'incomplete_payment' => 'Paiement incomplet',
    'go_to_payment' => 'Aller au paiement',
    'cancel' => 'Annuler',
];
