<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Models\Customer;
use App\Models\User;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Repositories\Interfaces\ImageRepositoryInterface;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct(
        private CustomerRepositoryInterface $customerRepository
    ) {}

    public function index(Request $request)
    {
        $customers = $this->customerRepository->get($request);

        return view('customer.index', ['customers' => $customers]);
    }

    public function create()
    {
        return view('customer.create');
    }

    public function store(StoreCustomerRequest $request)
    {
        // Anti-doublon (issue #159) : bouton "précédent" du navigateur puis
        // re-soumission du formulaire déjà rempli -> on ne recrée pas la fiche.
        $existing = Customer::where('email', $request->email)
            ->where('name', $request->name)
            ->first();

        if ($existing) {
            return redirect('customer')
                ->with('success', __('flash.customer_exists', ['name' => $existing->name]));
        }

        $customer = $this->customerRepository->store($request);

        return redirect('customer')->with('success', __('flash.customer_created', ['name' => $customer->name]));
    }

    public function show(Customer $customer)
    {
        return view('customer.show', ['customer' => $customer]);
    }

    public function edit(Customer $customer)
    {
        return view('customer.edit', ['customer' => $customer]);
    }

    public function update(Customer $customer, StoreCustomerRequest $request)
    {
        $data = $request->validated();

        // Prise en charge de la nouvelle photo (issue #204 : la mise à jour de
        // l'avatar était ignorée, seul le flux réservation la gérait).
        unset($data['avatar']);
        if ($request->hasFile('avatar')) {
            if ($customer->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($customer->avatar)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($customer->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $customer->update($data);

        return redirect('customer')->with('success', __('flash.customer_updated', ['name' => $customer->name]));
    }

    public function destroy(Customer $customer, ImageRepositoryInterface $imageRepository)
    {
        try {
            // Sauvegarde du nom pour le message
            $customerName = $customer->name;

            // Suppression LOGIQUE (SoftDeletes) : la fiche disparaît des listes mais
            // la ligne reste en base, donc les réservations liées gardent leur
            // référence et l'historique est préservé (issue #202 : avant, la FK
            // RESTRICT empêchait toute suppression d'un client ayant des résas).
            $customer->delete();

            // Best-effort : retirer le compte de connexion lié + son avatar. Si ce
            // compte est référencé ailleurs, on n'échoue pas la suppression du client.
            if ($customer->user) {
                try {
                    $user = $customer->user;
                    $avatar_path = public_path('img/user/'.$user->name.'-'.$user->id);
                    if (file_exists($avatar_path) && is_dir($avatar_path)) {
                        $imageRepository->destroy($avatar_path);
                    }
                    $user->delete();
                } catch (\Throwable $e) {
                    \Log::warning('Compte de connexion lié non supprimé: '.$e->getMessage());
                }
            }

            return redirect('customer')->with('success', __('flash.customer_deleted', ['name' => $customerName]));

        } catch (\Exception $e) {
            \Log::error('Delete customer error: '.$e->getMessage());

            // Message d'erreur plus détaillé
            $errorDetails = '';

            if (str_contains($e->getMessage(), 'foreign key constraint')) {
                $errorDetails = 'This customer has related records (transactions, payments, etc.). Delete those first.';
            } elseif (isset($e->errorInfo[0]) && $e->errorInfo[0] == '23000') {
                $errorDetails = 'This customer has related records in other tables.';
            } else {
                $errorDetails = $e->getMessage();
            }

            return redirect('customer')->with('failed', __('flash.customer_delete_error').': '.$errorDetails);
        }
    }

    /**
     * API de recherche de clients pour le check-in direct
     */
    public function apiSearch(Request $request)
    {
        $search = $request->get('search', '');

        if (strlen($search) < 2) {
            return response()->json([]);
        }

        $customers = Customer::with('user')
            ->where('name', 'LIKE', "%{$search}%")
            ->orWhere('phone', 'LIKE', "%{$search}%")
            ->orWhereHas('user', function ($query) use ($search) {
                $query->where('email', 'LIKE', "%{$search}%");
            })
            ->limit(10)
            ->get()
            ->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'phone' => $customer->phone,
                    'email' => $customer->user->email ?? null,
                    'reservation_count' => $customer->transactions()->count(),
                ];
            });

        return response()->json($customers);
    }
}
