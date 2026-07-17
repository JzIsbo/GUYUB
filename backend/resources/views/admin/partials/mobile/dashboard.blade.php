@php
    $summary = [];
    foreach($list_perangkat ?? [] as $item) {
        $jenis = $item->jenis_perangkat ?: 'Lainnya';
        if(!isset($summary[$jenis])) {
            $summary[$jenis] = 0;
        }
        $summary[$jenis] += $item->jumlah;
    }
@endphp

<!-- resources/views/admin/partials/mobile/dashboard.blade.php -->
<div class="space-y-5 max-w-[600px] mx-auto pb-8">

    {{-- WALLET SALDO CARD --}}
    <div class="bg-gradient-to-br from-[#1e3a8a] via-[#1e1b4b] to-[#0f172a] p-6 rounded-[2rem] shadow-xl text-white relative overflow-hidden flex flex-col justify-between min-h-[160px]">
        <div class="absolute -right-6 -bottom-6 w-28 h-28 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -left-6 -top-6 w-28 h-28 bg-blue-500/10 rounded-full blur-2xl pointer-events-none"></div>
        
        <div class="relative z-10 flex justify-between items-start">
            <div>
                <p class="text-blue-200/80 text-[10px] font-bold tracking-widest uppercase">Saldo Kas Saat Ini</p>
                <h2 class="stat-counter text-3xl font-black mt-1 tracking-tight" data-value="{{ $saldo_bersih ?? 0 }}" data-type="currency">Rp 0</h2>
            </div>
            <div class="bg-white/10 w-9 h-9 rounded-xl flex items-center justify-center border border-white/10"><i class="fa-solid fa-building-columns text-xs text-blue-200"></i></div>
        </div>
        <div class="relative z-10 mt-4">
            <div class="flex items-center gap-1.5 bg-white/5 w-fit px-2.5 py-1.5 rounded-full border border-white/5 backdrop-blur-sm">
                <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                <span class="text-[8px] text-blue-100 font-bold uppercase tracking-wider">Kas Utama Aktif</span>
            </div>
        </div>
    </div>

    {{-- QUICK ACTIONS --}}
    <div class="bg-white/80 dark:bg-slate-900 border border-slate-100/85 dark:border-slate-800/60 shadow-[0_4px_25px_rgba(0,0,0,0.01)] p-5 rounded-[2rem]">
        <h4 class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-3.5">Aksi Cepat Layanan</h4>
        <div class="grid grid-cols-2 gap-3">
            <button onclick="switchPage('pemasukan', document.querySelector('.menu-link[onclick*=\'pemasukan\\\']'))" class="bg-slate-50/50 dark:bg-slate-950/20 border border-slate-100/60 dark:border-slate-800 text-slate-800 dark:text-slate-300 rounded-2xl font-bold flex flex-col items-center justify-center p-3 hover:scale-[1.02] active:scale-95 transition-all shadow-xs group cursor-pointer border-none">
                <i class="fa-solid fa-circle-plus text-xl mb-1.5 text-emerald-500 group-hover:scale-105 transition-transform duration-300"></i>
                <span class="text-[10px]">Pemasukan</span>
            </button>
            <button onclick="switchPage('pengeluaran', document.querySelector('.menu-link[onclick*=\'pengeluaran\\\']'))" class="bg-slate-50/50 dark:bg-slate-950/20 border border-slate-100/60 dark:border-slate-800 text-slate-800 dark:text-slate-300 rounded-2xl font-bold flex flex-col items-center justify-center p-3 hover:scale-[1.02] active:scale-95 transition-all shadow-xs group cursor-pointer border-none">
                <i class="fa-solid fa-circle-minus text-xl mb-1.5 text-rose-500 group-hover:scale-105 transition-transform duration-300"></i>
                <span class="text-[10px]">Pengeluaran</span>
            </button>
            <button onclick="switchPage('data-warga', document.querySelector('.menu-link[onclick*=\'data-warga\\\']'))" class="bg-slate-50/50 dark:bg-slate-950/20 border border-slate-100/60 dark:border-slate-800 text-slate-800 dark:text-slate-300 rounded-2xl font-bold flex flex-col items-center justify-center p-3 hover:scale-[1.02] active:scale-95 transition-all shadow-xs group cursor-pointer border-none">
                <i class="fa-solid fa-user-plus text-xl mb-1.5 text-blue-500 group-hover:scale-105 transition-transform duration-300"></i>
                <span class="text-[10px]">Tambah Warga</span>
            </button>
            <button onclick="switchPage('laporan-keuangan', document.querySelector('.menu-link[onclick*=\\\'laporan-keuangan\\\']'))" class="bg-slate-50/50 dark:bg-slate-950/20 border border-slate-100/60 dark:border-slate-800 text-slate-800 dark:text-slate-300 rounded-2xl font-bold flex flex-col items-center justify-center p-3 hover:scale-[1.02] active:scale-95 transition-all shadow-xs group cursor-pointer border-none">
                <i class="fa-solid fa-file-invoice text-xl mb-1.5 text-amber-500 group-hover:scale-105 transition-transform duration-300"></i>
                <span class="text-[10px]">Cetak Laporan</span>
            </button>
        </div>
    </div>

    {{-- STATS GRID --}}
    <div class="grid grid-cols-2 gap-3">
        {{-- CARD 1: PEMASUKAN --}}
        <div class="relative p-4 rounded-2xl shadow-lg border bg-white/80 dark:bg-[#131B2E] border-slate-100/90 dark:border-slate-800/80 flex flex-col justify-between overflow-hidden min-h-[105px]">
            <div class="flex justify-between items-center mb-2.5">
                <div class="bg-emerald-50 dark:bg-emerald-500/10 w-7 h-7 rounded-lg text-emerald-600 dark:text-emerald-400 flex items-center justify-center border border-emerald-100 dark:border-emerald-500/20"><i class="fa-solid fa-arrow-down-long text-xs"></i></div>
                <span class="bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 text-[8px] px-1.5 py-0.5 rounded-full font-black uppercase">Masuk</span>
            </div>
            <div>
                <p class="text-slate-400 dark:text-slate-500 text-[9px] font-bold uppercase tracking-wider mb-0.5">Pemasukan</p>
                <h3 class="stat-counter text-sm font-black tracking-tight text-slate-800 dark:text-white" data-value="{{ $saldo ?? 0 }}" data-type="currency">Rp 0</h3>
            </div>
        </div>

        {{-- CARD 2: PENGELUARAN --}}
        <div class="relative p-4 rounded-2xl shadow-lg border bg-white/80 dark:bg-[#131B2E] border-slate-100/90 dark:border-slate-800/80 flex flex-col justify-between overflow-hidden min-h-[105px]">
            <div class="flex justify-between items-center mb-2.5">
                <div class="bg-rose-50 dark:bg-rose-500/10 w-7 h-7 rounded-lg text-rose-600 dark:text-rose-400 flex items-center justify-center border border-rose-100 dark:border-rose-500/20"><i class="fa-solid fa-arrow-up-long text-xs"></i></div>
                <span class="bg-rose-50 dark:bg-rose-500/10 text-rose-700 dark:text-rose-400 text-[8px] px-1.5 py-0.5 rounded-full font-black uppercase">Keluar</span>
            </div>
            <div>
                <p class="text-slate-400 dark:text-slate-500 text-[9px] font-bold uppercase tracking-wider mb-0.5">Pengeluaran</p>
                <h3 class="stat-counter text-sm font-black tracking-tight text-slate-800 dark:text-white" data-value="{{ $pengeluaran ?? 0 }}" data-type="currency">Rp 0</h3>
            </div>
        </div>

        {{-- CARD 3: SALDO GLOBAL --}}
        <div class="relative p-4 rounded-2xl shadow-lg border bg-white/80 dark:bg-[#131B2E] border-slate-100/90 dark:border-slate-800/80 flex flex-col justify-between overflow-hidden min-h-[105px]">
            <div class="flex justify-between items-center mb-2.5">
                <div class="bg-indigo-50 dark:bg-indigo-500/10 w-7 h-7 rounded-lg text-indigo-600 dark:text-indigo-400 flex items-center justify-center border border-indigo-100 dark:border-indigo-500/20"><i class="fa-solid fa-wallet text-xs"></i></div>
                <span class="bg-indigo-50 dark:bg-indigo-500/10 text-indigo-700 dark:text-indigo-400 text-[8px] px-1.5 py-0.5 rounded-full font-black uppercase">Saldo</span>
            </div>
            <div>
                <p class="text-slate-400 dark:text-slate-500 text-[9px] font-bold uppercase tracking-wider mb-0.5">Kas Global</p>
                <h3 class="stat-counter text-sm font-black tracking-tight text-slate-800 dark:text-white" data-value="{{ $saldo_bersih ?? 0 }}" data-type="currency">Rp 0</h3>
            </div>
        </div>

        {{-- CARD 4: SALDO KOPERASI --}}
        <div class="relative p-4 rounded-2xl shadow-lg border bg-white/80 dark:bg-[#131B2E] border-slate-100/90 dark:border-slate-800/80 flex flex-col justify-between overflow-hidden min-h-[105px]">
            <div class="flex justify-between items-center mb-2.5">
                <div class="bg-purple-50 dark:bg-purple-500/10 w-7 h-7 rounded-lg text-purple-600 dark:text-purple-400 flex items-center justify-center border border-purple-100 dark:border-purple-500/20"><i class="fa-solid fa-store text-xs"></i></div>
                <span class="bg-purple-50 dark:bg-purple-500/10 text-purple-700 dark:text-purple-400 text-[8px] px-1.5 py-0.5 rounded-full font-black uppercase">Koperasi</span>
            </div>
            <div>
                <p class="text-slate-400 dark:text-slate-500 text-[9px] font-bold uppercase tracking-wider mb-0.5">Kas Koperasi</p>
                <h3 class="stat-counter text-sm font-black tracking-tight text-slate-800 dark:text-white" data-value="{{ $saldo_bersih_kop ?? 0 }}" data-type="currency">Rp 0</h3>
            </div>
        </div>

        {{-- CARD 5: TOTAL WARGA --}}
        <div class="relative p-4 rounded-2xl shadow-lg border bg-white/80 dark:bg-[#131B2E] border-slate-100/90 dark:border-slate-800/80 flex flex-col justify-between overflow-hidden min-h-[105px]">
            <div class="flex justify-between items-center mb-2.5">
                <div class="bg-amber-50 dark:bg-amber-500/10 w-7 h-7 rounded-lg text-amber-600 dark:text-amber-400 flex items-center justify-center border border-amber-100 dark:border-amber-500/20"><i class="fa-solid fa-users text-xs"></i></div>
                <span class="bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400 text-[8px] px-1.5 py-0.5 rounded-full font-black uppercase">Warga</span>
            </div>
            <div>
                <p class="text-slate-400 dark:text-slate-500 text-[9px] font-bold uppercase tracking-wider mb-0.5">Total Warga</p>
                <h3 class="stat-counter text-sm font-black tracking-tight text-slate-800 dark:text-white" data-value="{{ $warga ?? 0 }}" data-type="warga">0 Jiwa</h3>
            </div>
        </div>
    </div>

    {{-- KARTU KELUARGA SAYA --}}
    <div class="bg-white/80 dark:bg-slate-900 border border-slate-100/85 dark:border-slate-800/60 shadow-[0_4px_25px_rgba(0,0,0,0.01)] p-5 rounded-[2rem] space-y-3 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-50/30 rounded-full blur-xl pointer-events-none"></div>
        <div class="flex items-center justify-between relative z-10">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-slate-900 to-slate-950 text-white flex items-center justify-center shadow-md shrink-0">
                    <i class="fa-solid fa-address-card text-sm"></i>
                </div>
                <div>
                    <h4 class="text-xs font-black text-slate-800 dark:text-white tracking-tight">Kartu Keluarga Saya</h4>
                    <p class="text-[9px] text-gray-400 font-mono">KK: {{ $user_warga->nomor_kk ?? '-' }}</p>
                </div>
            </div>
            @if(count($family_members ?? []) > 0)
            <button onclick="document.getElementById('modal-kartu-keluarga-m').classList.remove('hidden')" class="px-3 py-2 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-700 dark:text-indigo-400 border border-indigo-100/50 dark:border-indigo-500/20 rounded-xl font-extrabold text-[9px] cursor-pointer">
                Lihat Detail
            </button>
            @endif
        </div>

        @if(count($family_members ?? []) > 0)
        <div class="space-y-2 pt-2.5 border-t border-slate-50 dark:border-slate-800/50 relative z-10">
            @foreach($family_members as $m)
            <div class="flex items-center justify-between p-2 rounded-xl bg-slate-50/50 dark:bg-slate-950/20 border border-slate-100/30 dark:border-slate-800/30 text-xs">
                <div class="flex items-center gap-2 min-w-0">
                    <div class="w-6 h-6 rounded-lg {{ $m->status_keluarga == 'Kepala Keluarga' ? 'bg-gradient-to-tr from-indigo-500 to-indigo-600 text-white' : ($m->status_keluarga == 'Istri' ? 'bg-gradient-to-tr from-pink-400 to-pink-500 text-white' : 'bg-gradient-to-tr from-blue-400 to-blue-500 text-white') }} flex items-center justify-center font-bold text-[9px] shrink-0">
                        {{ strtoupper(substr($m->nama_lengkap, 0, 1)) }}
                    </div>
                    <span class="font-extrabold text-slate-700 dark:text-white text-[11px] truncate">{{ $m->nama_lengkap }}</span>
                </div>
                <span class="px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-wide {{ $m->status_keluarga == 'Kepala Keluarga' ? 'bg-indigo-100 text-indigo-700' : ($m->status_keluarga == 'Istri' ? 'bg-pink-100 text-pink-700' : 'bg-blue-100 text-blue-700') }}">
                    {{ $m->status_keluarga }}
                </span>
            </div>
            @endforeach
        </div>
        @else
        {{-- MOBILE DYNAMIC ONBOARDING CARD --}}
        <div class="p-5 bg-gradient-to-br from-indigo-500/5 via-slate-50/50 to-indigo-500/5 dark:from-slate-900 dark:via-slate-950 dark:to-indigo-950 text-slate-855 dark:text-white rounded-2xl border border-indigo-100/50 dark:border-slate-800 text-center text-xs space-y-3 shadow-xs">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-white/5 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-lg mx-auto border border-indigo-100/50 dark:border-white/10">
                <i class="fa-solid fa-house-chimney-user"></i>
            </div>
            <p class="font-black text-slate-800 dark:text-white text-[11px] tracking-tight">Portal Warga GUYUB</p>
            <p class="text-[10px] text-slate-500 dark:text-slate-400 leading-normal font-semibold">
                Profil Anda belum terhubung. Pastikan nama akun Anda terdaftar di data warga RT untuk melihat Kartu Keluarga.
            </p>
            <button onclick="if(document.querySelector('.menu-link[onclick*=\\\'data-keluarga\\\']')) switchPage('data-keluarga', document.querySelector('.menu-link[onclick*=\\\'data-keluarga\\\']'))" class="w-full bg-indigo-600 text-white py-2.5 rounded-xl font-bold text-[10px] shadow-sm border-none cursor-pointer">
                Cek Data Keluarga
            </button>
        </div>
        @endif
    </div>

    {{-- Mobile KK Modal --}}
    <div id="modal-kartu-keluarga-m" class="hidden fixed inset-0 z-50 flex items-end justify-center bg-black/60 backdrop-blur-sm p-0">
        <div class="bg-white rounded-t-[2rem] p-5 w-full max-w-[95vw] shadow-2xl max-h-[85vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-sm font-black text-gray-800">Kartu Keluarga Digital</h3>
                <button onclick="document.getElementById('modal-kartu-keluarga-m').classList.add('hidden')" class="w-7 h-7 bg-gray-50 text-gray-500 rounded-full flex items-center justify-center font-bold border-none cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="bg-gradient-to-br from-indigo-900 via-slate-900 to-indigo-950 text-white p-4 rounded-2xl mb-4 text-xs font-mono relative overflow-hidden">
                <p class="text-[8px] text-blue-300 uppercase tracking-widest font-bold">NO. KARTU KELUARGA</p>
                <p class="text-base font-black text-amber-300 mt-0.5">{{ $user_warga->nomor_kk ?? '-' }}</p>
                <p class="text-[10px] text-blue-200 mt-1 font-semibold"><i class="fa-solid fa-location-dot mr-1"></i> Alamat: {{ $user_warga->blok_rumah ?? 'Lingkungan RT/RW' }}</p>
            </div>

            <div class="space-y-2">
                @foreach($family_members ?? [] as $m)
                <div class="p-3 border border-slate-100 rounded-xl bg-slate-50/60 text-xs space-y-1.5">
                    <div class="flex justify-between items-center">
                        <span class="font-extrabold text-slate-800 text-[11px]">{{ $m->nama_lengkap }}</span>
                        <span class="px-2 py-0.5 rounded text-[8px] font-black bg-indigo-100 text-indigo-700 uppercase tracking-wide">{{ $m->status_keluarga }}</span>
                    </div>
                    <p class="text-[9px] text-gray-400 font-mono font-medium">NIK: {{ $m->nik }}</p>
                    <div class="flex items-center gap-1.5 text-[9px] text-gray-500 font-medium">
                        <span><i class="fa-solid fa-calendar-day mr-1 text-slate-400"></i>{{ $m->umur ? $m->umur . ' Thn' : '-' }}</span>
                        <span>&bull;</span>
                        <span><i class="fa-solid fa-book-open mr-1 text-slate-400"></i>{{ $m->agama ?? '-' }}</span>
                        <span>&bull;</span>
                        <span><i class="fa-solid fa-house-chimney mr-1 text-slate-400"></i>{{ $m->status_domisili ?? 'Tetap' }}</span>
                    </div>
                </div>
                @endforeach
            </div>

            <button onclick="document.getElementById('modal-kartu-keluarga-m').classList.add('hidden')" class="w-full mt-4 bg-gray-100 text-gray-700 font-bold py-2.5 rounded-xl text-xs border-none cursor-pointer transition hover:bg-gray-200">
                Tutup
            </button>
        </div>
    </div>

    {{-- CHART CARD --}}
    <div class="bg-white/80 dark:bg-slate-900 border border-slate-100/85 dark:border-slate-800/60 shadow-[0_4px_25px_rgba(0,0,0,0.01)] p-5 rounded-[2rem] flex flex-col">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h3 class="font-black text-slate-800 dark:text-white text-xs uppercase tracking-widest">Grafik Aliran Kas</h3>
            </div>
            <div class="flex gap-2.5">
                <div class="flex items-center gap-1"><span class="w-2 h-2 bg-emerald-500 rounded-full"></span><span class="text-[8px] text-gray-400 font-bold uppercase">Masuk</span></div>
                <div class="flex items-center gap-1"><span class="w-2 h-2 bg-rose-500 rounded-full"></span><span class="text-[8px] text-gray-400 font-bold uppercase">Keluar</span></div>
            </div>
        </div>
        <div class="relative w-full h-[180px]">
            <canvas id="kasChartMobile"></canvas>
        </div>
    </div>

    {{-- DEMOGRAFI DOUGHNUT CHART CARD (MOBILE) --}}
    <div class="bg-white/80 dark:bg-slate-900 border border-slate-100/85 dark:border-slate-800/60 shadow-[0_4px_25px_rgba(0,0,0,0.01)] p-5 rounded-[2rem] flex flex-col">
        <div class="mb-3">
            <h3 class="font-black text-slate-800 dark:text-white text-xs uppercase tracking-widest">Demografi Warga</h3>
        </div>
        <div class="flex items-center justify-between gap-4 mt-2">
            <div class="relative w-[95px] h-[95px] shrink-0">
                <canvas id="demografiChartMobile"></canvas>
            </div>
            <div class="flex-1 space-y-2 text-[10px] font-bold">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                        <span class="text-slate-550 dark:text-slate-400">Pria</span>
                    </div>
                    <span class="stat-counter text-slate-800 dark:text-white font-black" data-value="{{ $demografi_pria ?? 0 }}" data-type="warga">0 Jiwa</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-pink-500"></span>
                        <span class="text-slate-550 dark:text-slate-400">Wanita</span>
                    </div>
                    <span class="stat-counter text-slate-800 dark:text-white font-black" data-value="{{ $demografi_wanita ?? 0 }}" data-type="warga">0 Jiwa</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-violet-500"></span>
                        <span class="text-slate-550 dark:text-slate-400">Anak-anak</span>
                    </div>
                    <span class="stat-counter text-slate-800 dark:text-white font-black" data-value="{{ $demografi_anak ?? 0 }}" data-type="warga">0 Jiwa</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        <span class="text-slate-550 dark:text-slate-400">Lansia</span>
                    </div>
                    <span class="stat-counter text-slate-800 dark:text-white font-black" data-value="{{ $demografi_lansia ?? 0 }}" data-type="warga">0 Jiwa</span>
                </div>
            </div>
        </div>
    </div>

    {{-- RECENT TRANSACTIONS --}}
    <div class="bg-white/80 dark:bg-slate-900 border border-slate-100/85 dark:border-slate-800/60 shadow-[0_4px_25px_rgba(0,0,0,0.01)] p-5 rounded-[2rem]">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h3 class="font-black text-slate-800 dark:text-white text-xs uppercase tracking-widest">Transaksi Terbaru</h3>
            </div>
            <a href="javascript:void(0)" onclick="switchPage('transaksi', document.querySelector('.menu-link[onclick*=\'transaksi\\\']'))" class="text-indigo-600 text-[9px] font-black hover:underline uppercase tracking-wider">Semua</a>
        </div>

        <div class="space-y-3">
            @forelse($transaksi_terbaru as $item)
                <div class="flex justify-between items-center pb-3 border-b border-slate-50 dark:border-slate-800/50 last:border-b-0 hover:bg-slate-50/50 dark:hover:bg-slate-950/25 transition-colors rounded-xl p-2 -mx-2">
                    <div class="flex items-center gap-3 min-w-0">
                        @if($item->jenis == 'pemasukan')
                            <div class="bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 w-9 h-9 rounded-xl flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-500/20">
                                <i class="fa-solid fa-arrow-down-long text-xs"></i>
                            </div>
                        @else
                            <div class="bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 w-9 h-9 rounded-xl flex items-center justify-center shrink-0 border border-rose-100 dark:border-rose-500/20">
                                <i class="fa-solid fa-arrow-up-long text-xs"></i>
                            </div>
                        @endif
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-slate-800 dark:text-white truncate leading-tight">{{ $item->keterangan }}</p>
                            <p class="text-[8px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">{{ $item->kategori }}</p>
                        </div>
                    </div>
                    <div class="text-right shrink-0 ml-2">
                        @if($item->jenis == 'pemasukan')
                            <p class="text-xs font-black text-emerald-600 tracking-tight">+ Rp {{ number_format($item->nominal, 0, ',', '.') }}</p>
                        @else
                            <p class="text-xs font-black text-rose-600 tracking-tight">- Rp {{ number_format($item->nominal, 0, ',', '.') }}</p>
                        @endif
                        <p class="text-[8px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">{{ date('d M', strtotime($item->tanggal)) }}</p>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-400 py-4 text-xs italic">Belum ada transaksi terbaru.</div>
            @endforelse
        </div>
    </div>

