<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Store a newly created user in database.
     */
    public function store(Request $request)
    {
        abort_if(auth()->user()->role !== 'Super Admin', 403, 'Akses Ditolak');
        try {
            $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:6',
                'role'     => 'required|in:Super Admin,RW,Sekretaris RW,Bendahara RW,RT,Sekretaris RT,Bendahara RT,Warga',
                'status'   => 'required|in:Aktif,Nonaktif'
            ]);

            User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => $request->role,
                'status'   => $request->status
            ]);

            self::logActivity('BUAT PENGGUNA', "Mendaftarkan pengguna baru: {$request->name} ({$request->role})");

            return response()->json([
                'status'  => 'success',
                'message' => 'Akun pengguna baru berhasil didaftarkan!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errorMsg = collect($e->errors())->flatten()->first();
            return response()->json([
                'status'  => 'error',
                'message' => $errorMsg
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal mendaftarkan akun: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified user in database.
     */
    public function update(Request $request)
    {
        abort_if(auth()->user()->role !== 'Super Admin' && auth()->user()->role !== 'RT', 403, 'Akses Ditolak');
        try {
            $request->validate([
                'id'       => 'required|integer',
                'name'     => 'nullable|string|max:255',
                'email'    => 'nullable|string|email|max:255|unique:users,email,' . $request->id,
                'password' => 'nullable|string|min:6',
                'role'     => 'nullable|in:Super Admin,RW,Sekretaris RW,Bendahara RW,RT,Sekretaris RT,Bendahara RT,Warga',
                'status'   => 'nullable|in:Aktif,Nonaktif,Pending,Ditolak'
            ]);

            $user = User::findOrFail($request->id);
            $updateData = [];

            if ($request->has('name')) $updateData['name'] = $request->name;
            if ($request->has('email')) $updateData['email'] = $request->email;
            if ($request->has('role')) $updateData['role'] = $request->role;
            if ($request->has('status')) $updateData['status'] = $request->status;

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            self::logActivity('UPDATE PENGGUNA', "Memperbarui akun pengguna: {$user->name} (" . ($request->role ?? $user->role) . ") - status: " . ($request->status ?? $user->status));

            return response()->json([
                'status'  => 'success',
                'message' => 'Akun pengguna berhasil diperbarui!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errorMsg = collect($e->errors())->flatten()->first();
            return response()->json([
                'status'  => 'error',
                'message' => $errorMsg
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal memperbarui akun: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified user from database.
     */
    public function destroy(Request $request)
    {
        abort_if(auth()->user()->role !== 'Super Admin', 403, 'Akses Ditolak');
        $request->validate(['id' => 'required']);

        try {
            $user = User::findOrFail($request->id);
            $userName = $user->name;
            $userEmail = $user->email;
            $user->delete();

            self::logActivity('HAPUS PENGGUNA', "Menghapus akun pengguna: {$userName} ({$userEmail})");

            return response()->json([
                'status'  => 'success',
                'message' => 'Akun pengguna berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menghapus akun.'
            ], 500);
        }
    }

    /**
     * Update dynamic registration details for approval module
     */
    public function updateRegistration(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RT']), 403, 'Akses Ditolak');
        
        try {
            $request->validate([
                'id' => 'required|integer', // user_id
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $request->id,
                'nomor_kk' => 'required|numeric|digits:16',
                'status_warga' => 'required|in:Tetap,Kontrak,Kos',
                'blok_rumah' => 'required|string|max:255',
                'umur' => 'required|integer|min:1|max:120',
                'status_keluarga' => 'required|in:Kepala Keluarga,Istri,Anak,Lainnya',
                'status' => 'required|in:Aktif,Pending,Ditolak',
            ]);

            $user = User::findOrFail($request->id);
            $oldName = $user->name;

            // Update User
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'status' => $request->status,
            ]);

            // Update or Insert Warga linked by the old name or new name
            $warga = \Illuminate\Support\Facades\DB::table('wargas')->where('nama_lengkap', $oldName)->first();
            if ($warga) {
                \Illuminate\Support\Facades\DB::table('wargas')
                    ->where('id', $warga->id)
                    ->update([
                        'nama_lengkap' => $request->name,
                        'nomor_kk' => $request->nomor_kk,
                        'blok_rumah' => $request->blok_rumah,
                        'status_domisili' => $request->status_warga,
                        'status_keluarga' => $request->status_keluarga,
                        'umur' => $request->umur,
                    ]);
            } else {
                $nik = '3275' . str_pad(rand(0, 999999999999), 12, '0', STR_PAD_LEFT);
                \Illuminate\Support\Facades\DB::table('wargas')->insert([
                    'nik' => $nik,
                    'nomor_kk' => $request->nomor_kk,
                    'nama_lengkap' => $request->name,
                    'blok_rumah' => $request->blok_rumah,
                    'status_domisili' => $request->status_warga,
                    'status_keluarga' => $request->status_keluarga,
                    'umur' => $request->umur,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            self::logActivity('UPDATE REGISTRASI', "Memperbarui data registrasi warga: {$request->name} (Status: {$request->status})");

            return response()->json([
                'status' => 'success',
                'message' => 'Data pendaftaran warga berhasil diperbarui!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errorMsg = collect($e->errors())->flatten()->first();
            return response()->json([
                'status'  => 'error',
                'message' => $errorMsg
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal memperbarui data pendaftaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete pending/active citizen registration
     */
    public function deleteRegistration(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RT']), 403, 'Akses Ditolak');
        
        try {
            $request->validate([
                'id' => 'required|integer', // user_id
            ]);

            $user = User::findOrFail($request->id);
            $name = $user->name;

            // Delete user
            $user->delete();

            // Delete warga
            \Illuminate\Support\Facades\DB::table('wargas')->where('nama_lengkap', $name)->delete();

            self::logActivity('HAPUS REGISTRASI', "Menghapus pendaftaran warga: {$name}");

            return response()->json([
                'status' => 'success',
                'message' => 'Pendaftaran warga berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menghapus pendaftaran: ' . $e->getMessage()
            ], 500);
        }
    }
}
