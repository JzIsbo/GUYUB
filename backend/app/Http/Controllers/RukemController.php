<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RukemController extends Controller
{
    public function store(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RT', 'Bendahara']), 403, 'Akses Ditolak');
        $request->validate([
            'nama_almarhum'       => 'required|string|max:255',
            'keluarga_duka'       => 'required|string|max:255',
            'tanggal_duka'        => 'required|date',
            'santunan_diserahkan' => 'required|numeric|min:0',
            'status_santunan'     => 'required|string'
        ]);

        try {
            DB::table('rukems')->insert([
                'nama_almarhum'       => $request->nama_almarhum,
                'keluarga_duka'       => $request->keluarga_duka,
                'tanggal_duka'        => $request->tanggal_duka,
                'santunan_diserahkan' => $request->santunan_diserahkan,
                'status_santunan'     => $request->status_santunan,
                'created_at'          => now(),
                'updated_at'          => now()
            ]);

            return response()->json(['status' => 'success', 'message' => 'Data Rukem berhasil ditambahkan!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan data Rukem: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RT', 'Bendahara']), 403, 'Akses Ditolak');
        $request->validate(['id' => 'required|integer']);
        try {
            DB::table('rukems')->where('id', $request->id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Data Rukem berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus data.'], 500);
        }
    }
}
