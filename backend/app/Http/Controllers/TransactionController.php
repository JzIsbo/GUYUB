<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Store a newly created transaction.
     */
    public function store(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RW', 'Bendahara RW', 'RT', 'Bendahara RT']), 403, 'Akses Ditolak');
        $request->validate([
            'tanggal'    => 'required|date',
            'kategori'   => 'required|string',
            'keterangan' => 'required|string',
            'jumlah'     => 'required|numeric|min:1',
            'jenis'      => 'required|in:pemasukan,pengeluaran'
        ]);

        $nominal = $request->jumlah;

        try {
            DB::transaction(function () use ($request, $nominal) {
                // 1. Create Transaction
                Transaction::create([
                    'tanggal'    => $request->tanggal,
                    'kategori'   => $request->kategori,
                    'keterangan' => $request->keterangan,
                    'nominal'    => $nominal,
                    'jenis'      => $request->jenis
                ]);

                // 2. Synchronize Warga Iuran payments if category contains 'iuran'
                if (str_contains(strtolower($request->kategori), 'iuran')) {
                    $wargaId = $request->warga_id ?? DB::table('wargas')->value('id');

                    if ($wargaId) {
                        $warga = DB::table('wargas')->where('id', $wargaId)->first();
                        $wargaName = $warga ? $warga->nama_lengkap : 'Warga';

                        if (\Illuminate\Support\Facades\Schema::hasTable('contributions_payment')) {
                            DB::table('contributions_payment')->insert([
                                'warga_id'      => $wargaId,
                                'iuran_id'      => 1, // Default ID Iuran
                                'nominal_bayar' => $nominal,
                                'tanggal_bayar' => $request->tanggal,
                                'created_at'    => now(),
                                'updated_at'    => now()
                            ]);
                        }

                        // Record as lunas tagihan
                        $periode = date('Y-m', strtotime($request->tanggal));
                        DB::table('tagihans')->insert([
                            'warga_id'      => $wargaId,
                            'nama_warga'    => $wargaName,
                            'jenis_tagihan' => 'Iuran Kas',
                            'periode'       => $periode,
                            'jumlah'        => $nominal,
                            'metode_bayar'  => 'Cash/Cashier',
                            'status'        => 'lunas',
                            'tanggal_lunas' => $request->tanggal,
                            'batas_bayar'   => $request->tanggal,
                            'created_at'    => now(),
                            'updated_at'    => now()
                        ]);
                    }
                }
            });

            self::logActivity('BUAT TRANSAKSI', "Mencatat transaksi " . ($request->jenis == 'pemasukan' ? 'Pemasukan' : 'Pengeluaran') . " baru: {$request->kategori} - {$request->keterangan} sebesar Rp " . number_format($nominal, 0, ',', '.'));

            return response()->json([
                'status'  => 'success',
                'message' => 'Data transaksi berhasil disimpan dan masuk ke database!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan ke database: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified transaction.
     */
    public function update(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RW', 'Bendahara RW', 'RT', 'Bendahara RT']), 403, 'Akses Ditolak');
        $request->merge([
            'nominal' => $request->jumlah ?? $request->nominal
        ]);

        $request->validate([
            'id'         => 'required',
            'tanggal'    => 'required|date',
            'kategori'   => 'required|string',
            'keterangan' => 'required|string',
            'nominal'    => 'required|numeric|min:1'
        ], [
            'nominal.required' => 'Jumlah uang wajib diisi!',
            'nominal.min'      => 'Jumlah uang tidak boleh 0 atau minus.'
        ]);

        try {
            $transaction = Transaction::findOrFail($request->id);
            $transaction->update([
                'tanggal'    => $request->tanggal,
                'kategori'   => $request->kategori,
                'keterangan' => $request->keterangan,
                'nominal'    => $request->nominal
            ]);

            self::logActivity('UPDATE TRANSAKSI', "Memperbarui data transaksi: {$transaction->kategori} - {$transaction->keterangan} menjadi Rp " . number_format($request->nominal, 0, ',', '.'));

            return response()->json([
                'status'  => 'success',
                'message' => 'Data transaksi berhasil diperbarui!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal memperbarui transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified transaction from storage.
     */
    public function destroy(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'RW', 'Bendahara RW', 'RT', 'Bendahara RT']), 403, 'Akses Ditolak');
        $request->validate(['id' => 'required']);

        try {
            $transaction = Transaction::findOrFail($request->id);
            $cat = $transaction->kategori;
            $ket = $transaction->keterangan;
            $nom = $transaction->nominal;
            $transaction->delete();

            self::logActivity('HAPUS TRANSAKSI', "Menghapus data transaksi: {$cat} - {$ket} sebesar Rp " . number_format($nom, 0, ',', '.'));

            return response()->json([
                'status'  => 'success',
                'message' => 'Data transaksi berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menghapus transaksi.'
            ], 500);
        }
    }

    /**
     * Export reports to CSV.
     */
    public function export(Request $request, $tipe = 'all')
    {
        if ($tipe === 'all' && $request->has('tipe')) {
            $tipe = $request->query('tipe');
        }
        $format = $request->query('format', 'excel');

        if ($tipe == 'kas') {
            $data = Transaction::where('jenis', 'pemasukan')
                ->orWhere('jenis', 'pengeluaran')
                ->orderBy('tanggal', 'desc')
                ->get();
        } elseif ($tipe == 'iuran') {
            $data = Transaction::where('kategori', 'LIKE', '%Iuran%')
                ->orderBy('tanggal', 'desc')
                ->get();
        } elseif ($tipe == 'koperasi') {
            $raw_data = DB::table('koperasi_finances')
                ->orderBy('tanggal', 'desc')
                ->get();
            $data = $raw_data->map(function($item) {
                $item->jenis = $item->tipe;
                return $item;
            });
        } else {
            $data = Transaction::orderBy('tanggal', 'desc')->get();
        }

        $totalPemasukan = $data->where('jenis', 'pemasukan')->sum('nominal');
        $totalPengeluaran = $data->where('jenis', 'pengeluaran')->sum('nominal');
        $saldoBersih = $totalPemasukan - $totalPengeluaran;

        $rt_info = DB::table('rt_details')->first();
        $namaRT = $rt_info->nama_rt ?? 'RT 01 / RW 02';
        $alamatRT = $rt_info->alamat ?? 'Kelurahan Asri, Kecamatan Jaya';

        self::logActivity('EKSPOR LAPORAN', "Mengekspor laporan " . strtoupper($tipe) . " dalam format " . strtoupper($format));

        if ($format == 'pdf') {
            // Generate elegant PDF print layout
            return response()->make(view('admin.exports.pdf', [
                'data' => $data,
                'tipe' => $tipe,
                'totalPemasukan' => $totalPemasukan,
                'totalPengeluaran' => $totalPengeluaran,
                'saldoBersih' => $saldoBersih,
                'namaRT' => $namaRT,
                'alamatRT' => $alamatRT
            ]));
        }

        // Generate high-quality styled Excel (XLS)
        $fileName = 'Laporan_' . ucfirst($tipe) . '_' . date('Y-m-d') . '.xls';
        
        $output = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        $output .= '<head><meta http-equiv="Content-type" content="text/html;charset=utf-8" />';
        $output .= '<style>
            table { border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; }
            th { background-color: #2563EB; color: #FFFFFF; font-weight: bold; border: 1px solid #CBD5E1; padding: 10px; text-align: left; }
            td { border: 1px solid #CBD5E1; padding: 8px; color: #334155; }
            .title { font-size: 16px; font-weight: bold; color: #1E293B; margin-bottom: 5px; }
            .subtitle { font-size: 12px; color: #64748B; margin-bottom: 20px; }
            .nominal { text-align: right; font-weight: bold; }
            .pemasukan { color: #10B981; }
            .pengeluaran { color: #EF4444; }
            .total-row { background-color: #F8FAFC; font-weight: bold; }
        </style></head><body>';

        $titleText = $tipe == 'koperasi' ? 'LAPORAN KEUANGAN KOPERASI WARGA' : 'LAPORAN KEUANGAN KAS ' . strtoupper($tipe);
        $output .= '<div class="title">' . $titleText . '</div>';
        $output .= '<div class="subtitle">' . $namaRT . ' - ' . $alamatRT . '<br>Tanggal Unduh: ' . date('d-m-Y H:i') . '</div>';
        
        $output .= '<table><thead><tr>';
        $output .= '<th>Tanggal</th>';
        $output .= '<th>Keterangan</th>';
        $output .= '<th>Kategori</th>';
        $output .= '<th>Jenis</th>';
        $output .= '<th style="text-align: right;">Nominal</th>';
        $output .= '</tr></thead><tbody>';

        foreach ($data as $row) {
            $class = $row->jenis == 'pemasukan' ? 'pemasukan' : 'pengeluaran';
            $jenisLabel = $row->jenis == 'pemasukan' ? 'Pemasukan' : 'Pengeluaran';
            $output .= '<tr>';
            $output .= '<td>' . date('d-m-Y', strtotime($row->tanggal)) . '</td>';
            $output .= '<td>' . htmlspecialchars($row->keterangan) . '</td>';
            $output .= '<td>' . htmlspecialchars($row->kategori) . '</td>';
            $output .= '<td class="' . $class . '">' . $jenisLabel . '</td>';
            $output .= '<td class="nominal ' . $class . '">Rp ' . number_format($row->nominal, 0, ',', '.') . '</td>';
            $output .= '</tr>';
        }

        // Summary row
        $output .= '<tr class="total-row"><td colspan="4" style="text-align: right; padding: 10px;">Total Pemasukan:</td><td class="nominal pemasukan">Rp ' . number_format($totalPemasukan, 0, ',', '.') . '</td></tr>';
        $output .= '<tr class="total-row"><td colspan="4" style="text-align: right; padding: 10px;">Total Pengeluaran:</td><td class="nominal pengeluaran">Rp ' . number_format($totalPengeluaran, 0, ',', '.') . '</td></tr>';
        $output .= '<tr class="total-row"><td colspan="4" style="text-align: right; padding: 10px;">Saldo Bersih:</td><td class="nominal ' . ($saldoBersih >= 0 ? 'pemasukan' : 'pengeluaran') . '">Rp ' . number_format($saldoBersih, 0, ',', '.') . '</td></tr>';
        
        $output .= '</tbody></table></body></html>';

        return response($output, 200, [
            "Content-type"        => "application/vnd.ms-excel",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ]);
    }
}
