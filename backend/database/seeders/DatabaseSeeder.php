<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warga;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // PERHATIAN: Warga::truncate(); SENGJA DIHAPUS agar 87 data lama Anda TIDAK hilang.

        $faker = Faker::create('id_ID');
        $blok_list = ['Blok A1', 'Blok A2', 'Blok B1', 'Blok C1', 'Blok D2', 'Blok E1', 'Blok F1'];

        $totalDataBaru = 0;
        $targetDataBaru = 96; // Mengunci target tepat 96 jiwa baru

        // Perulangan akan terus berjalan hingga tepat menghasilkan 96 orang baru
        while ($totalDataBaru < $targetDataBaru) {

            // Generate identitas dasar untuk satu kelompok keluarga baru
            $nomor_kk = $faker->unique()->numerify('327##########');
            $blok_rumah = $faker->randomElement($blok_list);
            $domisili = $faker->randomElement(['Tetap', 'Kontrak', 'Kos']);

            // 1. Membuat Kepala Keluarga (Ayah)
            Warga::create([
                'nomor_kk'        => $nomor_kk,
                'nik'             => $faker->unique()->numerify('327##########'),
                'nama_lengkap'    => $faker->name('male'),
                'no_telepon'      => $faker->phoneNumber(),
                'blok_rumah'      => $blok_rumah,
                'status_keluarga' => 'Kepala Keluarga',
                'status_domisili' => $domisili,
            ]);
            $totalDataBaru++;
            if ($totalDataBaru >= $targetDataBaru) break;

            // 2. Membuat Istri
            Warga::create([
                'nomor_kk'        => $nomor_kk,
                'nik'             => $faker->unique()->numerify('327##########'),
                'nama_lengkap'    => $faker->name('female'),
                'no_telepon'      => $faker->phoneNumber(),
                'blok_rumah'      => $blok_rumah,
                'status_keluarga' => 'Istri',
                'status_domisili' => $domisili,
            ]);
            $totalDataBaru++;
            if ($totalDataBaru >= $targetDataBaru) break;

            // 3. Membuat Anak (Acak antara 1 sampai 3 anak)
            $jumlah_anak = rand(1, 3);
            for ($j = 0; $j < $jumlah_anak; $j++) {
                if ($totalDataBaru >= $targetDataBaru) break;

                Warga::create([
                    'nomor_kk'        => $nomor_kk,
                    'nik'             => $faker->unique()->numerify('327##########'),
                    'nama_lengkap'    => $faker->name(),
                    'no_telepon'      => null,
                    'blok_rumah'      => $blok_rumah,
                    'status_keluarga' => 'Anak',
                    'status_domisili' => $domisili,
                ]);
                $totalDataBaru++;
            }
        }
    }
}
