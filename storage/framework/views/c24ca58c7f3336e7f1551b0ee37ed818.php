<?php $__env->startSection('title', 'Room Status'); ?>
<?php $__env->startSection('content'); ?>

<style>
/* ═══════════════════════════════════════════════════════════════
   DESIGN SYSTEM - VERT SIDEBAR
═══════════════════════════════════════════════════════════════════ */
:root {
    /* Palette principale - Vert du Sidebar */
    --primary-50: #E8F5F0;
    --primary-100: #C1E4D6;
    --primary-200: #96D3BA;
    --primary-300: #6BC29E;
    --primary-400: #4BB589;
    --primary-500: #2AA874;
    --primary-600: #25A06C;
    --primary-700: #1F9661;
    --primary-800: #198C57;
    --primary-900: #0F7C44;

    /* Couleurs utilitaires */
    --success-500: #22C55E;
    --danger-500: #EF4444;
    --danger-600: #DC2626;
    --warning-500: #F59E0B;
    --warning-600: #D97706;
    --info-500: #3B82F6;
    --info-600: #2563EB;

    /* Neutres */
    --gray-50: #F9FAFB;
    --gray-100: #F3F4F6;
    --gray-200: #E5E7EB;
    --gray-300: #D1D5DB;
    --gray-400: #9CA3AF;
    --gray-500: #6B7280;
    --gray-600: #4B5563;
    --gray-700: #374151;
    --gray-800: #1F2937;
    --gray-900: #111827;

    /* Ombres */
    --shadow-sm: 0 1px 2px 0 rgba(42, 168, 116, 0.08);
    --shadow-md: 0 4px 6px -1px rgba(42, 168, 116, 0.12);
    --shadow-lg: 0 10px 15px -3px rgba(42, 168, 116, 0.15);
    
    /* Transitions */
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

* { 
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

.roomstatus-page {
    background: var(--gray-50);
    min-height: 100vh;
    padding: 32px;
    font-family: 'Inter', -apple-system, sans-serif;
}

/* ═══════════════════════════════════════════════════════════════
   BREADCRUMB
═══════════════════════════════════════════════════════════════════ */
.breadcrumb-pro {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    margin-bottom: 24px;
}

.breadcrumb-pro a {
    color: var(--gray-600);
    text-decoration: none;
    font-weight: 500;
    transition: var(--transition);
}

.breadcrumb-pro a:hover {
    color: var(--primary-600);
}

.breadcrumb-pro .separator {
    color: var(--gray-400);
}

.breadcrumb-pro .current {
    color: var(--gray-700);
    font-weight: 600;
}

/* ═══════════════════════════════════════════════════════════════
   PAGE HEADER
═══════════════════════════════════════════════════════════════════ */
.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 28px;
    flex-wrap: wrap;
    gap: 16px;
}

.header-title {
    display: flex;
    align-items: center;
    gap: 12px;
}

.header-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, var(--primary-700), var(--primary-500));
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    box-shadow: 0 4px 10px rgba(42, 168, 116, 0.3);
}

.header-title h1 {
    font-size: 1.875rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
}

.header-subtitle {
    color: var(--gray-500);
    font-size: 0.875rem;
    margin: 6px 0 0 60px;
}

/* ═══════════════════════════════════════════════════════════════
   STATISTICS
═══════════════════════════════════════════════════════════════════ */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 28px;
}

.stat-card {
    background: white;
    border-radius: 16px;
    padding: 20px;
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
}

.stat-card.primary::before { background: var(--primary-500); }
.stat-card.success::before { background: var(--success-500); }
.stat-card.warning::before { background: var(--warning-500); }
.stat-card.info::before { background: var(--info-500); }

.stat-number {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--gray-800);
    margin-bottom: 4px;
}

.stat-label {
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    color: var(--gray-500);
    letter-spacing: 0.5px;
}

.stat-footer {
    margin-top: 12px;
    font-size: 0.688rem;
    color: var(--gray-400);
    display: flex;
    align-items: center;
    gap: 4px;
}

