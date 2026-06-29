{{-- resources/views/welcome.blade.php --}}

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Aplikasi RT</title>

    {{-- Tailwind --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Icons --}}
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>

    <style>

        html {
            scroll-behavior: smooth;
        }

        *{
            font-family: 'Poppins', sans-serif;
        }

        body{
            overflow-x: hidden;
            background: #f5f8ff;
        }

        .hero-bg{
            background:
            linear-gradient(to right,
            rgba(5,25,75,.94),
            rgba(30,64,175,.82)),
            url('https://images.unsplash.com/photo-1564013799919-ab600027ffc6?q=80&w=1974&auto=format&fit=crop');

            background-size: cover;
            background-position: center;
        }

        .glass{
            background: rgba(255,255,255,.10);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,.12);
        }

        .btn-primary{
            background: linear-gradient(90deg,#2563eb,#3b82f6);
        }

        .btn-primary:hover{
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(37,99,235,.30);
        }

        .feature-card{
            transition: .3s;
        }

        .feature-card:hover{
            transform: translateY(-5px);
        }

        /* =========================
           PREMIUM PUBLIC TAB STYLING
        ========================= */
        .public-tab-btn {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
        .public-tab-btn:hover {
            transform: translateY(-2px);
            background-color: #f8fafc;
        }
        .public-tab-btn.active {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8) !important;
            color: white !important;
            border-color: transparent !important;
            box-shadow: 0 12px 24px -6px rgba(37, 99, 235, 0.35) !important;
        }
        .public-card {
            background: #ffffff;
            border: 1px solid #f1f5f9;
            transition: all 0.3s ease;
        }
        .public-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 30px -10px rgba(15, 23, 42, 0.08);
            border-color: rgba(59, 130, 246, 0.2);
        }

        /* =========================
           UKURAN DESKTOP FIX
        ========================= */

        .container-custom{
            max-width: 1120px;
            margin: auto;
        }

        .hero-title{
            font-size: 46px;
            line-height: 1.2;
        }

        .hero-desc{
            font-size: 15px;
            line-height: 30px;
        }

        .dashboard-card{
            max-width: 350px;
        }

        .section-title{
            font-size: 34px;
        }

        /* =========================
           LAPTOP
        ========================= */

        @media (max-width:1366px){

            .hero-title{
                font-size: 40px;
            }

            .dashboard-card{
                max-width: 320px;
            }

        }

        /* =========================
           TABLET
        ========================= */

        @media (max-width:1024px){

            .hero-title{
                font-size: 36px;
            }

        }

        /* =========================
           MOBILE
        ========================= */

        @media (max-width:768px){

            .hero-title{
                font-size: 30px;
                text-align: center;
            }

            .hero-desc{
                text-align: center;
            }

            .mobile-center{
                justify-content: center;
            }

            .stats-grid{
                grid-template-columns: 1fr;
            }

            .section-title{
                font-size: 28px;
            }

        }

    </style>

</head>

<body>

