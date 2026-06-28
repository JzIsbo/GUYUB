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
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'Bendahara']), 403, 'Akses Ditolak');
        try {
            $request->validate([
                'nama_iuran'        => 'required|string|max:255',
                'periode_penagihan' => 'required|string|max:100',
                'sifat'             => 'required|in:Wajib,Sukarela',
                'nominal'           => 'required|numeric|min:0',
                'deskripsi'         => 'nullable|string'
            ]);

            Contribution::create($request->all());

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
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'Bendahara']), 403, 'Akses Ditolak');
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
    public function destroy($id)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'Bendahara']), 403, 'Akses Ditolak');
        try {
            $contribution = Contribution::findOrFail($id);
            $contribution->delete();

            return response()->json([
                'status'  => 'success',
                'message' => 'Master iuran berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menghapus data iuran.'
            ], 500);
        }
    }

    /**
     * Store a manual contribution payment from a citizen.
     */
    public function storePayment(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'Bendahara']), 403, 'Akses Ditolak');
        try {
            $request->validate([
                'warga_id'      => 'required|integer',
                'iuran_id'      => 'required|integer',
                'nominal_bayar' => 'required|numeric|min:1',
                'tanggal_bayar' => 'required|date'
            ]);

            DB::table('contributions_payment')->insert([
                'warga_id'      => $request->warga_id,
                'iuran_id'      => $request->iuran_id,
                'nominal_bayar' => $request->nominal_bayar,
                'tanggal_bayar' => $request->tanggal_bayar,
                'created_at'    => now(),
                'updated_at'    => now()
            ]);

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