/* ═══════════════════════════════════════════════════════════════
   BUTTONS
═══════════════════════════════════════════════════════════════════ */
.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 10px;
    font-weight: 500;
    font-size: 0.875rem;
    border: none;
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
    background: linear-gradient(135deg, var(--primary-700), var(--primary-500));
    color: white;
    box-shadow: 0 4px 6px -1px rgba(42, 168, 116, 0.3);
}

.btn-primary:hover {
    background: linear-gradient(135deg, var(--primary-800), var(--primary-600));
    transform: translateY(-1px);
    box-shadow: 0 6px 8px -1px rgba(42, 168, 116, 0.4);
    color: white;
    text-decoration: none;
}

/* ═══════════════════════════════════════════════════════════════
   ACTION BAR
═══════════════════════════════════════════════════════════════════ */
.action-bar {
    background: white;
    border-radius: 16px;
    padding: 16px 20px;
    margin-bottom: 24px;
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow-sm);
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
}

.action-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.action-right {
    flex: 1;
    max-width: 400px;
}

.filter-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 500;
    background: var(--primary-100);
    color: var(--primary-700);
    border: 1px solid var(--primary-200);
}

.badge-count {
    background: rgba(255, 255, 255, 0.5);
    padding: 2px 6px;
    border-radius: 20px;
    font-size: 0.65rem;
    font-weight: 600;
}

/* Recherche */
.search-container {
    position: relative;
    width: 100%;
}

.search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-400);
    font-size: 0.9rem;
    pointer-events: none;
    z-index: 2;
}

.search-input {
    width: 100%;
    padding: 12px 16px 12px 42px;
    border: 1px solid var(--gray-200);
    border-radius: 12px;
    font-size: 0.875rem;
    transition: var(--transition);
    background: white;
}

.search-input:focus {
    outline: none;
    border-color: var(--primary-500);
    box-shadow: 0 0 0 3px rgba(42, 168, 116, 0.1);
}

/* ═══════════════════════════════════════════════════════════════
   CARDS & TABLES
═══════════════════════════════════════════════════════════════════ */
.card-modern {
    background: white;
    border-radius: 20px;
    border: 1px solid var(--gray-200);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    margin-bottom: 24px;
}

.card-header-modern {
    padding: 20px 24px;
    border-bottom: 1px solid var(--gray-200);
    background: var(--gray-50);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}

.card-header-modern h3 {
    font-size: 1rem;
    font-weight: 700;
    color: var(--gray-700);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.card-header-modern h3 i {
    color: var(--primary-500);
}

.card-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 600;
    background: var(--primary-100);
    color: var(--primary-700);
    border: 1px solid var(--primary-200);
}

/* Table */
.table-modern {
    width: 100%;
    border-collapse: collapse;
}

.table-modern thead th {
    background: var(--gray-50);
    padding: 16px 20px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--gray-500);
    border-bottom: 1px solid var(--gray-200);
    text-align: left;
    white-space: nowrap;
}

.table-modern tbody td {
    padding: 16px 20px;
    font-size: 0.875rem;
    color: var(--gray-700);
    border-bottom: 1px solid var(--gray-100);
    white-space: nowrap;
    vertical-align: middle;
}

.table-modern tbody tr:hover {
    background: var(--gray-50);
}

.table-modern tbody tr:last-child td {
    border-bottom: none;
}

/* Status Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 600;
}

.status-badge.available {
    background: #E8F5E9;
    color: #2E7D32;
    border: 1px solid #A5D6A7;
}

.status-badge.occupied {
    background: #FFEBEE;
    color: #C62828;
    border: 1px solid #EF9A9A;
}

.status-badge.maintenance {
    background: #FFF3E0;
    color: #EF6C00;
    border: 1px solid #FFB74D;
}

.status-badge.cleaning {
    background: #E3F2FD;
    color: #1565C0;
    border: 1px solid #90CAF9;
}

.status-badge.reserved {
    background: #F3E5F5;
    color: #7B1FA2;
    border: 1px solid #CE93D8;
}

.status-badge.out-of-order {
    background: #E0E0E0;
    color: #424242;
    border: 1px solid #BDBDBD;
}

/* Code badge */
.code-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    background: var(--gray-100);
    color: var(--gray-700);
    border: 1px solid var(--gray-300);
    font-family: 'Monaco', 'Menlo', monospace;
}

