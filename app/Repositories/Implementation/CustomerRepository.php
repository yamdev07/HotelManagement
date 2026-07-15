<?php

namespace App\Repositories\Implementation;

use App\Models\Customer;
use App\Models\User;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use Illuminate\Support\Str;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function get($request)
    {
        return Customer::with('user')->orderBy('id', 'DESC')
            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'Like', '%'.$request->search.'%')
                    ->orWhere('id', 'Like', '%'.$request->search.'%')
                    ->orWhere('phone', 'Like', '%'.$request->search.'%')
                    ->orWhere('email', 'Like', '%'.$request->search.'%');
            })
            ->paginate(8)
            ->appends($request->all());
    }

    public function count($request)
    {
        return Customer::with('user')->orderBy('id', 'DESC')
            ->when($request->q, function ($query) use ($request) {
                $query->where('name', 'Like', '%'.$request->q.'%')
                    ->orWhere('id', 'Like', '%'.$request->q.'%');
            })
            ->count();
    }

    public static function store($request)
    {
        // La fiche client (scopée par hôtel) suffit aux opérations. Le compte de
        // connexion (users, email GLOBALEMENT unique) n'est créé que si l'email est
        // libre — ainsi le MÊME client peut exister dans plusieurs hôtels sans conflit.
        $user = null;
        if ($request->email && ! User::where('email', $request->email)->exists()) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->birthdate),
                'role' => 'Customer',
                'random_key' => Str::random(60),
            ]);

            if ($request->hasFile('avatar')) {
                $path = 'img/user/'.$user->name.'-'.$user->id;
                $path = public_path($path);
                $file = $request->file('avatar');

                $imageRepository = new ImageRepository;

                $imageRepository->uploadImage($path, $file);

                $user->avatar = $file->getClientOriginalName();
                $user->save();
            }
        }

        return Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'user_id' => $user?->id,
            'address' => $request->address,
            'job' => $request->job,
            'birthdate' => $request->birthdate,
            'gender' => $request->gender,
        ]);
    }
}
