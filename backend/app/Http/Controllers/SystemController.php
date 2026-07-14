<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SystemController extends Controller
{
    /**
     * Update logged-in user profile settings (name, email, avatar, password).
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'avatar_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'password'    => 'nullable|string|min:6'
        ]);

        try {
            $user = Auth::user();
            $user->name = $request->name;
            $user->email = $request->email;

            if ($request->hasFile('avatar_file')) {
                $file = $request->file('avatar_file');
                $filename = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/profile'), $filename);
                $user->photo = '/uploads/profile/' . $filename;
            }

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            // Refresh user session with fresh data
            Auth::setUser($user->fresh());

            self::logActivity('UPDATE PROFIL', "Memperbarui profil akun: Nama menjadi '{$request->name}', Email menjadi '{$request->email}'");

            return response()->json([
                'status'  => 'success',
                'message' => 'Profil berhasil diperbarui!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal memperbarui profil: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update application global settings.
     */
    public function storeSettings(Request $request)
    {
        try {
            DB::table('settings')->updateOrInsert(['id' => 1], array_merge(
                $request->except('_token'),
                ['updated_at' => now()]
            ));

            self::logActivity('UPDATE PENGATURAN', "Memperbarui konfigurasi pengaturan sistem global.");

            return response()->json([
                'status'  => 'success',
                'message' => 'Pengaturan berhasil diperbarui!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal memperbarui pengaturan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update RT details configuration.
     */
    public function storeRt(Request $request)
    {
        $request->validate([
            'nomor_rt'       => 'required|string|max:20',
            'nomor_rw'       => 'required|string|max:20',
            'nama_wilayah'   => 'required|string|max:255',
            'alamat_lengkap' => 'required|string'
        ]);

        try {
            $rt = DB::table('rt_details')->first();
            $updateData = [
                'nomor_rt'       => $request->nomor_rt,
                'nomor_rw'       => $request->nomor_rw,
                'nama_wilayah'   => $request->nama_wilayah,
                'alamat_lengkap' => $request->alamat_lengkap,
                'updated_at'    => now()
            ];

            if ($rt) {
                DB::table('rt_details')->where('id', $rt->id)->update($updateData);
            } else {
                DB::table('rt_details')->insert(array_merge($updateData, ['created_at' => now()]));
            }

            self::logActivity('UPDATE RT', "Memperbarui identitas detail RT: RT {$request->nomor_rt} RW {$request->nomor_rw}, Wilayah {$request->nama_wilayah}");

            return response()->json([
                'status'  => 'success',
                'message' => 'Identitas profil RT berhasil diperbarui!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal memperbarui profil RT: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Fetch user activities feed data.
     */
    public function getAktivitasData(Request $request)
    {
        if (!$request->ajax()) return abort(404);

        try {
            $aktivitas = DB::table('activity_logs')
                ->leftJoin('users', 'activity_logs.user_id', '=', 'users.id')
                ->select(
                    'users.name',
                    'users.photo',
                    'users.role',
                    'activity_logs.action',
                    'activity_logs.description',
                    'activity_logs.created_at'
                )
                ->orderBy('activity_logs.created_at', 'desc')
                ->limit(20)
                ->get();

            $aktivitas->map(function($item) {
                $item->waktu_berlalu = $item->created_at
                    ? Carbon::parse($item->created_at)->diffForHumans()
                    : 'Beberapa saat yang lalu';

                $item->photo = $item->photo ?? 'https://ui-avatars.com/api/?name=' . urlencode($item->name ?? 'User') . '&background=random&color=fff';
                $item->hak_akses = $item->role ?? 'Sistem';
                return $item;
            });

            return response()->json($aktivitas);

        } catch (\Exception $e) {
            return response()->json([
                'error'   => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
