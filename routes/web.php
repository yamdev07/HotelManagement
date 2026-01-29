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
use App\Http\Controllers\AuthorizationController;

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

// ==================== ROUTE LOGOUT GLOBALE ====================
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==================== ROUTE LOGOUT URGENCE ====================
Route::get('/logout-now', function() {
    try {
        $userName = auth()->check() ? auth()->user()->name : 'Utilisateur';
        
        \Illuminate\Support\Facades\Auth::logout();
        session()->flush();
        session()->invalidate();
        session()->regenerateToken();
        
        \Illuminate\Support\Facades\Cookie::queue(\Illuminate\Support\Facades\Cookie::forget('laravel_session'));
        \Illuminate\Support\Facades\Cookie::queue(\Illuminate\Support\Facades\Cookie::forget('XSRF-TOKEN'));
        
        return redirect('/login')->with('success', '✅ Déconnexion réussie. Au revoir ' . $userName . ' !');
        
    } catch (\Exception $e) {
        return redirect('/login')->with('error', 'Erreur de déconnexion: ' . $e->getMessage());
    }
})->name('logout.now');

// ==================== FORGOT PASSWORD ====================
Route::group(['middleware' => 'guest'], function () {
    Route::get('/forgot-password', fn () => view('auth.passwords.email'))->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
    Route::get('/reset-password/{token}', fn (string $token) => view('auth.reset-password', ['token' => $token]))
        ->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// ==================== ROUTES D'AUTORISATION ====================
Route::prefix('authorization')->name('authorization.')->middleware('auth')->group(function () {
    Route::get('/request/{action}/{id?}', [AuthorizationController::class, 'requestForm'])->name('request.form');
    Route::post('/request', [AuthorizationController::class, 'submitRequest'])->name('request.submit');
    Route::post('/approve', [AuthorizationController::class, 'approve'])->name('approve');
    Route::get('/pending', [AuthorizationController::class, 'pendingRequests'])->name('pending')
        ->middleware('checkrole:Super,Admin');
});

// ==================== ROUTES SUPER ADMIN SEULEMENT ====================
Route::group(['middleware' => ['auth', 'checkrole:Super']], function () {
    Route::resource('user', UserController::class);
    
    // Routes supplémentaires pour la gestion des utilisateurs
    Route::prefix('user')->name('user.')->group(function () {
        // Réinitialisation du mot de passe
        Route::post('/{user}/reset-password', [UserController::class, 'resetPassword'])
            ->name('password.reset');
        
        // Activation/désactivation du compte
        Route::patch('/{user}/toggle-status', [UserController::class, 'toggleStatus'])
            ->name('toggle.status');
        
        // Journal d'activités de l'utilisateur
        Route::get('/{user}/activity', [UserController::class, 'activity'])
            ->name('activity');
        
        // Export des utilisateurs
        Route::get('/export', [UserController::class, 'export'])
            ->name('export');
    });
});

// ==================== ROUTES ADMIN + RÉCEPTIONNISTES (AVEC RESTRICTIONS) ====================
Route::group(['middleware' => ['auth', 'checkrole:Super,Admin,Receptionist', 'admin.restrict', 'receptionist.restrict']], function () {
    
    // ==================== IMAGES ====================
    Route::post('/room/{room}/image/upload', [ImageController::class, 'store'])->name('image.store')
        ->middleware('checkrole:Super,Admin');
    Route::delete('/image/{image}', [ImageController::class, 'destroy'])->name('image.destroy')
        ->middleware('checkrole:Super,Admin');

    // ==================== ROUTE RACCOURCIE ====================
    Route::get('/createIdentity', function () {
        return redirect()->route('transaction.reservation.createIdentity');
    })->name('quick.createIdentity');

    // ==================== RÉSERVATIONS (ACCESSIBLE AUX RÉCEPTIONNISTES) ====================
    Route::prefix('transaction/reservation')->name('transaction.reservation.')->group(function () {
        Route::get('/createIdentity', [TransactionRoomReservationController::class, 'createIdentity'])->name('createIdentity');
        Route::get('/pickFromCustomer', [TransactionRoomReservationController::class, 'pickFromCustomer'])->name('pickFromCustomer');
        Route::post('/search-by-email', [TransactionRoomReservationController::class, 'searchByEmail'])->name('searchByEmail');
        Route::post('/storeCustomer', [TransactionRoomReservationController::class, 'storeCustomer'])->name('storeCustomer');
        Route::get('/{customer}/viewCountPerson', [TransactionRoomReservationController::class, 'viewCountPerson'])->name('viewCountPerson');
        Route::get('/{customer}/chooseRoom', [TransactionRoomReservationController::class, 'chooseRoom'])->name('chooseRoom');
        Route::get('/{customer}/{room}/{from}/{to}/confirmation', [TransactionRoomReservationController::class, 'confirmation'])->name('confirmation');
        Route::post('/{customer}/{room}/payDownPayment', [TransactionRoomReservationController::class, 'payDownPayment'])->name('payDownPayment');
        Route::get('/customer/{customer}/reservations', [TransactionRoomReservationController::class, 'showCustomerReservations'])->name('customerReservations');
    });

    // ==================== CLIENTS (ACCESSIBLE AUX RÉCEPTIONNISTES) ====================
    Route::resource('customer', CustomerController::class);
    
    // Suppression nécessite autorisation pour réceptionnistes
    Route::delete('/customer/{customer}', [CustomerController::class, 'destroy'])->name('customer.destroy')
        ->middleware('require.authorization');

    // ==================== TYPES DE CHAMBRES ====================
    // Lecture seulement pour réceptionnistes
    Route::get('/type', [TypeController::class, 'index'])->name('type.index');
    Route::get('/type/{type}', [TypeController::class, 'show'])->name('type.show');
    
    // CRUD complet seulement pour admins
    Route::middleware('checkrole:Super,Admin')->group(function () {
        Route::get('/type/create', [TypeController::class, 'create'])->name('type.create');
        Route::post('/type', [TypeController::class, 'store'])->name('type.store');
        Route::get('/type/{type}/edit', [TypeController::class, 'edit'])->name('type.edit');
        Route::put('/type/{type}', [TypeController::class, 'update'])->name('type.update');
        Route::delete('/type/{type}', [TypeController::class, 'destroy'])->name('type.destroy');
    });

    // ==================== CHAMBRES ====================
    // Lecture seulement pour réceptionnistes
    Route::get('/room', [RoomController::class, 'index'])->name('room.index');
    Route::get('/room/{room}', [RoomController::class, 'show'])->name('room.show');
    
    // CRUD complet seulement pour admins
    Route::middleware('checkrole:Super,Admin')->group(function () {
        Route::get('/room/create', [RoomController::class, 'create'])->name('room.create');
        Route::post('/room', [RoomController::class, 'store'])->name('room.store');
        Route::get('/room/{room}/edit', [RoomController::class, 'edit'])->name('room.edit');
        Route::put('/room/{room}', [RoomController::class, 'update'])->name('room.update');
        Route::delete('/room/{room}', [RoomController::class, 'destroy'])->name('room.destroy');
    });

    // ==================== STATUTS DE CHAMBRES ====================
    // Seulement pour admins
    Route::resource('roomstatus', RoomStatusController::class)->middleware('checkrole:Super,Admin');
    
    // ==================== TRANSACTIONS (ACCESSIBLE AUX RÉCEPTIONNISTES) ====================
    Route::prefix('transaction')->name('transaction.')->group(function () {
        // Routes de base (accessibles à tous)
        Route::get('/', [TransactionController::class, 'index'])->name('index');
        Route::get('/create', [TransactionController::class, 'create'])->name('create');
        Route::post('/', [TransactionController::class, 'store'])->name('store');
        Route::get('/{transaction}', [TransactionController::class, 'show'])->name('show');
        Route::get('/{transaction}/edit', [TransactionController::class, 'edit'])->name('edit');
        Route::put('/{transaction}', [TransactionController::class, 'update'])->name('update');
        
        // Actions critiques nécessitant autorisation
        Route::middleware('require.authorization')->group(function () {
            Route::delete('/{transaction}', [TransactionController::class, 'destroy'])->name('destroy');
            Route::delete('/{transaction}/cancel', [TransactionController::class, 'cancel'])->name('cancel');
        });
        
        // Restauration seulement pour admins
        Route::post('/{transaction}/restore', [TransactionController::class, 'restore'])->name('restore')
            ->middleware('checkrole:Super,Admin');
        
        // Routes utilitaires
        Route::get('/{transaction}/invoice', [TransactionController::class, 'invoice'])->name('invoice');
        Route::get('/{transaction}/history', [TransactionController::class, 'history'])->name('history');
        
        // Export seulement pour admins
        Route::get('/export/{type}', [TransactionController::class, 'export'])->name('export')
            ->middleware('checkrole:Super,Admin');
        
        // Gestion des statuts
        Route::put('/{transaction}/update-status', [TransactionController::class, 'updateStatus'])->name('updateStatus');
        Route::post('/{transaction}/arrived', [TransactionController::class, 'markAsArrived'])->name('mark-arrived');
        Route::post('/{transaction}/departed', [TransactionController::class, 'markAsDeparted'])->name('mark-departed');
        
        // API/AJAX
        Route::get('/{transaction}/check-payment', [TransactionController::class, 'checkPaymentStatus'])->name('check-payment');
        Route::get('/{transaction}/can-complete', [TransactionController::class, 'checkIfCanComplete'])->name('can-complete');
        Route::get('/{transaction}/check-availability', [TransactionController::class, 'checkAvailability'])->name('checkAvailability');
        Route::get('/{id}/details', [TransactionController::class, 'showDetails'])->name('showDetails');
    });

    // ==================== ÉQUIPEMENTS ====================
    // Seulement pour admins
    Route::resource('facility', FacilityController::class)->middleware('checkrole:Super,Admin');

    // ==================== PAIEMENTS (ACCESSIBLE AUX RÉCEPTIONNISTES) ====================
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('index');
        Route::get('/{payment}/invoice', [PaymentController::class, 'invoice'])->name('invoice');
        
        // Annulation nécessite autorisation
        Route::delete('/{payment}/cancel', [PaymentController::class, 'cancel'])->name('cancel')
            ->middleware('require.authorization');
        
        // Restauration et export seulement pour admins
        Route::middleware('checkrole:Super,Admin')->group(function () {
            Route::post('/{payment}/restore', [PaymentController::class, 'restore'])->name('restore');
            Route::post('/{payment}/expire', [PaymentController::class, 'markAsExpired'])->name('expire');
            Route::get('/export', [PaymentController::class, 'export'])->name('export');
        });
    });

    // ==================== PAIEMENTS POUR TRANSACTIONS ====================
    Route::prefix('transaction/{transaction}/payment')->name('transaction.payment.')->group(function () {
        Route::get('/create', [PaymentController::class, 'create'])->name('create');
        Route::post('/store', [PaymentController::class, 'store'])->name('store');
        Route::get('/check-status', [PaymentController::class, 'checkTransactionStatus'])->name('check-status');
        Route::get('/force-sync', [PaymentController::class, 'forceSync'])->name('force-sync');
    });
    
    // ==================== ALIAS PAIEMENTS ====================
    Route::get('/payment', [PaymentController::class, 'index'])->name('payment.index');
    Route::get('/payment/{payment}/invoice', [PaymentController::class, 'invoice'])->name('payment.invoice');

    // ==================== CHARTS ====================
    Route::get('/get-dialy-guest-chart-data', [ChartController::class, 'dailyGuestPerMonth']);
    Route::get('/get-dialy-guest/{year}/{month}/{day}', [ChartController::class, 'dailyGuest'])->name('chart.dailyGuest');
    
    // ==================== CAISSE (ACCESSIBLE AUX RÉCEPTIONNISTES) ====================
    Route::prefix('cashier')->name('cashier.')->group(function () {
        Route::get('/sessions', [CashierSessionController::class, 'index'])->name('sessions.index');
        Route::get('/sessions/create', [CashierSessionController::class, 'create'])->name('sessions.create');
        Route::post('/sessions', [CashierSessionController::class, 'store'])->name('sessions.store');
        Route::get('/sessions/{cashierSession}', [CashierSessionController::class, 'show'])->name('sessions.show');
        Route::put('/sessions/{cashierSession}/close', [CashierSessionController::class, 'close'])->name('sessions.close');
        
        Route::get('/live-stats', [CashierSessionController::class, 'liveStats'])->name('live-stats');

        // Suppression seulement pour admins
        Route::delete('/sessions/{cashierSession}', [CashierSessionController::class, 'destroy'])->name('sessions.destroy')
            ->middleware('checkrole:Super,Admin');
        
        Route::get('/report/{cashierSession}', [CashierSessionController::class, 'report'])->name('sessions.report');
        Route::get('/daily-report', [CashierSessionController::class, 'dailyReport'])->name('daily-report');
        Route::get('/dashboard', [CashierSessionController::class, 'dashboard'])->name('dashboard');
        Route::get('/current-session', [CashierSessionController::class, 'getCurrentSession'])->name('current-session');
        Route::get('/session-summary', [CashierSessionController::class, 'sessionSummary'])->name('session-summary');
    });
});

