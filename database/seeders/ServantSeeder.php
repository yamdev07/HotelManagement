<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ServantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'servant@cactus.com'],
            [
                'name' => 'Jean Serveur',
                'password' => Hash::make('servant123'),
                'role' => 'Servant',
                'random_key' => Str::random(60),
            ]
        );

        User::updateOrCreate(
            ['email' => 'marie@cactus.com'],
            [
                'name' => 'Marie Akpotrossou',
                'password' => Hash::make('servant123'),
                'role' => 'Servant',
                'random_key' => Str::random(60),
            ]
        );
    }
}
