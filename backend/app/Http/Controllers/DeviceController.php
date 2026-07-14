<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    /**
     * Store a newly created device in storage.
     */
    public function store(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RT', 'Bendahara']), 403, 'Akses Ditolak');
        try {
            $request->validate([
                'nama_perangkat'  => 'required|string|max:255',
                'jenis_perangkat' => 'required|string|max:255',
                'kondisi'         => 'required|string|max:50',
                'nomor_serial'    => 'nullable|string|max:255',
                'keterangan'      => 'nullable|string'
            ]);

            Device::create($request->all());

            self::logActivity('BUAT ASET', "Menambahkan aset baru: {$request->nama_perangkat} ({$request->jenis_perangkat}, Kondisi: {$request->kondisi})");

            return response()->json([
                'status'  => 'success',
                'message' => 'Data perangkat berhasil disimpan!'
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
     * Update the specified device in storage.
     */
    public function update(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RT', 'Bendahara']), 403, 'Akses Ditolak');
        try {
            $request->validate([
                'id'              => 'required|integer',
                'nama_perangkat'  => 'required|string|max:255',
                'jenis_perangkat' => 'required|string|max:255',
                'kondisi'         => 'required|string|max:50',
                'nomor_serial'    => 'nullable|string|max:255',
                'keterangan'      => 'nullable|string'
            ]);

            $device = Device::findOrFail($request->id);
            $device->update($request->all());

            self::logActivity('UPDATE ASET', "Memperbarui aset: {$device->nama_perangkat} menjadi Kondisi: {$request->kondisi}");

            return response()->json([
                'status'  => 'success',
                'message' => 'Data perangkat berhasil diubah!'
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
                'message' => 'Gagal mengubah data perangkat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified device from storage.
     */
    public function destroy($id)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RT', 'Bendahara']), 403, 'Akses Ditolak');
        try {
            $device = Device::findOrFail($id);
            $devName = $device->nama_perangkat;
            $device->delete();

            self::logActivity('HAPUS ASET', "Menghapus data aset: {$devName}");

            return response()->json([
                'status'  => 'success',
                'message' => 'Data perangkat berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menghapus data perangkat.'
            ], 500);
        }
    }
}
