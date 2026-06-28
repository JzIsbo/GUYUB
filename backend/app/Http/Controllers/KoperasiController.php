<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KoperasiController extends Controller
{
    public function store(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RT', 'Bendahara']), 403, 'Akses Ditolak');
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga'       => 'required|numeric|min:0',
            'stok'        => 'required|integer|min:0',
            'kategori'    => 'required|string',
            'penjual'     => 'nullable|string'
        ]);

        try {
            DB::table('koperasi_items')->insert([
                'nama_produk' => $request->nama_produk,
                'harga'       => $request->harga,
                'stok'        => $request->stok,
                'kategori'    => $request->kategori,
                'penjual'     => $request->penjual ?? 'Koperasi RT',
                'created_at'  => now(),
                'updated_at'  => now()
            ]);

            return response()->json(['status' => 'success', 'message' => 'Produk Koperasi berhasil ditambahkan!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RT', 'Bendahara']), 403, 'Akses Ditolak');
        $request->validate(['id' => 'required|integer']);
        try {
            DB::table('koperasi_items')->where('id', $request->id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Produk Koperasi berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus data.'], 500);
        }
    }
}
