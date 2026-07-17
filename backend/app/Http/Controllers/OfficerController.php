<?php

namespace App\Http\Controllers;

use App\Models\Officer;
use Illuminate\Http\Request;

class OfficerController extends Controller
{
    /**
     * Store a newly created officer.
     */
    public function store(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RW', 'Sekretaris RW', 'RT', 'Sekretaris RT']), 403, 'Akses Ditolak');
        try {
            $request->validate([
                'warga_id'      => 'required|integer',
                'jabatan'       => 'required|string|max:255',
                'tanggal_mulai' => 'required|date',
                'status_aktif'  => 'required|in:Aktif,Demisioner'
            ]);

            Officer::create([
                'warga_id'      => $request->warga_id,
                'jabatan'       => $request->jabatan,
                'tanggal_mulai' => $request->tanggal_mulai,
                'status_aktif'  => $request->status_aktif
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Pengurus baru berhasil ditambah!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errorMsg = collect($e->errors())->flatten()->first();
            return response()->json([
                'status'  => 'error',
                'message' => $errorMsg
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified officer.
     */
    public function update(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RW', 'Sekretaris RW', 'RT', 'Sekretaris RT']), 403, 'Akses Ditolak');
        try {
            $request->validate([
                'id'            => 'required|integer',
                'jabatan'       => 'required|string|max:255',
                'tanggal_mulai' => 'required|date',
                'status_aktif'  => 'required|in:Aktif,Demisioner'
            ]);

            $officer = Officer::findOrFail($request->id);
            $officer->update([
                'jabatan'       => $request->jabatan,
                'tanggal_mulai' => $request->tanggal_mulai,
                'status_aktif'  => $request->status_aktif
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Data berhasil diubah!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errorMsg = collect($e->errors())->flatten()->first();
            return response()->json([
                'status'  => 'error',
                'message' => $errorMsg
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal mengubah data pengurus: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified officer.
     */
    public function destroy($id)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RW', 'Sekretaris RW', 'RT', 'Sekretaris RT']), 403, 'Akses Ditolak');
        try {
            $officer = Officer::findOrFail($id);
            $officer->delete();

            return response()->json([
                'status'  => 'success',
                'message' => 'Data berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menghapus data pengurus.'
            ], 500);
        }
    }
}
