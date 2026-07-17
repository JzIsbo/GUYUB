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
                'keterangan'      => 'nullable|string',
                'jumlah'          => 'required|integer|min:1'
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
                'keterangan'      => 'nullable|string',
                'jumlah'          => 'required|integer|min:1'
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

    /**
     * Store a new asset loan request.
     */
    public function storeLoan(Request $request)
    {
        try {
            $request->validate([
                'nama_aset'      => 'required|string|max:255',
                'jumlah_unit'    => 'required|integer|min:1',
                'tanggal_pinjam' => 'required|date',
                'tanggal_kembali'=> 'required|date|after_or_equal:tanggal_pinjam',
                'keperluan'      => 'required|string',
            ]);

            $user = auth()->user();
            $namaWarga = $request->input('nama_warga', $user ? $user->name : 'Warga');
            if (in_array($user->role ?? '', ['Super Admin', 'RW', 'Sekretaris RW', 'Bendahara RW', 'RT', 'Sekretaris RT', 'Bendahara RT']) && $request->filled('warga_id')) {
                $targetUser = \Illuminate\Support\Facades\DB::table('users')->where('id', $request->warga_id)->first();
                if ($targetUser) $namaWarga = $targetUser->name;
            }

            \Illuminate\Support\Facades\DB::table('asset_loans')->insert([
                'user_id'         => $user ? $user->id : null,
                'nama_warga'      => $namaWarga,
                'nama_aset'       => $request->nama_aset,
                'jumlah_unit'     => $request->jumlah_unit,
                'tanggal_pinjam'  => $request->tanggal_pinjam,
                'tanggal_kembali' => $request->tanggal_kembali,
                'keperluan'       => $request->keperluan,
                'status'          => 'Menunggu Approval',
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            self::logActivity('PENGAJUAN PINJAM ASET', "Pengajuan pinjam {$request->nama_aset} ({$request->jumlah_unit} unit) oleh {$namaWarga}");

            return response()->json([
                'status'  => 'success',
                'message' => 'Permohonan peminjaman aset berhasil diajukan! Menunggu persetujuan pengurus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal mengajukan peminjaman: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve asset loan request.
     */
    public function approveLoan(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RW', 'Sekretaris RW', 'Bendahara RW', 'RT', 'Sekretaris RT', 'Bendahara RT']), 403, 'Akses Ditolak');
        try {
            $id = $request->input('id');
            $catatan = $request->input('catatan_admin', 'Disetujui pengurus.');

            \Illuminate\Support\Facades\DB::table('asset_loans')->where('id', $id)->update([
                'status'        => 'Disetujui',
                'catatan_admin' => $catatan,
                'updated_at'    => now(),
            ]);

            self::logActivity('APPROVAL PINJAM ASET', "Menyetujui peminjaman aset ID {$id}");

            return response()->json([
                'status'  => 'success',
                'message' => 'Permohonan peminjaman aset berhasil disetujui!'
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Reject asset loan request.
     */
    public function rejectLoan(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RW', 'Sekretaris RW', 'Bendahara RW', 'RT', 'Sekretaris RT', 'Bendahara RT']), 403, 'Akses Ditolak');
        try {
            $id = $request->input('id');
            $catatan = $request->input('catatan_admin', 'Ditolak pengurus.');

            \Illuminate\Support\Facades\DB::table('asset_loans')->where('id', $id)->update([
                'status'        => 'Ditolak',
                'catatan_admin' => $catatan,
                'updated_at'    => now(),
            ]);

            self::logActivity('PENOLAKAN PINJAM ASET', "Menolak peminjaman aset ID {$id}");

            return response()->json([
                'status'  => 'success',
                'message' => 'Permohonan peminjaman aset telah ditolak.'
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Warga submits asset return declaration.
     */
    public function submitReturn(Request $request)
    {
        try {
            $id = $request->input('id');
            $loan = \Illuminate\Support\Facades\DB::table('asset_loans')->where('id', $id)->first();
            if (!$loan) {
                return response()->json(['status' => 'error', 'message' => 'Data peminjaman tidak ditemukan.'], 404);
            }

            \Illuminate\Support\Facades\DB::table('asset_loans')->where('id', $id)->update([
                'status'                       => 'Proses Pengembalian',
                'tanggal_dikembalikan_aktual' => $request->input('tanggal_dikembalikan_aktual', date('Y-m-d')),
                'catatan_pengembalian'        => $request->input('catatan_pengembalian', 'Pengajuan pengembalian aset oleh warga'),
                'updated_at'                   => now(),
            ]);

            self::logActivity('PENGAJUAN KEMBALI ASET', "Warga mengajukan pengembalian aset: {$loan->nama_aset} (ID {$id})");

            return response()->json([
                'status'  => 'success',
                'message' => 'Pengajuan pengembalian aset berhasil dikirim! Menunggu verifikasi fisik dari pengurus.'
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Mark asset loan as returned with inspection condition & confirmation.
     */
    public function returnLoan(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RW', 'Sekretaris RW', 'Bendahara RW', 'RT', 'Sekretaris RT', 'Bendahara RT']), 403, 'Akses Ditolak');
        try {
            $id = $request->input('id');
            $kondisi = $request->input('kondisi_pengembalian', 'Baik (Sesuai)');
            $tglAktual = $request->input('tanggal_dikembalikan_aktual', date('Y-m-d'));
            $catatan = $request->input('catatan_pengembalian', 'Verifikasi fisik pengurus selesai.');
            $denda = floatval($request->input('denda_kerusakan', 0));

            \Illuminate\Support\Facades\DB::table('asset_loans')->where('id', $id)->update([
                'status'                       => 'Sudah Dikembalikan',
                'kondisi_pengembalian'        => $kondisi,
                'tanggal_dikembalikan_aktual' => $tglAktual,
                'catatan_pengembalian'        => $catatan,
                'denda_kerusakan'             => $denda,
                'updated_at'                   => now(),
            ]);

            self::logActivity('VERIFIKASI PENGEMBALIAN ASET', "Pengurus memverifikasi pengembalian aset ID {$id} dengan kondisi: {$kondisi}");

            return response()->json([
                'status'  => 'success',
                'message' => "Pengembalian aset berhasil diverifikasi! Kondisi: {$kondisi}."
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete asset loan record.
     */
    public function destroyLoan(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RW', 'Sekretaris RW', 'Bendahara RW', 'RT', 'Sekretaris RT', 'Bendahara RT']), 403, 'Akses Ditolak');
        try {
            $id = $request->input('id');
            \Illuminate\Support\Facades\DB::table('asset_loans')->where('id', $id)->delete();

            self::logActivity('HAPUS PINJAM ASET', "Menghapus catatan peminjaman aset ID {$id}");

            return response()->json([
                'status'  => 'success',
                'message' => 'Catatan peminjaman aset berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
