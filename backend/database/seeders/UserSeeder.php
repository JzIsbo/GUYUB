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
                'role' => 'Super Admin',
                'status' => 'Aktif',
            ],
            [
                'name' => 'Ketua RW',
                'email' => 'rw@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'RW',
                'status' => 'Aktif',
            ],
            [
                'name' => 'Sekretaris RW',
                'email' => 'sekretarisrw@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'Sekretaris RW',
                'status' => 'Aktif',
            ],
            [
                'name' => 'Bendahara RW',
                'email' => 'bendahararw@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'Bendahara RW',
                'status' => 'Aktif',
            ],
            [
                'name' => 'Ketua RT',
                'email' => 'rt@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'RT',
                'status' => 'Aktif',
            ],
            [
                'name' => 'Sekretaris RT',
                'email' => 'sekretaris@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'Sekretaris RT',
                'status' => 'Aktif',
            ],
            [
                'name' => 'Bendahara RT',
                'email' => 'bendahara@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'Bendahara RT',
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
            User::updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }
    }
}
