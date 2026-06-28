<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UmkmController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama_usaha' => 'required|string|max:255',
            'pemilik'    => 'required|string|max:255',
            'kategori'   => 'required|string',
            'kontak'     => 'required|string|max:50',
            'deskripsi'  => 'nullable|string'
        ]);

        try {
            DB::table('umkms')->insert([
                'nama_usaha' => $request->nama_usaha,
                'pemilik'    => $request->pemilik,
                'kategori'   => $request->kategori,
                'kontak'     => $request->kontak,
                'deskripsi'  => $request->deskripsi,
                'status'     => 'Aktif',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json(['status' => 'success', 'message' => 'Usaha UMKM Warga berhasil didaftarkan!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal mendaftarkan: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RT']), 403, 'Akses Ditolak');
        $request->validate(['id' => 'required|integer']);
        try {
            DB::table('umkms')->where('id', $request->id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Data UMKM berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus data.'], 500);
        }
    }
}
