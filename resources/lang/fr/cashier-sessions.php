<?php

return [
    // ── Common / Breadcrumbs ──
    'home' => 'Accueil',
    'cashier' => 'Caissier',
    'sessions' => 'Sessions',
    'status_active' => 'Active',
    'status_closed' => 'Fermée',
    'in_progress' => 'En cours',
    'close' => 'Clôturer',
    'cancel' => 'Annuler',
    'details' => 'Voir détails',
    'back' => 'Retour',
    'print' => 'Imprimer',
    'reset' => 'Réinitialiser',
    'start' => 'Démarrer',

    // ── Index: Header ──
    'title' => 'Sessions de Caisse',
    'subtitle' => 'Gérez et consultez toutes les sessions de caisse',
    'new_session' => 'Nouvelle session',

    // ── Index: Stats cards ──
    'stat_total_sessions' => 'Total sessions',
    'stat_total_since' => 'Depuis le début',
    'stat_active_sessions' => 'Sessions actives',
    'stat_active_en_cours' => 'En cours',
    'stat_revenue' => 'Chiffre d\'affaires',
    'stat_revenue_total' => 'FCFA total',
    'stat_avg_session' => 'Moyenne/session',
    'stat_avg_per_session' => 'FCFA par session',

    // ── Index: Filters ──
    'filter_all_statuses' => 'Tous les statuts',
    'filter_active_sessions' => 'Sessions actives',
    'filter_closed_sessions' => 'Sessions fermées',
    'filter_all_users' => 'Tous les utilisateurs',
    'filter_date_placeholder' => 'Date',
    'filter_apply' => 'Filtrer',
    'filter_reset' => 'Réinitialiser',

    // ── Index: Table headers ──
    'col_id' => 'ID',
    'col_receptionist' => 'Réceptionniste',
    'col_start' => 'Début',
    'col_end' => 'Fin',
    'col_duration' => 'Durée',
    'col_initial' => 'Initial',
    'col_final' => 'Final',
    'col_difference' => 'Différence',
    'col_status' => 'Statut',
    'col_actions' => 'Actions',
    'unknown_user' => 'Utilisateur inconnu',
    'tooltip_current_balance' => 'Solde actuel',
    'estimated' => '(estimé)',
    'btn_view_details' => 'Voir détails',
    'btn_report' => 'Rapport détaillé',

    // ── Index: Empty state ──
    'empty_title' => 'Aucune session',
    'empty_message' => 'Aucune session de caisse n\'a été trouvée.',
    'btn_start_session' => 'Démarrer une session',

    // ── Show: Header ──
    'show_title' => 'Détails de la Session',
    'session_label' => 'Session',

    // ── Show: Stats cards ──
    'period' => 'Période',
    'initial_balance' => 'Solde initial',
    'collections' => 'Encaissements',
    'payments_count' => ':count paiement(s)',
    'refunds' => 'Remboursements',
    'refunds_count' => ':count tx',
    'net' => 'Net',
    'final_balance' => 'Solde final',
    'gap' => 'Écart',

    // ── Show: Payment table ──
    'payment_history' => 'Historique des paiements',
    'transactions_count' => ':count transaction(s)',
    'col_reference' => 'Référence',
    'col_date' => 'Date',
    'col_client' => 'Client',
    'col_amount' => 'Montant',
    'col_method' => 'Méthode',
    'col_status' => 'Statut',
    'status_completed' => '✓ Complété',
    'status_pending' => '⏱ En attente',
    'btn_view_payment' => 'Voir détails',
    'empty_payments_title' => 'Aucun paiement',
    'empty_payments_message' => 'Aucun paiement n\'a été enregistré pendant cette session.',

    // ── Show: Close modal ──
    'close_session_title' => 'Clôturer la session',
    'close_session_irreversible' => 'Cette action est irréversible.',
    'summary_initial_balance' => 'Solde initial',
    'summary_collections' => 'Encaissements',
    'summary_refunds' => 'Remboursements',
    'summary_theoretical_balance' => 'Solde théorique',
    'form_real_balance' => 'Solde final réel',
    'form_real_balance_hint' => 'Montant réel en caisse',
    'form_notes' => 'Notes',
    'form_notes_placeholder' => 'Observations, anomalies...',
    'btn_close_session' => 'Clôturer',

    // ── Create: Header ──
    'create_title' => 'Nouvelle Session',
    'create_breadcrumb' => 'Nouvelle session',

    // ── Create: Alerts ──
    'alert_fix_errors' => 'Veuillez corriger les erreurs :',

    // ── Create: Rules ──
    'rules_title' => 'Règles importantes',
    'rule_one_session' => 'Une seule session active à la fois par utilisateur',
    'rule_records_payments' => 'Cette session enregistrera tous vos paiements',
    'rule_close_anytime' => 'Vous pourrez la clôturer à tout moment avec un rapport détaillé',
    'rule_starting_balance' => 'Le solde de départ est automatiquement à 0 FCFA',

    // ── Create: Form ──
    'form_start_new_session' => 'Démarrer une nouvelle session',
    'form_start_subtitle' => 'Ouvrez votre shift pour commencer à enregistrer les paiements',
    'form_session_info' => 'Informations de la session',
    'form_receptionist' => 'Réceptionniste:',
    'form_date' => 'Date:',
    'form_time' => 'Heure:',
    'form_starting_balance' => 'Solde de départ:',
    'form_session_number' => 'Session #:',
    'form_auto_generated' => 'Généré automatiquement',
    'form_notes_optional' => 'Notes (optionnel)',
    'form_notes_placeholder' => 'Informations complémentaires (observations particulières...)',
    'form_notes_max' => 'Maximum 500 caractères',
    'btn_cancel' => 'Annuler',
    'btn_start_session' => 'Démarrer',
    'security_note' => 'Toutes les actions seront enregistrées avec votre nom',
    'confirm_start' => 'Démarrer une nouvelle session ?',
    'starting' => 'Démarrage...',

    // ── Report: Header ──
    'report_title' => 'Rapport de Session',
    'session_active_badge' => 'Session active',

    // ── Report: Info grid ──
    'info_receptionist' => 'RÉCEPTIONNISTE',
    'info_date' => 'DATE',
    'info_schedule' => 'HORAIRES',
    'info_initial_balance' => 'SOLDE INITIAL',

    // ── Report: KPI cards ──
    'kpi_collected' => 'Encaissé',
    'kpi_refunded' => 'Remboursé',
    'kpi_net' => 'Net',
    'kpi_gap' => 'Écart: :amount',
    'kpi_avg' => 'Moyenne',
    'kpi_payments_count' => ':count paiements',
    'kpi_per_tx' => 'FCFA/tx',

    // ── Report: Section headers ──
    'section_breakdown' => 'Répartition',
    'section_transactions' => 'Transactions',
    'section_tx_count' => ':count tx',

    // ── Report: Table headers ──
    'col_ref_short' => 'Réf.',
    'col_method_short' => 'Méthode',

    // ── Report: Table footer ──
    'total' => 'Total',
    'total_collected' => 'Encaissé',
    'total_refunded' => 'Remboursé',
    'total_net' => 'Net',

    // ── Report: Summary ──
    'summary_initial' => 'Solde initial',
    'summary_total_collected' => 'Total encaissé',
    'summary_total_refunded' => 'Total remboursé',
    'summary_final' => 'Solde final',

    // ── Report: Signatures ──
    'signature_receptionist' => 'Réceptionniste',
    'signature_superior' => 'Supérieur',
    'signature_stamp' => 'Cachet',
];
