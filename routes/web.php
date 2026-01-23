<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomStatusController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionRoomReservationController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\CashierSessionController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\HousekeepingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// ==================== ROUTES FRONTEND (Site Vitrine) ====================
Route::get('/', [FrontendController::class, 'home'])->name('frontend.home');
Route::get('/chambres', [FrontendController::class, 'rooms'])->name('frontend.rooms');
Route::get('/chambre/{id}', [FrontendController::class, 'roomDetails'])->name('frontend.room.details');
Route::get('/restaurant-vitrine', [FrontendController::class, 'restaurant'])->name('frontend.restaurant');
Route::get('/services', [FrontendController::class, 'services'])->name('frontend.services');
Route::get('/contact', [FrontendController::class, 'contact'])->name('frontend.contact');
Route::post('/contact/submit', [FrontendController::class, 'contactSubmit'])->name('frontend.contact.submit');
Route::post('/restaurant/reservation', [FrontendController::class, 'restaurantReservationStore'])
    ->name('restaurant.reservation.store');

// ==================== ROUTES D'AUTHENTIFICATION ====================
Route::view('/login', 'auth.login')->name('login.index');
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Forgot Password routes
Route::group(['middleware' => 'guest'], function () {
    Route::get('/forgot-password', fn () => view('auth.passwords.email'))->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
    Route::get('/reset-password/{token}', fn (string $token) => view('auth.reset-password', ['token' => $token]))
        ->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// ==================== ROUTES BACKEND (Dashboard) ====================

// Routes accessibles uniquement aux Super Admins
Route::group(['middleware' => ['auth', 'checkRole:Super']], function () {
    Route::resource('user', UserController::class);
});

// Routes accessibles aux Super Admins et Admins
Route::group(['middleware' => ['auth', 'checkRole:Super,Admin']], function () {
    // Images
    Route::post('/room/{room}/image/upload', [ImageController::class, 'store'])->name('image.store');
    Route::delete('/image/{image}', [ImageController::class, 'destroy'])->name('image.destroy');

    // ROUTE RACCOURCIE POUR CRÉATION RAPIDE (Alias)
    Route::get('/createIdentity', function () {
        return redirect()->route('transaction.reservation.createIdentity');
    })->name('quick.createIdentity');

    // Réservations - Processus étape par étape
    Route::prefix('transaction/reservation')->name('transaction.reservation.')->group(function () {
        Route::get('/createIdentity', [TransactionRoomReservationController::class, 'createIdentity'])->name('createIdentity');
        Route::get('/pickFromCustomer', [TransactionRoomReservationController::class, 'pickFromCustomer'])->name('pickFromCustomer');
        
        // Route AJAX pour vérifier l'email
        Route::post('/search-by-email', [TransactionRoomReservationController::class, 'searchByEmail'])
            ->name('searchByEmail');
        
        Route::post('/storeCustomer', [TransactionRoomReservationController::class, 'storeCustomer'])->name('storeCustomer');
        Route::get('/{customer}/viewCountPerson', [TransactionRoomReservationController::class, 'viewCountPerson'])->name('viewCountPerson');
        Route::get('/{customer}/chooseRoom', [TransactionRoomReservationController::class, 'chooseRoom'])->name('chooseRoom');
        Route::get('/{customer}/{room}/{from}/{to}/confirmation', [TransactionRoomReservationController::class, 'confirmation'])->name('confirmation');
        Route::post('/{customer}/{room}/payDownPayment', [TransactionRoomReservationController::class, 'payDownPayment'])->name('payDownPayment');
        
        // Nouvelle route pour voir les réservations d'un client
        Route::get('/customer/{customer}/reservations', [TransactionRoomReservationController::class, 'showCustomerReservations'])
            ->name('customerReservations');
    });

    // CRUD Resources
    Route::resource('customer', CustomerController::class);
    Route::resource('type', TypeController::class);
    Route::resource('room', RoomController::class);
    Route::resource('roomstatus', RoomStatusController::class);
    
    // TRANSACTIONS - Routes CRUD complètes avec NOUVEAUX STATUTS
    Route::prefix('transaction')->name('transaction.')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('index');
        Route::get('/create', [TransactionController::class, 'create'])->name('create');
        Route::post('/', [TransactionController::class, 'store'])->name('store');
        Route::get('/{transaction}', [TransactionController::class, 'show'])->name('show');
        Route::get('/{transaction}/edit', [TransactionController::class, 'edit'])->name('edit');
        Route::put('/{transaction}', [TransactionController::class, 'update'])->name('update');
        Route::delete('/{transaction}', [TransactionController::class, 'destroy'])->name('destroy');
        
        // Annulation
        Route::delete('/{transaction}/cancel', [TransactionController::class, 'cancel'])->name('cancel');
        
        // Restauration
        Route::post('/{transaction}/restore', [TransactionController::class, 'restore'])->name('restore');
        
        // Facture
        Route::get('/{transaction}/invoice', [TransactionController::class, 'invoice'])->name('invoice');
        
        // Historique
        Route::get('/{transaction}/history', [TransactionController::class, 'history'])->name('history');
        
        // Export
        Route::get('/export/{type}', [TransactionController::class, 'export'])->name('export');
        
        // === NOUVELLES ROUTES POUR LA GESTION DES STATUTS ===
        // Mise à jour via combo box (statut complet)
        Route::put('/{transaction}/update-status', [TransactionController::class, 'updateStatus'])->name('updateStatus');
        
        // Actions rapides
        Route::post('/{transaction}/arrived', [TransactionController::class, 'markAsArrived'])->name('mark-arrived');
        Route::post('/{transaction}/departed', [TransactionController::class, 'markAsDeparted'])->name('mark-departed');
        
        // Routes AJAX/API pour la vérification de paiement
        Route::get('/{transaction}/check-payment', [TransactionController::class, 'checkPaymentStatus'])
            ->name('check-payment');
        Route::get('/{transaction}/can-complete', [TransactionController::class, 'checkIfCanComplete'])
            ->name('can-complete');
        
        // Routes AJAX/API existantes
        Route::get('/{transaction}/check-availability', [TransactionController::class, 'checkAvailability'])->name('checkAvailability');
        Route::get('/{id}/details', [TransactionController::class, 'showDetails'])->name('showDetails');
    });
    
    Route::resource('facility', FacilityController::class);

    // PAIEMENTS - ROUTES COMPLÈTES (y compris annulation/restauration)
    Route::prefix('payments')->name('payments.')->group(function () {
        // Liste des paiements avec filtres
        Route::get('/', [PaymentController::class, 'index'])->name('index');
        
        // Annuler un paiement
        Route::delete('/{payment}/cancel', [PaymentController::class, 'cancel'])->name('cancel');
        
        // Restaurer un paiement annulé/expiré
        Route::post('/{payment}/restore', [PaymentController::class, 'restore'])->name('restore');
        
        // Marquer comme expiré (pour API)
        Route::post('/{payment}/expire', [PaymentController::class, 'markAsExpired'])->name('expire');
        
        // Facture/reçu
        Route::get('/{payment}/invoice', [PaymentController::class, 'invoice'])->name('invoice');
    });

    // Paiements pour les transactions (routes existantes maintenues)
    Route::prefix('transaction/{transaction}/payment')->name('transaction.payment.')->group(function () {
        Route::get('/create', [PaymentController::class, 'create'])->name('create');
        Route::post('/store', [PaymentController::class, 'store'])->name('store');
    });
    
    // Alias pour la compatibilité (consulter la liste des paiements)
    Route::get('/payment', [PaymentController::class, 'index'])->name('payment.index');
    Route::get('/payment/{payment}/invoice', [PaymentController::class, 'invoice'])->name('payment.invoice');

    // Charts
    Route::get('/get-dialy-guest-chart-data', [ChartController::class, 'dailyGuestPerMonth']);
    Route::get('/get-dialy-guest/{year}/{month}/{day}', [ChartController::class, 'dailyGuest'])->name('chart.dailyGuest');
    
    // ==================== NOUVELLES ROUTES : GESTION DES CAISSES ====================
    Route::prefix('cashier')->name('cashier.')->group(function () {
        // Sessions de caisse
        Route::get('/sessions', [CashierSessionController::class, 'index'])->name('sessions.index');
        Route::get('/sessions/create', [CashierSessionController::class, 'create'])->name('sessions.create');
        Route::post('/sessions', [CashierSessionController::class, 'store'])->name('sessions.store');
        Route::get('/sessions/{cashierSession}', [CashierSessionController::class, 'show'])->name('sessions.show');
        Route::put('/sessions/{cashierSession}/close', [CashierSessionController::class, 'close'])->name('sessions.close');
        Route::delete('/sessions/{cashierSession}', [CashierSessionController::class, 'destroy'])->name('sessions.destroy');
        
        // Rapport de caisse
        Route::get('/report/{cashierSession}', [CashierSessionController::class, 'report'])->name('sessions.report');
        Route::get('/daily-report', [CashierSessionController::class, 'dailyReport'])->name('daily-report');
        
        // Dashboard caisse
        Route::get('/dashboard', [CashierSessionController::class, 'dashboard'])->name('dashboard');
        
        // API pour AJAX
        Route::get('/current-session', [CashierSessionController::class, 'getCurrentSession'])->name('current-session');
        Route::get('/session-summary', [CashierSessionController::class, 'sessionSummary'])->name('session-summary');
    });
});

