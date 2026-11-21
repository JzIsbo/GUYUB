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
    // F. handleScan — API (rfid.device middleware)
    //    ESP32 registration terminal kirim UID → assigned ke sesi aktif
    // =========================================================================
    public function handleScan(Request $request)
    {
        $request->validate([
            'uid'         => 'required|string|max:50',
            'device_id'   => 'nullable|string|max:100',
            'device_type' => 'nullable|string|max:50',
        ]);

        $uid        = strtoupper(trim($request->uid));
        $deviceType = $request->input('device_type', 'registration');

        // Jika device adalah gate, arahkan ke gateCheck
        if ($deviceType === 'gate') {
            return $this->gateCheck($request);
        }

        // Cari sesi waiting paling lama (FIFO) yang belum expired
        $session = RfidScanSession::where('status', 'waiting')
            ->where('expires_at', '>', now())
            ->orderBy('created_at', 'asc')
            ->first();

        if (!$session) {
            return response()->json([
                'status'  => 'no_session',
                'message' => 'Tidak ada sesi registrasi aktif. Pastikan user sudah klik "Daftarkan KTP" di aplikasi.',
                'uid'     => $uid,
            ]);
        }

        // Assign UID ke sesi
        $session->update([
            'scanned_uid' => $uid,
            'status'      => 'completed',
        ]);

        return response()->json([
            'status'   => 'success',
            'assigned' => true,
            'uid'      => $uid,
            'user_id'  => $session->user_id,
            'message'  => "UID {$uid} berhasil di-scan. Menunggu konfirmasi user di aplikasi.",
        ]);
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
    //    ESP32 mengirim ping setiap 5 detik agar web tahu statusnya ONLINE
    // =========================================================================
    public function heartbeat(Request $request)
    {
        $deviceId = $request->input('device_id', 'esp32-scanner');
        $ip       = $request->input('ip', $request->ip());

        Cache::put('rfid_scanner_last_seen', now()->timestamp, 30);
        Cache::put('rfid_scanner_info', [
            'device_id' => $deviceId,
            'ip'        => $ip,
            'timestamp' => now()->toDateTimeString(),
        ], 30);

        return response()->json([
            'status' => 'ok',
            'time'   => now()->toDateTimeString()
        ]);
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
}
