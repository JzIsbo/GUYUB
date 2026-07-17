<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KerjaBaktiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required|string',
            'waktu_selesai' => 'required|string',
            'lokasi' => 'required|string|max:255',
            'perlengkapan' => 'required|string|max:255',
            'status' => 'required|string|in:Mendatang,Selesai,Dibatalkan',
        ]);

        DB::table('kerja_baktis')->insert([
            'nama_kegiatan' => $request->nama_kegiatan,
            'tanggal' => $request->tanggal,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'lokasi' => $request->lokasi,
            'perlengkapan' => $request->perlengkapan,
            'keterangan' => $request->keterangan,
            'status' => $request->status,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Jadwal Kerja Bakti & Gotong Royong berhasil ditambahkan!'
            ]);
        }

        return back()->with('success_message', 'Jadwal Kerja Bakti & Gotong Royong berhasil ditambahkan!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:kerja_baktis,id',
            'nama_kegiatan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required|string',
            'waktu_selesai' => 'required|string',
            'lokasi' => 'required|string|max:255',
            'perlengkapan' => 'required|string|max:255',
            'status' => 'required|string|in:Mendatang,Selesai,Dibatalkan',
        ]);

        DB::table('kerja_baktis')->where('id', $request->id)->update([
            'nama_kegiatan' => $request->nama_kegiatan,
            'tanggal' => $request->tanggal,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'lokasi' => $request->lokasi,
            'perlengkapan' => $request->perlengkapan,
            'keterangan' => $request->keterangan,
            'status' => $request->status,
            'updated_at' => now(),
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Jadwal Kerja Bakti & Gotong Royong berhasil diperbarui!'
            ]);
        }

        return back()->with('success_message', 'Jadwal Kerja Bakti & Gotong Royong berhasil diperbarui!');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:kerja_baktis,id',
        ]);

        DB::table('kerja_baktis')->where('id', $request->id)->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Jadwal Kerja Bakti & Gotong Royong berhasil dihapus!'
            ]);
        }

        return back()->with('success_message', 'Jadwal Kerja Bakti & Gotong Royong berhasil dihapus!');
    }
}
