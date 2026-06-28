<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Support\Facades\Hash;

class UserKepalaKeluargaSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil HANYA data warga yang berstatus 'Kepala Keluarga'
        $kepalaKeluarga = Warga::where('status_keluarga', 'Kepala Keluarga')->get();

        // 2. Lakukan perulangan untuk membuatkan akun masing-masing
        foreach ($kepalaKeluarga as $kk) {

            // Membuat email unik otomatis: nama (tanpa spasi/tanda baca) + ID warga
            $namaBersih = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $kk->nama_lengkap));
            $emailOtomatis = $namaBersih . $kk->id . '@gmail.com';

            // 3. Masukkan ke tabel users
            User::create([
                'name'     => $kk->nama_lengkap,
                'email'    => $emailOtomatis,
                'password' => Hash::make('password'), // Semua password default diset 'password'
                'role'     => 'Warga', // Set hak akses hanya sebagai warga

                // Catatan: Jika di tabel/model User Anda nama kolomnya berbeda, sesuaikan di bawah ini.
                // Misalnya jika Anda menggunakan kolom 'hak_akses' atau 'status':
                // 'hak_akses' => 'Warga',
                // 'status'    => 'Aktif',
            ]);
        }
    }
}
