<?php

return [
    // Page title
    'page_title' => 'Personnel',

    // Header
    'header_title' => 'Gestion du <em>personnel</em>',
    'header_subtitle' => '<i class="fas fa-shield-halved"></i> Comptes de votre équipe · limités à votre établissement',

    // Buttons
    'btn_new_member' => 'Nouveau membre',
    'btn_create_account' => 'Créer le compte',

    // Stats
    'stat_total_members' => 'Membres au total',
    'stat_your_team' => 'Votre équipe',

    // Role labels
    'role_receptionist' => 'Réceptionniste',
    'role_cashier' => 'Caissier',
    'role_housekeeping' => 'Housekeeping',
    'role_servant' => 'Serveur',
    'role_cuisinier' => 'Cuisinier',

    // Form
    'form_add_member' => 'Ajouter un membre',
    'form_hint' => 'Chaque membre reçoit ses <strong>propres identifiants</strong> et n\'accède qu\'à ce que son rôle autorise. Vous ne partagez jamais votre mot de passe.',
    'form_full_name' => 'Nom complet *',
    'form_email' => 'Email * <span class="text-muted">(identifiant de connexion)</span>',
    'form_phone' => 'Téléphone',
    'form_role' => 'Rôle *',
    'form_role_placeholder' => '- Choisir -',
    'form_password' => 'Mot de passe *',
    'form_password_placeholder' => '8+ car., lettres + chiffres',

    // Table
    'table_member' => 'Membre',
    'table_role' => 'Rôle',
    'table_actions' => 'Actions',
    'table_registered' => ':count enregistré(s)',

    // Action bar
    'action_team' => 'Équipe',
    'action_search_placeholder' => 'Rechercher un membre…',

    // List
    'list_title' => 'Mon équipe',

    // Actions
    'action_reset_password' => 'Réinitialiser le mot de passe',
    'action_delete' => 'Supprimer',
    'action_reset' => 'Réinitialiser',

    // Password reset
    'password_new_placeholder' => 'Nouveau mot de passe (8+, lettres + chiffres)',

    // Confirmations
    'confirm_delete' => 'Supprimer le compte de :name ?',

    // Empty state
    'empty_title' => 'Aucun membre pour l\'instant.',
    'empty_desc' => 'Cliquez sur <strong>« Nouveau membre »</strong> pour ajouter votre première recrue.',

    // Alerts
    'alert_success_created' => 'Membre du personnel « :name » créé. Il se connecte avec son email et le mot de passe que vous avez défini.',
    'alert_success_reset' => 'Mot de passe de :name réinitialisé.',
    'alert_success_deleted' => 'Membre « :name » supprimé.',
    'alert_error_no_hotel' => 'Aucun établissement associé à votre compte.',
    'alert_error_unauthorized' => 'Action non autorisée.',

    // Validation attributes
    'validation_name' => 'nom',
    'validation_email' => 'email',
    'validation_role' => 'rôle',
    'validation_password' => 'mot de passe',
];
