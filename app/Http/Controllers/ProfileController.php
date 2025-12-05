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
            'name' => 'required|string|max:255',
            'email' => [
                'required', 'email', Rule::unique('users')->ignore(Auth::id())
            ],
            'phone' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();
        $user->update($request->only('name', 'email', 'phone'));

        return back()->with('success', 'Informations mises à jour avec succès.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->with('error', 'Le mot de passe actuel est incorrect.');
        }

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Mot de passe modifié avec succès.');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|max:2048'
        ]);

        $user = Auth::user();

        $imageName = 'avatar_' . time() . '.' . $request->avatar->extension();
        $request->avatar->move(public_path('img/user'), $imageName);

        $user->avatar = '/img/user/' . $imageName;
        $user->save();

        return back()->with('success', 'Photo de profil mise à jour.');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min(6|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return back()->with('success', 'Profil mis à jour avec succès !');
    }

}
