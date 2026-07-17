<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\SafeName;
use App\Rules\StrongPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
    /** Rôles qu'un hôtelier peut attribuer (jamais Admin ni Super). */
    public const ROLES = [
        'Receptionist' => 'Réceptionniste',
        'Housekeeping' => 'Housekeeping (ménage)',
        'Servant'      => 'Serveur / Serveuse',
        'Cuisiner'     => 'Cuisinier / Cuisinière',
    ];

    public function __construct()
    {
        $this->middleware(['auth', 'checkrole:Super,Admin']);
    }

    private function hotelId(): ?int
    {
        return auth()->user()->hotel_id;
    }

    /** Vérifie que la cible appartient bien à MON hôtel et est un membre du personnel. */
    private function assertOwned(User $user): void
    {
        abort_unless(
            $user->hotel_id === $this->hotelId() && array_key_exists($user->role, self::ROLES),
            403,
            'Action non autorisée.'
        );
    }

    public function index()
    {
        $hotelId = $this->hotelId();

        $staff = $hotelId
            ? User::where('hotel_id', $hotelId)->whereIn('role', array_keys(self::ROLES))->orderBy('name')->get()
            : collect();

        return view('staff.index', ['staff' => $staff, 'roles' => self::ROLES]);
    }

    public function store(Request $request)
    {
        $hotelId = $this->hotelId();
        abort_if($hotelId === null, 403, 'Aucun établissement associé à votre compte.');

        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255', new SafeName],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'    => ['nullable', 'string', 'max:30'],
            'role'     => ['required', Rule::in(array_keys(self::ROLES))],
            'password' => ['required', new StrongPassword],
        ], [], [
            'name' => 'nom', 'email' => 'email', 'role' => 'rôle', 'password' => 'mot de passe',
        ]);

        User::create([
            'hotel_id'   => $hotelId,
            'name'       => $data['name'],
            'email'      => $data['email'],
            'phone'      => $data['phone'] ?? null,
            'role'       => $data['role'],
            'password'   => Hash::make($data['password']),
            'random_key' => Str::random(60),
        ]);

        return back()->with('success', 'Membre du personnel « '.$data['name'].' » créé. Il se connecte avec son email et le mot de passe que vous avez défini.');
    }

    public function resetPassword(Request $request, User $user)
    {
        $this->assertOwned($user);

        $data = $request->validate(['password' => ['required', new StrongPassword]], [], ['password' => 'mot de passe']);
        $user->update(['password' => Hash::make($data['password'])]);

        return back()->with('success', 'Mot de passe de '.$user->name.' réinitialisé.');
    }

    public function destroy(User $user)
    {
        $this->assertOwned($user);
        $name = $user->name;
        $user->delete();

        return back()->with('success', 'Membre « '.$name.' » supprimé.');
    }
}
