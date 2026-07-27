<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('profile.index', compact('user'));
    }

    public function updateInfo(Request $request)
    {
        $request->validate([
            // Nom sans emoji ni charabia (issue #197, comme les clients)
            'name' => ['required', 'string', 'max:255', new \App\Rules\SafeName],
            'email' => ['required', 'email', Rule::unique('users')->ignore(Auth::id())],
            // Téléphone : chiffres et séparateurs uniquement (pas de lettres/emoji)
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\s().\-]{6,20}$/'],
        ], [
            'phone.regex' => __('flash.profile_invalid_phone'),
            'email.unique' => __('flash.profile_email_taken'),
        ], ['name' => 'nom', 'email' => 'email', 'phone' => 'téléphone']);

        $user = Auth::user();
        $user->update($request->only('name', 'email', 'phone'));

        return back()->with('success', __('flash.profile_updated'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', new \App\Rules\StrongPassword],
        ]);

        if (! Hash::check($request->current_password, Auth::user()->password)) {
            return back()->with('error', __('flash.profile_wrong_password'));
        }

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', __('flash.profile_password_changed'));
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
        ]);

        $user = Auth::user();

        try {
            // Stockage sur le disque "public" (comme les logos) : fiable sur
            // hébergement mutualisé, servi via le lien/route /storage.
            $path = $request->file('avatar')->store('avatars', 'public');
        } catch (\Throwable $e) {
            \Log::error('Upload avatar échoué: '.$e->getMessage());

            return back()->with('error', __('flash.profile_avatar_error'));
        }

        // Supprime l'ancien avatar s'il était sur le disque public
        if ($user->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
        }

        $user->avatar = $path;
        $user->save();

        return back()->with('success', __('flash.profile_avatar_updated'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255', new \App\Rules\SafeName],
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => ['nullable', 'confirmed', new \App\Rules\StrongPassword],
        ], [
            'email.unique' => __('flash.profile_email_taken'),
        ], ['name' => 'nom', 'email' => 'email']);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return back()->with('success', __('flash.profile_updated_success'));
    }
}
