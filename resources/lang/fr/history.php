<?php

return [
    // Page title & navigation
    'page_title' => 'Historique Réservation',
    'dashboard' => 'Dashboard',
    'reservations' => 'Réservations',
    'reservation' => 'Réservation #:id',
    'history' => 'Historique',
    'page_heading' => 'Historique de la Réservation #:id',
    'page_description' => 'Journal complet des modifications et événements',
    'back_to_details' => 'Retour aux détails',

    // Summary
    'client' => 'Client',
    'room' => 'Chambre',
    'room_number' => 'Chambre :number',
    'current_status' => 'Statut actuel',
    'created_on' => 'Créée le',

    // Filters
    'filter_by_type' => 'Filtrer par type',
    'all' => 'Tous',
    'status' => 'Statut',
    'payments' => 'Paiements',
    'dates' => 'Dates',
    'notes' => 'Notes',
    'total_modifications' => 'Modifications totales',
    'users_involved' => 'Utilisateurs impliqués',

    // Timeline
    'event_timeline' => 'Chronologie des événements',
    'export' => 'Exporter',
    'system' => 'Système',

    // Creation event
    'creation_badge' => 'Création',
    'reservation_created' => 'Réservation créée',
    'reservation_created_text' => 'Nouvelle réservation créée avec les paramètres suivants :',
    'client_label' => 'Client :',
    'room_label' => 'Chambre :',
    'arrival_label' => 'Arrivée :',
    'departure_label' => 'Départ :',
    'initial_status' => 'Statut initial :',
    'nights' => 'Nuits :',
    'total_price' => 'Prix total :',
    'initial_note' => 'Note initiale :',

    // Arrival event
    'arrival_badge' => 'Arrivée',
    'guest_marked_arrived' => 'Client marqué comme arrivé',
    'guest_arrived_text' => 'Le client est arrivé à l\'hôtel et a été enregistré.',
    'actual_arrival_time' => 'Heure d\'arrivée réelle :',

    // Departure event
    'departure_badge' => 'Départ',
    'guest_marked_departed' => 'Client marqué comme parti',
    'guest_departed_text' => 'Le client a quitté l\'hôtel. Le séjour est terminé.',
    'actual_departure_time' => 'Heure de départ réelle :',

    // Cancellation event
    'cancellation_badge' => 'Annulation',
    'reservation_cancelled' => 'Réservation annulée',
    'cancellation_reason' => 'Raison de l\'annulation :',
    'cancellation_text' => 'La réservation a été annulée. Tous les paiements associés ont été remboursés.',

    // Payment event
    'payment_badge' => 'Paiement #:id',
    'payment_made' => 'Paiement effectué',
    'amount' => 'Montant :',
    'method' => 'Méthode :',
    'reference' => 'Référence :',
    'payment_status' => 'Statut :',

    // No history
    'no_additional_history' => 'Aucune modification supplémentaire n\'a été enregistrée pour cette réservation. L\'historique complet inclut les événements automatiques (création, arrivée, départ, paiements).',

    // About section
    'about_history' => 'À propos de l\'historique',
    'about_history_text' => 'Cet historique est généré automatiquement. Toutes les modifications sont enregistrées avec horodatage et auteur.',
    'print' => 'Imprimer',

    // JS alerts
    'print_title' => 'Imprimer l\'historique ?',
    'print_text' => 'L\'historique complet sera imprimé au format paysage.',
    'print_confirm' => 'Imprimer',
    'print_cancel' => 'Annuler',
];
