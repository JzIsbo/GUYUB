<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosyanduController extends Controller
{
    public function store(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RT']), 403, 'Akses Ditolak');
        $request->validate([
            'nama_kegiatan'  => 'required|string|max:255',
            'target_peserta' => 'required|string',
            'tanggal'        => 'required|date',
            'lokasi'         => 'required|string',
            'keterangan'     => 'nullable|string'
        ]);

        try {
            DB::table('posyandus')->insert([
                'nama_kegiatan'  => $request->nama_kegiatan,
                'target_peserta' => $request->target_peserta,
                'tanggal'        => $request->tanggal,
                'lokasi'         => $request->lokasi,
                'keterangan'     => $request->keterangan,
                'created_at'     => now(),
                'updated_at'     => now()
            ]);

            return response()->json(['status' => 'success', 'message' => 'Jadwal Posyandu berhasil ditambahkan!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RT']), 403, 'Akses Ditolak');
        $request->validate(['id' => 'required|integer']);
        try {
            DB::table('posyandus')->where('id', $request->id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Jadwal Posyandu berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus data.'], 500);
        }
    }
}