/* ═══════════════════════════════════════════════════════════════
   ACTION BUTTONS - TRÈS VISIBLE
═══════════════════════════════════════════════════════════════════ */
.action-group {
    display: flex;
    align-items: center;
    gap: 6px;
}

.action-btn {
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: white;
    border: 2px solid var(--gray-200);
    color: var(--gray-600);
    transition: var(--transition);
    cursor: pointer;
    text-decoration: none;
    font-size: 0.875rem;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.action-btn.view {
    border-color: var(--info-500);
    color: var(--info-500);
}

.action-btn.view:hover {
    background: var(--info-500);
    color: white;
}

.action-btn.edit {
    border-color: var(--primary-500);
    color: var(--primary-500);
}

.action-btn.edit:hover {
    background: var(--primary-500);
    color: white;
}

.action-btn.delete {
    border-color: var(--danger-500);
    color: var(--danger-500);
}

.action-btn.delete:hover {
    background: var(--danger-500);
    color: white;
}

/* ═══════════════════════════════════════════════════════════════
   EMPTY STATE
═══════════════════════════════════════════════════════════════════ */
.empty-state-modern {
    background: white;
    border-radius: 20px;
    padding: 60px 20px;
    text-align: center;
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow-sm);
}

.empty-state-modern i {
    font-size: 4rem;
    color: var(--gray-300);
    margin-bottom: 20px;
}

.empty-state-modern h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--gray-600);
    margin-bottom: 8px;
}

.empty-state-modern p {
    color: var(--gray-400);
    margin-bottom: 24px;
}

/* ═══════════════════════════════════════════════════════════════
   PAGINATION
═══════════════════════════════════════════════════════════════════ */
.pagination-modern {
    display: flex;
    gap: 6px;
    justify-content: center;
    margin: 24px 0 16px;
}

.pagination-modern .page-item {
    list-style: none;
}

.pagination-modern .page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 10px;
    border: 1px solid var(--gray-200);
    background: white;
    color: var(--gray-600);
    font-size: 0.875rem;
    font-weight: 500;
    transition: var(--transition);
    text-decoration: none;
}

.pagination-modern .page-link:hover {
    background: var(--gray-50);
    border-color: var(--gray-300);
    color: var(--gray-800);
    transform: translateY(-2px);
}

.pagination-modern .active .page-link {
    background: var(--primary-500);
    border-color: var(--primary-500);
    color: white;
}