{{-- HERO --}}
<section id="beranda" class="hero-bg min-h-screen relative overflow-hidden">

    {{-- SHAPES --}}
    <div class="absolute top-[-100px] right-[-100px] w-[220px] h-[220px] rounded-full bg-blue-400/20"></div>

    <div class="absolute bottom-[-100px] left-[-100px] w-[220px] h-[220px] rounded-full bg-blue-300/20"></div>

    {{-- NAVBAR --}}
    <nav class="relative z-20 container-custom flex items-center justify-between px-5 lg:px-8 py-4 transition-all duration-300">

        {{-- LOGO --}}
        <div class="flex items-center gap-3">

            <div class="w-11 h-11 rounded-2xl bg-white/10 flex items-center justify-center">

                <i class="fa-solid fa-people-roof text-lg text-white"></i>

            </div>

            <div>

                <h1 class="text-white text-lg font-bold">
                    Aplikasi RT
                </h1>

                <p class="text-blue-100 text-[11px]">
                    Sistem Informasi Warga
                </p>

            </div>

        </div>

        {{-- MENU --}}
        <div class="hidden lg:flex items-center gap-7 text-white text-sm font-medium">

            <a href="#beranda" class="hover:text-blue-300 transition nav-link" data-section="beranda">
                Beranda
            </a>

            <a href="#fitur" class="hover:text-blue-300 transition nav-link" data-section="fitur">
                Fitur & Layanan
            </a>

            <a href="#tentang" class="hover:text-blue-300 transition nav-link" data-section="tentang">
                Tentang
            </a>

            <a href="#kontak" class="hover:text-blue-300 transition nav-link" data-section="kontak">
                Kontak
            </a>

        </div>

        {{-- LOGIN --}}
        <a href="{{ route('login') }}"
           class="px-5 py-2 rounded-xl bg-white text-blue-700 text-sm font-semibold hover:bg-blue-50 transition">

            Login

        </a>

    </nav>

    {{-- CONTENT --}}
    <div class="relative z-10 container-custom px-5 lg:px-8 pt-4 lg:pt-10 pb-16">

        <div class="grid lg:grid-cols-2 gap-10 items-center">

            {{-- LEFT --}}
            <div>

                {{-- BADGE --}}
                <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full glass text-white mb-6">

                    <i class="fa-solid fa-shield-halved text-blue-300"></i>

                    <span class="text-xs">
                        Aman • Modern • Terintegrasi
                    </span>

                </div>

                {{-- TITLE --}}
                <h1 class="hero-title text-white font-extrabold">

                    Digitalisasi
                    <span class="text-blue-300">
                        Pelayanan RT
                    </span>

                </h1>

                {{-- DESC --}}
                <p class="hero-desc mt-6 text-blue-100 max-w-[520px]">

                    Kelola data warga, iuran, surat pengantar,
                    pengumuman, dan aktivitas lingkungan
                    dengan sistem modern berbasis web.

                </p>

                {{-- BUTTON --}}
                <div class="flex flex-wrap mobile-center gap-4 mt-8">

                    <a href="{{ route('login') }}"
                       class="btn-primary px-6 py-3 rounded-xl text-white text-sm font-semibold transition-all duration-300">

                        <i class="fa-solid fa-right-to-bracket mr-2"></i>
                        Masuk Sekarang

                    </a>

                    <a href="#fitur"
                       class="px-6 py-3 rounded-xl glass text-white text-sm font-semibold hover:bg-white/20 transition">

                        Lihat Fitur

                    </a>

                </div>

                {{-- STATS --}}
                <div class="stats-grid grid grid-cols-3 gap-4 mt-10">

                    <div class="glass rounded-2xl p-4 text-center">

                        <h2 class="text-white text-xl font-bold">
                            24/7
                        </h2>

                        <p class="text-blue-100 text-[11px] mt-2">
                            Akses Sistem
                        </p>

                    </div>

                    <div class="glass rounded-2xl p-4 text-center">

                        <h2 class="text-white text-xl font-bold">
                            100%
                        </h2>

                        <p class="text-blue-100 text-[11px] mt-2">
                            Digital
                        </p>

                    </div>

                    <div class="glass rounded-2xl p-4 text-center">

                        <h2 class="text-white text-xl font-bold">
                            Aman
                        </h2>

                        <p class="text-blue-100 text-[11px] mt-2">
                            Data Terenkripsi
                        </p>

                    </div>

                </div>

            </div>

            {{-- RIGHT --}}
            <div class="flex justify-center">

                <div class="dashboard-card glass rounded-[28px] p-6 w-full">

                    {{-- TOP --}}
                    <div class="flex items-center justify-between">

                        <div>

                            <h2 class="text-white text-lg font-bold">
                                Dashboard RT
                            </h2>

                            <p class="text-blue-100 text-[11px] mt-1">
                                Monitoring lingkungan real-time
                            </p>

                        </div>

                        <div class="w-11 h-11 rounded-2xl bg-blue-500/20 flex items-center justify-center">

                            <i class="fa-solid fa-chart-line text-blue-300 text-lg"></i>

                        </div>

                    </div>

                    {{-- CHART --}}
                    <div class="mt-6 bg-white/10 rounded-2xl p-4">

                        <div class="flex items-end gap-3 h-[120px]">

                            <div class="w-full bg-blue-300 rounded-t-2xl h-[45%]"></div>

                            <div class="w-full bg-blue-400 rounded-t-2xl h-[75%]"></div>

                            <div class="w-full bg-blue-500 rounded-t-2xl h-[55%]"></div>

                            <div class="w-full bg-blue-600 rounded-t-2xl h-[90%]"></div>

                            <div class="w-full bg-blue-700 rounded-t-2xl h-[65%]"></div>

                        </div>

                    </div>

                    {{-- INFO --}}
                    <div class="grid grid-cols-2 gap-4 mt-4">

                        <div class="bg-white/10 rounded-2xl p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-blue-100 text-[11px]">Total Warga</p>
                                    <h3 class="text-white text-xl font-bold mt-2">
                                        {{ $totalWarga ?? 0 }}
                                    </h3>
                                </div>
                                <i class="fa-solid fa-users text-xl text-blue-300"></i>
                            </div>
                        </div>

                        <div class="bg-white/10 rounded-2xl p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-blue-100 text-[11px]">UMKM Aktif</p>
                                    <h3 class="text-white text-xl font-bold mt-2">
                                        {{ $totalUmkm ?? 0 }}
                                    </h3>
                                </div>
                                <i class="fa-solid fa-shop text-xl text-blue-300"></i>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

