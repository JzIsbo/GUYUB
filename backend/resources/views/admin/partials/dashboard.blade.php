<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 lg:gap-5 mb-8">
    {{-- STAT CARD 1: PEMASUKAN --}}
    <div class="relative p-6 rounded-[2.2rem] border bg-white dark:bg-[#131B2E] border-slate-200 dark:border-slate-800/80 shadow-[0_8px_30px_rgba(0,0,0,0.03)] dark:shadow-[0_15px_35px_-10px_rgba(15,23,42,0.6)] hover:-translate-y-1.5 hover:shadow-[0_20px_40px_-5px_rgba(0,0,0,0.05)] dark:hover:shadow-[0_25px_50px_-12px_rgba(0,0,0,0.7)] transition-all duration-300 group overflow-hidden">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none group-hover:bg-emerald-500/15 transition-all duration-300"></div>
        <div class="relative z-10">
            <div class="flex justify-between items-center mb-4">
                <div class="bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 w-11 h-11 rounded-2xl flex items-center justify-center border border-emerald-100 dark:border-emerald-500/20 group-hover:scale-105 transition-transform duration-300">
                    <i class="fa-solid fa-arrow-down-long text-base"></i>
                </div>
                <span class="bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 text-[8px] px-2.5 py-0.5 rounded-full font-black border border-emerald-100 dark:border-emerald-500/20 uppercase tracking-widest">Buku Kas</span>
            </div>
            <p class="text-slate-400 dark:text-slate-500 text-[9px] font-black uppercase tracking-widest mb-1">Total Pemasukan</p>
            <h3 class="stat-counter text-2xl md:text-3xl font-black tracking-tight text-slate-800 dark:text-white font-sans mt-3" data-value="{{ $saldo ?? 0 }}" data-type="currency">Rp 0</h3>
            <p class="text-slate-400 dark:text-slate-500 text-[9px] font-semibold mt-2.5 flex items-center"><i class="fa-solid fa-circle-check text-emerald-500 mr-1.5"></i> Transaksi terverifikasi</p>
        </div>
    </div>

    {{-- STAT CARD 2: PENGELUARAN --}}
    <div class="relative p-6 rounded-[2.2rem] border bg-white dark:bg-[#131B2E] border-slate-200 dark:border-slate-800/80 shadow-[0_8px_30px_rgba(0,0,0,0.03)] dark:shadow-[0_15px_35px_-10px_rgba(15,23,42,0.6)] hover:-translate-y-1.5 hover:shadow-[0_20px_40px_-5px_rgba(0,0,0,0.05)] dark:hover:shadow-[0_25px_50px_-12px_rgba(0,0,0,0.7)] transition-all duration-300 group overflow-hidden">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-rose-500/10 rounded-full blur-2xl pointer-events-none group-hover:bg-rose-500/15 transition-all duration-300"></div>
        <div class="relative z-10">
            <div class="flex justify-between items-center mb-4">
                <div class="bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 w-11 h-11 rounded-2xl flex items-center justify-center border border-rose-100 dark:border-rose-500/20 group-hover:scale-105 transition-transform duration-300">
                    <i class="fa-solid fa-arrow-up-long text-base"></i>
                </div>
                <span class="bg-rose-50 dark:bg-rose-500/10 text-rose-700 dark:text-rose-400 text-[8px] px-2.5 py-0.5 rounded-full font-black border border-rose-100 dark:border-rose-500/20 uppercase tracking-widest">Operasional</span>
            </div>
            <p class="text-slate-400 dark:text-slate-500 text-[9px] font-black uppercase tracking-widest mb-1">Total Pengeluaran</p>
            <h3 class="stat-counter text-2xl md:text-3xl font-black tracking-tight text-slate-800 dark:text-white font-sans mt-3" data-value="{{ $pengeluaran ?? 0 }}" data-type="currency">Rp 0</h3>
            <p class="text-slate-400 dark:text-slate-500 text-[9px] font-semibold mt-2.5 flex items-center"><i class="fa-solid fa-circle-minus text-rose-500 mr-1.5"></i> Biaya RT disetujui</p>
        </div>
    </div>

    {{-- STAT CARD 3: SALDO GLOBAL --}}
    <div class="relative p-6 rounded-[2.2rem] border bg-white dark:bg-[#131B2E] border-slate-200 dark:border-slate-800/80 shadow-[0_8px_30px_rgba(0,0,0,0.03)] dark:shadow-[0_15px_35px_-10px_rgba(15,23,42,0.6)] hover:-translate-y-1.5 hover:shadow-[0_20px_40px_-5px_rgba(0,0,0,0.05)] dark:hover:shadow-[0_25px_50px_-12px_rgba(0,0,0,0.7)] transition-all duration-300 group overflow-hidden">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none group-hover:bg-indigo-500/15 transition-all duration-300"></div>
        <div class="relative z-10">
            <div class="flex justify-between items-center mb-4">
                <div class="bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 w-11 h-11 rounded-2xl flex items-center justify-center border border-indigo-100 dark:border-indigo-500/20 group-hover:scale-105 transition-transform duration-300">
                    <i class="fa-solid fa-wallet text-base"></i>
                </div>
                <span class="bg-indigo-50 dark:bg-indigo-500/10 text-indigo-700 dark:text-indigo-400 text-[8px] px-2.5 py-0.5 rounded-full font-black border border-indigo-100 dark:border-indigo-500/20 uppercase tracking-widest">Saldo Bersih</span>
            </div>
            <p class="text-slate-400 dark:text-slate-500 text-[9px] font-black uppercase tracking-widest mb-1">Saldo Kas Global</p>
            <h3 class="stat-counter text-2xl md:text-3xl font-black tracking-tight text-slate-800 dark:text-white font-sans mt-3" data-value="{{ $saldo_bersih ?? 0 }}" data-type="currency">Rp 0</h3>
            <p class="text-slate-400 dark:text-slate-500 text-[9px] font-semibold mt-2.5 flex items-center"><i class="fa-solid fa-wallet text-indigo-500 mr-1.5"></i> Saldo aktif utama</p>
        </div>
    </div>

    {{-- STAT CARD 4: SALDO KOPERASI --}}
    <div class="relative p-6 rounded-[2.2rem] border bg-white dark:bg-[#131B2E] border-slate-200 dark:border-slate-800/80 shadow-[0_8px_30px_rgba(0,0,0,0.03)] dark:shadow-[0_15px_35px_-10px_rgba(15,23,42,0.6)] hover:-translate-y-1.5 hover:shadow-[0_20px_40px_-5px_rgba(0,0,0,0.05)] dark:hover:shadow-[0_25px_50px_-12px_rgba(0,0,0,0.7)] transition-all duration-300 group overflow-hidden">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-purple-500/10 rounded-full blur-2xl pointer-events-none group-hover:bg-purple-500/15 transition-all duration-300"></div>
        <div class="relative z-10">
            <div class="flex justify-between items-center mb-4">
                <div class="bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400 w-11 h-11 rounded-2xl flex items-center justify-center border border-purple-100 dark:border-purple-500/20 group-hover:scale-105 transition-transform duration-300">
                    <i class="fa-solid fa-store text-base"></i>
                </div>
                <span class="bg-purple-50 dark:bg-purple-500/10 text-purple-700 dark:text-purple-400 text-[8px] px-2.5 py-0.5 rounded-full font-black border border-purple-100 dark:border-purple-500/20 uppercase tracking-widest">Koperasi</span>
            </div>
            <p class="text-slate-400 dark:text-slate-500 text-[9px] font-black uppercase tracking-widest mb-1">Saldo Koperasi</p>
            <h3 class="stat-counter text-2xl md:text-3xl font-black tracking-tight text-slate-800 dark:text-white font-sans mt-3" data-value="{{ $saldo_bersih_kop ?? 0 }}" data-type="currency">Rp 0</h3>
            <p class="text-slate-400 dark:text-slate-500 text-[9px] font-semibold mt-2.5 flex items-center"><i class="fa-solid fa-scale-balanced text-purple-500 mr-1.5"></i> Total kas bersih unit usaha</p>
        </div>
    </div>

    {{-- STAT CARD 5: TOTAL WARGA --}}
    <div class="relative p-6 rounded-[2.2rem] border bg-white dark:bg-[#131B2E] border-slate-200 dark:border-slate-800/80 shadow-[0_8px_30px_rgba(0,0,0,0.03)] dark:shadow-[0_15px_35px_-10px_rgba(15,23,42,0.6)] hover:-translate-y-1.5 hover:shadow-[0_20px_40px_-5px_rgba(0,0,0,0.05)] dark:hover:shadow-[0_25px_50px_-12px_rgba(0,0,0,0.7)] transition-all duration-300 group overflow-hidden">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-amber-500/10 rounded-full blur-2xl pointer-events-none group-hover:bg-amber-500/15 transition-all duration-300"></div>
        <div class="relative z-10">
            <div class="flex justify-between items-center mb-4">
                <div class="bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 w-11 h-11 rounded-2xl flex items-center justify-center border border-amber-100 dark:border-amber-500/20 group-hover:scale-105 transition-transform duration-300">
                    <i class="fa-solid fa-users text-base"></i>
                </div>
                <span class="bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400 text-[8px] px-2.5 py-0.5 rounded-full font-black border border-amber-100 dark:border-amber-500/20 uppercase tracking-widest">Kependudukan</span>
            </div>
            <p class="text-slate-400 dark:text-slate-500 text-[9px] font-black uppercase tracking-widest mb-1">Warga Terdaftar</p>
            <h3 class="stat-counter text-2xl md:text-3xl font-black tracking-tight text-slate-800 dark:text-white font-sans mt-3" data-value="{{ $warga ?? 0 }}" data-type="warga">0 Jiwa</h3>
            <p class="text-slate-400 dark:text-slate-500 text-[9px] font-semibold mt-2.5 flex items-center"><i class="fa-solid fa-user-check text-amber-500 mr-1.5"></i> Jiwa terdata di RT</p>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════════ --}}
{{-- 🎴 KARTU KELUARGA SAYA (DIGITAL FAMILY CARD) --}}
{{-- ════════════════════════════════════════════════════════════════════ --}}
<div class="bg-white/85 dark:bg-slate-900 border border-slate-200 dark:border-slate-800/60 shadow-[0_4px_30px_rgba(0,0,0,0.03)] rounded-[2.5rem] p-6 md:p-8 mb-8 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-80 h-80 bg-indigo-50/20 rounded-full blur-3xl pointer-events-none -mr-20 -mt-20"></div>
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between pb-6 mb-6 border-b border-gray-100 dark:border-slate-800 gap-4 relative z-10">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-slate-900 via-indigo-955 to-slate-950 flex items-center justify-center text-white shadow-lg shrink-0">
                <i class="fa-solid fa-address-card text-2xl"></i>
            </div>
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-0.5 rounded-full bg-indigo-50/60 dark:bg-indigo-500/10 text-indigo-700 dark:text-indigo-400 text-[10px] font-extrabold uppercase tracking-widest mb-1 border border-indigo-100/50 dark:border-indigo-500/20">
                    <i class="fa-solid fa-house-chimney-user text-[9px]"></i> Data Anggota Keluarga
                </div>
                <h3 class="text-xl font-black text-slate-800 dark:text-white tracking-tight leading-none">Kartu Keluarga Saya</h3>
                <p class="text-xs text-gray-400 font-bold mt-1.5">
                    No. KK: <span class="font-bold text-indigo-600 dark:text-indigo-400 font-mono tracking-wide">{{ $user_warga->nomor_kk ?? '-' }}</span>
                    @if($user_warga->blok_rumah ?? false) &bull; <span class="text-slate-600 dark:text-slate-400 font-bold">{{ $user_warga->blok_rumah }}</span> @endif
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2 w-full md:w-auto shrink-0 relative z-10">
            @if(count($family_members ?? []) > 0)
            <button onclick="document.getElementById('modal-kartu-keluarga').classList.remove('hidden')" class="w-full md:w-auto px-6 py-3.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-extrabold text-xs rounded-2xl shadow-md hover:scale-[1.01] active:scale-95 transition-all flex items-center justify-center gap-2 cursor-pointer border-none">
                <i class="fa-solid fa-id-card text-xs"></i> Tampilkan Kartu Digital
            </button>
            @endif
        </div>
    </div>

    {{-- Member List Cards Grid --}}
    @if(count($family_members ?? []) > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 relative z-10">
        @foreach($family_members as $m)
        <div class="bg-slate-50/50 dark:bg-slate-950/30 border border-slate-200 dark:border-slate-800/50 hover:border-indigo-300 dark:hover:border-indigo-900 hover:bg-white dark:hover:bg-slate-900 rounded-2xl p-4 flex items-start justify-between gap-3 hover:-translate-y-1 hover:shadow-md hover:shadow-indigo-500/5 transition-all duration-300 group">
            <div class="flex items-start gap-3 min-w-0 flex-1">
                <div class="w-10 h-10 rounded-xl {{ $m->status_keluarga == 'Kepala Keluarga' ? 'bg-gradient-to-tr from-indigo-500 to-indigo-600 text-white shadow-indigo-500/20' : ($m->status_keluarga == 'Istri' ? 'bg-gradient-to-tr from-pink-400 to-pink-500 text-white shadow-pink-500/20' : 'bg-gradient-to-tr from-blue-400 to-blue-500 text-white shadow-blue-500/20') }} flex items-center justify-center font-extrabold text-sm shrink-0 shadow-md group-hover:scale-105 transition-transform duration-300">
                    {{ strtoupper(substr($m->nama_lengkap, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <h4 class="font-extrabold text-slate-800 dark:text-white text-sm truncate leading-tight">{{ $m->nama_lengkap }}</h4>
                    <p class="text-[10px] text-gray-400 font-mono mt-1">NIK: {{ $m->nik }}</p>
                    <div class="flex items-center gap-1.5 mt-2 flex-wrap">
                        <span class="px-2 py-0.5 rounded-md text-[8px] font-black uppercase tracking-wider {{ $m->status_keluarga == 'Kepala Keluarga' ? 'bg-indigo-50 text-indigo-700' : ($m->status_keluarga == 'Istri' ? 'bg-pink-50 text-pink-700' : 'bg-blue-50 text-blue-700') }}">
                            {{ $m->status_keluarga }}
                        </span>
                        @if($m->umur)<span class="px-2 py-0.5 rounded-md text-[8px] font-black bg-slate-200/60 dark:bg-slate-800 text-slate-600 dark:text-slate-400">{{ $m->umur }} Thn</span>@endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    {{-- WELCOME BANNER (DYNAMIC DARK/LIGHT MODE) --}}
    <div class="relative bg-gradient-to-br from-indigo-500/5 via-slate-50/50 to-indigo-500/5 dark:from-slate-900/40 dark:via-slate-950/40 dark:to-indigo-950/40 text-slate-800 dark:text-white border border-indigo-100/50 dark:border-slate-800 rounded-3xl p-8 md:p-12 text-center overflow-hidden shadow-xs dark:shadow-xl">
        <div class="absolute -left-10 -top-10 w-48 h-48 bg-indigo-500/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-indigo-500/5 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="max-w-lg mx-auto relative z-10 flex flex-col items-center">
            <div class="w-16 h-16 rounded-2xl bg-indigo-50 dark:bg-white/5 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-2xl shadow-inner mb-6 border border-indigo-100/50 dark:border-white/10">
                <i class="fa-solid fa-house-chimney-user"></i>
            </div>
            <h4 class="text-xl font-black text-slate-800 dark:text-white leading-snug tracking-tight">Selamat Datang di Portal Warga GUYUB</h4>
            <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold mt-3 leading-relaxed">
                Profil kependudukan Anda belum terhubung dengan akun login Anda. Silakan laporkan atau hubungi pengurus RT agar nama lengkap Anda didaftarkan di data warga sehingga Kartu Keluarga Digital Anda muncul secara otomatis.
            </p>
            <div class="flex items-center gap-3 mt-8">
                <a href="javascript:void(0)" onclick="if(document.querySelector('.menu-link[onclick*=\\\'data-keluarga\\\']')) switchPage('data-keluarga', document.querySelector('.menu-link[onclick*=\\\'data-keluarga\\\']'))" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs rounded-xl shadow-lg shadow-indigo-500/10 transition hover:scale-[1.01] active:scale-95 flex items-center gap-2">
                    <i class="fa-solid fa-magnifying-glass"></i> Cek Data Keluarga
                </a>
            </div>
        </div>
    </div>
    @endif
</div>

{{-- ════════════════════════════════════════════════════════════════════ --}}
{{-- 🎴 MODAL KARTU KELUARGA DIGITAL --}}
{{-- ════════════════════════════════════════════════════════════════════ --}}
<div id="modal-kartu-keluarga" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white rounded-[2rem] p-6 md:p-8 w-full max-w-4xl max-h-[90vh] overflow-y-auto shadow-2xl border border-gray-100 relative">

        {{-- Official KK Card Banner Header --}}
        <div class="bg-gradient-to-br from-indigo-900 via-slate-900 to-indigo-950 text-white p-6 rounded-3xl mb-6 shadow-md relative overflow-hidden">
            <button onclick="document.getElementById('modal-kartu-keluarga').classList.add('hidden')" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white/80 hover:text-white flex items-center justify-center transition-all cursor-pointer border border-white/10 z-20">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
            <div class="absolute -right-10 -bottom-10 w-44 h-44 bg-indigo-500/10 rounded-full blur-3xl"></div>
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 relative z-10">
                <div>
                    <span class="text-[9px] font-black uppercase tracking-[3px] text-blue-300">Republik Indonesia &bull; KARTU KELUARGA DIGITAL</span>
                    <h2 class="text-2xl font-black font-mono tracking-wider mt-1 text-amber-300">NO. {{ $user_warga->nomor_kk ?? '-' }}</h2>
                    <p class="text-xs text-blue-200 mt-1 font-semibold"><i class="fa-solid fa-location-dot mr-1"></i> Alamat: {{ $user_warga->blok_rumah ?? 'Lingkungan RT/RW' }}</p>
                </div>
                <div class="bg-white/10 backdrop-blur-md px-4 py-3 rounded-2xl border border-white/10 text-right shrink-0">
                    <p class="text-[9px] text-blue-200 uppercase font-black tracking-widest">Total Anggota</p>
                    <p class="text-2xl font-black text-white leading-none mt-0.5">{{ count($family_members ?? []) }} Jiwa</p>
                </div>
            </div>
            <i class="fa-solid fa-address-card absolute -bottom-8 -right-8 text-white/5 text-[140px] pointer-events-none"></i>
        </div>

        {{-- KK Table --}}
        <div class="overflow-x-auto rounded-2xl border border-gray-150 shadow-sm mb-6">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 font-extrabold uppercase tracking-wider text-[10px] border-b border-gray-200">
                        <th class="p-4">No</th>
                        <th class="p-4">Nama Lengkap</th>
                        <th class="p-4">NIK</th>
                        <th class="p-4">Hubungan</th>
                        <th class="p-4">Usia</th>
                        <th class="p-4">Agama</th>
                        <th class="p-4">Status Domisili</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($family_members ?? [] as $idx => $m)
                    <tr class="hover:bg-indigo-50/30 transition-colors">
                        <td class="p-4 font-bold text-gray-400">{{ $idx + 1 }}</td>
                        <td class="p-4 font-extrabold text-slate-800">{{ $m->nama_lengkap }}</td>
                        <td class="p-4 font-mono text-gray-600">{{ $m->nik }}</td>
                        <td class="p-4">
                            <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-extrabold uppercase {{ $m->status_keluarga == 'Kepala Keluarga' ? 'bg-indigo-100 text-indigo-700' : ($m->status_keluarga == 'Istri' ? 'bg-pink-100 text-pink-700' : 'bg-blue-100 text-blue-700') }}">
                                {{ $m->status_keluarga }}
                            </span>
                        </td>
                        <td class="p-4 font-semibold text-gray-600">{{ $m->umur ? $m->umur . ' Tahun' : '-' }}</td>
                        <td class="p-4 font-semibold text-gray-600">{{ $m->agama ?? '-' }}</td>
                        <td class="p-4">
                            <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
                                {{ $m->status_domisili ?? 'Tetap' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-6 text-center text-gray-400 italic">Tidak ada data anggota keluarga.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex justify-end gap-2">
            <button onclick="document.getElementById('modal-kartu-keluarga').classList.add('hidden')" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 font-extrabold text-xs rounded-xl transition border-none cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    {{-- WALLET SALDO CARD --}}
    <div class="bg-gradient-to-br from-[#1e3a8a] via-[#1e1b4b] to-[#0f172a] p-8 rounded-[2.5rem] shadow-xl text-white relative overflow-hidden flex flex-col justify-between min-h-[320px] hover:shadow-2xl hover:shadow-indigo-950/20 transition-all duration-300 border border-slate-800/40">
        <div class="absolute -right-10 -bottom-10 w-44 h-44 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-10 -top-10 w-44 h-44 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="relative z-10 flex justify-between items-start">
            <div>
                <p class="text-blue-200/80 text-xs font-semibold tracking-widest uppercase">Saldo Kas Saat Ini</p>
                <h2 class="stat-counter text-4xl font-black mt-2 tracking-tighter" data-value="{{ $saldo_bersih ?? 0 }}" data-type="currency">Rp 0</h2>
            </div>
            <div class="bg-white/10 backdrop-blur-md w-10 h-10 rounded-xl flex items-center justify-center border border-white/10"><i class="fa-solid fa-building-columns text-sm text-blue-200"></i></div>
        </div>
        <div class="relative z-10 mt-8">
            <div class="flex items-center gap-1.5 bg-white/5 w-fit px-3 py-1.5 rounded-full border border-white/5 backdrop-blur-sm">
                <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                <span class="text-[9px] text-blue-100 font-bold uppercase tracking-wider">Kas Utama Aktif</span>
            </div>
            <p class="text-[9px] text-blue-300/60 uppercase tracking-[2px] font-bold mt-3">Pembaruan Real-Time</p>
        </div>
    </div>

    {{-- CHART CARD --}}
    <div class="bg-white/85 dark:bg-slate-900 border border-slate-200 dark:border-slate-800/60 shadow-[0_4px_30px_rgba(0,0,0,0.03)] p-8 rounded-[2.5rem] flex flex-col hover:shadow-md transition-shadow duration-300">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="font-extrabold text-slate-800 dark:text-white text-base tracking-tight leading-none">Grafik Kas Bulanan</h3>
                <p class="text-[10px] text-gray-400 font-semibold mt-2">Buku kas masuk & keluar tahun ini</p>
            </div>
            <div class="flex gap-3">
                <div class="flex items-center gap-1.5"><span class="w-2 h-2 bg-emerald-500 rounded-full"></span><span class="text-[8px] text-gray-400 font-bold uppercase">Masuk</span></div>
                <div class="flex items-center gap-1.5"><span class="w-2 h-2 bg-rose-500 rounded-full"></span><span class="text-[8px] text-gray-400 font-bold uppercase">Keluar</span></div>
            </div>
        </div>

        <div class="relative flex-1 w-full min-h-[200px]">
            <canvas id="kasChart"></canvas>
        </div>
    </div>

    {{-- DEMOGRAFI DOUGHNUT CHART CARD --}}
    <div class="bg-white/85 dark:bg-slate-900 border border-slate-200 dark:border-slate-800/60 shadow-[0_4px_30px_rgba(0,0,0,0.03)] p-8 rounded-[2.5rem] flex flex-col hover:shadow-md transition-shadow duration-300">
        <div>
            <h3 class="font-extrabold text-slate-800 dark:text-white text-base tracking-tight leading-none">Demografi Warga</h3>
            <p class="text-[10px] text-gray-400 font-semibold mt-2">Distribusi gender, anak, & lansia</p>
        </div>
        <div class="flex items-center justify-between flex-1 gap-4 mt-4">
            <div class="relative w-[110px] h-[110px] shrink-0">
                <canvas id="demografiChart"></canvas>
            </div>
            <div class="flex-1 space-y-2 text-[11px] font-bold">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        <span class="text-slate-500 dark:text-slate-400">Pria</span>
                    </div>
                    <span class="stat-counter text-slate-850 dark:text-white font-black" data-value="{{ $demografi_pria ?? 0 }}" data-type="warga">0 Jiwa</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-pink-500"></span>
                        <span class="text-slate-500 dark:text-slate-400">Wanita</span>
                    </div>
                    <span class="stat-counter text-slate-850 dark:text-white font-black" data-value="{{ $demografi_wanita ?? 0 }}" data-type="warga">0 Jiwa</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-violet-500"></span>
                        <span class="text-slate-500 dark:text-slate-400">Anak-anak</span>
                    </div>
                    <span class="stat-counter text-slate-850 dark:text-white font-black" data-value="{{ $demografi_anak ?? 0 }}" data-type="warga">0 Jiwa</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        <span class="text-slate-500 dark:text-slate-400">Lansia</span>
                    </div>
                    <span class="stat-counter text-slate-850 dark:text-white font-black" data-value="{{ $demografi_lansia ?? 0 }}" data-type="warga">0 Jiwa</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- TRANSAKSI TERBARU --}}
    <div class="bg-white/85 dark:bg-slate-900 border border-slate-200 dark:border-slate-800/60 shadow-[0_4px_30px_rgba(0,0,0,0.03)] p-8 rounded-[2.5rem] hover:shadow-md transition-shadow duration-300">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h3 class="font-extrabold text-slate-800 dark:text-white text-lg tracking-tight leading-none">Transaksi Terbaru</h3>
                <p class="text-xs text-gray-400 font-semibold mt-2">Catatan pengeluaran & pemasukan terakhir</p>
            </div>
            <a href="javascript:void(0)" onclick="switchPage('transaksi', document.querySelector('.menu-link[onclick*=\\\'transaksi\\\']'))" class="text-indigo-600 text-xs font-bold hover:text-indigo-800 tracking-widest uppercase transition-colors">Lihat Semua</a>
        </div>

        <div class="space-y-4">
            @forelse($transaksi_terbaru as $item)
                <div class="flex justify-between items-center pb-4 border-b border-slate-50 dark:border-slate-800/50 last:border-b-0 hover:bg-slate-50/50 dark:hover:bg-slate-950/20 transition-colors rounded-2xl p-3 -mx-3">
                    <div class="flex items-center gap-4 min-w-0">
                        @if($item->jenis == 'pemasukan')
                            <div class="bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 w-11 h-11 rounded-2xl flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-500/20">
                                <i class="fa-solid fa-arrow-down-long text-sm"></i>
                            </div>
                        @else
                            <div class="bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 w-11 h-11 rounded-2xl flex items-center justify-center shrink-0 border border-rose-100 dark:border-rose-500/20">
                                <i class="fa-solid fa-arrow-up-long text-sm"></i>
                            </div>
                        @endif
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-slate-800 dark:text-white truncate leading-tight">{{ $item->keterangan }}</p>
                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-1.5">{{ $item->jenis }} &bull; {{ $item->kategori }}</p>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        @if($item->jenis == 'pemasukan')
                            <p class="text-sm font-black text-emerald-600 tracking-tight">+ Rp {{ number_format($item->nominal, 0, ',', '.') }}</p>
                        @else
                            <p class="text-sm font-black text-rose-600 tracking-tight">- Rp {{ number_format($item->nominal, 0, ',', '.') }}</p>
                        @endif
                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-1.5">{{ date('d M Y', strtotime($item->tanggal)) }}</p>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-400 py-8 italic text-xs">Belum ada transaksi terbaru.</div>
            @endforelse
        </div>
    </div>

    {{-- QUICK ACTIONS --}}
    <div class="bg-white/85 dark:bg-slate-900 border border-slate-200 dark:border-slate-800/60 shadow-[0_4px_30px_rgba(0,0,0,0.03)] p-8 rounded-[2.5rem] flex flex-col hover:shadow-md transition-shadow duration-300">
        <div>
            <h3 class="font-extrabold text-slate-800 dark:text-white text-lg tracking-tight mb-1 leading-none">Aksi Cepat Layanan</h3>
            <p class="text-xs text-gray-400 font-semibold mb-8 mt-2">Jalan pintas ke menu manajemen kas & warga</p>
        </div>
        <div class="grid grid-cols-2 gap-4 flex-1">
            <button onclick="switchPage('pemasukan', document.querySelector('.menu-link[onclick*=\\\'pemasukan\\\']'))" class="bg-slate-50/50 dark:bg-slate-950/20 hover:bg-indigo-50/30 dark:hover:bg-indigo-950/20 border border-slate-200 dark:border-slate-800 hover:border-indigo-300 dark:hover:border-indigo-900 text-slate-800 dark:text-white rounded-3xl font-extrabold flex flex-col items-center justify-center hover:scale-[1.03] transition-all duration-300 py-6 cursor-pointer group">
                <i class="fa-solid fa-circle-plus text-3xl mb-3 text-emerald-500 group-hover:scale-110 transition-transform duration-300"></i>
                <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Catat Pemasukan</span>
            </button>
            <button onclick="switchPage('pengeluaran', document.querySelector('.menu-link[onclick*=\\\'pengeluaran\\\']'))" class="bg-slate-50/50 dark:bg-slate-950/20 hover:bg-indigo-50/30 dark:hover:bg-indigo-950/20 border border-slate-200 dark:border-slate-800 hover:border-indigo-300 dark:hover:border-indigo-900 text-slate-800 dark:text-white rounded-3xl font-extrabold flex flex-col items-center justify-center hover:scale-[1.03] transition-all duration-300 py-6 cursor-pointer group">
                <i class="fa-solid fa-circle-minus text-3xl mb-3 text-rose-500 group-hover:scale-110 transition-transform duration-300"></i>
                <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Catat Pengeluaran</span>
            </button>
            <button onclick="switchPage('data-warga', document.querySelector('.menu-link[onclick*=\\\'data-warga\\\']'))" class="bg-slate-50/50 dark:bg-slate-950/20 hover:bg-indigo-50/30 dark:hover:bg-indigo-950/20 border border-slate-200 dark:border-slate-800 hover:border-indigo-300 dark:hover:border-indigo-900 text-slate-800 dark:text-white rounded-3xl font-extrabold flex flex-col items-center justify-center hover:scale-[1.03] transition-all duration-300 py-6 cursor-pointer group">
                <i class="fa-solid fa-user-plus text-3xl mb-3 text-blue-500 group-hover:scale-110 transition-transform duration-300"></i>
                <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Tambah Warga</span>
            </button>
            <button onclick="switchPage('laporan-keuangan', document.querySelector('.menu-link[onclick*=\\\'laporan-keuangan\\\']'))" class="bg-slate-50/50 dark:bg-slate-950/20 hover:bg-indigo-50/30 dark:hover:bg-indigo-950/20 border border-slate-200 dark:border-slate-800 hover:border-indigo-300 dark:hover:border-indigo-900 text-slate-800 dark:text-white rounded-3xl font-extrabold flex flex-col items-center justify-center hover:scale-[1.03] transition-all duration-300 py-6 cursor-pointer group">
                <i class="fa-solid fa-file-invoice text-3xl mb-3 text-amber-500 group-hover:scale-110 transition-transform duration-300"></i>
                <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Cetak Laporan</span>
            </button>
        </div>
    </div>
</div>

<script>
    // (Using global runGlobalCounterAnimation from super-admin layout instead)

    // --- RENDER DEMOGRAFI DOUGHNUT CHART ---
    window.renderDemografiChart = function() {
        const canvas = document.getElementById('demografiChart');
        if(!canvas) return;

        if(window.demografiChartInstance) {
            window.demografiChartInstance.destroy();
        }

        const ctx = canvas.getContext('2d');
        const isDark = document.documentElement.classList.contains('dark');

        window.demografiChartInstance = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Pria', 'Wanita', 'Anak-anak', 'Lansia'],
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
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: isDark ? '#0f172a' : '#1e293b',
                        padding: 10,
                        cornerRadius: 8,
                        titleFont: { size: 10, weight: 'bold' },
                        bodyFont: { size: 11, weight: 'bold' }
                    }
                }
            }
        });
    };

    // --- RENDER MONTHLY CASH FLOW BAR CHART ---
    window.renderDashboard = function() {
        const canvas = document.getElementById('kasChart');
        if(!canvas) return;

        if(window.kasChartInstance) {
            window.kasChartInstance.destroy();
        }

        const ctx = canvas.getContext('2d');
        const isDark = document.documentElement.classList.contains('dark');
        
        // Buat dynamic vertical gradients untuk Bar Chart
        const gradMasuk = ctx.createLinearGradient(0, 0, 0, 200);
        gradMasuk.addColorStop(0, '#10b981');
        gradMasuk.addColorStop(1, '#059669');

        const gradKeluar = ctx.createLinearGradient(0, 0, 0, 200);
        gradKeluar.addColorStop(0, '#f43f5e');
        gradKeluar.addColorStop(1, '#e11d48');

        window.kasChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [
                    {
                        label: 'Masuk',
                        data: {!! json_encode($chart_pemasukan ?? array_fill(0,12,0)) !!},
                        backgroundColor: gradMasuk,
                        borderRadius: 8,
                        borderWidth: 0,
                        barPercentage: 0.6,
                        categoryPercentage: 0.7
                    },
                    {
                        label: 'Keluar',
                        data: {!! json_encode($chart_pengeluaran ?? array_fill(0,12,0)) !!},
                        backgroundColor: gradKeluar,
                        borderRadius: 8,
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
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: isDark ? '#0f172a' : '#1e293b',
                        titleColor: '#ffffff',
                        bodyColor: '#e2e8f0',
                        padding: 12,
                        cornerRadius: 12,
                        boxPadding: 6,
                        titleFont: { size: 11, weight: 'bold' },
                        bodyFont: { size: 12, weight: 'bold' },
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#94a3b8',
                            font: { size: 10, weight: 'bold' }
                        }
                    },
                    y: {
                        grid: {
                            color: isDark ? 'rgba(255, 255, 255, 0.06)' : 'rgba(226, 232, 240, 0.3)',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#94a3b8',
                            font: { size: 10, weight: 'bold' },
                            callback: function(value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID', { compactDisplay: 'short' }).format(value);
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
            window.renderDashboard();
            window.renderDemografiChart();
        };
        document.head.appendChild(s);
    } else {
        window.renderDashboard();
        window.renderDemografiChart();
    }
</script>