</div>

<script>
    // (Using global runGlobalCounterAnimation from super-admin layout instead)

    window.renderDemografiChartMobile = function() {
        const canvas = document.getElementById('demografiChartMobile');
        if(!canvas) return;

        if(window.demografiChartMobileInstance) {
            window.demografiChartMobileInstance.destroy();
        }

        const ctx = canvas.getContext('2d');
        const isDark = document.documentElement.classList.contains('dark');

        window.demografiChartMobileInstance = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Pria', 'Wanita', 'Anak', 'Lansia'],
                datasets: [{
                    data: [{{ $demografi_pria ?? 0 }}, {{ $demografi_wanita ?? 0 }}, {{ $demografi_anak ?? 0 }}, {{ $demografi_lansia ?? 0 }}],
                    backgroundColor: ['#3b82f6', '#f43f5e', '#8b5cf6', '#f59e0b'],
                    borderWidth: isDark ? 2 : 1,
                    borderColor: isDark ? '#0f172a' : '#ffffff'
                }]
            },
            options: {
                cutout: '70%',
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    animateRotate: true,
                    animateScale: true,
                    duration: 1500,
                    easing: 'easeOutQuart'
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    };

    window.renderDashboardMobile = function() {
        const canvas = document.getElementById('kasChartMobile');
        if(!canvas) return;

        if(window.kasChartMobileInstance) {
            window.kasChartMobileInstance.destroy();
        }

        const ctx = canvas.getContext('2d');
        const isDark = document.documentElement.classList.contains('dark');
        
        // Buat dynamic vertical gradients untuk Bar Chart
        const gradMasuk = ctx.createLinearGradient(0, 0, 0, 180);
        gradMasuk.addColorStop(0, '#10b981');
        gradMasuk.addColorStop(1, '#059669');

        const gradKeluar = ctx.createLinearGradient(0, 0, 0, 180);
        gradKeluar.addColorStop(0, '#f43f5e');
        gradKeluar.addColorStop(1, '#e11d48');

        window.kasChartMobileInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [
                    {
                        label: 'Masuk',
                        data: {!! json_encode($chart_pemasukan ?? array_fill(0,12,0)) !!},
                        backgroundColor: gradMasuk,
                        borderRadius: 6,
                        borderWidth: 0,
                        barPercentage: 0.6,
                        categoryPercentage: 0.7
                    },
                    {
                        label: 'Keluar',
                        data: {!! json_encode($chart_pengeluaran ?? array_fill(0,12,0)) !!},
                        backgroundColor: gradKeluar,
                        borderRadius: 6,
                        borderWidth: 0,
                        barPercentage: 0.6,
                        categoryPercentage: 0.7
                    }
                ]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false,
                animation: {
                    duration: 1500,
                    easing: 'easeOutQuart'
                },
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            color: '#94a3b8',
                            font: { size: 8, weight: 'bold' }
                        }
                    },
                    y: {
                        grid: { color: isDark ? 'rgba(255, 255, 255, 0.06)' : 'rgba(226, 232, 240, 0.2)', drawBorder: false },
                        ticks: {
                            color: '#94a3b8',
                            font: { size: 8, weight: 'bold' },
                            callback: function(value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID', { compact: 'true' }).format(value);
                            }
                        }
                    }
                }
            }
        });
    };

    // Jalankan ChartJS

    if (typeof Chart === 'undefined') {
        const s = document.createElement('script');
        s.src = "https://cdn.jsdelivr.net/npm/chart.js";
        s.onload = function() {
            window.renderDashboardMobile();
            window.renderDemografiChartMobile();
        };
        document.head.appendChild(s);
    } else {
        window.renderDashboardMobile();
        window.renderDemografiChartMobile();
    }
</script>
