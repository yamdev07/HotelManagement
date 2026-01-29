<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use App\Models\User;
use App\Repositories\Interface\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            abort(403, 'Only Super Admin can access user management.');
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
            abort(403, 'Only Super Admin can create users.');
        }
        
        return view('user.create');
    }

    public function store(StoreUserRequest $request)
    {
        // Vérification
        if (Auth::user()->role !== 'Super') {
            abort(403, 'Only Super Admin can create users.');
        }
        
        activity()->causedBy(auth()->user())->log('User '.$request->name.' created');
        $user = $this->userRepository->store($request);

        return redirect()->route('user.index')->with('success', 'User '.$user->name.' created');
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
            abort(403, 'You can only view your own profile.');
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
            abort(403, 'Only Super Admin can edit users.');
        }
        
        return view('user.edit', ['user' => $user]);
    }

    public function update(User $user, UpdateCustomerRequest $request)
    {
        // Seuls les "Super" peuvent modifier les utilisateurs
        if (Auth::user()->role !== 'Super') {
            abort(403, 'Only Super Admin can update users.');
        }
        
        activity()->causedBy(auth()->user())->log('User '.$user->name.' updated');
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
        // Seuls les "Super" peuvent supprimer des utilisateurs
        if (Auth::user()->role !== 'Super') {
            abort(403, 'Only Super Admin can delete users.');
        }
        
        activity()->causedBy(auth()->user())->log('User '.$user->name.' deleted');
        
        try {
            $user->delete();

            return redirect()->route('user.index')->with('success', 'User '.$user->name.' deleted!');
        } catch (\Exception $e) {
            return redirect()->route('user.index')->with('failed', 'User '.$user->name.' cannot be deleted! Error: '.$e->getMessage());
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
}