<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Warga;

class CompleteMasterSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $randomElement = function(array $arr) {
            return $arr[array_rand($arr)];
        };

        $maleNames = [
            'Ahmad Fauzi', 'Budi Santoso', 'Chandra Wijaya', 'Dedi Kurniawan', 'Eko Prasetyo',
            'Fajar Nugroho', 'Gunawan Wibowo', 'Hendra Setiawan', 'Indra Permana', 'Joko Susilo',
            'Lukman Hakim', 'Maulana Malik', 'Rian Hidayat', 'Surya Saputra', 'Teguh Purnomo',
            'Wahyu Ramadhan', 'Yudi Pratama', 'Zainal Abidin', 'Agus Supriyadi', 'Bambang Irawan'
        ];

        $femaleNames = [
            'Ayu Lestari', 'Citra Dewi', 'Dewi Sartika', 'Endang Rahayu', 'Fitri Handayani',
            'Gita Puspita', 'Hani Novitasari', 'Indah Permatasari', 'Lina Marlina', 'Maya Anggraini',
            'Nita Yuliana', 'Putri Wulandari', 'Rina Marlina', 'Siti Maryam', 'Sri Wahyuni',
            'Tri Astuti', 'Utami Ningsih', 'Wulan Dari', 'Yulia Safitri', 'Zahra Amalia'
        ];

        $childNames = [
            'Aditya Pratama', 'Aldi Kurniawan', 'Alif Ramadhan', 'Anisa Rahmawati', 'Aqila Putri',
            'Arya Sena', 'Bilal Haidar', 'Clarissa Aurelia', 'Danendra Farhan', 'Dhea Ananda',
            'Farrel Abimanyu', 'Genta Buana', 'Haikal Rabbani', 'Keyla Az-Zahra', 'Kevin Sanjaya',
            'Naura Salsabila', 'Raffi Ahmad', 'Rizky Billar', 'Siti Nurhaliza', 'Zidan Al-Ghifari'
        ];

        echo "🚀 MEMULAI SEEDING MASTER DATA LENGKAP GUYUB...\n\n";

        // ========================================================
        // 1. SEED KATEGORI KAS RT
        // ========================================================
        echo "1️⃣  Mengisi Kategori Kas RT...\n";
        $categories = [
            // Pemasukan
            ['nama' => 'Iuran Warga', 'deskripsi' => 'Iuran rutin bulanan warga RT (kebersihan, keamanan, kas)', 'tipe' => 'pemasukan'],
            ['nama' => 'Dana Hibah & Donasi', 'deskripsi' => 'Sumbangan sukarela warga, donatur, dan bantuan kelurahan', 'tipe' => 'pemasukan'],
            ['nama' => 'Penyewaan Fasilitas & Aset', 'deskripsi' => 'Sewa tenda, kursi, dan sound system untuk hajatan/acara', 'tipe' => 'pemasukan'],
            ['nama' => 'Bunga Bank & Pendapatan Lain', 'deskripsi' => 'Bagi hasil rekening bank RT dan penerimaan lain', 'tipe' => 'pemasukan'],

            // Pengeluaran
            ['nama' => 'Biaya Keamanan & Ronda', 'deskripsi' => 'Honor satpam, perlengkapan pos keamanan, HT, baterai', 'tipe' => 'pengeluaran'],
            ['nama' => 'Biaya Kebersihan & Sampah', 'deskripsi' => 'Honor petugas sampah, perawatan tong sampah, armada gerobak', 'tipe' => 'pengeluaran'],
            ['nama' => 'Biaya Operasional & ATK', 'deskripsi' => 'Kertas, tinta printer, internet balai RT, listrik pos ronda', 'tipe' => 'pengeluaran'],
            ['nama' => 'Kegiatan Sosial & Kematian', 'deskripsi' => 'Santunan duka cita, konsumsi rapat warga, snack posyandu', 'tipe' => 'pengeluaran'],
            ['nama' => 'Pembangunan & Prasarana', 'deskripsi' => 'Perbaikan jalan lingkungan, penerangan lampu jalan, pengecatan', 'tipe' => 'pengeluaran'],
        ];

        foreach ($categories as $cat) {
            DB::table('categories')->updateOrInsert(
                ['nama' => $cat['nama']],
                array_merge($cat, ['created_at' => $now, 'updated_at' => $now])
            );
        }

        // ========================================================
        // 2. SEED WARGA (MINIMAL 100 JIWA DENGAN BLOK & KK LENGKAP)
        // ========================================================
        echo "2️⃣  Mengisi Data Warga (Keluarga, KK, Demografi)...\n";
        $currentWargaCount = DB::table('wargas')->count();
        $targetWarga = 100;

        if ($currentWargaCount < $targetWarga) {
            $blok_list = ['Blok A1', 'Blok A2', 'Blok B1', 'Blok B2', 'Blok C1', 'Blok C2', 'Blok D1', 'Blok D2', 'Blok E1', 'Blok F1'];
            $familiesToSeed = [
                // Keluarga Sesepuh / Tokoh RT
                [
                    'ayah' => ['nama' => 'Haji Soekarno Hadi', 'umur' => 68, 'pekerjaan' => 'Pensiunan PNS'],
                    'ibu'  => ['nama' => 'Hj. Siti Aminah', 'umur' => 64],
                    'anak' => [
                        ['nama' => 'Bambang Soekarno', 'umur' => 38, 'telp' => '081234567890'],
                        ['nama' => 'Dewi Lestari Soekarno', 'umur' => 32, 'telp' => '081298765432']
                    ]
                ],
                [
                    'ayah' => ['nama' => 'R. Soeharto Prawiro', 'umur' => 71, 'pekerjaan' => 'Pensiunan BUMN'],
                    'ibu'  => ['nama' => 'Kartini Prawiro', 'umur' => 66],
                    'anak' => [
                        ['nama' => 'Agus Prawiro', 'umur' => 41, 'telp' => '081311223344']
                    ]
                ],
                [
                    'ayah' => ['nama' => 'Drs. Hendro Wibowo', 'umur' => 52, 'pekerjaan' => 'Wiraswasta'],
                    'ibu'  => ['nama' => 'Ratna Juwita', 'umur' => 48],
                    'anak' => [
                        ['nama' => 'Dimas Aditya Wibowo', 'umur' => 22, 'telp' => '085712341234'],
                        ['nama' => 'Anisa Putri Wibowo', 'umur' => 16, 'telp' => null],
                        ['nama' => 'Rizky Pratama Wibowo', 'umur' => 10, 'telp' => null]
                    ]
                ],
                [
                    'ayah' => ['nama' => 'Ir. Muhammad Firdaus', 'umur' => 46, 'pekerjaan' => 'Karyawan Swasta'],
                    'ibu'  => ['nama' => 'Nurul Hidayati', 'umur' => 43],
                    'anak' => [
                        ['nama' => 'Fikri Haikal Firdaus', 'umur' => 19, 'telp' => '087812349999'],
                        ['nama' => 'Nabila Syakira Firdaus', 'umur' => 14, 'telp' => null]
                    ]
                ],
                [
                    'ayah' => ['nama' => 'Joko Susanto', 'umur' => 39, 'pekerjaan' => 'Pedagang'],
                    'ibu'  => ['nama' => 'Sri Wahyuni', 'umur' => 36],
                    'anak' => [
                        ['nama' => 'Aldi Kurniawan Susanto', 'umur' => 12, 'telp' => null],
                        ['nama' => 'Zahra Aulia Susanto', 'umur' => 6, 'telp' => null]
                    ]
                ],
            ];

            foreach ($familiesToSeed as $f) {
                $nomor_kk = '3275' . str_pad(rand(100000000000, 999999999999), 12, '0', STR_PAD_LEFT);
                $blok = $randomElement($blok_list) . ' No. ' . rand(1, 30);

                // Ayah
                if (!DB::table('wargas')->where('nama_lengkap', $f['ayah']['nama'])->exists()) {
                    DB::table('wargas')->insert([
                        'nomor_kk'        => $nomor_kk,
                        'nik'             => '3275' . str_pad(rand(100000000000, 999999999999), 12, '0', STR_PAD_LEFT),
                        'nama_lengkap'    => $f['ayah']['nama'],
                        'umur'            => $f['ayah']['umur'],
                        'agama'           => 'Islam',
                        'no_telepon'      => '08' . rand(1111111111, 9999999999),
                        'blok_rumah'      => $blok,
                        'status_keluarga' => 'Kepala Keluarga',
                        'status_domisili' => 'Tetap',
                        'created_at'      => $now,
                        'updated_at'      => $now,
                    ]);
                }

                // Ibu
                if (!DB::table('wargas')->where('nama_lengkap', $f['ibu']['nama'])->exists()) {
                    DB::table('wargas')->insert([
                        'nomor_kk'        => $nomor_kk,
                        'nik'             => '3275' . str_pad(rand(100000000000, 999999999999), 12, '0', STR_PAD_LEFT),
                        'nama_lengkap'    => $f['ibu']['nama'],
                        'umur'            => $f['ibu']['umur'],
                        'agama'           => 'Islam',
                        'no_telepon'      => '08' . rand(1111111111, 9999999999),
                        'blok_rumah'      => $blok,
                        'status_keluarga' => 'Istri',
                        'status_domisili' => 'Tetap',
                        'created_at'      => $now,
                        'updated_at'      => $now,
                    ]);
                }

                // Anak
                foreach ($f['anak'] as $ak) {
                    if (!DB::table('wargas')->where('nama_lengkap', $ak['nama'])->exists()) {
                        DB::table('wargas')->insert([
                            'nomor_kk'        => $nomor_kk,
                            'nik'             => '3275' . str_pad(rand(100000000000, 999999999999), 12, '0', STR_PAD_LEFT),
                            'nama_lengkap'    => $ak['nama'],
                            'umur'            => $ak['umur'],
                            'agama'           => 'Islam',
                            'no_telepon'      => $ak['telp'] ?? null,
                            'blok_rumah'      => $blok,
                            'status_keluarga' => 'Anak',
                            'status_domisili' => 'Tetap',
                            'created_at'      => $now,
                            'updated_at'      => $now,
                        ]);
                    }
                }
            }

            // Tambah keluarga acak sampai minimal 75 warga
            while (DB::table('wargas')->count() < 80) {
                $nomor_kk = '3275' . str_pad(rand(100000000000, 999999999999), 12, '0', STR_PAD_LEFT);
                $blok = $randomElement($blok_list) . ' No. ' . rand(1, 35);
                $domisili = $randomElement(['Tetap', 'Tetap', 'Kontrak']);

                // Kepala Keluarga
                DB::table('wargas')->insert([
                    'nomor_kk'        => $nomor_kk,
                    'nik'             => '3275' . str_pad(rand(100000000000, 999999999999), 12, '0', STR_PAD_LEFT),
                    'nama_lengkap'    => $randomElement($maleNames) . ' ' . rand(1, 99),
                    'umur'            => rand(28, 62),
                    'agama'           => 'Islam',
                    'no_telepon'      => '08' . rand(1111111111, 9999999999),
                    'blok_rumah'      => $blok,
                    'status_keluarga' => 'Kepala Keluarga',
                    'status_domisili' => $domisili,
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ]);

                // Istri
                DB::table('wargas')->insert([
                    'nomor_kk'        => $nomor_kk,
                    'nik'             => '3275' . str_pad(rand(100000000000, 999999999999), 12, '0', STR_PAD_LEFT),
                    'nama_lengkap'    => $randomElement($femaleNames) . ' ' . rand(1, 99),
                    'umur'            => rand(25, 58),
                    'agama'           => 'Islam',
                    'no_telepon'      => '08' . rand(1111111111, 9999999999),
                    'blok_rumah'      => $blok,
                    'status_keluarga' => 'Istri',
                    'status_domisili' => $domisili,
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ]);

                // Anak
                $anakCount = rand(1, 3);
                for ($a = 0; $a < $anakCount; $a++) {
                    DB::table('wargas')->insert([
                        'nomor_kk'        => $nomor_kk,
                        'nik'             => '3275' . str_pad(rand(100000000000, 999999999999), 12, '0', STR_PAD_LEFT),
                        'nama_lengkap'    => $randomElement($childNames) . ' ' . rand(1, 99),
                        'umur'            => rand(3, 24),
                        'agama'           => 'Islam',
                        'no_telepon'      => rand(0, 1) ? '08' . rand(1111111111, 9999999999) : null,
                        'blok_rumah'      => $blok,
                        'status_keluarga' => 'Anak',
                        'status_domisili' => $domisili,
                        'created_at'      => $now,
                        'updated_at'      => $now,
                    ]);
                }
            }
        }

        // Pastikan akun demo 'Warga' (warga@gmail.com) memiliki data keluarga lengkap
        $kkWarga = '3275990011223344';
        if (!DB::table('wargas')->where('nama_lengkap', 'Warga')->exists()) {
            DB::table('wargas')->insert([
                [
                    'nomor_kk'        => $kkWarga,
                    'nik'             => '3275010101850001',
                    'nama_lengkap'    => 'Warga',
                    'jenis_kelamin'   => 'Laki-laki',
                    'umur'            => 41,
                    'agama'           => 'Islam',
                    'no_telepon'      => '081234567800',
                    'blok_rumah'      => 'Blok A1 No. 01',
                    'status_keluarga' => 'Kepala Keluarga',
                    'status_domisili' => 'Tetap',
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ],
                [
                    'nomor_kk'        => $kkWarga,
                    'nik'             => '3275010202880002',
                    'nama_lengkap'    => 'Siti Rahayu',
                    'jenis_kelamin'   => 'Perempuan',
                    'umur'            => 38,
                    'agama'           => 'Islam',
                    'no_telepon'      => '081234567801',
                    'blok_rumah'      => 'Blok A1 No. 01',
                    'status_keluarga' => 'Istri',
                    'status_domisili' => 'Tetap',
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ],
                [
                    'nomor_kk'        => $kkWarga,
                    'nik'             => '3275010303100003',
                    'nama_lengkap'    => 'Rian Pratama',
                    'jenis_kelamin'   => 'Laki-laki',
                    'umur'            => 15,
                    'agama'           => 'Islam',
                    'no_telepon'      => null,
                    'blok_rumah'      => 'Blok A1 No. 01',
                    'status_keluarga' => 'Anak',
                    'status_domisili' => 'Tetap',
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ],
                [
                    'nomor_kk'        => $kkWarga,
                    'nik'             => '3275010404160004',
                    'nama_lengkap'    => 'Alya Putri',
                    'jenis_kelamin'   => 'Perempuan',
                    'umur'            => 9,
                    'agama'           => 'Islam',
                    'no_telepon'      => null,
                    'blok_rumah'      => 'Blok A1 No. 01',
                    'status_keluarga' => 'Anak',
                    'status_domisili' => 'Tetap',
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ]
            ]);
        }

        // Pastikan akun Steve Marvin Politon memiliki data keluarga lengkap
        $steve = DB::table('wargas')->where('nama_lengkap', 'Steve Marvin Politon')->first();
        if ($steve && DB::table('wargas')->where('nomor_kk', $steve->nomor_kk)->count() == 1) {
            DB::table('wargas')->insert([
                [
                    'nomor_kk'        => $steve->nomor_kk,
                    'nik'             => '3275636752765770',
                    'nama_lengkap'    => 'Clara Politon',
                    'jenis_kelamin'   => 'Perempuan',
                    'umur'            => 24,
                    'agama'           => 'Kristen',
                    'no_telepon'      => '082199887766',
                    'blok_rumah'      => $steve->blok_rumah,
                    'status_keluarga' => 'Istri',
                    'status_domisili' => 'Tetap',
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ],
                [
                    'nomor_kk'        => $steve->nomor_kk,
                    'nik'             => '3275636752765771',
                    'nama_lengkap'    => 'Baby Marvin Politon',
                    'jenis_kelamin'   => 'Laki-laki',
                    'umur'            => 2,
                    'agama'           => 'Kristen',
                    'no_telepon'      => null,
                    'blok_rumah'      => $steve->blok_rumah,
                    'status_keluarga' => 'Anak',
                    'status_domisili' => 'Tetap',
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ]
            ]);
        }

        // ========================================================
        // KARTU KELUARGA LENGKAP UNTUK SEMUA ROLE (KECUALI SUPER ADMIN)
        // ========================================================
        // 1. Keluarga Ketua RW
        $kkKetuaRW = '3275020011220001';
        if (!DB::table('wargas')->where('nama_lengkap', 'Ketua RW')->exists()) {
            DB::table('wargas')->insert([
                ['nomor_kk' => $kkKetuaRW, 'nik' => '3275020101700001', 'nama_lengkap' => 'Ketua RW', 'jenis_kelamin' => 'Laki-laki', 'umur' => 55, 'agama' => 'Islam', 'no_telepon' => '081122334401', 'blok_rumah' => 'Blok D1 No. 01', 'status_keluarga' => 'Kepala Keluarga', 'status_domisili' => 'Tetap', 'created_at' => $now, 'updated_at' => $now],
                ['nomor_kk' => $kkKetuaRW, 'nik' => '3275020202740002', 'nama_lengkap' => 'Hj. Endang Suryaningsih', 'jenis_kelamin' => 'Perempuan', 'umur' => 51, 'agama' => 'Islam', 'no_telepon' => '081122334402', 'blok_rumah' => 'Blok D1 No. 01', 'status_keluarga' => 'Istri', 'status_domisili' => 'Tetap', 'created_at' => $now, 'updated_at' => $now],
                ['nomor_kk' => $kkKetuaRW, 'nik' => '3275020303990003', 'nama_lengkap' => 'Aditya Purnomo, ST', 'jenis_kelamin' => 'Laki-laki', 'umur' => 26, 'agama' => 'Islam', 'no_telepon' => '081122334403', 'blok_rumah' => 'Blok D1 No. 01', 'status_keluarga' => 'Anak', 'status_domisili' => 'Tetap', 'created_at' => $now, 'updated_at' => $now],
                ['nomor_kk' => $kkKetuaRW, 'nik' => '3275020404060004', 'nama_lengkap' => 'Annisa Purnomo', 'jenis_kelamin' => 'Perempuan', 'umur' => 19, 'agama' => 'Islam', 'no_telepon' => null, 'blok_rumah' => 'Blok D1 No. 01', 'status_keluarga' => 'Anak', 'status_domisili' => 'Tetap', 'created_at' => $now, 'updated_at' => $now],
            ]);
        }

        // 2. Keluarga Sekretaris RW
        $kkSekretarisRW = '3275020011220002';
        if (!DB::table('wargas')->where('nama_lengkap', 'Sekretaris RW')->exists()) {
            DB::table('wargas')->insert([
                ['nomor_kk' => $kkSekretarisRW, 'nik' => '3275020101770001', 'nama_lengkap' => 'Sekretaris RW', 'jenis_kelamin' => 'Laki-laki', 'umur' => 48, 'agama' => 'Islam', 'no_telepon' => '081122334411', 'blok_rumah' => 'Blok D2 No. 05', 'status_keluarga' => 'Kepala Keluarga', 'status_domisili' => 'Tetap', 'created_at' => $now, 'updated_at' => $now],
                ['nomor_kk' => $kkSekretarisRW, 'nik' => '3275020202800002', 'nama_lengkap' => 'Tri Wahyuni, S.Pd', 'jenis_kelamin' => 'Perempuan', 'umur' => 45, 'agama' => 'Islam', 'no_telepon' => '081122334412', 'blok_rumah' => 'Blok D2 No. 05', 'status_keluarga' => 'Istri', 'status_domisili' => 'Tetap', 'created_at' => $now, 'updated_at' => $now],
                ['nomor_kk' => $kkSekretarisRW, 'nik' => '3275020303070003', 'nama_lengkap' => 'Bagus Dwi Susanto', 'jenis_kelamin' => 'Laki-laki', 'umur' => 18, 'agama' => 'Islam', 'no_telepon' => null, 'blok_rumah' => 'Blok D2 No. 05', 'status_keluarga' => 'Anak', 'status_domisili' => 'Tetap', 'created_at' => $now, 'updated_at' => $now],
                ['nomor_kk' => $kkSekretarisRW, 'nik' => '3275020404130004', 'nama_lengkap' => 'Citra Lestari Susanto', 'jenis_kelamin' => 'Perempuan', 'umur' => 12, 'agama' => 'Islam', 'no_telepon' => null, 'blok_rumah' => 'Blok D2 No. 05', 'status_keluarga' => 'Anak', 'status_domisili' => 'Tetap', 'created_at' => $now, 'updated_at' => $now],
            ]);
        }

        // 3. Keluarga Bendahara RW
        $kkBendaharaRW = '3275020011220003';
        if (!DB::table('wargas')->where('nama_lengkap', 'Bendahara RW')->exists()) {
            DB::table('wargas')->insert([
                ['nomor_kk' => $kkBendaharaRW, 'nik' => '3275020101750001', 'nama_lengkap' => 'Bendahara RW', 'jenis_kelamin' => 'Laki-laki', 'umur' => 50, 'agama' => 'Islam', 'no_telepon' => '081122334421', 'blok_rumah' => 'Blok D1 No. 08', 'status_keluarga' => 'Kepala Keluarga', 'status_domisili' => 'Tetap', 'created_at' => $now, 'updated_at' => $now],
                ['nomor_kk' => $kkBendaharaRW, 'nik' => '3275020202780002', 'nama_lengkap' => 'Ratna Kusuma Dewi, SE', 'jenis_kelamin' => 'Perempuan', 'umur' => 47, 'agama' => 'Islam', 'no_telepon' => '081122334422', 'blok_rumah' => 'Blok D1 No. 08', 'status_keluarga' => 'Istri', 'status_domisili' => 'Tetap', 'created_at' => $now, 'updated_at' => $now],
                ['nomor_kk' => $kkBendaharaRW, 'nik' => '3275020303040003', 'nama_lengkap' => 'Gilang Ramadhan Wibisono', 'jenis_kelamin' => 'Laki-laki', 'umur' => 21, 'agama' => 'Islam', 'no_telepon' => '081122334423', 'blok_rumah' => 'Blok D1 No. 08', 'status_keluarga' => 'Anak', 'status_domisili' => 'Tetap', 'created_at' => $now, 'updated_at' => $now],
            ]);
        }

        // 4. Keluarga Ketua RT
        $kkKetuaRT = '3275050011220001';
        if (!DB::table('wargas')->where('nama_lengkap', 'Ketua RT')->exists()) {
            DB::table('wargas')->insert([
                ['nomor_kk' => $kkKetuaRT, 'nik' => '3275050101780001', 'nama_lengkap' => 'Ketua RT', 'jenis_kelamin' => 'Laki-laki', 'umur' => 47, 'agama' => 'Islam', 'no_telepon' => '081122334431', 'blok_rumah' => 'Blok A2 No. 10', 'status_keluarga' => 'Kepala Keluarga', 'status_domisili' => 'Tetap', 'created_at' => $now, 'updated_at' => $now],
                ['nomor_kk' => $kkKetuaRT, 'nik' => '3275050202820002', 'nama_lengkap' => 'Indah Permatasari', 'jenis_kelamin' => 'Perempuan', 'umur' => 43, 'agama' => 'Islam', 'no_telepon' => '081122334432', 'blok_rumah' => 'Blok A2 No. 10', 'status_keluarga' => 'Istri', 'status_domisili' => 'Tetap', 'created_at' => $now, 'updated_at' => $now],
                ['nomor_kk' => $kkKetuaRT, 'nik' => '3275050303080003', 'nama_lengkap' => 'Rifki Pratama Prasetyo', 'jenis_kelamin' => 'Laki-laki', 'umur' => 17, 'agama' => 'Islam', 'no_telepon' => null, 'blok_rumah' => 'Blok A2 No. 10', 'status_keluarga' => 'Anak', 'status_domisili' => 'Tetap', 'created_at' => $now, 'updated_at' => $now],
                ['nomor_kk' => $kkKetuaRT, 'nik' => '3275050404140004', 'nama_lengkap' => 'Naura Putri Prasetyo', 'jenis_kelamin' => 'Perempuan', 'umur' => 11, 'agama' => 'Islam', 'no_telepon' => null, 'blok_rumah' => 'Blok A2 No. 10', 'status_keluarga' => 'Anak', 'status_domisili' => 'Tetap', 'created_at' => $now, 'updated_at' => $now],
            ]);
        }

        // 5. Keluarga Sekretaris RT
        $kkSekretarisRT = '3275050011220002';
        if (!DB::table('wargas')->where('nama_lengkap', 'Sekretaris RT')->exists()) {
            DB::table('wargas')->insert([
                ['nomor_kk' => $kkSekretarisRT, 'nik' => '3275050101850001', 'nama_lengkap' => 'Sekretaris RT', 'jenis_kelamin' => 'Laki-laki', 'umur' => 40, 'agama' => 'Islam', 'no_telepon' => '081122334441', 'blok_rumah' => 'Blok B1 No. 15', 'status_keluarga' => 'Kepala Keluarga', 'status_domisili' => 'Tetap', 'created_at' => $now, 'updated_at' => $now],
                ['nomor_kk' => $kkSekretarisRT, 'nik' => '3275050202880002', 'nama_lengkap' => 'Maya Sari Wijaya', 'jenis_kelamin' => 'Perempuan', 'umur' => 37, 'agama' => 'Islam', 'no_telepon' => '081122334442', 'blok_rumah' => 'Blok B1 No. 15', 'status_keluarga' => 'Istri', 'status_domisili' => 'Tetap', 'created_at' => $now, 'updated_at' => $now],
                ['nomor_kk' => $kkSekretarisRT, 'nik' => '3275050303120003', 'nama_lengkap' => 'Fajar Wijaya', 'jenis_kelamin' => 'Laki-laki', 'umur' => 13, 'agama' => 'Islam', 'no_telepon' => null, 'blok_rumah' => 'Blok B1 No. 15', 'status_keluarga' => 'Anak', 'status_domisili' => 'Tetap', 'created_at' => $now, 'updated_at' => $now],
                ['nomor_kk' => $kkSekretarisRT, 'nik' => '3275050404180004', 'nama_lengkap' => 'Kirana Wijaya', 'jenis_kelamin' => 'Perempuan', 'umur' => 7, 'agama' => 'Islam', 'no_telepon' => null, 'blok_rumah' => 'Blok B1 No. 15', 'status_keluarga' => 'Anak', 'status_domisili' => 'Tetap', 'created_at' => $now, 'updated_at' => $now],
            ]);
        }

        // 6. Keluarga Bendahara RT
        $kkBendaharaRT = '3275050011220003';
        if (!DB::table('wargas')->where('nama_lengkap', 'Bendahara RT')->exists()) {
            DB::table('wargas')->insert([
                ['nomor_kk' => $kkBendaharaRT, 'nik' => '3275050101810001', 'nama_lengkap' => 'Bendahara RT', 'jenis_kelamin' => 'Laki-laki', 'umur' => 44, 'agama' => 'Islam', 'no_telepon' => '081122334451', 'blok_rumah' => 'Blok B2 No. 04', 'status_keluarga' => 'Kepala Keluarga', 'status_domisili' => 'Tetap', 'created_at' => $now, 'updated_at' => $now],
                ['nomor_kk' => $kkBendaharaRT, 'nik' => '3275050202840002', 'nama_lengkap' => 'Dewi Sartika Fauzi', 'jenis_kelamin' => 'Perempuan', 'umur' => 41, 'agama' => 'Islam', 'no_telepon' => '081122334452', 'blok_rumah' => 'Blok B2 No. 04', 'status_keluarga' => 'Istri', 'status_domisili' => 'Tetap', 'created_at' => $now, 'updated_at' => $now],
                ['nomor_kk' => $kkBendaharaRT, 'nik' => '3275050303090003', 'nama_lengkap' => 'Farhan Fauzi', 'jenis_kelamin' => 'Laki-laki', 'umur' => 16, 'agama' => 'Islam', 'no_telepon' => null, 'blok_rumah' => 'Blok B2 No. 04', 'status_keluarga' => 'Anak', 'status_domisili' => 'Tetap', 'created_at' => $now, 'updated_at' => $now],
                ['nomor_kk' => $kkBendaharaRT, 'nik' => '3275050404150004', 'nama_lengkap' => 'Zaskia Fauzi', 'jenis_kelamin' => 'Perempuan', 'umur' => 10, 'agama' => 'Islam', 'no_telepon' => null, 'blok_rumah' => 'Blok B2 No. 04', 'status_keluarga' => 'Anak', 'status_domisili' => 'Tetap', 'created_at' => $now, 'updated_at' => $now],
            ]);
        }

        // ========================================================
        // 3. SEED TRANSAKSI KAS RT (PEMASUKAN & PENGELUARAN REALISTIS)
        // ========================================================
        echo "3️⃣  Mengisi Transaksi Kas RT (Pemasukan & Pengeluaran)...\n";
        if (DB::table('transactions')->count() < 10) {
            $transList = [
                // Pemasukan
                ['tanggal' => '2026-11-01', 'keterangan' => 'Iuran Warga Rutin Bulan November (Blok A & B)', 'kategori' => 'Iuran Warga', 'jenis' => 'pemasukan', 'nominal' => 3500000],
                ['tanggal' => '2026-11-02', 'keterangan' => 'Iuran Warga Rutin Bulan November (Blok C & D)', 'kategori' => 'Iuran Warga', 'jenis' => 'pemasukan', 'nominal' => 3200000],
                ['tanggal' => '2026-11-05', 'keterangan' => 'Donasi Warga untuk Perbaikan Pos Ronda', 'kategori' => 'Dana Hibah & Donasi', 'jenis' => 'pemasukan', 'nominal' => 1500000],
                ['tanggal' => '2026-11-08', 'keterangan' => 'Sewa Tenda & Sound System Warga Hajatan', 'kategori' => 'Penyewaan Fasilitas & Aset', 'jenis' => 'pemasukan', 'nominal' => 500000],
                ['tanggal' => '2026-11-12', 'keterangan' => 'Iuran Kebersihan & Keamanan Tambahan', 'kategori' => 'Iuran Warga', 'jenis' => 'pemasukan', 'nominal' => 1250000],
                ['tanggal' => '2026-11-18', 'keterangan' => 'Donasi Rutin Pengusaha UMKM Lingkungan RT', 'kategori' => 'Dana Hibah & Donasi', 'jenis' => 'pemasukan', 'nominal' => 750000],

                // Pengeluaran
                ['tanggal' => '2026-11-03', 'keterangan' => 'Honor Petugas Keamanan / Satpam (2 Orang)', 'kategori' => 'Biaya Keamanan & Ronda', 'jenis' => 'pengeluaran', 'nominal' => 2800000],
                ['tanggal' => '2026-11-04', 'keterangan' => 'Honor Petugas Angkut Sampah & Operasional Gerobak', 'kategori' => 'Biaya Kebersihan & Sampah', 'jenis' => 'pengeluaran', 'nominal' => 1200000],
                ['tanggal' => '2026-11-06', 'keterangan' => 'Pembelian Lampu LED Sorot Jalan & Kabel Instalasi', 'kategori' => 'Pembangunan & Prasarana', 'jenis' => 'pengeluaran', 'nominal' => 650000],
                ['tanggal' => '2026-11-10', 'keterangan' => 'Pembelian Kertas HVS, Tinta Printer, dan Amplop Surat', 'kategori' => 'Biaya Operasional & ATK', 'jenis' => 'pengeluaran', 'nominal' => 275000],
                ['tanggal' => '2026-11-14', 'keterangan' => 'Santunan Duka Cita Keluarga Warga Sakit / Duka', 'kategori' => 'Kegiatan Sosial & Kematian', 'jenis' => 'pengeluaran', 'nominal' => 500000],
                ['tanggal' => '2026-11-16', 'keterangan' => 'Konsumsi & Snack Kerja Bakti Warga Bersih Selokan', 'kategori' => 'Kegiatan Sosial & Kematian', 'jenis' => 'pengeluaran', 'nominal' => 350000],
                ['tanggal' => '2026-11-20', 'keterangan' => 'Pembelian Baterai Cadangan HT Ronda & Senter Cas', 'kategori' => 'Biaya Keamanan & Ronda', 'jenis' => 'pengeluaran', 'nominal' => 220000],
            ];

            foreach ($transList as $t) {
                DB::table('transactions')->insert(array_merge($t, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
            }
        }

        // ========================================================
        // 4. SEED DATA IURAN WARGA (CONTRIBUTIONS)
        // ========================================================
        echo "4️⃣  Mengisi Master Data Iuran Warga...\n";
        $iuranList = [
            ['nama_iuran' => 'Iuran Keamanan & Satpam', 'periode_penagihan' => 'Bulanan', 'nominal' => 35000, 'sifat' => 'Wajib', 'deskripsi' => 'Honor petugas jaga pos ronda dan pemeliharaan portal otomatis'],
            ['nama_iuran' => 'Iuran Kebersihan & Sampah', 'periode_penagihan' => 'Bulanan', 'nominal' => 25000, 'sifat' => 'Wajib', 'deskripsi' => 'Pengangkutan sampah rumah tangga 3x seminggu ke TPA'],
            ['nama_iuran' => 'Iuran Kas Rutin RT', 'periode_penagihan' => 'Bulanan', 'nominal' => 20000, 'sifat' => 'Wajib', 'deskripsi' => 'Kas operasional kegiatan warga, rapat bulanan, dan perawatan fasum'],
            ['nama_iuran' => 'Dana Sosial & Kematian (Rukem)', 'periode_penagihan' => 'Bulanan', 'nominal' => 10000, 'sifat' => 'Sukarela', 'deskripsi' => 'Santunan duka cita, perlengkapan jenazah, dan santunan warga sakit'],
        ];

        foreach ($iuranList as $iuran) {
            DB::table('contributions')->updateOrInsert(
                ['nama_iuran' => $iuran['nama_iuran']],
                array_merge($iuran, ['created_at' => $now, 'updated_at' => $now])
            );
        }

        // ========================================================
        // 5. SEED PENGURUS RT (OFFICERS)
        // ========================================================
        echo "5️⃣  Mengisi Struktur Pengurus RT...\n";
        if (DB::table('officers')->count() == 0) {
            $wargaKetua = DB::table('wargas')->where('nama_lengkap', 'LIKE', '%Soekarno%')->first()
                        ?? DB::table('wargas')->first();

            $wargaList = DB::table('wargas')->where('status_keluarga', 'Kepala Keluarga')->take(6)->get();

            $jabatanList = [
                'Ketua RT 05',
                'Sekretaris RT',
                'Bendahara RT',
                'Seksi Keamanan & Ketertiban',
                'Seksi Kebersihan & Lingkungan',
                'Seksi Humas & Pemuda'
            ];

            foreach ($jabatanList as $idx => $jabatan) {
                $w = $wargaList[$idx] ?? $wargaKetua;
                if ($w) {
                    DB::table('officers')->insert([
                        'warga_id'      => $w->id,
                        'jabatan'       => $jabatan,
                        'tanggal_mulai' => '2024-01-01',
                        'status_aktif'  => 'Aktif',
                        'created_at'    => $now,
                        'updated_at'    => $now,
                    ]);
                }
            }
        }

        // ========================================================
        // 6. SEED PERANGKAT & INVENTARIS SISTEM (DEVICES)
        // ========================================================
        echo "6️⃣  Mengisi Inventaris & Perangkat RT...\n";
        if (DB::table('devices')->count() == 0) {
            $devices = [
                ['nama_perangkat' => 'Tenda Pesta Lipat Ukuran 3x6 Meter', 'jenis_perangkat' => 'Tenda & Terpal', 'nomor_serial' => 'INV-TND-01', 'kondisi' => 'Baik', 'keterangan' => 'Lengkap dengan terpal atap dan dinding penutup samping (Lokasi: Gudang Balai RT)'],
                ['nama_perangkat' => 'Kursi Lipat Chitose Besi (Set 50 Pcs)', 'jenis_perangkat' => 'Mebel & Kursi', 'nomor_serial' => 'INV-KRS-01', 'kondisi' => 'Baik', 'keterangan' => 'Kursi rapat warga dan hajatan lingkungan (Lokasi: Gudang Balai RT)'],
                ['nama_perangkat' => 'Sound System Portable Wireless 12 Inch', 'jenis_perangkat' => 'Audio Elektronik', 'nomor_serial' => 'INV-SND-01', 'kondisi' => 'Baik', 'keterangan' => 'Lengkap dengan 2 Mic Wireless dan kabel charger (Lokasi: Rumah Ketua RT)'],
                ['nama_perangkat' => 'Mesin Pemotong Rumput Dorong Honda', 'jenis_perangkat' => 'Alat Kebersihan', 'nomor_serial' => 'INV-MPT-01', 'kondisi' => 'Baik', 'keterangan' => 'Digunakan saat kerja bakti membersihkan lapangan fasum (Lokasi: Pos Ronda)'],
                ['nama_perangkat' => 'Handy Talkie (HT) Baofeng UV-5R (4 Unit)', 'jenis_perangkat' => 'Komunikasi Keamanan', 'nomor_serial' => 'INV-HT-01', 'kondisi' => 'Baik', 'keterangan' => 'Komunikasi petugas keamanan ronda malam warga (Lokasi: Pos Ronda)'],
                ['nama_perangkat' => 'Portal Gerbang Otomatis RFID / ESP32', 'jenis_perangkat' => 'Sistem Akses IoT', 'nomor_serial' => 'PORTAL-RFID-01', 'kondisi' => 'Baik', 'keterangan' => 'Terhubung dengan sistem akses kartu e-KTP GUYUB (Lokasi: Gerbang Masuk)'],
            ];

            foreach ($devices as $dev) {
                DB::table('devices')->insert(array_merge($dev, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
            }
        }

        // ========================================================
        // 7. SEED PRODUK KOPERASI WARGA (KOPERASI ITEMS)
        // ========================================================
        echo "7️⃣  Mengisi Katalog Produk Sembako Koperasi...\n";
        if (DB::table('koperasi_items')->count() == 0) {
            $sembako = [
                ['nama_produk' => 'Beras Rojolele Super Premium 5kg', 'kategori' => 'Sembako', 'harga' => 72000, 'stok' => 30, 'penjual' => 'Koperasi RT'],
                ['nama_produk' => 'Minyak Goreng Tropical 2 Liter', 'kategori' => 'Sembako', 'harga' => 36500, 'stok' => 45, 'penjual' => 'Koperasi RT'],
                ['nama_produk' => 'Gula Pasir Gulaku Kuning Premium 1kg', 'kategori' => 'Sembako', 'harga' => 17500, 'stok' => 50, 'penjual' => 'Koperasi RT'],
                ['nama_produk' => 'Telur Ayam Negeri Segar (Tray 30 Butir)', 'kategori' => 'Sembako', 'harga' => 52000, 'stok' => 15, 'penjual' => 'Koperasi RT'],
                ['nama_produk' => 'Indomie Kuah Ayam Bawang (Dus 40 Pcs)', 'kategori' => 'Sembako', 'harga' => 115000, 'stok' => 20, 'penjual' => 'Koperasi RT'],
                ['nama_produk' => 'Sabun Cuci Piring Sunlight Jeruk Nipis 750ml', 'kategori' => 'Sembako', 'harga' => 14000, 'stok' => 35, 'penjual' => 'Koperasi RT'],
            ];

            foreach ($sembako as $sb) {
                DB::table('koperasi_items')->insert(array_merge($sb, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
            }
        }

        // ========================================================
        // 8. SEED DATA UMKM WARGA
        // ========================================================
        echo "8️⃣  Mengisi Data UMKM Warga...\n";
        if (DB::table('umkms')->count() < 3) {
            $umkmList = [
                [
                    'nama_usaha' => 'Warung Nasi Uduk & Lontong Sayur Bu Siti',
                    'pemilik'    => 'Hj. Siti Aminah',
                    'kategori'   => 'Kuliner',
                    'kontak'     => '081234567890',
                    'lokasi'     => 'Blok A1 No. 04',
                    'deskripsi'  => 'Menyediakan nasi uduk komplit, lontong sayur, gorengan hangat, dan aneka lauk pauk setiap pagi.',
                    'status'     => 'Aktif'
                ],
                [
                    'nama_usaha' => 'Laundry Kiloan Bersih Wangi',
                    'pemilik'    => 'Ratna Juwita',
                    'kategori'   => 'Jasa',
                    'kontak'     => '081399887766',
                    'lokasi'     => 'Blok B2 No. 12',
                    'deskripsi'  => 'Jasa cuci setrika cepat, wangi tahan lama, melayani antar jemput pakaian warga perumahan.',
                    'status'     => 'Aktif'
                ],
                [
                    'nama_usaha' => 'Toko Sayur & Bumbu Segar Mas Firdaus',
                    'pemilik'    => 'Ir. Muhammad Firdaus',
                    'kategori'   => 'Sembako',
                    'kontak'     => '085711223344',
                    'lokasi'     => 'Blok C1 No. 08',
                    'deskripsi'  => 'Sayuran segar hidroponik, bumbu dapur racikan, tahu tempe, ikan dan ayam potong segar setiap hari.',
                    'status'     => 'Aktif'
                ],
            ];

            foreach ($umkmList as $u) {
                DB::table('umkms')->updateOrInsert(
                    ['nama_usaha' => $u['nama_usaha']],
                    array_merge($u, ['created_at' => $now, 'updated_at' => $now])
                );
            }
        }

        // ========================================================
        // 9. SEED POSYANDU
        // ========================================================
        echo "9️⃣  Mengisi Jadwal Posyandu...\n";
        if (DB::table('posyandus')->count() == 0) {
            $posyandus = [
                [
                    'nama_kegiatan'  => 'Posyandu Balita Melati 05 (Imunisasi & Timbang)',
                    'target_peserta' => 'Balita (0 - 5 Tahun)',
                    'tanggal'        => '2026-11-28',
                    'waktu_mulai'    => '08:30:00',
                    'waktu_selesai'  => '11:30:00',
                    'lokasi'         => 'Balai Warga RT 05',
                    'keterangan'     => 'Penimbangan balita, pengukuran tinggi badan, pemberian vitamin A dan imunisasi polio oleh Bidan Puskesmas.',
                    'created_at'     => $now,
                    'updated_at'     => $now,
                ],
                [
                    'nama_kegiatan'  => 'Posyandu Lansia Sehat Bugar Bersama',
                    'target_peserta' => 'Lansia (≥ 60 Tahun)',
                    'tanggal'        => '2026-12-05',
                    'waktu_mulai'    => '09:00:00',
                    'waktu_selesai'  => '12:00:00',
                    'lokasi'         => 'Balai Warga RT 05',
                    'keterangan'     => 'Pemeriksaan tensi darah gratis, cek gula darah, kolesterol, asam urat, serta konsultasi kesehatan dokter umum.',
                    'created_at'     => $now,
                    'updated_at'     => $now,
                ],
            ];

            foreach ($posyandus as $p) {
                DB::table('posyandus')->insert($p);
            }
        }

        // ========================================================
        // 10. SEED BANK SAMPAH
        // ========================================================
        echo "🔟 Mengisi Setoran Bank Sampah Warga...\n";
        if (DB::table('bank_sampah_deposits')->count() == 0) {
            $w = DB::table('wargas')->first();
            $namaWarga = $w ? $w->nama_lengkap : 'Steve Marvin Politon';

            $bankSampah = [
                ['nama_warga' => $namaWarga, 'jenis_sampah' => 'Kardus & Kertas Bekas', 'berat_kg' => 15.5, 'total_rupiah' => 31000, 'tanggal' => '2026-11-10', 'created_at' => $now, 'updated_at' => $now],
                ['nama_warga' => 'Hj. Siti Aminah', 'jenis_sampah' => 'Botol Plastik Bersih (PET)', 'berat_kg' => 8.0, 'total_rupiah' => 32000, 'tanggal' => '2026-11-14', 'created_at' => $now, 'updated_at' => $now],
                ['nama_warga' => 'Bambang Soekarno', 'jenis_sampah' => 'Minyak Jelantah', 'berat_kg' => 6.0, 'total_rupiah' => 36000, 'tanggal' => '2026-11-18', 'created_at' => $now, 'updated_at' => $now],
            ];

            foreach ($bankSampah as $bs) {
                DB::table('bank_sampah_deposits')->insert($bs);
            }
        }

        // ========================================================
        // 11. SEED PERATURAN & SK RT
        // ========================================================
        echo "1️⃣1️⃣ Mengisi Dokumen Peraturan & SK RT...\n";
        if (DB::table('peraturan_sks')->count() == 0) {
            $peraturan = [
                [
                    'judul'           => 'Surat Keputusan Pengurus RT 05 Periode 2024-2027',
                    'nomor_dokumen'   => '001/SK-RT05/I/2024',
                    'kategori'        => 'Surat Keputusan',
                    'tanggal_berlaku' => '2024-01-01',
                    'keterangan'      => 'Pengangkatan susunan pengurus RT 05 RW 02 masa bakti 2024-2027.',
                    'file_path'       => null,
                    'status'          => 'Aktif',
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ],
                [
                    'judul'           => 'Tata Tertib Keamanan Lingkungan & Akses Portal Otomatis',
                    'nomor_dokumen'   => '002/PER-RT05/II/2024',
                    'kategori'        => 'Peraturan',
                    'tanggal_berlaku' => '2024-02-15',
                    'keterangan'      => 'Ketentuan buka tutup portal malam hari (22.00 - 05.00) dan kewajiban tap e-KTP terdaftar.',
                    'file_path'       => null,
                    'status'          => 'Aktif',
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ],
                [
                    'judul'           => 'Aturan Pengelolaan Sampah Rumah Tangga & Jadwal Kerja Bakti',
                    'nomor_dokumen'   => '003/PER-RT05/IV/2024',
                    'kategori'        => 'Peraturan',
                    'tanggal_berlaku' => '2024-04-01',
                    'keterangan'      => 'Kewajiban memilah sampah basah dan kering serta gotong royong dua minggu sekali.',
                    'file_path'       => null,
                    'status'          => 'Aktif',
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ],
            ];

            foreach ($peraturan as $p) {
                DB::table('peraturan_sks')->insert($p);
            }
        }

        // ========================================================
        // 12. SEED KERJA BAKTI
        // ========================================================
        echo "1️⃣2️⃣ Mengisi Jadwal Kerja Bakti Lingkungan...\n";
        if (DB::table('kerja_baktis')->count() == 0) {
            $kerjaBaktis = [
                [
                    'nama_kegiatan' => 'Kerja Bakti Bersih Selokan & Antisipasi Musim Hujan',
                    'tanggal'       => '2026-11-29',
                    'waktu_mulai'   => '07:00',
                    'waktu_selesai' => '10:30',
                    'lokasi'        => 'Sepanjang Selokan Blok A, B, dan C',
                    'perlengkapan'  => 'Cangkul, sekop, karung sampah, dan sarung tangan karet',
                    'keterangan'    => 'Pembersihan endapan lumpur got saluran pembuangan utama. Disediakan konsumsi kopi & snack.',
                    'status'        => 'Mendatang',
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ],
                [
                    'nama_kegiatan' => 'Pengecatan Gapura Masuk & Perapihan Taman Fasum',
                    'tanggal'       => '2026-12-13',
                    'waktu_mulai'   => '07:30',
                    'waktu_selesai' => '11:00',
                    'lokasi'        => 'Gerbang Portal Utama & Taman Bermain Anak',
                    'perlengkapan'  => 'Kuas cat, ember, gunting rumput, dan tangga lipat',
                    'keterangan'    => 'Gotong royong mempercantik gerbang pintu masuk perumahan menjelang akhir tahun.',
                    'status'        => 'Mendatang',
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ],
            ];

            foreach ($kerjaBaktis as $kb) {
                DB::table('kerja_baktis')->insert($kb);
            }
        }

        // ========================================================
        // 13. SEED RUKEM (SANTUNAN DUKA CITA & KEMATIAN)
        // ========================================================
        echo "1️⃣3️⃣ Mengisi Data Santunan Duka Cita (Rukem)...\n";
        if (DB::table('rukems')->count() == 0) {
            $rukems = [
                [
                    'nama_almarhum'       => 'Alm. Bapak Suparman',
                    'keluarga_duka'       => 'Ibu Sri Wahyuni (Blok A2 No. 05)',
                    'tanggal_duka'        => '2026-10-15',
                    'santunan_diserahkan' => 1000000,
                    'status_santunan'     => 'Tersalurkan',
                    'created_at'          => $now,
                    'updated_at'          => $now,
                ],
            ];

            foreach ($rukems as $r) {
                DB::table('rukems')->insert($r);
            }
        }

        // ========================================================
        // 14. SEED ASPIRASI WARGA
        // ========================================================
        echo "1️⃣4️⃣ Mengisi Aspirasi & Saran Warga...\n";
        if (DB::table('aspirasis')->count() == 0) {
            $aspirasis = [
                [
                    'nama_warga'   => 'Hendra Setiawan',
                    'topik'        => 'Penerangan Jalan di Tikungan Blok C',
                    'isi_aspirasi' => 'Mohon ditambah lampu jalan LED di tikungan Blok C2 karena saat malam cukup gelap dan rawan terserempet kendaraan.',
                    'tanggapan_rt' => 'Terima kasih atas masukannya. Pengurus sudah menganggarkan pembelian lampu LED sorot untuk dipasang akhir pekan ini.',
                    'status'       => 'Selesai',
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ],
                [
                    'nama_warga'   => 'Steve Marvin Politon',
                    'topik'        => 'Penambahan Tempat Sampah Pilah Organik/Anorganik',
                    'isi_aspirasi' => 'Usul agar di area taman bermain dan balai warga disediakan tempat sampah terpisah untuk plastik dan sisa makanan.',
                    'tanggapan_rt' => 'Usulan sangat baik. Akan dikoordinasikan dengan tim Bank Sampah RT.',
                    'status'       => 'Diproses',
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ]
            ];

            foreach ($aspirasis as $asp) {
                DB::table('aspirasis')->insert($asp);
            }
        }

        // ========================================================
        // 15. SEED PEMINJAMAN ASET (ASSET LOANS)
        // ========================================================
        echo "1️⃣5️⃣ Mengisi Peminjaman Aset Fasum...\n";
        if (DB::table('asset_loans')->count() == 0) {
            $userSteve = DB::table('users')->where('email', 'steve@gmail.com')->first();
            $userId = $userSteve ? $userSteve->id : null;
            $assetLoans = [
                [
                    'user_id'                     => $userId,
                    'nama_warga'                  => 'Steve Marvin Politon',
                    'nama_aset'                   => 'Tenda Pesta Lipat Ukuran 3x6 Meter',
                    'jumlah_unit'                 => 1,
                    'tanggal_pinjam'              => '2026-11-20',
                    'tanggal_kembali'             => '2026-11-23',
                    'keperluan'                   => 'Acara syukuran keluarga di rumah',
                    'status'                      => 'Disetujui',
                    'catatan_admin'               => 'Tenda diambil di gudang RT, jaga kebersihan terpal.',
                    'tanggal_dikembalikan_aktual' => null,
                    'kondisi_pengembalian'        => null,
                    'catatan_pengembalian'        => null,
                    'denda_kerusakan'             => 0,
                    'created_at'                  => $now,
                    'updated_at'                  => $now,
                ],
                [
                    'user_id'                     => null,
                    'nama_warga'                  => 'Hj. Siti Aminah',
                    'nama_aset'                   => 'Kursi Lipat Chitose Besi (Set 50 Pcs)',
                    'jumlah_unit'                 => 20,
                    'tanggal_pinjam'              => '2026-11-25',
                    'tanggal_kembali'             => '2026-11-26',
                    'keperluan'                   => 'Arisan bulanan PKK RT 05',
                    'status'                      => 'Menunggu Approval',
                    'catatan_admin'               => null,
                    'tanggal_dikembalikan_aktual' => null,
                    'kondisi_pengembalian'        => null,
                    'catatan_pengembalian'        => null,
                    'denda_kerusakan'             => 0,
                    'created_at'                  => $now,
                    'updated_at'                  => $now,
                ]
            ];

            foreach ($assetLoans as $al) {
                DB::table('asset_loans')->insert($al);
            }
        }

        // ========================================================
        // 16. SEED KOPERASI (PESANAN, SIMPANAN, PINJAMAN, MODAL)
        // ========================================================
        echo "1️⃣6️⃣ Mengisi Data Koperasi Warga...\n";
        if (DB::table('koperasi_orders')->count() == 0) {
            $userSteve = DB::table('users')->where('email', 'steve@gmail.com')->first();
            $userId = $userSteve ? $userSteve->id : null;
            $kopItem = DB::table('koperasi_items')->first();

            DB::table('koperasi_orders')->insert([
                'user_id'           => $userId,
                'nama_warga'        => 'Steve Marvin Politon',
                'item_id'           => $kopItem ? $kopItem->id : 1,
                'nama_produk'       => $kopItem ? ($kopItem->nama_produk ?? 'Beras Premium Ramos 5 Kg') : 'Beras Premium Ramos 5 Kg',
                'jumlah'            => 2,
                'total_harga'       => 150000,
                'metode_pembayaran' => 'QRIS Kas RT',
                'status'            => 'Selesai',
                'created_at'        => $now,
                'updated_at'        => $now,
            ]);
        }

        if (DB::table('koperasi_simpanans')->count() == 0) {
            $userSteve = DB::table('users')->where('email', 'steve@gmail.com')->first();
            $userId = $userSteve ? $userSteve->id : null;

            DB::table('koperasi_simpanans')->insert([
                [
                    'user_id'           => $userId,
                    'nama_warga'        => 'Steve Marvin Politon',
                    'jenis_simpanan'    => 'Simpanan Pokok',
                    'jumlah'            => 100000,
                    'metode_pembayaran' => 'Transfer Kas RT',
                    'keterangan'        => 'Simpanan pokok pendaftaran anggota koperasi',
                    'status'            => 'Terverifikasi',
                    'created_at'        => $now,
                    'updated_at'        => $now,
                ],
                [
                    'user_id'           => $userId,
                    'nama_warga'        => 'Steve Marvin Politon',
                    'jenis_simpanan'    => 'Simpanan Wajib',
                    'jumlah'            => 25000,
                    'metode_pembayaran' => 'Tunai',
                    'keterangan'        => 'Simpanan wajib bulan November 2026',
                    'status'            => 'Terverifikasi',
                    'created_at'        => $now,
                    'updated_at'        => $now,
                ]
            ]);
        }

        if (DB::table('koperasi_pinjamans')->count() == 0) {
            $userSteve = DB::table('users')->where('email', 'steve@gmail.com')->first();
            $userId = $userSteve ? $userSteve->id : null;

            DB::table('koperasi_pinjamans')->insert([
                'user_id'            => $userId,
                'nama_warga'         => 'Steve Marvin Politon',
                'jumlah_pinjaman'    => 1500000,
                'tenor_bulan'        => 6,
                'angsuran_per_bulan' => 250000,
                'keperluan'          => 'Tambahan modal etalase warung sembako',
                'status'             => 'Disetujui',
                'catatan_pengurus'   => 'Pencairan dana tanggal 1 Desember 2026 via transfer rekening.',
                'created_at'         => $now,
                'updated_at'         => $now,
            ]);
        }

        if (DB::table('koperasi_permodalans')->count() == 0) {
            $userSteve = DB::table('users')->where('email', 'steve@gmail.com')->first();
            $userId = $userSteve ? $userSteve->id : null;

            DB::table('koperasi_permodalans')->insert([
                'user_id'           => $userId,
                'nama_warga'        => 'Steve Marvin Politon',
                'nama_usaha'        => 'Warung Kopi & Snack Steve',
                'kategori_umkm'     => 'Kuliner',
                'nominal_pengajuan' => 2000000,
                'deskripsi_usaha'   => 'Pengembangan usaha warung kopi dan gorengan di teras rumah.',
                'status'            => 'Disetujui',
                'catatan_pengurus'  => 'Disetujui dalam rapat pengurus RT/Koperasi.',
                'created_at'        => $now,
                'updated_at'        => $now,
            ]);
        }

        // =====================================================================
        // 17. SINKRONISASI AKUN PENGGUNA (GMAIL & PASSWORD 'password') UNTUK SEMUA WARGA
        // =====================================================================
        $defaultHashedPassword = Hash::make('password');
        DB::table('users')->update([
            'password'   => $defaultHashedPassword,
            'status'     => 'Aktif',
            'updated_at' => $now,
        ]);

        $demoAccounts = [
            ['name' => 'Super Admin',           'email' => 'superadmin@gmail.com',    'role' => 'Super Admin'],
            ['name' => 'Ketua RW',              'email' => 'rw@gmail.com',            'role' => 'RW'],
            ['name' => 'Sekretaris RW',         'email' => 'sekretarisrw@gmail.com',  'role' => 'Sekretaris RW'],
            ['name' => 'Bendahara RW',          'email' => 'bendahararw@gmail.com',   'role' => 'Bendahara RW'],
            ['name' => 'Ketua RT',              'email' => 'rt@gmail.com',            'role' => 'RT'],
            ['name' => 'Sekretaris RT',         'email' => 'sekretaris@gmail.com',    'role' => 'Sekretaris RT'],
            ['name' => 'Bendahara RT',          'email' => 'bendahara@gmail.com',     'role' => 'Bendahara RT'],
            ['name' => 'Warga',                 'email' => 'warga@gmail.com',         'role' => 'Warga'],
            ['name' => 'Steve Marvin Politon',  'email' => 'steve@gmail.com',         'role' => 'Warga'],
        ];

        foreach ($demoAccounts as $demo) {
            $existing = DB::table('users')->where('email', $demo['email'])->first();
            if ($existing) {
                DB::table('users')->where('email', $demo['email'])->update([
                    'name'       => $demo['name'],
                    'role'       => $demo['role'],
                    'password'   => $defaultHashedPassword,
                    'status'     => 'Aktif',
                    'updated_at' => $now,
                ]);
            } else {
                DB::table('users')->insert([
                    'name'       => $demo['name'],
                    'email'      => $demo['email'],
                    'password'   => $defaultHashedPassword,
                    'role'       => $demo['role'],
                    'status'     => 'Aktif',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        $allWargasList = DB::table('wargas')->get();
        foreach ($allWargasList as $w) {
            $userExists = DB::table('users')->where('name', $w->nama_lengkap)->first();
            if (!$userExists) {
                $cleanName = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $w->nama_lengkap));
                if (empty($cleanName)) {
                    $cleanName = 'warga' . $w->id;
                }
                $email = $cleanName . '@gmail.com';
                if (DB::table('users')->where('email', $email)->exists()) {
                    $email = $cleanName . $w->id . '@gmail.com';
                }
                if (DB::table('users')->where('email', $email)->exists()) {
                    $email = $cleanName . rand(100, 999) . '@gmail.com';
                }

                $officer = DB::table('officers')->where('warga_id', $w->id)->first();
                $role = 'Warga';
                if ($officer) {
                    $jabatan = strtolower($officer->jabatan ?? '');
                    if (str_contains($jabatan, 'ketua rw')) $role = 'RW';
                    elseif (str_contains($jabatan, 'sekretaris rw')) $role = 'Sekretaris RW';
                    elseif (str_contains($jabatan, 'bendahara rw')) $role = 'Bendahara RW';
                    elseif (str_contains($jabatan, 'ketua rt')) $role = 'RT';
                    elseif (str_contains($jabatan, 'sekretaris rt') || str_contains($jabatan, 'sekretaris')) $role = 'Sekretaris RT';
                    elseif (str_contains($jabatan, 'bendahara rt') || str_contains($jabatan, 'bendahara')) $role = 'Bendahara RT';
                }

                DB::table('users')->insert([
                    'name'       => $w->nama_lengkap,
                    'email'      => $email,
                    'password'   => $defaultHashedPassword,
                    'role'       => $role,
                    'status'     => 'Aktif',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        echo "\n🎉 SEEDING MASTER DATA SELESAI DENGAN SUKSES!\n";
    }
}