// Routes accessibles à tous les utilisateurs authentifiés (Super, Admin, Customer)
Route::group(['middleware' => ['auth', 'checkRole:Super,Admin,Customer']], function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/home', function () {
        return redirect()->route('dashboard.index');
    })->name('home');
    
    // Activity Log
    Route::get('/activity-log', [ActivityController::class, 'index'])->name('activity-log.index');
    Route::get('/activity-log/all', [ActivityController::class, 'all'])->name('activity-log.all');
    
    // User profile (view only)
    Route::get('/user/{user}', [UserController::class, 'show'])->name('user.show');
    
    // Notifications
    Route::view('/notification', 'notification.index')->name('notification.index');
    Route::get('/mark-all-as-read', [NotificationsController::class, 'markAllAsRead'])->name('notification.markAllAsRead');
    Route::get('/notification-to/{id}', [NotificationsController::class, 'routeTo'])->name('notification.routeTo');
    
    // Profile management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::post('/update-info', [ProfileController::class, 'updateInfo'])->name('update.info');
        Route::post('/update-password', [ProfileController::class, 'updatePassword'])->name('update.password');
        Route::post('/update-avatar', [ProfileController::class, 'updateAvatar'])->name('update.avatar');
    });
    
    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // TRANSACTIONS - Routes accessibles aux clients pour voir leurs réservations
    Route::get('/my-reservations', [TransactionController::class, 'myReservations'])->name('transaction.myReservations');
    
    // SHOW transaction pour les clients (séparé de la route admin)
    Route::get('/my-transaction/{transaction}', [TransactionController::class, 'show'])->name('transaction.show.customer');
    
    // === ROUTES POUR LES STATUTS ACCESSIBLES AUX CLIENTS ===
    // Les clients peuvent voir leurs réservations mais pas changer le statut
    // Seuls Super, Admin, Reception peuvent changer les statuts
    
    // RESTAURANT MODULE - Accessible à tous les utilisateurs connectés
    Route::prefix('restaurant')->name('restaurant.')->group(function () {
        // Menus
        Route::get('/', [RestaurantController::class, 'index'])->name('index');
        Route::get('/create', [RestaurantController::class, 'create'])->name('create');
        Route::post('/store', [RestaurantController::class, 'store'])->name('store');
        Route::delete('/menus/{id}', [RestaurantController::class, 'destroy'])->name('menus.destroy');
        
        // Commandes
        Route::get('/orders', [RestaurantController::class, 'orders'])->name('orders');
        Route::get('/orders/{id}', [RestaurantController::class, 'showOrder'])->name('orders.show');
        Route::post('/orders', [RestaurantController::class, 'storeOrder'])->name('orders.store');
        Route::put('/orders/{id}', [RestaurantController::class, 'updateOrder'])->name('orders.update');
        Route::put('/orders/{id}/cancel', [RestaurantController::class, 'cancelOrder'])->name('orders.cancel');
        
        // API pour AJAX
        Route::get('/api/customers', [RestaurantController::class, 'getCustomers'])->name('api.customers');
        Route::get('/api/menus', [RestaurantController::class, 'getMenus'])->name('api.menus');
    });
    
    // ==================== ROUTES POUR LA DISPONIBILITÉ DES CHAMBRES ====================
    // ACCESSIBLE À TOUS LES UTILISATEURS CONNECTÉS
    Route::prefix('availability')->name('availability.')->group(function () {
        // Calendrier des disponibilités
        Route::get('/calendar', [AvailabilityController::class, 'calendar'])->name('calendar');
        
        // Recherche de disponibilité par période
        Route::get('/search', [AvailabilityController::class, 'search'])->name('search');
        
        // Inventaire des chambres
        Route::get('/inventory', [AvailabilityController::class, 'inventory'])->name('inventory');
        
        // Dashboard de disponibilité
        Route::get('/dashboard', [AvailabilityController::class, 'dashboard'])->name('dashboard');
        
        // Détail d'une chambre
        Route::get('/room/{room}', [AvailabilityController::class, 'roomDetail'])->name('room.detail');
        
        // API pour vérifier disponibilité (AJAX)
        Route::get('/check', [AvailabilityController::class, 'checkAvailability'])->name('check');
        
        // AJOUTEZ CETTE ROUTE POUR L'EXPORT
        Route::post('/export', [AvailabilityController::class, 'export'])->name('export');
    });
});

