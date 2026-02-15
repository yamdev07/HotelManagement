<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@hotel.test'],
            [
                'name' => 'Demo Admin',
                'password' => Hash::make('password123'),
                'random_key' => Str::random(32), // ✅ required field
            ]
        );
    }
}
