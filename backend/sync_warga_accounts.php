<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

echo "========================================================\n";
echo "🚀 SINKRONISASI AKUN WARGA & RESET PASSWORD KE 'password'\n";
echo "========================================================\n\n";

$defaultHashedPassword = Hash::make('password');

// 1. UPDATE SEMUA USER YANG SUDAH ADA
$existingUpdated = DB::table('users')->update([
    'password' => $defaultHashedPassword,
    'status'   => 'Aktif',
    'updated_at' => now(),
]);
echo "✅ Sebanyak {$existingUpdated} akun pengguna yang ada telah diupdate passwordnya menjadi 'password' & status 'Aktif'.\n\n";

// Pastikan semua Akun Demo Utama selalu ada dan aktif
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
            'name'     => $demo['name'],
            'role'     => $demo['role'],
            'password' => $defaultHashedPassword,
            'status'   => 'Aktif',
            'updated_at' => now(),
        ]);
    } else {
        DB::table('users')->insert([
            'name'       => $demo['name'],
            'email'      => $demo['email'],
            'password'   => $defaultHashedPassword,
            'role'       => $demo['role'],
            'status'     => 'Aktif',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

// 2. CEK SELURUH WARGA DARI TABEL 'wargas'
$allWargas = DB::table('wargas')->get();
$createdCount = 0;
$skippedCount = 0;

foreach ($allWargas as $w) {
    // Cek apakah warga ini sudah punya user berdasarkan nama lengkap
    $userExists = DB::table('users')->where('name', $w->nama_lengkap)->first();

    if ($userExists) {
        $skippedCount++;
        continue;
    }

    // Buat email gmail yang rapi & unik
    // Ambil nama depan atau nama gabungan bersih
    $cleanName = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $w->nama_lengkap));
    if (empty($cleanName)) {
        $cleanName = 'warga' . $w->id;
    }

    $email = $cleanName . '@gmail.com';

    // Jika email sudah dipakai akun lain, tambahkan ID warga
    if (DB::table('users')->where('email', $email)->exists()) {
        $email = $cleanName . $w->id . '@gmail.com';
    }

    // Double check agar benar-benar unik
    if (DB::table('users')->where('email', $email)->exists()) {
        $email = $cleanName . rand(100, 999) . '@gmail.com';
    }

    // Tentukan role: cek jika ada di tabel officers
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

    // Buat user baru
    DB::table('users')->insert([
        'name'       => $w->nama_lengkap,
        'email'      => $email,
        'password'   => $defaultHashedPassword,
        'role'       => $role,
        'status'     => 'Aktif',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $createdCount++;
}

echo "✅ Berhasil membuat {$createdCount} akun login baru untuk warga yang belum memiliki akun.\n";
echo "ℹ️  Sebanyak {$skippedCount} warga sudah memiliki akun sebelumnya.\n";

$finalUserCount = DB::table('users')->count();
echo "\n🎉 SELESAI! Total seluruh akun user aktif di sistem sekarang: {$finalUserCount} Akun.\n";
echo "🔑 Password untuk SEMUA user: 'password'\n";
