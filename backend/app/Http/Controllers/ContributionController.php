<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContributionController extends Controller
{
    /**
     * Store a newly created iuran (contribution) config.
     */
    public function store(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RW', 'Bendahara RW', 'RT', 'Bendahara RT']), 403, 'Akses Ditolak');
        try {
            $request->validate([
                'nama_iuran'        => 'required|string|max:255',
                'periode_penagihan' => 'required|string|max:100',
                'sifat'             => 'required|in:Wajib,Sukarela',
                'nominal'           => 'required|numeric|min:0',
                'deskripsi'         => 'nullable|string'
            ]);

            Contribution::create($request->all());

            self::logActivity('BUAT IURAN', "Membuat konfigurasi master iuran baru: {$request->nama_iuran} (Nominal: Rp " . number_format($request->nominal, 0, ',', '.') . ")");

            return response()->json([
                'status'  => 'success',
                'message' => 'Master iuran baru berhasil ditambahkan!'
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
     * Update the specified iuran config.
     */
    public function update(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RW', 'Bendahara RW', 'RT', 'Bendahara RT']), 403, 'Akses Ditolak');
        try {
            $request->validate([
                'id'                => 'required|integer',
                'nama_iuran'        => 'required|string|max:255',
                'periode_penagihan' => 'required|string|max:100',
                'sifat'             => 'required|in:Wajib,Sukarela',
                'nominal'           => 'required|numeric|min:0',
                'deskripsi'         => 'nullable|string'
            ]);

            $contribution = Contribution::findOrFail($request->id);
            $contribution->update($request->all());

            self::logActivity('UPDATE IURAN', "Memperbarui konfigurasi master iuran: {$request->nama_iuran} menjadi Rp " . number_format($request->nominal, 0, ',', '.'));

            return response()->json([
                'status'  => 'success',
                'message' => 'Master iuran berhasil diperbarui!'
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
                'message' => 'Gagal memperbarui: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified iuran config.
     */
    public function destroy(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RW', 'Bendahara RW', 'RT', 'Bendahara RT']), 403, 'Akses Ditolak');
        try {
            $id = $request->id ?? $request->route('id');
            $contribution = Contribution::findOrFail($id);
            $iuranName = $contribution->nama_iuran;
            $contribution->delete();

            self::logActivity('HAPUS IURAN', "Menghapus master iuran: {$iuranName}");

            return response()->json([
                'status'  => 'success',
                'message' => 'Master iuran berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menghapus data iuran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a manual contribution payment from a citizen.
     */
    public function storePayment(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RW', 'Bendahara RW', 'RT', 'Bendahara RT']), 403, 'Akses Ditolak');
        try {
            $request->validate([
                'warga_id'      => 'required|integer',
                'iuran_id'      => 'required|integer',
                'nominal_bayar' => 'required|numeric|min:1',
                'tanggal_bayar' => 'required|date'
            ]);

            $wargaName = DB::table('wargas')->where('id', $request->warga_id)->value('nama_lengkap') ?? 'Warga';
            $iuranName = DB::table('contributions')->where('id', $request->iuran_id)->value('nama_iuran') ?? 'Iuran';

            if (\Illuminate\Support\Facades\Schema::hasTable('contributions_payment')) {
                DB::table('contributions_payment')->insert([
                    'warga_id'      => $request->warga_id,
                    'iuran_id'      => $request->iuran_id,
                    'nominal_bayar' => $request->nominal_bayar,
                    'tanggal_bayar' => $request->tanggal_bayar,
                    'created_at'    => now(),
                    'updated_at'    => now()
                ]);
            }

            // Record as lunas tagihan
            $periode = date('Y-m', strtotime($request->tanggal_bayar));
            DB::table('tagihans')->insert([
                'warga_id'      => $request->warga_id,
                'nama_warga'    => $wargaName,
                'jenis_tagihan' => $iuranName,
                'periode'       => $periode,
                'jumlah'        => $request->nominal_bayar,
                'metode_bayar'  => 'Manual',
                'status'        => 'lunas',
                'tanggal_lunas' => $request->tanggal_bayar,
                'batas_bayar'   => $request->tanggal_bayar,
                'created_at'    => now(),
                'updated_at'    => now()
            ]);
            
            self::logActivity('BAYAR IURAN', "Mencatat pembayaran iuran {$iuranName} untuk {$wargaName} sebesar Rp " . number_format($request->nominal_bayar, 0, ',', '.'));

            return response()->json([
                'status'  => 'success',
                'message' => 'Pembayaran iuran warga berhasil disimpan!'
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
                'message' => 'Gagal menyimpan pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }
}
