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
            DB::table('posyandu_pendaftarans')->where('posyandu_id', $request->id)->delete();
            DB::table('posyandus')->where('id', $request->id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Jadwal Posyandu berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus data.'], 500);
        }
    }

    // Pendaftaran peserta oleh warga
    public function daftarStore(Request $request)
    {
        $request->validate([
            'posyandu_id'    => 'required|integer',
            'nama_peserta'   => 'required|string|max:255',
            'usia'           => 'nullable|string|max:50',
            'kategori'       => 'required|string',
            'nama_pendaftar' => 'required|string|max:255',
            'hubungan'       => 'required|string|max:100',
            'catatan'        => 'nullable|string'
        ]);

        try {
            DB::table('posyandu_pendaftarans')->insert([
                'posyandu_id'    => $request->posyandu_id,
                'nama_peserta'   => $request->nama_peserta,
                'usia'           => $request->usia,
                'kategori'       => $request->kategori,
                'nama_pendaftar' => $request->nama_pendaftar,
                'hubungan'       => $request->hubungan,
                'catatan'        => $request->catatan,
                'status'         => 'Terdaftar',
                'created_at'     => now(),
                'updated_at'     => now()
            ]);

            return response()->json(['status' => 'success', 'message' => 'Pendaftaran berhasil! Peserta telah terdaftar.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal mendaftar: ' . $e->getMessage()], 500);
        }
    }

    // Hapus pendaftaran
    public function daftarDestroy(Request $request)
    {
        $request->validate(['id' => 'required|integer']);
        $pendaftaran = DB::table('posyandu_pendaftarans')->where('id', $request->id)->first();

        if (!$pendaftaran) {
            return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan.'], 404);
        }

        // Warga hanya bisa hapus pendaftaran milik sendiri
        $user = auth()->user();
        if ($user->role === 'Warga' && $pendaftaran->nama_pendaftar !== $user->name) {
            return response()->json(['status' => 'error', 'message' => 'Anda tidak memiliki akses.'], 403);
        }

        try {
            DB::table('posyandu_pendaftarans')->where('id', $request->id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Pendaftaran berhasil dibatalkan.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus.'], 500);
        }
    }

    // Update status kehadiran (Admin/RT only)
    public function daftarStatus(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RT']), 403, 'Akses Ditolak');
        $request->validate([
            'id'     => 'required|integer',
            'status' => 'required|in:Terdaftar,Hadir,Tidak Hadir'
        ]);

        try {
            DB::table('posyandu_pendaftarans')->where('id', $request->id)->update([
                'status'     => $request->status,
                'updated_at' => now()
            ]);
            return response()->json(['status' => 'success', 'message' => 'Status kehadiran diperbarui.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal memperbarui status.'], 500);
        }
    }
}
