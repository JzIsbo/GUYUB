<?php

namespace App\Http\Controllers;

use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KeluargaController extends Controller
{
    /**
     * Check authorization.
     */
    private function checkAccess($targetNomorKk = null, $targetWargaId = null)
    {
        abort_if(!Auth::check(), 403, 'Akses Ditolak');
        $user = Auth::user();
        
        // Admin, RW, RT, Sekretaris have full access to manage any family
        if (in_array($user->role, ['Super Admin', 'RW', 'Sekretaris RW', 'RT', 'Sekretaris RT'])) {
            return;
        }
        
        // Warga & Bendahara roles have restricted access to their own family only
        if (in_array($user->role, ['Warga', 'Bendahara'])) {
            $userWarga = DB::table('wargas')->where('nama_lengkap', $user->name)->first();
            if (!$userWarga) {
                // If they don't have a warga profile at all, they can only do storeKk (creating their profile as Kepala Keluarga)
                return;
            }
            
            if (!$userWarga->nomor_kk) {
                // Profile exists but has no KK yet
                return;
            }
            
            // If they have a KK, check if the targeted KK matches their own KK
            if ($targetNomorKk !== null && $targetNomorKk != $userWarga->nomor_kk) {
                abort(403, 'Akses Ditolak: Anda hanya diperbolehkan mengelola data keluarga Anda sendiri.');
            }
            
            // If editing a specific member, check if the member belongs to their KK
            if ($targetWargaId !== null) {
                $targetWarga = DB::table('wargas')->where('id', $targetWargaId)->first();
                if (!$targetWarga || $targetWarga->nomor_kk != $userWarga->nomor_kk) {
                    abort(403, 'Akses Ditolak: Anggota tersebut bukan bagian dari keluarga Anda.');
                }
            }
            return;
        }
        
        abort(403, 'Akses Ditolak');
    }

    /**
     * Create a new KK (creates the Kepala Keluarga).
     */
    public function storeKk(Request $request)
    {
        $user = Auth::user();
        if ($user && in_array($user->role, ['Warga', 'Bendahara'])) {
            $userWarga = DB::table('wargas')->where('nama_lengkap', $user->name)->first();
            if ($userWarga && $userWarga->nomor_kk) {
                return response()->json(['status' => 'error', 'message' => 'Akses Ditolak: Anda sudah memiliki Kartu Keluarga.'], 403);
            }
            if ($request->nama_lengkap !== $user->name) {
                return response()->json(['status' => 'error', 'message' => 'Akses Ditolak: Nama Kepala Keluarga harus sesuai dengan nama akun Anda.'], 403);
            }
        } else {
            $this->checkAccess();
        }

        try {
            $request->validate([
                'nomor_kk'        => 'required|numeric',
                'nik'             => 'required|numeric|unique:wargas,nik',
                'nama_lengkap'    => 'required|string|max:255',
                'umur'            => 'nullable|integer|min:0|max:150',
                'agama'           => 'nullable|string|max:20',
                'no_telepon'      => 'nullable|string|max:20',
                'blok_rumah'      => 'required|string|max:255',
                'status_domisili' => 'required|string'
            ], [
                'nik.unique' => 'Gagal! NIK tersebut sudah terdaftar di sistem.'
            ]);

            $warga = Warga::create([
                'nomor_kk'        => $request->nomor_kk,
                'nik'             => $request->nik,
                'nama_lengkap'    => $request->nama_lengkap,
                'umur'            => $request->umur,
                'agama'           => $request->agama,
                'no_telepon'      => $request->no_telepon,
                'blok_rumah'      => $request->blok_rumah,
                'status_keluarga' => 'Kepala Keluarga',
                'status_domisili' => $request->status_domisili
            ]);

            self::logActivity('BUAT KK', "Menambahkan Kartu Keluarga baru: No. KK {$request->nomor_kk} dengan Kepala Keluarga {$request->nama_lengkap}");

            return response()->json(['status' => 'success', 'message' => 'Kartu Keluarga baru berhasil dibuat!']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['status' => 'error', 'message' => collect($e->errors())->flatten()->first()], 422);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Sistem gagal menyimpan data.'], 500);
        }
    }

    /**
     * Update KK details (updates nomor_kk and blok_rumah for all members).
     */
    public function updateKk(Request $request)
    {
        $this->checkAccess($request->old_nomor_kk);

        try {
            $request->validate([
                'old_nomor_kk' => 'required',
                'nomor_kk'     => 'required|numeric',
                'blok_rumah'   => 'required|string|max:255'
            ]);

            DB::transaction(function () use ($request) {
                DB::table('wargas')
                    ->where('nomor_kk', $request->old_nomor_kk)
                    ->update([
                        'nomor_kk'   => $request->nomor_kk,
                        'blok_rumah' => $request->blok_rumah
                    ]);
            });

            self::logActivity('UPDATE KK', "Memperbarui No. KK {$request->old_nomor_kk} menjadi {$request->nomor_kk} dan Blok {$request->blok_rumah}");

            return response()->json(['status' => 'success', 'message' => 'Data Kartu Keluarga berhasil diperbarui!']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['status' => 'error', 'message' => collect($e->errors())->flatten()->first()], 422);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Sistem gagal memperbarui data.'], 500);
        }
    }

    /**
     * Delete entire KK (deletes all members under the KK).
     */
    public function destroyKk(Request $request, $nomor_kk)
    {
        $this->checkAccess($nomor_kk);

        try {
            $deleted = DB::table('wargas')->where('nomor_kk', $nomor_kk)->delete();

            self::logActivity('HAPUS KK', "Menghapus seluruh anggota keluarga pada No. KK {$nomor_kk}");

            return response()->json(['status' => 'success', 'message' => "Kartu Keluarga No. {$nomor_kk} berhasil dihapus beserta {$deleted} anggotanya."]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus Kartu Keluarga.'], 500);
        }
    }

    /**
     * Add a family member to an existing KK.
     */
    public function storeMember(Request $request)
    {
        $this->checkAccess($request->nomor_kk);

        try {
            $request->validate([
                'nomor_kk'        => 'required|numeric',
                'nik'             => 'required|numeric|unique:wargas,nik',
                'nama_lengkap'    => 'required|string|max:255',
                'jenis_kelamin'   => 'required|string|max:20',
                'umur'            => 'nullable|integer|min:0|max:150',
                'agama'           => 'nullable|string|max:20',
                'no_telepon'      => 'nullable|string|max:20',
                'blok_rumah'      => 'required|string|max:255',
                'status_keluarga' => 'required|string',
                'status_domisili' => 'required|string'
            ], [
                'nik.unique' => 'Gagal! NIK tersebut sudah terdaftar di sistem.'
            ]);

            Warga::create($request->all());

            self::logActivity('BUAT ANGGOTA KELUARGA', "Menambahkan anggota keluarga baru {$request->nama_lengkap} (Status: {$request->status_keluarga}) pada KK {$request->nomor_kk}");

            return response()->json(['status' => 'success', 'message' => 'Anggota keluarga baru berhasil ditambahkan!']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['status' => 'error', 'message' => collect($e->errors())->flatten()->first()], 422);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Sistem gagal menyimpan data.'], 500);
        }
    }

    /**
     * Update an individual family member's details.
     */
    public function updateMember(Request $request)
    {
        $this->checkAccess($request->nomor_kk, $request->id);

        try {
            $request->validate([
                'id'              => 'required',
                'nik'             => 'required|numeric|unique:wargas,nik,' . $request->id,
                'nama_lengkap'    => 'required|string|max:255',
                'jenis_kelamin'   => 'required|string|max:20',
                'umur'            => 'nullable|integer|min:0|max:150',
                'agama'           => 'nullable|string|max:20',
                'no_telepon'      => 'nullable|string|max:20',
                'blok_rumah'      => 'required|string',
                'status_keluarga' => 'required|string',
                'status_domisili' => 'required|string'
            ], [
                'nik.unique' => 'Gagal! NIK tersebut sudah terdaftar.'
            ]);

            $warga = Warga::findOrFail($request->id);
            $warga->update($request->all());

            self::logActivity('UPDATE ANGGOTA KELUARGA', "Memperbarui anggota keluarga {$warga->nama_lengkap} (NIK: {$request->nik})");

            return response()->json(['status' => 'success', 'message' => 'Data anggota keluarga berhasil diperbarui!']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['status' => 'error', 'message' => collect($e->errors())->flatten()->first()], 422);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Sistem gagal memperbarui data.'], 500);
        }
    }

    /**
     * Delete an individual family member.
     */
    public function destroyMember($id)
    {
        $this->checkAccess(null, $id);

        try {
            $warga = Warga::findOrFail($id);
            $nama = $warga->nama_lengkap;
            $kk = $warga->nomor_kk;
            $warga->delete();

            self::logActivity('HAPUS ANGGOTA KELUARGA', "Menghapus anggota keluarga {$nama} dari KK {$kk}");

            return response()->json(['status' => 'success', 'message' => 'Anggota keluarga berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus anggota keluarga.'], 500);
        }
    }
}
