<?php

namespace App\Http\Controllers;

use App\Mail\StaffCredentialsMail;
use App\Models\User;
use App\Notifications\StaffCreatedNotification;
use App\Rules\SafeName;
use App\Rules\StrongPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Gestion du personnel par l'hôtelier (issue #180).
 *
 * L'admin crée les comptes de son équipe (réception, ménage, service…) avec
 * leurs propres identifiants et des droits limités : il n'a plus besoin de
 * partager son mot de passe. Tout est STRICTEMENT limité à son hôtel.
 */
class StaffController extends Controller
{
    /** Rôles opérationnels qu'un membre de la direction peut attribuer. */
    public const ROLES = [
        'Receptionist' => 'staff.role_receptionist',
        'Cashier' => 'staff.role_cashier',
        'Housekeeping' => 'staff.role_housekeeping',
        'Servant' => 'staff.role_servant',
        'Cuisiner' => 'staff.role_cuisinier',
    ];

    public function __construct()
    {
        // Direction (Manager) autorisée via l'équivalence Manager→Admin de CheckRole.
        $this->middleware(['auth', 'checkrole:Super,Admin']);
    }

    private function hotelId(): ?int
    {
        return auth()->user()->hotel_id;
    }

    /**
     * Rôles que l'utilisateur COURANT peut gérer (créer / lister / supprimer).
     * Seuls le propriétaire (Admin) et le Super peuvent créer un compte Direction ;
     * un Manager ne gère que l'opérationnel (il ne peut pas créer d'autres Managers).
     */
    private function manageableRoles(): array
    {
        $roles = self::ROLES;

        if (in_array(auth()->user()->role, ['Admin', 'Super'], true)) {
            $roles['Manager'] = 'Direction';
        }

        return $roles;
    }

    /** Vérifie que la cible appartient à MON hôtel et fait partie des rôles que je gère. */
    private function assertOwned(User $user): void
    {
        abort_unless(
            $user->hotel_id === $this->hotelId() && array_key_exists($user->role, $this->manageableRoles()),
            403,
            __('staff.alert_error_unauthorized')
        );
    }

    public function index()
    {
        $hotelId = $this->hotelId();
        $roles   = $this->manageableRoles();

        $staff = $hotelId
            ? User::where('hotel_id', $hotelId)->whereIn('role', array_keys($roles))->orderBy('name')->get()
            : collect();

        return view('staff.index', ['staff' => $staff, 'roles' => $roles]);
    }

    public function store(Request $request)
    {
        $hotelId = $this->hotelId();
        abort_if($hotelId === null, 403, __('staff.alert_error_no_hotel'));

        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255', new SafeName],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'    => ['nullable', 'string', 'max:30'],
            'role'     => ['required', Rule::in(array_keys($this->manageableRoles()))],
            'password' => ['required', new StrongPassword],
        ], [], [
            'name' => __('staff.validation_name'), 'email' => __('staff.validation_email'), 'role' => __('staff.validation_role'), 'password' => __('staff.validation_password'),
        ]);

        User::create([
            'hotel_id' => $hotelId,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'role' => $data['role'],
            'password' => Hash::make($data['password']),
            'random_key' => Str::random(60),
        ]);

        $newUser = User::where('email', $data['email'])->first();
        Mail::to($data['email'])->send(new StaffCredentialsMail($newUser, $data['password']));

        auth()->user()->notify(new StaffCreatedNotification($newUser, $data['password']));

        return back()->with('success', __('staff.alert_success_created', ['name' => $data['name']]));
    }

    public function resetPassword(Request $request, User $user)
    {
        $this->assertOwned($user);

        $data = $request->validate(['password' => ['required', new StrongPassword]], [], ['password' => __('staff.validation_password')]);
        $user->update(['password' => Hash::make($data['password'])]);

        return back()->with('success', __('staff.alert_success_reset', ['name' => $user->name]));
    }

    public function destroy(User $user)
    {
        $this->assertOwned($user);
        $name = $user->name;
        $user->delete();

        return back()->with('success', __('staff.alert_success_deleted', ['name' => $name]));
    }
}
