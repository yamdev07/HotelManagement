<?php

return [
    // ─── ROUTES (web.php fallback) ──────────────────────
    'page_not_found' => 'Page non trouvée.',
    'page_not_found_login' => 'Page non trouvée. Veuillez vous connecter.',

    // ─── AUTH ───────────────────────────────────────────
    'login_welcome' => 'Bienvenue',
    'login_invalid' => 'Identifiants incorrects. Vérifiez votre email et votre mot de passe.',
    'register_success' => 'Votre compte a bien été créé. Vous pouvez maintenant vous connecter.',
    'logout_success' => 'Déconnexion réussie. Au revoir',

    // ─── MIDDLEWARE ─────────────────────────────────────
    'middleware_login_required' => 'Veuillez vous connecter.',
    'middleware_unauthorized' => 'Vous n\'êtes pas autorisé à accéder à cette page.',
    'middleware_admin_no_delete' => 'Les administrateurs ne peuvent pas supprimer les réservations.',
    'middleware_admin_no_cancel' => 'Les administrateurs ne peuvent pas annuler les réservations.',
    'middleware_housekeeping_restricted' => 'Accès restreint au personnel housekeeping.',
    'middleware_housekeeping_readonly' => 'Le personnel housekeeping a un accès en lecture seulement.',
    'middleware_receptionist_unauthorized' => 'Action non autorisée pour le personnel de réception.',
    'middleware_receptionist_no_cancel' => 'L\'annulation des réservations est réservée aux administrateurs.',
    'middleware_receptionist_no_delete' => 'La suppression des réservations est réservée aux administrateurs.',
    'middleware_receptionist_no_delete_customer' => 'La suppression des clients est réservée aux administrateurs.',
    'middleware_receptionist_no_delete_room' => 'La suppression des chambres est réservée aux administrateurs.',
    'middleware_receptionist_no_cancel_payment' => 'L\'annulation des paiements est réservée aux administrateurs.',
    'middleware_receptionist_no_assign_housekeeping' => 'L\'assignation du personnel de ménage est réservée aux administrateurs.',
    'middleware_receptionist_no_start_cleaning' => 'Le démarrage du nettoyage est réservé au personnel housekeeping.',
    'middleware_receptionist_action_denied' => 'Action non autorisée pour le personnel de réception.',
    'middleware_receptionist_needs_auth' => 'Cette action nécessite une autorisation spéciale.',
    'middleware_plan_missing' => 'Le module « :label » n\'est pas inclus dans votre offre :plan. Passez à une formule supérieure pour l\'activer.',

    // ─── TRANSACTION ────────────────────────────────────
    'transaction_edit_closed' => 'Impossible de modifier une réservation terminée ou annulée.',
    'transaction_updated' => 'Réservation #:id mise à jour avec succès.',
    'transaction_update_error' => 'Erreur interne lors de la modification.',
    'transaction_deleted' => 'Réservation #:id (:name) supprimée définitivement.',
    'transaction_delete_error' => 'Erreur lors de la suppression.',
    'transaction_status_updated' => 'Statut mis à jour : :status.',
    'transaction_status_error' => 'Erreur interne.',
    'transaction_cancelled' => 'Réservation annulée avec succès.',
    'transaction_no_show' => 'Réservation marquée comme No Show.',
    'transaction_restored' => 'Réservation restaurée avec succès.',
    'transaction_extended' => 'Séjour prolongé de :nights nuit(s). Nouveau départ : :date.',
    'transaction_extend_error' => 'Erreur lors de la prolongation.',
    'transaction_checkin_done' => 'Client marqué comme arrivé. Chambre :room maintenant occupée.',
    'transaction_checkout_done' => 'Check-out effectué avec succès.',
    'transaction_checkout_cleaning' => 'Départ enregistré - Chambre à nettoyer.',
    'transaction_checkout_cleaning_detail' => 'Client parti. Chambre marquée "À NETTOYER".',
    'transaction_late_checkout_created' => 'Late checkout enregistré et paiement créé.',
    'transaction_late_checkout_paid' => 'Late checkout enregistré et paiement encaissé.',
    'transaction_early_checkout' => 'Early checkout enregistré.',

    // ─── TRANSACTION ROOM RESERVATION ───────────────────
    'reservation_customer_updated' => 'Informations client mises à jour : :name',
    'reservation_login_required' => 'Vous devez être connecté pour créer un client',
    'reservation_customer_created' => 'Nouveau client créé par :agent : :name',
    'reservation_system_error' => 'Erreur système: Aucun utilisateur trouvé dans la base de données. Veuillez contacter l\'administrateur.',
    'reservation_date_arrival_required' => 'La date d\'arrivée est obligatoire',
    'reservation_date_departure_required' => 'La date de départ est obligatoire',
    'reservation_date_departure_after' => 'La date de départ doit être après la date d\'arrivée',
    'reservation_capacity_exceeded' => 'Le nombre de personnes ne peut pas dépasser la capacité de la chambre (:capacity)',
    'reservation_deposit_exceeded' => 'L\'acompte ne peut pas dépasser le prix total',
    'reservation_duplicate' => 'Cette réservation existe déjà. Aucun doublon créé.',
    'reservation_room_unavailable' => 'Cette chambre n\'est plus disponible pour les dates sélectionnées. Veuillez choisir d\'autres dates ou une autre chambre.',
    'reservation_error' => 'Erreur lors de la réservation.',
    'reservation_db_error' => 'Erreur de base de données lors de la réservation.',
    'reservation_column_missing' => 'Erreur: La colonne :column n\'existe pas dans la table.',
    'reservation_field_required' => 'Erreur: Le champ :field est requis.',
    'reservation_customer_not_found' => 'Aucun client trouvé avec cet email',

    // ─── CHECK-IN ───────────────────────────────────────
    'checkin_not_allowed' => 'Cette réservation ne peut pas être checkée-in. Statut: :status',
    'checkin_room_error' => 'La chambre sélectionnée ne permet pas le check-in.',
    'checkin_room_unavailable' => 'La chambre sélectionnée n\'est pas disponible pour cette période',
    'checkin_price_change' => 'Changement de prix détecté. Ancien: :old CFA, Nouveau: :new CFA. Veuillez confirmer.',
    'checkin_error' => 'Erreur lors du check-in.',
    'checkin_direct_room_unavailable' => 'La chambre n\'est pas disponible. Veuillez utiliser le check-in normal pour sélectionner une autre chambre.',
    'checkin_direct_error' => 'Erreur lors du check-in rapide.',
    'checkin_direct_error2' => 'Erreur lors du check-in direct.',

    // ─── PROFILE ────────────────────────────────────────
    'profile_invalid_phone' => 'Le numéro de téléphone n\'est pas valide (chiffres et + - ( ) espaces uniquement).',
    'profile_email_taken' => 'Cette adresse email est déjà utilisée par un autre compte.',
    'profile_updated' => 'Informations mises à jour avec succès.',
    'profile_wrong_password' => 'Le mot de passe actuel est incorrect.',
    'profile_password_changed' => 'Mot de passe modifié avec succès.',
    'profile_avatar_error' => 'Le téléversement de la photo a échoué. Réessayez avec une image JPG/PNG de moins de 2 Mo.',
    'profile_avatar_updated' => 'Photo de profil mise à jour.',
    'profile_updated_success' => 'Profil mis à jour avec succès !',

    'user_no_activity' => 'Aucune activité',

    // ─── USER ───────────────────────────────────────────
    'user_super_reserved' => 'Réservé au Super-Admin.',
    'user_created' => 'Utilisateur :name créé',
    'user_own_profile_only' => 'Vous ne pouvez voir que votre propre profil.',
    'user_delete_restricted' => 'Seuls les Super Admins et Admins peuvent supprimer des utilisateurs.',
    'user_cannot_delete_self' => 'Vous ne pouvez pas supprimer votre propre compte.',
    'user_has_active_reservations' => 'Ce client a des réservations actives. Impossible de supprimer.',
    'user_deleted' => 'Utilisateur :name supprimé avec succès!',
    'user_delete_error' => 'Impossible de supprimer :name.',
    'user_super_required' => 'Unauthorized: Super Admin privileges required.',
    'user_password_reset' => 'Mot de passe réinitialisé avec succès.',
    'user_password_reset_error' => 'Erreur lors de la réinitialisation.',
    'user_cannot_disable_self' => 'Vous ne pouvez pas désactiver votre propre compte.',
    'user_status_changed' => 'Utilisateur :label avec succès.',
    'user_status_error' => 'Erreur lors du changement de statut.',

    // ─── PAYMENT ────────────────────────────────────────
    'payment_already_paid' => 'Cette transaction est déjà entièrement payée.',
    'payment_cancelled_no_show' => 'Impossible d\'effectuer un paiement sur une transaction annulée ou no show.',
    'payment_refund' => 'Remboursement early checkout',

    // ─── BILLING ────────────────────────────────────────
    'billing_suspended' => 'Votre compte a été suspendu par la plateforme. Le paiement en ligne est indisponible.',
    'billing_not_configured' => 'Le paiement en ligne n\'est pas encore configuré. Contactez la plateforme.',
    'billing_error' => 'Impossible de démarrer le paiement pour le moment. Réessayez plus tard.',
    'billing_session_not_found' => 'Session de paiement introuvable. Si vous avez été débité, contactez-nous.',
    'billing_payment_failed' => 'Le paiement n\'a pas abouti. Aucun montant n\'a été prélevé.',
    'billing_confirmed' => 'Paiement confirmé ! Votre abonnement est actif jusqu\'au :date.',

    // ─── HOTEL SETTINGS ─────────────────────────────────
    'hotel_settings_updated' => 'Les informations de votre établissement ont été mises à jour.',
    'hotel_settings_no_hotel' => 'Aucun établissement associé à ce compte.',

    // ─── ONBOARDING ─────────────────────────────────────
    'onboarding_complete' => 'Votre site est configuré ! Bienvenue dans votre espace.',

    // ─── FACILITY ───────────────────────────────────────
    'facility_created' => 'Équipement ajouté.',
    'facility_updated' => 'Équipement mis à jour.',
    'facility_deleted' => 'Équipement supprimé.',

    // ─── TRANSACTION EXTRA ──────────────────────────────
    'extra_created' => 'Extra ajouté avec succès',
    'extra_created_detail' => 'Extra ":description" ajouté à la facture.',
    'extra_deleted' => 'Extra supprimé',
    'extra_deleted_detail' => 'Extra supprimé de la facture.',

    // ─── RESTAURANT CATEGORY ────────────────────────────
    'restaurant_category_created' => 'Catégorie ajoutée avec succès.',
    'restaurant_category_updated' => 'Catégorie mise à jour avec succès.',
    'restaurant_category_deleted' => 'Catégorie supprimée avec succès.',

    // ─── RESTAURANT ─────────────────────────────────────
    'restaurant_menu_created' => 'Menu ajouté avec succès!',
    'restaurant_menu_updated' => 'Menu modifié avec succès!',
    'restaurant_room_number_missing' => 'Numéro de chambre manquant.',
    'restaurant_no_active_guest' => 'Aucun client actif dans la chambre :room. Veuillez vérifier le numéro.',

    // ─── IMAGE ──────────────────────────────────────────
    'image_created' => 'Image ajoutée !',

    // ─── ROOM ───────────────────────────────────────────
    'room_cannot_mark_dirty_occupied' => 'Impossible de marquer une chambre occupée comme sale. Le client est toujours présent.',
    'room_cannot_mark_dirty_maintenance' => 'Impossible de marquer une chambre en maintenance comme sale. Terminez d\'abord la maintenance.',
    'room_marked_dirty' => 'Chambre :number marquée comme sale avec succès.',

    // ─── CASHIER SESSION ────────────────────────────────
    'session_load_error' => 'Erreur lors du chargement des sessions.',
    'session_already_active' => 'Vous avez déjà une session active. Veuillez la clôturer avant d\'en démarrer une nouvelle.',
    'session_no_permission' => 'Vous n\'avez pas les permissions nécessaires pour démarrer une session.',
    'session_active_id' => 'Vous avez déjà une session active. ID: #:id',
    'session_started' => 'Session démarrée avec succès! ID: #:id à :time',
    'session_no_access' => 'Vous n\'avez pas accès à cette session.',
    'session_unauthorized' => 'Action non autorisée.',
    'session_closed_error' => 'Les sessions clôturées ne peuvent pas être modifiées.',
    'session_updated' => 'Session mise à jour avec succès.',
    'session_not_active' => 'Cette session n\'est pas active.',
    'session_closed' => 'Session #:id clôturée.',
    'session_close_error' => 'Erreur lors de la clôture.',
    'session_no_active' => 'Aucune session active. Veuillez démarrer une session.',
    'session_admin_only' => 'Accès réservé aux administrateurs.',
    'session_access_denied' => 'Accès non autorisé.',
    'session_must_be_closed' => 'La session doit être fermée pour générer le rapport.',

    // ─── RECEPTIONIST SESSION ───────────────────────────
    'receptionist_session_unauthorized' => 'Accès non autorisé',

    // ─── REGISTER HOTEL ─────────────────────────────────
    'register_invalid_logo' => 'Le logo doit être une image JPG, PNG, WEBP ou SVG.',
    'register_logo_too_heavy' => 'Le logo est trop lourd (4 Mo maximum). Réduisez sa taille et réessayez.',
    'register_logo_unreadable' => 'Le logo n\'a pas pu être lu. Réessayez avec une image JPG ou PNG.',
    'register_existing_account' => 'Compte existant détécté. Plan mis à jour : :plan.',
    'register_trial_started' => 'Bienvenue ! Votre essai gratuit de :days jours a démarré.',

    // ─── HOUSEKEEPING ───────────────────────────────────
    'housekeeping_load_error' => 'Erreur lors du chargement.',
    'housekeeping_room_cleaned' => 'Chambre nettoyée avec succès.',
    'housekeeping_cleaning_started' => 'Nettoyage démarré.',
    'housekeeping_inspection_requested' => 'Inspection demandée.',
    'housekeeping_task_completed' => 'Tâche complétée.',
    'housekeeping_assignment_saved' => 'Assignation sauvegardée.',
    'housekeeping_status_updated' => 'Statut mis à jour.',
    'housekeeping_report_generated' => 'Rapport généré.',
    'housekeeping_stats_loaded' => 'Statistiques chargées.',
    'housekeeping_maintenance_requested' => 'Maintenance demandée.',
    'housekeeping_room_available' => 'Chambre remise en service.',
    'housekeeping_error' => 'Une erreur est survenue.',
    'housekeeping_not_found' => 'Élément non trouvé.',
    'housekeeping_unauthorized' => 'Action non autorisée.',

    // ─── REPORT ─────────────────────────────────────────
    'report_today' => 'Aujourd\'hui',
    'report_yesterday' => 'Hier',
    'report_this_week' => 'Cette semaine',
    'report_this_month' => 'Ce mois',
    'report_this_quarter' => 'Ce trimestre',
    'report_this_year' => 'Cette année',
];
