<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Menu;
use App\Models\RestaurantOrder;
use App\Models\RestaurantOrderItem;
use App\Models\Room;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RestaurantController extends Controller
{
    // Afficher tous les menus
    public function index()
    {
        $menus = Menu::paginate(12);
        $allMenus = Menu::all(); // Pour le modal (panier)
        $customers = Customer::all();

        return view('restaurant.index', compact('menus', 'allMenus', 'customers'));
    }

    // Afficher le formulaire de création
    public function create()
    {
        $totalMenus = Menu::count();
        $lastAdded = Menu::latest()->first();

        return view('restaurant.create', compact('totalMenus', 'lastAdded'));
    }

    // Enregistrer un nouveau menu
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('menus', 'public');
            $validated['image'] = $path;
        }

        Menu::create($validated);

        return redirect()->route('restaurant.index')
            ->with('success', 'Menu ajouté avec succès!');
    }

    // Afficher le formulaire de modification
    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        return view('restaurant.edit', compact('menu'));
    }

    // Mettre à jour un menu existant
    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('menus', 'public');
            $validated['image'] = $path;
        }

        $menu->update($validated);

        return redirect()->route('restaurant.index')
            ->with('success', 'Menu modifié avec succès!');
    }

    // Afficher toutes les commandes
    public function orders(Request $request)
    {
        $query = RestaurantOrder::with(['customer', 'items.menu'])
            ->orderBy('created_at', 'desc');

        // Filtrage par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtrage par date de début
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        // Filtrage par date de fin
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $orders = $query->paginate(15)->withQueryString();

        $customers = Customer::all();
        $menus = Menu::all();

        // Statistiques du jour
        $todayRevenue = RestaurantOrder::whereDate('created_at', today())
            ->whereIn('status', ['paid', 'delivered']) // ou juste 'paid', à adapter
            ->sum('total');
            
        $todayRoomRevenue = RestaurantOrder::whereDate('created_at', today())
            ->whereIn('status', ['paid', 'delivered'])
            ->whereNotNull('room_id')
            ->sum('total');
            
        $todayNoRoomRevenue = RestaurantOrder::whereDate('created_at', today())
            ->whereIn('status', ['paid', 'delivered'])
            ->whereNull('room_id')
            ->sum('total');

        // Nombres de commandes par statut (seulement du jour ou en cours)
        $pendingOrders = RestaurantOrder::whereDate('created_at', today())->where('status', 'pending')->count();
        $preparingOrders = RestaurantOrder::whereDate('created_at', today())->where('status', 'preparing')->count();
        $deliveredOrders = RestaurantOrder::whereDate('created_at', today())->where('status', 'delivered')->count();
        $paidOrders = RestaurantOrder::whereDate('created_at', today())->where('status', 'paid')->count();
        $cancelledOrders = RestaurantOrder::whereDate('created_at', today())->where('status', 'cancelled')->count();

        // Répartition volumétrique
        $todayOrdersTotal = RestaurantOrder::whereDate('created_at', today())->count();
        $todayOrdersRoom = RestaurantOrder::whereDate('created_at', today())->whereNotNull('room_id')->count();
        $todayOrdersNoRoom = RestaurantOrder::whereDate('created_at', today())->whereNull('room_id')->count();

        return view('restaurant.orders', compact(
            'orders', 'customers', 'menus',
            'todayRevenue', 'todayRoomRevenue', 'todayNoRoomRevenue',
            'pendingOrders', 'preparingOrders', 'deliveredOrders', 'paidOrders', 'cancelledOrders',
            'todayOrdersTotal', 'todayOrdersRoom', 'todayOrdersNoRoom'
        ));
    }

    // Afficher les détails d'une commande
    public function showOrder($id)
    {
        $order = RestaurantOrder::with(['items.menu', 'customer'])->findOrFail($id);

        return response()->json([
            'html' => view('restaurant.partials.order-details', compact('order'))->render(),
        ]);
    }

    // Enregistrer une nouvelle commande
    public function storeOrder(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'customer_id' => 'nullable|exists:customers,id',
            'room_number' => 'nullable|string',
            'items' => 'required|json',
            'total' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'payment_method' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $customerId = $validated['customer_id'] ?? null;

            // Si pas de customer_id fourni, essayer de trouver ou créer un client temporaire
            if (!$customerId && ($validated['customer_name'] || $validated['phone'])) {
                $customer = $this->findOrCreateCustomer($validated);
                $customerId = $customer->id;
            }

            $transactionId = $this->resolveRestaurantOrderTransaction(
                $customerId,
                $validated['room_number'] ?? null
            );

            $items = json_decode($validated['items'], true);
            $calculatedTotal = 0;

            foreach ($items as $item) {
                $menu = Menu::find($item['menu_id']);
                if (! $menu) {
                    continue;
                }

                $quantity = max(1, (int) ($item['quantity'] ?? 1));
                $calculatedTotal += $menu->price * $quantity;
            }

            $order = RestaurantOrder::create([
                'customer_id' => $customerId,
                'room_id' => $this->getRoomIdFromNumber($validated['room_number'] ?? null),
                'transaction_id' => $transactionId,
                'total' => $calculatedTotal,
                'notes' => $validated['notes'] ?? null,
                'payment_method' => $validated['payment_method'] ?? 'cash',
                'status' => 'pending',
            ]);

            foreach ($items as $item) {
                $menu = Menu::find($item['menu_id']);
                if (! $menu) {
                    continue;
                }

                RestaurantOrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $item['menu_id'],
                    'quantity' => max(1, (int) ($item['quantity'] ?? 1)),
                    'price' => $menu->price,
                ]);
            }

            if ($transactionId) {
                $transaction = Transaction::find($transactionId);
                if ($transaction) {
                    $transaction->update(['total_price' => $transaction->getTotalPrice()]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Commande enregistrée avec succès!',
                'order_id' => $order->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la commande: ' . $e->getMessage()
            ], 500);
        }
    }

    // Mettre à jour une commande (statut)
    public function updateOrder(Request $request, $id)
    {
        $order = RestaurantOrder::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,preparing,delivered,paid,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return response()->json(['success' => true]);
    }

    // Annuler une commande
    public function cancelOrder($id)
    {
        $order = RestaurantOrder::findOrFail($id);
        $order->update(['status' => 'cancelled']);

        return response()->json(['success' => true]);
    }

    // Supprimer un menu
    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();

        return response()->json(['success' => true]);
    }

    // API - Obtenir les clients
    public function getCustomers()
    {
        $customers = Customer::with(['transactions' => function ($q) {
                $q->whereIn('status', ['active', 'reservation'])
                  ->where('check_out', '>=', now())
                  ->with('room')
                  ->latest();
            }])
            ->get()
            ->map(function ($customer) {
                $activeTransaction = $customer->transactions->first();
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'room_number' => $activeTransaction?->room?->number ?? null,
                ];
            });

        return response()->json($customers);
    }

    // API - Obtenir les menus
    public function getMenus()
    {
        $menus = Menu::select('id', 'name', 'price', 'image', 'category')->get();

        return response()->json($menus);
    }

    // Méthode utilitaire pour obtenir l'ID de la chambre
    private function getRoomIdFromNumber($roomNumber)
    {
        if (! $roomNumber) {
            return null;
        }

        $room = Room::where('number', $roomNumber)->first();

        return $room ? $room->id : null;
    }

    private function resolveRestaurantOrderTransaction($customerId = null, $roomNumber = null)
    {
        if ($customerId) {
            $customer = Customer::find($customerId);

            if ($customer) {
                $transaction = $customer->currentTransaction;
                if ($transaction) {
                    return $transaction->id;
                }

                $transaction = $customer->activeTransactions()->latest()->first();
                if ($transaction) {
                    return $transaction->id;
                }

                $transaction = Transaction::where('customer_id', $customerId)
                    ->whereIn('status', ['active', 'pending_checkout', 'completed'])
                    ->latest()
                    ->first();

                if ($transaction) {
                    return $transaction->id;
                }
            }
        }

        if ($roomNumber) {
            $room = Room::where('number', $roomNumber)->first();
            if ($room) {
                $transaction = Transaction::where('room_id', $room->id)
                    ->whereIn('status', ['active', 'pending_checkout'])
                    ->where('check_out', '>=', now())
                    ->latest()
                    ->first();

                if ($transaction) {
                    return $transaction->id;
                }
            }
        }

        return null;
    }



    /* ─────────────────────────────────────────────────────────
     *  SUIVI DES VENTES
     * ───────────────────────────────────────────────────────── */

    public function sales()
    {
        // Top 10 items les plus vendus
        $topItems = RestaurantOrderItem::with('menu')
            ->select('menu_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(quantity * price) as total_revenue'))
            ->whereHas('order', fn($q) => $q->whereIn('status', ['delivered', 'paid']))
            ->groupBy('menu_id')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        // Revenus des 7 derniers jours
        $dailyRevenue = RestaurantOrder::whereIn('status', ['delivered', 'paid'])
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as revenue'), DB::raw('COUNT(*) as nb_orders'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Revenus par mois (12 derniers mois)
        $monthlyRevenue = RestaurantOrder::whereIn('status', ['delivered', 'paid'])
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as nb_orders')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Ventes par catégorie de menu
        $salesByCategory = RestaurantOrderItem::with('menu')
            ->select('menu_id', DB::raw('SUM(quantity * price) as category_revenue'), DB::raw('SUM(quantity) as category_qty'))
            ->whereHas('order', fn($q) => $q->whereIn('status', ['delivered', 'paid']))
            ->groupBy('menu_id')
            ->get()
            ->groupBy(fn($item) => $item->menu?->category ?? 'autre')
            ->map(fn($group) => [
                'revenue' => $group->sum('category_revenue'),
                'qty'     => $group->sum('category_qty'),
            ]);

        // Statistiques globales
        $totalRevenue     = RestaurantOrder::whereIn('status', ['delivered', 'paid'])->sum('total');
        $totalOrders      = RestaurantOrder::whereIn('status', ['delivered', 'paid'])->count();
        $todayRevenue     = RestaurantOrder::whereDate('created_at', today())->whereIn('status', ['delivered', 'paid'])->sum('total');
        $monthRevenue     = RestaurantOrder::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->whereIn('status', ['delivered', 'paid'])->sum('total');

        return view('restaurant.sales', compact(
            'topItems', 'dailyRevenue', 'monthlyRevenue', 'salesByCategory',
            'totalRevenue', 'totalOrders', 'todayRevenue', 'monthRevenue'
        ));
    }

    private function findOrCreateCustomer($data)
    {
        // Essayer de trouver un client existant par téléphone
        if (!empty($data['phone'])) {
            $customer = Customer::where('phone', $data['phone'])->first();
            if ($customer) {
                return $customer;
            }
        }

        // Essayer de trouver par email
        if (!empty($data['email'])) {
            $customer = Customer::where('email', $data['email'])->first();
            if ($customer) {
                return $customer;
            }
        }

        // Créer un nouveau client temporaire
        return Customer::create([
            'name' => $data['customer_name'] ?? 'Client Restaurant',
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'notes' => 'Client créé automatiquement depuis la commande restaurant',
        ]);
    }
}
