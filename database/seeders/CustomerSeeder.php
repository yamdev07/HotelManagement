<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        $customersData = [
            [
                'name' => 'Jean Dupont',
                'email' => 'jean.dupont@gmail.com',
                'phone' => '0788123456',
                'gender' => 'Male',
                'job' => 'Architecte',
                'address' => 'Avenue Steinmetz, Cotonou',
                'nationality' => 'Béninoise',
                'avatar' => 'https://i.pravatar.cc/150?u=jean'
            ],
            [
                'name' => 'Marie Akpotrossou',
                'email' => 'marie.akp@yahoo.fr',
                'phone' => '0699223344',
                'gender' => 'Female',
                'job' => 'Médecin',
                'address' => 'quartier Haie Vive, Cotonou',
                'nationality' => 'Béninoise',
                'avatar' => 'https://i.pravatar.cc/150?u=marie'
            ],
            [
                'name' => 'Marc Keller',
                'email' => 'm.keller@outlook.com',
                'phone' => '0744556677',
                'gender' => 'Male',
                'job' => 'Consultant IT',
                'address' => 'Rue 120, Abomey-Calavi',
                'nationality' => 'Allemande',
                'avatar' => 'https://i.pravatar.cc/150?u=marc'
            ],
            [
                'name' => 'Sophie Lawson',
                'email' => 'sophie.l@gmail.com',
                'phone' => '0611889900',
                'gender' => 'Female',
                'job' => 'Designer',
                'address' => 'Zongo, Parakou',
                'nationality' => 'Togolaise',
                'avatar' => 'https://i.pravatar.cc/150?u=sophie'
            ],
            [
                'name' => 'Paul Yao',
                'email' => 'pyao@entreprise.ci',
                'phone' => '0755443322',
                'gender' => 'Male',
                'job' => 'Directeur Commercial',
                'address' => 'Cocody, Abidjan',
                'nationality' => 'Ivoirienne',
                'avatar' => 'https://i.pravatar.cc/150?u=paul'
            ],
        ];

        foreach ($customersData as $data) {
            // Créer ou mettre à jour un utilisateur pour le client
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'avatar' => $data['avatar'],
                    'role' => 'Customer',
                    'random_key' => Str::random(10),
                ]
            );

            Customer::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'gender' => $data['gender'],
                    'job' => $data['job'],
                    'address' => $data['address'],
                    'birthdate' => '1990-01-01',
                ]
            );
        }
    }
}
