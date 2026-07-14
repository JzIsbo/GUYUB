<?php

namespace App\Http\Controllers;

use App\Models\Warga;
use App\Models\Tagihan;
use App\Services\SelfHealingDatabaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Centralized layout loader for Kas RT Dashboard SPA.
     */
    public function loadPage(Request $request, $page = 'dashboard')
    {
        // 1. Run database self-healing checks on boot
        SelfHealingDatabaseService::checkAndCreateTables();

        // 2. CCTV LOGIN: Automatically log system access
        if (Auth::check() && !session()->has('cctv_login_tercatat')) {
            try {
                DB::table('activity_logs')->insert([
                    'user_id'     => Auth::id(),
                    'action'      => 'LOGIN SISTEM',
                    'description' => 'Pengguna berhasil masuk ke dalam aplikasi.',
                    'created_at'  => now(),
                    'updated_at'  => now()
                ]);
                session()->put('cctv_login_tercatat', true);
            } catch (\Exception $e) {
                // Ignore connection errors if database fails to connect
            }
        }

        // 3. DETEKSI ROLE LOGIN & PEMBAGIAN HAK AKSES HALAMAN (12 FITUR)
        $userRole = Auth::check() ? Auth::user()->role : 'Warga';
        
        $allPages = [
            'dashboard', 'pemasukan', 'pengeluaran', 'transaksi', 'kategori',
            'data-warga', 'data-iuran', 'data-pengurus-rt', 'data-rt', 'pengguna', 'perangkat-sistem',
            'laporan-keuangan', 'laporan-iuran', 'laporan-kas', 'export-laporan', 'pengaturan', 'backup-restore', 'aktivitas-pengguna',
            'tagihan-warga', 'pembayaran-online', 'status-pembayaran', 'riwayat-gateway', 'qris-va',
            'surat-online', 'pengumuman',
            'koperasi', 'bank-sampah', 'umkm', 'posyandu', 'keamanan', 'kegiatan', 'rukem', 'aspirasi'
        ];

        $aksesHalaman = [
            'Super Admin' => $allPages,
            'RT'          => [
                'dashboard', 'data-warga', 'data-pengurus-rt', 'data-rt', 'perangkat-sistem',
                'surat-online', 'pengumuman', 'tagihan-warga', 'pembayaran-online', 'status-pembayaran', 'riwayat-gateway', 'qris-va',
                'laporan-keuangan', 'laporan-iuran', 'laporan-kas', 'pengaturan',
                'koperasi', 'bank-sampah', 'umkm', 'posyandu', 'keamanan', 'kegiatan', 'rukem', 'aspirasi'
            ],
            'Bendahara'   => [
                'dashboard', 'pemasukan', 'pengeluaran', 'transaksi', 'kategori', 'data-iuran',
                'laporan-keuangan', 'laporan-iuran', 'laporan-kas', 'export-laporan',
                'tagihan-warga', 'pembayaran-online', 'status-pembayaran', 'riwayat-gateway', 'qris-va',
                'surat-online', 'pengumuman', 'pengaturan',
                'koperasi', 'bank-sampah', 'rukem', 'umkm', 'posyandu', 'keamanan', 'kegiatan', 'aspirasi'
            ],
            'Warga'       => [
                'dashboard', 'data-warga', 'data-pengurus-rt', 'surat-online', 'pengumuman',
                'tagihan-warga', 'pembayaran-online', 'status-pembayaran', 'riwayat-gateway', 'qris-va', 'pengaturan',
                'koperasi', 'bank-sampah', 'umkm', 'posyandu', 'keamanan', 'kegiatan', 'rukem', 'aspirasi'
            ]
        ];

        if (!isset($aksesHalaman[$userRole]) || !in_array($page, $aksesHalaman[$userRole])) {
            if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return "<div class='p-10 bg-red-50 text-center rounded-[2rem] border border-red-100 text-red-500 font-bold'>⛔ Akses Ditolak! Akun dengan role <u>{$userRole}</u> tidak diizinkan membuka halaman ini.</div>";
            }
            abort(403, 'Akses Ditolak.');
        }

        // 4. FETCH DATA GLOBAL UNTUK WIDGET STATISTIK & GRAFIK
        $pemasukan = 0;
        $pengeluaran = 0;
        $total_warga = 0;
        $transaksi_terbaru = collect();
        $rt_info = null;
        $chart_pemasukan = array_fill(0, 12, 0);
        $chart_pengeluaran = array_fill(0, 12, 0);

        $isAjax = $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest';

        if (!$isAjax || $page === 'dashboard') {
            try {
                $pemasukan = (float) DB::table('transactions')->where('jenis', 'pemasukan')->sum('nominal');
            $pengeluaran = (float) DB::table('transactions')->where('jenis', 'pengeluaran')->sum('nominal');

            $total_warga = DB::table('wargas')->count();

            $transaksi_terbaru = DB::table('transactions')->orderBy('tanggal', 'desc')->take(5)->get();
            $rt_info = DB::table('rt_details')->first();

            // Chart Bulanan
            $chartData = DB::table('transactions')
                ->selectRaw('MONTH(tanggal) as bulan, jenis, SUM(nominal) as total')
                ->whereYear('tanggal', date('Y'))
                ->groupBy('bulan', 'jenis')
                ->get();

            $pemasukan_bulanan = array_fill(1, 12, 0);
            $pengeluaran_bulanan = array_fill(1, 12, 0);

            foreach ($chartData as $row) {
                if ($row->jenis == 'pemasukan') {
                    $pemasukan_bulanan[$row->bulan] = (float)$row->total;
                } else {
                    $pengeluaran_bulanan[$row->bulan] = (float)$row->total;
                }
            }
                $chart_pemasukan = array_values($pemasukan_bulanan);
                $chart_pengeluaran = array_values($pengeluaran_bulanan);
            } catch (\Exception $e) {
                // Degrade gracefully
            }
        }

        $saldo_bersih = $pemasukan - $pengeluaran;

        $data = [
            'saldo'             => $pemasukan,
            'pengeluaran'       => $pengeluaran,
            'saldo_bersih'      => $saldo_bersih,
            'warga'             => $total_warga,
            'transaksi_terbaru' => $transaksi_terbaru,
            'rt_info'           => $rt_info,
            'chart_pemasukan'   => $chart_pemasukan,
            'chart_pengeluaran' => $chart_pengeluaran,
            'user'              => Auth::user()
        ];

        // 5. DATA FILTER SPESIFIK UNTUK MASING-MASING DETAIL HALAMAN (12 FITUR)
        try {
            if ($page == 'pemasukan') {
                $data['list_pemasukan'] = DB::table('transactions')->where('jenis', 'pemasukan')->orderBy('tanggal', 'desc')->get();
                $data['kategori_pemasukan'] = DB::table('categories')->where('tipe', 'pemasukan')->select('id', 'nama as nama_kategori', 'tipe')->get();
            } elseif ($page == 'pengeluaran') {
                $data['list_pengeluaran'] = DB::table('transactions')->where('jenis', 'pengeluaran')->orderBy('tanggal', 'desc')->get();
                $data['kategori_pengeluaran'] = DB::table('categories')->where('tipe', 'pengeluaran')->select('id', 'nama as nama_kategori', 'tipe')->get();
            } elseif ($page == 'transaksi') {
                $data['list_transaksi'] = DB::table('transactions')->orderBy('tanggal', 'desc')->get();
            } elseif ($page == 'kategori') {
                $data['list_kategori'] = DB::table('categories')
                    ->leftJoin('transactions', 'categories.nama', '=', 'transactions.kategori')
                    ->select('categories.*', DB::raw('COUNT(transactions.id) as total_dipakai'))
                    ->groupBy('categories.id', 'categories.nama', 'categories.tipe', 'categories.created_at', 'categories.updated_at')
                    ->get();
            } elseif ($page == 'data-warga') {
                $warga_raw = Warga::orderBy('blok_rumah', 'asc')->orderBy('nomor_kk', 'asc')->get();
                $data['warga_grouped'] = $warga_raw->groupBy(function($item) {
                    return $item->blok_rumah . '_' . $item->nomor_kk;
                });
            } elseif ($page == 'data-iuran') {
                $data['list_iuran'] = DB::table('contributions')->orderBy('sifat', 'desc')->get();
            } elseif ($page == 'data-pengurus-rt') {
                $data['list_pengurus'] = DB::table('officers')
                    ->join('wargas', 'officers.warga_id', '=', 'wargas.id')
                    ->select('officers.*', 'wargas.nama_lengkap as nama_warga')
                    ->orderBy('officers.status_aktif', 'asc')->get();
                $data['all_warga'] = DB::table('wargas')->orderBy('nama_lengkap', 'asc')->get();
            } elseif ($page == 'pengguna') {
                $users = DB::table('users')->orderBy('role', 'asc')->get();
                foreach ($users as $user) {
                    if ($user->role === 'Super Admin') $user->badge_class = 'bg-red-50 text-red-600 border-red-100';
                    elseif ($user->role === 'RT') $user->badge_class = 'bg-blue-50 text-blue-600 border-blue-100';
                    elseif ($user->role === 'Bendahara') $user->badge_class = 'bg-emerald-50 text-emerald-600 border-emerald-100';
                    else $user->badge_class = 'bg-gray-50 text-gray-600 border-gray-100';
                    $user->is_aktif = ($user->status === 'Aktif');
                }
                $data['list_pengguna'] = $users;
            } elseif ($page == 'perangkat-sistem') {
                $devices = DB::table('devices')->orderBy('nama_perangkat', 'asc')->get();
                foreach ($devices as $dev) {
                    if ($dev->kondisi === 'Baik') $dev->badge_class = 'bg-emerald-50 text-emerald-600 border-emerald-100';
                    elseif ($dev->kondisi === 'Rusak Ringan') $dev->badge_class = 'bg-amber-50 text-amber-600 border-amber-100';
                    else $dev->badge_class = 'bg-red-50 text-red-600 border-red-100';
                }
                $data['list_perangkat'] = $devices;
            } elseif ($page == 'laporan-keuangan' || $page == 'laporan-kas') {
                $list_kas = DB::table('transactions')->orderBy('tanggal', 'asc')->get();
                $saldo = 0;
                foreach ($list_kas as $kas) {
                    if ($kas->jenis == 'pemasukan') $saldo += $kas->nominal;
                    else $saldo -= $kas->nominal;
                    $kas->saldo_akhir = $saldo;
                }
                $data['list_kas'] = $list_kas;
                $data['list_transaksi'] = $list_kas->reverse()->values();
            } elseif ($page == 'laporan-iuran') {
                $data['list_laporan_iuran'] = DB::table('transactions')->where('kategori', 'LIKE', '%Iuran%')->orderBy('tanggal', 'desc')->get();
            } elseif ($page == 'tagihan-warga') {
                $user = Auth::user();
                if (in_array($user->role, ['Super Admin', 'RT', 'Bendahara'])) {
                    $data['tagihans'] = Tagihan::orderBy('created_at', 'desc')->get();
                } else {
                    $data['tagihans'] = Tagihan::where('nama_warga', $user->name)->orderBy('created_at', 'desc')->get();
                }
            } elseif ($page == 'pembayaran-online') {
                $data['gateway'] = DB::table('payment_gateways')->first();
            } elseif ($page == 'status-pembayaran') {
                $user = Auth::user();
                if (in_array($user->role, ['Super Admin', 'RT', 'Bendahara'])) {
                    $data['payments'] = DB::table('online_payments')->orderBy('created_at', 'desc')->get();
                } else {
                    $data['payments'] = DB::table('online_payments')->where('nama_pembayar', $user->name)->orderBy('created_at', 'desc')->get();
                }
            } elseif ($page == 'riwayat-gateway') {
                $data['logs'] = DB::table('gateway_logs')->orderBy('created_at', 'desc')->take(50)->get();
            } elseif ($page == 'qris-va') {
                $data['qris'] = DB::table('qris_settings')->first();
            } elseif ($page == 'surat-online') {
                $user = Auth::user();
                if (in_array($user->role, ['Super Admin', 'RT'])) {
                    $data['list_surat'] = DB::table('surat_online')->orderBy('created_at', 'desc')->get();
                } else {
                    $data['list_surat'] = DB::table('surat_online')->where('nama_warga', $user->name)->orderBy('created_at', 'desc')->get();
                }
            } elseif ($page == 'pengumuman') {
                $data['list_pengumuman'] = DB::table('pengumumans')->orderBy('created_at', 'desc')->get();
            }
            // --- DATA LOADERS UNTUK 8 FITUR BARU ---
            elseif ($page == 'koperasi') {
                $data['list_koperasi'] = DB::table('koperasi_items')->orderBy('created_at', 'desc')->get();
            } elseif ($page == 'bank-sampah') {
                $data['list_deposit'] = DB::table('bank_sampah_deposits')->orderBy('tanggal', 'desc')->get();
                $data['total_berat'] = DB::table('bank_sampah_deposits')->sum('berat_kg');
                $data['total_rupiah'] = DB::table('bank_sampah_deposits')->sum('total_rupiah');
            } elseif ($page == 'umkm') {
                $data['list_umkm'] = DB::table('umkms')->orderBy('created_at', 'desc')->get();
            } elseif ($page == 'posyandu') {
                $data['list_posyandu'] = DB::table('posyandus')->orderBy('tanggal', 'asc')->get();
                // Load pendaftaran peserta
                $pendaftaranQuery = DB::table('posyandu_pendaftarans')->orderBy('created_at', 'desc');
                if (Auth::user()->role === 'Warga') {
                    $pendaftaranQuery->where('nama_pendaftar', Auth::user()->name);
                }
                $data['list_pendaftaran'] = $pendaftaranQuery->get();
            } elseif ($page == 'keamanan') {
                $data['list_ronda'] = DB::table('rondas')->get();
                $data['list_incidents'] = DB::table('incidents')->orderBy('created_at', 'desc')->get();
            } elseif ($page == 'kegiatan') {
                $data['list_kegiatan'] = DB::table('kegiatans')->orderBy('tanggal', 'asc')->get();
            } elseif ($page == 'rukem') {
                $data['list_rukem'] = DB::table('rukems')->orderBy('tanggal_duka', 'desc')->get();
                $data['total_santunan'] = DB::table('rukems')->sum('santunan_diserahkan');
            } elseif ($page == 'aspirasi') {
                $data['list_aspirasi'] = DB::table('aspirasis')->orderBy('created_at', 'desc')->get();
            } elseif ($page == 'backup-restore') {
                return view('admin.partials.backup-restore', $data);
            } elseif ($page == 'edit-warga') {
                $id = request('id');
                $data['warga'] = DB::table('wargas')->where('id', $id)->first();
                return view('admin.partials.edit-warga', $data);
            }
        } catch (\Exception $e) {
            // Degrade gracefully
        }

        // 6. RENDER JSON HANYA UNTUK ROUTE api/* ATAU QUERY ?format=json
        //    (Jangan pakai wantsJson() — browser bisa kirim Accept: application/json secara otomatis)
        if ($request->is('api/*') || $request->query('format') === 'json') {
            return response()->json(array_merge(['status' => 'success', 'page' => $page], $data));
        }

        // 7. RESOLVE RESOLVED VIEW PATH BASED ON DEVICE
        $userAgent = $request->header('User-Agent');
        $isMobile = preg_match('/Mobile|Android|BlackBerry|iPhone|iPad|iPod|Opera Mini|IEMobile/i', $userAgent);

        $viewPath = "admin.partials." . $page;
        if ($isMobile && view()->exists("admin.partials.mobile." . $page)) {
            $viewPath = "admin.partials.mobile." . $page;
        }
        $data['resolvedView'] = $viewPath;

        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            if (view()->exists($viewPath)) {
                return view($viewPath, $data)->render();
            }
            return "<div class='p-10 bg-white text-center rounded-[2rem] border border-gray-100 shadow-sm'>File view {$viewPath}.blade.php belum dibuat.</div>";
        }

        return view('admin.super-admin', array_merge(['page' => $page], $data));
    }
}