/* ═══════════════════════════════════════════════════════════════
   RESPONSIVE
═══════════════════════════════════════════════════════════════════ */
@media (max-width: 1200px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .roomstatus-page {
        padding: 16px;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .action-bar {
        flex-direction: column;
        align-items: stretch;
    }
    
    .action-right {
        max-width: 100%;
    }
    
    .table-modern {
        display: block;
        overflow-x: auto;
    }
}
</style>

<div class="roomstatus-page">
    <!-- Breadcrumb -->
    <div class="breadcrumb-pro">
        <a href="<?php echo e(route('dashboard.index')); ?>">
            <i class="fas fa-home fa-xs me-1"></i>Dashboard
        </a>
        <span class="separator"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Room Status</span>
    </div>

    <!-- Header -->
    <div class="page-header">
        <div class="header-title">
            <span class="header-icon">
                <i class="fas fa-toggle-on"></i>
            </span>
            <div>
                <h1>Gestion des Statuts de Chambres</h1>
                <p class="header-subtitle">Gérez les statuts de disponibilité des chambres et leurs codes</p>
            </div>
        </div>
        <button id="add-button" type="button" class="btn-primary">
            <i class="fas fa-plus-circle"></i>
            Nouveau statut
        </button>
    </div>

    <!-- Statistics -->
    <?php
        // Simuler des statistiques - à remplacer par vos vraies données
        $totalStatuses = 8;
        $availableCount = 3;
        $occupiedCount = 2;
        $maintenanceCount = 2;
        $cleaningCount = 1;
    ?>
    
    <div class="stats-grid">
        <div class="stat-card primary">
            <div class="stat-number"><?php echo e($totalStatuses); ?></div>
            <div class="stat-label">Statuts totaux</div>
            <div class="stat-footer">
                <i class="fas fa-tags"></i>
                Tous les statuts
            </div>
        </div>
        
        <div class="stat-card success">
            <div class="stat-number"><?php echo e($availableCount); ?></div>
            <div class="stat-label">Disponibles</div>
            <div class="stat-footer">
                <i class="fas fa-check-circle"></i>
                Chambres libres
            </div>
        </div>
        
        <div class="stat-card warning">
            <div class="stat-number"><?php echo e($occupiedCount); ?></div>
            <div class="stat-label">Occupés</div>
            <div class="stat-footer">
                <i class="fas fa-user"></i>
                Chambres prises
            </div>
        </div>
        
        <div class="stat-card info">
            <div class="stat-number"><?php echo e($maintenanceCount + $cleaningCount); ?></div>
            <div class="stat-label">Hors service</div>
            <div class="stat-footer">
                <i class="fas fa-tools"></i>
                Maintenance/nettoyage
            </div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="action-bar">
        <div class="action-left">
            <span class="filter-badge">
                <i class="fas fa-toggle-on"></i>
                Tous les statuts
                <span class="badge-count"><?php echo e($totalStatuses); ?></span>
            </span>
        </div>
        
        <div class="action-right">
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" 
                       class="search-input" 
                       id="searchInput"
                       placeholder="Rechercher un statut..." 
                       autocomplete="off">
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card-modern">
        <div class="card-header-modern">
            <h3>
                <i class="fas fa-toggle-on"></i>
                Liste des statuts de chambres
            </h3>
            <span class="card-badge">
                <i class="fas fa-list"></i>
                <?php echo e($totalStatuses); ?> enregistrés
            </span>
        </div>
        
        <div class="card-body-modern" style="padding: 0;">
            <!-- Table -->
            <div class="table-responsive">
                <table id="roomstatus-table" class="table-modern" style="width: 100%;">
                    <thead>
                        <tr>
                            <th scope="col">
                                <i class="fas fa-hashtag me-1"></i>#
                            </th>
                            <th scope="col">
                                <i class="fas fa-tag me-1"></i>Nom
                            </th>
                            <th scope="col">
                                <i class="fas fa-code me-1"></i>Code
                            </th>
                            <th scope="col">
                                <i class="fas fa-info-circle me-1"></i>Information
                            </th>
                            <th scope="col" style="text-align: center;">
                                <i class="fas fa-cog me-1"></i>Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <!-- Data will be populated here -->
                    </tbody>
                </table>
            </div>
            
            <!-- Empty State (hidden by default) -->
            <div id="emptyState" class="empty-state-modern" style="display: none;">
                <i class="fas fa-toggle-off"></i>
                <h3>Aucun statut trouvé</h3>
                <p>Commencez par ajouter votre premier statut de chambre.</p>
                <button id="empty-add-button" type="button" class="btn-primary">
                    <i class="fas fa-plus-circle"></i>
                    Ajouter un statut
                </button>
            </div>

            <!-- Pagination -->
            <div class="pagination-modern" id="pagination">
                <!-- Pagination will be here -->
            </div>
        </div>
    </div>
</div>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Sample data for room statuses
    var statusData = [
        { id: 1, name: 'Available', code: 'AVBL', info: 'Room is ready for check-in', statusClass: 'available' },
        { id: 2, name: 'Occupied', code: 'OCC', info: 'Room is currently occupied', statusClass: 'occupied' },
        { id: 3, name: 'Maintenance', code: 'MNT', info: 'Room under maintenance', statusClass: 'maintenance' },
        { id: 4, name: 'Cleaning', code: 'CLN', info: 'Room being cleaned', statusClass: 'cleaning' },
        { id: 5, name: 'Reserved', code: 'RSV', info: 'Room is reserved', statusClass: 'reserved' },
        { id: 6, name: 'Out of Order', code: 'OOO', info: 'Room temporarily unavailable', statusClass: 'out-of-order' },
        { id: 7, name: 'Do Not Disturb', code: 'DND', info: 'Guest requested privacy', statusClass: 'occupied' },
        { id: 8, name: 'Check-out', code: 'COUT', info: 'Room pending cleaning after checkout', statusClass: 'cleaning' }
    ];

    // Function to get status badge class
    function getStatusClass(name) {
        const statusMap = {
            'Available': 'available',
            'Occupied': 'occupied',
            'Maintenance': 'maintenance',
            'Cleaning': 'cleaning',
            'Reserved': 'reserved',
            'Out of Order': 'out-of-order',
            'Do Not Disturb': 'occupied',
            'Check-out': 'cleaning'
        };
        return statusMap[name] || 'available';
    }

    // Initialize DataTable
    var table = $('#roomstatus-table').DataTable({
        data: statusData,
        columns: [
            { 
                data: 'id',
                render: function(data) {
                    return '<span style="font-weight: 600; color: var(--primary-600);">' + data + '</span>';
                }
            },
            { 
                data: 'name',
                render: function(data, type, row) {
                    var statusClass = getStatusClass(data);
                    return '<span class="status-badge ' + statusClass + '">' +
                           '<i class="fas fa-circle fa-xs"></i>' +
                           data +
                           '</span>';
                }
            },
            { 
                data: 'code',
                render: function(data) {
                    return '<span class="code-badge">' + data + '</span>';
                }
            },
            { data: 'info' },
            {
                data: null,
                render: function(data, type, row) {
                    return '<div class="action-group" style="justify-content: center;">' +
                           '<button class="action-btn view" onclick="viewStatus(' + row.id + ')" data-bs-toggle="tooltip" title="Voir les détails">' +
                           '<i class="fas fa-eye"></i>' +
                           '</button>' +
                           '<button class="action-btn edit" onclick="editStatus(' + row.id + ')" data-bs-toggle="tooltip" title="Modifier">' +
                           '<i class="fas fa-edit"></i>' +
                           '</button>' +
                           '<button class="action-btn delete" onclick="deleteStatus(' + row.id + ', \'' + row.name + '\')" data-bs-toggle="tooltip" title="Supprimer">' +
                           '<i class="fas fa-trash"></i>' +
                           '</button>' +
                           '</div>';
                }
            }
        ],
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json',
            emptyTable: "Aucun statut disponible",
            info: "Affichage de _START_ à _END_ sur _TOTAL_ statuts",
            infoEmpty: "Affichage 0 à 0 sur 0 statuts",
            infoFiltered: "(filtré sur _MAX_ statuts au total)",
            lengthMenu: "Afficher _MENU_ statuts",
            search: "Rechercher :",
            zeroRecords: "Aucun statut trouvé"
        },
        dom: '<"row"<"col-sm-12"t>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        pageLength: 10,
        lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Tous"]],
        initComplete: function() {
            // Hide default search input
            $('.dataTables_filter').hide();
            
            // Connect custom search
            $('#searchInput').on('keyup', function() {
                table.search(this.value).draw();
            });
        },
        drawCallback: function() {
            // Reinitialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (el) {
                return new bootstrap.Tooltip(el);
            });
        }
    });

    // Handle empty state
    table.on('draw', function() {
        var info = table.page.info();
        if (info.recordsDisplay === 0) {
            $('#emptyState').show();
            $('#pagination').hide();
        } else {
            $('#emptyState').hide();
            $('#pagination').show();
        }
    });

    // Add button click handler
    $('#add-button, #empty-add-button').on('click', function() {
        Swal.fire({
            title: 'Ajouter un statut',
            html: `
                <div style="text-align: left;">
                    <div style="margin-bottom: 15px;">
                        <label style="font-weight: 500; color: var(--gray-700); margin-bottom: 5px; display: block;">
                            <i class="fas fa-tag" style="color: var(--primary-500); margin-right: 5px;"></i>Nom du statut
                        </label>
                        <input id="statusName" class="swal2-input" placeholder="Ex: Available" style="width: 100%;">
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="font-weight: 500; color: var(--gray-700); margin-bottom: 5px; display: block;">
                            <i class="fas fa-code" style="color: var(--primary-500); margin-right: 5px;"></i>Code
                        </label>
                        <input id="statusCode" class="swal2-input" placeholder="Ex: AVBL" style="width: 100%;">
                    </div>
                    <div>
                        <label style="font-weight: 500; color: var(--gray-700); margin-bottom: 5px; display: block;">
                            <i class="fas fa-info-circle" style="color: var(--primary-500); margin-right: 5px;"></i>Information
                        </label>
                        <textarea id="statusInfo" class="swal2-textarea" placeholder="Description du statut..." style="width: 100%;"></textarea>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-save me-2"></i>Ajouter',
            cancelButtonText: '<i class="fas fa-times me-2"></i>Annuler',
            reverseButtons: true,
            confirmButtonColor: '#2AA874',
            cancelButtonColor: '#6B7280',
            preConfirm: () => {
                const name = document.getElementById('statusName').value;
                const code = document.getElementById('statusCode').value;
                const info = document.getElementById('statusInfo').value;
                
                if (!name || !code) {
                    Swal.showValidationMessage('Le nom et le code sont requis');
                    return false;
                }
                
                return { name, code, info };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Succès!',
                    text: `Le statut "${result.value.name}" a été ajouté avec succès.`,
                    icon: 'success',
                    confirmButtonColor: '#2AA874',
                    timer: 2000
                });
            }
        });
    });

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (el) {
        return new bootstrap.Tooltip(el);
    });
});

// View function
function viewStatus(id) {
    Swal.fire({
        title: 'Détails du statut',
        html: `
            <div style="text-align: left; padding: 10px;">
                <p><strong><i class="fas fa-hashtag" style="color: var(--primary-500);"></i> ID:</strong> ${id}</p>
                <p><strong><i class="fas fa-tag" style="color: var(--primary-500);"></i> Nom:</strong> Sample Status</p>
                <p><strong><i class="fas fa-code" style="color: var(--primary-500);"></i> Code:</strong> SMP</p>
                <p><strong><i class="fas fa-info-circle" style="color: var(--primary-500);"></i> Information:</strong> Detailed information about this status.</p>
            </div>
        `,
        confirmButtonText: '<i class="fas fa-check me-2"></i>Fermer',
        confirmButtonColor: '#2AA874'
    });
}

// Edit function
function editStatus(id) {
    Swal.fire({
        title: 'Modifier le statut',
        html: `
            <div style="text-align: left;">
                <div style="margin-bottom: 15px;">
                    <label style="font-weight: 500; color: var(--gray-700); margin-bottom: 5px; display: block;">
                        <i class="fas fa-tag" style="color: var(--primary-500); margin-right: 5px;"></i>Nom du statut
                    </label>
                    <input id="editStatusName" class="swal2-input" value="Sample Status" style="width: 100%;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="font-weight: 500; color: var(--gray-700); margin-bottom: 5px; display: block;">
                        <i class="fas fa-code" style="color: var(--primary-500); margin-right: 5px;"></i>Code
                    </label>
                    <input id="editStatusCode" class="swal2-input" value="SMP" style="width: 100%;">
                </div>
                <div>
                    <label style="font-weight: 500; color: var(--gray-700); margin-bottom: 5px; display: block;">
                        <i class="fas fa-info-circle" style="color: var(--primary-500); margin-right: 5px;"></i>Information
                    </label>
                    <textarea id="editStatusInfo" class="swal2-textarea" style="width: 100%;">Detailed information</textarea>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-save me-2"></i>Enregistrer',
        cancelButtonText: '<i class="fas fa-times me-2"></i>Annuler',
        reverseButtons: true,
        confirmButtonColor: '#2AA874',
        cancelButtonColor: '#6B7280'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Succès!',
                text: 'Le statut a été modifié avec succès.',
                icon: 'success',
                confirmButtonColor: '#2AA874',
                timer: 2000
            });
        }
    });
}

// Delete function
function deleteStatus(id, name) {
    Swal.fire({
        title: 'Confirmer la suppression',
        html: `<strong>${name}</strong> (Code: SMP) sera supprimé définitivement.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-trash me-2"></i>Oui, supprimer',
        cancelButtonText: '<i class="fas fa-times me-2"></i>Annuler',
        reverseButtons: true,
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#6B7280'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Supprimé!',
                text: `Le statut "${name}" a été supprimé.`,
                icon: 'success',
                confirmButtonColor: '#2AA874',
                timer: 2000
            });
        }
    });
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/roomstatus/index.blade.php ENDPATH**/ ?>