// ==================== ROUTES POUR TOUS LES UTILISATEURS AUTHENTIFIÉS ====================
Route::group(['middleware' => ['auth', 'checkrole:Super,Admin,Customer,Housekeeping,Receptionist']], function () {
    // ==================== DASHBOARD ====================
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
        Route::get('/data', [DashboardController::class, 'getDashboardData'])->name('data');
        Route::get('/stats', [DashboardController::class, 'updateStats'])->name('stats');
        Route::get('/debug', [DashboardController::class, 'debug'])->name('debug');
    });
    
    Route::get('/home', function () {
        return redirect()->route('dashboard.index');
    })->name('home');
    
    // ==================== ACTIVITY LOG ====================
    Route::prefix('activity')->name('activity.')->group(function () {
        Route::get('/', [ActivityController::class, 'index'])->name('index');
        Route::get('/all', [ActivityController::class, 'all'])->name('all'); // Changé ici
        Route::get('/{id}', [ActivityController::class, 'show'])->name('show');
        Route::get('/{id}/details', [ActivityController::class, 'getDetails'])->name('details');
        Route::get('/export/{format?}', [ActivityController::class, 'export'])->name('export');
        Route::post('/cleanup', [ActivityController::class, 'cleanup'])->name('cleanup');
        Route::get('/statistics', [ActivityController::class, 'statistics'])->name('statistics');
    });
    
    // ==================== NOTIFICATIONS ====================
    Route::view('/notification', 'notification.index')->name('notification.index');
    Route::get('/mark-all-as-read', [NotificationsController::class, 'markAllAsRead'])->name('notification.markAllAsRead');
    Route::get('/notification-to/{id}', [NotificationsController::class, 'routeTo'])->name('notification.routeTo');
    
    // ==================== PROFIL ====================
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::post('/update-info', [ProfileController::class, 'updateInfo'])->name('update.info');
        Route::post('/update-password', [ProfileController::class, 'updatePassword'])->name('update.password');
        Route::post('/update-avatar', [ProfileController::class, 'updateAvatar'])->name('update.avatar');
        
        // Ajout pour la compatibilité avec votre vue
        Route::get('/{user?}', function ($user = null) {
            if ($user) {
                // Redirection vers la vue show si un ID est fourni
                return app()->make(UserController::class)->show($user);
            }
            return redirect()->route('profile.index');
        })->name('profile.show');
    });
    
    // ==================== RAPPORTS ====================
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    
    // ==================== RÉSERVATIONS CLIENTS ====================
    Route::get('/my-reservations', [TransactionController::class, 'myReservations'])->name('transaction.myReservations')
        ->middleware('checkrole:Customer');
    
    Route::get('/my-transaction/{transaction}', [TransactionController::class, 'show'])->name('transaction.show.customer')
        ->middleware('checkrole:Customer');
    
    // ==================== RESTAURANT (ACCESSIBLE À TOUS) ====================
    Route::prefix('restaurant')->name('restaurant.')->group(function () {
        Route::get('/', [RestaurantController::class, 'index'])->name('index');
        Route::get('/orders', [RestaurantController::class, 'orders'])->name('orders');
        Route::get('/orders/{id}', [RestaurantController::class, 'showOrder'])->name('orders.show');
        Route::post('/orders', [RestaurantController::class, 'storeOrder'])->name('orders.store');
        Route::put('/orders/{id}', [RestaurantController::class, 'updateOrder'])->name('orders.update');
        
        // Annulation de commande nécessite autorisation pour réceptionnistes
        Route::put('/orders/{id}/cancel', [RestaurantController::class, 'cancelOrder'])->name('orders.cancel')
            ->middleware('require.authorization');
        
        // API AJAX
        Route::get('/api/customers', [RestaurantController::class, 'getCustomers'])->name('api.customers');
        Route::get('/api/menus', [RestaurantController::class, 'getMenus'])->name('api.menus');
        
        // Gestion des menus seulement pour admins
        Route::middleware('checkrole:Super,Admin,Receptionist')->group(function () {
            Route::get('/create', [RestaurantController::class, 'create'])->name('create');
            Route::post('/store', [RestaurantController::class, 'store'])->name('store');
            Route::delete('/menus/{id}', [RestaurantController::class, 'destroy'])->name('menus.destroy');
        });
    });
});

