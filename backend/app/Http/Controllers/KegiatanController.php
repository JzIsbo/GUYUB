<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KegiatanController extends Controller
{
    public function store(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RT']), 403, 'Akses Ditolak');
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tanggal'       => 'required|date',
            'waktu'         => 'required|string',
            'lokasi'        => 'required|string',
            'deskripsi'     => 'nullable|string'
        ]);

        try {
            DB::table('kegiatans')->insert([
                'nama_kegiatan' => $request->nama_kegiatan,
                'tanggal'       => $request->tanggal,
                'waktu'         => $request->waktu,
                'lokasi'        => $request->lokasi,
                'deskripsi'     => $request->deskripsi,
                'created_at'    => now(),
                'updated_at'    => now()
            ]);

            return response()->json(['status' => 'success', 'message' => 'Agenda Kegiatan RT berhasil ditambahkan!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RT']), 403, 'Akses Ditolak');
        $request->validate(['id' => 'required|integer']);
        try {
            DB::table('kegiatans')->where('id', $request->id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Agenda kegiatan berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus data.'], 500);
        }
    }
}
