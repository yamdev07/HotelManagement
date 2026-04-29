<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Ingredient;
use App\Models\Menu;
use App\Models\RestaurantOrder;
use App\Models\RestaurantOrderItem;
use App\Models\RestaurantTable;
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
        // MODIFIEZ CETTE LIGNE : enlevez le where('status', 'active')
        $customers = Customer::all();

        return view('restaurant.index', compact('menus', 'customers'));
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

    // Afficher toutes les commandes
    public function orders()
    {
        $orders = RestaurantOrder::with(['customer', 'items.menu'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // MODIFIEZ CETTE LIGNE AUSSI : enlevez le where('status', 'active')
        $customers = Customer::all();
        $menus = Menu::all();

        // Statistiques (ces status sont pour RestaurantOrder, PAS pour Customer)
        $pendingOrders = RestaurantOrder::where('status', 'pending')->count();
        $deliveredOrders = RestaurantOrder::where('status', 'delivered')->count();
        $todayRevenue = RestaurantOrder::whereDate('created_at', today())
            ->where('status', 'paid')
            ->sum('total');
        $monthlyOrders = RestaurantOrder::whereMonth('created_at', now()->month)->count();

        return view('restaurant.orders', compact(
            'orders', 'customers', 'menus',
            'pendingOrders', 'deliveredOrders',
            'todayRevenue', 'monthlyOrders'
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
     *  PLAN DE SALLE  –  éditeur drag & drop
     * ───────────────────────────────────────────────────────── */

    public function layout()
    {
        $tables = RestaurantTable::orderBy('z_order')->get();

        // Premier chargement : injecter le plan par défaut
        if ($tables->isEmpty()) {
            DB::transaction(function () {
                foreach ($this->defaultLayout() as $item) {
                    RestaurantTable::create($item);
                }
            });
            $tables = RestaurantTable::orderBy('z_order')->get();
        }

        return view('restaurant.layout', compact('tables'));
    }

    /**
     * Plan par défaut : 4 ensembles table ronde 4P + 2 ensembles table rect 6P + bar
     * Positions en pourcentage du canvas (1140 × 680 px)
     */
    private function defaultLayout(): array
    {
        $CW = 1140; $CH = 680;
        $items = [];
        $z = 0;

        $pct = fn($v, $max) => round($v / $max * 100, 4);

        $el = function ($name, $type, $seats, $x, $y, $w, $h, $color) use (&$items, &$z, $pct, $CW, $CH) {
            $items[] = [
                'name'     => $name,
                'type'     => $type,
                'seats'    => $seats,
                'x'        => $pct($x, $CW),
                'y'        => $pct($y, $CH),
                'w'        => $pct($w, $CW),
                'h'        => $pct($h, $CH),
                'rotation' => 0,
                'color'    => $color,
                'z_order'  => ++$z,
            ];
        };

        $chair = fn($x, $y) => $el('', 'chair', 1, $x, $y, 38, 38, '#a07848');

        /*
         * ── 4 ensembles Table ronde 4P + 4 chaises ────────────────────────
         *  Disposition : 2 colonnes × 2 lignes, côté gauche du restaurant
         *  Chaque ensemble occupe ~200×200 px
         */
        $sets4P = [[90, 90], [310, 90], [90, 330], [310, 330]];
        foreach ($sets4P as $i => [$tx, $ty]) {
            $el('T'.($i+1), 'round', 4, $tx, $ty, 100, 100, '#c9956a');
            // chaise haut, bas, gauche, droite (centrées sur les bords)
            $chair($tx + 31, $ty - 48);   // haut
            $chair($tx + 31, $ty + 110);  // bas
            $chair($tx - 48, $ty + 31);   // gauche
            $chair($tx + 110, $ty + 31);  // droite
        }

        /*
         * ── 2 ensembles Table rectangle 6P + 6 chaises ────────────────────
         *  Côté droit du restaurant
         *  Table 160×80 — 3 chaises par côté (haut / bas)
         */
        $sets6P = [[560, 90], [560, 310]];
        foreach ($sets6P as $i => [$tx, $ty]) {
            $el('T'.($i+5), 'rect', 6, $tx, $ty, 160, 80, '#c9956a');
            // 3 chaises haut
            $chair($tx + 11, $ty - 48);
            $chair($tx + 61, $ty - 48);
            $chair($tx + 111, $ty - 48);
            // 3 chaises bas
            $chair($tx + 11, $ty + 90);
            $chair($tx + 61, $ty + 90);
            $chair($tx + 111, $ty + 90);
        }

        /*
         * ── Bar / Comptoir ─────────────────────────────────────────────────
         *  Toute la largeur du bas, avec 6 tabourets devant
         */
        $el('Bar', 'bar', 0, 80, 545, 720, 65, '#4a2e1a');
        // 6 tabourets de bar
        $bx = 105;
        for ($s = 0; $s < 6; $s++) {
            $el('', 'chair', 1, $bx + $s * 115, 497, 36, 36, '#a07848');
        }

        /*
         * ── Plantes décoratives ────────────────────────────────────────────
         */
        $el('', 'plant', 0, 870,  60, 55, 55, '#22c55e');
        $el('', 'plant', 0, 1010, 60, 55, 55, '#22c55e');
        $el('', 'plant', 0, 870, 290, 55, 55, '#22c55e');
        $el('', 'plant', 0, 1010,290, 55, 55, '#22c55e');
        $el('', 'plant', 0, 870, 510, 55, 55, '#22c55e');

        return $items;
    }

    public function saveLayout(Request $request)
    {
        $request->validate([
            'tables'            => 'required|array',
            'tables.*.name'     => 'required|string|max:50',
            'tables.*.type'     => 'required|string|max:30',
            'tables.*.seats'    => 'required|integer|min:0|max:99',
            'tables.*.x'        => 'required|numeric',
            'tables.*.y'        => 'required|numeric',
            'tables.*.w'        => 'required|numeric',
            'tables.*.h'        => 'required|numeric',
            'tables.*.rotation' => 'required|integer',
            'tables.*.color'    => 'required|string|max:20',
            'tables.*.z_order'  => 'required|integer',
        ]);

        // Remplacer tout le plan en une transaction
        DB::transaction(function () use ($request) {
            RestaurantTable::truncate();
            foreach ($request->tables as $t) {
                RestaurantTable::create($t);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Plan du restaurant sauvegardé.',
            'count'   => count($request->tables),
        ]);
    }

    /* ─────────────────────────────────────────────────────────
     *  GESTION DU STOCK (Ingrédients)
     * ───────────────────────────────────────────────────────── */

    public function stock()
    {
        $ingredients = Ingredient::orderBy('name')->get();
        $lowStockCount = $ingredients->filter(fn($i) => $i->isLowStock())->count();
        $outOfStockCount = $ingredients->filter(fn($i) => $i->quantity_in_stock <= 0)->count();

        return view('restaurant.stock', compact('ingredients', 'lowStockCount', 'outOfStockCount'));
    }

    public function storeIngredient(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'unit'              => 'required|string|max:50',
            'quantity_in_stock' => 'required|numeric|min:0',
            'min_stock'         => 'required|numeric|min:0',
            'price_per_unit'    => 'nullable|numeric|min:0',
        ]);

        Ingredient::create($request->only(['name', 'unit', 'quantity_in_stock', 'min_stock', 'price_per_unit']));

        return response()->json(['success' => true, 'message' => 'Ingrédient ajouté avec succès.']);
    }

    public function updateIngredient(Request $request, $id)
    {
        $ingredient = Ingredient::findOrFail($id);

        $request->validate([
            'name'              => 'sometimes|required|string|max:255',
            'unit'              => 'sometimes|required|string|max:50',
            'quantity_in_stock' => 'required|numeric|min:0',
            'min_stock'         => 'sometimes|required|numeric|min:0',
            'price_per_unit'    => 'nullable|numeric|min:0',
        ]);

        $ingredient->update($request->only(['name', 'unit', 'quantity_in_stock', 'min_stock', 'price_per_unit']));

        return response()->json(['success' => true, 'message' => 'Stock mis à jour.']);
    }

    public function destroyIngredient($id)
    {
        Ingredient::findOrFail($id)->delete();

        return response()->json(['success' => true]);
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
