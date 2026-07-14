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
                'role'     => 'required|in:Super Admin,RT,Bendahara,Warga',
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
        abort_if(auth()->user()->role !== 'Super Admin', 403, 'Akses Ditolak');
        try {
            $request->validate([
                'id'       => 'required|integer',
                'name'     => 'required|string|max:255',
                'email'    => 'required|string|email|max:255|unique:users,email,' . $request->id,
                'password' => 'nullable|string|min:6',
                'role'     => 'required|in:Super Admin,RT,Bendahara,Warga',
                'status'   => 'required|in:Aktif,Nonaktif'
            ]);

            $user = User::findOrFail($request->id);
            $updateData = [
                'name'   => $request->name,
                'email'  => $request->email,
                'role'   => $request->role,
                'status' => $request->status
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            self::logActivity('UPDATE PENGGUNA', "Memperbarui akun pengguna: {$user->name} menjadi role {$request->role} ({$request->status})");

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
}
