<?php

namespace App\Http\Controllers;

use App\Models\PortalAccessLog;
use App\Models\RfidScanSession;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RfidController extends Controller
{
    // =========================================================================
    // A. startRegistration — Web (auth)
    //    User klik "Daftarkan KTP" → server buat sesi → polling dimulai
    // =========================================================================
    public function startRegistration(Request $request)
    {
        $user = Auth::user();

        // Expire semua sesi lama user ini
        RfidScanSession::where('user_id', $user->id)
            ->where('status', 'waiting')
            ->update(['status' => 'expired']);

        // Buat sesi baru
        $ttl   = config('rfid.session_ttl_minutes', 5);
        $token = (string) Str::uuid();

        RfidScanSession::create([
            'user_id'    => $user->id,
            'token'      => $token,
            'status'     => 'waiting',
            'expires_at' => now()->addMinutes($ttl),
        ]);

        return response()->json([
            'status'  => 'success',
            'token'   => $token,
            'ttl'     => $ttl * 60, // dalam detik, untuk countdown di frontend
            'message' => "Silakan tempelkan KTP ke perangkat RFID dalam {$ttl} menit.",
        ]);
    }

    // =========================================================================
    // B. sessionStatus — Web (auth), dipanggil via polling setiap ~1.5 detik
    // =========================================================================
    public function sessionStatus(Request $request, string $token)
    {
        $session = RfidScanSession::where('token', $token)
            ->where('user_id', Auth::id())
            ->first();

        if (!$session) {
            return response()->json(['status' => 'not_found'], 404);
        }

        // Cek expired
        if ($session->status === 'waiting' && $session->isExpired()) {
            $session->update(['status' => 'expired']);
            return response()->json([
                'status'  => 'expired',
                'message' => 'Waktu pendaftaran habis. Silakan coba lagi.',
            ]);
        }

        if ($session->status === 'completed') {
            $uid = strtoupper(trim($session->scanned_uid));
            $existing = User::where('rfid_uid', $uid)->first();

            if ($existing) {
                if ($existing->id === Auth::id()) {
                    return response()->json([
                        'status'         => 'already_registered',
                        'is_self'        => true,
                        'uid'            => $uid,
                        'registered_to'  => $existing->name,
                        'message'        => 'Kartu ini sudah terdaftar pada akun Anda sendiri.',
                    ]);
                } else {
                    return response()->json([
                        'status'         => 'already_registered',
                        'is_self'        => false,
                        'uid'            => $uid,
                        'registered_to'  => $existing->name,
                        'message'        => "Kartu ini sudah terdaftar atas nama warga lain ({$existing->name}). Satu kartu e-KTP tidak dapat didaftarkan ke lebih dari satu akun.",
                    ]);
                }
            }

            return response()->json([
                'status' => 'completed',
                'uid'    => $session->scanned_uid,
            ]);
        }

        if ($session->status === 'expired') {
            return response()->json([
                'status'  => 'expired',
                'message' => 'Waktu pendaftaran habis. Silakan coba lagi.',
            ]);
        }

        // Masih waiting
        $remaining = now()->diffInSeconds($session->expires_at, false);
        return response()->json([
            'status'    => 'waiting',
            'remaining' => max(0, $remaining),
        ]);
    }

    // =========================================================================
    // C. assignUid — Web (auth)
    //    Setelah polling dapat 'completed', user konfirmasi → UID disimpan ke akun
    // =========================================================================
    public function assignUid(Request $request)
    {
        $request->validate(['token' => 'required|string']);

        $session = RfidScanSession::where('token', $request->token)
            ->where('user_id', Auth::id())
            ->where('status', 'completed')
            ->first();

        if (!$session) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Sesi tidak ditemukan atau belum selesai scanning.',
            ], 404);
        }

        $uid = strtoupper($session->scanned_uid);

        // Cek apakah UID sudah dipakai user lain
        $existing = User::where('rfid_uid', $uid)
            ->where('id', '!=', Auth::id())
            ->first();

        if ($existing) {
            $session->delete();
            return response()->json([
                'status'  => 'error',
                'message' => "UID kartu ini sudah terdaftar untuk akun {$existing->name}. Satu kartu e-KTP hanya dapat digunakan untuk 1 akun warga.",
            ], 409);
        }

        // Simpan UID ke user
        $user = Auth::user();
        $user->rfid_uid = $uid;
        $user->save();

        $session->delete();

        self::logActivity(
            'DAFTARKAN RFID KTP',
            "Pengguna {$user->name} berhasil mendaftarkan KTP dengan UID: {$uid}"
        );

        return response()->json([
            'status'  => 'success',
            'uid'     => $uid,
            'message' => 'KTP berhasil didaftarkan ke akun Anda!',
        ]);
    }

    // =========================================================================
    // D. unregisterUid — Web (auth)
    //    Hapus UID dari akun user
    // =========================================================================
    public function unregisterUid(Request $request)
    {
        $user = Auth::user();
        $oldUid = $user->rfid_uid;

        if (!$oldUid) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Tidak ada KTP yang terdaftar di akun Anda.',
            ], 400);
        }

        $user->rfid_uid = null;
        $user->save();

        self::logActivity(
            'HAPUS RFID KTP',
            "Pengguna {$user->name} menghapus KTP terdaftar (UID: {$oldUid})"
        );

        return response()->json([
            'status'  => 'success',
            'message' => 'KTP berhasil dihapus dari akun Anda.',
        ]);
    }

    // =========================================================================
    // E. getLogs — Web (auth, role: Super Admin / RT / RW)
    //    Ambil log akses portal untuk halaman admin
    // =========================================================================
    public function getLogs(Request $request)
    {
        $user = Auth::user();
        $allowedRoles = ['Super Admin', 'RT', 'RW', 'Sekretaris RT', 'Sekretaris RW'];

        if (!in_array($user->role, $allowedRoles)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Akses ditolak. Hanya pengurus yang dapat melihat log portal.',
            ], 403);
        }

        $logs = PortalAccessLog::with('user:id,name,role')
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json([
            'status' => 'success',
            'logs'   => $logs,
        ]);
    }

    // =========================================================================
    // F. tap — API (rfid.device middleware)
    //    Menerima data tap kartu RFID / e-KTP dari alat fisik ESP32
    // =========================================================================
    public function tap(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'uid'       => 'required|string',
            'device_id' => 'nullable|string',
        ]);

        $uid      = strtoupper(trim($request->input('uid')));
        $deviceId = $request->input('device_id', 'esp32-scanner-01');

        // Simpan denyut online alat di Cache Laravel setiap kali kartu di-tap
        Cache::put('rfid_reader_online', true, 30);
        Cache::put('rfid_scanner_last_seen', now()->timestamp, 30);
        Cache::put('rfid_scanner_info', [
            'device_id' => $deviceId,
            'ip'        => $request->ip(),
            'timestamp' => now()->toDateTimeString(),
        ], 30);

        // 2. Hubungkan logika fitur:
        // A. Cek apakah ada sesi pendaftaran KTP aktif di web
        $activeSession = RfidScanSession::where('status', 'waiting')
            ->where('expires_at', '>', now())
            ->orderBy('created_at', 'asc')
            ->first();

        if ($activeSession) {
            $activeSession->update([
                'scanned_uid' => $uid,
                'status'      => 'completed',
            ]);

            try {
                PortalAccessLog::create([
                    'user_id'   => $activeSession->user_id,
                    'rfid_uid'  => $uid,
                    'status'    => 'granted',
                    'device_id' => $deviceId,
                    'message'   => "UID {$uid} berhasil dipindai untuk pendaftaran e-KTP",
                ]);
            } catch (\Throwable $e) {}

            return response()->json([
                'success'           => true,
                'status'            => 'registered',
                'feedback'          => 'success',
                'user_name'         => 'Pendaftaran KTP',
                'direction'         => '',
                'buzzer'            => 'success',
                'open_gate'         => false,
                'message'           => 'Kartu berhasil dibaca untuk pendaftaran!',
                'uid'               => $uid,
                'device_id'         => $deviceId,
                'registered'        => true,
            ], 200);
        }

        // B. Cari data warga / user berdasarkan nomor kartu (UID)
        $user = User::where('rfid_uid', $uid)->first();
        $wargaInfo = null;

        if (!$user) {
            // Cek di tabel wargas berdasarkan NIK jika UID identik dengan NIK
            $warga = DB::table('wargas')->where('nik', $uid)->first();
            if ($warga) {
                $user = User::where('name', $warga->nama_lengkap)->first();
                $wargaInfo = [
                    'type'       => 'warga',
                    'name'       => $warga->nama_lengkap,
                    'nik'        => $warga->nik,
                    'blok_rumah' => $warga->blok_rumah,
                ];
            }
        }

        if ($user) {
            if (!$wargaInfo) {
                $wargaData = DB::table('wargas')->where('nama_lengkap', $user->name)->first();
                $wargaInfo = [
                    'type'       => 'user',
                    'name'       => $user->name,
                    'role'       => $user->role,
                    'status'     => $user->status,
                    'blok_rumah' => $wargaData->blok_rumah ?? '-',
                    'nik'        => $wargaData->nik ?? '-',
                ];
            }

            if ($user->status === 'Aktif') {
                // Catat log akses portal: DIIZINKAN (GRANTED)
                try {
                    PortalAccessLog::create([
                        'user_id'   => $user->id,
                        'rfid_uid'  => $uid,
                        'status'    => 'granted',
                        'device_id' => $deviceId,
                        'message'   => "Akses gerbang dibuka untuk {$user->name} ({$user->role})",
                    ]);
                } catch (\Throwable $e) {}

                // Response HTTP 200 lengkap sesuai format smart_rw_rfid_reader.ino
                // LCD akan menampilkan:
                // Baris 1: MASUK: [Nama Warga]
                // Baris 2: Gerbang Terbuka
                // Buzzer membunyikan beepGateIn(), Servo berputar membuka palang gerbang
                return response()->json([
                    'success'           => true,
                    'status'            => 'gate_opened',
                    'open_gate'         => true,
                    'direction'         => 'masuk',
                    'buzzer'            => 'gate_in',
                    'gate_open_seconds' => config('rfid.gate_open_seconds', 5),
                    'user_name'         => $user->name,
                    'message'           => "Akses gerbang dibuka untuk {$user->name}",
                    'uid'               => $uid,
                    'device_id'         => $deviceId,
                    'warga'             => $wargaInfo,
                    'registered'        => true,
                ], 200);
            } else {
                // Catat log akses portal: DITOLAK (AKUN NONAKTIF)
                try {
                    PortalAccessLog::create([
                        'user_id'   => $user->id,
                        'rfid_uid'  => $uid,
                        'status'    => 'denied',
                        'device_id' => $deviceId,
                        'message'   => "Akses ditolak: akun tidak aktif ({$user->name})",
                    ]);
                } catch (\Throwable $e) {}

                return response()->json([
                    'success'   => false,
                    'status'    => 'inactive',
                    'open_gate' => false,
                    'buzzer'    => 'error',
                    'user_name' => $user->name,
                    'message'   => "Akun tidak aktif ({$user->name}). Hubungi pengurus RT.",
                    'uid'       => $uid,
                    'device_id' => $deviceId,
                    'registered'=> true,
                ], 403);
            }
        }

        // C. Kartu Belum Terdaftar (UNREGISTERED)
        try {
            PortalAccessLog::create([
                'user_id'   => null,
                'rfid_uid'  => $uid,
                'status'    => 'denied',
                'device_id' => $deviceId,
                'message'   => "Kartu belum terdaftar di-tap pada perangkat {$deviceId}",
            ]);
        } catch (\Throwable $e) {}

        return response()->json([
            'success'   => false,
            'status'    => 'unregistered',
            'open_gate' => false,
            'buzzer'    => 'error',
            'message'   => 'Kartu belum terdaftar di sistem. Akses ditolak.',
            'uid'       => $uid,
            'device_id' => $deviceId,
            'registered'=> false,
        ], 403);
    }

    // =========================================================================
    // F2. handleScan — Alias kompatibel untuk /api/rfid/scan
    // =========================================================================
    public function handleScan(Request $request)
    {
        return $this->tap($request);
    }

    // =========================================================================
    // G. gateCheck — API (rfid.device middleware)
    //    ESP32 portal gate kirim UID → server cek → grant/deny → catat log
    // =========================================================================
    public function gateCheck(Request $request)
    {
        $request->validate([
            'uid'       => 'required|string|max:50',
            'device_id' => 'nullable|string|max:100',
        ]);

        $uid      = strtoupper(trim($request->uid));
        $deviceId = $request->input('device_id', 'gate-unknown');

        // Cari user dengan UID ini dan status Aktif
        $user = User::where('rfid_uid', $uid)
            ->where('status', 'Aktif')
            ->first();

        if ($user) {
            // LOG: akses diberikan
            PortalAccessLog::create([
                'user_id'   => $user->id,
                'rfid_uid'  => $uid,
                'status'    => 'granted',
                'device_id' => $deviceId,
                'message'   => "Akses diberikan untuk {$user->name} ({$user->role})",
            ]);

            return response()->json([
                'access'            => true,
                'name'              => $user->name,
                'role'              => $user->role,
                'gate_open_seconds' => config('rfid.gate_open_seconds', 5),
                'message'           => "Selamat datang, {$user->name}!",
            ]);
        }

        // Cek apakah UID terdaftar tapi akun tidak aktif
        $inactiveUser = User::where('rfid_uid', $uid)->first();
        $message = $inactiveUser
            ? "KTP terdaftar tapi akun tidak aktif (status: {$inactiveUser->status}). Hubungi pengurus RT."
            : 'KTP tidak terdaftar di sistem. Akses ditolak.';

        // LOG: akses ditolak
        PortalAccessLog::create([
            'user_id'   => $inactiveUser?->id,
            'rfid_uid'  => $uid,
            'status'    => 'denied',
            'device_id' => $deviceId,
            'message'   => $message,
        ]);

        return response()->json([
            'access'  => false,
            'message' => $message,
        ], 403);
    }

    // =========================================================================
    // H. heartbeat — API (rfid.device middleware)
    //    ESP32 mengirim ping setiap 2-3 detik agar sistem tahu alat ONLINE
    // =========================================================================
    public function heartbeat(Request $request)
    {
        $deviceId = $request->input('device_id', 'esp32-scanner-01');
        $ip       = $request->input('ip', $request->ip());

        // Simpan status online alat di Cache Laravel (durasi 30 detik)
        Cache::put('rfid_reader_online', true, 30);
        Cache::put('rfid_scanner_last_seen', now()->timestamp, 30);
        Cache::put('rfid_scanner_info', [
            'device_id' => $deviceId,
            'ip'        => $ip,
            'timestamp' => now()->toDateTimeString(),
        ], 30);

        return response()->json([
            'status' => 'online',
        ], 200);
    }

    // =========================================================================
    // I. getDeviceStatus — Web (auth)
    //    Cek apakah ESP32 sedang online (aktif dalam 15 detik terakhir)
    // =========================================================================
    public function getDeviceStatus(Request $request)
    {
        $lastSeen = Cache::get('rfid_scanner_last_seen');
        $info     = Cache::get('rfid_scanner_info');

        if (!$lastSeen) {
            return response()->json([
                'online'             => false,
                'seconds_ago'        => null,
                'message'            => 'ESP32 belum terhubung atau sedang mati.',
                'info'               => null,
            ]);
        }

        $diff = now()->timestamp - $lastSeen;
        $isOnline = $diff <= 15; // Aktif jika ping <= 15 detik lalu

        return response()->json([
            'online'      => $isOnline,
            'seconds_ago' => $diff,
            'message'     => $isOnline ? 'ESP32 Scanner Online & Siap Digunakan' : "ESP32 Offline (terakhir terlihat {$diff} detik lalu)",
            'info'        => $info,
        ]);
    }

    // =========================================================================
    // J. manualOpenGate — Web (auth)
    //    Admin / Warga membuka palang gerbang secara manual melalui sistem Web
    // =========================================================================
    public function manualOpenGate(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        try {
            PortalAccessLog::create([
                'user_id'   => $user->id,
                'rfid_uid'  => $user->rfid_uid ?? 'MANUAL_WEB',
                'status'    => 'granted',
                'device_id' => 'web-manual',
                'message'   => "Gerbang dibuka secara manual via Web oleh {$user->name} ({$user->role})",
            ]);
        } catch (\Throwable $e) {}

        Cache::put('manual_open_gate_trigger', [
            'triggered_by' => $user->name,
            'role'         => $user->role,
            'at'           => now()->toDateTimeString(),
        ], 10);

        self::logActivity(
            'BUKA PORTAL MANUAL',
            "Pengguna {$user->name} ({$user->role}) membuka palang pintu portal gerbang secara manual via antarmuka Web."
        );

        return response()->json([
            'status'  => 'success',
            'message' => "Perintah buka portal manual berhasil dieksekusi oleh {$user->name}!",
        ]);
    }

    // =========================================================================
    // K. getPortalLive — Web (auth)
    //    Polling data realtime untuk live log portal akses
    // =========================================================================
    public function getPortalLive(Request $request)
    {
        $today = now()->toDateString();

        $logs = DB::table('portal_access_logs')
            ->leftJoin('users', 'portal_access_logs.user_id', '=', 'users.id')
            ->leftJoin('wargas', function($join) {
                $join->on('users.name', '=', 'wargas.nama_lengkap');
            })
            ->select(
                'portal_access_logs.id',
                'portal_access_logs.rfid_uid',
                'portal_access_logs.status',
                'portal_access_logs.device_id',
                'portal_access_logs.message',
                'portal_access_logs.created_at',
                'users.name as user_name',
                'users.role as user_role',
                'users.photo as user_photo',
                'users.status as user_status',
                'wargas.blok_rumah',
                'wargas.nik'
            )
            ->orderBy('portal_access_logs.created_at', 'desc')
            ->limit(30)
            ->get();

        $lastSeen = Cache::get('rfid_scanner_last_seen');
        $diff = $lastSeen ? (now()->timestamp - $lastSeen) : null;
        $isOnline = ($diff !== null && $diff <= 15);
        $scannerInfo = Cache::get('rfid_scanner_info');

        return response()->json([
            'status' => 'success',
            'online' => $isOnline,
            'diff'   => $diff,
            'scanner_info' => $scannerInfo,
            'stats' => [
                'today'           => DB::table('portal_access_logs')->whereDate('created_at', $today)->count(),
                'granted'         => DB::table('portal_access_logs')->whereDate('created_at', $today)->where('status', 'granted')->count(),
                'denied'          => DB::table('portal_access_logs')->whereDate('created_at', $today)->where('status', 'denied')->count(),
                'registered_rfid' => DB::table('users')->whereNotNull('rfid_uid')->count(),
            ],
            'logs'   => $logs,
        ]);
    }
}
