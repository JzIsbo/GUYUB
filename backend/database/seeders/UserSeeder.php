<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'Super Admin', // Harus persis dengan Enum
                'status' => 'Aktif',     // Harus persis dengan Enum
            ],
            [
                'name' => 'RT',
                'email' => 'rt@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'RT',
                'status' => 'Aktif',
            ],
            [
                'name' => 'Bendahara',
                'email' => 'bendahara@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'Bendahara',
                'status' => 'Aktif',
            ],
            [
                'name' => 'Warga',
                'email' => 'warga@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'Warga',
                'status' => 'Aktif',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
