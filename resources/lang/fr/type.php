<?php

return [
    // Index page
    'page_title' => 'Types de Chambres',
    'header_title' => 'Types de <em>chambres</em>',
    'header_sub' => ':count type(s) disponible(s)',
    'new_type' => 'Nouveau type',
    'view_rooms' => 'Voir les chambres',

    // Stat cards
    'stat_total' => 'Total types',
    'stat_total_footer' => 'Types de chambres',
    'stat_active' => 'Actifs',
    'stat_active_footer' => 'Visibles',
    'stat_inactive' => 'Inactifs',
    'stat_inactive_footer' => 'Masqués',
    'stat_rooms' => 'Chambres',
    'stat_rooms_footer' => 'Au total',

    // Table
    'card_title' => 'Tous les types de chambres',
    'col_number' => 'N°',
    'col_details' => 'Détails',
    'col_rate' => 'Tarif',
    'col_capacity' => 'Capacité',
    'col_status' => 'Statut',
    'col_rooms' => 'Chambres',
    'col_actions' => 'Actions',

    // Labels
    'no_description' => 'Aucune description',
    'popular' => 'Populaire',
    'base_price_label' => 'prix de base',
    'not_defined' => 'Non défini',
    'person_s' => 'personne(s)',
    'bed_type' => 'Lit :type',
    'active' => 'Actif',
    'inactive' => 'Inactif',
    'room_s' => 'chambre(s)',

    // Tooltips
    'tooltip_edit' => 'Modifier',
    'tooltip_view_rooms' => 'Voir les chambres',
    'tooltip_delete' => 'Supprimer',

    // Footer
    'footer_showing' => 'Affichage de :count type(s) de chambre',
    'footer_delete_disabled' => 'La suppression est désactivée pour les types ayant des chambres assignées',

    // Empty state
    'empty_title' => 'Aucun type de chambre',
    'empty_text_1' => 'Commencez par créer votre premier type de chambre.',
    'empty_text_2' => 'Les types aident à organiser vos chambres par catégorie et tarif.',
    'empty_add' => 'Créer un type',

    // Help
    'help_title' => "Besoin d'aide pour gérer les types de chambres ?",
    'help_text' => 'Les types définissent des catégories comme "Standard", "Deluxe" ou "Suite". Chaque type peut avoir des tarifs, capacités et équipements différents.',
    'help_manage_rooms' => 'Gérer les chambres',

    // JS
    'js_delete_impossible' => 'Impossible de supprimer ":name" car :count chambre(s) y sont assignées.\n\nVeuillez d\'abord réassigner ou supprimer ces chambres.',
    'js_delete_confirm' => 'Êtes-vous sûr de vouloir supprimer ":name" ?\n\nCette action est irréversible.',

    // Create page
    'create_title' => 'Nouveau <em>type</em>',
    'create_subtitle' => 'Ajouter un nouveau type de chambre',
    'breadcrumb_dashboard' => 'Dashboard',
    'breadcrumb_types' => 'Types de chambres',
    'breadcrumb_new' => 'Nouveau type',
    'back' => 'Retour',
    'card_info' => 'Informations du type',
    'label_name' => 'Nom du type *',
    'label_price' => 'Prix de base (FCFA)',
    'label_capacity' => 'Capacité',
    'label_description' => 'Description',
    'label_active' => 'Actif (disponible pour sélection)',
    'placeholder_name' => 'Ex: Standard, Deluxe, Suite',
    'placeholder_price' => '50000',
    'placeholder_description' => 'Description du type de chambre...',
    'hint_price' => 'Prix par nuit recommandé',
    'hint_select' => '-- Sélectionner --',
    'submit_create' => 'Créer le type',
    'cancel' => 'Annuler',
    'saving' => 'Enregistrement...',
    'js_error_create' => 'Erreur lors de la création',
    'js_error_network' => 'Erreur réseau. Veuillez réessayer.',

    // Edit page
    'edit_title' => 'Modifier le <em>type</em>',
    'edit_subtitle' => ':name · Mise à jour des informations',
    'breadcrumb_edit' => 'Modifier: :name',
    'submit_update' => 'Mettre à jour',
    'js_error_edit' => 'Erreur lors de la modification',
];
