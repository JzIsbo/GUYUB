<?php

namespace App\Services;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class SelfHealingDatabaseService
{
    public static function checkAndCreateTables()
    {
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            return;
        }

        // 1. users table role and status columns check
        if (Schema::hasTable('users')) {
            if (!Schema::hasColumn('users', 'role')) {
                Schema::table('users', function ($table) {
                    $table->string('role')->default('Warga');
                });
            }
            if (!Schema::hasColumn('users', 'status')) {
                Schema::table('users', function ($table) {
                    $table->string('status')->default('Aktif');
                });
            }
        }

        // 2. devices (Aset & Perangkat) table
        if (!Schema::hasTable('devices')) {
            Schema::create('devices', function ($table) {
                $table->id();
                $table->string('nama_perangkat');
                $table->string('jenis_perangkat');
                $table->string('nomor_serial')->nullable();
                $table->string('kondisi')->default('Baik');
                $table->text('keterangan')->nullable();
                $table->timestamps();
            });
        }

        // 3. payment_gateways table
        if (!Schema::hasTable('payment_gateways')) {
            Schema::create('payment_gateways', function ($table) {
                $table->id();
                $table->string('environment')->default('sandbox');
                $table->string('merchant_id')->nullable();
                $table->string('client_key')->nullable();
                $table->string('server_key')->nullable();
                $table->boolean('is_active')->default(false);
                $table->timestamps();
            });

            DB::table('payment_gateways')->insert([
                'environment' => 'sandbox',
                'created_at'  => now(),
                'updated_at'  => now()
            ]);
        }

        // 4. online_payments table
        if (!Schema::hasTable('online_payments')) {
            Schema::create('online_payments', function ($table) {
                $table->id();
                $table->string('order_id')->unique();
                $table->string('nama_pembayar');
                $table->string('metode_pembayaran')->nullable();
                $table->decimal('nominal', 15, 2);
                $table->string('status')->default('pending');
                $table->timestamps();
            });
        }

        // 5. gateway_logs table
        if (!Schema::hasTable('gateway_logs')) {
            Schema::create('gateway_logs', function ($table) {
                $table->id();
                $table->string('status_code', 10)->default('200');
                $table->string('method', 10)->default('POST');
                $table->string('endpoint')->default('/payment/callback');
                $table->string('order_id')->nullable();
                $table->text('payload')->nullable();
                $table->timestamps();
            });
        }

