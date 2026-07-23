<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {
        $this->userRepository = $userRepository;

        // ⭐⭐ CORRECTION CRITIQUE : Middleware de sécurité
        $this->middleware('auth');

        // Seuls les "Super" peuvent accéder à toutes les méthodes
        $this->middleware('checkrole:Super')->except(['show']);

        // Pour la méthode show, permettre à chaque utilisateur de voir son propre profil
        // Ou les "Super" peuvent voir tous les profils
        $this->middleware(function ($request, $next) {
            if ($request->route()->getName() === 'user.show') {
                $user = Auth::user();
                $requestedUserId = $request->route('user')->id;

                // "Super" peut voir tous les profils
                if ($user->role === 'Super') {
                    return $next($request);
                }

                // Un utilisateur peut seulement voir son propre profil
                if ($user->id == $requestedUserId) {
                    return $next($request);
                }

                abort(403, 'Unauthorized action.');
            }

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        // Vérification supplémentaire (au cas où)
        if (Auth::user()->role !== 'Super') {
            abort(403, 'Réservé au Super-Admin.');
        }

        $users = $this->userRepository->showUser($request);
        $customers = $this->userRepository->showCustomer($request);

        return view('user.index', [
            'users' => $users,
            'customers' => $customers,
        ]);
    }

    public function create()
    {
        // Vérification
        if (Auth::user()->role !== 'Super') {
            abort(403, 'Réservé au Super-Admin.');
        }

        return view('user.create');
    }

    public function store(StoreUserRequest $request)
    {
        // Vérification
        if (Auth::user()->role !== 'Super') {
            abort(403, 'Réservé au Super-Admin.');
        }

        activity()->causedBy(auth()->user())->log('User '.$request->name.' created');
        $user = $this->userRepository->store($request);

        return redirect()->route('user.index')->with('success', 'Utilisateur '.$user->name.' créé');
    }

    public function show(User $user)
    {
        $currentUser = Auth::user();

        // ⭐⭐ SOLUTION SIMPLE : "Super" peut TOUT voir
        if ($currentUser->role === 'Super') {
            // "Super" a accès à tous les profils - PAS DE BLOCAGE
        }
        // Les autres utilisateurs ne peuvent voir que leur propre profil
        elseif ($currentUser->id !== $user->id) {
            abort(403, 'Vous ne pouvez voir que votre propre profil.');
        }

        activity()->causedBy(auth()->user())->log('User '.$user->name.' viewed');

        if ($user->role === 'Customer') {
            $customer = Customer::where('user_id', $user->id)->first();

            return view('customer.show', ['customer' => $customer]);
        }

        return view('user.show', ['user' => $user]);
    }

    public function edit(User $user)
    {
        // Seuls les "Super" peuvent éditer les utilisateurs
        if (Auth::user()->role !== 'Super') {
            abort(403, 'Réservé au Super-Admin.');
        }

        return view('user.edit', ['user' => $user]);
    }

    public function update(User $user, UpdateCustomerRequest $request)
    {
        // Seuls les "Super" peuvent modifier les utilisateurs
        if (Auth::user()->role !== 'Super') {
            abort(403, 'Réservé au Super-Admin.');
        }

        activity()->causedBy(auth()->user())->log('Utilisateur '.$user->name.' mis à jour');
        $user->update($request->all());

        if ($user->isCustomer()) {
            $user->customer->update([
                'name' => $request->name,
            ]);
        }

        return redirect()->route('user.index')->with('success', 'User '.$user->name.' updated!');
    }

    public function destroy(User $user)
    {
        // Vérification des permissions (Super ou Admin)
        if (!in_array(Auth::user()->role, ['Super', 'Admin'])) {
            abort(403, 'Seuls les Super Admins et Admins peuvent supprimer des utilisateurs.');
        }

        // Empêcher la suppression de son propre compte
        if (Auth::user()->id === $user->id) {
            return redirect()->route('user.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        // Vérifier si l'utilisateur a des transactions actives
        if ($user->role === 'Customer') {
            $customer = Customer::where('user_id', $user->id)->first();
            
            if ($customer) {
                $activeTransactions = $customer->transactions()
                    ->whereIn('status', ['reservation', 'active'])
                    ->count();
                
                if ($activeTransactions > 0) {
                    return redirect()->route('user.index')
                        ->with('error', 'Ce client a des réservations actives. Impossible de supprimer.');
                }
            }
        }

        activity()->causedBy(auth()->user())->log('Utilisateur '.$user->name.' supprimé');

        try {
            // Soft delete ou suppression définitive ?
            $user->delete(); // Suppression définitive

            return redirect()->route('user.index')
                ->with('success', 'Utilisateur '.$user->name.' supprimé avec succès!');
                
        } catch (\Exception $e) {
            return redirect()->route('user.index')
                ->with('error', 'Impossible de supprimer '.$user->name.'. Erreur: '.$e->getMessage());
        }
    }

    /**
     * Méthode helper pour vérifier les permissions
     */
    private function checkSuperPermission()
    {
        if (Auth::user()->role !== 'Super') {
            abort(403, 'Unauthorized: Super Admin privileges required.');
        }
    }

    public function resetPassword(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $user->update([
                'password' => Hash::make($request->password),
            ]);

            activity()->causedBy(auth()->user())->log('Mot de passe réinitialisé pour ' . $user->name);

            return redirect()->route('user.show', $user)->with('success', 'Mot de passe réinitialisé avec succès.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la réinitialisation : ' . $e->getMessage());
        }
    }

    public function toggleStatus(User $user)
    {
        if (Auth::user()->id === $user->id) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas désactiver votre propre compte.');
        }

        try {
            $newStatus = $user->is_active ? false : true;
            $user->update(['is_active' => $newStatus]);

            $label = $newStatus ? 'activé' : 'désactivé';
            activity()->causedBy(auth()->user())->log("Utilisateur {$user->name} {$label}");

            return redirect()->back()->with('success', "Utilisateur {$label} avec succès.");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors du changement de statut : ' . $e->getMessage());
        }
    }

    public function activity(User $user)
    {
        $activities = activity()
            ->causedBy($user)
            ->latest()
            ->paginate(20);

        return view('activity.index', [
            'activities'       => $activities,
            'users'            => [$user],
            'totalActivities'  => activity()->causedBy($user)->count(),
        ]);
    }

    public function export(Request $request)
    {
        $users = User::select('id', 'name', 'email', 'role', 'is_active', 'created_at')
            ->orderBy('name')
            ->get();

        $csv = fopen('php://temp', 'w');
        fputcsv($csv, ['ID', 'Nom', 'Email', 'Rôle', 'Statut', 'Date de création']);

        foreach ($users as $u) {
            fputcsv($csv, [
                $u->id,
                $u->name,
                $u->email,
                $u->role,
                $u->is_active ? 'Actif' : 'Inactif',
                $u->created_at->format('d/m/Y H:i'),
            ]);
        }

        rewind($csv);
        $data = stream_get_contents($csv);
        fclose($csv);

        return response($data)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="utilisateurs_' . date('Y-m-d') . '.csv"');
    }
}
