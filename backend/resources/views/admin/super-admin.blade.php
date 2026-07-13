<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Super Admin - KAS RT Digital System</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.csrfToken = "{{ csrf_token() }}";
        
        // Override native alert with modern SweetAlert2 notifications
        window.alert = function(msg) {
            if (typeof Swal === 'undefined') return;
            let strMsg = String(msg || '');
            let cleanMsg = strMsg.replace(/^[✅❌🎉]\s*/, '');
            let isError = /gagal|error|❌|peringatan|salah|terjadi kesalahan/i.test(strMsg);
            
            Swal.fire({
                title: isError ? 'Perhatian' : 'Berhasil!',
                text: cleanMsg,
                icon: isError ? 'error' : 'success',
                confirmButtonText: 'OK',
                buttonsStyling: false,
                customClass: {
                    popup: 'rounded-[2.5rem] p-6 shadow-2xl border border-gray-100 bg-white',
                    title: 'text-xl font-extrabold text-gray-800 tracking-tight',
                    htmlContainer: 'text-sm font-semibold text-gray-600 mt-2',
                    confirmButton: isError 
                        ? 'bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-8 rounded-2xl shadow-lg shadow-red-200 transition-all text-sm cursor-pointer'
                        : 'bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-2xl shadow-lg shadow-blue-200 transition-all text-sm cursor-pointer'
                }
            });
        };
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
        .sidebar-scroll::-webkit-scrollbar { width: 3px; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.05); border-radius: 10px; }
        .menu-active { background-color: #2563EB !important; color: white !important; box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.4); font-weight: 700; }
    </style>
</head>
<body class="flex h-screen overflow-hidden">

    @php
        // 1. LOGIC HAK AKSES SIDEBAR
        $aksesHalaman = [
            'Super Admin' => [
                'dashboard', 'pembayaran-menu', 'tagihan-warga', 'pembayaran-online', 'status-pembayaran', 'riwayat-gateway', 'qris-va',
                'pemasukan', 'pengeluaran', 'transaksi', 'kategori', 'surat-online', 'pengumuman', 'data-warga', 'data-iuran',
                'data-pengurus-rt', 'data-rt', 'pengguna', 'perangkat-sistem', 'laporan-keuangan', 'laporan-iuran', 'laporan-kas',
                'export-laporan', 'pengaturan', 'backup-restore', 'aktivitas-pengguna',
                'koperasi', 'bank-sampah', 'umkm', 'posyandu', 'keamanan', 'kegiatan', 'rukem', 'aspirasi'
            ],
            'RT'          => [
                'dashboard', 'pembayaran-menu', 'tagihan-warga', 'status-pembayaran', 'qris-va',
                'surat-online', 'pengumuman', 'data-warga', 'data-pengurus-rt', 'data-rt', 'perangkat-sistem',
                'laporan-keuangan', 'laporan-iuran', 'laporan-kas', 'pengaturan',
                'koperasi', 'bank-sampah', 'umkm', 'posyandu', 'keamanan', 'kegiatan', 'rukem', 'aspirasi'
            ],
            'Bendahara'   => [
                'dashboard', 'pembayaran-menu', 'tagihan-warga', 'pembayaran-online', 'status-pembayaran', 'riwayat-gateway', 'qris-va',
                'pemasukan', 'pengeluaran', 'transaksi', 'kategori', 'surat-online', 'pengumuman', 'data-iuran',
                'laporan-keuangan', 'laporan-iuran', 'laporan-kas', 'export-laporan', 'pengaturan',
                'koperasi', 'bank-sampah', 'rukem', 'umkm', 'posyandu', 'keamanan', 'kegiatan', 'aspirasi'
            ],
            'Warga'       => [
                'dashboard', 'pembayaran-menu', 'tagihan-warga', 'status-pembayaran', 'qris-va',
                'surat-online', 'pengumuman', 'data-warga', 'data-pengurus-rt', 'pengaturan',
                'koperasi', 'bank-sampah', 'umkm', 'posyandu', 'keamanan', 'kegiatan', 'rukem', 'aspirasi'
            ]
        ];

        $can = function($menu) use ($aksesHalaman) {
            $role = Auth::check() ? Auth::user()->role : 'Warga';
            return isset($aksesHalaman[$role]) && in_array($menu, $aksesHalaman[$role]);
        };
    @endphp

    <!-- Sidebar Backdrop Overlay -->
    <div id="sidebar-backdrop" onclick="toggleSidebar(false)" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-40 transition-opacity duration-300"></div>

    <aside id="sidebar" class="w-[300px] bg-[#0F172A] text-[#94A3B8] flex flex-col shrink-0 sidebar-scroll overflow-y-auto fixed inset-y-0 left-0 z-50 transform -translate-x-full transition-transform duration-300 ease-in-out">
        <!-- Close button inside sidebar -->
        <button onclick="toggleSidebar(false)" class="p-2 text-gray-400 hover:text-white rounded-lg absolute top-6 right-6 focus:outline-none transition-colors">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>

        <div class="p-8 flex items-center shrink-0">
            <div class="bg-white p-2.5 rounded-2xl mr-4 shadow-sm flex items-center justify-center">
                <i class="fa-solid fa-house-chimney-window text-[#0F172A] text-xl"></i>
            </div>
            <div>
                <h1 class="text-white font-extrabold text-xl leading-none italic uppercase tracking-tighter">KAS RT</h1>
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-[2px] mt-1.5 text-nowrap">Super Admin Panel</p>
            </div>
        </div>

        <nav class="flex-1 px-4 space-y-1 mt-2 pb-10">
            @if($can('dashboard'))
            <a href="javascript:void(0)" onclick="switchPage('dashboard', this)" class="menu-link {{ isset($page) && $page == 'dashboard' ? 'menu-active text-white' : 'hover:bg-white/5 hover:text-white' }} flex items-center px-4 py-3.5 rounded-2xl transition-all">
                <i class="fa-solid fa-th-large w-6 text-lg"></i> <span class="ml-3 text-sm font-bold">Dashboard</span>
            </a>
            @endif

            @if($can('tagihan-warga') || $can('pembayaran-online') || $can('status-pembayaran') || $can('riwayat-gateway') || $can('qris-va'))
            <div class="pt-8 pb-2 px-4">
                <p class="text-[10px] font-black text-gray-600 uppercase tracking-[2.5px]">Pembayaran</p>
            </div>

            <div class="space-y-1">
                <button onclick="toggleDropdown('pembayaran-menu')" class="menu-link hover:bg-white/5 hover:text-white flex items-center w-full px-4 py-3 text-sm rounded-2xl transition-all group">
                    <i class="fa-solid fa-wallet w-6 opacity-50 group-hover:opacity-100"></i>
                    <span class="ml-3 font-semibold flex-1 text-left">Pembayaran</span>
                    <i class="fa-solid fa-chevron-down text-[10px] opacity-50"></i>
                </button>

                <div id="pembayaran-menu" class="hidden pl-10 space-y-1 mt-1 border-l border-white/10 ml-6">
                    @php
                        $role = Auth::check() ? Auth::user()->role : 'Warga';
                        $labelTagihan = ($role == 'Warga') ? 'Tagihan Saya' : (($role == 'RT') ? 'Daftar Tagihan Warga' : 'Kelola Tagihan Warga');
                        $labelStatus = ($role == 'Warga') ? 'Status Pembayaran Saya' : (($role == 'RT') ? 'Daftar Pembayaran Warga' : 'Daftar Pembayaran');
                        $labelQris = ($role == 'Warga') ? 'Rekening & QRIS RT' : (($role == 'RT') ? 'Daftar Rekening & QRIS' : 'Kelola Rekening & QRIS');
                    @endphp

                    @if($can('tagihan-warga'))
                    <a href="javascript:void(0)" onclick="switchPage('tagihan-warga', this)" class="menu-link flex items-center px-4 py-2 text-xs font-medium text-gray-500 hover:text-white transition-all rounded-xl">{{ $labelTagihan }}</a>
                    @endif
                    @if($can('pembayaran-online'))
                    <a href="javascript:void(0)" onclick="switchPage('pembayaran-online', this)" class="menu-link flex items-center px-4 py-2 text-xs font-medium text-gray-500 hover:text-white transition-all rounded-xl">Pengaturan Gateway</a>
                    @endif
                    @if($can('status-pembayaran'))
                    <a href="javascript:void(0)" onclick="switchPage('status-pembayaran', this)" class="menu-link flex items-center px-4 py-2 text-xs font-medium text-gray-500 hover:text-white transition-all rounded-xl">{{ $labelStatus }}</a>
                    @endif
                    @if($can('riwayat-gateway'))
                    <a href="javascript:void(0)" onclick="switchPage('riwayat-gateway', this)" class="menu-link flex items-center px-4 py-2 text-xs font-medium text-gray-500 hover:text-white transition-all rounded-xl">Log Transaksi Gateway</a>
                    @endif
                    @if($can('qris-va'))
                    <a href="javascript:void(0)" onclick="switchPage('qris-va', this)" class="menu-link flex items-center px-4 py-2 text-xs font-medium text-gray-500 hover:text-white transition-all rounded-xl">{{ $labelQris }}</a>
                    @endif
                </div>
            </div>
            @endif
            <script>
                    function toggleDropdown(id) {
                        const menu = document.getElementById(id);
                        menu.classList.toggle('hidden');
                    }
            </script>


            @if($can('pemasukan') || $can('pengeluaran') || $can('transaksi') || $can('kategori'))
            <div class="pt-8 pb-2 px-4"><p class="text-[10px] font-black text-gray-600 uppercase tracking-[2.5px]">Transaksi</p></div>

            @if($can('pemasukan'))
            <a href="javascript:void(0)" onclick="switchPage('pemasukan', this)" class="menu-link {{ isset($page) && $page == 'pemasukan' ? 'menu-active text-white' : 'hover:bg-white/5 hover:text-white' }} flex items-center px-4 py-3 text-sm rounded-2xl transition-all group">
                <i class="fa-solid fa-circle-arrow-down w-6 opacity-50 group-hover:opacity-100"></i> <span class="ml-3 font-semibold">Pemasukan</span>
            </a>
            @endif
            @if($can('pengeluaran'))
            <a href="javascript:void(0)" onclick="switchPage('pengeluaran', this)" class="menu-link {{ isset($page) && $page == 'pengeluaran' ? 'menu-active text-white' : 'hover:bg-white/5 hover:text-white' }} flex items-center px-4 py-3 text-sm rounded-2xl transition-all group">
                <i class="fa-solid fa-circle-arrow-up w-6 opacity-50 group-hover:opacity-100"></i> <span class="ml-3 font-semibold">Pengeluaran</span>
            </a>
            @endif
            @if($can('transaksi'))
            <a href="javascript:void(0)" onclick="switchPage('transaksi', this)" class="menu-link {{ isset($page) && $page == 'transaksi' ? 'menu-active text-white' : 'hover:bg-white/5 hover:text-white' }} flex items-center px-4 py-3 text-sm rounded-2xl transition-all group">
                <i class="fa-solid fa-shuffle w-6 opacity-50 group-hover:opacity-100"></i> <span class="ml-3 font-semibold">Transaksi</span>
            </a>
            @endif
            @if($can('kategori'))
            <a href="javascript:void(0)" onclick="switchPage('kategori', this)" class="menu-link {{ isset($page) && $page == 'kategori' ? 'menu-active text-white' : 'hover:bg-white/5 hover:text-white' }} flex items-center px-4 py-3 text-sm rounded-2xl transition-all group">
                <i class="fa-solid fa-folder-tree w-6 opacity-50 group-hover:opacity-100"></i> <span class="ml-3 font-semibold">Kategori</span>
            </a>
            @endif
            @endif

            @if($can('surat-online') || $can('pengumuman'))
            <div class="pt-8 pb-2 px-4"><p class="text-[10px] font-black text-gray-600 uppercase tracking-[2.5px]">Interaksi Warga</p></div>

            @if($can('surat-online'))
            <a href="javascript:void(0)" onclick="switchPage('surat-online', this)" class="menu-link {{ isset($page) && $page == 'surat-online' ? 'menu-active text-white' : 'hover:bg-white/5 hover:text-white' }} flex items-center px-4 py-3 text-sm rounded-2xl transition-all group">
                <i class="fa-solid fa-envelope w-6 opacity-50 group-hover:opacity-100"></i> <span class="ml-3 font-semibold">Surat Online</span>
            </a>
            @endif
            @if($can('pengumuman'))
            <a href="javascript:void(0)" onclick="switchPage('pengumuman', this)" class="menu-link {{ isset($page) && $page == 'pengumuman' ? 'menu-active text-white' : 'hover:bg-white/5 hover:text-white' }} flex items-center px-4 py-3 text-sm rounded-2xl transition-all group">
                <i class="fa-solid fa-bullhorn w-6 opacity-50 group-hover:opacity-100"></i> <span class="ml-3 font-semibold">Pengumuman</span>
            </a>
            @endif
            @endif

            @if($can('koperasi') || $can('bank-sampah') || $can('umkm') || $can('posyandu') || $can('keamanan') || $can('kegiatan') || $can('rukem') || $can('aspirasi'))
            <div class="pt-8 pb-2 px-4"><p class="text-[10px] font-black text-gray-600 uppercase tracking-[2.5px]">Layanan Warga</p></div>

            @if($can('koperasi'))
            <a href="javascript:void(0)" onclick="switchPage('koperasi', this)" class="menu-link {{ isset($page) && $page == 'koperasi' ? 'menu-active text-white' : 'hover:bg-white/5 hover:text-white' }} flex items-center px-4 py-3 text-sm rounded-2xl transition-all group">
                <i class="fa-solid fa-store w-6 opacity-50 group-hover:opacity-100"></i> <span class="ml-3 font-semibold">Koperasi Warga</span>
            </a>
            @endif

            @if($can('bank-sampah'))
            <a href="javascript:void(0)" onclick="switchPage('bank-sampah', this)" class="menu-link {{ isset($page) && $page == 'bank-sampah' ? 'menu-active text-white' : 'hover:bg-white/5 hover:text-white' }} flex items-center px-4 py-3 text-sm rounded-2xl transition-all group">
                <i class="fa-solid fa-recycle w-6 opacity-50 group-hover:opacity-100"></i> <span class="ml-3 font-semibold">Bank Sampah</span>
            </a>
            @endif

            @if($can('umkm'))
            <a href="javascript:void(0)" onclick="switchPage('umkm', this)" class="menu-link {{ isset($page) && $page == 'umkm' ? 'menu-active text-white' : 'hover:bg-white/5 hover:text-white' }} flex items-center px-4 py-3 text-sm rounded-2xl transition-all group">
                <i class="fa-solid fa-shop w-6 opacity-50 group-hover:opacity-100"></i> <span class="ml-3 font-semibold">UMKM Warga</span>
            </a>
            @endif

            @if($can('posyandu'))
            <a href="javascript:void(0)" onclick="switchPage('posyandu', this)" class="menu-link {{ isset($page) && $page == 'posyandu' ? 'menu-active text-white' : 'hover:bg-white/5 hover:text-white' }} flex items-center px-4 py-3 text-sm rounded-2xl transition-all group">
                <i class="fa-solid fa-heart-pulse w-6 opacity-50 group-hover:opacity-100"></i> <span class="ml-3 font-semibold">Posyandu</span>
            </a>
            @endif

            @if($can('keamanan'))
            <a href="javascript:void(0)" onclick="switchPage('keamanan', this)" class="menu-link {{ isset($page) && $page == 'keamanan' ? 'menu-active text-white' : 'hover:bg-white/5 hover:text-white' }} flex items-center px-4 py-3 text-sm rounded-2xl transition-all group">
                <i class="fa-solid fa-shield-halved w-6 opacity-50 group-hover:opacity-100"></i> <span class="ml-3 font-semibold">Keamanan & Ronda</span>
            </a>
            @endif

            @if($can('kegiatan'))
            <a href="javascript:void(0)" onclick="switchPage('kegiatan', this)" class="menu-link {{ isset($page) && $page == 'kegiatan' ? 'menu-active text-white' : 'hover:bg-white/5 hover:text-white' }} flex items-center px-4 py-3 text-sm rounded-2xl transition-all group">
                <i class="fa-solid fa-calendar-check w-6 opacity-50 group-hover:opacity-100"></i> <span class="ml-3 font-semibold">Kegiatan RT</span>
            </a>
            @endif

            @if($can('rukem'))
            <a href="javascript:void(0)" onclick="switchPage('rukem', this)" class="menu-link {{ isset($page) && $page == 'rukem' ? 'menu-active text-white' : 'hover:bg-white/5 hover:text-white' }} flex items-center px-4 py-3 text-sm rounded-2xl transition-all group">
                <i class="fa-solid fa-hands-holding-child w-6 opacity-50 group-hover:opacity-100"></i> <span class="ml-3 font-semibold">Rukem (Duka Cita)</span>
            </a>
            @endif

            @if($can('aspirasi'))
            <a href="javascript:void(0)" onclick="switchPage('aspirasi', this)" class="menu-link {{ isset($page) && $page == 'aspirasi' ? 'menu-active text-white' : 'hover:bg-white/5 hover:text-white' }} flex items-center px-4 py-3 text-sm rounded-2xl transition-all group">
                <i class="fa-solid fa-comment-dots w-6 opacity-50 group-hover:opacity-100"></i> <span class="ml-3 font-semibold">Aspirasi & Keluhan</span>
            </a>
            @endif
            @endif

            @if($can('data-warga') || $can('data-iuran') || $can('data-pengurus-rt') || $can('data-rt') || $can('pengguna') || $can('perangkat-sistem'))
            <div class="pt-8 pb-2 px-4"><p class="text-[10px] font-black text-gray-600 uppercase tracking-[2.5px]">Master Data</p></div>

            @if($can('data-warga'))
            <a href="javascript:void(0)" onclick="switchPage('data-warga', this)" class="menu-link {{ isset($page) && $page == 'data-warga' ? 'menu-active text-white' : 'hover:bg-white/5 hover:text-white' }} flex items-center px-4 py-3 text-sm rounded-2xl transition-all group">
                <i class="fa-solid fa-users w-6 opacity-50 group-hover:opacity-100"></i> <span class="ml-3 font-semibold">Data Warga</span>
            </a>
            @endif
            @if($can('data-iuran'))
            <a href="javascript:void(0)" onclick="switchPage('data-iuran', this)" class="menu-link {{ isset($page) && $page == 'data-iuran' ? 'menu-active text-white' : 'hover:bg-white/5 hover:text-white' }} flex items-center px-4 py-3 text-sm rounded-2xl transition-all group">
                <i class="fa-solid fa-wallet w-6 opacity-50 group-hover:opacity-100"></i> <span class="ml-3 font-semibold">Data Iuran</span>
            </a>
            @endif
            @if($can('data-pengurus-rt'))
            <a href="javascript:void(0)" onclick="switchPage('data-pengurus-rt', this)" class="menu-link {{ isset($page) && $page == 'data-pengurus-rt' ? 'menu-active text-white' : 'hover:bg-white/5 hover:text-white' }} flex items-center px-4 py-3 text-sm rounded-2xl transition-all group">
                <i class="fa-solid fa-user-tie w-6 opacity-50 group-hover:opacity-100"></i> <span class="ml-3 font-semibold">Data Pengurus RT</span>
            </a>
            @endif
            @if($can('data-rt'))
            <a href="javascript:void(0)" onclick="switchPage('data-rt', this)" class="menu-link {{ isset($page) && $page == 'data-rt' ? 'menu-active text-white' : 'hover:bg-white/5 hover:text-white' }} flex items-center px-4 py-3 text-sm rounded-2xl transition-all group">
                <i class="fa-solid fa-database w-6 opacity-50 group-hover:opacity-100"></i> <span class="ml-3 font-semibold">Data RT</span>
            </a>
            @endif
            @if($can('pengguna'))
            <a href="javascript:void(0)" onclick="switchPage('pengguna', this)" class="menu-link {{ isset($page) && $page == 'pengguna' ? 'menu-active text-white' : 'hover:bg-white/5 hover:text-white' }} flex items-center px-4 py-3 text-sm rounded-2xl transition-all group">
                <i class="fa-solid fa-user-gear w-6 opacity-50 group-hover:opacity-100"></i> <span class="ml-3 font-semibold">Pengguna</span>
            </a>
            @endif
            @if($can('perangkat-sistem'))
            <a href="javascript:void(0)" onclick="switchPage('perangkat-sistem', this)" class="menu-link {{ isset($page) && $page == 'perangkat-sistem' ? 'menu-active text-white' : 'hover:bg-white/5 hover:text-white' }} flex items-center px-4 py-3 text-sm rounded-2xl transition-all group">
                <i class="fa-solid fa-boxes-stacked w-6 opacity-50 group-hover:opacity-100"></i> <span class="ml-3 font-semibold">Aset RT</span>
            </a>
            @endif
            @endif

            @if($can('laporan-keuangan') || $can('laporan-iuran') || $can('laporan-kas') || $can('export-laporan'))
            <div class="pt-8 pb-2 px-4"><p class="text-[10px] font-black text-gray-600 uppercase tracking-[2.5px]">Laporan</p></div>

            @if($can('laporan-keuangan'))
            <a href="javascript:void(0)" onclick="switchPage('laporan-keuangan', this)" class="menu-link {{ isset($page) && $page == 'laporan-keuangan' ? 'menu-active text-white' : 'hover:bg-white/5 hover:text-white' }} flex items-center px-4 py-3 text-sm rounded-2xl transition-all group">
                <i class="fa-solid fa-chart-line w-6 opacity-50 group-hover:opacity-100"></i> <span class="ml-3 font-semibold">Laporan Keuangan</span>
            </a>
            @endif
            @if($can('laporan-iuran'))
            <a href="javascript:void(0)" onclick="switchPage('laporan-iuran', this)" class="menu-link {{ isset($page) && $page == 'laporan-iuran' ? 'menu-active text-white' : 'hover:bg-white/5 hover:text-white' }} flex items-center px-4 py-3 text-sm rounded-2xl transition-all group">
                <i class="fa-solid fa-file-invoice w-6 opacity-50 group-hover:opacity-100"></i> <span class="ml-3 font-semibold">Laporan Iuran</span>
            </a>
            @endif
            @if($can('laporan-kas'))
            <a href="javascript:void(0)" onclick="switchPage('laporan-kas', this)" class="menu-link {{ isset($page) && $page == 'laporan-kas' ? 'menu-active text-white' : 'hover:bg-white/5 hover:text-white' }} flex items-center px-4 py-3 text-sm rounded-2xl transition-all group">
                <i class="fa-solid fa-vault w-6 opacity-50 group-hover:opacity-100"></i> <span class="ml-3 font-semibold">Laporan Kas</span>
            </a>
            @endif
            @if($can('export-laporan'))
            <a href="javascript:void(0)" onclick="switchPage('export-laporan', this)" class="menu-link {{ isset($page) && $page == 'export-laporan' ? 'menu-active text-white' : 'hover:bg-white/5 hover:text-white' }} flex items-center px-4 py-3 text-sm rounded-2xl transition-all group">
                <i class="fa-solid fa-file-export w-6 opacity-50 group-hover:opacity-100"></i> <span class="ml-3 font-semibold">Export Laporan</span>
            </a>
            @endif
            @endif

            @if($can('pengaturan') || $can('backup-restore') || $can('aktivitas-pengguna'))
            <div class="pt-8 pb-2 px-4"><p class="text-[10px] font-black text-gray-600 uppercase tracking-[2.5px]">Pengaturan</p></div>

            @if($can('pengaturan'))
            <a href="javascript:void(0)" onclick="switchPage('pengaturan', this)" class="menu-link {{ isset($page) && $page == 'pengaturan' ? 'menu-active text-white' : 'hover:bg-white/5 hover:text-white' }} flex items-center px-4 py-3 text-sm rounded-2xl transition-all group">
                <i class="fa-solid fa-gears w-6 opacity-50 group-hover:opacity-100"></i> <span class="ml-3 font-semibold">Pengaturan</span>
            </a>
            @endif
            @if($can('backup-restore'))
            <a href="javascript:void(0)" onclick="switchPage('backup-restore', this)" class="menu-link {{ isset($page) && $page == 'backup-restore' ? 'menu-active text-white' : 'hover:bg-white/5 hover:text-white' }} flex items-center px-4 py-3 text-sm rounded-2xl transition-all group">
                <i class="fa-solid fa-cloud-arrow-up w-6 opacity-50 group-hover:opacity-100"></i> <span class="ml-3 font-semibold">Backup & Restore</span>
            </a>
            @endif
            @if($can('aktivitas-pengguna'))
            <a href="javascript:void(0)" onclick="switchPage('aktivitas-pengguna', this)" class="menu-link {{ isset($page) && $page == 'aktivitas-pengguna' ? 'menu-active text-white' : 'hover:bg-white/5 hover:text-white' }} flex items-center px-4 py-3 text-sm rounded-2xl transition-all group">
                <i class="fa-solid fa-clock-rotate-left w-6 opacity-50 group-hover:opacity-100"></i> <span class="ml-3 font-semibold">Aktivitas Pengguna</span>
            </a>
            @endif
            @endif
        </nav>

        <div class="p-6 border-t border-white/5 bg-[#0F172A] sticky bottom-0 shrink-0 flex flex-col gap-3">
            {{-- Tombol kembali ke halaman publik --}}
            <a href="{{ route('welcome') }}" class="flex items-center w-full px-5 py-3 bg-blue-500/10 text-blue-400 hover:bg-blue-500 hover:text-white rounded-[24px] transition-all font-bold text-sm group">
                <i class="fa-solid fa-globe mr-3 group-hover:-translate-x-1 transition-transform duration-300"></i>
                <span>Halaman Publik</span>
                <i class="fa-solid fa-arrow-up-right-from-square ml-auto text-xs opacity-60 group-hover:opacity-100"></i>
            </a>
            {{-- Tombol logout --}}
            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center w-full px-5 py-3 bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white rounded-[24px] transition-all font-bold text-sm group">
                    <i class="fa-solid fa-power-off mr-3 group-hover:rotate-90 transition-transform duration-300"></i>
                    <span>Keluar Akun</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="h-20 bg-white border-b border-gray-100 flex items-center justify-between px-4 md:px-10 shrink-0">
            <div class="flex items-center gap-3">
                <!-- Sidebar Toggle Button -->
                <button onclick="toggleSidebar(true)" class="flex items-center justify-center px-4 py-2 bg-slate-50 hover:bg-slate-100 text-slate-700 rounded-xl mr-2 focus:outline-none transition-all border border-slate-200 shadow-sm gap-2">
                    <i class="fa-solid fa-bars text-sm"></i>
                    <span class="text-xs font-bold uppercase tracking-wider hidden sm:inline">Menu Halaman</span>
                </button>
                <div class="flex flex-col">
                    <h2 class="text-base md:text-xl font-bold text-gray-800 tracking-tight italic line-clamp-1">Halo, {{ Auth::user()->name }} 👋</h2>
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                        <p class="text-[9px] md:text-[10px] text-gray-400 font-bold uppercase tracking-[1.5px]">Status: Verified Online</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 md:gap-6">
                <div class="bg-gray-50 p-2.5 rounded-xl text-gray-400 relative cursor-pointer hover:bg-gray-100 transition hidden sm:block">
                </div>

                <div class="flex items-center gap-3 md:gap-4 pl-3 md:pl-6 border-l border-gray-100">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-black text-gray-800 leading-none lowercase tracking-tighter">{{ Auth::user()->name }}</p>
                        <p class="text-[9px] text-blue-600 font-black uppercase mt-1 italic tracking-widest leading-none">{{ Auth::user()->role }}</p>
                    </div>
                    <img src="{{ Auth::user()->photo ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=2563EB&color=fff' }}" class="h-10 w-10 md:h-11 md:w-11 rounded-2xl shadow-md border-2 border-white bg-gray-50 object-cover" alt="Avatar">
                </div>
            </div>
        </header>

        <main id="main-content" class="flex-1 overflow-y-auto p-4 md:p-10 pb-28 bg-[#F8FAFC]">
            @php
                $currentView = $resolvedView ?? ('admin.partials.' . ($page ?? 'dashboard'));
            @endphp
            @if(view()->exists($currentView))
                @include($currentView)
            @else
                <div class="p-10 bg-white rounded-[2.5rem] shadow-sm border border-gray-100 text-center min-h-[400px] flex flex-col justify-center items-center">
                    <i class="fa-solid fa-code text-5xl text-blue-200 mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-800">Konten Kosong</h3>
                    <p class="text-gray-500 mt-2">Silakan buat file <code class="text-red-400 bg-red-50 px-2 py-1 rounded">{{ str_replace('.', '/', $currentView) }}.blade.php</code> terlebih dahulu.</p>
                </div>
            @endif
        </main>
    </div>

    <!-- Floating Bottom Navigation Bar (Mobile Only) -->
    <div class="fixed bottom-0 left-0 right-0 z-40 p-4 md:hidden">
        <div class="max-w-md mx-auto bg-white/90 backdrop-blur-md border border-gray-100 rounded-2xl shadow-[0_-8px_30px_rgba(0,0,0,0.08)] flex items-center justify-around py-2 px-1">
            <!-- Tab 1: Dashboard -->
            <button onclick="switchPage('dashboard', this);" class="bottom-tab-link flex flex-col items-center gap-1 text-blue-600">
                <i class="fa-solid fa-th-large text-base"></i>
                <span class="text-[9px] font-bold">Beranda</span>
            </button>
            
            <!-- Tab 2: Tagihan -->
            @if($can('tagihan-warga'))
            <button onclick="switchPage('tagihan-warga', this);" class="bottom-tab-link flex flex-col items-center gap-1 text-gray-400 hover:text-blue-600">
                <i class="fa-solid fa-wallet text-base"></i>
                <span class="text-[9px] font-bold">Tagihan</span>
            </button>
            @endif
            
            <!-- Tab 3: Surat Online -->
            @if($can('surat-online'))
            <button onclick="switchPage('surat-online', this);" class="bottom-tab-link flex flex-col items-center gap-1 text-gray-400 hover:text-blue-600">
                <i class="fa-solid fa-envelope text-base"></i>
                <span class="text-[9px] font-bold">Surat</span>
            </button>
            @endif
            
            <!-- Tab 4: Semua Menu (Opens Sheet) -->
            <button onclick="toggleMobileMenuSheet(true)" class="flex flex-col items-center gap-1 text-gray-400 hover:text-blue-600">
                <i class="fa-solid fa-ellipsis text-base"></i>
                <span class="text-[9px] font-bold">Menu</span>
            </button>
            
            <!-- Tab 5: Pengaturan -->
            @if($can('pengaturan'))
            <button onclick="switchPage('pengaturan', this);" class="bottom-tab-link flex flex-col items-center gap-1 text-gray-400 hover:text-blue-600">
                <i class="fa-solid fa-gears text-base"></i>
                <span class="text-[9px] font-bold">Setelan</span>
            </button>
            @endif
        </div>
    </div>

    <!-- Floating Bottom Navigation Sheet (Mobile Only) -->
    <div id="mobile-menu-sheet" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm hidden flex items-end justify-center transition-all duration-300">
        <!-- Sheet Backdrop -->
        <div class="absolute inset-0" onclick="toggleMobileMenuSheet(false)"></div>
        <!-- Sheet Content -->
        <div class="relative w-full max-w-md bg-white rounded-t-[2rem] p-5 shadow-2xl max-h-[85vh] overflow-y-auto transform translate-y-full transition-transform duration-300">
            <!-- Handle bar -->
            <div class="w-12 h-1 bg-gray-200 rounded-full mx-auto mb-4"></div>
            
            <div class="flex items-center justify-between mb-5 border-b border-gray-50 pb-3">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                        <i class="fa-solid fa-grip text-xs"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-gray-800 text-sm">Semua Fitur & Layanan</h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-0.5">Role: {{ Auth::user()->role }}</p>
                    </div>
                </div>
                <button onclick="toggleMobileMenuSheet(false)" class="w-7 h-7 rounded-full bg-gray-50 text-gray-400 flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
            
            <div class="space-y-6">
                <!-- Section 1: Transaksi & Keuangan -->
                @if($can('tagihan-warga') || $can('status-pembayaran') || $can('qris-va') || $can('pemasukan') || $can('pengeluaran') || $can('transaksi') || $can('kategori'))
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2.5">Keuangan & Pembayaran</h4>
                    <div class="grid grid-cols-3 gap-2">
                        @if($can('tagihan-warga'))
                        <button onclick="switchPage('tagihan-warga'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-wallet text-blue-500 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">{{ (Auth::user()->role == 'Warga') ? 'Tagihan Saya' : 'Tagihan Warga' }}</span>
                        </button>
                        @endif
                        @if($can('status-pembayaran'))
                        <button onclick="switchPage('status-pembayaran'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-circle-check text-blue-500 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Status Bayar</span>
                        </button>
                        @endif
                        @if($can('qris-va'))
                        <button onclick="switchPage('qris-va'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-qrcode text-blue-500 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">QRIS / VA</span>
                        </button>
                        @endif
                        @if($can('pemasukan'))
                        <button onclick="switchPage('pemasukan'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-arrow-down-long text-emerald-500 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Pemasukan</span>
                        </button>
                        @endif
                        @if($can('pengeluaran'))
                        <button onclick="switchPage('pengeluaran'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-arrow-up-long text-red-500 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Pengeluaran</span>
                        </button>
                        @endif
                        @if($can('transaksi'))
                        <button onclick="switchPage('transaksi'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-shuffle text-indigo-500 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Transaksi</span>
                        </button>
                        @endif
                        @if($can('kategori'))
                        <button onclick="switchPage('kategori'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-folder-tree text-amber-500 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Kategori</span>
                        </button>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Section 2: Layanan & Interaksi -->
                @if($can('surat-online') || $can('pengumuman') || $can('koperasi') || $can('bank-sampah') || $can('umkm') || $can('posyandu') || $can('keamanan') || $can('kegiatan') || $can('rukem') || $can('aspirasi'))
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2.5">Layanan & Interaksi Warga</h4>
                    <div class="grid grid-cols-3 gap-2">
                        @if($can('surat-online'))
                        <button onclick="switchPage('surat-online'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-envelope-open-text text-blue-500 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Surat Online</span>
                        </button>
                        @endif
                        @if($can('pengumuman'))
                        <button onclick="switchPage('pengumuman'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-bullhorn text-amber-500 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Pengumuman</span>
                        </button>
                        @endif
                        @if($can('koperasi'))
                        <button onclick="switchPage('koperasi'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-store text-emerald-500 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Koperasi</span>
                        </button>
                        @endif
                        @if($can('bank-sampah'))
                        <button onclick="switchPage('bank-sampah'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-recycle text-green-600 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Bank Sampah</span>
                        </button>
                        @endif
                        @if($can('umkm'))
                        <button onclick="switchPage('umkm'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-shop text-rose-500 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">UMKM</span>
                        </button>
                        @endif
                        @if($can('posyandu'))
                        <button onclick="switchPage('posyandu'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-heart-pulse text-rose-600 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Posyandu</span>
                        </button>
                        @endif
                        @if($can('keamanan'))
                        <button onclick="switchPage('keamanan'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-shield-halved text-slate-700 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Keamanan</span>
                        </button>
                        @endif
                        @if($can('kegiatan'))
                        <button onclick="switchPage('kegiatan'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-calendar-check text-purple-600 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Kegiatan</span>
                        </button>
                        @endif
                        @if($can('rukem'))
                        <button onclick="switchPage('rukem'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-hands-holding-child text-amber-700 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Rukem</span>
                        </button>
                        @endif
                        @if($can('aspirasi'))
                        <button onclick="switchPage('aspirasi'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-comment-dots text-sky-500 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Aspirasi</span>
                        </button>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Section 3: Master Data & Laporan -->
                @if($can('data-warga') || $can('data-iuran') || $can('data-pengurus-rt') || $can('data-rt') || $can('pengguna') || $can('perangkat-sistem') || $can('laporan-keuangan') || $can('laporan-iuran') || $can('laporan-kas') || $can('export-laporan'))
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2.5">Master Data & Laporan</h4>
                    <div class="grid grid-cols-3 gap-2">
                        @if($can('data-warga'))
                        <button onclick="switchPage('data-warga'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-users text-teal-600 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Data Warga</span>
                        </button>
                        @endif
                        @if($can('data-iuran'))
                        <button onclick="switchPage('data-iuran'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-sack-dollar text-emerald-600 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Data Iuran</span>
                        </button>
                        @endif
                        @if($can('data-pengurus-rt'))
                        <button onclick="switchPage('data-pengurus-rt'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-user-tie text-blue-700 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Pengurus RT</span>
                        </button>
                        @endif
                        @if($can('data-rt'))
                        <button onclick="switchPage('data-rt'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-database text-slate-600 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Data RT</span>
                        </button>
                        @endif
                        @if($can('pengguna'))
                        <button onclick="switchPage('pengguna'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-user-gear text-indigo-600 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Pengguna</span>
                        </button>
                        @endif
                        @if($can('perangkat-sistem'))
                        <button onclick="switchPage('perangkat-sistem'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-boxes-stacked text-amber-700 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Aset RT</span>
                        </button>
                        @endif
                        @if($can('laporan-keuangan'))
                        <button onclick="switchPage('laporan-keuangan'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-chart-line text-blue-600 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Lap Keuangan</span>
                        </button>
                        @endif
                        @if($can('laporan-iuran'))
                        <button onclick="switchPage('laporan-iuran'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-file-invoice text-emerald-600 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Lap Iuran</span>
                        </button>
                        @endif
                        @if($can('laporan-kas'))
                        <button onclick="switchPage('laporan-kas'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-vault text-amber-600 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Lap Arus Kas</span>
                        </button>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Section 4: Sistem & Lainnya -->
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2.5">Sistem & Akun</h4>
                    <div class="grid grid-cols-3 gap-2">
                        <button onclick="switchPage('pengaturan'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-gears text-gray-500 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Pengaturan</span>
                        </button>
                        @if($can('backup-restore'))
                        <button onclick="switchPage('backup-restore'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-cloud-arrow-up text-cyan-600 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Backup</span>
                        </button>
                        @endif
                        @if($can('aktivitas-pengguna'))
                        <button onclick="switchPage('aktivitas-pengguna'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-clock-rotate-left text-slate-500 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Log Aktivitas</span>
                        </button>
                        @endif
                        <a href="{{ route('welcome') }}" class="flex flex-col items-center justify-center text-center p-2.5 bg-blue-50 hover:bg-blue-100 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-globe text-blue-600 text-base"></i>
                            <span class="text-[9px] font-bold text-blue-700 truncate w-full">Web Publik</span>
                        </a>
                        <button onclick="document.getElementById('logout-form').submit();" class="flex flex-col items-center text-center p-2.5 bg-red-50 hover:bg-red-100 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-power-off text-red-600 text-base"></i>
                            <span class="text-[9px] font-bold text-red-700 truncate w-full">Keluar</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ==========================================
        // 0. FUNGSI TOGGLE SIDEBAR MOBILE & DESKTOP
        // ==========================================
        window.toggleSidebar = function(forceOpen) {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            if (!sidebar || !backdrop) return;

            const isCurrentlyClosed = sidebar.classList.contains('-translate-x-full');
            const shouldOpen = typeof forceOpen === 'boolean' ? forceOpen : isCurrentlyClosed;

            if (shouldOpen) {
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.remove('translate-x-0');
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        };

        // ==========================================
        // 0.5. FUNGSI TOGGLE MOBILE MENU SHEET
        // ==========================================
        window.toggleMobileMenuSheet = function(isOpen) {
            const sheet = document.getElementById('mobile-menu-sheet');
            if(!sheet) return;
            const content = sheet.querySelector('.relative');
            if (isOpen) {
                sheet.classList.remove('hidden');
                setTimeout(() => {
                    content.classList.remove('translate-y-full');
                    content.classList.add('translate-y-0');
                }, 10);
            } else {
                content.classList.remove('translate-y-0');
                content.classList.add('translate-y-full');
                setTimeout(() => {
                    sheet.classList.add('hidden');
                }, 300);
            }
        };

        // ==========================================
        // 1. FUNGSI NAVIGASI AJAX
        // ==========================================
        function executeScripts(container) {
            const scripts = Array.from(container.querySelectorAll('script'));
            scripts.forEach(oldScript => {
                try {
                    if (oldScript.src) {
                        if (!document.querySelector(`script[src="${oldScript.src}"]`)) {
                            const newScript = document.createElement('script');
                            Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
                            document.head.appendChild(newScript);
                        }
                    } else {
                        const newScript = document.createElement('script');
                        Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
                        newScript.text = oldScript.innerHTML;
                        document.body.appendChild(newScript);
                        document.body.removeChild(newScript);
                    }
                } catch (scriptError) {
                    console.warn('Inline script execution warning:', scriptError);
                }
                oldScript.remove();
            });
        }

        function switchPage(pageName, element) {
            // Auto hide sidebar on page switch (both mobile and desktop)
            try { toggleSidebar(false); } catch(e) {}

            const mainContent = document.getElementById('main-content');
            if (!mainContent) return;

            // Reset desktop sidebar links
            document.querySelectorAll('.menu-link').forEach(item => {
                item.classList.remove('menu-active', 'text-white');
                item.classList.add('hover:bg-white/5', 'hover:text-white');
            });

            // Reset bottom nav tabs active state
            document.querySelectorAll('.bottom-tab-link').forEach(item => {
                item.classList.remove('text-blue-600');
                item.classList.add('text-gray-400');
            });

            // Find and activate the correct sidebar item
            const sidebarElement = element && element.classList.contains('menu-link') 
                ? element 
                : (document.querySelector(`.menu-link[onclick*="'${pageName}'"]`) || document.querySelector(`.menu-link[onclick*='"${pageName}"']`));
            if (sidebarElement) {
                sidebarElement.classList.add('menu-active', 'text-white');
                sidebarElement.classList.remove('hover:bg-white/5', 'hover:text-white');
            }

            // Find and activate the correct bottom nav tab
            const bottomTabElement = element && element.classList.contains('bottom-tab-link')
                ? element
                : (document.querySelector(`.bottom-tab-link[onclick*="'${pageName}'"]`) || document.querySelector(`.bottom-tab-link[onclick*='"${pageName}"']`));
            if (bottomTabElement) {
                bottomTabElement.classList.add('text-blue-600');
                bottomTabElement.classList.remove('text-gray-400');
            }

            mainContent.innerHTML = `
                <div class="flex flex-col items-center justify-center h-full min-h-[400px] space-y-4">
                    <div class="w-12 h-12 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                    <p class="text-gray-400 font-bold italic animate-pulse tracking-widest uppercase text-xs">MENGAKSES HALAMAN ${pageName.replace(/-/g, ' ').toUpperCase()}...</p>
                </div>
            `;

            fetch(`/${pageName}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(async response => {
                if (response.status === 401) {
                    window.location.href = '/login';
                    return;
                }
                const text = await response.text();
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: Halaman tidak ditemukan atau terjadi kesalahan server.`);
                }
                return text;
            })
            .then(html => {
                if (!html) return;
                mainContent.innerHTML = html;
                window.history.pushState({}, '', `/${pageName}`);

                try {
                    executeScripts(mainContent);
                } catch (scriptErr) {
                    console.warn('Script execution notice:', scriptErr);
                }

                // Pemicu Grafik Dashboard
                if (pageName === 'dashboard' && typeof window.renderDashboard === 'function') {
                    try {
                        window.renderDashboard();
                    } catch (dashErr) {
                        console.warn('Dashboard render notice:', dashErr);
                    }
                }
            })
            .catch(error => {
                mainContent.innerHTML = `
                    <div class="p-10 bg-white rounded-[2.5rem] border border-red-100 shadow-sm text-center min-h-[400px] flex flex-col justify-center items-center">
                        <i class="fa-solid fa-triangle-exclamation text-5xl text-red-400 mb-4"></i>
                        <h3 class="text-xl font-bold text-gray-800">Gagal Memuat Halaman</h3>
                        <p class="text-gray-500 mt-2 max-w-lg">${error.message || 'Pastikan koneksi lancar dan route halaman sudah terdaftar.'}</p>
                        <button onclick="switchPage('${pageName}', document.querySelector('.bottom-tab-link[onclick*=\\'${pageName}\\']') || document.querySelector('.menu-active'))" class="mt-4 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition shadow-md shadow-blue-500/20">
                            Coba Lagi
                        </button>
                    </div>`;
            });
        }

        // ==========================================
        // 2. FUNGSI TOGGLE PASSWORD PENGATURAN
        // ==========================================
        window.togglePasswordPengaturan = function() {
            let inputPass = document.getElementById('input-password');
            let iconMata = document.getElementById('icon-mata');
            if(inputPass && iconMata) {
                if (inputPass.type === "password") {
                    inputPass.type = "text";
                    iconMata.classList.remove('fa-eye');
                    iconMata.classList.add('fa-eye-slash');
                } else {
                    inputPass.type = "password";
                    iconMata.classList.remove('fa-eye-slash');
                    iconMata.classList.add('fa-eye');
                }
            }
        };

        // ==========================================
        // 3. FUNGSI AJAX SIMPAN DATA UMUM
        // ==========================================
        function simpanDataUmum(event, formId, pageToReload) {
            event.preventDefault();

            let form = document.getElementById(formId);
            if (!form) return;
            if (!form.reportValidity()) {
                return;
            }

            let formData = new FormData(form);
            let submitBtn = form.querySelector('button[type="submit"]') || form.querySelector('button[onclick*="simpanDataUmum"]');
            let originalBtnText = submitBtn ? submitBtn.innerHTML : 'Simpan';
            let targetUrl = form.getAttribute('action');

            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Menyimpan...';
                submitBtn.disabled = true;
            }

            fetch(targetUrl, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                let modal = form.closest('[id^="modal-"]');
                if (modal) {
                    modal.classList.add('hidden');
                }

                form.reset();
                alert(data.message || 'Data berhasil disimpan!');

                if (pageToReload === 'pengaturan') {
                    window.location.reload();
                } else {
                    switchPage(pageToReload, document.querySelector(`.menu-link[onclick*='${pageToReload}']`));
                }
            })
            .catch(error => {
                if (error.errors) {
                    let messages = Object.values(error.errors).flat().join('\n');
                    alert("Gagal menyimpan:\n" + messages);
                } else {
                    alert(error.message || "Gagal terhubung ke server / Terjadi kesalahan sistem.");
                }
                console.error(error);
            })
            .finally(() => {
                if (submitBtn) {
                    submitBtn.innerHTML = originalBtnText;
                    submitBtn.disabled = false;
                }
            });
        }
    </script>
</body>
</html>
