<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {



        $users = [
            [
                'name' => 'admin',
                'email' => 'admin@example.com',
                'password' => '123456',
                'role' => 'admin',
            ],
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => '123456',
                'role' => 'user',
            ]
        ];
        foreach ($users as $user) {
            $created_user =   User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make($user['password']),
            ]);
            $created_user->assignRole($user['role']);
        }
    }
}
