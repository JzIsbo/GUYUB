<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    /**
     * Store manual gateway settings.
     */
    public function storeGateway(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'Bendahara']), 403, 'Akses Ditolak');
        $request->validate([
            'environment' => 'required|in:sandbox,production',
            'merchant_id' => 'required|string',
            'client_key'  => 'required|string',
            'server_key'  => 'required|string',
        ]);

        try {
            DB::table('payment_gateways')->where('id', 1)->update([
                'environment' => $request->environment,
                'merchant_id' => $request->merchant_id,
                'client_key'  => $request->client_key,
                'server_key'  => $request->server_key,
                'is_active'   => true,
                'updated_at'  => now()
            ]);

            self::logActivity('SETTING GATEWAY', "Memperbarui konfigurasi payment gateway Midtrans ({$request->environment})");

            return response()->json([
                'status'  => 'success',
                'message' => 'Konfigurasi Payment Gateway berhasil disimpan ke sistem!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan gateway: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear all payment logs.
     */
    public function clearGatewayLogs()
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'Bendahara']), 403, 'Akses Ditolak');
        try {
            DB::table('gateway_logs')->truncate();

            self::logActivity('BERSIH LOG GATEWAY', "Membersihkan seluruh catatan log gateway.");

            return response()->json([
                'status'  => 'success',
                'message' => 'Semua catatan log riwayat gateway berhasil dibersihkan!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal membersihkan log.'
            ], 500);
        }
    }

    /**
     * Midtrans Callback.
     */
    public function paymentCallback(Request $request)
    {
        try {
            $gateway = DB::table('payment_gateways')->first();
            $serverKey = $gateway->server_key ?? '';

            $signatureKey = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
            $isValid = ($signatureKey == $request->signature_key);

            // Record transaction log
            DB::table('gateway_logs')->insert([
                'status_code' => $isValid ? '200' : '403',
                'method'      => $request->method(),
                'endpoint'    => $request->getRequestUri(),
                'order_id'    => $request->order_id,
                'payload'     => json_encode($request->all(), JSON_PRETTY_PRINT),
                'created_at'  => now(),
                'updated_at'  => now()
            ]);

            if ($isValid) {
                // If payment is settlement, update online payment status
                $status = 'pending';
                $transactionStatus = $request->transaction_status;
                if (in_array($transactionStatus, ['capture', 'settlement'])) {
                    $status = 'settlement';
                } elseif (in_array($transactionStatus, ['expire', 'cancel', 'deny'])) {
                    $status = 'expire';
                }

                DB::table('online_payments')
                    ->where('order_id', $request->order_id)
                    ->update([
                        'status'             => $status,
                        'metode_pembayaran' => $request->payment_type,
                        'updated_at'         => now()
                    ]);

                return response()->json(['status' => 'success']);
            }

            return response()->json(['status' => 'error', 'message' => 'Invalid signature key'], 403);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Sync pending transactions with Midtrans status.
     */
    public function syncPembayaran()
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'Bendahara']), 403, 'Akses Ditolak');
        try {
            $gateway = DB::table('payment_gateways')->first();
            if (!$gateway || empty($gateway->server_key)) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Gagal! Server Key Midtrans belum diatur di menu Pembayaran Online.'
                ], 400);
            }

            $serverKey = $gateway->server_key;
            $isProduction = $gateway->environment === 'production';
            $baseUrl = $isProduction ? 'https://api.midtrans.com/v2' : 'https://api.sandbox.midtrans.com/v2';

            $pendingPayments = DB::table('online_payments')->where('status', 'pending')->get();
            $updatedCount = 0;

            foreach ($pendingPayments as $payment) {
                $response = Http::withBasicAuth($serverKey, '')
                    ->get($baseUrl . '/' . $payment->order_id . '/status');

                if ($response->successful()) {
                    $midtransStatus = $response->json('transaction_status');
                    $newStatus = 'pending';

                    if (in_array($midtransStatus, ['capture', 'settlement'])) {
                        $newStatus = 'settlement';
                    } elseif (in_array($midtransStatus, ['expire', 'cancel', 'deny'])) {
                        $newStatus = 'expire';
                    }

                    if ($newStatus !== 'pending') {
                        DB::table('online_payments')->where('id', $payment->id)->update([
                            'status'             => $newStatus,
                            'metode_pembayaran' => $response->json('payment_type'),
                            'updated_at'         => now()
                        ]);
                        $updatedCount++;
                    }
                }
            }

            self::logActivity('SINKRONISASI MIDTRANS', "Melakukan sinkronisasi status transaksi online. {$updatedCount} transaksi berhasil diperbarui.");

            return response()->json([
                'status'  => 'success',
                'message' => "Sinkronisasi selesai! $updatedCount transaksi berhasil diupdate dari Midtrans."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal melakukan sinkronisasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update manual payment options (QRIS & Bank Transfer detail settings).
     */
    public function updateQrisVa(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RT', 'Bendahara']), 403, 'Akses Ditolak');
        try {
            DB::table('qris_settings')->where('id', 1)->update([
                'qris_data'     => $request->qris_data,
                'bank_1_name'   => $request->bank_1_name,
                'bank_1_number' => $request->bank_1_number,
                'bank_1_owner'  => $request->bank_1_owner,
                'bank_2_name'   => $request->bank_2_name,
                'bank_2_number' => $request->bank_2_number,
                'bank_2_owner'  => $request->bank_2_owner,
                'updated_at'    => now()
            ]);

            self::logActivity('SETTING REKENING/QRIS', "Memperbarui data QRIS dan nomor rekening tujuan iuran RT.");

            return response()->json([
                'status'  => 'success',
                'message' => 'Data QRIS & Rekening berhasil diperbarui!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal memperbarui QRIS/VA: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create citizen bill.
     */
    public function storeTagihan(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RT', 'Bendahara']), 403, 'Akses Ditolak');
        $request->validate([
            'nama_warga'    => 'required|string',
            'jenis_tagihan' => 'required|string',
            'jumlah'        => 'required|numeric|min:1',
            'batas_bayar'   => 'required|date',
        ]);

        try {
            Tagihan::create([
                'nama_warga'    => $request->nama_warga,
                'jenis_tagihan' => $request->jenis_tagihan,
                'jumlah'        => $request->jumlah,
                'status'        => 'menunggu',
                'batas_bayar'   => $request->batas_bayar
            ]);

            self::logActivity('BUAT TAGIHAN', "Membuat tagihan {$request->jenis_tagihan} baru untuk {$request->nama_warga} sebesar Rp " . number_format($request->jumlah, 0, ',', '.'));

            return response()->json([
                'status'  => 'success',
                'message' => 'Tagihan berhasil dibuat!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal membuat tagihan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update bill info / change status.
     */
    public function updateTagihan(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RT', 'Bendahara']), 403, 'Akses Ditolak');
        $request->validate([
            'id'            => 'required',
            'nama_warga'    => 'required|string',
            'jenis_tagihan' => 'required|string',
            'jumlah'        => 'required|numeric|min:1',
            'status'        => 'required|in:menunggu,berhasil'
        ]);

        try {
            $tagihan = Tagihan::findOrFail($request->id);
            $tagihan->update([
                'nama_warga'    => $request->nama_warga,
                'jenis_tagihan' => $request->jenis_tagihan,
                'jumlah'        => $request->jumlah,
                'status'        => $request->status
            ]);

            self::logActivity('UPDATE TAGIHAN', "Memperbarui detail tagihan {$request->jenis_tagihan} untuk {$request->nama_warga} menjadi Rp " . number_format($request->jumlah, 0, ',', '.') . " (Status: {$request->status})");

            return response()->json([
                'status'  => 'success',
                'message' => 'Detail tagihan berhasil diperbarui!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal memperbarui tagihan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete bill.
     */
    public function destroyTagihan(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RT', 'Bendahara']), 403, 'Akses Ditolak');
        $request->validate(['id' => 'required']);

        try {
            $tagihan = Tagihan::findOrFail($request->id);
            $tagName = $tagihan->nama_warga;
            $tagType = $tagihan->jenis_tagihan;
            $tagihan->delete();

            self::logActivity('HAPUS TAGIHAN', "Menghapus data tagihan {$tagType} milik {$tagName}");

            return response()->json([
                'status'  => 'success',
                'message' => 'Data tagihan berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menghapus tagihan.'
            ], 500);
        }
    }
}
