<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UmkmController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama_usaha' => 'required|string|max:255',
            'pemilik'    => 'required|string|max:255',
            'kategori'   => 'required|string',
            'kontak'     => 'required|string|max:50',
            'lokasi'     => 'nullable|string|max:255',
            'deskripsi'  => 'nullable|string',
            'gambar'     => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048'
        ]);

        try {
            $gambarPath = null;

            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar');
                $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
                $file->storeAs('public/umkm', $filename);
                $gambarPath = '/storage/umkm/' . $filename;
            }

            DB::table('umkms')->insert([
                'nama_usaha' => $request->nama_usaha,
                'pemilik'    => $request->pemilik,
                'kategori'   => $request->kategori,
                'kontak'     => $request->kontak,
                'lokasi'     => $request->lokasi,
                'deskripsi'  => $request->deskripsi,
                'gambar'     => $gambarPath,
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
            // Delete image file if exists
            $umkm = DB::table('umkms')->where('id', $request->id)->first();
            if ($umkm && $umkm->gambar && !str_starts_with($umkm->gambar, 'http')) {
                $storagePath = str_replace('/storage/', 'public/', $umkm->gambar);
                Storage::delete($storagePath);
            }

            DB::table('umkms')->where('id', $request->id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Data UMKM berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus data.'], 500);
        }
    }
}