// ==================== DISPONIBILITÉ DES CHAMBRES ====================
Route::group(['middleware' => ['auth', 'checkrole:Super,Admin,Customer,Housekeeping,Receptionist']], function () {
    Route::prefix('availability')->name('availability.')->group(function () {
        Route::get('/dashboard', [AvailabilityController::class, 'dashboard'])->name('dashboard');
        Route::get('/search', [AvailabilityController::class, 'search'])->name('search');
        Route::get('/calendar', [AvailabilityController::class, 'calendar'])->name('calendar');
        Route::get('/inventory', [AvailabilityController::class, 'inventory'])->name('inventory');
        Route::get('/room/{room}', [AvailabilityController::class, 'roomDetail'])->name('room.detail');
        Route::get('/room/{room}/conflicts', [AvailabilityController::class, 'showConflicts'])->name('room.conflicts');
        
        // Réserver sans conflit - avec restrictions
        Route::post('/reserve-without-conflict', [AvailabilityController::class, 'reserveWithoutConflict'])
            ->name('reserve.without.conflict')
            ->middleware('checkrole:Super,Admin,Receptionist');
        
        // API AJAX
        Route::get('/check-availability', [AvailabilityController::class, 'checkAvailability'])->name('check.availability');
        Route::get('/calendar-cell-details', [AvailabilityController::class, 'calendarCellDetails'])->name('calendar.cell.details');
        
        // Export seulement pour admins
        Route::post('/export', [AvailabilityController::class, 'export'])->name('export')
            ->middleware('checkrole:Super,Admin');
    });
});

