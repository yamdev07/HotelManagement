<?php

return [
    // Page title
    'page_title' => 'Détails de la Réservation #:id',

    // Breadcrumb
    'reservations' => 'Réservations',

    // Header
    'reservation_title' => 'Réservation #:id',
    'check_in_out_badge' => 'Check-in 12h | Check-out 12h (largesse 14h)',

    // Status select options
    'status_reservation' => '📅 Réservation',
    'status_active' => '🏨 Dans l\'hôtel',
    'status_completed' => '✅ Terminé',
    'status_cancelled' => '❌ Annulée',
    'status_no_show' => '👤 No Show',

    // Buttons
    'back' => 'Retour',
    'arrival' => 'Arrivée',
    'arrival_at_12h' => 'Arrivée à 12h',
    'departure_largesse' => 'Départ (largesse)',
    'departure_late_checkout' => 'Départ (late checkout)',
    'departure_pending_payment' => 'Départ (paiement en attente)',
    'departure_blocked' => 'Départ bloqué',
    'departure_impossible' => 'Départ impossible',
    'departure_at' => 'Départ à :time',
    'departure_at_12h' => 'Départ à 12h',
    'extend' => 'Prolonger',
    'extend_one_night' => 'Prolonger d\'une nuit',
    'edit' => 'Modifier',
    'payment' => 'Paiement',
    'cancel' => 'Annuler',
    'view_profile' => 'Voir profil',
    'history' => 'Historique',
    'mark_as_paid' => 'Marquer payé',
    'add_payment' => 'Ajouter un paiement',
    'create_payment' => 'Créer un paiement',
    'invoice' => 'Facture',
    'restore' => 'Restaurer',
    'receipt' => 'Reçu',
    'close' => 'Fermer',
    'processing' => 'Traitement...',
    'confirm_late_checkout' => 'Confirmer late checkout',
    'confirm_early_checkout' => 'Confirmer early checkout',
    'confirm_cancellation' => 'Confirmer l\'annulation',
    'early_checkout' => 'Early checkout',
    'late_checkout' => 'Late checkout',

    // Status alerts
    'reservation_heading' => '📅 RÉSERVATION',
    'expected_arrival' => 'Arrivée prévue : :date à 12h00',
    'active_heading' => '🏨 DANS L\'HÔTEL',
    'expected_departure' => 'Départ prévu :',
    'completed_heading' => '✅ SÉJOUR TERMINÉ',
    'client_left_on' => 'Client parti le :date',
    'cancelled_heading' => '❌ ANNULÉE',
    'cancelled_on' => 'Annulée le :date',
    'cancel_reason' => 'Raison : :reason',
    'no_show_heading' => '👤 NO SHOW',
    'no_show_text' => 'Client ne s\'est pas présenté',

    // Late / Early checkout badges
    'late_checkout_label' => 'Late checkout: :time',
    'early_checkout_departure' => 'Early checkout - Départ anticipé',
    'refunded' => 'Remboursé: :amount FCFA',
    'early_checkout_abbrev' => 'Départ anticipé',
    'in_late_checkout' => '(dont late checkout)',
    'with_early_checkout' => '(early checkout)',

    // Tooltips
    'late_checkout_tooltip' => 'Paiement en attente - Cliquez sur "Marquer payé"',
    'late_fee_unpaid_tooltip' => 'Supplément late checkout de :amount FCFA non payé',
    'departure_after_20h_tooltip' => 'Départ après 20h - Prolongation nécessaire',
    'departure_at_time_tooltip' => 'Départ prévu à :time',

    // Customer card
    'customer_info' => 'Informations Client',
    'email_not_set' => 'Email non renseigné',
    'phone' => 'Téléphone',
    'nic_id' => 'NIC/ID',
    'not_specified' => 'Non renseigné',

    // Stay card
    'stay_info' => 'Informations Séjour',
    'room' => 'Chambre',
    'type_not_specified' => 'Type non spécifié',
    'stay_duration' => 'Durée du séjour',
    'night' => 'nuit',
    'nights' => 'nuit(s)',
    'arrival_date' => 'Arrivée',
    'departure_date' => 'Départ',
    'room_status' => 'Statut chambre',
    'reservation_status' => 'Statut réservation',

    // Payments card
    'payments' => 'Paiements',
    'settled' => 'Soldé',
    'pending' => 'En attente',
    'no_debt' => 'Aucune dette',
    'initial_total' => 'Total initial',
    'late_supplement' => 'Supplément late',
    'refund' => 'Remboursement',
    'final_total' => 'Total final',
    'paid' => 'Payé',
    'remaining' => 'Reste',
    'no_payment' => 'Aucun paiement',
    'no_payment_text' => 'Aucun paiement n\'a été effectué pour cette réservation.',

    // Restaurant consumption
    'restaurant_consumptions' => 'Consommations Restaurant (Facture Chambre)',
    'id' => 'ID',
    'date' => 'Date',
    'status' => 'Statut',
    'amount' => 'Montant',
    'delivered_on_bill' => 'Livré (Sur facture)',
    'not_invoiced' => '(Non facturé)',
    'total_added_to_bill' => 'Total ajouté à la facture :',

    // Payment history
    'payment_history' => 'Historique des paiements',
    'payment_number' => 'Paiement #:id',
    'refund_badge' => 'Remboursement',
    'note' => 'Note:',

    // Late checkout alerts
    'late_checkout_registered' => 'Late checkout enregistré',
    'late_checkout_pending_info' => 'Paiement en attente - Cliquez sur "Marquer payé"',
    'no_payment_created' => 'Aucun paiement créé',
    'early_checkout_registered' => 'Early checkout enregistré',
    'early_checkout_refund_text' => 'Départ anticipé - Remboursement de :amount FCFA',

    // Quick actions card
    'quick_actions' => 'Actions rapides',

    // Details card
    'details' => 'Détails',
    'number_of_guests' => 'Nombre de personnes',
    'person' => 'personne',
    'persons' => 'personne(s)',
    'price_per_night' => 'Prix par nuit',
    'late_checkout_supplement' => 'Supplément late checkout',
    'early_checkout_refund_label' => 'Remboursement early checkout',
    'departure_time' => 'Heure de départ',
    'created_on' => 'Créée le',
    'created_by' => 'Créée par',
    'last_modified' => 'Dernière modification',
    'notes' => 'Notes',
    'early_checkout_reason' => 'Raison early checkout',
    'departure_notes' => 'Notes départ',

    // Statistics card
    'statistics' => 'Statistiques',
    'nights_label' => 'Nuits',
    'total' => 'Total',
    'remaining_to_pay' => 'Reste à payer',
    'remaining_with_late' => 'dont :amount CFA de late checkout',

    // Early checkout modal
    'early_checkout_title' => 'Early checkout - Départ anticipé',
    'stay_summary' => 'Résumé du séjour',
    'client_label' => 'Client: :name',
    'room_label' => 'Chambre: :number',
    'planned_nights' => 'Nuités prévues',
    'actual_nights' => 'Nuités effectuées',
    'unused_nights' => 'Nuités non utilisées',
    'total_paid' => 'Total payé',
    'potential_refund_calc' => 'Calcul du remboursement potentiel:',
    'new_total' => 'Nouveau total',
    'already_paid' => 'Déjà payé',
    'max_refund' => 'Remboursement maximum',
    'refund_policy' => 'Politique de remboursement',
    'full_refund' => 'Remboursement intégral (:amount FCFA)',
    'partial_refund' => 'Remboursement partiel',
    'no_refund' => 'Aucun remboursement',
    'refund_amount' => 'Montant du remboursement (FCFA)',
    'refund_method' => 'Méthode de remboursement',
    'cash' => 'Espèces',
    'credit_card' => 'Carte bancaire',
    'mobile_money' => 'Mobile Money',
    'bank_transfer' => 'Virement',
    'early_departure_reason' => 'Raison du départ anticipé',
    'early_departure_placeholder' => 'Ex: Urgence, Changement de programme, Insatisfaction...',

    // Late checkout modal
    'late_checkout_title' => 'Late checkout - Chambre :room',
    'normal_departure' => 'Départ normal:',
    'new_departure_time' => 'Nouvelle heure de départ',
    'choose_time' => 'Choisir une heure',
    'payment_method' => 'Méthode de paiement',
    'amount_suggestions' => 'Suggestions de montant',
    'free' => 'Gratuit',
    'supplement_label' => 'Supplément (FCFA)',
    'free_label' => 'LIBRE',
    'night_price' => 'Prix nuit:',
    'notes_optional' => 'Notes (optionnel)',
    'late_checkout_reason_placeholder' => 'Raison du late checkout...',
    'transfer' => 'Virement',

    // Cancel modal
    'cancel_title' => 'Annuler la réservation',
    'cancel_confirm_text' => 'Êtes-vous sûr de vouloir annuler cette réservation ?',
    'cancel_reason_label' => 'Raison (optionnelle)',
    'cancel_reason_placeholder' => 'Pourquoi annuler ?',

    // Restore confirm
    'restore_confirm' => 'Restaurer cette réservation ?',

    // JavaScript confirms
    'confirm_payment' => 'Confirmer le paiement de ce supplément ?',
    'confirm_cancel_status' => '⚠️ Êtes-vous sûr de vouloir annuler cette réservation ?',
    'confirm_no_show_status' => '⚠️ Marquer comme "No Show" ?',

    // Toast messages
    'payment_confirmed_title' => 'Paiement confirmé !',
    'payment_confirmed_message' => 'Le paiement a été marqué comme payé avec succès.',
    'error_title' => 'Erreur',
    'unknown_error' => 'Erreur inconnue',
    'communication_error' => 'Erreur de communication',
];
