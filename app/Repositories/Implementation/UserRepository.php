<?php

namespace App\Repositories\Implementation;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Str;

class UserRepository implements UserRepositoryInterface
{
    public function store($userData)
    {
        $user = new User;
        $user->name = $userData->name;
        $user->email = $userData->email;
        $user->password = bcrypt($userData->password);
        $user->role = $userData->role;
        $user->random_key = Str::random(60);
        $user->save();

        return $user;
    }

    public function showUser($request)
    {
        return User::whereIn('role', ['Super', 'Admin', 'Receptionist', 'Housekeeping', 'Servant', 'Cuisiner']) // ✅ TOUS les rôles sauf Customer
            ->orderBy('id', 'DESC')
            ->when($request->qu, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('email', 'LIKE', '%'.$request->qu.'%')
                      ->orWhere('name', 'LIKE', '%'.$request->qu.'%'); // ✅ Ajout de la recherche par nom
                });
            })
            ->paginate(10, ['*'], 'users') // Passez à 10 par page pour plus de cohérence
            ->appends($request->all());
    }

    public function showCustomer($request)
    {
        return User::where('role', 'Customer')
            ->orderBy('id', 'DESC')
            ->when($request->qc, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('email', 'LIKE', '%'.$request->qc.'%')
                      ->orWhere('name', 'LIKE', '%'.$request->qc.'%'); // ✅ Ajout de la recherche par nom
                });
            })
            ->paginate(10, ['*'], 'customers')
            ->appends($request->all());
    }
}