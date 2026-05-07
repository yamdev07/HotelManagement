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
        $menus = Menu::with('category')->latest()->paginate(12);
        $allMenus = Menu::with('category')->latest()->get()->map(function($m) {
            $m->image = $m->image_url;
            return $m;
        }); 
        
        $customers = Customer::whereHas('transactions', function ($q) {
            $q->whereIn('status', ['active', 'reservation', 'pending_checkout', 'reserved_waiting']);
        })->with(['transactions' => function ($q) {
            $q->whereIn('status', ['active', 'reservation', 'pending_checkout', 'reserved_waiting'])
              ->with('room')
              ->latest();
        }])->get()->map(function ($customer) {
            $activeTransaction = $customer->transactions->whereIn('status', ['active', 'pending_checkout'])->first() 
                                ?: $customer->transactions->first();
            $customer->room_number = $activeTransaction?->room?->number ?? null;
            return $customer;
        });

        $categories = \App\Models\Category::all();

        return view('restaurant.index', compact('menus', 'allMenus', 'customers', 'categories'));
    }

    // Afficher le formulaire de création
    public function create()
    {
        $totalMenus = Menu::count();
        $lastAdded = Menu::latest()->first();
        $categories = \App\Models\Category::all();

        return view('restaurant.create', compact('totalMenus', 'lastAdded', 'categories'));
    }

    // Enregistrer un nouveau menu
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'available_days' => 'nullable|array',
            'is_available' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('menus', 'public');
            $validated['image'] = $path;
        }

        // Par défaut, tous les jours si non spécifié
        if (!$request->has('available_days')) {
            $validated['available_days'] = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
        }

        $validated['is_available'] = $request->has('is_available');

        Menu::create($validated);

        return redirect()->route('restaurant.index')
            ->with('success', 'Menu ajouté avec succès!');
    }

    // Afficher le formulaire de modification
    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        $categories = \App\Models\Category::all();
        return view('restaurant.edit', compact('menu', 'categories'));
    }

    // Mettre à jour un menu existant
    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'available_days' => 'nullable|array',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('menus', 'public');
            $validated['image'] = $path;
        }

        $validated['available_days'] = $request->input('available_days', []);
        $validated['is_available'] = $request->has('is_available');

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

        $customers = Customer::whereHas('transactions', function ($q) {
            $q->whereIn('status', ['active', 'reservation', 'pending_checkout', 'reserved_waiting']);
        })->with(['transactions' => function ($q) {
            $q->whereIn('status', ['active', 'reservation', 'pending_checkout', 'reserved_waiting'])
              ->with('room')
              ->latest();
        }])->get()->map(function ($customer) {
            $activeTransaction = $customer->transactions->whereIn('status', ['active', 'pending_checkout'])->first() 
                                ?: $customer->transactions->first();
            $customer->room_number = $activeTransaction?->room?->number ?? null;
            return $customer;
        });
        
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

    public function showOrder($id)
    {
        $order = RestaurantOrder::with(['items.menu', 'customer', 'room'])->findOrFail($id);

        return response()->json([
            'html'  => view('restaurant.partials.order-details', compact('order'))->render(),
            'order' => $order->append(['customer_name', 'customer_phone', 'room_number'])->toArray(),
        ]);
    }

    // Page facture imprimable (standalone, sans layout)
    public function printInvoice($id)
    {
        $order = RestaurantOrder::with(['items.menu', 'customer', 'room'])->findOrFail($id);
        return view('restaurant.invoice', compact('order'));
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

            // Si pas de customer_id fourni, essayer de trouver un client existant (sans le créer)
            if (!$customerId && (!empty($validated['phone']) || !empty($validated['email']))) {
                $customer = $this->findCustomer($validated);
                if ($customer) {
                    $customerId = $customer->id;
                }
            }

            $transactionId = $this->resolveRestaurantOrderTransaction(
                $customerId,
                $validated['room_number'] ?? null
            );

            // Vérification de sécurité pour éviter les fausses commandes sur chambre d'autrui
            if ($transactionId && !empty($validated['room_number'])) {
                $transaction = Transaction::find($transactionId);
                if ($transaction && $transaction->customer) {
                    // Bypass sécurité email pour les admins/staff
                    $isAdmin = auth()->check() && in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist', 'Servant', 'Cuisiner', 'Cashier']);
                    $inputEmail = $validated['email'] ?? null;
                    $realEmail = $transaction->customer->email ?? null;

                    if (!$isAdmin && (!empty($inputEmail) && strtolower($realEmail) !== strtolower($inputEmail))) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => "Sécurité : L'email indiqué ne correspond pas au titulaire de la chambre."
                        ], 403);
                    }
                    // Associer la commande à ce client
                    $customerId = $transaction->customer_id;
                }
            } elseif (!$customerId && $transactionId) {
                // Secours
                $transaction = Transaction::find($transactionId);
                if ($transaction) {
                    $customerId = $transaction->customer_id;
                }
            }
            
            $notes = $validated['notes'] ?? null;
            
            // Gestion du lieu de service et numéro de table (comme vitrine)
            $location = $request->input('order_location');
            $tableNumber = $request->input('table_number');
            if ($location === 'table' && !empty($tableNumber)) {
                $tableInfo = "📍 TABLE: " . $tableNumber;
                $notes = $notes ? $tableInfo . " | " . $notes : $tableInfo;
            } elseif ($location === 'room' && !empty($validated['room_number'])) {
                 $roomInfo = "🔑 CHAMBRE: " . $validated['room_number'];
                 $notes = $notes ? $roomInfo . " | " . $notes : $roomInfo;
            }

            // Enregistrer le nom spécifique s'il est fourni (même pour les clients résidents)
            if (!empty($validated['customer_name'])) {
                $guestInfo = "👤 Client: " . $validated['customer_name'];
                $notes = $notes ? $guestInfo . " | " . $notes : $guestInfo;
                if (!empty($validated['phone']) && empty($customerId)) {
                    $notes .= " (Tel: " . $validated['phone'] . ")";
                }
            }

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
                'notes' => $notes,
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

            // Ne PAS encore mettre à jour le total de la transaction à la création.
            // Le montant sera ajouté à la facture uniquement quand la commande sera "livrée".

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

        $newStatus = $request->status;

        // Règle : les commandes "sur chambre" ne peuvent pas être manuellement marquées "payées"
        if ($newStatus === 'paid' && $order->payment_method === 'room_charge') {
            return response()->json([
                'success' => false,
                'message' => 'Cette commande est sur facture chambre. Elle sera marquée payée automatiquement lors du règlement de la transaction.'
            ], 422);
        }

        $order->update(['status' => $newStatus]);

        // Règle : quand la commande passe en "livré", on ajoute son montant à la facture (si room_charge)
        if ($newStatus === 'delivered' && $order->payment_method === 'room_charge' && $order->transaction_id) {
            $transaction = Transaction::find($order->transaction_id);
            if ($transaction) {
                $transaction->update(['total_price' => $transaction->getTotalPrice()]);
                $transaction->updatePaymentStatus(); // Vérifier si le solde reste à 0 (déjà payé d'avance)
            }
        }

        // Règle : si la commande est annulée et était livrée (donc déjà sur la facture), recalculer
        if ($newStatus === 'cancelled' && $order->payment_method === 'room_charge' && $order->transaction_id) {
            $transaction = Transaction::find($order->transaction_id);
            if ($transaction) {
                $transaction->update(['total_price' => $transaction->getTotalPrice()]);
            }
        }

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
        $menus = Menu::with('category')->get()->map(function($m) {
            return [
                'id' => $m->id,
                'name' => $m->name,
                'price' => $m->price,
                'image' => $m->image_url,
                'category' => $m->category->name ?? 'Menu',
            ];
        });

        return response()->json($menus);
    }

    // API publique — vérifier si une chambre a un client actif
    public function checkRoomGuest(Request $request)
    {
        $roomNumber = trim($request->get('room_number', ''));
        if (!$roomNumber) {
            return response()->json(['valid' => false, 'message' => 'Numéro de chambre manquant.']);
        }

        $room = Room::where('number', $roomNumber)->first();
        if (!$room) {
            return response()->json(['valid' => false, 'message' => "La chambre {$roomNumber} n'existe pas."]);
        }

        $transaction = Transaction::where('room_id', $room->id)
            ->whereIn('status', ['active', 'pending_checkout'])
            ->latest()
            ->first();

        if (!$transaction) {
            return response()->json([
                'valid' => false,
                'message' => "Aucun client actif dans la chambre {$roomNumber}. Veuillez vérifier le numéro ou laisser ce champ vide."
            ]);
        }

        $inputEmail = trim($request->get('email', ''));
        if ($inputEmail) {
            $realEmail = trim($transaction->customer->email ?? '');
            
            // Vérification de sécurité stricte : l'email saisi doit correspondre
            if (empty($realEmail) || strtolower($realEmail) !== strtolower($inputEmail)) {
                 return response()->json([
                    'valid' => false,
                    'message' => "Sécurité : L'email saisi ne correspond pas à la personne occupant la chambre {$roomNumber}."
                 ]);
            }
        }

        return response()->json([
            'valid' => true,
            'customer_name' => $transaction->customer->name ?? null,
            'room' => $roomNumber,
        ]);
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

    private function findCustomer($data)
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

        // Retourner null au lieu de créer un nouveau client
        return null;
    }

    public function toggleStatus($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->is_available = !$menu->is_available;
        $menu->save();

        return response()->json([
            'success' => true,
            'is_available' => $menu->is_available
        ]);
    }

    public function updateCustomerName(Request $request, $id)
    {
        $order = RestaurantOrder::findOrFail($id);
        $name = trim($request->input('customer_name', ''));

        if ($name) {
            $marker = "👤 Client: " . $name;
            $notes = $order->notes;

            // Si le marqueur existe déjà, on le remplace
            if (preg_match('/👤\s*Client\s*:\s*([^\|\n\r]+)/u', $notes)) {
                $notes = preg_replace('/👤\s*Client\s*:\s*([^\|\n\r]+)/u', $marker, $notes);
            } else {
                // Sinon on l'ajoute au début
                $notes = $marker . ($notes ? " | " . $notes : "");
            }

            $order->notes = $notes;
            $order->save();

            return response()->json([
                'success' => true,
                'customer_name' => $name
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Nom vide']);
    }
}