// ==================== ROUTES POUR LA DISPONIBILITÉ ET CHECK-IN AVANCÉ ====================

// Routes accessibles au personnel de réception (en plus des admins)
Route::group(['middleware' => ['auth', 'checkRole:Super,Admin,Reception']], function () {
    // === ROUTES POUR LE CHECK-IN AVANCÉ ===
    Route::prefix('checkin')->name('checkin.')->group(function () {
        // Dashboard check-in
        Route::get('/', [CheckInController::class, 'index'])->name('index');
        
        // Recherche de réservations
        Route::get('/search', [CheckInController::class, 'search'])->name('search');
        
        // Check-in direct (sans réservation)
        Route::get('/direct', [CheckInController::class, 'directCheckIn'])->name('direct');
        
        // Check-in d'une réservation spécifique
        Route::get('/{transaction}', [CheckInController::class, 'show'])->name('show');
        Route::post('/{transaction}', [CheckInController::class, 'store'])->name('store');
        
        // Check-in rapide
        Route::post('/{transaction}/quick', [CheckInController::class, 'quickCheckIn'])->name('quick');
        
        // Vérification de disponibilité (AJAX)
        Route::get('/availability/check', [CheckInController::class, 'checkAvailability'])->name('availability');
    });
    
    // === ROUTES POUR LA GESTION DES STATUTS (RÉCEPTION) ===
    Route::prefix('transaction')->name('transaction.')->group(function () {
        // Actions rapides de réception
        Route::post('/{transaction}/check-in', function($transaction) {
            // Redirige vers mark-arrived
            return app(TransactionController::class)->markAsArrived($transaction);
        })->name('check-in');
        
        Route::post('/{transaction}/check-out', function($transaction) {
            // Redirige vers mark-departed
            return app(TransactionController::class)->markAsDeparted($transaction);
        })->name('check-out');
        
        // Vue spéciale pour la réception (dashboard réception)
        Route::get('/reception/today', [TransactionController::class, 'index'])->name('reception.today')
            ->defaults('view', 'reception'); // Paramètre pour la vue
    });
    
    // ==================== ROUTES CAISSE POUR LA RÉCEPTION ====================
    Route::prefix('cashier')->name('cashier.')->group(function () {
        // Gestion des sessions pour la réception
        Route::get('/open-session', [CashierSessionController::class, 'openSession'])->name('open-session');
        Route::post('/start-session', [CashierSessionController::class, 'startSession'])->name('start-session');
        Route::post('/close-session/{cashierSession}', [CashierSessionController::class, 'closeSession'])->name('close-session');
        Route::get('/my-sessions', [CashierSessionController::class, 'mySessions'])->name('my-sessions');
        Route::get('/session-report/{cashierSession}', [CashierSessionController::class, 'sessionReport'])->name('session-report');
        
        // Dashboard réception avec caisse
        Route::get('/reception-dashboard', [CashierSessionController::class, 'receptionDashboard'])->name('reception-dashboard');
    });
    
    // === ROUTES HOUSEKEEPING SPÉCIFIQUES À LA RÉCEPTION ===
    Route::prefix('housekeeping')->name('housekeeping.')->group(function () {
        // Export des disponibilités (seulement pour réception)
        Route::post('/export', [HousekeepingController::class, 'export'])->name('export');
        
        // Gestion avancée du nettoyage
        Route::post('/{room}/assign-cleaner', [HousekeepingController::class, 'assignCleaner'])->name('assign-cleaner');
        Route::post('/{room}/update-priority', [HousekeepingController::class, 'updatePriority'])->name('update-priority');
    });
});

