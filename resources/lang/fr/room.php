<?php

return [
    // ── Index page ──
    'page_title' => 'Gestion des Chambres',
    'header_title' => 'Gestion des <em>chambres</em>',
    'total_rooms_suffix' => 'chambres au total',
    'showing_range' => 'Affichage :first-:last',
    'new_room' => 'Nouvelle chambre',

    // Stat cards
    'stat_total' => 'Total chambres',
    'stat_total_footer' => 'Capacité totale',
    'stat_available' => 'Disponibles',
    'stat_available_footer' => 'Prêtes pour check-in',
    'stat_occupied' => 'Occupées',
    'stat_occupied_footer' => 'En cours',
    'stat_dirty' => 'À nettoyer',
    'stat_dirty_footer' => 'Check-in bloqué',
    'stat_maintenance' => 'Maintenance',
    'stat_maintenance_footer' => 'Hors service',

    // Action bar
    'all_rooms' => 'Toutes les chambres',
    'search_placeholder' => 'Rechercher par numéro, nom ou type...',

    // Card
    'list_title' => 'Liste des chambres',
    'entries_badge' => ':count entrées',

    // Table headers
    'col_number' => 'N° Chambre',
    'col_name' => 'Nom',
    'col_type' => 'Type',
    'col_capacity' => 'Capacité',
    'col_price' => 'Prix (FCFA)',
    'col_status' => 'Statut',
    'col_actions' => 'Actions',

    // Capacity
    'person_s' => 'personne(s)',
    'custom_price' => 'Prix personnalisé',
    'base_price' => 'Base: :price FCFA',
    'standard' => 'Standard',

    // Action tooltips
    'tooltip_view' => 'Voir détails',
    'tooltip_edit' => 'Modifier',
    'tooltip_mark_dirty' => 'Marquer comme sale',
    'tooltip_mark_clean' => 'Marquer comme propre',
    'tooltip_delete' => 'Supprimer',
    'tooltip_already_dirty' => 'Déjà sale',
    'tooltip_room_occupied' => 'Chambre occupée',
    'tooltip_action_unavailable' => 'Action non disponible',
    'tooltip_no_clean_needed' => 'Pas besoin de nettoyage',
    'tooltip_delete_occupied' => 'Impossible de supprimer une chambre occupée',

    // Confirm messages
    'confirm_mark_dirty' => 'Marquer la chambre :number comme sale ?',
    'confirm_mark_clean' => 'Marquer la chambre :number comme propre ?',
    'confirm_delete' => 'Supprimer la chambre :number ? Cette action est irréversible.',

    // Empty state
    'empty_title' => 'Aucune chambre trouvée',
    'empty_text' => "Vous n'avez pas encore ajouté de chambres.",
    'empty_add' => 'Ajouter une chambre',

    // Pagination
    'pagination_info' => 'Affichage de :first à :last sur :total entrées',

    // JS search empty
    'search_no_result' => 'Aucun résultat',
    'search_no_match' => 'Aucune chambre ne correspond à votre recherche',

    // ── Create page ──
    'create_page_title' => 'Nouvelle Chambre',
    'breadcrumb_dashboard' => 'Dashboard',
    'breadcrumb_rooms' => 'Chambres',
    'breadcrumb_new' => 'Nouvelle chambre',
    'create_title' => 'Nouvelle <em>chambre</em>',
    'create_subtitle' => "Ajouter une nouvelle chambre à l'hôtel",
    'back' => 'Retour',
    'error_heading' => 'Veuillez corriger les erreurs suivantes :',

    // Form
    'card_info' => 'Informations de la chambre',
    'label_number' => 'Numéro de chambre *',
    'label_name' => 'Nom de la chambre',
    'optional' => '(Optionnel)',
    'label_type' => 'Type de chambre *',
    'label_room_status' => 'Statut de la chambre *',
    'label_capacity' => 'Capacité *',
    'label_price' => 'Prix par nuit *',
    'label_view' => 'Description de la vue',

    // Placeholders
    'placeholder_number' => 'Ex: 101, 201, 301',
    'placeholder_name' => 'Ex: Suite Présidentielle, Vue Mer',
    'placeholder_description' => 'Ex: Suite spacieuse avec vue panoramique...',
    'placeholder_capacity' => 'Ex: 2, 4, 6',
    'placeholder_price' => 'Ex: 50000',
    'placeholder_view' => 'Ex: Vue sur mer, Vue sur montagne, Vue sur ville',

    // Hints
    'hint_unique_id' => 'Identifiant unique de la chambre · doit être différent des numéros existants',
    'hint_name' => 'Nom descriptif de la chambre',
    'hint_select_type' => '-- Sélectionner un type --',
    'hint_select_status' => '-- Sélectionner un statut --',
    'hint_status_initial' => 'Statut initial de la chambre (peut être modifié plus tard)',
    'hint_capacity' => 'Nombre de personnes (1-10)',
    'hint_view_optional' => 'Optionnel - décrit la vue depuis la chambre',

    // Live number check
    'number_taken' => 'Ce numéro est <strong>déjà utilisé</strong>, choisissez-en un autre.',
    'number_available' => 'Numéro disponible.',

    // Existing numbers
    'existing_numbers_title' => 'Numéros déjà utilisés (:count)',
    'existing_numbers_empty' => "Aucune chambre pour l'instant · c'est votre première.",

    // Buttons
    'cancel' => 'Annuler',
    'reset' => 'Réinitialiser',
    'create_submit' => 'Créer la chambre',
    'saving' => 'Enregistrement...',

    // ── Edit page ──
    'edit_page_title' => 'Modifier la Chambre',
    'breadcrumb_room_number' => 'Chambre :number',
    'breadcrumb_edit' => 'Modifier',
    'edit_title' => 'Modifier la <em>chambre</em>',
    'edit_subtitle' => 'Chambre :number · :name',
    'view_button' => 'Voir',
    'hint_unique_id_edit' => 'Identifiant unique de la chambre',
    'hint_status_auto' => 'Statut auto-géré',
    'hint_status_auto_desc' => 'Ce statut est automatiquement mis à jour en fonction des réservations et séjours.',
    'maintenance_toggle_on' => 'Mettre en maintenance',
    'maintenance_toggle_off' => 'Terminer la maintenance',
    'label_status_current' => 'Statut actuel',
    'auto_managed' => '(Auto-géré)',
    'meta_created' => 'Créée le: :date',
    'meta_updated' => 'Dernière modification: :date',
    'submit_update' => 'Mettre à jour',

    // SweetAlert edit page
    'swal_maintenance_start_title' => 'Mettre en maintenance ?',
    'swal_maintenance_end_title' => 'Terminer la maintenance ?',
    'swal_maintenance_start_html' => 'Cette action marquera temporairement la chambre comme indisponible.',
    'swal_maintenance_end_html' => 'Cette action marquera la chambre comme disponible à nouveau.',
    'swal_maintenance_reason_label' => 'Raison de la maintenance :',
    'swal_maintenance_reason_placeholder' => 'Nettoyage, réparations, rénovation...',
    'swal_maintenance_reason_required' => 'Veuillez entrer une raison de maintenance',
    'swal_confirm_end' => 'Oui, terminer',
    'swal_confirm_start' => 'Oui, mettre en maintenance',
    'swal_processing' => 'Traitement en cours...',
    'swal_please_wait' => 'Veuillez patienter',
    'swal_success_title' => 'Succès !',
    'swal_error_title' => 'Erreur',
    'swal_operation_failed' => 'Opération échouée',
    'swal_network_error' => 'Erreur réseau. Veuillez réessayer.',

    // ── Show page ──
    'show_page_title' => 'Détails de la Chambre',
    'show_title' => 'Détails de la <em>chambre</em>',
    'show_subtitle' => 'Chambre :number · :name',
    'edit_action' => 'Modifier',

    // Guest section
    'guest_current' => 'Client actuel',
    'guest_no_specified' => 'Non spécifié',
    'guest_room_available' => 'Chambre disponible',
    'guest_no_current' => 'Aucun client actuellement',

    // Info section
    'info_title' => 'Informations',
    'info_type' => 'Type',
    'info_status' => 'Statut',
    'info_capacity' => 'Capacité',
    'info_persons' => 'personnes',
    'info_price' => 'Prix',
    'info_price_per_night' => 'FCFA / nuit',
    'info_view' => 'Vue',
    'info_add_image' => 'Ajouter une image',

    // Images section
    'images_title' => 'Images',
    'images_empty_title' => 'Aucune image',
    'images_empty_text' => "Cette chambre n'a pas encore d'images",

    // Modal
    'modal_upload_title' => 'Ajouter une image à la galerie',
    'modal_label_image' => 'Sélectionner une image',
    'modal_formats' => 'JPG, PNG ou WEBP · 4 Mo maximum.',
    'modal_upload_btn' => 'Ajouter la photo',
    'image_err_required' => 'Veuillez sélectionner une image.',
    'image_err_type' => 'Format non pris en charge. Utilisez JPG, PNG ou WEBP.',
    'image_err_size' => "L'image est trop lourde (4 Mo maximum).",
    'upl_drop_title' => 'Glissez une photo ici',
    'upl_drop_or' => 'ou',
    'upl_drop_browse' => 'parcourez vos fichiers',
    'upl_remove' => 'Retirer',
    'modal_close' => 'Fermer',
    'modal_save' => 'Enregistrer',
    'modal_image_title_label' => "Titre de l'image (optionnel)",

    // Confirm
    'confirm_delete_image' => 'Supprimer cette image ?',

    // Toast
    'toast_success' => 'Succès',
    'toast_error' => 'Erreur',
    'toast_upload_failed' => 'Upload échoué',

    // Shared
    'unknown' => 'Inconnu',
    'image_alt' => 'Image chambre',
    'label_guest' => 'Client:',
    'label_arrival' => 'Arrivée:',
    'label_since' => 'Depuis:',
    'label_reason' => 'Raison:',
];
