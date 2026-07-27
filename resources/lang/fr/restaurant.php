<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Restaurant Navigation
    |--------------------------------------------------------------------------
    */
    'nav' => [
        'menus' => 'Menus',
        'orders' => 'Commandes',
        'categories' => 'Catégories',
        'sales' => 'Ventes',
    ],

    /*
    |--------------------------------------------------------------------------
    | Index - Menu Listing
    |--------------------------------------------------------------------------
    */
    'index' => [
        'page_title' => 'Restaurant - Menus',
        'header' => 'Gestion de la Carte',
        'header_desc' => 'Configurez et gérez les menus de votre restaurant',
        'new_menu' => 'Nouveau Menu',
        'all_categories' => 'Toutes les catégories',
        'search_placeholder' => 'Rechercher une spécialité...',
        'cart' => 'Panier',
        'add' => 'Ajouter',
        'edit' => 'Modifier',
        'delete' => 'Supprimer',
        'empty' => 'Carte vide',
        'empty_desc' => 'Aucun menu n\'a encore été ajouté.',
        'add_first' => 'Ajouter le premier menu',
        'no_category' => 'Sans catégorie',
        'availability' => 'Disponibilité du plat',
        'confirm_title' => 'Êtes-vous sûr ?',
        'confirm_text' => 'Ce menu sera supprimé !',
        'confirm_yes' => 'Oui, supprimer !',
        'confirm_cancel' => 'Annuler',
        'delete_error' => 'Erreur lors de la suppression',
        'monday' => 'L',
        'tuesday' => 'M',
        'wednesday' => 'M',
        'thursday' => 'J',
        'friday' => 'V',
        'saturday' => 'S',
        'sunday' => 'D',
    ],

    /*
    |--------------------------------------------------------------------------
    | Create - Add New Menu
    |--------------------------------------------------------------------------
    */
    'create' => [
        'page_title' => 'Restaurant - Nouveau Menu',
        'header' => 'Ajouter un Menu',
        'back' => 'Retour',
        'name' => 'Nom du menu',
        'category' => 'Catégorie',
        'category_select' => 'Sélectionner une catégorie',
        'price' => 'Prix (FCFA)',
        'image' => 'Image du menu',
        'image_hint' => 'Formats acceptés: JPG, PNG, GIF. Max: 2MB',
        'description' => 'Description',
        'description_hint' => 'Décrivez les ingrédients, la préparation, etc.',
        'availability_days' => 'Jours de disponibilité',
        'select_all' => 'Tous',
        'select_none' => 'Aucun',
        'availability_hint' => 'Sélectionnez les jours où ce menu est disponible. Les jours décochés (comme Vendredi par défaut ici) ne seront pas affichés aux clients pour la commande ce jour-là.',
        'available_now' => 'Dispo immédiatement',
        'preview' => 'Prévisualisation',
        'remove' => 'Retirer',
        'save' => 'Enregistrer le menu',
        'monday' => 'Lun',
        'tuesday' => 'Mar',
        'wednesday' => 'Mer',
        'thursday' => 'Jeu',
        'friday' => 'Ven',
        'saturday' => 'Sam',
        'sunday' => 'Dim',
    ],

    /*
    |--------------------------------------------------------------------------
    | Edit - Update Menu
    |--------------------------------------------------------------------------
    */
    'edit' => [
        'page_title' => 'Restaurant - Modifier Menu',
        'header' => 'Modifier un Menu',
        'back' => 'Retour',
        'name' => 'Nom du menu',
        'category' => 'Catégorie',
        'category_select' => 'Sélectionner une catégorie',
        'price' => 'Prix (FCFA)',
        'image' => 'Image du menu',
        'image_hint' => 'Laissez vide pour conserver l\'image actuelle.',
        'description' => 'Description',
        'description_hint' => 'Décrivez les ingrédients, la préparation, etc.',
        'availability_days' => 'Jours de disponibilité',
        'available_now' => 'Dispo immédiatement',
        'preview_original' => 'Prévisualisation d\'origine',
        'delete_image' => 'Supprimer l\'image actuelle',
        'cancel' => 'Annuler',
        'save' => 'Enregistrer les modifications',
        'monday' => 'Lun',
        'tuesday' => 'Mar',
        'wednesday' => 'Mer',
        'thursday' => 'Jeu',
        'friday' => 'Ven',
        'saturday' => 'Sam',
        'sunday' => 'Dim',
    ],

    /*
    |--------------------------------------------------------------------------
    | Orders - Order Management
    |--------------------------------------------------------------------------
    */
    'orders' => [
        'page_title' => 'Restaurant - Commandes',
        'header' => 'Gestion des Commandes',
        'header_desc' => 'Suivez et traitez les commandes des clients en temps réel',
        'new_order' => 'Nouvelle Commande',
        'qr_title' => 'Menu Digital Restaurant',
        'qr_desc' => 'Ce QR Code permet aux clients de scanner et commander directement via leur mobile ou une tablette de table.',
        'download' => 'Télécharger',
        'kpi_revenue' => 'CA DU JOUR',
        'kpi_orders' => 'COMMANDES (AUJ.)',
        'kpi_pending' => 'EN ATTENTE / PRÉP.',
        'kpi_ready' => 'PRÊTES',
        'kpi_delivered' => 'LIVRÉES / PAYÉES',
        'filter_all' => 'Tous les statuts',
        'filter_pending' => 'En attente',
        'filter_validated' => 'Validée',
        'filter_preparing' => 'En préparation',
        'filter_ready' => 'Prêt',
        'filter_delivered' => 'Livré',
        'filter_paid' => 'Payé',
        'filter_cancelled' => 'Annulé',
        'filter_btn' => 'Filtrer',
        'th_id' => 'ID',
        'th_client' => 'Client',
        'th_room' => 'Chambre',
        'th_menus' => 'Menus',
        'th_total' => 'Total',
        'th_status' => 'Statut',
        'th_date' => 'Date',
        'th_actions' => 'Actions',
        'no_orders' => 'Aucune commande',
        'details_title' => 'Détails de la commande',
        'close' => 'Fermer',
        'print_ticket' => 'Imprimer le ticket',
        'view' => 'Voir',
        'validate' => 'Valider la commande',
        'start_prep' => 'Lancer la préparation',
        'mark_ready' => 'Marquer comme prêt',
        'mark_delivered' => 'Marquer comme livré',
        'collect' => 'Encaisser',
        'print_invoice' => 'Imprimer la facture',
        'room_charge' => 'Sur facture chambre',
        'error' => 'Erreur inconnue',
    ],

    /*
    |--------------------------------------------------------------------------
    | Invoice
    |--------------------------------------------------------------------------
    */
    'invoice' => [
        'invoice' => 'FACTURE',
        'client' => 'Client',
        'name' => 'Nom',
        'room' => 'Chambre',
        'order' => 'Commande',
        'reference' => 'Référence',
        'date' => 'Date',
        'payment' => 'Paiement',
        'method_cash' => 'Espèces',
        'method_card' => 'Carte Bancaire',
        'method_mobile' => 'Mobile Money',
        'method_fedapay' => 'Fedapay',
        'method_transfer' => 'Virement',
        'method_check' => 'Chèque',
        'method_room' => 'Facture de la chambre',
        'method_none' => 'Non spécifié',
        'designation' => 'Désignation',
        'qty' => 'Qté',
        'unit_price' => 'Prix unit.',
        'total' => 'Total',
        'total_due' => 'TOTAL À RÉGLER',
        'notes' => 'Notes :',
        'thank_you' => 'Merci pour votre commande',
        'printed_on' => 'Imprimé le',
        'close' => 'Fermer',
        'prompt_name' => 'Veuillez saisir le nom du client pour cette facture (Laisser vide pour ignorer) :',
    ],

    /*
    |--------------------------------------------------------------------------
    | Sales - Sales Tracking
    |--------------------------------------------------------------------------
    */
    'sales' => [
        'page_title' => 'Restaurant - Suivi des Ventes',
        'header' => 'Suivi des Ventes',
        'header_desc' => 'Analysez les performances et le chiffre d\'affaires du restaurant',
        'print_report' => 'Imprimer le rapport',
        'kpi_total_revenue' => 'CHIFFRE D\'AFFAIRES TOTAL',
        'kpi_today' => 'REVENUS AUJOURD\'HUI',
        'kpi_month' => 'CHIFFRE DU MOIS',
        'kpi_orders' => 'TOTAL COMMANDES',
        'chart_revenue' => 'Revenus · 7 derniers jours',
        'chart_category' => 'Par catégorie',
        'chart_top10' => 'Top 10 · Plats les plus vendus',
        'th_dish' => 'Désignation du plat',
        'th_category' => 'Catégorie',
        'th_sales' => 'Ventes',
        'th_revenue' => 'Recettes',
        'unknown_item' => 'Article inconnu',
        'chart_monthly' => 'Évolution mensuelle (12 mois)',
        'monthly_ca' => 'CA mensuel',
        'dataset_revenue' => 'Revenus (CFA)',
        'dataset_orders' => 'Commandes',
    ],

    /*
    |--------------------------------------------------------------------------
    | Categories
    |--------------------------------------------------------------------------
    */
    'categories' => [
        'page_title' => 'Restaurant - Catégories',
        'header' => 'Gestion des Catégories',
        'header_desc' => 'Organisez vos menus par types de plats',
        'new' => 'Nouvelle Catégorie',
        'th_name' => 'Nom',
        'th_slug' => 'Slug',
        'th_actions' => 'Actions',
        'empty' => 'Aucune catégorie définie.',
        'create_title' => 'Nouvelle Catégorie',
        'name_label' => 'Nom de la catégorie',
        'name_placeholder' => 'Ex: Entrées, Boissons...',
        'cancel' => 'Annuler',
        'save' => 'Enregistrer',
        'edit_title' => 'Modifier la Catégorie',
        'update' => 'Mettre à jour',
        'delete_confirm' => 'Confirmation',
        'delete_yes' => 'Oui, supprimer',
        'delete_cancel' => 'Annuler',
        'delete_message' => 'Voulez-vous vraiment supprimer la catégorie',
        'delete_warning' => 'Attention : Cette catégorie contient',
        'delete_plat' => 'plat(s)',
    ],

    /*
    |--------------------------------------------------------------------------
    | Modal - New Order Modal
    |--------------------------------------------------------------------------
    */
    'modal' => [
        'title' => 'Nouvelle Commande',
        'subtitle' => 'Restaurant · Interface Administration',
        'step_dishes' => 'Plats',
        'step_customer' => 'Client',
        'step_confirm' => 'Confirmation',
        'cart_detail' => 'Détail de la commande',
        'cart_desc' => 'Voici les plats sélectionnés. Ajustez les quantités ou supprimez un article avant de continuer.',
        'cart_empty' => 'Votre panier est vide.',
        'cart_empty_hint' => 'Ajoutez des plats depuis la page restaurant puis revenez ici.',
        'clear_cart' => 'Vider le panier',
        'total' => 'Total :',
        'step2_title' => 'Identification & Lieu de service',
        'step2_desc' => 'Précisez où le client sera servi et identifiez-le.',
        'location_label' => 'Lieu de service',
        'location_room' => 'En Chambre',
        'location_table' => 'Au Restaurant',
        'table_number' => 'N° de Table',
        'customer_type' => 'Type de client',
        'customer_resident' => 'Client Résident',
        'customer_outdoor' => 'Client Extérieur / Nouveau',
        'search_customer' => 'Nom, téléphone, n° de chambre...',
        'no_resident' => 'Aucun client résident actif',
        'no_resident_hint' => 'Tous les clients enregistrés sont actuellement libérés ou n\'ont pas de séjour actif.',
        'change' => 'Modifier',
        'room' => 'Chambre',
        'status' => 'Statut',
        'registered_guest' => 'Client enregistré',
        'name_ref' => 'Nom ou Référence (Optionnel)',
        'step3_title' => 'Confirmation & Facturation',
        'step3_desc' => 'Vérifiez la commande, ajoutez des notes et choisissez le mode de facturation.',
        'room_billing' => 'Facturation chambre active',
        'payment_label' => 'Choisir le règlement',
        'pay_cash' => 'Espèces',
        'pay_card' => 'Carte',
        'pay_mobile' => 'Mobile M.',
        'pay_transfer' => 'Virement',
        'pay_fedapay' => 'Fedapay',
        'pay_check' => 'Chèque',
        'notes_chef' => 'Notes pour le chef',
        'preview' => 'Aperçu & Détails',
        'info_client' => 'Infos Client & Lieu',
        'chef_prefs' => 'Préférences Chef',
        'selected_dishes' => 'Plats sélectionnés',
        'prev' => 'Précédent',
        'next' => 'Suivant',
        'cancel' => 'Annuler',
        'submit' => 'Enregistrer la commande',
        'error_add_dish' => 'Veuillez ajouter au moins un plat.',
        'error_table' => 'Veuillez indiquer le numéro de table.',
        'error_customer' => 'Veuillez sélectionner un client.',
        'clear_cart_title' => 'Vider le panier ?',
        'clear_cart_text' => 'Tous les plats sélectionnés seront retirés.',
        'clear_cart_yes' => 'Oui, vider',
        'clear_cart_cancel' => 'Annuler',
        'error_loading' => 'Erreur de chargement.',
        'cancel_order' => 'Annuler la commande ?',
        'cancel_yes' => 'Oui, annuler',
        'cancel_no' => 'Non',
        'cancelled' => 'Annulé !',
        'error_action' => 'Action impossible.',
        'error_title' => 'Erreur',
    ],

    /*
    |--------------------------------------------------------------------------
    | Details - Order Details
    |--------------------------------------------------------------------------
    */
    'details' => [
        'customer_info' => 'Informations client',
        'name' => 'Nom:',
        'phone' => 'Téléphone:',
        'room' => 'Chambre:',
        'order_date' => 'Date commande:',
        'order_summary' => 'Résumé de la commande',
        'number' => 'Numéro:',
        'status' => 'Statut:',
        'total' => 'Total:',
        'payment' => 'Paiement:',
        'method_cash' => 'Espèces',
        'method_card' => 'Carte bancaire',
        'method_room' => 'Frais de chambre',
        'method_online' => 'En ligne',
        'items_title' => 'Détails des articles',
        'th_menu' => 'Menu',
        'th_unit_price' => 'Prix unitaire',
        'th_quantity' => 'Quantité',
        'th_total' => 'Total',
        'subtotal' => 'Sous-total:',
        'tax' => 'Taxes',
        'discount' => 'Réduction:',
        'notes_title' => 'Notes',
        'history_title' => 'Historique',
        'order_created' => 'Commande créée',
        'last_modified' => 'Dernière modification',
    ],

];