        // 6. qris_settings table
        if (!Schema::hasTable('qris_settings')) {
            Schema::create('qris_settings', function ($table) {
                $table->id();
                $table->string('qris_data')->default('KAS-RT-01-PEMBAYARAN-VALID');
                $table->string('bank_1_name')->default('Bank BCA');
                $table->string('bank_1_number')->default('8721 0092 112');
                $table->string('bank_1_owner')->default('a.n Kas RT 01 (Bpk. Budi)');
                $table->string('bank_2_name')->default('Bank BNI');
                $table->string('bank_2_number')->default('0991 2234 55');
                $table->string('bank_2_owner')->default('a.n Bendahara RT (Ibu Siti)');
                $table->timestamps();
            });

            DB::table('qris_settings')->insert([
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // 7. surat_online table
        if (!Schema::hasTable('surat_online')) {
            Schema::create('surat_online', function ($table) {
                $table->id();
                $table->string('nama_warga');
                $table->string('jenis_surat');
                $table->text('keperluan');
                $table->string('status')->default('Menunggu');
                $table->timestamps();
            });
        }

        // 8. pengumumans table
        if (!Schema::hasTable('pengumumans')) {
            Schema::create('pengumumans', function ($table) {
                $table->id();
                $table->string('judul');
                $table->text('isi');
                $table->string('status')->default('Aktif');
                $table->timestamps();
            });
        }

        // 9. Koperasi table
        if (!Schema::hasTable('koperasi_items')) {
            Schema::create('koperasi_items', function ($table) {
                $table->id();
                $table->string('nama_produk');
                $table->string('kategori')->default('Sembako');
                $table->decimal('harga', 15, 2);
                $table->integer('stok')->default(0);
                $table->string('penjual')->default('Koperasi RT');
                $table->timestamps();
            });
        }

        // 10. Bank Sampah table
        if (!Schema::hasTable('bank_sampah_deposits')) {
            Schema::create('bank_sampah_deposits', function ($table) {
                $table->id();
                $table->string('nama_warga');
                $table->string('jenis_sampah'); // Plastik, Kertas, Logam, Botol
                $table->decimal('berat_kg', 8, 2);
                $table->decimal('total_rupiah', 15, 2);
                $table->date('tanggal');
                $table->timestamps();
            });
        }

        // 11. UMKM table
        if (!Schema::hasTable('umkms')) {
            Schema::create('umkms', function ($table) {
                $table->id();
                $table->string('nama_usaha');
                $table->string('pemilik');
                $table->string('kategori')->default('Kuliner');
                $table->string('kontak');
                $table->text('deskripsi')->nullable();
                $table->string('gambar')->nullable();
                $table->string('status')->default('Aktif');
                $table->timestamps();
            });
        } elseif (!Schema::hasColumn('umkms', 'gambar')) {
            Schema::table('umkms', function ($table) {
                $table->string('gambar')->nullable();
            });
        }

        // 12. Posyandu table
        if (!Schema::hasTable('posyandus')) {
            Schema::create('posyandus', function ($table) {
                $table->id();
                $table->string('nama_kegiatan');
                $table->string('target_peserta')->default('Balita & Lansia');
                $table->date('tanggal');
                $table->string('lokasi');
                $table->text('keterangan')->nullable();
                $table->timestamps();
            });
        }

        // 12b. Posyandu Pendaftaran table
        if (!Schema::hasTable('posyandu_pendaftarans')) {
            Schema::create('posyandu_pendaftarans', function ($table) {
                $table->id();
                $table->unsignedBigInteger('posyandu_id');
                $table->string('nama_peserta');
                $table->string('usia')->nullable();
                $table->string('tinggi_badan')->nullable(); // cm
                $table->string('berat_badan')->nullable(); // kg
                $table->string('kategori')->default('Balita'); // Balita, Lansia
                $table->string('nama_pendaftar'); // user yang mendaftarkan
                $table->string('hubungan')->default('Ibu'); // Ibu, Ayah, Cucu, dll
                $table->text('catatan')->nullable();
                $table->string('status')->default('Terdaftar'); // Terdaftar, Hadir, Tidak Hadir
                $table->timestamps();
            });
        } else {
            // Add columns if table already exists
            if (!Schema::hasColumn('posyandu_pendaftarans', 'tinggi_badan')) {
                Schema::table('posyandu_pendaftarans', function ($table) {
                    $table->string('tinggi_badan')->nullable()->after('usia');
                    $table->string('berat_badan')->nullable()->after('tinggi_badan');
                });
            }
        }

        // 13. Ronda & Keamanan table
        if (!Schema::hasTable('rondas')) {
            Schema::create('rondas', function ($table) {
                $table->id();
                $table->string('hari');
                $table->string('petugas_ronda'); // Nama-nama warga
                $table->string('koordinator');
                $table->string('jam_shift')->default('22:00 - 04:00 WIB');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('incidents')) {
            Schema::create('incidents', function ($table) {
                $table->id();
                $table->string('pelapor');
                $table->string('jenis_kejadian'); // Pencurian, Kebakaran, Keributan, Lainnya
                $table->text('deskripsi');
                $table->string('status')->default('Perlu Penanganan');
                $table->timestamps();
            });
        }

        // 14. Kegiatan RT table
        if (!Schema::hasTable('kegiatans')) {
            Schema::create('kegiatans', function ($table) {
                $table->id();
                $table->string('nama_kegiatan');
                $table->date('tanggal');
                $table->string('waktu');
                $table->string('lokasi');
                $table->text('deskripsi')->nullable();
                $table->string('gambar')->nullable();
                $table->timestamps();
            });
        } elseif (!Schema::hasColumn('kegiatans', 'gambar')) {
            Schema::table('kegiatans', function ($table) {
                $table->string('gambar')->nullable();
            });
        }

        // 15. Rukem table
        if (!Schema::hasTable('rukems')) {
            Schema::create('rukems', function ($table) {
                $table->id();
                $table->string('nama_almarhum');
                $table->string('keluarga_duka');
                $table->date('tanggal_duka');
                $table->decimal('santunan_diserahkan', 15, 2);
                $table->string('status_santunan')->default('Tersalurkan');
                $table->timestamps();
            });
        }

        // 16. Aspirasi table
        if (!Schema::hasTable('aspirasis')) {
            Schema::create('aspirasis', function ($table) {
                $table->id();
                $table->string('nama_warga')->default('Anonim');
                $table->string('topik');
                $table->text('isi_aspirasi');
                $table->text('tanggapan_rt')->nullable();
                $table->string('status')->default('Menunggu Response');
                $table->timestamps();
            });
        }

        // --- AUTOMATIC SAMPLE DATA SEEDER FOR PUBLIC MODULES ---
        self::seedSampleData();
    }

    private static function seedSampleData()
    {
        try {
            // Seed Pengumuman if empty
            if (DB::table('pengumumans')->count() === 0) {
                DB::table('pengumumans')->insert([
                    [
                        'judul' => 'Kerja Bakti Massal & Penghijauan Lingkungan',
                        'isi' => 'Dihimbau kepada seluruh warga RT 01 untuk mengikuti agenda kerja bakti pembersihan saluran air dan penanaman pohon pada hari Minggu besok jam 07.00 WIB. Kumpul di Balai RT.',
                        'status' => 'Aktif',
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'judul' => 'Jadwal Layanan Posyandu Balita & Lansia Bulan Ini',
                        'isi' => 'Kegiatan Posyandu rutin akan dilaksanakan pada hari Rabu mendatang. Mohon para ibu membawa balita untuk penimbangan & imunisasi, serta lansia untuk pemeriksaan kesehatan gratis.',
                        'status' => 'Aktif',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                ]);
            }

            // Seed UMKM if empty
            if (DB::table('umkms')->count() === 0) {
                DB::table('umkms')->insert([
                    [
                        'nama_usaha' => 'Warung Makan Bu Sri (Nasi Uduk & Soto)',
                        'pemilik' => 'Ibu Sri Rahayu',
                        'kategori' => 'Kuliner',
                        'kontak' => '081234567890',
                        'deskripsi' => 'Menerima pesanan nasi uduk lezat, soto ayam kampung segar, dan aneka kue basah tasyakuran warga. Siap antar gratis ke rumah warga.',
                        'gambar' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=800&auto=format&fit=crop',
                        'status' => 'Aktif',
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'nama_usaha' => 'Toko Sembako Berkah Jaya',
                        'pemilik' => 'Bpk. Hendra',
                        'kategori' => 'Sembako',
                        'kontak' => '081987654321',
                        'deskripsi' => 'Menyediakan beras kualitas super, minyak goreng, telur segar, gula, dan kebutuhan pokok harian warga dengan harga terjangkau.',
                        'gambar' => 'https://images.unsplash.com/photo-1578916171728-46686eac8d58?q=80&w=800&auto=format&fit=crop',
                        'status' => 'Aktif',
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'nama_usaha' => 'Kopi Seduh & Camilan Dapur Warga',
                        'pemilik' => 'Mas Rian',
                        'kategori' => 'Minuman',
                        'kontak' => '085712345678',
                        'deskripsi' => 'Kopi susu gula aren kekinian dan snack kentang renyah. Siap menemani waktu santai sore keluarga dan pos ronda malam.',
                        'gambar' => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?q=80&w=800&auto=format&fit=crop',
                        'status' => 'Aktif',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                ]);
            }

            // Seed Kegiatan if empty
            if (DB::table('kegiatans')->count() === 0) {
                DB::table('kegiatans')->insert([
                    [
                        'nama_kegiatan' => 'Kerja Bakti & Pembersihan Selokan RT',
                        'tanggal' => date('Y-m-d', strtotime('+3 days')),
                        'waktu' => '07:00 - 10:00 WIB',
                        'lokasi' => 'Sepanjang Jalan Utama RT 01',
                        'deskripsi' => 'Pembersihan saluran air musim hujan dan pemangkasan dahan pohon liar demi keamanan lingkungan warga.',
                        'gambar' => 'https://images.unsplash.com/photo-1582213782179-e0d53f98f2ca?q=80&w=800&auto=format&fit=crop',
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'nama_kegiatan' => 'Turnamen Bulutangkis Persahabatan',
                        'tanggal' => date('Y-m-d', strtotime('+10 days')),
                        'waktu' => '19:30 WIB',
                        'lokasi' => 'Lapangan Olahraga Balai RT',
                        'deskripsi' => 'Pertandingan persahabatan ganda putra dan ganda campuran antar blok untuk mempererat silaturahmi.',
                        'gambar' => 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?q=80&w=800&auto=format&fit=crop',
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'nama_kegiatan' => 'Musyawarah Warga & Laporan KAS RT',
                        'tanggal' => date('Y-m-d', strtotime('+15 days')),
                        'waktu' => '20:00 WIB',
                        'lokasi' => 'Balai Pertemuan RT 01',
                        'deskripsi' => 'Penyampaian transparansi keuangan kas RT semester pertama serta pembahasan rencana kerja bakti agustus.',
                        'gambar' => 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?q=80&w=800&auto=format&fit=crop',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                ]);
            }

            // Seed Posyandu if empty
            if (DB::table('posyandus')->count() === 0) {
                DB::table('posyandus')->insert([
                    [
                        'nama_kegiatan' => 'Posyandu Balita Sehat & Imunisasi',
                        'target_peserta' => 'Balita (0-5 Tahun)',
                        'tanggal' => date('Y-m-d', strtotime('+5 days')),
                        'lokasi' => 'Pos Sehat Balai RT 01',
                        'keterangan' => 'Penimbangan BB, pengukuran TB, imunisasi rutin, pemberian kapsul Vitamin A, serta penyuluhan gizi balita.',
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'nama_kegiatan' => 'Posyandu Lansia Bugar & Cek Kesehatan',
                        'target_peserta' => 'Lansia (>60 Tahun)',
                        'tanggal' => date('Y-m-d', strtotime('+12 days')),
                        'lokasi' => 'Halaman Masjid RT 01',
                        'keterangan' => 'Pemeriksaan gratis tekanan darah, asam urat, gula darah, senam lansia bersama, serta konsultasi medis.',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                ]);
            }

            // Seed Ronda if empty
            if (DB::table('rondas')->count() === 0) {
                DB::table('rondas')->insert([
                    ['hari' => 'Senin', 'petugas_ronda' => 'Bpk. Ahmad, Bpk. Budi, Bpk. Candra, Bpk. Dedi', 'koordinator' => 'Bpk. Ahmad', 'jam_shift' => '22:00 - 04:00 WIB', 'created_at' => now(), 'updated_at' => now()],
                    ['hari' => 'Selasa', 'petugas_ronda' => 'Bpk. Eko, Bpk. Fajar, Bpk. Gilang, Bpk. Herman', 'koordinator' => 'Bpk. Eko', 'jam_shift' => '22:00 - 04:00 WIB', 'created_at' => now(), 'updated_at' => now()],
                    ['hari' => 'Rabu', 'petugas_ronda' => 'Bpk. Indra, Bpk. Joko, Bpk. Krisna, Bpk. Lukman', 'koordinator' => 'Bpk. Joko', 'jam_shift' => '22:00 - 04:00 WIB', 'created_at' => now(), 'updated_at' => now()],
                    ['hari' => 'Kamis', 'petugas_ronda' => 'Bpk. Mulyadi, Bpk. Nanda, Bpk. Oscar, Bpk. Putu', 'koordinator' => 'Bpk. Mulyadi', 'jam_shift' => '22:00 - 04:00 WIB', 'created_at' => now(), 'updated_at' => now()],
                    ['hari' => 'Jumat', 'petugas_ronda' => 'Bpk. Qasim, Bpk. Rian, Bpk. Syaiful, Bpk. Taufik', 'koordinator' => 'Bpk. Rian', 'jam_shift' => '22:00 - 04:00 WIB', 'created_at' => now(), 'updated_at' => now()],
                    ['hari' => 'Sabtu', 'petugas_ronda' => 'Bpk. Umar, Bpk. Victor, Bpk. Wahyu, Bpk. Yoga', 'koordinator' => 'Bpk. Wahyu', 'jam_shift' => '22:00 - 04:00 WIB', 'created_at' => now(), 'updated_at' => now()],
                    ['hari' => 'Minggu', 'petugas_ronda' => 'Bpk. Zainal, Bpk. Agung, Bpk. Bambang, Bpk. Doni', 'koordinator' => 'Bpk. Zainal', 'jam_shift' => '22:00 - 04:00 WIB', 'created_at' => now(), 'updated_at' => now()],
                ]);
            }
        } catch (\Exception $e) {
            // Ignore seeding errors
        }
    }
}