// ==================== CHECK-IN AVANCÉ (RÉCEPTIONNISTES + ADMINS) ====================
Route::group(['middleware' => ['auth', 'checkrole:Super,Admin,Receptionist']], function () {
    Route::prefix('checkin')->name('checkin.')->group(function () {
        Route::get('/', [CheckInController::class, 'index'])->name('index');
        Route::get('/search', [CheckInController::class, 'search'])->name('search');
        Route::get('/direct', [CheckInController::class, 'directCheckIn'])->name('direct');
        Route::post('/process-direct-checkin', [CheckInController::class, 'processDirectCheckIn'])->name('process-direct-checkin');
        Route::get('/{transaction}', [CheckInController::class, 'show'])->name('show');
        Route::post('/{transaction}', [CheckInController::class, 'store'])->name('store');
        Route::post('/{transaction}/quick', [CheckInController::class, 'quickCheckIn'])->name('quick');
        Route::get('/availability/check', [CheckInController::class, 'checkAvailability'])->name('availability');
    });
    
    Route::get('/checkin-dashboard', [DashboardController::class, 'checkinDashboard'])->name('checkin.dashboard');
    
    // ==================== ACTIONS RAPIDES RÉCEPTION ====================
    Route::prefix('transaction')->name('transaction.')->group(function () {
        Route::post('/{transaction}/check-in', function($transaction) {
            return app(TransactionController::class)->markAsArrived($transaction);
        })->name('check-in');
        
        Route::post('/{transaction}/check-out', function($transaction) {
            return app(TransactionController::class)->markAsDeparted($transaction);
        })->name('check-out');
        
        Route::get('/reception/today', [TransactionController::class, 'index'])->name('reception.today')
            ->defaults('view', 'reception');
    });
    
    // ==================== CAISSE RÉCEPTION ====================
    Route::prefix('cashier')->name('cashier.')->group(function () {
        Route::get('/open-session', [CashierSessionController::class, 'openSession'])->name('open-session');
        Route::post('/start-session', [CashierSessionController::class, 'startSession'])->name('start-session');
        Route::post('/close-session/{cashierSession}', [CashierSessionController::class, 'closeSession'])->name('close-session');
        Route::get('/my-sessions', [CashierSessionController::class, 'mySessions'])->name('my-sessions');
        Route::get('/session-report/{cashierSession}', [CashierSessionController::class, 'sessionReport'])->name('session-report');
        Route::get('/reception-dashboard', [CashierSessionController::class, 'receptionDashboard'])->name('reception-dashboard');
    });
    
    // ==================== HOUSEKEEPING POUR RÉCEPTION ====================
    Route::prefix('housekeeping')->name('housekeeping.')->group(function () {
        // Lecture seulement pour réceptionnistes
        Route::get('/', [HousekeepingController::class, 'index'])->name('index');
        Route::get('/to-clean', [HousekeepingController::class, 'to-clean'])->name('to-clean');
        Route::get('/reports', [HousekeepingController::class, 'reports'])->name('reports');
        Route::get('/daily-report', [HousekeepingController::class, 'dailyReport'])->name('daily-report');
        Route::get('/mobile', [HousekeepingController::class, 'mobile'])->name('mobile');
        Route::get('/quick-list/{status}', [HousekeepingController::class, 'quickList'])->name('quick-list');
        Route::get('/scan', [HousekeepingController::class, 'scan'])->name('scan');
        Route::post('/scan/process', [HousekeepingController::class, 'processScan'])->name('scan.process');
        Route::get('/stats', [HousekeepingController::class, 'stats'])->name('stats');
        Route::get('/schedule', [HousekeepingController::class, 'schedule'])->name('schedule');
        Route::get('/maintenance', [HousekeepingController::class, 'maintenance'])->name('maintenance');
        Route::get('/inspections', [HousekeepingController::class, 'inspections'])->name('inspections');
        Route::get('/monthly-stats', [HousekeepingController::class, 'monthlyStats'])->name('monthly-stats');
        
        // Export seulement pour admins
        Route::post('/export', [HousekeepingController::class, 'export'])->name('export')
            ->middleware('checkrole:Super,Admin');
        
        // Actions seulement pour housekeeping staff
        Route::middleware('checkrole:Super,Admin,Housekeeping')->group(function () {
            Route::post('/{room}/start-cleaning', [HousekeepingController::class, 'startCleaning'])->name('start-cleaning');
            Route::post('/{room}/mark-cleaned', [HousekeepingController::class, 'markCleaned'])->name('mark-cleaned');
            Route::post('/{room}/mark-inspection', [HousekeepingController::class, 'markInspection'])->name('mark-inspection');
            Route::post('/{room}/mark-maintenance', [HousekeepingController::class, 'markMaintenance'])->name('mark-maintenance');
            Route::post('/{room}/complete-inspection', [HousekeepingController::class, 'completeInspection'])->name('complete-inspection');
            Route::get('/{room}/maintenance-form', [HousekeepingController::class, 'showMaintenanceForm'])->name('maintenance-form');
            Route::post('/{room}/end-maintenance', [HousekeepingController::class, 'endMaintenance'])->name('end-maintenance');
        });
        
        // Gestion avancée seulement pour admins
        Route::middleware('checkrole:Super,Admin')->group(function () {
            Route::post('/{room}/assign-cleaner', [HousekeepingController::class, 'assignCleaner'])->name('assign-cleaner');
            Route::post('/{room}/update-priority', [HousekeepingController::class, 'updatePriority'])->name('update-priority');
        });
    });
});

