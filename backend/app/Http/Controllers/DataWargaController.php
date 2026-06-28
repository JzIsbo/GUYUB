<?php

namespace App\Http\Controllers;

use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DataWargaController extends Controller
{
    /**
     * Store a newly created warga in database.
     */
    public function store(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RT']), 403, 'Akses Ditolak');
        try {
            $request->validate([
                'nomor_kk'        => 'required|numeric',
                'nik'             => 'required|numeric|unique:wargas,nik',
                'nama_lengkap'    => 'required|string|max:255',
                'no_telepon'      => 'nullable|string|max:20',
                'blok_rumah'      => 'required|string|max:255',
                'status_keluarga' => 'required|string',
                'status_domisili' => 'required|string'
            ], [
                'nik.unique' => 'Gagal! NIK tersebut sudah terdaftar di sistem.'
            ]);

            Warga::create($request->all());

            session()->flash('success', 'Berhasil! Data warga baru telah ditambahkan.');

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Data warga baru berhasil ditambahkan!']);
            }

            return redirect()->back()->with('success', 'Data warga berhasil ditambahkan!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            $pesanError = collect($e->errors())->flatten()->first();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $pesanError], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Sistem gagal menyimpan data.'], 500);
            }
            return redirect()->back()->with('error', 'Sistem gagal menyimpan data.');
        }
    }

    /**
     * Update the specified warga in database.
     */
    public function update(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RT']), 403, 'Akses Ditolak');
        try {
            $request->validate([
                'id'              => 'required',
                'nik'             => 'required|numeric|unique:wargas,nik,' . $request->id,
                'nama_lengkap'    => 'required|string|max:255',
                'no_telepon'      => 'nullable|string|max:20',
                'blok_rumah'      => 'required|string',
                'status_keluarga' => 'required|string',
                'status_domisili' => 'required|string'
            ], [
                'nik.unique' => 'Gagal! NIK tersebut sudah dipakai oleh warga lain.'
            ]);

            $warga = Warga::findOrFail($request->id);
            $warga->update($request->all());

            session()->flash('success', 'Berhasil! Data warga telah diperbarui.');

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Data warga berhasil diperbarui!']);
            }

            return redirect()->back()->with('success', 'Data warga berhasil diperbarui!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            $pesanError = collect($e->errors())->flatten()->first();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $pesanError], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Sistem gagal mengupdate data.'], 500);
            }
            return redirect()->back()->with('error', 'Sistem gagal mengupdate data.');
        }
    }

    /**
     * Remove the specified warga from database (Realtime AJAX and manual cascade delete).
     */
    public function destroy(Request $request, $id = null)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RT']), 403, 'Akses Ditolak');
        $targetId = $id ?? $request->id;

        if (!$targetId) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'ID Warga tidak valid.'], 400);
            }
            return redirect()->back()->with('error', 'ID Warga tidak valid.');
        }

        try {
            DB::transaction(function () use ($targetId) {
                // 1. Hapus warga dari daftar Pengurus RT (jika dia menjabat)
                DB::table('officers')->where('warga_id', $targetId)->delete();

                // 2. Hapus riwayat pembayaran iuran warga ini (agar tidak nyangkut)
                if (Schema::hasTable('contributions_payment')) {
                    DB::table('contributions_payment')->where('warga_id', $targetId)->delete();
                }

                // 3. Setelah semua data yang menyangkut warga ini dibersihkan, baru hapus warganya
                Warga::findOrFail($targetId)->delete();
            });

            session()->flash('success', 'Berhasil! Data warga beserta rekam jejaknya telah dihapus.');

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Data warga beserta rekam jejaknya berhasil dihapus!']);
            }

            return redirect()->back()->with('success', 'Data warga beserta rekam jejaknya berhasil dihapus!');

        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal menghapus: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Gagal menghapus data.');
        }
    }
}
