<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Customer;
use App\Models\RestaurantOrder;
use App\Models\RestaurantOrderItem;
use App\Models\Room;
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
            'image' => 'nullable|image|max:2048'
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
            'html' => view('restaurant.partials.order-details', compact('order'))->render()
        ]);
    }

    // Enregistrer une nouvelle commande
    public function storeOrder(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'room_number' => 'nullable|string',
            'items' => 'required|json',
            'total' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'payment_method' => 'nullable|string'
        ]);

        DB::beginTransaction();
        
        try {
            $order = RestaurantOrder::create([
                'customer_id' => $validated['customer_id'],
                'room_id' => $this->getRoomIdFromNumber($validated['room_number']),
                'total' => $validated['total'],
                'notes' => $validated['notes'],
                'payment_method' => $validated['payment_method'],
                'status' => 'pending'
            ]);

            $items = json_decode($validated['items'], true);
            
            foreach ($items as $item) {
                $menu = Menu::find($item['menu_id']);
                
                RestaurantOrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $item['menu_id'],
                    'quantity' => $item['quantity'],
                    'price' => $menu->price
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('restaurant.orders')
                ->with('success', 'Commande enregistrée avec succès!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la création de la commande: ' . $e->getMessage());
        }
    }

    // Mettre à jour une commande (statut)
    public function updateOrder(Request $request, $id)
    {
        $order = RestaurantOrder::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:pending,preparing,delivered,paid,cancelled'
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
        // MODIFIEZ CETTE LIGNE AUSSI
        $customers = Customer::all()
            ->map(function($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'room_number' => $customer->room->room_number ?? null
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
        if (!$roomNumber) return null;
        
        $room = Room::where('room_number', $roomNumber)->first();
        return $room ? $room->id : null;
    }
}