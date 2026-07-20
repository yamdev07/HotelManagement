<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        // Connexion par email OU téléphone (issue #165) : si l'identifiant
        // saisi n'est pas un email, on le traite comme un numéro de téléphone.
        $identifier = trim((string) $request->input('email'));
        $field = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        if (Auth::attempt([$field => $identifier, 'password' => $request->password])) {
            activity()->causedBy(auth()->user())->log('User logged into the portal');

            // Super-Admin plateforme (sans hôtel) -> dashboard de gestion des hôtels
            if (auth()->user()->hotel_id === null && auth()->user()->role === 'Super') {
                return redirect()->route('platform.hotels.index')->with('success', 'Bienvenue ' . auth()->user()->name);
            }

            // Redirection intelligente selon le rôle
            if (auth()->user()->role === 'Customer') {
                return redirect()->route('transaction.myReservations')->with('success', 'Bienvenue ' . auth()->user()->name);
            }

            if (in_array(auth()->user()->role, ['Servant', 'Cuisiner'])) {
                return redirect()->route('restaurant.orders')->with('success', 'Bienvenue ' . auth()->user()->name);
            }

            return redirect('/home')->with('success', 'Bienvenue ' . auth()->user()->name);
        }

        return redirect('login')->with('failed', 'Identifiants incorrects. Vérifiez votre email (ou téléphone) et votre mot de passe.');
    }

    public function register(Request $request)
    {
        // Nom sans emoji/charabia (issue #199 : des noms type « lionel sisso🎉🎉🎉 »
        // se retrouvaient affichés dans les messages de l'app).
        $request->validate([
            'name' => ['required', 'string', 'max:255', new \App\Rules\SafeName],
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', new \App\Rules\StrongPassword],
        ], [], [
            'name' => 'nom',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login.index')->with('success', 'Votre compte a bien été créé. Vous pouvez maintenant vous connecter.');
    }

    public function logout()
    {
        // Sauvegardez le nom AVANT de déconnecter
        $name = auth()->user()->name;

        // Nettoie les emojis d'éventuels anciens noms (issue #199 : « lionel sisso🎉🎉🎉 »
        // s'affichait tel quel dans le message de déconnexion).
        $name = trim(preg_replace('/\s+/', ' ', preg_replace(\App\Rules\NoEmoji::EMOJI_PATTERN, '', $name)));

        // Déconnexion complète
        Auth::logout();

        // Invalide la session (important!)
        session()->invalidate();

        // Régénère le token CSRF
        session()->regenerateToken();

        $bye = $name !== '' ? 'Au revoir '.$name.' !' : 'À bientôt !';

        return redirect('login')->with('success', 'Déconnexion réussie. '.$bye);
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login.index')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
