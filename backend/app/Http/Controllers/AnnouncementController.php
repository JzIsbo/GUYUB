<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnnouncementController extends Controller
{
    /**
     * Store a newly created announcement.
     */
    public function store(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RT']), 403, 'Akses Ditolak');
        $request->validate([
            'judul'  => 'required|string|max:255',
            'isi'    => 'required|string',
            'status' => 'nullable|string|max:50'
        ]);

        try {
            DB::table('pengumumans')->insert([
                'judul'      => $request->judul,
                'isi'        => $request->isi,
                'status'     => $request->status ?? 'Aktif',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            self::logActivity('BUAT PENGUMUMAN', "Menyiarkan pengumuman baru: {$request->judul}");

            return response()->json([
                'status'  => 'success',
                'message' => 'Pengumuman baru berhasil disiarkan!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyiarkan pengumuman: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified announcement from database.
     */
    public function destroy(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RT']), 403, 'Akses Ditolak');
        $request->validate(['id' => 'required']);

        try {
            $ann = DB::table('pengumumans')->where('id', $request->id)->first();
            if ($ann) {
                self::logActivity('HAPUS PENGUMUMAN', "Menghapus pengumuman: {$ann->judul}");
            }

            DB::table('pengumumans')->where('id', $request->id)->delete();

            return response()->json([
                'status'  => 'success',
                'message' => 'Pengumuman berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menghapus pengumuman.'
            ], 500);
        }
    }
}
