<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KoperasiController extends Controller
{
    private function checkOfficerAccess()
    {
        $role = Auth::user()->role;
        return in_array($role, ['Super Admin', 'RW', 'Sekretaris RW', 'Bendahara RW', 'RT', 'Sekretaris RT', 'Bendahara RT']);
    }

    // ==========================================
    // 1. KEBUTUHAN POKOK / SEMBAKO (CRUD & ORDER)
    // ==========================================
    public function store(Request $request)
    {
        abort_if(!$this->checkOfficerAccess(), 403, 'Akses Ditolak');
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga'       => 'required|numeric|min:0',
            'stok'        => 'required|integer|min:0',
            'satuan'      => 'nullable|string',
            'kategori'    => 'required|string',
            'deskripsi'   => 'nullable|string',
            'penjual'     => 'nullable|string',
            'foto'        => 'nullable|image|max:5120'
        ]);

        try {
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/koperasi'), $filename);
                $fotoPath = '/uploads/koperasi/' . $filename;
            }

            DB::table('koperasi_items')->insert([
                'nama_produk' => $request->nama_produk,
                'harga'       => $request->harga,
                'stok'        => $request->stok,
                'satuan'      => $request->satuan ?? 'pcs',
                'kategori'    => $request->kategori,
                'deskripsi'   => $request->deskripsi,
                'penjual'     => $request->penjual ?? 'Koperasi RT/RW',
                'foto'        => $fotoPath,
                'status'      => $request->stok > 0 ? 'Tersedia' : 'Habis',
                'created_at'  => now(),
                'updated_at'  => now()
            ]);

            return response()->json(['status' => 'success', 'message' => 'Produk Kebutuhan Pokok berhasil ditambahkan!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request)
    {
        abort_if(!$this->checkOfficerAccess(), 403, 'Akses Ditolak');
        $request->validate([
            'id'          => 'required|integer',
            'nama_produk' => 'required|string|max:255',
            'harga'       => 'required|numeric|min:0',
            'stok'        => 'required|integer|min:0',
            'satuan'      => 'nullable|string',
            'kategori'    => 'required|string',
            'deskripsi'   => 'nullable|string',
            'foto'        => 'nullable|image|max:5120'
        ]);

        try {
            $updateData = [
                'nama_produk' => $request->nama_produk,
                'harga'       => $request->harga,
                'stok'        => $request->stok,
                'satuan'      => $request->satuan ?? 'pcs',
                'kategori'    => $request->kategori,
                'deskripsi'   => $request->deskripsi,
                'status'      => $request->stok > 0 ? 'Tersedia' : 'Habis',
                'updated_at'  => now()
            ];

            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/koperasi'), $filename);
                $updateData['foto'] = '/uploads/koperasi/' . $filename;
            }

            DB::table('koperasi_items')->where('id', $request->id)->update($updateData);

            return response()->json(['status' => 'success', 'message' => 'Data produk berhasil diperbarui!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal memperbarui: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request)
    {
        abort_if(!$this->checkOfficerAccess(), 403, 'Akses Ditolak');
        $request->validate(['id' => 'required|integer']);
        try {
            DB::table('koperasi_items')->where('id', $request->id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Produk Koperasi berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus data.'], 500);
        }
    }

    public function order(Request $request)
    {
        $request->validate([
            'item_id' => 'required|integer',
            'jumlah'  => 'required|integer|min:1'
        ]);

        $user = Auth::user();
        $item = DB::table('koperasi_items')->where('id', $request->item_id)->first();
        if (!$item) {
            return response()->json(['status' => 'error', 'message' => 'Produk tidak ditemukan.'], 404);
        }
        if ($item->stok < $request->jumlah) {
            return response()->json(['status' => 'error', 'message' => 'Stok produk tidak mencukupi.'], 400);
        }

        try {
            $totalHarga = $item->harga * $request->jumlah;
            DB::table('koperasi_orders')->insert([
                'user_id'           => $user->id,
                'nama_warga'        => $user->name,
                'item_id'           => $item->id,
                'nama_produk'       => $item->nama_produk,
                'jumlah'            => $request->jumlah,
                'total_harga'       => $totalHarga,
                'metode_pembayaran' => $request->metode_pembayaran ?? 'Tunai',
                'status'            => 'Menunggu',
                'created_at'        => now(),
                'updated_at'        => now()
            ]);

            // Potong stok
            DB::table('koperasi_items')->where('id', $item->id)->decrement('stok', $request->jumlah);

            return response()->json(['status' => 'success', 'message' => 'Pesanan kebutuhan pokok berhasil dibuat!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal membuat pesanan: ' . $e->getMessage()], 500);
        }
    }

    public function updateOrderStatus(Request $request)
    {
        abort_if(!$this->checkOfficerAccess(), 403, 'Akses Ditolak');
        $request->validate([
            'id'     => 'required|integer',
            'status' => 'required|string|in:Menunggu,Diproses,Selesai,Dibatalkan'
        ]);

        try {
            DB::table('koperasi_orders')->where('id', $request->id)->update([
                'status'     => $request->status,
                'updated_at' => now()
            ]);
            return response()->json(['status' => 'success', 'message' => 'Status pesanan berhasil diperbarui!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal mengubah status pesanan.'], 500);
        }
    }

    // ==========================================
    // 2. SIMPANAN WARGA (Pencatatan & Pengajuan)
    // ==========================================
    public function storeSimpanan(Request $request)
    {
        $request->validate([
            'jenis_simpanan'    => 'required|string|in:Simpanan Pokok,Simpanan Wajib,Simpanan Sukarela',
            'jumlah'            => 'required|numeric|min:10000',
            'metode_pembayaran' => 'required|string',
            'warga_id'          => 'nullable|integer'
        ]);

        $user = Auth::user();
        $isOfficer = $this->checkOfficerAccess();

        // Jika diproses pengurus untuk warga tertentu
        if ($isOfficer && $request->filled('warga_id')) {
            $targetWarga = DB::table('wargas')->where('id', $request->warga_id)->first();
            $namaWarga = $targetWarga ? $targetWarga->nama_lengkap : $user->name;
            $status = 'Disetujui';
        } else {
            $namaWarga = $user->name;
            $status = $isOfficer ? 'Disetujui' : 'Menunggu Verifikasi';
        }

        try {
            DB::table('koperasi_simpanans')->insert([
                'user_id'           => $user->id,
                'nama_warga'        => $namaWarga,
                'jenis_simpanan'    => $request->jenis_simpanan,
                'jumlah'            => $request->jumlah,
                'metode_pembayaran' => $request->metode_pembayaran,
                'keterangan'        => $request->keterangan ?? 'Setoran Simpanan Koperasi',
                'status'            => $status,
                'created_at'        => now(),
                'updated_at'        => now()
            ]);

            return response()->json(['status' => 'success', 'message' => 'Setoran simpanan berhasil dicatat!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal mencatat simpanan: ' . $e->getMessage()], 500);
        }
    }

    public function approveSimpanan(Request $request)
    {
        abort_if(!$this->checkOfficerAccess(), 403, 'Akses Ditolak');
        $request->validate(['id' => 'required|integer', 'status' => 'required|string|in:Disetujui,Ditolak']);

        try {
            DB::table('koperasi_simpanans')->where('id', $request->id)->update([
                'status'     => $request->status,
                'updated_at' => now()
            ]);
            return response()->json(['status' => 'success', 'message' => 'Verifikasi simpanan berhasil diperbarui!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal mengubah status.'], 500);
        }
    }

    public function destroySimpanan(Request $request)
    {
        abort_if(!$this->checkOfficerAccess(), 403, 'Akses Ditolak');
        $request->validate(['id' => 'required|integer']);
        try {
            DB::table('koperasi_simpanans')->where('id', $request->id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Data simpanan berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus data simpanan.'], 500);
        }
    }

    // ==========================================
    // 3. SIMPAN PINJAM (PINJAMAN WARGA)
    // ==========================================
    public function storePinjaman(Request $request)
    {
        $request->validate([
            'jumlah_pinjaman' => 'required|numeric|min:100000',
            'tenor_bulan'     => 'required|integer|min:1|max:36',
            'keperluan'       => 'required|string'
        ]);

        $user = Auth::user();
        $angsuran = ceil($request->jumlah_pinjaman / $request->tenor_bulan);

        try {
            DB::table('koperasi_pinjamans')->insert([
                'user_id'            => $user->id,
                'nama_warga'         => $user->name,
                'jumlah_pinjaman'    => $request->jumlah_pinjaman,
                'tenor_bulan'        => $request->tenor_bulan,
                'angsuran_per_bulan' => $angsuran,
                'keperluan'          => $request->keperluan,
                'status'             => 'Menunggu',
                'created_at'         => now(),
                'updated_at'         => now()
            ]);

            return response()->json(['status' => 'success', 'message' => 'Permohonan pinjaman berhasil diajukan!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal mengajukan pinjaman: ' . $e->getMessage()], 500);
        }
    }

    public function approvePinjaman(Request $request)
    {
        abort_if(!$this->checkOfficerAccess(), 403, 'Akses Ditolak');
        $request->validate([
            'id'            => 'required|integer',
            'catatan_admin' => 'nullable|string'
        ]);

        try {
            DB::table('koperasi_pinjamans')->where('id', $request->id)->update([
                'status'        => 'Disetujui',
                'catatan_admin' => $request->catatan_admin ?? 'Disetujui Pengurus Koperasi',
                'updated_at'    => now()
            ]);
            return response()->json(['status' => 'success', 'message' => 'Permohonan pinjaman disetujui!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal memproses pinjaman.'], 500);
        }
    }

    public function rejectPinjaman(Request $request)
    {
        abort_if(!$this->checkOfficerAccess(), 403, 'Akses Ditolak');
        $request->validate([
            'id'            => 'required|integer',
            'catatan_admin' => 'nullable|string'
        ]);

        try {
            DB::table('koperasi_pinjamans')->where('id', $request->id)->update([
                'status'        => 'Ditolak',
                'catatan_admin' => $request->catatan_admin ?? 'Ditolak oleh pengurus',
                'updated_at'    => now()
            ]);
            return response()->json(['status' => 'success', 'message' => 'Permohonan pinjaman ditolak.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal mengubah status pinjaman.'], 500);
        }
    }

    public function payPinjaman(Request $request)
    {
        $request->validate(['id' => 'required|integer']);
        try {
            DB::table('koperasi_pinjamans')->where('id', $request->id)->update([
                'status'     => 'Lunas',
                'updated_at' => now()
            ]);
            return response()->json(['status' => 'success', 'message' => 'Pinjaman berhasil ditandai LUNAS!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal memperbarui status pelunasan.'], 500);
        }
    }

    public function destroyPinjaman(Request $request)
    {
        abort_if(!$this->checkOfficerAccess(), 403, 'Akses Ditolak');
        $request->validate(['id' => 'required|integer']);
        try {
            DB::table('koperasi_pinjamans')->where('id', $request->id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Data pinjaman berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus pinjaman.'], 500);
        }
    }

    // ==========================================
    // 4. AKSES PERMODALAN UMKM
    // ==========================================
    public function storePermodalan(Request $request)
    {
        $request->validate([
            'nama_usaha'        => 'required|string|max:255',
            'kategori_umkm'     => 'required|string',
            'nominal_pengajuan' => 'required|numeric|min:500000',
            'deskripsi_usaha'   => 'required|string'
        ]);

        $user = Auth::user();

        try {
            DB::table('koperasi_permodalans')->insert([
                'user_id'           => $user->id,
                'nama_warga'        => $user->name,
                'nama_usaha'        => $request->nama_usaha,
                'kategori_umkm'     => $request->kategori_umkm,
                'nominal_pengajuan' => $request->nominal_pengajuan,
                'deskripsi_usaha'   => $request->deskripsi_usaha,
                'status'            => 'Menunggu',
                'created_at'        => now(),
                'updated_at'        => now()
            ]);

            return response()->json(['status' => 'success', 'message' => 'Pengajuan permodalans UMKM berhasil diajukan!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal mengajukan permodalans: ' . $e->getMessage()], 500);
        }
    }

    public function approvePermodalan(Request $request)
    {
        abort_if(!$this->checkOfficerAccess(), 403, 'Akses Ditolak');
        $request->validate([
            'id'            => 'required|integer',
            'catatan_admin' => 'nullable|string'
        ]);

        try {
            DB::table('koperasi_permodalans')->where('id', $request->id)->update([
                'status'        => 'Dicairkan',
                'catatan_admin' => $request->catatan_admin ?? 'Disetujui & Permodalan Dicairkan',
                'updated_at'    => now()
            ]);
            return response()->json(['status' => 'success', 'message' => 'Permodalan UMKM berhasil disetujui & dicairkan!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal memproses permodalan.'], 500);
        }
    }

    public function rejectPermodalan(Request $request)
    {
        abort_if(!$this->checkOfficerAccess(), 403, 'Akses Ditolak');
        $request->validate([
            'id'            => 'required|integer',
            'catatan_admin' => 'nullable|string'
        ]);

        try {
            DB::table('koperasi_permodalans')->where('id', $request->id)->update([
                'status'        => 'Ditolak',
                'catatan_admin' => $request->catatan_admin ?? 'Ditolak oleh pengurus',
                'updated_at'    => now()
            ]);
            return response()->json(['status' => 'success', 'message' => 'Pengajuan permodalans ditolak.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal mengubah status.'], 500);
        }
    }

    public function destroyPermodalan(Request $request)
    {
        abort_if(!$this->checkOfficerAccess(), 403, 'Akses Ditolak');
        $request->validate(['id' => 'required|integer']);
        try {
            DB::table('koperasi_permodalans')->where('id', $request->id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Data permodalan berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus data permodalan.'], 500);
        }
    }

    private function checkFinanceManagerAccess()
    {
        $role = Auth::user()->role;
        return in_array($role, ['Super Admin', 'Bendahara RW', 'Bendahara RT']);
    }

    // ==========================================
    // 6. LAPORAN KEUANGAN KOPERASI (CRUD)
    // ==========================================
    public function storeFinance(Request $request)
    {
        abort_if(!$this->checkFinanceManagerAccess(), 403, 'Akses Ditolak');
        $request->validate([
            'tanggal'         => 'required|date',
            'tipe'            => 'required|in:pemasukan,pengeluaran',
            'kategori'        => 'required|string',
            'nominal'         => 'required|numeric|min:0',
            'keterangan'      => 'nullable|string',
            'bukti_transaksi' => 'nullable|file|image|max:2048'
        ]);

        try {
            $buktiPath = null;
            if ($request->hasFile('bukti_transaksi')) {
                $file = $request->file('bukti_transaksi');
                $filename = 'kop_fin_' . time() . '_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/bukti_bayar'), $filename);
                $buktiPath = 'uploads/bukti_bayar/' . $filename;
            }

            DB::table('koperasi_finances')->insert([
                'user_id'         => Auth::id(),
                'tanggal'         => $request->tanggal,
                'tipe'            => $request->tipe,
                'kategori'        => $request->kategori,
                'nominal'         => $request->nominal,
                'keterangan'      => $request->keterangan,
                'bukti_transaksi' => $buktiPath,
                'created_at'      => now(),
                'updated_at'      => now()
            ]);

            return response()->json(['status' => 'success', 'message' => 'Transaksi Keuangan Koperasi berhasil dicatat!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal mencatat transaksi: ' . $e->getMessage()], 500);
        }
    }

    public function updateFinance(Request $request)
    {
        abort_if(!$this->checkFinanceManagerAccess(), 403, 'Akses Ditolak');
        $request->validate([
            'id'              => 'required|integer',
            'tanggal'         => 'required|date',
            'tipe'            => 'required|in:pemasukan,pengeluaran',
            'kategori'        => 'required|string',
            'nominal'         => 'required|numeric|min:0',
            'keterangan'      => 'nullable|string',
            'bukti_transaksi' => 'nullable|file|image|max:2048'
        ]);

        try {
            $oldData = DB::table('koperasi_finances')->where('id', $request->id)->first();
            if (!$oldData) {
                return response()->json(['status' => 'error', 'message' => 'Transaksi tidak ditemukan!'], 404);
            }

            $buktiPath = $oldData->bukti_transaksi;
            if ($request->hasFile('bukti_transaksi')) {
                if ($buktiPath && file_exists(public_path($buktiPath))) {
                    @unlink(public_path($buktiPath));
                }

                $file = $request->file('bukti_transaksi');
                $filename = 'kop_fin_' . time() . '_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/bukti_bayar'), $filename);
                $buktiPath = 'uploads/bukti_bayar/' . $filename;
            }

            DB::table('koperasi_finances')->where('id', $request->id)->update([
                'tanggal'         => $request->tanggal,
                'tipe'            => $request->tipe,
                'kategori'        => $request->kategori,
                'nominal'         => $request->nominal,
                'keterangan'      => $request->keterangan,
                'bukti_transaksi' => $buktiPath,
                'updated_at'      => now()
            ]);

            return response()->json(['status' => 'success', 'message' => 'Transaksi Keuangan Koperasi berhasil diperbarui!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal memperbarui transaksi: ' . $e->getMessage()], 500);
        }
    }

    public function destroyFinance(Request $request)
    {
        abort_if(!$this->checkFinanceManagerAccess(), 403, 'Akses Ditolak');
        $request->validate(['id' => 'required|integer']);

        try {
            $oldData = DB::table('koperasi_finances')->where('id', $request->id)->first();
            if ($oldData && $oldData->bukti_transaksi && file_exists(public_path($oldData->bukti_transaksi))) {
                @unlink(public_path($oldData->bukti_transaksi));
            }

            DB::table('koperasi_finances')->where('id', $request->id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Transaksi Keuangan Koperasi berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus transaksi.'], 500);
        }
    }
}