// Routes accessibles aux femmes de chambre
Route::group(['middleware' => ['auth', 'checkRole:Super,Admin,Housekeeping']], function () {
    Route::prefix('housekeeping')->name('housekeeping.')->group(function () {
        // Dashboard femmes de chambre
        Route::get('/', [HousekeepingController::class, 'index'])->name('index');
        
        // Chambres à nettoyer
        Route::get('/to-clean', [HousekeepingController::class, 'toClean'])->name('to-clean');
        
        // Marquer comme en nettoyage
        Route::post('/{room}/start-cleaning', [HousekeepingController::class, 'startCleaning'])->name('start-cleaning');
        
        // Marquer comme nettoyée
        Route::post('/{room}/mark-cleaned', [HousekeepingController::class, 'markCleaned'])->name('mark-cleaned');
        
        // Marquer comme à inspecter
        Route::post('/{room}/mark-inspection', [HousekeepingController::class, 'markInspection'])->name('mark-inspection');
        
        // Marquer comme en maintenance
        Route::post('/{room}/mark-maintenance', [HousekeepingController::class, 'markMaintenance'])->name('mark-maintenance');
        
        // Rapports de nettoyage
        Route::get('/reports', [HousekeepingController::class, 'reports'])->name('reports');
        
        // Rapport quotidien
        Route::get('/daily-report', [HousekeepingController::class, 'dailyReport'])->name('daily-report');
        
        // Interface mobile/simplifiée pour femmes de chambre
        Route::get('/mobile', [HousekeepingController::class, 'mobile'])->name('mobile');
        
        // Liste rapide des chambres par statut
        Route::get('/quick-list/{status}', [HousekeepingController::class, 'quickList'])->name('quick-list');
        
        // Scanner QR code (pour mobile)
        Route::get('/scan', [HousekeepingController::class, 'scan'])->name('scan');
        Route::post('/scan/process', [HousekeepingController::class, 'processScan'])->name('scan.process');
        
        // Statistiques pour femmes de chambre
        Route::get('/stats', [HousekeepingController::class, 'stats'])->name('stats');
        
        // Planning de nettoyage
        Route::get('/schedule', [HousekeepingController::class, 'schedule'])->name('schedule');
        
        // MAINTENANCE - routes manquantes
        Route::get('/maintenance', [HousekeepingController::class, 'maintenance'])->name('maintenance');
        Route::get('/inspections', [HousekeepingController::class, 'inspections'])->name('inspections');
        Route::post('/{room}/complete-inspection', [HousekeepingController::class, 'completeInspection'])->name('complete-inspection');
        Route::get('/monthly-stats', [HousekeepingController::class, 'monthlyStats'])->name('monthly-stats');
        Route::get('/{room}/maintenance-form', [HousekeepingController::class, 'showMaintenanceForm'])->name('maintenance-form');
        Route::post('/{room}/end-maintenance', [HousekeepingController::class, 'endMaintenance'])->name('end-maintenance');
    });
});

