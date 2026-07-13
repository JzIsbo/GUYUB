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

    <!-- Sidebar Backdrop Overlay for Mobile -->
    <div id="sidebar-backdrop" onclick="toggleSidebar(false)" class="hidden fixed inset-0 bg-black/50 z-40 md:hidden transition-opacity duration-300"></div>

    <aside id="sidebar" class="w-[300px] bg-[#0F172A] text-[#94A3B8] flex flex-col shrink-0 sidebar-scroll overflow-y-auto fixed inset-y-0 left-0 z-50 transform -translate-x-full md:translate-x-0 md:static transition-transform duration-300 ease-in-out">
        <!-- Close button inside sidebar for Mobile -->
        <button onclick="toggleSidebar(false)" class="md:hidden p-2 text-gray-400 hover:text-white rounded-lg absolute top-6 right-6 focus:outline-none transition-colors">
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
                <!-- Hamburger Menu Button -->
                <button onclick="toggleSidebar(true)" class="md:hidden p-2 text-gray-600 hover:bg-gray-100 rounded-xl mr-1 focus:outline-none transition-colors">
                    <i class="fa-solid fa-bars text-xl"></i>
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

        <main id="main-content" class="flex-1 overflow-y-auto p-4 md:p-10 bg-[#F8FAFC]">
            @if(isset($page) && view()->exists('admin.partials.' . $page))
                @include('admin.partials.' . $page)
            @else
                <div class="p-10 bg-white rounded-[2.5rem] shadow-sm border border-gray-100 text-center min-h-[400px] flex flex-col justify-center items-center">
                    <i class="fa-solid fa-code text-5xl text-blue-200 mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-800">Konten Kosong</h3>
                    <p class="text-gray-500 mt-2">Silakan buat file <code class="text-red-400 bg-red-50 px-2 py-1 rounded">resources/views/admin/partials/{{ $page ?? 'dashboard' }}.blade.php</code> terlebih dahulu.</p>
                </div>
            @endif
        </main>
    </div>

    <script>
        // ==========================================
        // 0. FUNGSI TOGGLE SIDEBAR MOBILE
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
            // Close sidebar on mobile after selecting a page
            if (window.innerWidth < 768) {
                window.toggleSidebar(false);
            }

            const mainContent = document.getElementById('main-content');
            if (!mainContent) return;

            document.querySelectorAll('.menu-link').forEach(item => {
                item.classList.remove('menu-active', 'text-white');
                item.classList.add('hover:bg-white/5', 'hover:text-white');
            });

            if (element) {
                element.classList.add('menu-active', 'text-white');
                element.classList.remove('hover:bg-white/5', 'hover:text-white');
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
                        <button onclick="switchPage('${pageName}', document.querySelector('.menu-active'))" class="mt-4 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition shadow-md shadow-blue-500/20">
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
        // 3. FUNGSI AJAX SIMPAN DATA UMUM (TERBARU)
        // ==========================================
        function simpanDataUmum(event, formId, pageToReload) {
            event.preventDefault();

            let form = document.getElementById(formId);
            if (!form) return;
                    // ==============================================================
            // JURUS SAKTI: Membangunkan kembali validasi form bawaan browser
            // ==============================================================
            if (!form.reportValidity()) {
                return; // Hentikan proses AJAX jika masih ada input yang kosong/salah!
            }

            let formData = new FormData(form);

            // Cari tombol type="submit" ATAU tombol biasa yang memanggil fungsi simpanDataUmum
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
                // Tutup modal jika ada
                let modal = form.closest('[id^="modal-"]');
                if (modal) {
                    modal.classList.add('hidden');
                }

                form.reset();
                alert(data.message || 'Data berhasil disimpan!');

                // Refresh halaman partial via AJAX
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
