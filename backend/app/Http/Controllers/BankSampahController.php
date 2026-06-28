<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BankSampahController extends Controller
{
    public function store(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RT', 'Bendahara']), 403, 'Akses Ditolak');
        $request->validate([
            'nama_warga'   => 'required|string|max:255',
            'jenis_sampah' => 'required|string',
            'berat_kg'     => 'required|numeric|min:0.1',
            'total_rupiah' => 'required|numeric|min:0',
            'tanggal'      => 'required|date'
        ]);

        try {
            DB::table('bank_sampah_deposits')->insert([
                'nama_warga'   => $request->nama_warga,
                'jenis_sampah' => $request->jenis_sampah,
                'berat_kg'     => $request->berat_kg,
                'total_rupiah' => $request->total_rupiah,
                'tanggal'      => $request->tanggal,
                'created_at'   => now(),
                'updated_at'   => now()
            ]);

            return response()->json(['status' => 'success', 'message' => 'Setoran Bank Sampah berhasil dicatat!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RT', 'Bendahara']), 403, 'Akses Ditolak');
        $request->validate(['id' => 'required|integer']);
        try {
            DB::table('bank_sampah_deposits')->where('id', $request->id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Catatan setoran berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus data.'], 500);
        }
    }
}
