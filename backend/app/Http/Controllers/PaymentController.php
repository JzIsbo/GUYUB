<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /* ══════════════════════════════════════════════════
     |  TAGIHAN — CRUD
     ══════════════════════════════════════════════════ */

    /**
     * Buat tagihan baru untuk satu warga.
     */
    public function storeTagihan(Request $request)
    {
        $user = auth()->user();
        if (!in_array($user->role, ['Super Admin', 'RT', 'Bendahara'])) {
            if ($request->nama_warga !== $user->name) {
                abort(403, 'Akses Ditolak');
            }
        }

        $request->validate([
            'nama_warga'    => 'required|string|max:255',
            'jenis_tagihan' => 'required|string|max:255',
            'jumlah'        => 'required|numeric|min:1000',
            'batas_bayar'   => 'nullable|date',
            'periode'       => 'nullable|string|max:50',
        ]);

        try {
            // Cari warga_id dari nama
            $warga = DB::table('wargas')->where('nama_lengkap', $request->nama_warga)->first();

            Tagihan::create([
                'warga_id'      => $warga?->id,
                'nama_warga'    => $request->nama_warga,
                'jenis_tagihan' => $request->jenis_tagihan,
                'periode'       => $request->periode,
                'jumlah'        => $request->jumlah,
                'status'        => 'belum_bayar',
                'batas_bayar'   => $request->batas_bayar ?? now()->endOfMonth()->toDateString(),
            ]);

            self::logActivity('BUAT TAGIHAN', "Membuat tagihan {$request->jenis_tagihan} untuk {$request->nama_warga} sebesar Rp " . number_format($request->jumlah, 0, ',', '.'));

            return response()->json(['status' => 'success', 'message' => 'Tagihan berhasil dibuat!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal membuat tagihan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update info tagihan (admin).
     */
    public function updateTagihan(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RT', 'Bendahara']), 403);

        $request->validate([
            'id'            => 'required|exists:tagihans,id',
            'nama_warga'    => 'required|string',
            'jenis_tagihan' => 'required|string',
            'jumlah'        => 'required|numeric|min:1000',
            'status'        => 'required|in:belum_bayar,menunggu_verifikasi,lunas',
            'batas_bayar'   => 'nullable|date',
            'periode'       => 'nullable|string|max:50',
            'catatan'       => 'nullable|string',
        ]);

        try {
            $tagihan  = Tagihan::findOrFail($request->id);
            $oldStatus = $tagihan->status;

            $updateData = [
                'nama_warga'    => $request->nama_warga,
                'jenis_tagihan' => $request->jenis_tagihan,
                'periode'       => $request->periode,
                'jumlah'        => $request->jumlah,
                'status'        => $request->status,
                'batas_bayar'   => $request->batas_bayar ?? $tagihan->batas_bayar ?? now()->endOfMonth()->toDateString(),
                'catatan'       => $request->catatan,
            ];

            // Kalau admin ubah manual ke lunas
            if ($request->status === 'lunas' && $oldStatus !== 'lunas') {
                $updateData['tanggal_lunas'] = now()->toDateString();
                $updateData['metode_bayar'] = 'manual_admin';
                $this->catatKasRT($tagihan->nama_warga, $tagihan->jenis_tagihan, $request->jumlah, 'Konfirmasi Admin');
            }

            $tagihan->update($updateData);

            self::logActivity('UPDATE TAGIHAN', "Memperbarui tagihan #{$tagihan->id} {$request->jenis_tagihan} milik {$request->nama_warga} → Status: {$request->status}");

            return response()->json(['status' => 'success', 'message' => 'Tagihan berhasil diperbarui!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal memperbarui tagihan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Hapus tagihan.
     */
    public function destroyTagihan(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RT', 'Bendahara']), 403);
        $request->validate(['id' => 'required|exists:tagihans,id']);

        try {
            $tagihan = Tagihan::findOrFail($request->id);
            // Hapus bukti bayar kalau ada
            if ($tagihan->bukti_bayar && file_exists(public_path($tagihan->bukti_bayar))) {
                unlink(public_path($tagihan->bukti_bayar));
            }
            $info = "{$tagihan->jenis_tagihan} milik {$tagihan->nama_warga}";
            $tagihan->delete();

            self::logActivity('HAPUS TAGIHAN', "Menghapus tagihan: $info");

            return response()->json(['status' => 'success', 'message' => 'Tagihan berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus tagihan.'], 500);
        }
    }

    /**
     * Generate tagihan massal untuk semua warga.
     */
    public function generateTagihanMassal(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RT', 'Bendahara']), 403);

        $request->validate([
            'jenis_tagihan' => 'required|string',
            'jumlah'        => 'required|numeric|min:1000',
            'batas_bayar'   => 'nullable|date',
            'periode'       => 'nullable|string|max:50',
        ]);

        try {
            // Ambil dari tabel wargas (kepala keluarga / satu per KK)
            $wargas = DB::table('wargas')
                ->select('id', 'nama_lengkap', 'nomor_kk')
                ->orderBy('nama_lengkap')
                ->get();

            if ($wargas->isEmpty()) {
                return response()->json(['status' => 'error', 'message' => 'Tidak ada data warga di sistem!'], 400);
            }

            $count = 0;
            foreach ($wargas as $warga) {
                // Cegah duplikasi: tagihan dengan jenis & periode yang sama masih belum_bayar
                $exists = Tagihan::where('warga_id', $warga->id)
                    ->where('jenis_tagihan', $request->jenis_tagihan)
                    ->when($request->periode, fn($q) => $q->where('periode', $request->periode))
                    ->whereIn('status', ['belum_bayar', 'menunggu_verifikasi'])
                    ->exists();

                if (!$exists) {
                    Tagihan::create([
                        'warga_id'      => $warga->id,
                        'nama_warga'    => $warga->nama_lengkap,
                        'jenis_tagihan' => $request->jenis_tagihan,
                        'periode'       => $request->periode,
                        'jumlah'        => $request->jumlah,
                        'status'        => 'belum_bayar',
                        'batas_bayar'   => $request->batas_bayar ?? now()->endOfMonth()->toDateString(),
                    ]);
                    $count++;
                }
            }

            $periodeText = $request->periode ? " periode {$request->periode}" : '';
            self::logActivity('GENERATE TAGIHAN MASSAL', "Menerbitkan tagihan {$request->jenis_tagihan}{$periodeText} untuk {$count} warga @ Rp " . number_format($request->jumlah, 0, ',', '.'));

            return response()->json([
                'status'  => 'success',
                'message' => "Berhasil menerbitkan {$count} tagihan baru! (warga yang sudah memiliki tagihan aktif dilewati)"
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal: ' . $e->getMessage()], 500);
        }
    }

    /* ══════════════════════════════════════════════════
     |  PEMBAYARAN — WARGA
     ══════════════════════════════════════════════════ */

    /**
     * Warga bayar iuran langsung (buat tagihan + upload bukti bayar sekaligus).
     */
    public function bayarLangsung(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'jenis_tagihan' => 'required|string|max:255',
            'jumlah'        => 'required|numeric|min:1000',
            'periode'       => 'required|string|max:50',
            'bukti_bayar'   => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:3072',
            'catatan'       => 'nullable|string|max:500',
        ]);

        try {
            $dir = public_path('uploads/bukti_bayar');
            if (!file_exists($dir)) mkdir($dir, 0775, true);

            $warga = DB::table('wargas')->where('nama_lengkap', $user->name)->first();

            $tagihan = Tagihan::create([
                'warga_id'      => $warga?->id,
                'nama_warga'    => $user->name,
                'jenis_tagihan' => $request->jenis_tagihan,
                'periode'       => $request->periode,
                'jumlah'        => $request->jumlah,
                'status'        => 'menunggu_verifikasi',
                'metode_bayar'  => 'manual',
                'batas_bayar'   => now()->addDays(7)->toDateString(),
                'catatan'       => $request->catatan,
            ]);

            $file     = $request->file('bukti_bayar');
            $filename = 'bukti_' . $tagihan->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $filename);

            $tagihan->update(['bukti_bayar' => 'uploads/bukti_bayar/' . $filename]);

            self::logActivity('BAYAR IURAN LANGSUNG', "Warga {$user->name} membayar iuran {$request->jenis_tagihan} ({$request->periode}) sebesar Rp " . number_format($request->jumlah, 0, ',', '.'));

            return response()->json([
                'status'  => 'success',
                'message' => 'Pembayaran iuran berhasil dikirim! Menunggu konfirmasi pengurus RT.'
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal memproses pembayaran: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Warga upload bukti bayar manual.
     */
    public function bayarManual(Request $request)
    {
        $request->validate([
            'tagihan_id'  => 'required|exists:tagihans,id',
            'bukti_bayar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:3072',
            'catatan'     => 'nullable|string|max:500',
        ]);

        try {
            $tagihan = Tagihan::findOrFail($request->tagihan_id);

            if ($tagihan->status === 'lunas') {
                return response()->json(['status' => 'error', 'message' => 'Tagihan ini sudah lunas!'], 400);
            }

            // Pastikan hanya pemilik tagihan yang bisa bayar (atau admin)
            $user = auth()->user();
            if (!in_array($user->role, ['Super Admin', 'RT', 'Bendahara']) && $tagihan->nama_warga !== $user->name) {
                return response()->json(['status' => 'error', 'message' => 'Anda tidak berhak membayar tagihan ini.'], 403);
            }

            // Hapus bukti lama kalau ada
            if ($tagihan->bukti_bayar && file_exists(public_path($tagihan->bukti_bayar))) {
                unlink(public_path($tagihan->bukti_bayar));
            }

            // Simpan file bukti bayar
            $dir = public_path('uploads/bukti_bayar');
            if (!file_exists($dir)) mkdir($dir, 0775, true);

            $file     = $request->file('bukti_bayar');
            $filename = 'bukti_' . $tagihan->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $filename);

            $tagihan->update([
                'status'      => 'menunggu_verifikasi',
                'metode_bayar'=> 'manual',
                'bukti_bayar' => 'uploads/bukti_bayar/' . $filename,
                'catatan'     => $request->catatan,
            ]);

            self::logActivity('UPLOAD BUKTI BAYAR', "Upload bukti pembayaran {$tagihan->jenis_tagihan} sebesar Rp " . number_format($tagihan->jumlah, 0, ',', '.'));

            return response()->json(['status' => 'success', 'message' => 'Bukti pembayaran berhasil diunggah! Menunggu konfirmasi pengurus RT.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal mengunggah bukti: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Admin verifikasi bukti bayar manual — setujui atau tolak.
     */
    public function verifikasiTagihan(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RT', 'Bendahara']), 403);

        $request->validate([
            'id'      => 'required|exists:tagihans,id',
            'aksi'    => 'required|in:setujui,tolak',
            'catatan' => 'nullable|string|max:500',
        ]);

        try {
            $tagihan = Tagihan::findOrFail($request->id);

            if ($request->aksi === 'setujui') {
                $tagihan->update([
                    'status'        => 'lunas',
                    'catatan'       => $request->catatan ?: 'Disetujui oleh ' . auth()->user()->name,
                    'tanggal_lunas' => now()->toDateString(),
                ]);

                // Catat ke kas RT
                $this->catatKasRT($tagihan->nama_warga, $tagihan->jenis_tagihan, $tagihan->jumlah, 'Bukti Bayar Manual Disetujui');

                self::logActivity('SETUJUI BAYAR', "Menyetujui pembayaran {$tagihan->jenis_tagihan} milik {$tagihan->nama_warga} sebesar Rp " . number_format($tagihan->jumlah, 0, ',', '.'));

                return response()->json(['status' => 'success', 'message' => 'Pembayaran disetujui dan dicatat ke Kas RT!']);
            } else {
                // Tolak — kembalikan ke belum_bayar, hapus bukti
                if ($tagihan->bukti_bayar && file_exists(public_path($tagihan->bukti_bayar))) {
                    unlink(public_path($tagihan->bukti_bayar));
                }
                $tagihan->update([
                    'status'      => 'belum_bayar',
                    'bukti_bayar' => null,
                    'metode_bayar'=> null,
                    'catatan'     => $request->catatan ?: 'Bukti pembayaran ditolak. Silakan upload ulang.',
                ]);

                self::logActivity('TOLAK BAYAR', "Menolak bukti pembayaran {$tagihan->jenis_tagihan} milik {$tagihan->nama_warga}");

                return response()->json(['status' => 'success', 'message' => 'Pembayaran ditolak. Warga perlu upload ulang bukti.']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal memproses verifikasi: ' . $e->getMessage()], 500);
        }
    }

    /* ══════════════════════════════════════════════════
     |  PEMBAYARAN ONLINE — MIDTRANS
     ══════════════════════════════════════════════════ */

    /**
     * Generate Midtrans Snap token untuk tagihan.
     */
    public function bayarMidtrans(Request $request)
    {
        $request->validate(['tagihan_id' => 'required|exists:tagihans,id']);

        try {
            $tagihan = Tagihan::findOrFail($request->tagihan_id);

            if ($tagihan->status === 'lunas') {
                return response()->json(['status' => 'error', 'message' => 'Tagihan ini sudah lunas!'], 400);
            }

            $gateway = DB::table('payment_gateways')->first();
            if (!$gateway || empty($gateway->server_key)) {
                return response()->json(['status' => 'error', 'message' => 'Pembayaran online belum dikonfigurasi pengurus RT.'], 400);
            }

            \Midtrans\Config::$serverKey    = $gateway->server_key;
            \Midtrans\Config::$isProduction = ($gateway->environment === 'production');
            \Midtrans\Config::$isSanitized  = true;
            \Midtrans\Config::$is3ds        = true;

            $orderId = 'TG-' . $tagihan->id . '-' . time();

            $params = [
                'transaction_details' => ['order_id' => $orderId, 'gross_amount' => (int) $tagihan->jumlah],
                'customer_details'    => ['first_name' => auth()->user()->name, 'email' => auth()->user()->email],
                'item_details'        => [['id' => 'TG-'.$tagihan->id, 'price' => (int) $tagihan->jumlah, 'quantity' => 1, 'name' => $tagihan->jenis_tagihan]],
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);

            // Catat log transaksi Midtrans
            DB::table('online_payments')->insert([
                'tagihan_id'        => $tagihan->id,
                'order_id'          => $orderId,
                'nama_pembayar'     => auth()->user()->name,
                'metode_pembayaran' => 'Midtrans',
                'nominal'           => $tagihan->jumlah,
                'status'            => 'pending',
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            // Update tagihan ke menunggu verifikasi sementara
            $tagihan->update(['status' => 'menunggu_verifikasi', 'metode_bayar' => 'midtrans']);

            return response()->json(['status' => 'success', 'snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menginisialisasi pembayaran: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Callback Midtrans (webhook).
     */
    public function paymentCallback(Request $request)
    {
        try {
            $gateway   = DB::table('payment_gateways')->first();
            $serverKey = $gateway->server_key ?? '';
            $sigKey    = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

            // Log callback
            DB::table('gateway_logs')->insert([
                'status_code' => ($sigKey == $request->signature_key) ? '200' : '403',
                'method'      => $request->method(),
                'endpoint'    => $request->getRequestUri(),
                'order_id'    => $request->order_id,
                'payload'     => json_encode($request->all(), JSON_PRETTY_PRINT),
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            if ($sigKey !== $request->signature_key) {
                return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 403);
            }

            $txStatus = $request->transaction_status;
            $newStatus = 'pending';
            if (in_array($txStatus, ['capture', 'settlement'])) $newStatus = 'settlement';
            elseif (in_array($txStatus, ['expire', 'cancel', 'deny'])) $newStatus = 'expire';

            // Update online_payments
            DB::table('online_payments')
                ->where('order_id', $request->order_id)
                ->update(['status' => $newStatus, 'metode_pembayaran' => $request->payment_type, 'updated_at' => now()]);

            // Settlement: update tagihan & catat kas
            if ($newStatus === 'settlement') {
                $onlinePay = DB::table('online_payments')->where('order_id', $request->order_id)->first();
                if ($onlinePay && $onlinePay->tagihan_id) {
                    $tagihan = Tagihan::find($onlinePay->tagihan_id);
                    if ($tagihan && $tagihan->status !== 'lunas') {
                        $tagihan->update(['status' => 'lunas', 'tanggal_lunas' => now()->toDateString()]);
                        $this->catatKasRT($tagihan->nama_warga, $tagihan->jenis_tagihan, $tagihan->jumlah, 'Midtrans Online');
                    }
                }
            }

            // Expire: kembalikan tagihan ke belum_bayar
            if ($newStatus === 'expire') {
                $onlinePay = DB::table('online_payments')->where('order_id', $request->order_id)->first();
                if ($onlinePay && $onlinePay->tagihan_id) {
                    $tagihan = Tagihan::find($onlinePay->tagihan_id);
                    if ($tagihan && $tagihan->status === 'menunggu_verifikasi') {
                        $tagihan->update(['status' => 'belum_bayar', 'metode_bayar' => null]);
                    }
                }
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Sinkronisasi status Midtrans untuk transaksi pending.
     */
    public function syncPembayaran()
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'Bendahara']), 403);

        try {
            $gateway = DB::table('payment_gateways')->first();
            if (!$gateway || empty($gateway->server_key)) {
                return response()->json(['status' => 'error', 'message' => 'Server Key Midtrans belum diatur.'], 400);
            }

            $baseUrl  = $gateway->environment === 'production'
                ? 'https://api.midtrans.com/v2'
                : 'https://api.sandbox.midtrans.com/v2';

            $pending  = DB::table('online_payments')->where('status', 'pending')->get();
            $updated  = 0;

            foreach ($pending as $pay) {
                $res = Http::withBasicAuth($gateway->server_key, '')->get("$baseUrl/{$pay->order_id}/status");
                if (!$res->successful()) continue;

                $txStatus = $res->json('transaction_status');
                $newStatus = 'pending';
                if (in_array($txStatus, ['capture', 'settlement'])) $newStatus = 'settlement';
                elseif (in_array($txStatus, ['expire', 'cancel', 'deny'])) $newStatus = 'expire';

                if ($newStatus !== 'pending') {
                    DB::table('online_payments')->where('id', $pay->id)->update([
                        'status'             => $newStatus,
                        'metode_pembayaran'  => $res->json('payment_type'),
                        'updated_at'         => now(),
                    ]);
                    $updated++;

                    if ($newStatus === 'settlement' && $pay->tagihan_id) {
                        $tagihan = Tagihan::find($pay->tagihan_id);
                        if ($tagihan && $tagihan->status !== 'lunas') {
                            $tagihan->update(['status' => 'lunas', 'tanggal_lunas' => now()->toDateString()]);
                            $this->catatKasRT($tagihan->nama_warga, $tagihan->jenis_tagihan, $tagihan->jumlah, 'Midtrans Sync');
                        }
                    }
                }
            }

            self::logActivity('SINKRONISASI MIDTRANS', "$updated transaksi berhasil diperbarui.");

            return response()->json(['status' => 'success', 'message' => "Sinkronisasi selesai! $updated transaksi diperbarui."]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /* ══════════════════════════════════════════════════
     |  PENGATURAN GATEWAY & QRIS
     ══════════════════════════════════════════════════ */

    public function storeGateway(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'Bendahara']), 403);
        $request->validate([
            'environment' => 'required|in:sandbox,production',
            'merchant_id' => 'required|string',
            'client_key'  => 'required|string',
            'server_key'  => 'required|string',
        ]);

        try {
            DB::table('payment_gateways')->updateOrInsert(['id' => 1], [
                'environment' => $request->environment,
                'merchant_id' => $request->merchant_id,
                'client_key'  => $request->client_key,
                'server_key'  => $request->server_key,
                'is_active'   => true,
                'updated_at'  => now(),
            ]);

            self::logActivity('SETTING GATEWAY', "Memperbarui konfigurasi Midtrans ({$request->environment})");

            return response()->json(['status' => 'success', 'message' => 'Konfigurasi Midtrans berhasil disimpan!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateQrisVa(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RT', 'Bendahara']), 403);

        $request->validate([
            'qris_image' => 'nullable|image|max:2048',
        ]);

        try {
            $dataToUpdate = [
                'qris_data'     => $request->qris_data,
                'bank_1_name'   => $request->bank_1_name,
                'bank_1_number' => $request->bank_1_number,
                'bank_1_owner'  => $request->bank_1_owner,
                'bank_2_name'   => $request->bank_2_name,
                'bank_2_number' => $request->bank_2_number,
                'bank_2_owner'  => $request->bank_2_owner,
                'updated_at'    => now(),
            ];

            if ($request->clear_qris_image == '1') {
                $old = DB::table('qris_settings')->where('id', 1)->value('qris_image');
                if ($old && file_exists(public_path($old))) {
                    @unlink(public_path($old));
                }
                $dataToUpdate['qris_image'] = null;
            } elseif ($request->hasFile('qris_image')) {
                $old = DB::table('qris_settings')->where('id', 1)->value('qris_image');
                if ($old && file_exists(public_path($old))) {
                    @unlink(public_path($old));
                }
                $file = $request->file('qris_image');
                $filename = 'qris_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $filename);
                $dataToUpdate['qris_image'] = 'uploads/' . $filename;
            }

            DB::table('qris_settings')->updateOrInsert(['id' => 1], $dataToUpdate);

            self::logActivity('SETTING QRIS/REKENING', 'Memperbarui data QRIS dan nomor rekening RT.');

            return response()->json(['status' => 'success', 'message' => 'Data QRIS & Rekening berhasil diperbarui!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function uploadDirectQris(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RT', 'Bendahara']), 403);

        $request->validate([
            'qris_image' => 'required|image|max:2048',
        ]);

        try {
            $old = DB::table('qris_settings')->where('id', 1)->value('qris_image');
            if ($old && file_exists(public_path($old))) {
                @unlink(public_path($old));
            }

            $file = $request->file('qris_image');
            $filename = 'qris_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $qrisImagePath = 'uploads/' . $filename;

            DB::table('qris_settings')->updateOrInsert(['id' => 1], [
                'qris_image' => $qrisImagePath,
                'updated_at' => now(),
            ]);

            self::logActivity('UPLOAD DIRECT QRIS', 'Mengunggah gambar QRIS kustom secara langsung.');

            return response()->json(['status' => 'success', 'message' => 'Gambar QRIS berhasil diperbarui!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function clearGatewayLogs()
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'Bendahara']), 403);
        try {
            DB::table('gateway_logs')->truncate();
            self::logActivity('BERSIH LOG GATEWAY', 'Membersihkan seluruh log gateway.');
            return response()->json(['status' => 'success', 'message' => 'Log gateway berhasil dibersihkan!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /* ══════════════════════════════════════════════════
     |  PRIVATE HELPER
     ══════════════════════════════════════════════════ */

    /**
     * Catat pemasukan ke tabel transactions (kas RT).
     */
    private function catatKasRT(string $namaWarga, string $jenisTagihan, float $jumlah, string $metode): void
    {
        DB::table('transactions')->insert([
            'kategori'   => 'Iuran Warga',
            'nominal'    => $jumlah,
            'jenis'      => 'pemasukan',
            'tanggal'    => now()->toDateString(),
            'keterangan' => "{$metode}: {$jenisTagihan} — {$namaWarga}",
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
