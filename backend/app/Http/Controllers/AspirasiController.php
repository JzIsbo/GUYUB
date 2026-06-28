<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AspirasiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'topik'        => 'required|string|max:255',
            'isi_aspirasi' => 'required|string'
        ]);

        try {
            DB::table('aspirasis')->insert([
                'nama_warga'   => $request->nama_warga ?? 'Anonim',
                'topik'        => $request->topik,
                'isi_aspirasi' => $request->isi_aspirasi,
                'status'       => 'Menunggu Response',
                'created_at'   => now(),
                'updated_at'   => now()
            ]);

            return response()->json(['status' => 'success', 'message' => 'Aspirasi / Masukan Anda berhasil dikirim ke Pengurus RT!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal mengirim aspirasi: ' . $e->getMessage()], 500);
        }
    }

    public function respond(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RT']), 403, 'Akses Ditolak');
        $request->validate([
            'id'           => 'required|integer',
            'tanggapan_rt' => 'required|string',
            'status'       => 'required|string'
        ]);

        try {
            DB::table('aspirasis')->where('id', $request->id)->update([
                'tanggapan_rt' => $request->tanggapan_rt,
                'status'       => $request->status,
                'updated_at'   => now()
            ]);

            return response()->json(['status' => 'success', 'message' => 'Tanggapan aspirasi berhasil disimpan!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan tanggapan.'], 500);
        }
    }

    public function destroy(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RT']), 403, 'Akses Ditolak');
        $request->validate(['id' => 'required|integer']);
        try {
            DB::table('aspirasis')->where('id', $request->id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Aspirasi berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus data.'], 500);
        }
    }
}
