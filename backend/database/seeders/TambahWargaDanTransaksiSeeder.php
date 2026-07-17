<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warga;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TambahWargaDanTransaksiSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $blok_list = ['Blok A1', 'Blok A2', 'Blok A3', 'Blok B1', 'Blok B2', 'Blok C1', 'Blok C2', 'Blok D1', 'Blok D2', 'Blok E1', 'Blok F1'];

        // ══════════════════════════════════════════════
        // 1. TAMBAH WARGA HINGGA 150 (TERMASUK LANSIA)
        // ══════════════════════════════════════════════
        $currentCount = DB::table('wargas')->count();
        $target = 150;
        $needed = $target - $currentCount;

        echo "Warga saat ini: {$currentCount}, target: {$target}, perlu ditambah: {$needed}\n";

        if ($needed > 0) {
            $added = 0;

            // --- Tambah beberapa keluarga dengan lansia (kakek/nenek) ---
            $lansiaFamilies = [
                ['nama' => 'Haji Soekarno Hadi', 'umur' => 72, 'gender' => 'male', 'status' => 'Kepala Keluarga'],
                ['nama' => 'Siti Aminah', 'umur' => 68, 'gender' => 'female', 'status' => 'Istri'],
                ['nama' => 'Bambang Soekarno', 'umur' => 42, 'gender' => 'male', 'status' => 'Anak'],
                ['nama' => 'Dewi Soekarno', 'umur' => 38, 'gender' => 'female', 'status' => 'Anak'],
            ];

            $nomor_kk = $faker->unique()->numerify('327##########');
            $blok = $faker->randomElement($blok_list);
            foreach ($lansiaFamilies as $lf) {
                if ($added >= $needed) break;
                Warga::create([
                    'nomor_kk' => $nomor_kk,
                    'nik' => $faker->unique()->numerify('327##########'),
                    'nama_lengkap' => $lf['nama'],
                    'umur' => $lf['umur'],
                    'agama' => 'Islam',
                    'no_telepon' => $lf['status'] === 'Anak' ? null : $faker->phoneNumber(),
                    'blok_rumah' => $blok,
                    'status_keluarga' => $lf['status'],
                    'status_domisili' => 'Tetap',
                ]);
                $added++;
            }

            // Keluarga lansia 2
            $lansiaFamilies2 = [
                ['nama' => 'R. Soeharto Prawiro', 'umur' => 75, 'gender' => 'male', 'status' => 'Kepala Keluarga'],
                ['nama' => 'Kartini Prawiro', 'umur' => 70, 'gender' => 'female', 'status' => 'Istri'],
                ['nama' => 'Agus Prawiro', 'umur' => 45, 'gender' => 'male', 'status' => 'Anak'],
            ];

            $nomor_kk = $faker->unique()->numerify('327##########');
            $blok = $faker->randomElement($blok_list);
            foreach ($lansiaFamilies2 as $lf) {
                if ($added >= $needed) break;
                Warga::create([
                    'nomor_kk' => $nomor_kk,
                    'nik' => $faker->unique()->numerify('327##########'),
                    'nama_lengkap' => $lf['nama'],
                    'umur' => $lf['umur'],
                    'agama' => 'Islam',
                    'no_telepon' => $lf['status'] === 'Anak' ? null : $faker->phoneNumber(),
                    'blok_rumah' => $blok,
                    'status_keluarga' => $lf['status'],
                    'status_domisili' => 'Tetap',
                ]);
                $added++;
            }

            // Keluarga lansia 3 (Nenek janda)
            $lansiaFamilies3 = [
                ['nama' => 'Sri Mulyani Widodo', 'umur' => 67, 'gender' => 'female', 'status' => 'Kepala Keluarga'],
                ['nama' => 'Ratna Widodo', 'umur' => 40, 'gender' => 'female', 'status' => 'Anak'],
                ['nama' => 'Budi Widodo', 'umur' => 35, 'gender' => 'male', 'status' => 'Anak'],
            ];

            $nomor_kk = $faker->unique()->numerify('327##########');
            $blok = $faker->randomElement($blok_list);
            foreach ($lansiaFamilies3 as $lf) {
                if ($added >= $needed) break;
                Warga::create([
                    'nomor_kk' => $nomor_kk,
                    'nik' => $faker->unique()->numerify('327##########'),
                    'nama_lengkap' => $lf['nama'],
                    'umur' => $lf['umur'],
                    'agama' => 'Islam',
                    'no_telepon' => $lf['status'] !== 'Anak' ? $faker->phoneNumber() : null,
                    'blok_rumah' => $blok,
                    'status_keluarga' => $lf['status'],
                    'status_domisili' => 'Tetap',
                ]);
                $added++;
            }

            // Keluarga lansia 4
            $lansiaFamilies4 = [
                ['nama' => 'Mohamad Yusuf Habibie', 'umur' => 78, 'gender' => 'male', 'status' => 'Kepala Keluarga'],
                ['nama' => 'Nurhayati Habibie', 'umur' => 73, 'gender' => 'female', 'status' => 'Istri'],
            ];

            $nomor_kk = $faker->unique()->numerify('327##########');
            $blok = $faker->randomElement($blok_list);
            foreach ($lansiaFamilies4 as $lf) {
                if ($added >= $needed) break;
                Warga::create([
                    'nomor_kk' => $nomor_kk,
                    'nik' => $faker->unique()->numerify('327##########'),
                    'nama_lengkap' => $lf['nama'],
                    'umur' => $lf['umur'],
                    'agama' => 'Islam',
                    'no_telepon' => $faker->phoneNumber(),
                    'blok_rumah' => $blok,
                    'status_keluarga' => $lf['status'],
                    'status_domisili' => 'Tetap',
                ]);
                $added++;
            }

            // Keluarga lansia 5
            $lansiaFamilies5 = [
                ['nama' => 'Supardi Hartono', 'umur' => 65, 'gender' => 'male', 'status' => 'Kepala Keluarga'],
                ['nama' => 'Endang Hartono', 'umur' => 62, 'gender' => 'female', 'status' => 'Istri'],
                ['nama' => 'Wawan Hartono', 'umur' => 30, 'gender' => 'male', 'status' => 'Anak'],
            ];

            $nomor_kk = $faker->unique()->numerify('327##########');
            $blok = $faker->randomElement($blok_list);
            foreach ($lansiaFamilies5 as $lf) {
                if ($added >= $needed) break;
                Warga::create([
                    'nomor_kk' => $nomor_kk,
                    'nik' => $faker->unique()->numerify('327##########'),
                    'nama_lengkap' => $lf['nama'],
                    'umur' => $lf['umur'],
                    'agama' => 'Islam',
                    'no_telepon' => $lf['status'] === 'Anak' ? null : $faker->phoneNumber(),
                    'blok_rumah' => $blok,
                    'status_keluarga' => $lf['status'],
                    'status_domisili' => 'Tetap',
                ]);
                $added++;
            }

            // --- Isi sisa dengan keluarga random (termasuk beberapa lansia random) ---
            while ($added < $needed) {
                $nomor_kk = $faker->unique()->numerify('327##########');
                $blok_rumah = $faker->randomElement($blok_list);
                $domisili = $faker->randomElement(['Tetap', 'Kontrak', 'Kos']);
                $agama = $faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha']);

                // Random chance keluarga lansia
                $isLansia = rand(1, 5) === 1; // 20% chance

                // Kepala Keluarga
                $kkAge = $isLansia ? rand(60, 80) : rand(30, 55);
                Warga::create([
                    'nomor_kk' => $nomor_kk,
                    'nik' => $faker->unique()->numerify('327##########'),
                    'nama_lengkap' => $faker->name('male'),
                    'umur' => $kkAge,
                    'agama' => $agama,
                    'no_telepon' => $faker->phoneNumber(),
                    'blok_rumah' => $blok_rumah,
                    'status_keluarga' => 'Kepala Keluarga',
                    'status_domisili' => $domisili,
                ]);
                $added++;
                if ($added >= $needed) break;

                // Istri
                $istriAge = $isLansia ? rand(58, 75) : rand(25, 50);
                Warga::create([
                    'nomor_kk' => $nomor_kk,
                    'nik' => $faker->unique()->numerify('327##########'),
                    'nama_lengkap' => $faker->name('female'),
                    'umur' => $istriAge,
                    'agama' => $agama,
                    'no_telepon' => $faker->phoneNumber(),
                    'blok_rumah' => $blok_rumah,
                    'status_keluarga' => 'Istri',
                    'status_domisili' => $domisili,
                ]);
                $added++;
                if ($added >= $needed) break;

                // Anak (1-3)
                $jumlahAnak = $isLansia ? rand(1, 2) : rand(1, 3);
                for ($j = 0; $j < $jumlahAnak; $j++) {
                    if ($added >= $needed) break;
                    $anakAge = $isLansia ? rand(25, 45) : rand(3, 20);
                    Warga::create([
                        'nomor_kk' => $nomor_kk,
                        'nik' => $faker->unique()->numerify('327##########'),
                        'nama_lengkap' => $faker->name(),
                        'umur' => $anakAge,
                        'agama' => $agama,
                        'no_telepon' => $anakAge >= 17 ? $faker->phoneNumber() : null,
                        'blok_rumah' => $blok_rumah,
                        'status_keluarga' => 'Anak',
                        'status_domisili' => $domisili,
                    ]);
                    $added++;
                }
            }

            echo "✅ Berhasil menambahkan {$added} warga baru.\n";
        }

        $newCount = DB::table('wargas')->count();
        echo "Total warga sekarang: {$newCount}\n";

        // ══════════════════════════════════════════════
        // 2. BUAT DATA TRANSAKSI JANUARI - JULI 2026
        // ══════════════════════════════════════════════
        DB::table('transactions')->truncate();
        echo "🗑️  Transaksi lama dihapus.\n";

        $kategori_pemasukan = [
            'Iuran Warga' => [
                'Iuran bulanan warga Blok A',
                'Iuran bulanan warga Blok B',
                'Iuran bulanan warga Blok C',
                'Iuran bulanan warga Blok D',
                'Iuran bulanan warga Blok E',
                'Iuran keamanan lingkungan',
                'Iuran kebersihan lingkungan',
                'Iuran parkir warga',
            ],
            'Dana Hibah & Donasi' => [
                'Donasi warga untuk kegiatan 17 Agustus',
                'Donasi untuk renovasi musala',
                'Sumbangan warga untuk kegiatan sosial',
                'Dana bantuan pemerintah kelurahan',
                'Donasi perbaikan jalan lingkungan',
                'Sumbangan HUT RT',
            ],
        ];

        $kategori_pengeluaran = [
            'Biaya Keamanan & Ronda' => [
                'Honor satpam bulan ini',
                'Pembelian alat ronda (senter & tongkat)',
                'Perbaikan pos ronda',
                'Biaya listrik pos keamanan',
                'Pembelian HT untuk ronda',
            ],
            'Biaya Kebersihan & Sampah' => [
                'Honor petugas kebersihan',
                'Pembelian alat kebersihan (sapu, sekop)',
                'Biaya angkut sampah bulanan',
                'Pembelian tong sampah',
                'Pengecatan tempat sampah',
            ],
            'Biaya Operasional & ATK' => [
                'Pembelian ATK sekretariat',
                'Biaya cetak undangan rapat',
                'Biaya fotokopi surat-surat',
                'Biaya internet kantor RT',
                'Pembelian tinta printer',
                'Kertas HVS dan amplop',
            ],
            'Kegiatan Sosial & Kematian' => [
                'Santunan warga sakit',
                'Bantuan duka cita keluarga warga',
                'Biaya kegiatan posyandu',
                'Snack rapat bulanan RT',
                'Biaya lomba HUT RI',
                'Konsumsi kerja bakti',
            ],
            'Pembangunan & Prasarana' => [
                'Perbaikan jalan lingkungan',
                'Pembelian cat pagar komplek',
                'Perbaikan saluran air',
                'Pemasangan lampu jalan',
                'Perbaikan gapura masuk',
                'Pengecatan trotoar',
            ],
        ];

        $totalTransaksi = 0;
        $now = now();

        for ($month = 1; $month <= 7; $month++) {
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, 2026);

            // --- PEMASUKAN (6-10 per bulan) ---
            $jumlahPemasukan = rand(6, 10);
            for ($i = 0; $i < $jumlahPemasukan; $i++) {
                $kat = $faker->randomElement(array_keys($kategori_pemasukan));
                $ket = $faker->randomElement($kategori_pemasukan[$kat]);
                $day = rand(1, $daysInMonth);
                $tanggal = sprintf('2026-%02d-%02d', $month, $day);

                // Nominal realistis
                if ($kat === 'Iuran Warga') {
                    $nominal = $faker->randomElement([150000, 200000, 250000, 300000, 350000, 500000, 750000, 1000000, 1500000]);
                } else {
                    $nominal = $faker->randomElement([500000, 750000, 1000000, 1500000, 2000000, 2500000, 3000000, 5000000]);
                }

                DB::table('transactions')->insert([
                    'tanggal' => $tanggal,
                    'keterangan' => $ket,
                    'kategori' => $kat,
                    'jenis' => 'pemasukan',
                    'nominal' => $nominal,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                $totalTransaksi++;
            }

            // --- PENGELUARAN (5-8 per bulan) ---
            $jumlahPengeluaran = rand(5, 8);
            for ($i = 0; $i < $jumlahPengeluaran; $i++) {
                $kat = $faker->randomElement(array_keys($kategori_pengeluaran));
                $ket = $faker->randomElement($kategori_pengeluaran[$kat]);
                $day = rand(1, $daysInMonth);
                $tanggal = sprintf('2026-%02d-%02d', $month, $day);

                // Nominal realistis
                $nominal = $faker->randomElement([75000, 100000, 150000, 200000, 250000, 300000, 350000, 500000, 750000, 1000000, 1250000, 1500000]);

                DB::table('transactions')->insert([
                    'tanggal' => $tanggal,
                    'keterangan' => $ket,
                    'kategori' => $kat,
                    'jenis' => 'pengeluaran',
                    'nominal' => $nominal,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                $totalTransaksi++;
            }
        }

        echo "✅ Berhasil membuat {$totalTransaksi} transaksi (Jan-Jul 2026).\n";

        // Statistik akhir
        $totalPemasukan = DB::table('transactions')->where('jenis', 'pemasukan')->sum('nominal');
        $totalPengeluaran = DB::table('transactions')->where('jenis', 'pengeluaran')->sum('nominal');
        echo "💰 Total Pemasukan: Rp " . number_format($totalPemasukan, 0, ',', '.') . "\n";
        echo "💸 Total Pengeluaran: Rp " . number_format($totalPengeluaran, 0, ',', '.') . "\n";
        echo "📊 Saldo Bersih: Rp " . number_format($totalPemasukan - $totalPengeluaran, 0, ',', '.') . "\n";

        // Hitung demografi
        $lansia = DB::table('wargas')->where('umur', '>=', 60)->count();
        $anak = DB::table('wargas')->where('umur', '<', 17)->count();
        echo "👴 Lansia (≥60): {$lansia}\n";
        echo "👶 Anak-anak (<17): {$anak}\n";
    }
}
