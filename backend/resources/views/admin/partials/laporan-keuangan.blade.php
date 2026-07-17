<style>
/* CSS overrides for Dark Mode on Laporan Keuangan page */
html.dark .lap-card-masuk {
    background: linear-gradient(to bottom right, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.02)) !important;
    border-color: rgba(16, 185, 129, 0.2) !important;
}
html.dark .lap-card-keluar {
    background: linear-gradient(to bottom right, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.02)) !important;
    border-color: rgba(239, 68, 68, 0.2) !important;
}
html.dark .lap-card-saldo {
    background: linear-gradient(to bottom right, rgba(99, 102, 241, 0.1), rgba(99, 102, 241, 0.02)) !important;
    border-color: rgba(99, 102, 241, 0.2) !important;
}
html.dark .lap-card-masuk h3 { color: #34d399 !important; }
html.dark .lap-card-keluar h3 { color: #f87171 !important; }
html.dark .lap-card-saldo h3 { color: #818cf8 !important; }

html.dark .lap-card-masuk p { color: rgba(52, 211, 153, 0.8) !important; }
html.dark .lap-card-keluar p { color: rgba(248, 113, 113, 0.8) !important; }
html.dark .lap-card-saldo p { color: rgba(129, 140, 248, 0.8) !important; }
</style>

@php
    $totalPemasukan = $list_transaksi->where('jenis', 'pemasukan')->sum('nominal');
    $totalPengeluaran = $list_transaksi->where('jenis', 'pengeluaran')->sum('nominal');
    $saldoBersih = $totalPemasukan - $totalPengeluaran;
@endphp

<div class="p-4 lg:p-8 space-y-6 max-w-[1400px] mx-auto">

    <!-- Hero Banner & Stats Header -->
    <div class="bg-gradient-to-br from-[#1e3a5f] via-[#1a2e4a] to-[#0f172a] rounded-[2.5rem] p-6 lg:p-8 text-white relative overflow-hidden shadow-2xl border border-white/10">
        <div class="absolute top-0 right-0 w-80 h-80 bg-blue-500/10 rounded-full -translate-y-1/2 translate-x-1/3 blur-2xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-60 h-60 bg-emerald-500/10 rounded-full translate-y-1/2 -translate-x-1/4 blur-2xl pointer-events-none"></div>
        <i class="fa-solid fa-file-invoice-dollar absolute -bottom-8 -right-6 text-[150px] opacity-[0.03] rotate-12 pointer-events-none"></i>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 rounded-xl bg-blue-500/20 border border-blue-400/20 flex items-center justify-center">
                        <i class="fa-solid fa-wallet text-blue-300 text-sm"></i>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-[3px] text-blue-300/90">Laporan & Audit Kas</span>
                </div>
                <h1 class="text-2xl lg:text-3xl font-black tracking-tight">Laporan Keuangan RT</h1>
                <p class="text-sm text-blue-200/70 font-medium mt-1">Rekapitulasi seluruh transaksi penerimaan dan pengeluaran kas RT/RW</p>
            </div>

            <!-- Export buttons -->
            <div class="flex items-center gap-2 shrink-0 flex-wrap">
                <a href="{{ route('admin.export', ['tipe' => 'all', 'format' => 'excel']) }}" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-md">
                    <i class="fa-solid fa-file-excel"></i> Export Excel
                </a>
                <a href="{{ route('admin.export', ['tipe' => 'all', 'format' => 'pdf']) }}" target="_blank" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-md">
                    <i class="fa-solid fa-file-pdf"></i> Cetak PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Widgets -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="lap-card-masuk bg-gradient-to-br from-emerald-50 to-white rounded-3xl border border-emerald-100 p-6 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-emerald-600 uppercase tracking-wider mb-1">Total Pemasukan Kas</p>
                <h3 class="stat-counter text-2xl font-black text-emerald-700" data-value="{{ $totalPemasukan }}" data-type="currency">Rp 0</h3>
                <p class="text-[10px] text-emerald-500 font-semibold mt-1">Akumulasi iuran & penerimaan</p>
            </div>
            <div class="w-12 h-12 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl flex items-center justify-center text-emerald-600 text-lg">
                <i class="fa-solid fa-arrow-down-long"></i>
            </div>
        </div>
        <div class="lap-card-keluar bg-gradient-to-br from-red-50 to-white rounded-3xl border border-red-100 p-6 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-red-600 uppercase tracking-wider mb-1">Total Pengeluaran Kas</p>
                <h3 class="stat-counter text-2xl font-black text-red-700" data-value="{{ $totalPengeluaran }}" data-type="currency">Rp 0</h3>
                <p class="text-[10px] text-red-500 font-semibold mt-1">Akumulasi belanja & biaya operasional</p>
            </div>
            <div class="w-12 h-12 bg-red-500/10 border border-red-500/20 rounded-2xl flex items-center justify-center text-red-600 text-lg">
                <i class="fa-solid fa-arrow-up-long"></i>
            </div>
        </div>
        <div class="lap-card-saldo bg-gradient-to-br from-indigo-50 to-white rounded-3xl border border-indigo-100 p-6 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-indigo-600 uppercase tracking-wider mb-1">Saldo Kas Bersih</p>
                <h3 class="stat-counter text-2xl font-black text-indigo-700" data-value="{{ $saldoBersih }}" data-type="currency">Rp 0</h3>
                <p class="text-[10px] text-indigo-500 font-semibold mt-1">Sisa saldo kas aktif</p>
            </div>
            <div class="w-12 h-12 bg-indigo-500/10 border border-indigo-500/20 rounded-2xl flex items-center justify-center text-indigo-600 text-lg">
                <i class="fa-solid fa-vault"></i>
            </div>
        </div>
    </div>

    <!-- Table Finances -->
    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="font-black text-gray-800">Daftar Transaksi Keuangan RT</h3>
                <p class="text-xs text-gray-400 font-medium">Rincian jurnal transaksi iuran dan pengeluaran warga</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-gray-50 text-gray-400 font-extrabold uppercase tracking-wider border-b border-gray-100">
                        <th class="py-3.5 px-6">Tanggal</th>
                        <th class="py-3.5 px-6">Keterangan</th>
                        <th class="py-3.5 px-6">Kategori</th>
                        <th class="py-3.5 px-6">Jenis</th>
                        <th class="py-3.5 px-6 text-right">Nominal (Rp)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 font-medium text-gray-700">
                    @forelse($list_transaksi as $t)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="py-3.5 px-6 whitespace-nowrap">{{ date('d M Y', strtotime($t->tanggal)) }}</td>
                        <td class="py-3.5 px-6 font-bold text-gray-800">{{ $t->keterangan }}</td>
                        <td class="py-3.5 px-6"><span class="bg-gray-100 text-gray-700 font-bold px-2.5 py-1 rounded-lg text-[10px]">{{ $t->kategori }}</span></td>
                        <td class="py-3.5 px-6">
                            <span class="px-2.5 py-1 rounded-full font-bold text-[10px] {{ $t->jenis == 'pemasukan' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                {{ strtoupper($t->jenis) }}
                            </span>
                        </td>
                        <td class="py-3.5 px-6 text-right font-black {{ $t->jenis == 'pemasukan' ? 'text-emerald-600' : 'text-red-600' }}">
                            {{ $t->jenis == 'pemasukan' ? '+' : '-' }} Rp {{ number_format($t->nominal, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-400 italic">Belum ada transaksi keuangan tercatat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
