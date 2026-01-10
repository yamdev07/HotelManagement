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
    
    // TRANSACTIONS - Routes CRUD complètes
    Route::prefix('transaction')->name('transaction.')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('index');
        Route::get('/create', [TransactionController::class, 'create'])->name('create');
        Route::post('/', [TransactionController::class, 'store'])->name('store');
        Route::get('/{transaction}', [TransactionController::class, 'show'])->name('show');
        Route::get('/{transaction}/edit', [TransactionController::class, 'edit'])->name('edit');
        Route::put('/{transaction}', [TransactionController::class, 'update'])->name('update');
        Route::delete('/{transaction}', [TransactionController::class, 'destroy'])->name('destroy');
        
        // Routes supplémentaires
        Route::post('/{transaction}/cancel', [TransactionController::class, 'cancel'])->name('cancel'); // CHANGÉ: POST au lieu de GET
        Route::post('/{transaction}/restore', [TransactionController::class, 'restore'])->name('restore'); // NOUVELLE ROUTE
        Route::get('/{transaction}/invoice', [TransactionController::class, 'invoice'])->name('invoice');
        Route::get('/{transaction}/history', [TransactionController::class, 'history'])->name('history');
        Route::get('/export/{type}', [TransactionController::class, 'export'])->name('export');
        
        // Routes AJAX/API
        Route::get('/{transaction}/check-availability', [TransactionController::class, 'checkAvailability'])->name('checkAvailability');
        Route::put('/{transaction}/update-status', [TransactionController::class, 'updateStatus'])->name('updateStatus');
        Route::get('/{id}/details', [TransactionController::class, 'showDetails'])->name('showDetails');
    });
    
    Route::resource('facility', FacilityController::class);

    // Paiements
    Route::prefix('transaction/{transaction}/payment')->name('transaction.payment.')->group(function () {
        Route::get('/create', [PaymentController::class, 'create'])->name('create');
        Route::post('/store', [PaymentController::class, 'store'])->name('store');
    });
    
    Route::get('/payment', [PaymentController::class, 'index'])->name('payment.index');
    Route::get('/payment/{payment}/invoice', [PaymentController::class, 'invoice'])->name('payment.invoice');

    // Charts
    Route::get('/get-dialy-guest-chart-data', [ChartController::class, 'dailyGuestPerMonth']);
    Route::get('/get-dialy-guest/{year}/{month}/{day}', [ChartController::class, 'dailyGuest'])->name('chart.dailyGuest');
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
}

// ==================== ROUTES FALLBACK (SANS VUE 404) ====================
Route::fallback(function () {
    if (auth()->check()) {
        return redirect()->route('dashboard.index')->with('error', 'Page non trouvée.');
    }
    return redirect()->route('login.index')->with('error', 'Page non trouvée. Veuillez vous connecter.');
});