// Routes d'administration
Route::get('/admin', function () {
    return redirect()->route('dashboard.index');
})->name('admin');

// ==================== ROUTES POUR DEBUG ET TEST ====================
if (env('APP_DEBUG', false)) {
    Route::get('/test-delete-customer/{id}', function($id) {
        try {
            $customer = \App\Models\Customer::find($id);
            if (!$customer) {
                return 'Customer not found';
            }
            
            $customerName = $customer->name;
            
            if ($customer->user) {
                $customer->user->delete();
            }
            
            $customer->delete();
            
            return redirect('customer')->with('success', 'Test delete successful: ' . $customerName);
            
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    })->name('test.delete.customer');
    
    Route::get('/test-route/{id}', function($id) {
        return response()->json([
            'id' => $id,
            'route_exists' => Route::has('transaction.edit'),
            'url' => route('transaction.edit', $id),
            'all_routes' => collect(Route::getRoutes())->map(function($route) {
                return [
                    'uri' => $route->uri(),
                    'name' => $route->getName(),
                    'methods' => $route->methods(),
                ];
            })->filter(function($route) {
                return str_contains($route['uri'], 'transaction');
            })->values()
        ]);
    })->name('test.route');
    
    // Route pour tester la recherche AJAX
    Route::get('/test-email-check/{email}', function($email) {
        $customer = \App\Models\Customer::where('email', $email)->first();
        if ($customer) {
            return response()->json([
                'exists' => true,
                'customer' => [
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'phone' => $customer->phone,
                    'reservation_count' => $customer->transactions()->count(),
                ]
            ]);
        }
        return response()->json(['exists' => false]);
    });
    
    // Route pour lister toutes les routes
    Route::get('/debug-routes', function() {
        $routes = collect(Route::getRoutes())->map(function($route) {
            return [
                'method' => implode('|', $route->methods()),
                'uri' => $route->uri(),
                'name' => $route->getName(),
                'action' => $route->getActionName(),
            ];
        });
        
        return response()->json($routes);
    });
    
    // Route pour tester les statuts
    Route::get('/test-status/{id}', function($id) {
        $transaction = \App\Models\Transaction::find($id);
        if (!$transaction) {
            return 'Transaction not found';
        }
        
        return response()->json([
            'id' => $transaction->id,
            'status' => $transaction->status,
            'status_label' => $transaction->status_label,
            'status_color' => $transaction->status_color,
            'status_icon' => $transaction->status_icon,
            'check_in' => $transaction->check_in,
            'check_out' => $transaction->check_out,
            'is_reservation' => $transaction->isReservation(),
            'is_active' => $transaction->isActive(),
            'is_completed' => $transaction->isCompleted(),
            'is_cancelled' => $transaction->isCancelled(),
            'can_be_cancelled' => $transaction->canBeCancelled(),
            'is_fully_paid' => $transaction->isFullyPaid(),
            'total_price' => $transaction->getTotalPrice(),
            'total_paid' => $transaction->getTotalPayment(),
            'remaining' => $transaction->getRemainingPayment(),
        ]);
    });
    
    // Route pour tester la commande automatique
    Route::get('/test-auto-status', function() {
        \Artisan::call('transactions:update-statuses');
        return response()->json([
            'output' => \Artisan::output(),
            'success' => true
        ]);
    })->name('test.auto-status');
    
    // Route pour tester la validation de paiement
    Route::get('/test-payment-validation/{id}', function($id) {
        $transaction = \App\Models\Transaction::find($id);
        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }
        
        return response()->json([
            'transaction_id' => $transaction->id,
            'can_complete' => $transaction->isFullyPaid(),
            'total_price' => $transaction->getTotalPrice(),
            'total_paid' => $transaction->getTotalPayment(),
            'remaining' => $transaction->getRemainingPayment(),
            'payment_rate' => $transaction->getPaymentRate(),
            'test_scenarios' => [
                'should_block_completed' => !$transaction->isFullyPaid() && $transaction->status === 'active',
                'should_allow_completed' => $transaction->isFullyPaid() && $transaction->status === 'active',
            ]
        ]);
    });
    
    // Route pour tester la disponibilité
    Route::get('/test-availability/{roomId}', function($roomId) {
        $room = \App\Models\Room::find($roomId);
        if (!$room) {
            return response()->json(['error' => 'Room not found'], 404);
        }
        
        $checkIn = now()->format('Y-m-d');
        $checkOut = now()->addDays(2)->format('Y-m-d');
        
        return response()->json([
            'room_id' => $room->id,
            'room_number' => $room->number,
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'is_available' => $room->isAvailableForPeriod($checkIn, $checkOut),
            'room_status' => $room->room_status_id,
            'is_occupied_today' => $room->isOccupiedOnDate(now()),
            'next_available_date' => $room->next_available_date,
            'available_periods' => $room->getAvailablePeriods(now(), now()->addDays(30), 1)
        ]);
    });
    
    // Route pour tester le check-in
    Route::get('/test-checkin/{transactionId}', function($transactionId) {
        $transaction = \App\Models\Transaction::find($transactionId);
        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }
        
        return response()->json([
            'transaction_id' => $transaction->id,
            'customer' => $transaction->customer->name,
            'room' => $transaction->room->number,
            'check_in_date' => $transaction->check_in->format('Y-m-d'),
            'check_out_date' => $transaction->check_out->format('Y-m-d'),
            'status' => $transaction->status,
            'can_be_checked_in' => $transaction->canBeCheckedIn(),
            'can_be_checked_out' => $transaction->canBeCheckedOut(),
            'is_fully_paid' => $transaction->isFullyPaid(),
            'actual_check_in' => $transaction->actual_check_in,
            'actual_check_out' => $transaction->actual_check_out,
            'room_availability' => $transaction->room->isAvailableForPeriod(
                $transaction->check_in,
                $transaction->check_out,
                $transaction->id
            )
        ]);
    });
}

// ==================== ROUTES FALLBACK (SANS VUE 404) ====================
Route::fallback(function () {
    if (auth()->check()) {
        return redirect()->route('dashboard.index')->with('error', 'Page non trouvée.');
    }
    return redirect()->route('login.index')->with('error', 'Page non trouvée. Veuillez vous connecter.');
});