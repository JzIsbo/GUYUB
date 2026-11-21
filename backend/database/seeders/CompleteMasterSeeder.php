<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Warga;
use Faker\Factory as Faker;

class CompleteMasterSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $now = now();

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
                $blok = $faker->randomElement($blok_list) . ' No. ' . rand(1, 30);

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
                $blok = $faker->randomElement($blok_list) . ' No. ' . rand(1, 35);
                $domisili = $faker->randomElement(['Tetap', 'Tetap', 'Kontrak']);

                // Kepala Keluarga
                DB::table('wargas')->insert([
                    'nomor_kk'        => $nomor_kk,
                    'nik'             => '3275' . str_pad(rand(100000000000, 999999999999), 12, '0', STR_PAD_LEFT),
                    'nama_lengkap'    => $faker->name('male'),
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
                    'nama_lengkap'    => $faker->name('female'),
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
                        'nama_lengkap'    => $faker->name(),
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
            ['nama_iuran' => 'Iuran Keamanan & Satpam', 'nominal' => 35000, 'sifat' => 'Wajib', 'deskripsi' => 'Honor petugas jaga pos ronda dan pemeliharaan portal otomatis'],
            ['nama_iuran' => 'Iuran Kebersihan & Sampah', 'nominal' => 25000, 'sifat' => 'Wajib', 'deskripsi' => 'Pengangkutan sampah rumah tangga 3x seminggu ke TPA'],
            ['nama_iuran' => 'Iuran Kas Rutin RT', 'nominal' => 20000, 'sifat' => 'Wajib', 'deskripsi' => 'Kas operasional kegiatan warga, rapat bulanan, dan perawatan fasum'],
            ['nama_iuran' => 'Dana Sosial & Kematian (Rukem)', 'nominal' => 10000, 'sifat' => 'Sukarela', 'deskripsi' => 'Santunan duka cita, perlengkapan jenazah, dan santunan warga sakit'],
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
                        'warga_id'     => $w->id,
                        'jabatan'      => $jabatan,
                        'status_aktif' => 'Aktif',
                        'periode'      => '2024 - 2027',
                        'created_at'   => $now,
                        'updated_at'   => $now,
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
                ['nama_perangkat' => 'Tenda Pesta Lipat Ukuran 3x6 Meter', 'nomor_seri' => 'INV-TND-01', 'kondisi' => 'Baik', 'lokasi' => 'Gudang Balai RT', 'keterangan' => 'Lengkap dengan terpal atap dan dinding penutup samping'],
                ['nama_perangkat' => 'Kursi Lipat Chitose Besi (Set 50 Pcs)', 'nomor_seri' => 'INV-KRS-01', 'kondisi' => 'Baik', 'lokasi' => 'Gudang Balai RT', 'keterangan' => 'Kursi rapat warga dan hajatan lingkungan'],
                ['nama_perangkat' => 'Sound System Portable Wireless 12 Inch', 'nomor_seri' => 'INV-SND-01', 'kondisi' => 'Baik', 'lokasi' => 'Rumah Ketua RT', 'keterangan' => 'Lengkap dengan 2 Mic Wireless dan kabel charger'],
                ['nama_perangkat' => 'Mesin Pemotong Rumput Dorong Honda', 'nomor_seri' => 'INV-MPT-01', 'kondisi' => 'Baik', 'lokasi' => 'Pos Ronda Utama', 'keterangan' => 'Digunakan saat kerja bakti membersihkan lapangan fasum'],
                ['nama_perangkat' => 'Handy Talkie (HT) Baofeng UV-5R (4 Unit)', 'nomor_seri' => 'INV-HT-01', 'kondisi' => 'Baik', 'lokasi' => 'Pos Ronda Utama', 'keterangan' => 'Komunikasi petugas keamanan ronda malam warga'],
                ['nama_perangkat' => 'Portal Gerbang Otomatis RFID / ESP32', 'nomor_seri' => 'PORTAL-RFID-01', 'kondisi' => 'Baik', 'lokasi' => 'Pintu Gerbang Masuk Utama', 'keterangan' => 'Terhubung dengan sistem akses kartu e-KTP GUYUB'],
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
                ['nama_produk' => 'Beras Rojolele Super Premium 5kg', 'kategori' => 'Sembako', 'harga' => 72000, 'stok' => 30, 'satuan' => 'Sak', 'deskripsi' => 'Beras putih pulen wangi kualitas super'],
                ['nama_produk' => 'Minyak Goreng Tropical 2 Liter', 'kategori' => 'Sembako', 'harga' => 36500, 'stok' => 45, 'satuan' => 'Pouch', 'deskripsi' => 'Minyak goreng kelapa sawit 2x penyaringan'],
                ['nama_produk' => 'Gula Pasir Gulaku Kuning Premium 1kg', 'kategori' => 'Sembako', 'harga' => 17500, 'stok' => 50, 'satuan' => 'Kg', 'deskripsi' => 'Gula tebu murni manis alami'],
                ['nama_produk' => 'Telur Ayam Negeri Segar (Tray 30 Butir)', 'kategori' => 'Sembako', 'harga' => 52000, 'stok' => 15, 'satuan' => 'Tray', 'deskripsi' => 'Telur langsung dari peternak lokal segar'],
                ['nama_produk' => 'Indomie Kuah Ayam Bawang (Dus 40 Pcs)', 'kategori' => 'Sembako', 'harga' => 115000, 'stok' => 20, 'satuan' => 'Dus', 'deskripsi' => 'Mie instan favorit keluarga Indonesia'],
                ['nama_produk' => 'Sabun Cuci Piring Sunlight Jeruk Nipis 750ml', 'kategori' => 'Kebutuhan Rumah', 'harga' => 14000, 'stok' => 35, 'satuan' => 'Pouch', 'deskripsi' => 'Pembersih lemak ampuh dengan ekstrak jeruk nipis'],
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
                    'nama_kegiatan' => 'Posyandu Balita Melati 05 (Imunisasi & Timbang)',
                    'tanggal'       => '2026-11-28',
                    'waktu'         => '08:30 - 11:30 WIB',
                    'lokasi'        => 'Balai Warga RT 05',
                    'keterangan'    => 'Penimbangan balita, pengukuran tinggi badan, pemberian vitamin A dan imunisasi polio oleh Bidan Puskesmas.',
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ],
                [
                    'nama_kegiatan' => 'Posyandu Lansia Sehat Bugar Bersama',
                    'tanggal'       => '2026-12-05',
                    'waktu'         => '09:00 - 12:00 WIB',
                    'lokasi'        => 'Balai Warga RT 05',
                    'keterangan'    => 'Pemeriksaan tensi darah gratis, cek gula darah, kolesterol, asam urat, serta konsultasi kesehatan dokter umum.',
                    'created_at'    => $now,
                    'updated_at'    => $now,
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
                ['nama_warga' => $namaWarga, 'jenis_sampah' => 'Kardus & Kertas Bekas', 'berat_kg' => 15.5, 'harga_per_kg' => 2000, 'total_rupiah' => 31000, 'tanggal' => '2026-11-10', 'created_at' => $now, 'updated_at' => $now],
                ['nama_warga' => 'Hj. Siti Aminah', 'jenis_sampah' => 'Botol Plastik Bersih (PET)', 'berat_kg' => 8.0, 'harga_per_kg' => 4000, 'total_rupiah' => 32000, 'tanggal' => '2026-11-14', 'created_at' => $now, 'updated_at' => $now],
                ['nama_warga' => 'Bambang Soekarno', 'jenis_sampah' => 'Minyak Jelantah', 'berat_kg' => 6.0, 'harga_per_kg' => 6000, 'total_rupiah' => 36000, 'tanggal' => '2026-11-18', 'created_at' => $now, 'updated_at' => $now],
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

        echo "\n🎉 SEEDING MASTER DATA SELESAI DENGAN SUKSES!\n";
    }
}
