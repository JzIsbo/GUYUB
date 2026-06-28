<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuratController extends Controller
{
    /**
     * Store a newly created letter request in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_warga'  => 'required|string|max:255',
            'jenis_surat' => 'required|string|max:255',
            'keperluan'   => 'required|string'
        ]);

        try {
            DB::table('surat_online')->insert([
                'nama_warga'  => $request->nama_warga,
                'jenis_surat' => $request->jenis_surat,
                'keperluan'   => $request->keperluan,
                'status'      => 'Menunggu',
                'created_at'  => now(),
                'updated_at'  => now()
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Pengajuan surat berhasil ditambahkan!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan pengajuan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the status of a letter request.
     */
    public function updateStatus(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RT']), 403, 'Akses Ditolak');
        $request->validate([
            'id'     => 'required|integer',
            'status' => 'required|string|max:50'
        ]);

        try {
            DB::table('surat_online')->where('id', $request->id)->update([
                'status'     => $request->status,
                'updated_at' => now()
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Status surat berhasil diubah!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal mengubah status: ' . $e->getMessage()
            ], 500);
        }
    }
}
