<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Payment;
use App\Models\Room;
use App\Models\Customer;
use App\Models\ReceptionistSession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // ================================================
        // 1. PARAMÈTRES DE PÉRIODE
        // ================================================
        $period = $request->get('period', 'month');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        
        if ($period === 'custom' && $dateFrom && $dateTo) {
            $startDate = Carbon::parse($dateFrom)->startOfDay();
            $endDate = Carbon::parse($dateTo)->endOfDay();
        } else {
            switch ($period) {
                case 'today':
                    $startDate = Carbon::today();
                    $endDate = Carbon::today();
                    break;
                case 'yesterday':
                    $startDate = Carbon::yesterday();
                    $endDate = Carbon::yesterday();
                    break;
                case 'week':
                    $startDate = Carbon::now()->startOfWeek();
                    $endDate = Carbon::now()->endOfWeek();
                    break;
                case 'quarter':
                    $startDate = Carbon::now()->startOfQuarter();
                    $endDate = Carbon::now()->endOfQuarter();
                    break;
                case 'year':
                    $startDate = Carbon::now()->startOfYear();
                    $endDate = Carbon::now()->endOfYear();
                    break;
                case 'month':
                default:
                    $startDate = Carbon::now()->startOfMonth();
                    $endDate = Carbon::now()->endOfMonth();
                    break;
            }
        }
        
        $periodLabel = $this->getPeriodLabel($period, $startDate, $endDate);

        // ================================================
        // 2. STATISTIQUES GÉNÉRALES
        // ================================================
        
        // Chiffre d'affaires total (transactions terminées)
        $totalRevenue = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('total_price');
        
        // Paiements
        $payments = Payment::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->select('payment_method', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('payment_method')
            ->get();
        
        $totalPaymentsAmount = $payments->sum('total');
        $paymentsCount = $payments->sum('count');
        $averagePayment = $paymentsCount > 0 ? $totalPaymentsAmount / $paymentsCount : 0;
        
        // Transactions
        $totalTransactions = Transaction::whereBetween('created_at', [$startDate, $endDate])->count();
        $completedTransactions = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->count();
        
        // Nuitées et séjours
        $transactions = Transaction::whereBetween('created_at', [$startDate, $endDate])->get();
        $totalNights = 0;
        foreach ($transactions as $t) {
            $checkIn = Carbon::parse($t->check_in);
            $checkOut = Carbon::parse($t->check_out);
            $totalNights += $checkIn->diffInDays($checkOut);
        }
        
        $averageStayLength = $transactions->count() > 0 
            ? round($totalNights / $transactions->count(), 1) 
            : 0;
        
        $averageNightRate = $totalNights > 0 
            ? $totalRevenue / $totalNights 
            : 0;

        // ================================================
        // 3. STATISTIQUES D'OCCUPATION
        // ================================================
        
        $totalRooms = Room::count();
        $occupiedRooms = Room::where('room_status_id', 2)->count(); // STATUS_OCCUPIED = 2
        $availableRooms = Room::where('room_status_id', 1)->count(); // STATUS_AVAILABLE = 1
        $maintenanceRooms = Room::where('room_status_id', 3)->count(); // STATUS_MAINTENANCE = 3
        $reservedRooms = Room::where('room_status_id', 4)->count(); // STATUS_RESERVED = 4
        $cleaningRooms = Room::where('room_status_id', 5)->count(); // STATUS_CLEANING = 5
        $dirtyRooms = Room::where('room_status_id', 6)->count(); // STATUS_DIRTY = 6
        
        $occupancyRate = $totalRooms > 0 
            ? round((($occupiedRooms + $reservedRooms) / $totalRooms) * 100, 1)
            : 0;
        
        $revPAR = $totalRooms > 0 
            ? $totalRevenue / $totalRooms 
            : 0;

        // ================================================
        // 4. DONNÉES POUR LES GRAPHIQUES
        // ================================================
        
        // Revenue Chart
        $revenueData = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_price) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        $revenueChartLabels = $revenueData->pluck('date')->map(function ($date) {
            return Carbon::parse($date)->format('d/m');
        });
        $revenueChartData = $revenueData->pluck('total');

        // Payment Methods Chart
        $paymentChartLabels = [];
        $paymentChartData = [];
        $paymentSummary = [];
        
        $paymentMethodConfig = [
            'cash' => ['label' => 'Espèces', 'icon' => 'fas fa-money-bill-wave text-success', 'color' => '#28a745'],
            'card' => ['label' => 'Carte bancaire', 'icon' => 'fas fa-credit-card text-danger', 'color' => '#dc3545'],
            'mobile_money' => ['label' => 'Mobile Money', 'icon' => 'fas fa-mobile-alt text-info', 'color' => '#17a2b8'],
            'transfer' => ['label' => 'Virement', 'icon' => 'fas fa-university text-warning', 'color' => '#ffc107'],
            'fedapay' => ['label' => 'Fedapay', 'icon' => 'fas fa-bolt text-purple', 'color' => '#6f42c1'],
            'check' => ['label' => 'Chèque', 'icon' => 'fas fa-money-check text-orange', 'color' => '#fd7e14'],
        ];
        
        foreach ($paymentMethodConfig as $key => $config) {
            $payment = $payments->firstWhere('payment_method', $key);
            $amount = $payment ? $payment->total : 0;
            $count = $payment ? $payment->count : 0;
            $percentage = $totalPaymentsAmount > 0 ? round(($amount / $totalPaymentsAmount) * 100, 1) : 0;
            $average = $count > 0 ? $amount / $count : 0;
            
            if ($amount > 0) {
                $paymentChartLabels[] = $config['label'];
                $paymentChartData[] = $amount;
            }
            
            $paymentSummary[] = [
                'label' => $config['label'],
                'icon' => $config['icon'],
                'amount' => $amount,
                'count' => $count,
                'percentage' => $percentage,
                'average' => $average,
            ];
        }

        // Room Status Chart
        $roomStatusData = [$availableRooms, $occupiedRooms, $maintenanceRooms, $reservedRooms, $cleaningRooms, $dirtyRooms];
        $roomStatusLabels = ['Disponible', 'Occupée', 'Maintenance', 'Réservée', 'Nettoyage', 'Sale'];

        // Checkout Times Chart
        $checkouts = Transaction::whereBetween('actual_check_out', [$startDate, $endDate])
            ->whereNotNull('actual_check_out')
            ->get();
        
        $beforeNoon = 0;
        $betweenNoonAndTwo = 0;
        $afterTwo = 0;
        
        foreach ($checkouts as $checkout) {
            $time = Carbon::parse($checkout->actual_check_out)->format('H:i');
            if ($time < '12:00') {
                $beforeNoon++;
            } elseif ($time >= '12:00' && $time <= '14:00') {
                $betweenNoonAndTwo++;
            } else {
                $afterTwo++;
            }
        }
        
        $checkoutTimesData = [$beforeNoon, $betweenNoonAndTwo, $afterTwo];

        // ================================================
        // 5. TOP PERFORMERS
        // ================================================
        
        // Top 5 rooms
        $topRooms = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->select('room_id', DB::raw('SUM(total_price) as revenue'), DB::raw('COUNT(*) as nights'))
            ->groupBy('room_id')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                $room = Room::with('type')->find($item->room_id);
                return [
                    'number' => $room->number ?? 'N/A',
                    'name' => $room->name ?? '',
                    'type' => $room->type->name ?? 'N/A',
                    'nights' => $item->nights,
                    'revenue' => $item->revenue,
                ];
            });

        // Top 5 customers
        $topCustomers = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->select('customer_id', DB::raw('SUM(total_price) as spent'), DB::raw('COUNT(*) as stays'), DB::raw('SUM(DATEDIFF(check_out, check_in)) as nights'))
            ->groupBy('customer_id')
            ->orderByDesc('spent')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                $customer = Customer::find($item->customer_id);
                return [
                    'name' => $customer->name ?? 'N/A',
                    'email' => $customer->email ?? '',
                    'stays' => $item->stays,
                    'nights' => $item->nights,
                    'spent' => $item->spent,
                ];
            });

        // ================================================
        // 6. RECEPTIONIST PERFORMANCE
        // ================================================
        
        $receptionists = User::whereIn('role', ['Receptionist'])->get();
        $receptionistPerformance = [];
        
        foreach ($receptionists as $recep) {
            $sessions = ReceptionistSession::where('user_id', $recep->id)
                ->whereBetween('login_time', [$startDate, $endDate])
                ->get();
            
            $totalAmount = $sessions->sum('total_transactions_amount');
            $totalCheckins = $sessions->sum('checkins_count');
            $totalCheckouts = $sessions->sum('checkouts_count');
            $totalReservations = $sessions->sum('reservations_count');
            
            // Productivité = moyenne des scores de productivité
            $productivitySum = 0;
            $productivityCount = 0;
            foreach ($sessions as $session) {
                $metrics = json_decode($session->performance_metrics, true);
                if (isset($metrics['productivity_score'])) {
                    $productivitySum += $metrics['productivity_score'];
                    $productivityCount++;
                }
            }
            $avgProductivity = $productivityCount > 0 ? round($productivitySum / $productivityCount) : 0;
            
            $receptionistPerformance[] = [
                'name' => $recep->name,
                'sessions' => $sessions->count(),
                'checkins' => $totalCheckins,
                'checkouts' => $totalCheckouts,
                'reservations' => $totalReservations,
                'total' => $totalAmount,
                'productivity' => $avgProductivity,
            ];
        }

        // ================================================
        // 7. RECENT TRANSACTIONS
        // ================================================
        
        $recentTransactions = Transaction::with(['customer', 'room'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // ================================================
        // 8. RETOUR VUE
        // ================================================
        
        return view('reports.index', compact(
            'periodLabel',
            'totalRevenue',
            'totalPaymentsAmount',
            'paymentsCount',
            'averagePayment',
            'totalNights',
            'averageStayLength',
            'averageNightRate',
            'totalRooms',
            'occupiedRooms',
            'availableRooms',
            'occupancyRate',
            'revPAR',
            'revenueChartLabels',
            'revenueChartData',
            'paymentChartLabels',
            'paymentChartData',
            'paymentSummary',
            'roomStatusData',
            'roomStatusLabels',
            'checkoutTimesData',
            'topRooms',
            'topCustomers',
            'receptionistPerformance',
            'recentTransactions'
        ));
    }

    /**
     * Obtenir le libellé de la période
     */
    private function getPeriodLabel($period, $startDate, $endDate)
    {
        if ($startDate->format('Y-m-d') === $endDate->format('Y-m-d')) {
            return $startDate->format('d/m/Y');
        }
        
        $labels = [
            'today' => 'Aujourd\'hui',
            'yesterday' => 'Hier',
            'week' => 'Cette semaine',
            'month' => 'Ce mois',
            'quarter' => 'Ce trimestre',
            'year' => 'Cette année',
            'custom' => $startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y'),
        ];
        
        return $labels[$period] ?? $startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y');
    }
}