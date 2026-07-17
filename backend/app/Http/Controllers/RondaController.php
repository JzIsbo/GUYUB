<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RondaController extends Controller
{
    public function storeRonda(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RT']), 403, 'Akses Ditolak');
        $request->validate([
            'hari'          => 'required|string',
            'petugas_ronda' => 'required|string',
            'koordinator'   => 'required|string',
            'jam_shift'     => 'required|string'
        ]);

        try {
            DB::table('rondas')->insert([
                'hari'          => $request->hari,
                'petugas_ronda' => $request->petugas_ronda,
                'koordinator'   => $request->koordinator,
                'jam_shift'     => $request->jam_shift,
                'created_at'    => now(),
                'updated_at'    => now()
            ]);

            return response()->json(['status' => 'success', 'message' => 'Jadwal Ronda malam berhasil disimpan!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan: ' . $e->getMessage()], 500);
        }
    }

    public function destroyRonda(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RT']), 403, 'Akses Ditolak');
        $request->validate(['id' => 'required|integer']);
        try {
            DB::table('rondas')->where('id', $request->id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Jadwal ronda berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus data.'], 500);
        }
    }

    public function storeIncident(Request $request)
    {
        $request->validate([
            'pelapor'        => 'required|string',
            'jenis_kejadian' => 'required|string',
            'deskripsi'      => 'required|string',
            'waktu_kejadian' => 'nullable|string',
            'foto'           => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ]);

        try {
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('incidents', 'public');
            }

            DB::table('incidents')->insert([
                'pelapor'        => $request->pelapor,
                'jenis_kejadian' => $request->jenis_kejadian,
                'deskripsi'      => $request->deskripsi,
                'waktu_kejadian' => $request->waktu_kejadian ?? date('H:i') . ' WIB',
                'foto'           => $fotoPath,
                'status'         => 'Perlu Penanganan',
                'created_at'     => now(),
                'updated_at'     => now()
            ]);

            \App\Http\Controllers\Controller::logActivity('LAPOR KEJADIAN', 'Melaporkan kejadian ' . $request->jenis_kejadian . ' (Pelapor: ' . $request->pelapor . ')', $fotoPath);

            return response()->json(['status' => 'success', 'message' => 'Laporan Kejadian berhasil dikirim ke Petugas RT!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal mengirim laporan: ' . $e->getMessage()], 500);
        }
    }

    public function destroyIncident(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RT']), 403, 'Akses Ditolak');
        $request->validate(['id' => 'required|integer']);
        try {
            DB::table('incidents')->where('id', $request->id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Laporan kejadian berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus.'], 500);
        }
    }
}
