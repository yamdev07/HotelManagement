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
        if (Auth::attempt($request->only('email', 'password'))) {
            activity()->causedBy(auth()->user())->log('User logged into the portal');

            // Redirection intelligente selon le rôle
            if (auth()->user()->role === 'Customer') {
                return redirect()->route('transaction.myReservations')->with('success', 'Bienvenue ' . auth()->user()->name);
            }

            return redirect('/home')->with('success', 'Welcome ' . auth()->user()->name);
        }

        return redirect('login')->with('failed', 'Incorrect email / password');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
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
        // CORRECTION ICI : Sauvegardez le nom AVANT de déconnecter
        $name = auth()->user()->name;

        // Déconnexion complète
        Auth::logout();

        // Invalide la session (important!)
        session()->invalidate();

        // Régénère le token CSRF
        session()->regenerateToken();

        return redirect('login')->with('success', 'Logout success, goodbye '.$name);
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