{{-- INTERACTIVE PUBLIC DIRECTORIES --}}
<div id="informasi-publik"></div>
<section id="fitur" class="py-20 px-5 lg:px-8 bg-white border-t border-gray-100">
    <div class="container-custom">
        
        <div class="text-center mb-16">
            <span class="text-blue-600 font-extrabold text-xs uppercase tracking-widest bg-blue-50 px-4 py-2 rounded-full">Layanan Informasi Terbuka</span>
            <h2 class="section-title font-black text-gray-800 tracking-tight mt-4">Direktori Publik RT</h2>
            <p class="text-gray-500 mt-3 text-sm max-w-xl mx-auto leading-relaxed">
                Informasi dan pelayanan lingkungan warga dapat diakses secara langsung tanpa masuk ke sistem.
            </p>
        </div>

        <!-- Tab Controls -->
        <div class="flex flex-wrap justify-center gap-3 mb-12">
            <button onclick="switchPublicTab('tab-pengumuman', this)" class="public-tab-btn active bg-blue-600 text-white font-bold px-6 py-3.5 rounded-2xl shadow-lg shadow-blue-200 transition-all text-xs flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-bullhorn"></i> Pengumuman
            </button>
            <button onclick="switchPublicTab('tab-umkm', this)" class="public-tab-btn bg-gray-50 hover:bg-gray-100 text-gray-600 font-bold px-6 py-3.5 rounded-2xl transition-all text-xs flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-shop"></i> UMKM Warga
            </button>
            <button onclick="switchPublicTab('tab-kegiatan', this)" class="public-tab-btn bg-gray-50 hover:bg-gray-100 text-gray-600 font-bold px-6 py-3.5 rounded-2xl transition-all text-xs flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-calendar-check"></i> Kegiatan RT
            </button>
            <button onclick="switchPublicTab('tab-posyandu', this)" class="public-tab-btn bg-gray-50 hover:bg-gray-100 text-gray-600 font-bold px-6 py-3.5 rounded-2xl transition-all text-xs flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-heart-pulse"></i> Posyandu
            </button>
            <button onclick="switchPublicTab('tab-ronda', this)" class="public-tab-btn bg-gray-50 hover:bg-gray-100 text-gray-600 font-bold px-6 py-3.5 rounded-2xl transition-all text-xs flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-shield-halved"></i> Jadwal Ronda
            </button>
        </div>

        <!-- Tab Contents -->
        <div class="bg-gray-50/50 rounded-[2.5rem] p-8 border border-gray-100/50 min-h-[300px]">

            <!-- 1. PENGUMUMAN TAB -->
            <div id="tab-pengumuman" class="public-tab-content space-y-6">
                @forelse($announcements ?? [] as $item)
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow transition duration-200">
                    <div class="flex items-center justify-between mb-3">
                        <span class="bg-blue-50 text-blue-600 font-bold px-3 py-1 rounded-full text-[10px] tracking-wide uppercase">{{ $item->status }}</span>
                        <span class="text-xs text-gray-400 font-medium"><i class="fa-regular fa-clock mr-1"></i> {{ date('d-m-Y', strtotime($item->created_at)) }}</span>
                    </div>
                    <h3 class="text-base font-extrabold text-gray-800 mb-2">{{ $item->judul }}</h3>
                    <p class="text-xs text-gray-600 leading-relaxed whitespace-pre-line">{{ $item->isi }}</p>
                </div>
                @empty
                <div class="text-center py-12 text-gray-400 italic">Belum ada pengumuman publik yang aktif.</div>
                @endforelse
            </div>

            <!-- 2. UMKM WARGA TAB -->
            <div id="tab-umkm" class="public-tab-content hidden grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($umkms ?? [] as $item)
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-lg transition duration-300 overflow-hidden group">
                    <div>
                        <div class="h-48 w-full overflow-hidden relative bg-gray-100">
                            <img src="{{ $item->gambar ?? 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?q=80&w=800&auto=format&fit=crop' }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="{{ $item->nama_usaha }}">
                            <span class="absolute top-4 right-4 bg-white/90 backdrop-blur-md text-emerald-600 px-3 py-1 rounded-full text-[10px] font-extrabold flex items-center gap-1 shadow-sm">
                                <i class="fa-solid fa-circle text-[6px]"></i> {{ $item->status }}
                            </span>
                        </div>
                        <div class="p-6">
                            <span class="inline-block bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full text-[10px] font-extrabold tracking-wide uppercase mb-3">{{ $item->kategori }}</span>
                            <h3 class="text-base font-extrabold text-gray-800 mb-1 leading-snug">{{ $item->nama_usaha }}</h3>
                            <p class="text-[11px] font-bold text-gray-400 mb-3"><i class="fa-solid fa-user text-indigo-400 mr-1"></i> Pemilik: {{ $item->pemilik }}</p>
                            <p class="text-xs text-gray-600 line-clamp-3 leading-relaxed">{{ $item->deskripsi ?? 'Usaha lokal warga RT.' }}</p>
                        </div>
                    </div>
                    <div class="px-6 pb-6 pt-2">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->kontak) }}" target="_blank" class="w-full py-3 px-4 rounded-2xl bg-emerald-50 hover:bg-emerald-600 text-emerald-600 hover:text-white font-bold text-xs flex items-center justify-center gap-2 transition duration-200">
                            <i class="fa-brands fa-whatsapp text-base"></i> Hubungi WhatsApp Usaha
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-12 text-gray-400 italic">Belum ada usaha UMKM warga terdaftar.</div>
                @endforelse
            </div>

            <!-- 3. AGENDA KEGIATAN TAB -->
            <div id="tab-kegiatan" class="public-tab-content hidden grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($kegiatans ?? [] as $item)
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-lg transition duration-300 overflow-hidden group">
                    <div>
                        <div class="h-44 w-full overflow-hidden relative bg-gray-100">
                            <img src="{{ $item->gambar ?? 'https://images.unsplash.com/photo-1511632765486-a01980e01a18?q=80&w=800&auto=format&fit=crop' }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="{{ $item->nama_kegiatan }}">
                            <span class="absolute top-4 left-4 bg-teal-600 text-white px-3 py-1 rounded-full text-[10px] font-extrabold shadow-md">
                                <i class="fa-solid fa-clock mr-1"></i> {{ $item->waktu }}
                            </span>
                        </div>
                        <div class="p-6">
                            <span class="text-xs text-gray-400 font-bold block mb-1"><i class="fa-solid fa-calendar text-teal-500 mr-1.5"></i> {{ date('d-m-Y', strtotime($item->tanggal)) }}</span>
                            <h3 class="text-base font-extrabold text-gray-800 mb-2 leading-snug">{{ $item->nama_kegiatan }}</h3>
                            <p class="text-xs font-bold text-gray-500 mb-3"><i class="fa-solid fa-location-dot text-rose-500 mr-1.5"></i> {{ $item->lokasi }}</p>
                            <p class="text-xs text-gray-600 line-clamp-3 leading-relaxed">{{ $item->deskripsi ?? 'Aktivitas kebersamaan warga.' }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-12 text-gray-400 italic">Belum ada kegiatan RT terdekat.</div>
                @endforelse
            </div>

            <!-- 4. JADWAL POSYANDU TAB -->
            <div id="tab-posyandu" class="public-tab-content hidden space-y-4">
                @forelse($posyandus ?? [] as $item)
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4 hover:shadow transition duration-200">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg shadow-sm">
                            <i class="fa-solid fa-heart-pulse"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-extrabold text-gray-800">{{ $item->nama_kegiatan }}</h4>
                            <p class="text-xs text-gray-400 font-medium mt-0.5"><i class="fa-solid fa-location-dot text-rose-500 mr-1"></i> {{ $item->lokasi }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="bg-rose-50 text-rose-600 px-3 py-1.5 rounded-xl text-xs font-extrabold">{{ $item->target_peserta }}</span>
                        <span class="text-xs text-gray-500 font-bold"><i class="fa-solid fa-calendar mr-1"></i> {{ date('d-m-Y', strtotime($item->tanggal)) }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-12 text-gray-400 italic">Belum ada agenda Posyandu terjadwal.</div>
                @endforelse
            </div>

            <!-- 5. JADWAL RONDA TAB -->
            <div id="tab-ronda" class="public-tab-content hidden grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($rondas ?? [] as $item)
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow transition duration-200">
                    <div class="flex items-center justify-between mb-3">
                        <span class="bg-slate-800 text-white px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider">{{ $item->hari }}</span>
                        <span class="text-xs text-gray-400 font-medium">{{ $item->jam_shift }}</span>
                    </div>
                    <h4 class="text-sm font-extrabold text-gray-800 mt-2"><i class="fa-solid fa-user-shield text-slate-500 mr-1"></i> Petugas: {{ $item->petugas_ronda }}</h4>
                    <p class="text-xs text-gray-400 mt-1">Koordinator Shift: <span class="font-bold text-gray-600">{{ $item->koordinator }}</span></p>
                </div>
                @empty
                <div class="col-span-2 text-center py-12 text-gray-400 italic">Belum ada jadwal ronda siskamling malam.</div>
                @endforelse
            </div>

        </div>

    </div>
</section>

{{-- SECTION TENTANG --}}
<section id="tentang" class="py-20 bg-gradient-to-br from-blue-50 via-white to-indigo-50">
    <div class="max-w-6xl mx-auto px-6 lg:px-10">

        {{-- HEADER --}}
        <div class="text-center mb-14">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-blue-100 text-blue-700 rounded-full text-xs font-bold uppercase tracking-widest mb-4">
                <i class="fa-solid fa-circle-info"></i> Tentang Kami
            </div>
            <h2 class="text-3xl lg:text-4xl font-extrabold text-gray-900 mb-4">Siapa Kami?</h2>
            <p class="text-gray-500 max-w-2xl mx-auto text-base">Aplikasi RT adalah platform digital modern untuk mendukung administrasi dan pelayanan warga secara transparan, cepat, dan mudah.</p>
        </div>

        {{-- GRID KONTEN --}}
        <div class="grid lg:grid-cols-2 gap-12 items-center">

            {{-- KIRI: Narasi --}}
            <div class="space-y-6">
                <div class="flex gap-4 items-start">
                    <div class="w-12 h-12 rounded-2xl bg-blue-600 flex items-center justify-center shrink-0 shadow-lg shadow-blue-200">
                        <i class="fa-solid fa-bullseye text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 mb-1">Visi Kami</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">Mewujudkan lingkungan RT yang modern, terdigitalisasi, dan berdaya saing melalui teknologi informasi yang mudah diakses semua warga.</p>
                    </div>
                </div>
                <div class="flex gap-4 items-start">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-600 flex items-center justify-center shrink-0 shadow-lg shadow-indigo-200">
                        <i class="fa-solid fa-rocket text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 mb-1">Misi Kami</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">Membangun sistem administrasi RT yang transparan, efisien, dan dapat diandalkan — dari pencatatan kas hingga layanan surat-menyurat.</p>
                    </div>
                </div>
                <div class="flex gap-4 items-start">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-600 flex items-center justify-center shrink-0 shadow-lg shadow-emerald-200">
                        <i class="fa-solid fa-shield-halved text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 mb-1">Keamanan Data</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">Data warga dikelola dengan standar keamanan tinggi. Setiap akses dicatat dan hak akses diatur berdasarkan peran pengguna.</p>
                    </div>
                </div>
            </div>

            {{-- KANAN: Statistik / Fakta --}}
            <div class="grid grid-cols-2 gap-5">
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 text-center hover:shadow-md transition">
                    <div class="text-4xl font-black text-blue-600 mb-1">100+</div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Warga Terdaftar</p>
                </div>
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 text-center hover:shadow-md transition">
                    <div class="text-4xl font-black text-indigo-600 mb-1">12</div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Fitur Layanan</p>
                </div>
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 text-center hover:shadow-md transition">
                    <div class="text-4xl font-black text-emerald-600 mb-1">24/7</div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Akses Sistem</p>
                </div>
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 text-center hover:shadow-md transition">
                    <div class="text-4xl font-black text-rose-500 mb-1">100%</div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Digital & Gratis</p>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- SECTION KONTAK --}}
<section id="kontak" class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-6 lg:px-10">

        {{-- HEADER --}}
        <div class="text-center mb-14">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-rose-100 text-rose-600 rounded-full text-xs font-bold uppercase tracking-widest mb-4">
                <i class="fa-solid fa-headset"></i> Kontak & Bantuan
            </div>
            <h2 class="text-3xl lg:text-4xl font-extrabold text-gray-900 mb-4">Hubungi Kami</h2>
            <p class="text-gray-500 max-w-xl mx-auto text-base">Punya pertanyaan atau kendala? Hubungi pengurus RT atau kirim pesan langsung melalui formulir di bawah ini.</p>
        </div>

        <div class="grid lg:grid-cols-2 gap-12">

            {{-- KIRI: Info Kontak --}}
            <div class="space-y-5">
                @php $rt = $rt_info ?? null; @endphp

                <div class="flex items-center gap-4 p-5 bg-blue-50 rounded-2xl border border-blue-100">
                    <div class="w-11 h-11 rounded-xl bg-blue-600 text-white flex items-center justify-center shadow">
                        <i class="fa-solid fa-people-roof"></i>
                    </div>
                    <div>
                        <p class="text-xs text-blue-500 font-semibold uppercase tracking-wider">Nama RT / RW</p>
                        <p class="font-bold text-gray-800 text-sm">RT {{ $rt->nomor_rt ?? '001' }} / RW {{ $rt->nomor_rw ?? '001' }} — {{ $rt->nama_wilayah ?? 'Wilayah RT' }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 p-5 bg-emerald-50 rounded-2xl border border-emerald-100">
                    <div class="w-11 h-11 rounded-xl bg-emerald-600 text-white flex items-center justify-center shadow">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <div>
                        <p class="text-xs text-emerald-600 font-semibold uppercase tracking-wider">Alamat Sekretariat</p>
                        <p class="font-bold text-gray-800 text-sm">{{ $rt->alamat_lengkap ?? 'Sekretariat RT setempat' }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 p-5 bg-amber-50 rounded-2xl border border-amber-100">
                    <div class="w-11 h-11 rounded-xl bg-amber-500 text-white flex items-center justify-center shadow">
                        <i class="fa-brands fa-whatsapp"></i>
                    </div>
                    <div>
                        <p class="text-xs text-amber-600 font-semibold uppercase tracking-wider">WhatsApp Pengurus</p>
                        <a href="https://wa.me/6281234567890" target="_blank" class="font-bold text-gray-800 text-sm hover:text-amber-600 transition">+62 812-3456-7890</a>
                    </div>
                </div>

                <div class="flex items-center gap-4 p-5 bg-indigo-50 rounded-2xl border border-indigo-100">
                    <div class="w-11 h-11 rounded-xl bg-indigo-600 text-white flex items-center justify-center shadow">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <div>
                        <p class="text-xs text-indigo-600 font-semibold uppercase tracking-wider">Jam Layanan</p>
                        <p class="font-bold text-gray-800 text-sm">Senin – Jumat: 08.00 – 16.00 WIB</p>
                    </div>
                </div>
            </div>

            {{-- KANAN: Form Pesan --}}
            <div class="bg-gray-50 rounded-3xl p-8 border border-gray-100 shadow-sm">
                <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-paper-plane text-blue-600"></i> Kirim Pesan
                </h3>
                <form id="kontak-form" class="space-y-4" onsubmit="kirimKontak(event)">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nama Lengkap</label>
                        <input type="text" id="kontak-nama" required placeholder="Nama kamu..." class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-400 bg-white transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nomor HP / WhatsApp</label>
                        <input type="tel" id="kontak-hp" placeholder="08xx-xxxx-xxxx" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-400 bg-white transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Pesan / Pertanyaan</label>
                        <textarea id="kontak-pesan" required rows="4" placeholder="Tuliskan pesan atau pertanyaan kamu di sini..." class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-400 bg-white transition resize-none"></textarea>
                    </div>
                    <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition text-sm flex items-center justify-center gap-2 shadow-md shadow-blue-200">
                        <i class="fa-brands fa-whatsapp text-lg"></i> Kirim via WhatsApp
                    </button>
                </form>
                <p id="kontak-sukses" class="hidden mt-4 text-center text-emerald-600 font-semibold text-sm">✅ Pesan berhasil dikirim via WhatsApp!</p>
            </div>

        </div>
    </div>
</section>

{{-- FOOTER --}}
<footer class="bg-[#0f172a] py-10 text-center">
    <div class="max-w-4xl mx-auto px-6">
        <div class="flex items-center justify-center gap-3 mb-4">
            <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center">
                <i class="fa-solid fa-people-roof text-white text-sm"></i>
            </div>
            <span class="text-white font-bold text-base">Aplikasi RT</span>
        </div>
        <div class="flex items-center justify-center gap-6 mb-5">
            <a href="#beranda" class="text-gray-400 hover:text-white text-xs transition">Beranda</a>
            <a href="#fitur" class="text-gray-400 hover:text-white text-xs transition">Fitur</a>
            <a href="#tentang" class="text-gray-400 hover:text-white text-xs transition">Tentang</a>
            <a href="#kontak" class="text-gray-400 hover:text-white text-xs transition">Kontak</a>
        </div>
        <p class="text-gray-500 text-xs">© 2026 Aplikasi RT — Sistem Informasi dan Pelayanan Warga</p>
    </div>
</footer>

<script>
    /* =============================================
       TAB INFORMASI PUBLIK
    ============================================= */
    function switchPublicTab(tabId, btn) {
        document.querySelectorAll('.public-tab-content').forEach(c => c.classList.add('hidden'));
        document.getElementById(tabId).classList.remove('hidden');
        document.querySelectorAll('.public-tab-btn').forEach(b => {
            b.classList.remove('active', 'bg-blue-600', 'text-white', 'shadow-lg', 'shadow-blue-200');
            b.classList.add('bg-gray-50', 'text-gray-600');
        });
        btn.classList.add('active', 'bg-blue-600', 'text-white', 'shadow-lg', 'shadow-blue-200');
        btn.classList.remove('bg-gray-50', 'text-gray-600');
    }

    /* =============================================
       KIRIM KONTAK VIA WHATSAPP
    ============================================= */
    function kirimKontak(e) {
        e.preventDefault();
        const nama  = document.getElementById('kontak-nama').value.trim();
        const hp    = document.getElementById('kontak-hp').value.trim();
        const pesan = document.getElementById('kontak-pesan').value.trim();

        if (!nama || !pesan) return;

        const nomorWA = '6281234567890'; // <-- ganti dengan nomor WA pengurus RT
        const teks = encodeURIComponent(
            `Halo Pengurus RT,\n\nSaya *${nama}*${hp ? ' (HP: ' + hp + ')' : ''} ingin menyampaikan:\n\n_${pesan}_\n\nTerima kasih.`
        );

        window.open(`https://wa.me/${nomorWA}?text=${teks}`, '_blank');

        document.getElementById('kontak-form').reset();
        const sukses = document.getElementById('kontak-sukses');
        sukses.classList.remove('hidden');
        setTimeout(() => sukses.classList.add('hidden'), 4000);
    }

    /* =============================================
       NAVBAR: HIGHLIGHT AKTIF SAAT SCROLL
    ============================================= */
    const sections  = ['beranda', 'fitur', 'tentang', 'kontak'];
    const navLinks  = document.querySelectorAll('.nav-link');

    function updateActiveNav() {
        let current = 'beranda';
        sections.forEach(id => {
            const el = document.getElementById(id);
            if (el && window.scrollY >= el.offsetTop - 100) current = id;
        });
        navLinks.forEach(link => {
            const isActive = link.dataset.section === current;
            link.classList.toggle('text-blue-300', isActive);
            link.classList.toggle('font-bold',     isActive);
        });
    }

    window.addEventListener('scroll', updateActiveNav, { passive: true });
    updateActiveNav();

    /* =============================================
       NAVBAR: BACKGROUND SOLID SAAT SCROLL
    ============================================= */
    const navbar = document.querySelector('nav');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 60) {
            navbar.classList.add('bg-[#0f2460]', 'shadow-lg');
        } else {
            navbar.classList.remove('bg-[#0f2460]', 'shadow-lg');
        }
    }, { passive: true });
</script>

</body>
</html>
