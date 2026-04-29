<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // User::factory(5)->create();
        User::updateOrCreate(
            ['email' => 'wailantirajoh@gmail.com'],
            [
                'name' => 'Wailan Tirajoh',
                'password' => Hash::make('wailan'),
                'role' => 'Super',
                'random_key' => Str::random(60),
            ]
        );
    }
}