// ==================== HOUSEKEEPING STAFF SEULEMENT ====================
Route::group(['middleware' => ['auth', 'checkrole:Super,Admin,Housekeeping']], function () {
    // Routes déjà définies ci-dessus dans la section réception
});

// ==================== ROUTE ADMIN ====================
Route::get('/admin', function () {
    return redirect()->route('dashboard.index');
})->name('admin');

// ==================== ROUTES DEBUG ====================
if (env('APP_DEBUG', false)) {
    Route::get('/test-delete-customer/{id}', function($id) {
        try {
            $customer = \App\Models\Customer::find($id);
            if (!$customer) return 'Customer not found';
            
            $customerName = $customer->name;
            if ($customer->user) $customer->user->delete();
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
    
    Route::get('/test-status/{id}', function($id) {
        $transaction = \App\Models\Transaction::find($id);
        if (!$transaction) return 'Transaction not found';
        
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
    
    Route::get('/test-auto-status', function() {
        \Artisan::call('transactions:update-statuses');
        return response()->json([
            'output' => \Artisan::output(),
            'success' => true
        ]);
    })->name('test.auto-status');
    
    Route::get('/test-payment-validation/{id}', function($id) {
        $transaction = \App\Models\Transaction::find($id);
        if (!$transaction) return response()->json(['error' => 'Transaction not found'], 404);
        
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
    
    Route::get('/test-availability/{roomId}', function($roomId) {
        $room = \App\Models\Room::find($roomId);
        if (!$room) return response()->json(['error' => 'Room not found'], 404);
        
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
    
    Route::get('/test-checkin/{transactionId}', function($transactionId) {
        $transaction = \App\Models\Transaction::find($transactionId);
        if (!$transaction) return response()->json(['error' => 'Transaction not found'], 404);
        
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

    Route::get('/test-permissions', function() {
        $user = auth()->user();
        if (!$user) return "Non connecté";
        
        $tests = [
            'admin_cannot_delete' => route('transaction.destroy', 1),
            'receptionist_cannot_access_users' => route('user.index'),
            'housekeeping_read_only' => route('room.edit', 1),
        ];
        
        return view('test-permissions', [
            'user' => $user,
            'tests' => $tests
        ]);
    })->middleware('auth');
    
    Route::get('/test-simple-conflicts/{room}', [AvailabilityController::class, 'showConflictsSimple'])
        ->name('test.simple.conflicts');
    
    Route::get('/test-receptionist-access', function() {
        $user = auth()->user();
        if (!$user) return "Non connecté";
        
        $routes = [
            'transaction.index' => Route::has('transaction.index'),
            'transaction.create' => Route::has('transaction.create'),
            'restaurant.index' => Route::has('restaurant.index'),
            'housekeeping.index' => Route::has('housekeeping.index'),
            'customer.index' => Route::has('customer.index'),
            'availability.dashboard' => Route::has('availability.dashboard'),
        ];
        
        return response()->json([
            'user_role' => $user->role,
            'routes' => $routes,
            'can_access_transaction' => in_array($user->role, ['Super', 'Admin', 'Receptionist']),
            'can_access_restaurant' => in_array($user->role, ['Super', 'Admin', 'Receptionist', 'Customer', 'Housekeeping']),
            'can_access_housekeeping_view' => in_array($user->role, ['Super', 'Admin', 'Receptionist', 'Housekeeping']),
        ]);
    })->middleware('auth');
}

// ==================== ROUTE D'URGENCE ====================
Route::get('/force-logout-all', function() {
    \Illuminate\Support\Facades\Auth::logout();
    session()->flush();
    session()->invalidate();
    session()->regenerateToken();
    \Illuminate\Support\Facades\Cookie::queue(\Illuminate\Support\Facades\Cookie::forget('laravel_session'));
    \Illuminate\Support\Facades\Cookie::queue(\Illuminate\Support\Facades\Cookie::forget('XSRF-TOKEN'));
    return redirect('/login')->with('success', 'Déconnexion forcée réussie.');
});

// ==================== ROUTE FALLBACK ====================
Route::fallback(function () {
    if (auth()->check()) {
        return redirect()->route('dashboard.index')->with('error', 'Page non trouvée.');
    }
    return redirect()->route('login.index')->with('error', 'Page non trouvée. Veuillez vous connecter.');
});